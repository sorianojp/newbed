<x-app-layout>
    <x-slot name="title">Salary Schedule</x-slot>
    <x-slot name="header">Salary Schedule</x-slot>

    <div class="mx-auto max-w-3xl">
        <form action="{{ route('schedules.update', $payrollSchedule) }}" method="post">
            @csrf
            @method('put')
            <x-card>
                <div class="mb-2">
                    <x-label>Payroll Schedule Type</x-label>
                    <x-text-input-select name="payroll_type_id">
                        <option value="">Select</option>
                        @foreach ($payrollTypes as $payrollType)
                            <option value="{{ $payrollType->id }}"
                                {{ $payrollSchedule->payrollType->id == $payrollType->id ? 'selected' : '' }}>
                                {{ $payrollType->name }}</option>
                        @endforeach
                    </x-text-input-select>
                </div>
                <div class="mb-2">
                    <x-label>Salary Period</x-label>
                    <div class="mb-2 grid grid-cols-2 gap-2">
                        <div>
                            <x-label>Start</x-label>
                            <x-text-input type="date" name="salary_start_date" :value="$payrollSchedule->salary_start_date" />
                        </div>
                        <div>

                            <x-label>End</x-label>
                            <x-text-input type="date" name="salary_end_date" :value="$payrollSchedule->salary_end_date" />
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <x-label>Salary Cut-off Range</x-label>
                    <div class="mb-2 grid grid-cols-2 gap-2">
                        <div>
                            <x-label>Start</x-label>
                            <x-text-input type="date" name="cutoff_start_date" :value="$payrollSchedule->cutoff_start_date" />
                        </div>
                        <div>

                            <x-label>End</x-label>
                            <x-text-input type="date" name="cutoff_end_date" :value="$payrollSchedule->cutoff_end_date" />
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <x-label>Pay Date</x-label>
                    <x-text-input type="date" name="pay_date" :value="$payrollSchedule->pay_date" />
                </div>
                <div class="mb-2">
                    <x-label>Schedule Period</x-label>
                    <x-text-input-select name="period">
                        <option value="15th/1st" {{ $payrollSchedule->period == '15th/1st' ? 'selected' : '' }}>15th/1st
                        </option>
                        <option value="30th/2nd" {{ $payrollSchedule->period == '30th/2nd' ? 'selected' : '' }}>30th/2nd
                        </option>
                    </x-text-input-select>
                </div>
                <div class="mb-2">
                    <x-label>Salary Month</x-label>
                    <div class="mb-2 grid grid-cols-2 gap-2">
                        <div>
                            <x-label>Month</x-label>
                            <x-text-input-select name="month">
                                @foreach ($months as $month)
                                    <option value="{{ $month }}"
                                        {{ $payrollSchedule->month == $month ? 'selected' : '' }}>{{ $month }}
                                    </option>
                                @endforeach
                            </x-text-input-select>
                        </div>
                        <div>
                            <x-label>Year</x-label>
                            <x-text-input type="number" name="year" value="{{ $payrollSchedule->year }}" />
                        </div>
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-primary-button>Save</x-primary-button>
                </div>
            </x-card>
        </form>

    </div>
</x-app-layout>

<script></script>
