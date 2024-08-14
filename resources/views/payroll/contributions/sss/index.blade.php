<x-app-layout>
    <x-slot name="title">SSS</x-slot>
    <x-slot name="header">SSS</x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('sss.create') }}">
                <x-primary-button>Add SSS</x-primary-button>
            </a>
        </div>
        <x-card>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-left">Name</th>
                        <th class="px-5 py-3 text-left">Effective Date</th>
                        <th class="px-5 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sss as $ss)
                        <tr>
                            <td>{{ $ss->name }}</td>
                            <td>{{ $ss->effective_date->format('F d, Y') }}</td>
                            <td>
                                <a href="{{ route('sss.sss-brackets.index', $ss) }}">
                                    <x-primary-button>View Brackets</x-primary-button>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>
    </div>
</x-app-layout>
