<x-app-layout>
    <x-slot name="header">
        {{ __('Create User') }}
    </x-slot>
    <div class="mx-auto max-w-3xl">
        <x-auth-validation-errors />
        <x-card>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="my-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <x-label>Name</x-label>
                            <x-text-input type="text" name="name" placeholder="Username" />
                        </div>
                        <div>
                            <x-label>Email</x-label>
                            <x-text-input type="email" name="email" placeholder="Email" />
                        </div>
                        <div>
                            <x-label>Password</x-label>
                            <x-text-input type="password" name="password" placeholder="Password" />
                        </div>
                        <div>
                            <x-label>Confirm Password</x-label>
                            <x-text-input type="password" name="confirm-password" placeholder="Confirm Password" />
                        </div>
                    </div>
                </div>
                <div class="my-2">
                    <x-label>Roles</x-label>
                    @foreach ($roles as $role)
                        <div class="flex items-center space-x-2">
                            <x-text-input-checkbox name="roles[]" value="{{ $role }}"
                                id="role_{{ $role }}" />
                            <x-label for="role_{{ $role }}">{{ $role }}</x-label>
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
