<x-app-layout>
    <x-slot name="header">
        {{ __('Groupings') }}
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('groupings.create') }}">
                <x-primary-button>New Groupings</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="relative overflow-x-auto sm:rounded-lg my-2">
            <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                <thead class="text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="p-2">Payroll Type</th>
                        <th scope="col" class="p-2">Name</th>
                        <th scope="col" class="p-2">Description</th>
                        <th scope="col" class="p-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($groupings as $grouping)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="p-2">{{ $grouping->payrollType->name }}</td>
                            <td class="p-2">{{ $grouping->name }}</td>
                            <td class="p-2">{{ $grouping->description }}</td>
                            <td>
                                <a href="{{ route('groupings.show', $grouping->id) }}">
                                    Employees
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
