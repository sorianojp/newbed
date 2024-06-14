<x-app-layout>
    <x-slot name="header">
        {{ __('Create Employee') }}
    </x-slot>
    <div class="mx-auto max-w-lg">
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf
                <div class="grid grid-cols-1 gap-2">
                    <div>
                        <x-label>Lastname</x-label>
                        <x-text-input type="text" name="lastname" :value="old('lastname')" placeholder="Dela Cruz" />
                    </div>
                    <div>
                        <x-label>Firstname</x-label>
                        <x-text-input type="text" name="firstname" :value="old('firstname')" placeholder="Juan" />
                    </div>
                    <div>
                        <x-label>Middlename</x-label>
                        <x-text-input type="text" name="middlename" :value="old('middlename')" placeholder="Garcia" />
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 my-2">
                    <div>
                        <x-label>Name Ext</x-label>
                        <x-text-input type="text" name="name_ext" :value="old('name_ext')"
                            placeholder="Jr., Sr., II, III" />
                    </div>
                    <div>
                        <x-label>Birthdate</x-label>
                        <x-text-input type="date" name="birthdate" :value="old('birthdate')" placeholder="Birthdate" />
                    </div>
                    <div>
                        <x-label>Mobile No</x-label>
                        <x-text-input type="number" name="mobile_no" :value="old('mobile_no')" placeholder="090000000000" />
                    </div>
                    <div>
                        <x-label>Personal Email</x-label>
                        <x-text-input type="email" name="personal_email" :value="old('personal_email')"
                            placeholder="delacruzjuan@gmail.com" />
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2 my-2">
                    <div>
                        <x-label>Position</x-label>
                        <x-text-input-select id="position_id" name="position_id">
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                    {{ $position->name }}
                                </option>
                            @endforeach
                        </x-text-input-select>
                    </div>
                    <div>
                        <x-label>Department</x-label>
                        <x-text-input-select id="department_id" name="department_id">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </x-text-input-select>
                    </div>
                    <div>
                        <x-label>Tenureship</x-label>
                        <x-text-input-select id="tenureship_id" name="tenureship_id">
                            @foreach ($tenureships as $tenureship)
                                <option value="{{ $tenureship->id }}"
                                    {{ old('tenureship_id') == $position->id ? 'selected' : '' }}>
                                    {{ $tenureship->name }}
                                </option>
                            @endforeach
                        </x-text-input-select>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 my-2">
                    <div>
                        <x-label>Base Salary</x-label>
                        <x-text-input type="number" name="base_salary" :value="old('base_salary')" placeholder="0.000"
                            min="0" step="any" />
                    </div>
                    <div>
                        <x-label>Start Date</x-label>
                        <x-text-input type="date" name="start_date" :value="old('start_date')" placeholder="Start Date" />
                    </div>
                    <div>
                        <x-label>End Date</x-label>
                        <x-text-input type="date" name="end_date" :value="old('end_date')" placeholder="End Date" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
