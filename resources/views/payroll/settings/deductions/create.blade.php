<x-app-layout>
    <x-slot name="title">Deduction Type Create</x-slot>
    <x-slot name="header">Deduction Type Create</x-slot>

    <div class="mx-auto max-w-xl">
        <x-card>
            <form method="post" action="{{ route('deductions.store') }}">
                @csrf
                <div class="mb-2">
                    <x-label>Code</x-label>
                    <x-text-input type="text" name="code" />
                </div>
                <div class="mb-2">
                    <x-label>Name</x-label>
                    <x-text-input type="text" name="name" />
                </div>
                <div class="mb-2 flex items-center space-x-2">
                    <x-text-input-checkbox type="checkbox" value='1' name="hidden" id="hidden" />
                    <x-label for="hidden">Hidden</x-label>
                </div>
                <div class="mb-2 flex items-center space-x-2">
                    <x-text-input-checkbox type="checkbox" value='1' name="deducted" id="deducted" />
                    <x-label for="deducted">Deducted from non-taxable gross</x-label>
                </div>
                <div class="mt-2 flex justify-end">
                    <x-primary-button type="submit">Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
