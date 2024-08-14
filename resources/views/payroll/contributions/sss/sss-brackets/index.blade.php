<x-app-layout>
    <x-slot name="title">SSS</x-slot>
    <x-slot name="header">SSS</x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('sss.sss-brackets.create', $sss) }}">
                <x-primary-button>Add SSS Bracket</x-primary-button>
            </a>
        </div>
        <x-card>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-left">Range</th>
                        <th class="px-5 py-3 text-left">MSC</th>
                        <th class="px-5 py-3 text-left">EC</th>
                        <th class="px-5 py-3 text-left">ER</th>
                        <th class="px-5 py-3 text-left">EE</th>
                        <th class="px-5 py-3 text-left">Total</th>
                        <th class="px-5 py-3 text-left">
                            <span class="sr-only">Action</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sss->sssBrackets as $bracket)
                        <tr>
                            <td class="px-3 py-2 capitalize">{{ $bracket->salary_range }}</td>
                            <td class="px-3 py-2">{{ number_format($bracket->msc, 2) }}</td>
                            <td class="px-3 py-2">{{ number_format($bracket->ec, 2) }}</td>
                            <td class="px-3 py-2">{{ number_format($bracket->er, 2) }}</td>
                            <td class="px-3 py-2">{{ number_format($bracket->ee, 2) }}</td>
                            <td class="px-3 py-2">{{ number_format($bracket->total, 2) }}</td>
                            <td class="px-3 py-2">
                                <form action="{{ route('sss-brackets.destroy', $bracket) }}" method="post">
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
