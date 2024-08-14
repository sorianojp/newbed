<x-app-layout>
    <x-slot name="title">Pagibig</x-slot>
    <x-slot name="header">Pagibig</x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('pagibig.create') }}">
                <x-primary-button>Add Pagibig</x-primary-button>
            </a>
        </div>
        <x-card>
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-left">Name</th>
                        <th class="px-5 py-3 text-left">Effective Date</th>
                        <th class="px-5 py-3 text-left">Percentage</th>
                        <th class="px-5 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pagibigs as $pagibig)
                        <tr>
                            <td>{{ $pagibig->name }}</td>
                            <td>{{ $pagibig->effective_date->format('F d, Y') }}</td>
                            <td>{{ $pagibig->percentage . '%' }}</td>
                            <td>
                                <form action="{{ route('pagibig.destroy', $pagibig) }}" method="post">
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
