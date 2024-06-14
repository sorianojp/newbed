<x-app-layout>
    <x-slot name="header">
        {{ __('Create Permission') }}
    </x-slot>
    <div class="max-w-lg mx-auto">
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('storePermission') }}">
                @csrf
                <!-- Role Name -->
                <div class="mb-2">
                    <x-label>Permission</x-label>
                    <x-text-input type="text" name="permission" placeholder="Can Create" />
                </div>
                <div class="flex justify-end">
                    <x-primary-button type="submit">Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
