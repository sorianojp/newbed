<x-app-layout>
    <x-slot name="title">Contribution Schedule</x-slot>
    <x-slot name="header">Contribution Schedule</x-slot>

    <div class="mx-auto max-w-7xl">

        <x-card class="mx-auto max-w-3xl">
            <x-auth-validation-errors></x-auth-validation-errors>
            <form method="post" action="{{ route('contribution-settings.store') }}">
                @csrf
                <div class="mb-2">
                    <x-label>Contribution</x-label>
                    <x-text-input-select name="contribution">
                        <option value="">Select</option>
                        <option value="tax">Tax</option>
                        <option value="sss">SSS</option>
                        <option value="pagibig">Pagibig</option>
                        <option value="philhealth">Philhealth</option>
                    </x-text-input-select>
                </div>

                <div class="mb-2">
                    <x-label>Period</x-label>
                    <x-text-input-select name="period">
                        <option value="">Select</option>
                        <option value="every salary period">Every Period</option>
                        <option value="15th/1st">15th/1st</option>
                        <option value="30th/2nd">30th/2nd</option>
                    </x-text-input-select>
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
                        <th class="px-5 py-3 text-left">Contribution</th>
                        <th class="px-5 py-3 text-left">Period</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($contributions as $contribution)
                        <tr>
                            <td class="px-2 py-1 uppercase">{{ $contribution->contribution }}</td>
                            <td class="px-2 py-1 capitalize">{{ $contribution->period }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-card>
    </div>
</x-app-layout>
