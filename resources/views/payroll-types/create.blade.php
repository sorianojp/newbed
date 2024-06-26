<x-app-layout>
    <x-slot name="header">
        {{ __('Create Payroll Type') }}
    </x-slot>
    <div class="mx-auto max-w-xl">
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('payroll-types.store') }}">
                @csrf
                <div class="space-y-2">
                    <div>
                        <x-label>Name</x-label>
                        <x-text-input type="text" name="name" :value="old('name')" placeholder="Name" />
                    </div>
                    <div>
                        <x-label>Description</x-label>
                        <x-text-input type="text" name="description" :value="old('description')" placeholder="Description" />
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button>Save</x-primary-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
