<x-app-layout>
    <x-slot name="title">Tax Create</x-slot>
    <x-slot name="header">Tax Create</x-slot>

    <div class="mx-auto max-w-xl">
        <x-card>
            <form method="post" action="{{ route('taxes.tax-brackets.store', $tax) }}">
                @csrf
                <div class="mb-2">
                    <x-label>Tax Period</x-label>
                    <x-text-input-select name="period">
                        <option value="">Select One</option>
                        <option value="bi-monthly">Bi-Monthly</option>
                        <option value="monthly">Monthly</option>
                    </x-text-input-select>
                </div>
                <div class="mb-2">
                    <x-label>Bracket</x-label>
                    <x-text-input type="number" name="bracket"></x-text-input>
                </div>
                <div class="mb-2 grid grid-cols-2 gap-2">
                    <div>
                        <x-label>Salary Range:</x-label>
                        <x-text-input type="number" name="start_range" />
                    </div>
                    <div>
                        <x-label> (Note: Don't put if infinite)</x-label>
                        <x-text-input type="number" name="end_range" />
                    </div>
                </div>
                <div class="mb-2">
                    <x-label>Fixed Amount</x-label>
                    <x-text-input type="number" name="fixed_amount" step="0.01" />
                </div>
                <div class="mb-2">
                    <x-label>Percentage %</x-label>
                    <x-text-input type="number" name="excess_percentage" />
                </div>
                <div class="mt-2 flex justify-end">
                    <x-primary-button type="submit">Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
