<x-app-layout>
    <x-slot name="title">Payroll Schedule</x-slot>
    <x-slot name="header">Payroll Schedule</x-slot>

    <div class="mx-auto max-w-7xl">
        <a href="{{ route('schedules.create') }}">
            <x-primary-button>Create</x-primary-button>
        </a>
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th class="px-3 py-2 tracking-tight">Type</th>
                    <th class="px-3 py-2 tracking-tight">Month/Year</th>
                    <th class="px-3 py-2 tracking-tight">Period</th>
                    <th class="px-3 py-2 tracking-tight">Range</th>
                    <th class="px-3 py-2 tracking-tight">Cut-Off</th>
                    <th class="px-3 py-2 tracking-tight">Pay Date</th>
                    <th class="px-3 py-2 tracking-tight">
                        <span class="sr-only">Action</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($payrollSchedules as $schedule)
                    <tr>
                        <td class="px-2 py-1 text-center text-sm tracking-tight">{{ $schedule->payrollType->name }}</td>
                        <td class="px-2 py-1 text-center text-sm tracking-tight">{{ $schedule->month_year }}</td>
                        <td class="px-2 py-1 text-center text-sm tracking-tight">{{ $schedule->period }}</td>
                        <td class="px-2 py-1 text-center text-sm tracking-tight">{{ $schedule->range }}</td>
                        <td class="px-2 py-1 text-center text-sm tracking-tight">{{ $schedule->cutoff }}</td>
                        <td class="px-2 py-1 text-center text-sm tracking-tight">{{ $schedule->pay_date }}</td>
                        <td class="px-2 py-1 text-center text-sm tracking-tight">
                            @if ($schedule->payslips->count())
                                <span class="text-red-500">closed</span>
                            @else
                                <a href="{{ route('schedules.edit', $schedule) }}">Edit</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
