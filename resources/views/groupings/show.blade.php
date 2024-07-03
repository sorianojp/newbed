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
                <div class="space-y-2">
                    @foreach ($employees as $employee)
                        <div
                            class="p-2 border rounded-lg flex items-center space-x-2 {{ $employee->groupings->isNotEmpty() && !$grouping->employees->contains($employee->id) ? 'bg-gray-200' : '' }}">
                            <x-text-input-checkbox name="employees[]" value="{{ $employee->id }}" :checked="$grouping->employees->contains($employee->id)"
                                :disabled="$employee->groupings->isNotEmpty() &&
                                    !$grouping->employees->contains($employee->id)" />
                            <x-label>
                                {{ $employee->full_name }}
                                @if ($employee->groupings->isNotEmpty() && !$grouping->employees->contains($employee->id))
                                    (Group:
                                    {{ $employee->groupings->first()->name }})
                                @endif
                            </x-label>
                        </div>
                    @endforeach
                    <div class="flex justify-end">
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
