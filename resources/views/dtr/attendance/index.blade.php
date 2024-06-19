<x-app-layout>
    <x-slot name="title">
        {{ __('Attendances') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Attendances') }}
    </x-slot>

    <div class="mx-auto max-w-xl">
        <x-auth-session-status :status="session()->get('success')" />
        <div class="my-2 overflow-x-auto sm:rounded-lg" x-data="employees">
            <x-card>
                @include('_partials.search-employees')

                <div class="mb-2">
                    <x-label>Date</x-label>
                    <x-text-input type="date" name="date" />
                </div>
                <div class="mb-2">
                    <x-label>Time</x-label>
                    <x-text-input type="time" name="time" />
                </div>
            </x-card>

        </div>
    </div>

    @include('_partials.search-employees-js')
</x-app-layout>
