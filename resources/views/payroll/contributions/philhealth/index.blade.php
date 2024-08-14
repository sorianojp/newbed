<x-app-layout>
    <x-slot name="title">Philhealth</x-slot>
    <x-slot name="header">Philhealth</x-slot>

    <div class="mx-auto max-w-7xl">

        <x-card class="mx-auto max-w-3xl">
            <x-auth-validation-errors></x-auth-validation-errors>
            <form method="post" action="{{ route('philhealth.store') }}">
                @csrf
                <div class="mb-2">
                    <x-label>Salary Bracket</x-label>
                    <x-text-input type="numeric" min="0" name="bracket"></x-text-input>
                </div>
                <div class="mb-2 grid grid-cols-2 gap-2">
                    <div>
                        <x-label>Salary Range:</x-label>
                        <x-text-input type="number" step="0.01" min="0" name="start_range" />
                    </div>
                    <div>
                        <x-label> (Note: Don't put if infinite)</x-label>
                        <x-text-input type="number" step="0.01" min="0" name="end_range" />
                    </div>
                </div>
                <div class="mb-2">
                    <x-label>Base Salary</x-label>
                    <x-text-input type="numeric" min="0" step="0.01" name="premium"></x-text-input>
                </div>
                <div class="mb-2">
                    <x-label>Employee Share</x-label>
                    <x-text-input type="numeric" min="0" step="0.01" name="employee_share"></x-text-input>
                </div>
                <div class="mb-2">
                    <x-label>Employer Share</x-label>
                    <x-text-input type="numeric" min="0" step="0.01" name="employer_share"></x-text-input>
                </div>
                <div class="mb-2">
                    <x-label>Percentange (%)</x-label>
                    <x-text-input type="numeric" min="0" step="0.01" name="percentage"></x-text-input>
                </div>

                <div class="mt-2 flex justify-end">
                    <x-primary-button type="submit">Save</x-primary-button>
                </div>
            </form>
        </x-card>

        <x-card class="mt-2">
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-5 py-3 text-left">Bracket</th>
                        <th class="px-5 py-3 text-left">Range</th>
                        <th class="px-5 py-3 text-left">Base</th>
                        <th class="px-5 py-3 text-left">Premium</th>
                        <th class="px-5 py-3 text-left">Employee</th>
                        <th class="px-5 py-3 text-left">Employer</th>
                        <th class="px-5 py-3 text-left">Percentage</th>
                        <th class="px-5 py-3 text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($philhealths as $philhealth)
                        <tr>
                            <td>{{ $philhealth->bracket }}</td>
                            <td>{{ $philhealth->range }}</td>
                            <td>{{ number_format($philhealth->base, 2) }}</td>
                            <td>{{ number_format($philhealth->premium, 2) }}</td>
                            <td>{{ number_format($philhealth->employee_share, 2) }}</td>
                            <td>{{ number_format($philhealth->employer_share, 2) }}</td>

                            <td>{{ $philhealth->percent }}</td>
                            <td>
                                <form action="{{ route('philhealth.destroy', $philhealth) }}" method="post">
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
