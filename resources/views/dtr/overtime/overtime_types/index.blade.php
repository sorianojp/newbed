<x-app-layout>
    <x-slot name="header">
        {{ __('Overtime Types') }}
    </x-slot>

    <div class="mx-auto max-w-xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('overtime-types.create') }}">
                <x-primary-button>New Overtime Type</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="relative my-2 overflow-x-auto sm:rounded-lg">
            <table class="w-full text-left text-xs text-gray-500 rtl:text-right">
                <thead class="bg-gray-50 uppercase text-gray-700">
                    <tr>
                        <th scope="col" class="p-2">Name</th>
                        <th scope="col" class="p-2">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($overtimeTypes as $overtimeType)
                        <tr class="border-b bg-white hover:bg-gray-50">
                            <td class="p-2">{{ $overtimeType->name }}</td>
                            <td class="p-2">{{ $overtimeType->rate }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
