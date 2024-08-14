<x-app-layout>
    <x-slot name="title">Tax</x-slot>
    <x-slot name="header">Tax</x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('taxes.create') }}">
                <x-primary-button>Add Tax</x-primary-button>
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
                    @foreach ($taxes as $tax)
                        <tr>
                            <td>{{ $tax->name }}</td>
                            <td>{{ $tax->effective_date->format('F d, Y') }}</td>
                            <td>
                                <a href="{{ route('taxes.tax-brackets.index', $tax) }}">
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
