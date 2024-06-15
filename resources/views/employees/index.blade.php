<x-app-layout>
    <x-slot name="header">
        {{ __('Employees') }}
    </x-slot>

    <div class="mx-auto max-w-5xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('employees.create') }}">
                <x-primary-button>New Employee</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="relative overflow-x-auto sm:rounded-lg my-2">
            <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                <thead class="text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="p-2">Last Name</th>
                        <th scope="col" class="p-2">First Name</th>
                        <th scope="col" class="p-2">Middle Name</th>
                        <th scope="col" class="p-2">Name Ext</th>
                        <th scope="col" class="p-2">Birthdate</th>
                        <th scope="col" class="p-2">Mobile No</th>
                        <th scope="col" class="p-2">Personal Email</th>
                        <th scope="col" class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="p-2">{{ $employee->lastname }}</td>
                            <td class="p-2">{{ $employee->firstname }}</td>
                            <td class="p-2">{{ $employee->middlename }}</td>
                            <td class="p-2">{{ $employee->name_ext }}</td>
                            <td class="p-2">{{ $employee->birthdate }}</td>
                            <td class="p-2">{{ $employee->mobile_no }}</td>
                            <td class="p-2">{{ $employee->personal_email }}</td>
                            <td class="p-2">
                                <a href="{{ route('employees.createPersonalData', ['employee' => $employee]) }}">
                                    Personal Data
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
