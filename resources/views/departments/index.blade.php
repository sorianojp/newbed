<x-app-layout>
    <x-slot name="header">
        {{ __('Departments') }}
    </x-slot>

    <div class="mx-auto max-w-xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('departments.create') }}">
                <x-primary-button>New Department</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="relative my-2 overflow-x-auto sm:rounded-lg">
            <table class="w-full text-left text-xs text-gray-500 rtl:text-right">
                <thead class="bg-gray-50 uppercase text-gray-700">
                    <tr>
                        <th scope="col" class="p-2">Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                        <tr class="border-b bg-white hover:bg-gray-50">
                            <td class="p-2">{{ $department->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
