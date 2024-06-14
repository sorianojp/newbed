<x-app-layout>
    <x-slot name="header">
        {{ __('Users') }}
    </x-slot>
    <div class="mx-auto max-w-5xl">
        <div class="flex justify-end">
            <a href="{{ route('users.create') }}">
                <x-primary-button type="button">Create User</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="relative overflow-x-auto sm:rounded-lg my-2">
            <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                <thead class="text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="p-2">Name</th>
                        <th scope="col" class="p-2">Email</th>
                        <th scope="col" class="p-2">Roles</th>
                        <th scope="col" class="p-2">
                            <span class="sr-only">Action</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key => $user)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="p-2">{{ $user->name }}</td>
                            <td class="p-2">{{ $user->email }}</td>
                            <td class="p-2">
                                @if (!empty($user->getRoleNames()))
                                    @foreach ($user->getRoleNames() as $v)
                                        <label>{{ $v }}</label>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                <x-link href="{{ route('users.edit', $user->id) }}">Edit</x-link>
                                <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit">
                                        <x-link-red>Delete</x-link-red>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</x-app-layout>
