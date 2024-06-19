<x-card>
    <form method="POST" action="{{ route('employees.storeEmploymentRecord', ['employee' => $employee]) }}">
        @csrf
        <div class="grid grid-cols-3 gap-2 my-2">
            <div>
                <x-label>Position</x-label>
                <x-text-input-select id="position_id" name="position_id">
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                            {{ $position->name }}
                        </option>
                    @endforeach
                </x-text-input-select>
            </div>
            <div>
                <x-label>Department</x-label>
                <x-text-input-select id="department_id" name="department_id">
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </x-text-input-select>
            </div>
            <div>
                <x-label>Tenureship</x-label>
                <x-text-input-select id="tenureship_id" name="tenureship_id">
                    @foreach ($tenureships as $tenureship)
                        <option value="{{ $tenureship->id }}"
                            {{ old('tenureship_id') == $tenureship->id ? 'selected' : '' }}>
                            {{ $tenureship->name }}
                        </option>
                    @endforeach
                </x-text-input-select>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-2 sm:grid-cols-3 my-2">
            <div>
                <x-label>Start Date</x-label>
                <x-text-input type="date" name="start_date" :value="old('start_date')" />
            </div>
            <div>
                <x-label>End Date</x-label>
                <x-text-input type="date" name="end_date" :value="old('end_date')" />
            </div>
            <div>
                <x-label>Base Salary</x-label>
                <x-text-input type="number" name="base_salary" :value="old('base_salary')" placeholder="0.000" min="0"
                    step="any" />
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
                <th scope="col" class="p-2">Position</th>
                <th scope="col" class="p-2">Department</th>
                <th scope="col" class="p-2">Tenureship</th>
                <th scope="col" class="p-2">Salary</th>
                <th scope="col" class="p-2">Start</th>
                <th scope="col" class="p-2">End</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->employmentRecords as $record)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="p-2">{{ $record->position->name }}</td>
                    <td class="p-2">{{ $record->department->name }}</td>
                    <td class="p-2">{{ $record->tenureship->name }}</td>
                    <td class="p-2">{{ number_format($record->base_salary, 3) }}</td>
                    <td class="p-2">{{ $record->start_date }}</td>
                    <td class="p-2">{{ $record->end_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
