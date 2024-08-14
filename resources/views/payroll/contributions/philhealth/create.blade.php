<x-app-layout>
    <x-slot name="title">Tax Create</x-slot>
    <x-slot name="header">Tax Create</x-slot>

    <div class="mx-auto max-w-xl">
        <x-card>
            <form method="post" action="{{ route('philhealth.store') }}">
                @csrf
                <div class="mb-2">
                    <x-label>Name</x-label>
                    <x-text-input type="text" name="name" />
                </div>
                <div class="mb-2">
                    <x-label>Effective Date</x-label>
                    <x-text-input type="date" name="effective_date"></x-text-input>
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
    </div>
</x-app-layout>
