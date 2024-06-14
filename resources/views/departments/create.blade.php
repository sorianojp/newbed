<x-app-layout>
    <x-slot name="header">
        {{ __('Create Department') }}
    </x-slot>
    <div class="mx-auto max-w-lg">
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf
                <div class="my-2">
                    <x-label>Name</x-label>
                    <x-text-input type="text" name="name" placeholder="Name" />
                </div>
                <div class="flex justify-end">
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
