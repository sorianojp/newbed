<x-card>
    <form method="POST" action="{{ route('employees.storeJobSkill', ['employee' => $employee]) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 my-2">
            <div>
                <x-label>Skill</x-label>
                <x-text-input type="text" name="skill" :value="old('skill')" placeholder="Skill" />
            </div>
            <div>
                <x-label>Level</x-label>
                <x-text-input-select name="level">
                    @foreach (['Beginner', 'Intermediate', 'Advance', 'Expert'] as $option)
                        <option value="{{ $option }}" {{ old('level') == $option ? 'selected' : '' }}>
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
                <th scope="col" class="p-2">Skill</th>
                <th scope="col" class="p-2">Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->jobSkills as $record)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="p-2">{{ $record->skill }}</td>
                    <td class="p-2">{{ $record->level }}</td>
            @endforeach
        </tbody>
    </table>
</div>
