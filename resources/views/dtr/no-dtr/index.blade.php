<x-app-layout>
    <x-slot name="title">
        {{ __('No DTR') }}
    </x-slot>
    <x-slot name="header">
        {{ __('No DTR') }}
    </x-slot>

    <div class="mx-auto max-w-5xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('no-dtr.create') }}">
                <x-primary-button>Create</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="relative overflow-x-auto sm:rounded-lg my-2">
            <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                <thead class="text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="p-2">Full Name</th>
                        <th scope="col" class="p-2">Effective Date</th>
                        <th scope="col" class="p-2">End Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($noDTRs as $noDTR)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="p-2">{{ $noDTR->employee->full_name }}</td>
                            <td class="p-2">{{ $noDTR->effective_date }}</td>
                            <td class="p-2">{{ $noDTR->end_date }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
