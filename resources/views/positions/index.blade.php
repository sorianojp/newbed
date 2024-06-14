<x-app-layout>
    <x-slot name="header">
        {{ __('Positions') }}
    </x-slot>
    <div class="mx-auto max-w-xl">
        <div class="flex justify-end">
            <a href="{{ route('positions.create') }}">
                <x-primary-button type="button">Create Position</x-primary-button>
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
                    @foreach ($positions as $position)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="p-2">{{ $position->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</x-app-layout>
