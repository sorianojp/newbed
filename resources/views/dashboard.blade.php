<x-app-layout>
    <x-slot name="header">
        {{ __('Dashboard') }}
    </x-slot>

    <div class="flex space-x-4">
        <a href="{{ route('regular-schedules.index') }}">
            <div class="max-w-60 rounded-sm bg-white shadow-md">
                <div class="flex h-full flex-col items-center justify-center p-4">
                    <x-application-logo></x-application-logo>
                    <span class="font-semibold uppercase tracking-wide">
                        DTR
                    </span>
                </div>
            </div>
        </a>
        <a href="{{ route('schedules.index') }}">
            <div class="max-w-60 rounded-sm bg-white shadow-md">
                <div class="flex h-full flex-col items-center justify-center p-4">
                    <x-application-logo></x-application-logo>
                    <span class="font-semibold uppercase tracking-wide">
                        Payroll
                    </span>
                </div>
            </div>
        </a>
        <a href="{{ route('attendances.index') }}">
            <div class="max-w-60 rounded-sm bg-white shadow-md">
                <div class="flex h-full flex-col items-center justify-center p-4">
                    <x-application-logo></x-application-logo>
                    <span class="font-semibold uppercase tracking-wide">
                        Attendance
                    </span>
                </div>
            </div>
        </a>
    </div>

</x-app-layout>
