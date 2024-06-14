<x-app-layout>
    <x-slot name="header">
        {{ __('Edit User') }}
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="my-2">
                    <x-h6>User Details</x-h6>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <x-label>Name:</x-label>
                            <x-text-input type="text" name="name" placeholder="Name" value="{{ $user->name }}" />
                        </div>

                        <div>
                            <x-label>Email:</x-label>
                            <x-text-input type="email" name="email" placeholder="Email"
                                value="{{ $user->email }}" />
                        </div>

                        <div>
                            <x-label>Password:</x-label>
                            <x-text-input type="password" name="password" placeholder="Password" />
                        </div>

                        <div>
                            <x-label>Confirm Password:</x-label>
                            <x-text-input type="password" name="confirm-password" placeholder="Confirm Password" />
                        </div>
                    </div>
                </div>
                <div class="my-2">
                    <x-h6>Roles</x-h6>
                    @foreach ($roles as $value => $label)
                        <div class="flex items-center space-x-2">
                            <x-text-input-checkbox name="roles[]" value="{{ $value }}" :checked="isset($userRole[$value])"
                                id="role_{{ $value }}" />
                            <x-label for="role_{{ $value }}">{{ $value }}</x-label>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-end">
                    <x-primary-button>Submit</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
