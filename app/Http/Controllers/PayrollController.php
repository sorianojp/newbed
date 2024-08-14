<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ContributionSetting;
use App\Models\Employee;
use App\Models\Pagibig;
use App\Models\PayrollSchedule;
use App\Models\PaySlip;
use App\Models\Philhealth;
use App\Models\RegularSchedule;
use App\Models\SSS;
use App\Models\Tax;
use App\Models\TeachingAttendance;
use App\Models\TeachingSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{

    public $contributionsSettings;

    public function __construct()
    {
        $contributionSettings = ContributionSetting::all();
    }

    public function index()
    {
        //
    }

    public function showIndividual()
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
        ];

        return view('payroll.computations.individual', compact('months'));
    }

    public function store(Request $request)
    {
        //
    }

    public function computePayroll(Request $request): JsonResponse
    {
        $employee = Employee::find($request->employee_id);
        $payrollSchedule = PayrollSchedule::findOrFail($request->schedule_id);

        // return $this->calcOld($employeeId);
        $compensations = $this->calculatePayroll($employee, $payrollSchedule->cutoff_start_date, $payrollSchedule->cutoff_end_date, $payrollSchedule);

        return response()->json([
            'compensations' => $compensations,
            'employee' => $employee->load('employeeSetting'),
        ]);

    }

    private function calculateTardiness($time, $scheduledTime, $repeated = false, $undertime = false)
    {
        if ((!$undertime && $time <= $scheduledTime) || ($undertime && $time >= $scheduledTime) || $repeated) {
            return 0;
        }

        $latenessMinutes = $undertime ? $time->diffInMinutes($scheduledTime) : $scheduledTime->diffInMinutes($time);

        if ($latenessMinutes <= 15) {
            $latenessHours = 0;
        } elseif ($latenessMinutes <= 60) {
            $latenessHours = 1;
        } else {
            $latenessHours = ceil($latenessMinutes / 60);
        }

        return $latenessHours;
    }

    private function calculatePayroll($employee, $first_date = '', $last_date = '', $payrollSchedule)
    {
        // Set the date range
        $employeeId = $employee->id;
        $first_date = Carbon::parse($first_date);
        $last_date = Carbon::parse($last_date); // Adjusted end date for testing

        // Define pay rates
        $basicRate = $employee->employeeSetting->monthly_basic_salary;
        $teachingRate = $employee->employeeSetting->teaching_rate;
        $totalWorkingDays = $employee->employeeSetting->cut_off_days_per_month; // Or 26, depending on the settings

        // Calculate the hourly rate based on basicRate
        $dailyHours = 8;
        $staffHourlyRate = $basicRate / $totalWorkingDays / $dailyHours;

        // Get attendance records between the specified dates
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$first_date->format('Y-m-d'), $last_date->format('Y-m-d')])
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->groupBy('date');

        // Get staff schedules and group by day of week
        $staffSchedules = RegularSchedule::where('employee_id', $employeeId)
            ->where(function ($query) use ($first_date, $last_date) {
                $query->where(function ($query) use ($first_date, $last_date) {
                    $query->where('start_date', '<=', $last_date->format('Y-m-d'))
                        ->where(function ($query) use ($first_date) {
                            $query->whereNull('end_date')
                                ->orWhere('end_date', '>=', $first_date->format('Y-m-d'));
                        });
                });
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        // Get teaching schedules within the date range and group by day of week
        $teachingSchedules = TeachingSchedule::where('employee_id', $employeeId)
            ->where(function ($query) use ($first_date, $last_date) {
                $query->whereBetween('start_date', [$first_date->format('Y-m-d'), $last_date->format('Y-m-d')])
                    ->orWhereBetween('end_date', [$first_date->format('Y-m-d'), $last_date->format('Y-m-d')])
                    ->orWhere(function ($query) use ($first_date, $last_date) {
                        $query->where('start_date', '<=', $last_date->format('Y-m-d'))
                            ->where('end_date', '>=', $first_date->format('Y-m-d'));
                    });
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        // Get teaching attendances
        $teachingAttendances = TeachingAttendance::whereHas('teachingSchedule', function ($query) use ($employeeId) {
            $query->where('employee_id', $employeeId);
        })
            ->whereBetween('date', [$first_date->format('Y-m-d'), $last_date->format('Y-m-d')])
            ->get()
            ->keyBy(function ($item) {
                return $item['teaching_schedule_id'] . '_' . $item['date'];
            });

        $overtimes = $employee->load('overtimes.overtimeType')->overtimes()->where(function ($query) use ($first_date, $last_date) {
            $query->whereBetween('date', [$first_date->format('Y-m-d'), $last_date->format('Y-m-d')]);
        })->orderBy('date')->get()->groupBy('date');

        $totalStaffHours = 0;
        $totalTeachingHours = 0;
        $totalExpectedStaffHours = 0;
        $totalExpectedTeachingHours = 0;
        // $totalLatenessMinutesStaff = 0;
        // $totalLatenessMinutesTeaching = 0;
        $totalLatenessHoursStaff = 0;
        $totalLatenessHoursTeaching = 0;
        $totalUndertimeHoursStaff = 0;
        $totalUndertimeHoursTeaching = 0;
        $totalAbsentDays = 0;
        $totalAbsentHoursStaff = 0;
        $totalAbsentHoursTeaching = 0;

        $totalOvertimeHours = collect();

        $dailySummaries = [];

        // Find the earliest start date among the schedules
        $earliestStartDate = $staffSchedules->flatten()->min('start_date') ?? $teachingSchedules->flatten()->min('start_date');
        $startDate = Carbon::parse($earliestStartDate)->startOfDay()->greaterThan($first_date) ? Carbon::parse($earliestStartDate) : $first_date;
        $dailySummaries[] = [
            $earliestStartDate,
        ];
        // Create a collection of all dates in the range
        $dateRange = collect();
        for ($date = $startDate->copy(); $date->lte($last_date); $date->addDay()) {

            $dateRange->push($date->copy());
        }

        foreach ($dateRange as $date) {

            $staffStartTime = null;
            $staffEndTime = null;
            $dayOfWeek = $date->format('l');
            $dateStr = $date->format('Y-m-d');
            $dailyExpectedStaffHours = 0;
            $dailyActualStaffHours = 0;
            $dailyExpectedTeachingHours = 0;
            $dailyActualTeachingHours = 0;
            // $dailyLatenessMinutesStaff = 0;
            // $dailyLatenessMinutesTeaching = 0;
            $dailyLatenessHoursStaff = 0;
            $dailyLatenessHoursTeaching = 0;
            $dailyUndertimeHoursStaff = 0;
            $dailyUndertimeHoursTeaching = 0;
            $dailyAbsentHoursStaff = 0;
            $dailyAbsentHoursTeaching = 0;
            $dailyOvertimeHours = collect();

            $times = $attendances->get($dateStr, collect());

            // Assume the first item is time-in and the last item is time-out
            $timeIn = $times->isNotEmpty() ? Carbon::createFromFormat('Y-m-d H:i:s', $dateStr . ' ' . $times->first()->time, 'Asia/Manila') : null;
            $timeOut = $times->isNotEmpty() ? Carbon::createFromFormat('Y-m-d H:i:s', $dateStr . ' ' . $times->last()->time, 'Asia/Manila') : null;

            // Track absence for this day
            $isAbsentStaff = false;
            $isAbsentTeaching = false;

            if (!$staffSchedules->has($dayOfWeek) && !$teachingSchedules->has($dayOfWeek)) {
                continue;
            }

            if ($timeIn && $timeOut) {
                $timeInTime = Carbon::createFromTime($timeIn->hour, $timeIn->minute, $timeIn->second);
                $timeOutTime = Carbon::createFromTime($timeOut->hour, $timeOut->minute, $timeOut->second);
            }

            $hasStaffSchedule = $staffSchedules->has($dayOfWeek);
            $hasTeachingSchedule = $teachingSchedules->has($dayOfWeek);

            // Process staff schedules
            if ($hasStaffSchedule) {

                foreach ($staffSchedules[$dayOfWeek] as $staffSchedule) {

                    // Calculate expected hours from staff schedule
                    $scheduledStartTime = Carbon::createFromFormat('H:i:s', $staffSchedule->start_time, 'Asia/Manila');
                    $scheduledEndTime = Carbon::createFromFormat('H:i:s', $staffSchedule->end_time, 'Asia/Manila');

                    $staffStartTime = $scheduledStartTime;
                    $staffEndTime = $scheduledEndTime;

                    $latenessHours = 0;
                    $undertimeHours = 0;

                    if ($scheduledEndTime < $scheduledStartTime) {
                        $scheduledEndTime->addDay(); // Handle overnight shifts
                    }
                    $expectedHours = $scheduledStartTime->diffInMinutes($scheduledEndTime);
                    $dailyExpectedStaffHours += $expectedHours / 60; // Convert minutes to hours

                    // Calculate actual hours from attendance
                    if ($timeIn && $timeOut && $timeOut > $timeIn) {
                        // Extract time components for comparison

                        $scheduledStartTimeTime = Carbon::createFromTime($scheduledStartTime->hour, $scheduledStartTime->minute, $scheduledStartTime->second);
                        $scheduledEndTimeTime = Carbon::createFromTime($scheduledEndTime->hour, $scheduledEndTime->minute, $scheduledEndTime->second);

                        $latenessHours = $this->calculateTardiness($timeInTime, $scheduledStartTimeTime);
                        $undertimeHours = $this->calculateTardiness($timeOutTime, $scheduledEndTimeTime, false, true);

                        $dailyLatenessHoursStaff += $latenessHours;
                        $dailyUndertimeHoursStaff += $undertimeHours;

                        $dailyActualStaffHours += ($expectedHours / 60) - ($latenessHours + $undertimeHours);
                    }

                    // Calculate absent days for staff schedule
                    if ($dailyActualStaffHours == 0 && $dailyExpectedStaffHours > 0) {
                        $isAbsentStaff = true;
                        $dailyAbsentHoursStaff += $expectedHours / 60;
                    }
                }
            }

            // Process teaching schedules
            if ($hasTeachingSchedule) {
                foreach ($teachingSchedules[$dayOfWeek] as $teachingSchedule) {
                    // Check if the date falls within the schedule range
                    $start_date = Carbon::parse($teachingSchedule->start_date);
                    $end_date = Carbon::parse($teachingSchedule->end_date);

                    $latenessHours = 0;
                    $undertimeHours = 0;

                    if ($date->between($start_date, $end_date)) {
                        // Calculate expected hours from teaching schedule
                        $scheduledStartTime = Carbon::createFromFormat('H:i:s', $teachingSchedule->start_time, 'Asia/Manila');
                        $scheduledEndTime = Carbon::createFromFormat('H:i:s', $teachingSchedule->end_time, 'Asia/Manila');
                        if ($scheduledEndTime < $scheduledStartTime) {
                            $scheduledEndTime->addDay(); // Handle overnight shifts
                        }
                        $expectedHours = $scheduledStartTime->diffInMinutes($scheduledEndTime);
                        $dailyExpectedTeachingHours += ($expectedHours / 60) * $teachingSchedule->weight; // Convert minutes to hours

                        $latenessHours = $this->calculateTardiness($timeInTime, $scheduledStartTimeTime, $hasStaffSchedule && $scheduledStartTime == $staffStartTime);
                        $undertimeHours = $this->calculateTardiness($timeOutTime, $scheduledEndTimeTime, $hasStaffSchedule && $scheduledEndTime == $staffEndTime, true);

                        $dailyLatenessHoursTeaching += $latenessHours;
                        $dailyUndertimeHoursTeaching += $undertimeHours;

                        // Check if the employee was teaching
                        $attendanceKey = $teachingSchedule->id . '_' . $dateStr;
                        $attendance = $teachingAttendances->get($attendanceKey);

                        if ($attendance) {
                            if ($attendance->status == 'Present') {
                                // If present, use the schedule's start and end time for teaching hours
                                $dailyActualTeachingHours += ($expectedHours / 60) * $teachingSchedule->weight;
                            } else {
                                // Calculate lateness
                                if ($attendance->status == 'Late') {
                                    $dailyActualTeachingHours += ($expectedHours / 60) * $teachingSchedule->weight;
                                }

                                if ($attendance->status != 'Absent') {
                                    $dailyAbsentHoursTeaching += ($expectedHours / 60) * $teachingSchedule->weight;
                                }
                            }
                        } else {
                            $dailyAbsentHoursTeaching += ($expectedHours / 60) * $teachingSchedule->weight;
                        }

                    }
                    // Calculate absent days for teaching schedule
                    if ($dailyActualTeachingHours == 0 && $dailyExpectedTeachingHours > 0) {
                        $isAbsentTeaching = true;
                    }
                }
            }

            // Calculate total overtime hours
            if ($overtimes->has($dateStr)) {
                foreach ($overtimes[$dateStr] as $overtime) {
                    $overtimeStartTime = Carbon::createFromFormat('H:i:s', $overtime->start_time, 'Asia/Manila');
                    $overtimeEndTime = Carbon::createFromFormat('H:i:s', $overtime->end_time, 'Asia/Manila');
                    if ($overtimeEndTime < $overtimeStartTime) {
                        $overtimeEndTime->addDay(); // Handle overnight shifts
                    }
                    if ($timeIn && $timeOut && $timeOutTime >= $overtimeStartTime) {
                        $overtimeHours = $overtimeStartTime->diffInMinutes($timeOutTime > $overtimeEndTime ? $overtimeEndTime : $timeOutTime) / 60;
                        $dailyOvertimeHours->push([
                            'hours' => $overtimeHours,
                            'rate' => $overtime->overtimeType->rate,
                        ]);
                    }
                    // $dailyOvertimeHours += $overtimeStartTime->diffInMinutes($overtimeEndTime) / 60;
                }
            }

            // Count a full absent day only once
            if ($isAbsentStaff || (!$hasStaffSchedule && $isAbsentTeaching)) {
                $totalAbsentDays++;
            }

            $dailySummaries[] = [
                'dailyUndertimeTeaching' => $dailyUndertimeHoursTeaching,
                'dailyUndertimeStaff' => $dailyUndertimeHoursStaff,
                'daily' => $dayOfWeek,
                'dailyExpectedTeachingHours' => $dailyExpectedTeachingHours,
                'isAbsentStaff' => $isAbsentStaff,
                'isAbsentTeaching' => $isAbsentTeaching,
            ];

            $totalExpectedStaffHours += $dailyExpectedStaffHours;
            $totalStaffHours += ($dailyExpectedStaffHours - $dailyLatenessHoursStaff - $dailyUndertimeHoursStaff - $dailyAbsentHoursStaff);
            $totalAbsentHoursStaff += $dailyAbsentHoursStaff;
            $totalExpectedTeachingHours += $dailyExpectedTeachingHours;
            $totalTeachingHours += $dailyActualTeachingHours;
            $totalLatenessHoursStaff += $dailyLatenessHoursStaff;
            $totalLatenessHoursTeaching += $dailyLatenessHoursTeaching;
            $totalUndertimeHoursStaff += $dailyUndertimeHoursStaff;
            $totalUndertimeHoursTeaching += $dailyUndertimeHoursTeaching;
            $totalOvertimeHours = $totalOvertimeHours->merge($dailyOvertimeHours);

        }

        // Calculate payroll
        $expectedStaffPay = $totalExpectedStaffHours * $staffHourlyRate;
        $absentDeductionStaff = ($totalAbsentHoursStaff * $staffHourlyRate);
        $lateDeductionStaff = (($totalLatenessHoursStaff + $totalUndertimeHoursStaff) * $staffHourlyRate);
        $staffPay = $expectedStaffPay - ($absentDeductionStaff + $lateDeductionStaff);

        $expectedTeachingPay = $totalExpectedTeachingHours * $teachingRate;
        $absentDeductionTeaching = $totalAbsentHoursTeaching * $teachingRate;
        $lateDeductionTeaching = (($totalLatenessHoursTeaching + $totalUndertimeHoursTeaching) * $teachingRate);
        $teachingPay = $expectedTeachingPay - ($absentDeductionTeaching + $lateDeductionTeaching);

        $absentDeduction = $absentDeductionStaff + $absentDeductionTeaching;
        $lateDeduction = $lateDeductionStaff + $lateDeductionTeaching;

        $totalLatenessHours = $totalLatenessHoursStaff + $totalLatenessHoursTeaching + $totalUndertimeHoursStaff + $totalUndertimeHoursTeaching;
        $totalAbsentHours = $totalAbsentHoursStaff + $totalAbsentHoursTeaching;

        $overtimePay = $totalOvertimeHours->map(function ($overtimeHours) use ($staffHourlyRate) {
            return $overtimeHours['hours'] * ($overtimeHours['rate'] * $staffHourlyRate);
        })->sum();

        // Inclusion of additionals to contributions
        $additionals = $employee->additionals()
            ->schedule($payrollSchedule->id)
            ->get()
            ->groupBy('additional.included');

        $includedAdditionals = $additionals->get(true, collect());
        $notIncludedAdditionals = $additionals->get(false, collect());

        // Deduction from not-taxable gross
        $deductions = $employee->deductions()
            ->schedule($payrollSchedule->id)
            ->get()
            ->groupBy('deduction.deducted');

        $taxableDeductions = $deductions->get(true, collect());

        $grossSalary = $staffPay + $teachingPay + $overtimePay + $includedAdditionals->sum('amount') - $taxableDeductions->sum('amount');

        $contributions = $this->getContributions($employee, $payrollSchedule, $grossSalary);

        $nonTaxableDeductions = collect($contributions['contributions'])->merge($deductions->get(false, collect())->map(function ($deduction) {
            return [
                'name' => $deduction->deduction->name,
                'amount' => $deduction->amount,
            ];
        }));

        // Include deductions here
        $netPay = $grossSalary + $notIncludedAdditionals->sum('amount') - $nonTaxableDeductions->sum('amount');

        return [
            'daily_summaries' => $dailySummaries,
            'basic_rate' => $basicRate,
            'teaching_rate' => $teachingRate,
            'total_expected_staff_hours' => $totalExpectedStaffHours,
            'total_expected_teaching_hours' => $totalExpectedTeachingHours,
            'total_staff_hours' => $totalStaffHours,
            'total_teaching_hours' => $totalTeachingHours,
            'lateness_hours_staff' => $totalLatenessHoursStaff,
            'lateness_hours_teaching' => $totalLatenessHoursTeaching,
            'undertime_hours_staff' => $totalUndertimeHoursStaff,
            'undertime_hours_teaching' => $totalUndertimeHoursTeaching,
            'expected_staff_pay' => $expectedStaffPay,
            'expected_teaching_pay' => $expectedTeachingPay,
            'total_tardiness' => $totalLatenessHours,
            'total_absent_hours' => $totalAbsentHours,
            'absent_deduction' => $absentDeduction,
            'late_deduction' => $lateDeduction,
            'staff_pay' => $staffPay,
            'teaching_pay' => $teachingPay,
            'gross_salary' => $grossSalary,
            'absent_days' => $totalAbsentDays,
            'absent_hours_staff' => $totalAbsentHoursStaff,
            'absent_hours_teaching' => $totalAbsentHoursTeaching,
            'net_pay' => $netPay,
            'overtimes' => $totalOvertimeHours,
            'overtime_hours' => $totalOvertimeHours->sum('hours'),
            'overtime_pay' => $overtimePay,
            'included_additionals' => $includedAdditionals,
            'not_included_additionals' => $notIncludedAdditionals,
            'taxable_deductions' => $taxableDeductions,
            'non_taxable_deductions' => $nonTaxableDeductions,
            'wTax' => $contributions['wTax'],
            'sss' => $contributions['sss'],
            'pagibig' => $contributions['pagibig'],
            'philhealth' => $contributions['philhealth'],
            'holiday_hours' => 0,
            'holiday_pay' => 0,
            'monthly_gross_salary' => $monthlyGrossSalary ?? 0,
        ];
    }

    private function getPreviousPaySlip($employee, $payrollSchedule)
    {
        $previousPaySlip = PaySlip::where('employee_id', $employee->id)->whereHas('payrollSchedule', function ($query) use ($payrollSchedule) {
            $query->where('month', $payrollSchedule->month)->where('year', $payrollSchedule->year)->where('period', '15th/1st');
        })->first();

        return $previousPaySlip->gross_salary ?? 0;
    }

    private function getContributions($employee, $payrollSchedule, $grossSalary)
    {
        $contributions = collect();

        $prev = $this->getPreviousPaySlip($employee, $payrollSchedule);
        $monthlyGrossSalary = $prev + $grossSalary;

        $tax = Tax::whereDate('effective_date', '<', $payrollSchedule->pay_date)->first();

        $bracket = $tax->taxBrackets()->where(function ($query) use ($grossSalary) {
            $query->where(function ($q) use ($grossSalary) {
                $q->where('end_range', '>=', $grossSalary)
                    ->orWhereNull('end_range');
            })->where('start_range', '<=', $grossSalary);
        })->orderBy('start_range', 'desc')->first();

        $withHoldingTax = max(0, ($bracket->fixed_amount + (($grossSalary - $bracket->start_range) * ($bracket->excess_percentage / 100))));

        $contributions = $contributions->merge([
            [
                'name' => 'Witholding Tax',
                'amount' => $withHoldingTax,
            ],
        ]);

        if ($payrollSchedule->period == '30th/2nd') {

            if ($employee->employeeSetting->sss) {
                $sss = SSS::with(['sssBrackets'])->whereDate('effective_date', '<', $payrollSchedule->pay_date)->first();
                $sssBracket = $sss->sssBrackets()->where(function ($query) use ($monthlyGrossSalary) {
                    $query->where(function ($q) use ($monthlyGrossSalary) {
                        $q->where('end_range', '>=', $monthlyGrossSalary)
                            ->orWhereNull('end_range');
                    })->where('start_range', '<=', $monthlyGrossSalary);
                })->orderBy('start_range', 'desc')->first();

                $contributions = $contributions->merge([
                    [
                        'name' => 'SSS',
                        'amount' => $sssBracket->ee,
                    ],
                ]);
            }

            if ($employee->employeeSetting->phil_health) {
                $philhealth = Philhealth::where('start_range', '<=', $monthlyGrossSalary)->where('end_range', '>=', $monthlyGrossSalary)->first();
                $phamount = $philhealth->percentage ? ($philhealth->percentage / 100) * $monthlyGrossSalary : $philhealth->employee_share;

                $contributions = $contributions->merge([
                    [
                        'name' => 'Philhealth',
                        'amount' => $phamount,
                    ],
                ]);
            }

            if ($employee->employeeSetting->pag_ibig) {
                $pagibig = Pagibig::latest()->first();
                $pbamount = ($pagibig->percentage / 100) * $monthlyGrossSalary;

                $contributions = $contributions->merge([
                    [
                        'name' => 'Pagibig',
                        'amount' => $pbamount,
                    ],
                ]);
            }
        }

        return [
            'contributions' => $contributions,
            'wTax' => $withHoldingTax ?? 0,
            'sss' => $sssBracket->ee ?? 0,
            'philhealth' => $phamount ?? 0,
            'pagibig' => $pbamount ?? 0,
        ];
    }

    public function createPayslip(Request $request): JsonResponse
    {
        // return response()->json($request->all());
        try {
            DB::beginTransaction();
            $validatedData = $request->validate([
                'payroll_schedule_id' => ['required'],
                'employee_id' => ['required'],
                'basic_rate' => ['required'],
                'teaching_rate' => ['required'],
                'total_hours' => ['nullable'],
                'working_hours' => ['nullable'],
                'teaching_hours' => ['nullable'],
                'total_teaching_hours' => ['nullable'],
                'absent_days' => ['nullable'],
                'absent_amount' => ['nullable'],
                'tardiness_hours' => ['nullable'],
                'tardiness_amount' => ['nullable'],
                'overtime_hours' => ['nullable'],
                'overtime_amount' => ['nullable'],
                'holiday_hours' => ['nullable'],
                'holiday_amount' => ['nullable'],
                'gross_salary' => ['nullable'],
                'included_additionals' => ['nullable'],
                'not_included_additionals' => ['nullable'],
                'taxable_deductions' => ['nullable'],
                'not_taxable_deductions' => ['nullable'],
                'wtax' => ['nullable'],
                'sss' => ['nullable'],
                'philhealth' => ['nullable'],
                'pagibig' => ['nullable'],
                'net_salary' => ['nullable'],
            ]);

            PaySlip::updateOrCreate(
                collect($validatedData)->only(['payroll_schedule_id', 'employee_id'])->toArray(),
                collect($validatedData)->except(['payroll_schedule_id', 'employee_id'])->toArray(),
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['errors' => $e->getMessage()], 500);
        }

        return response()->json([
            'message' => 'Successful',
        ]);
    }

    public function calcOld($employeeId)
    {
        // Set the date range
        $first_date = Carbon::createFromDate(2024, 6, 1, 'Asia/Manila');
        $last_date = Carbon::createFromDate(2024, 6, 15, 'Asia/Manila'); // Adjusted end date for testing

        // Define pay rates
        $hourlyPay = 120;
        $monthlyPay = 23000;
        $totalWorkingDays = 22;

        // Get attendance records between the specified dates
        $attendances = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$first_date->format('Y-m-d'), $last_date->format('Y-m-d')])
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        // Get staff schedules
        $staffSchedules = RegularSchedule::where('employee_id', $employeeId)
            ->where(function ($query) use ($first_date, $last_date) {
                $query->where(function ($query) use ($first_date, $last_date) {
                    $query->where('start_date', '<=', $last_date->format('Y-m-d'))
                        ->where(function ($query) use ($first_date) {
                            $query->whereNull('end_date')
                                ->orWhere('end_date', '>=', $first_date->format('Y-m-d'));
                        });
                });
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        // Get teaching schedules within the date range
        $teachingSchedules = TeachingSchedule::where('employee_id', $employeeId)
            ->where(function ($query) use ($first_date, $last_date) {
                $query->whereBetween('start_date', [$first_date->format('Y-m-d'), $last_date->format('Y-m-d')])
                    ->orWhereBetween('end_date', [$first_date->format('Y-m-d'), $last_date->format('Y-m-d')])
                    ->orWhere(function ($query) use ($first_date, $last_date) {
                        $query->where('start_date', '<=', $last_date->format('Y-m-d'))
                            ->where('end_date', '>=', $first_date->format('Y-m-d'));
                    });
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $totalStaffHours = 0;
        $totalTeachingHours = 0;
        $totalExpectedStaffHours = 0;
        $totalExpectedTeachingHours = 0;
        $totalLatenessMinutesStaff = 0;
        $totalLatenessMinutesTeaching = 0;
        $totalUndertimeMinutesStaff = 0;
        $totalUndertimeMinutesTeaching = 0;
        $workingDaysCount = 0;
        $dailySummaries = [];

        // Create a collection of all dates in the range
        $dateRange = collect();
        for ($date = $first_date->copy(); $date->lte($last_date); $date->addDay()) {
            $dateRange->push($date->copy());
        }

        foreach ($dateRange as $date) {
            $dayOfWeek = $date->format('l'); // Get day of the week in full text (e.g., 'Monday')

            // Get attendances for the current date
            $times = $attendances->where('date', $date->format('Y-m-d'));

            // Assume the first item is time-in and the last item is time-out
            $timeIn = $times->isNotEmpty() ? Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $times->first()->time, 'Asia/Manila') : null;
            $timeOut = $times->isNotEmpty() ? Carbon::createFromFormat('Y-m-d H:i:s', $date->format('Y-m-d') . ' ' . $times->last()->time, 'Asia/Manila') : null;

            $dailyExpectedStaffHours = 0;
            $dailyActualStaffHours = 0;
            $dailyExpectedTeachingHours = 0;
            $dailyActualTeachingHours = 0;
            $dailyLatenessMinutesStaff = 0;
            $dailyLatenessMinutesTeaching = 0;
            $dailyUndertimeMinutesStaff = 0;
            $dailyUndertimeMinutesTeaching = 0;

            // Find matching staff schedules for the day
            $matchingStaffSchedules = $staffSchedules->filter(function ($schedule) use ($dayOfWeek) {
                return $schedule->day_of_week === $dayOfWeek;
            });

            if ($matchingStaffSchedules) {
                $workingDaysCount++; // Increment working days count for each schedule
            }

            foreach ($matchingStaffSchedules as $staffSchedule) {
                // Calculate expected hours from staff schedule
                $scheduledStartTime = Carbon::createFromFormat('H:i:s', $staffSchedule->start_time, 'Asia/Manila');
                $scheduledEndTime = Carbon::createFromFormat('H:i:s', $staffSchedule->end_time, 'Asia/Manila');
                if ($scheduledEndTime < $scheduledStartTime) {
                    $scheduledEndTime->addDay(); // Handle overnight shifts
                }
                $expectedHours = $scheduledStartTime->diffInMinutes($scheduledEndTime);
                $dailyExpectedStaffHours += $expectedHours / 60; // Convert minutes to hours

                // Calculate actual hours from attendance
                if ($timeIn && $timeOut && $timeOut > $timeIn) {
                    // Extract time components for comparison
                    $timeInTime = Carbon::createFromTime($timeIn->hour, $timeIn->minute, $timeIn->second);
                    $timeOutTime = Carbon::createFromTime($timeOut->hour, $timeOut->minute, $timeOut->second);
                    $scheduledStartTimeTime = Carbon::createFromTime($scheduledStartTime->hour, $scheduledStartTime->minute, $scheduledStartTime->second);
                    $scheduledEndTimeTime = Carbon::createFromTime($scheduledEndTime->hour, $scheduledEndTime->minute, $scheduledEndTime->second);

                    // Calculate lateness
                    if ($timeInTime > $scheduledStartTimeTime) {
                        $latenessMinutes = $scheduledStartTimeTime->diffInMinutes($timeInTime);
                        $dailyLatenessMinutesStaff += $latenessMinutes;
                    }

                    // Calculate presence duration
                    if ($timeInTime < $scheduledStartTimeTime) {
                        // Time in is earlier than scheduled start time
                        $presenceDuration = $scheduledStartTime->diffInMinutes($scheduledEndTime);
                    } else {
                        // Time in is on or after scheduled start time
                        $presenceDuration = $timeInTime->diffInMinutes($scheduledEndTimeTime);
                    }

                    // Decrease presenceDuration by undertime if applicable
                    if ($timeOutTime < $scheduledEndTimeTime) {
                        $undertimeMinutes = $timeOutTime->diffInMinutes($scheduledEndTimeTime);
                        $dailyUndertimeMinutesStaff += $undertimeMinutes;
                        $presenceDuration -= $undertimeMinutes;
                    }

                    if ($presenceDuration > $expectedHours) {
                        $presenceDuration = $expectedHours; // Ensure actual hours do not exceed expected hours
                    }
                    if ($presenceDuration > 0) {
                        $dailyActualStaffHours += $presenceDuration / 60; // Convert minutes to hours
                    }
                }
            }

            // Find matching teaching schedules for the day
            $matchingTeachingSchedules = $teachingSchedules->filter(function ($schedule) use ($dayOfWeek, $date) {
                return $schedule->day_of_week === $dayOfWeek &&
                $date->between(Carbon::parse($schedule->start_date), Carbon::parse($schedule->end_date));
            });

            foreach ($matchingTeachingSchedules as $teachingSchedule) {
                // Calculate teaching schedule presence hours
                $scheduledStartTime = Carbon::createFromFormat('H:i:s', $teachingSchedule->start_time, 'Asia/Manila');
                $scheduledEndTime = Carbon::createFromFormat('H:i:s', $teachingSchedule->end_time, 'Asia/Manila');
                if ($scheduledEndTime < $scheduledStartTime) {
                    $scheduledEndTime->addDay(); // Handle overnight shifts
                }
                $expectedHours = $scheduledStartTime->diffInMinutes($scheduledEndTime);
                $dailyExpectedTeachingHours += ($expectedHours / 60) * $teachingSchedule->weight; // Convert minutes to hours

                // Calculate actual hours from attendance
                if ($timeIn && $timeOut && $timeOut > $timeIn) {
                    // Extract time components for comparison
                    $timeInTime = Carbon::createFromTime($timeIn->hour, $timeIn->minute, $timeIn->second);
                    $timeOutTime = Carbon::createFromTime($timeOut->hour, $timeOut->minute, $timeOut->second);
                    $scheduledStartTimeTime = Carbon::createFromTime($scheduledStartTime->hour, $scheduledStartTime->minute, $scheduledStartTime->second);
                    $scheduledEndTimeTime = Carbon::createFromTime($scheduledEndTime->hour, $scheduledEndTime->minute, $scheduledEndTime->second);

                    // Calculate lateness
                    if ($timeInTime > $scheduledStartTimeTime) {
                        $latenessMinutes = $scheduledStartTimeTime->diffInMinutes($timeInTime);
                        $dailyLatenessMinutesTeaching += $latenessMinutes;
                    }

                    // Calculate presence duration
                    if ($timeInTime < $scheduledStartTimeTime) {
                        // Time in is earlier than scheduled start time
                        $presenceDuration = $scheduledStartTime->diffInMinutes($scheduledEndTime);
                    } else {
                        // Time in is on or after scheduled start time
                        $presenceDuration = $timeInTime->diffInMinutes($scheduledEndTimeTime);
                    }

                    // Decrease presenceDuration by undertime if applicable
                    if ($timeOutTime < $scheduledEndTimeTime) {
                        $undertimeMinutes = $timeOutTime->diffInMinutes($scheduledEndTimeTime);
                        $dailyUndertimeMinutesTeaching += $undertimeMinutes;
                        $presenceDuration -= $undertimeMinutes;
                    }

                    if ($presenceDuration > 0) {
                        $dailyActualTeachingHours += ($presenceDuration / 60) * $teachingSchedule->weight; // Convert minutes to hours with weight
                    }
                }
            }

            $totalExpectedStaffHours += $dailyExpectedStaffHours;
            $totalStaffHours += $dailyActualStaffHours;
            $totalExpectedTeachingHours += $dailyExpectedTeachingHours;
            $totalTeachingHours += $dailyActualTeachingHours;
            $totalLatenessMinutesStaff += $dailyLatenessMinutesStaff;
            $totalLatenessMinutesTeaching += $dailyLatenessMinutesTeaching;
            $totalUndertimeMinutesStaff += $dailyUndertimeMinutesStaff;
            $totalUndertimeMinutesTeaching += $dailyUndertimeMinutesTeaching;

            $dailySummaries[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $dayOfWeek,
                'absent' => ($dailyActualStaffHours + $dailyActualTeachingHours) == 0 ? true : false,
                'late_minutes_staff' => $dailyLatenessMinutesStaff,
                'late_minutes_teaching' => $dailyLatenessMinutesTeaching,
                'undertime_minutes_staff' => $dailyUndertimeMinutesStaff,
                'undertime_minutes_teaching' => $dailyUndertimeMinutesTeaching,
                'expected_hours_staff' => $dailyExpectedStaffHours,
                'actual_hours_staff' => $dailyActualStaffHours,
                'expected_hours_teaching' => $dailyExpectedTeachingHours,
                'actual_hours_teaching' => $dailyActualTeachingHours,
            ];
        }

        // Calculate payroll
        $absentOrLateHours = max($totalExpectedStaffHours - $totalStaffHours, 0);
        $proportionalMonthlyPay = $monthlyPay * ($workingDaysCount / $totalWorkingDays); // Adjust the monthly pay proportionally
        $staffPay = $proportionalMonthlyPay - ($absentOrLateHours * ($proportionalMonthlyPay / $totalExpectedStaffHours)); // Adjust pay for lateness/absence
        $teachingPay = $totalTeachingHours * $hourlyPay;
        $totalPayroll = $staffPay + $teachingPay;

        return response()->json([
            'workingDaysCount' => $workingDaysCount,
            'total_expected_staff_hours' => number_format($totalExpectedStaffHours, 2),
            'total_expected_teaching_hours' => number_format($totalExpectedTeachingHours, 2),
            'total_staff_hours' => number_format($totalStaffHours, 2),
            'total_teaching_hours' => number_format($totalTeachingHours, 2),
            'absent_or_late_hours' => number_format($absentOrLateHours, 2),
            'lateness_minutes_staff' => number_format($totalLatenessMinutesStaff, 2),
            'lateness_minutes_teaching' => number_format($totalLatenessMinutesTeaching, 2),
            'undertime_minutes_staff' => number_format($totalUndertimeMinutesStaff, 2),
            'undertime_minutes_teaching' => number_format($totalUndertimeMinutesTeaching, 2),
            'staff_pay' => number_format($staffPay, 2),
            'teaching_pay' => number_format($teachingPay, 2),
            'total_payroll' => number_format($totalPayroll, 2),
            'daily_summaries' => $dailySummaries,
        ]);
    }
}
