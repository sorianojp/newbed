<x-app-layout>
    <x-slot name="header">
        {{ __('Roles and Permissions') }}
    </x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('roles.create') }}">
                <x-primary-button>New Role</x-primary-button>
            </a>
            <a href="{{ route('createPermission') }}">
                <x-primary-button>New Permission</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />
        <div class="grid grid-cols-2 gap-2">
            <div class="relative overflow-x-auto sm:rounded-lg my-2">
                <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                    <thead class="text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="p-2">Name</th>
                            <th scope="col" class="p-2">Permissions</th>
                            <th scope="col" class="p-2">
                                <span class="sr-only">Action</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key => $role)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="p-2">{{ $role->name }}</td>
                                <td class="p-2">
                                    @if (!empty($role->permissions))
                                        @foreach ($role->permissions as $permission)
                                            <span>{{ $permission->name }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="p-2">
                                    <x-link href="{{ route('roles.edit', $role->id) }}">Edit</x-link>
                                    <form method="POST" action="{{ route('roles.destroy', $role->id) }}"
                                        style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <x-link-red>Delete</x-link-red>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="relative overflow-x-auto sm:rounded-lg my-2">
                <table class="w-full text-xs text-left rtl:text-right text-gray-500">
                    <thead class="text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th scope="col" class="p-2">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $key => $permission)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="p-2">{{ $permission->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
</x-app-layout>
