<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\RegularSchedule;
use App\Models\TeachingSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class PayrollController extends Controller
{
    public function index(): JsonResponse
    {

        $employeeId = Employee::first()->id;

        // return $this->calcOld($employeeId);
        return $this->calcNew($employeeId);

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

    public function calcNew($employeeId)
    {
        // Set the date range
        $first_date = Carbon::createFromDate(2024, 6, 7, 'Asia/Manila');
        $last_date = Carbon::createFromDate(2024, 6, 21, 'Asia/Manila'); // Adjusted end date for testing

        // Define pay rates
        $basicRate = 20000;
        $hourlyPay = 135;
        $totalWorkingDays = 22; // Or 26, depending on the settings

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

        $totalStaffHours = 0;
        $totalTeachingHours = 0;
        $totalExpectedStaffHours = 0;
        $totalExpectedTeachingHours = 0;
        $totalLatenessMinutesStaff = 0;
        $totalLatenessMinutesTeaching = 0;
        $totalUndertimeMinutesStaff = 0;
        $totalUndertimeMinutesTeaching = 0;
        $dailySummaries = [];

// Find the earliest start date among the schedules
        $earliestStartDate = $staffSchedules->flatten()->min('start_date');
        $startDate = Carbon::parse($earliestStartDate)->startOfDay()->greaterThan($first_date) ? Carbon::parse($earliestStartDate) : $first_date;

// Create a collection of all dates in the range
        $dateRange = collect();
        for ($date = $startDate->copy(); $date->lte($last_date); $date->addDay()) {
            $dateRange->push($date->copy());
        }

        foreach ($dateRange as $date) {
            $dayOfWeek = $date->format('l');
            $dateStr = $date->format('Y-m-d');
            $dailyExpectedStaffHours = 0;
            $dailyActualStaffHours = 0;
            $dailyExpectedTeachingHours = 0;
            $dailyActualTeachingHours = 0;
            $dailyLatenessMinutesStaff = 0;
            $dailyLatenessMinutesTeaching = 0;
            $dailyUndertimeMinutesStaff = 0;
            $dailyUndertimeMinutesTeaching = 0;

            $times = $attendances->get($dateStr, collect());

            // Assume the first item is time-in and the last item is time-out
            $timeIn = $times->isNotEmpty() ? Carbon::createFromFormat('Y-m-d H:i:s', $dateStr . ' ' . $times->first()->time, 'Asia/Manila') : null;
            $timeOut = $times->isNotEmpty() ? Carbon::createFromFormat('Y-m-d H:i:s', $dateStr . ' ' . $times->last()->time, 'Asia/Manila') : null;

            // Process staff schedules
            if ($staffSchedules->has($dayOfWeek)) {

                foreach ($staffSchedules[$dayOfWeek] as $staffSchedule) {
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
                        $presenceDuration = ($timeInTime < $scheduledStartTimeTime)
                        ? $scheduledStartTime->diffInMinutes($scheduledEndTime)
                        : $timeInTime->diffInMinutes($scheduledEndTimeTime);

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
            }

            // Process teaching schedules
            if ($teachingSchedules->has($dayOfWeek)) {

                foreach ($teachingSchedules[$dayOfWeek] as $teachingSchedule) {
                    // Check if the date falls within the schedule range
                    $start_date = Carbon::parse($teachingSchedule->start_date);
                    $end_date = Carbon::parse($teachingSchedule->end_date);
                    if ($date->between($start_date, $end_date)) {
                        // Calculate expected hours from teaching schedule
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
                            $presenceDuration = ($timeInTime < $scheduledStartTimeTime)
                            ? $scheduledStartTime->diffInMinutes($scheduledEndTime)
                            : $timeInTime->diffInMinutes($scheduledEndTimeTime);

                            // Decrease presenceDuration by undertime if applicable
                            if ($timeOutTime < $scheduledEndTimeTime) {
                                $undertimeMinutes = $timeOutTime->diffInMinutes($scheduledEndTimeTime);
                                $dailyUndertimeMinutesTeaching += $undertimeMinutes;
                                $presenceDuration -= $undertimeMinutes;
                            }

                            if ($presenceDuration > $expectedHours) {
                                $presenceDuration = $expectedHours; // Ensure actual hours do not exceed expected hours
                            }
                            if ($presenceDuration > 0) {
                                $dailyActualTeachingHours += ($presenceDuration / 60) * $teachingSchedule->weight; // Convert minutes to hours with weight
                            }
                        }
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
                'date' => $dateStr,
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
        $staffPay = ($totalStaffHours * $staffHourlyRate); // Calculate staff pay based on actual hours worked
        $teachingPay = $totalTeachingHours * $hourlyPay;
        $totalPayroll = $staffPay + $teachingPay;

        return response()->json([
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
