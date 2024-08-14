<x-app-layout>
    <x-slot name="title">Tax</x-slot>
    <x-slot name="header">Tax</x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('taxes.tax-brackets.create', $tax) }}">
                <x-primary-button>Add Tax Bracket</x-primary-button>
            </a>
        </div>
        <x-card>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-left">Period</th>
                        <th class="px-5 py-3 text-left">Bracket</th>
                        <th class="px-5 py-3 text-left">Salary Range</th>
                        <th class="px-5 py-3 text-left">Fixed Amount</th>
                        <th class="px-5 py-3 text-left">Excess Percentage</th>
                        <th class="px-5 py-3 text-left">
                            <span class="sr-only">Action</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tax->taxBrackets as $bracket)
                        <tr>
                            <td class="px-3 py-2 capitalize">{{ $bracket->period }}</td>
                            <td class="px-3 py-2">{{ $bracket->bracket }}</td>
                            <td class="px-3 py-2">{{ $bracket->salary_range }}</td>
                            <td class="px-3 py-2 text-left">{{ 'Php ' . number_format($bracket->fixed_amount, 2) }}</td>
                            <td class="px-3 py-2 text-left">{{ $bracket->excess_percentage . '%' }}</td>
                            <td class="px-3 py-2">
                                <form action="{{ route('tax-brackets.destroy', $bracket) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <x-primary-button>Delete</x-primary-button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>
    </div>
</x-app-layout>
