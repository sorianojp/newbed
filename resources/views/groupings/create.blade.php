<x-app-layout>
    <x-slot name="header">
        {{ __('Create Groupings') }}
    </x-slot>
    <div class="mx-auto max-w-xl">
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('groupings.store') }}">
                @csrf
                <div class="space-y-2">
                    <div>
                        <x-label>Payroll Type</x-label>
                        <x-text-input-select name="payroll_type_id">
                            @foreach ($payrollTypes as $payrollTypes)
                                <option value="{{ $payrollTypes->id }}"
                                    {{ old('payroll_type_id') == $payrollTypes->id ? 'selected' : '' }}>
                                    {{ $payrollTypes->name }}
                                </option>
                            @endforeach
                        </x-text-input-select>
                    </div>
                    <div>
                        <x-label>Name</x-label>
                        <x-text-input type="text" name="name" :value="old('name')" placeholder="Name" />
                    </div>
                    <div>
                        <x-label>Description</x-label>
                        <x-text-input type="text" name="description" :value="old('description')" placeholder="Description" />
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
