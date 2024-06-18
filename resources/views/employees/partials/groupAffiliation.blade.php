<x-card>
    <form method="POST" action="{{ route('employees.storeGroupAffiliation', ['employee' => $employee]) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 my-2">
            <div>
                <x-label>Organization Name</x-label>
                <x-text-input type="text" name="name" :value="old('name')" placeholder="Organization Name" />
            </div>
            <div>
                <x-label>Position</x-label>
                <x-text-input type="text" name="position" :value="old('position')" placeholder="Position" />
            </div>
            <div>
                <x-label>Start Date</x-label>
                <x-text-input type="date" name="start_date" :value="old('start_date')" placeholder="Start Date" />
            </div>
            <div>
                <x-label>End Date</x-label>
                <x-text-input type="date" name="end_date" :value="old('end_date')" placeholder="End Date" />
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
                <th scope="col" class="p-2">Name</th>
                <th scope="col" class="p-2">Position</th>
                <th scope="col" class="p-2">Start Date</th>
                <th scope="col" class="p-2">End Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->groupAffiliations as $record)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="p-2">{{ $record->name }}</td>
                    <td class="p-2">{{ $record->position }}</td>
                    <td class="p-2">{{ $record->start_date }}</td>
                    <td class="p-2">{{ $record->end_date }}</td>
            @endforeach
        </tbody>
    </table>
</div>
