<x-card>
    <form method="POST" action="{{ route('employees.storeEducationalAttainment', ['employee' => $employee]) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 my-2">
            <div>
                <x-label>Level</x-label>
                <x-text-input-select name="level">
                    <option value="" disabled
                        {{ is_null(optional($employee->personalData)->level) && is_null(old('level')) ? 'selected' : '' }}>
                        Select Level</option>
                    @foreach (['Grade School', 'Junior High', 'Senior High', 'Vocational', 'Bachelor\'s', 'Master\'s', 'Doctorate'] as $option)
                        <option value="{{ $option }}"
                            {{ optional($employee->personalData)->level == $option || old('level') == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </x-text-input-select>
            </div>
            <div>
                <x-label>Course</x-label>
                <x-text-input type="text" name="course" :value="old('course')" placeholder="Course" />
            </div>
            <div>
                <x-label>School</x-label>
                <x-text-input type="text" name="school" :value="old('school')" placeholder="School" />
            </div>
            <div>
                <x-label>Address</x-label>
                <x-text-input type="text" name="address" :value="old('address')" placeholder="Address" />
            </div>
            <div>
                <x-label>Year Started</x-label>
                <x-text-input type="text" name="year_started" :value="old('year_started')" placeholder="Year Started" />
            </div>
            <div>
                <x-label>Year Ended</x-label>
                <x-text-input type="text" name="year_ended" :value="old('year_ended')" placeholder="Year Ended" />
            </div>
            <div>
                <x-label>Year Graduated</x-label>
                <x-text-input type="text" name="year_graduated" :value="old('year_graduated')" placeholder="Year Graduated" />
            </div>
            <div>
                <x-label>Units Earned {{ '(if not yet graduated)' }}</x-label>
                <x-text-input type="text" name="units" :value="old('units')" placeholder="Units" />
            </div>
            <div>
                <x-label>Honor</x-label>
                <x-text-input type="text" name="honor" :value="old('honor')" placeholder="Honor" />
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
                <th scope="col" class="p-2">Level</th>
                <th scope="col" class="p-2">Course</th>
                <th scope="col" class="p-2">School</th>
                <th scope="col" class="p-2">Address</th>
                <th scope="col" class="p-2">Started</th>
                <th scope="col" class="p-2">Ended</th>
                <th scope="col" class="p-2">Graduated</th>
                <th scope="col" class="p-2">Units</th>
                <th scope="col" class="p-2">Honor</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->educationalAttainments as $record)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="p-2">{{ $record->level }}</td>
                    <td class="p-2">{{ $record->course }}</td>
                    <td class="p-2">{{ $record->school }}</td>
                    <td class="p-2">{{ $record->address }}</td>
                    <td class="p-2">{{ $record->year_started }}</td>
                    <td class="p-2">{{ $record->year_ended }}</td>
                    <td class="p-2">{{ $record->year_graduated }}</td>
                    <td class="p-2">{{ $record->units }}</td>
                    <td class="p-2">{{ $record->honor }}</td>
            @endforeach
        </tbody>
    </table>
</div>
