<x-app-layout>
    <x-slot name="header">
        {{ __('Create Employee') }}
    </x-slot>
    <div class="mx-auto max-w-3xl">
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('employees.store') }}">
                @csrf
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <x-label>Employee ID</x-label>
                        <x-text-input type="text" name="employee_id_no" :value="old('employee_id_no')" placeholder="00000000000" />
                    </div>
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
                        <x-label>Mobile No</x-label>
                        <x-text-input type="number" name="mobile_no" :value="old('mobile_no')" placeholder="090000000000" />
                    </div>
                    <div>
                        <x-label>Personal Email</x-label>
                        <x-text-input type="email" name="personal_email" :value="old('personal_email')"
                            placeholder="delacruzjuan@gmail.com" />
                    </div>
                    <div>
                        <x-label>Company Email</x-label>
                        <x-text-input type="email" name="company_email" :value="old('company_email')"
                            placeholder="delacruzjuan@udd.edu.ph" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
