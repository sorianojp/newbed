<?php
namespace App\Services;

use App\Models\RegularSchedule;
use App\Models\TeachingSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    private $dayOfWeekOrder = "CASE
        WHEN day_of_week = 'Monday' THEN 1
        WHEN day_of_week = 'Tuesday' THEN 2
        WHEN day_of_week = 'Wednesday' THEN 3
        WHEN day_of_week = 'Thursday' THEN 4
        WHEN day_of_week = 'Friday' THEN 5
        WHEN day_of_week = 'Saturday' THEN 6
        WHEN day_of_week = 'Sunday' THEN 7
        END";

    public function getSchedules($employee, $type = 'regular', $first_date = null, $last_date = null)
    {
        $dayOfWeekOrder = $this->dayOfWeekOrder;
        $query = null;

        if ($type === 'regular') {
            $query = RegularSchedule::select(
                'id', 'employee_id', 'day_of_week', 'start_time', 'end_time', 'start_date',
                DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, start_time, end_time) / 60, 2) AS hours'),
                DB::raw('CONCAT(DATE_FORMAT(start_time, "%h:%i%p"), " - ", DATE_FORMAT(end_time, "%h:%i%p")) AS time_range'),
                DB::raw('"Regular" AS type') // Add type indicator

            );
        } elseif ($type === 'teaching') {
            $query = TeachingSchedule::select(
                'id', 'employee_id', 'day_of_week', 'start_time', 'end_time', 'start_date', 'end_date', 'weight',
                DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, start_time, end_time) / 60, 2) AS hours'),
                DB::raw('ROUND(TIMESTAMPDIFF(MINUTE, start_time, end_time) / 60 * weight, 2) AS weighted_hours'),
                DB::raw('CONCAT(DATE_FORMAT(start_time, "%h:%i%p"), " - ", DATE_FORMAT(end_time, "%h:%i%p")) AS time_range'),
                DB::raw('"Teaching" AS type') // Add type indicator
            );
        }

        if ($first_date != null || $last_date != null) {
            $query->where(function ($query) use ($first_date, $last_date) {
                $query->where(function ($query) use ($first_date, $last_date) {
                    $query->where('start_date', '<=', $last_date->format('Y-m-d'))
                        ->where(function ($query) use ($first_date) {
                            $query->whereNull('end_date')
                                ->orWhere('end_date', '>=', $first_date->format('Y-m-d'));
                        });
                });
            });
        }

        $schedules = $query
            ->where('employee_id', $employee)
            ->orderByRaw($dayOfWeekOrder)
            ->orderBy('start_time')
            ->get();

        // Calculate total hours per day
        $totalHoursPerDay = $schedules->groupBy('day_of_week')->map(function ($daySchedules) use ($type) {
            if ($type === 'teaching') {
                return number_format($daySchedules->sum('weighted_hours'), 2);
            }
            return number_format($daySchedules->sum('hours'), 2);
        });

        // Default array for all days of the week with 0 hours
        $defaultHoursPerDay = collect([
            'Monday' => 0.00,
            'Tuesday' => 0.00,
            'Wednesday' => 0.00,
            'Thursday' => 0.00,
            'Friday' => 0.00,
            'Saturday' => 0.00,
            'Sunday' => 0.00,
        ]);

        // Merge the calculated hours with the default array
        $totalHoursPerDay = $defaultHoursPerDay->merge($totalHoursPerDay);
        $totalHours = number_format($totalHoursPerDay->sum(), 2);

        return [
            'schedules' => $schedules,
            'total_hours_per_day' => $totalHoursPerDay,
            'total_hours' => $totalHours,
        ];
    }

    public function getRegularSchedules($employee, $first_date = null, $last_date = null)
    {
        return $this->getSchedules($employee, 'regular', $first_date, $last_date);
    }

    public function getTeachingSchedules($employee, $first_date = null, $last_date = null)
    {
        return $this->getSchedules($employee, 'teaching', $first_date, $last_date);
    }

    public function getWorkingSchedules($employee, $first_date, $last_date)
    {
        $first_date = Carbon::parse($first_date);
        $last_date = Carbon::parse($last_date);

        $regularSchedules = $this->getRegularSchedules($employee, $first_date, $last_date);
        $teachingSchedules = $this->getTeachingSchedules($employee, $first_date, $last_date);

        // Combine regular and teaching schedules
        $combinedSchedules = $regularSchedules['schedules']->merge($teachingSchedules['schedules']);

        // Sort the combined schedules by dayOfWeekOrder
        $dayOfWeekOrder = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
        $sortedSchedules = $combinedSchedules->sortBy(function ($schedule) use ($dayOfWeekOrder) {
            return $dayOfWeekOrder->search($schedule->day_of_week);
        })->values();

        $totalHoursPerDay = $regularSchedules['total_hours_per_day']->map(function ($regularHours, $day) use ($teachingSchedules) {
            $teachingHours = $teachingSchedules['total_hours_per_day']->get($day, 0);
            return $regularHours + $teachingHours;
        });

        // Ensure the total hours per day is in the correct order
        $sortedTotalHoursPerDay = $dayOfWeekOrder->mapWithKeys(function ($day) use ($totalHoursPerDay) {
            return [$day => $totalHoursPerDay->get($day, 0.00)];
        });

        $totalHours = number_format($sortedTotalHoursPerDay->sum(), 2);

        return [
            'schedules' => $sortedSchedules,
            'total_hours_per_day' => $sortedTotalHoursPerDay,
            'total_hours' => $totalHours,
        ];
    }
}
