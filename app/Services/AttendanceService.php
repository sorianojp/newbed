<?php
namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceService
{
    public function getAttendances($employee, $startDate, $endDate)
    {
        $attendances = Attendance::where('employee_id', $employee)->where(function ($query) use ($startDate, $endDate) {
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            } elseif ($startDate) {
                $query->where('date', $startDate);
            }
        })->orderBy('date')->orderBy('time')->get();

        $formattedAttendances = $attendances->groupBy(function ($attendance) {
            return $attendance->date;
        })->mapWithKeys(function ($group, $date) {
            return [Carbon::parse($date)->format('Y-m-d') => [
                'date' => Carbon::parse($date)->format('F j, Y, l'),
                'orig_date' => Carbon::parse($date),
                'attendances' => $group->map(function ($attendance) {
                    return $attendance;
                })->toArray()],
            ];
        });

        return $formattedAttendances;
    }
}
