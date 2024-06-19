<x-card>
    <form method="POST" action="{{ route('employees.storeChildrenData', ['employee' => $employee]) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 my-2">
            <div>
                <x-label>Full Name</x-label>
                <x-text-input type="text" name="full_name" :value="old('full_name')" placeholder="Juana Garcia Dela Cruz" />
            </div>
            <div>
                <x-label>Birthdate</x-label>
                <x-text-input type="date" name="birthdate" :value="old('birthdate')" />
            </div>
            <div>
                <x-label>Gender</x-label>
                <x-text-input-select name="gender">
                    @foreach (['Male', 'Female', 'Other'] as $option)
                        <option value="{{ $option }}" {{ old('gender') == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </x-text-input-select>
            </div>
        </div>
        <div class="flex justify-end mt-2">
            <x-primary-button>Save</x-primary-button>
        </div>
    </form>
</x-card>
<div class="relative overflow-x-auto sm:rounded-lg my-2">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="p-2">Full Name</th>
                <th scope="col" class="p-2">Birthdate</th>
                <th scope="col" class="p-2">Gender</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->childrenDatas as $record)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="p-2">{{ $record->full_name }}</td>
                    <td class="p-2">{{ $record->birthdate }}</td>
                    <td class="p-2">{{ $record->gender }}</td>
            @endforeach
        </tbody>
    </table>
</div>
