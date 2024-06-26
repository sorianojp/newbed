<x-app-layout>
    <x-slot name="header">
        {{ __('Payroll Types') }}
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('payroll-types.create') }}">
                <x-primary-button>New Payroll Type</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="relative overflow-x-auto sm:rounded-lg my-2">
            <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                <thead class="text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="p-2">Name</th>
                        <th scope="col" class="p-2">Description</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payrollTypes as $payrollType)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="p-2">{{ $payrollType->name }}</td>
                            <td class="p-2">{{ $payrollType->description }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
