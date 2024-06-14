<x-app-layout>
    <x-slot name="header">
        {{ __('Create Role') }}
    </x-slot>
    <div class="mx-auto max-w-lg">
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="my-2">
                    <x-label>Name</x-label>
                    <x-text-input type="text" name="name" placeholder="Name" />
                </div>
                <div class="my-2">
                    <x-h6>Permissions</x-h6>
                    @foreach ($permission as $value)
                        <div class="flex items-center space-x-2">
                            <x-text-input-checkbox type="checkbox" name="permission[{{ $value->id }}]"
                                value="{{ $value->id }}" class="name" />
                            <x-label> {{ $value->name }}</x-label>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end">
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
