<x-app-layout>
    <x-slot name="header">
        {{ __('Departments') }}
    </x-slot>

    <div class="mx-auto max-w-xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('departments.create') }}">
                <x-primary-button>New Employee</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="relative overflow-x-auto sm:rounded-lg my-2">
            <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                <thead class="text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="p-2">Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="p-2">{{ $department->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
