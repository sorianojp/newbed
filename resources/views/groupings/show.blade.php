<x-app-layout>
    <x-slot name="header">
        {{ __('Create Groupings') }}
    </x-slot>
    <div class="mx-auto max-w-xl">
        <x-auth-validation-errors />
        <x-card>
            <form action="{{ route('addEmployees', $grouping->id) }}" method="POST">
                @csrf
                @method('PUT')
                <x-h6>Employees</x-h6>
                @foreach ($employees as $employee)
                    <div class="flex items-center space-x-2">
                        <x-text-input-checkbox name="employees[]" value="{{ $employee->id }}" :checked="$grouping->employees->contains($employee->id)" />
                        <x-label>{{ $employee->full_name }}</x-label>
                    </div>
                @endforeach
                <x-primary-button>Save</x-primary-button>
            </form>
        </x-card>
    </div>
</x-app-layout>
