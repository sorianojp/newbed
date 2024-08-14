<x-app-layout>
    <x-slot name="title">SSS Create</x-slot>
    <x-slot name="header">SSS Create</x-slot>

    <div class="mx-auto max-w-xl">
        <x-card>
            <form method="post" action="{{ route('sss.sss-brackets.store', $sss) }}">
                @csrf

                <div class="mb-2 grid grid-cols-2 gap-2">
                    <div>
                        <x-label>Salary Range:</x-label>
                        <x-text-input type="number" name="start_range" step="0.01" />
                    </div>
                    <div>
                        <x-label> (Note: Don't put if infinite)</x-label>
                        <x-text-input type="number" name="end_range" step="0.01" />
                    </div>
                </div>
                <div class="mb-2">
                    <x-label>Monthly Salary Credit</x-label>
                    <x-text-input type="number" name="msc" step="0.01" />
                </div>
                <div class="mb-2">
                    <x-label>EC</x-label>
                    <x-text-input type="number" name="ec" step="0.01" />
                </div>
                <div class="mb-2">
                    <x-label>ER</x-label>
                    <x-text-input type="number" name="er" step="0.01" />
                </div>
                <div class="mb-2">
                    <x-label>EE</x-label>
                    <x-text-input type="number" name="ee" step="0.01" />
                </div>
                <div class="mt-2 flex justify-end">
                    <x-primary-button type="submit">Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
