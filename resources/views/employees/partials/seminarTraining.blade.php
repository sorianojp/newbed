<x-card>
    <form method="POST" action="{{ route('employees.storeSeminarTraining', ['employee' => $employee]) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 my-2">
            <div>
                <x-label>Title</x-label>
                <x-text-input type="text" name="title" :value="old('title')" placeholder="Title" />
            </div>
            <div>
                <x-label>Venue</x-label>
                <x-text-input type="text" name="venue" :value="old('venue')" placeholder="Venue" />
            </div>
            <div>
                <x-label>Start Date</x-label>
                <x-text-input type="date" name="start_date" :value="old('start_date')" placeholder="Start Date" />
            </div>
            <div>
                <x-label>End Date</x-label>
                <x-text-input type="date" name="end_date" :value="old('end_date')" placeholder="End Date" />
            </div>
            <div>
                <x-label>Hours</x-label>
                <x-text-input type="number" name="hours" :value="old('hours')" placeholder="Hours" />
            </div>
            <div>
                <x-label>Type of LD</x-label>
                <x-text-input-select name="ld_type">
                    @foreach (['Managerial', 'Supervisory', 'Technical'] as $option)
                        <option value="{{ $option }}" {{ old('ld_type') == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </x-text-input-select>
            </div>
            <div>
                <x-label>Conducted/Sponsored</x-label>
                <x-text-input type="text" name="conducted_sponsored" :value="old('conducted_sponsored')"
                    placeholder="Conducted/Sponsored" />
            </div>
            <div>
                <x-label>Service Return</x-label>
                <x-text-input type="text" name="service_return" :value="old('service_return')" placeholder="Service Return" />
            </div>
            <div>
                <x-label>Remark</x-label>
                <x-text-input type="text" name="remark" :value="old('remark')" placeholder="Remark" />
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
                <th scope="col" class="p-2">Title</th>
                <th scope="col" class="p-2">Venue</th>
                <th scope="col" class="p-2">Start Date</th>
                <th scope="col" class="p-2">End Date</th>
                <th scope="col" class="p-2">Hours</th>
                <th scope="col" class="p-2">Type of LD</th>
                <th scope="col" class="p-2">Conducted/Sponsored</th>
                <th scope="col" class="p-2">Service Return</th>
                <th scope="col" class="p-2">Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->seminarTrainings as $record)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="p-2">{{ $record->title }}</td>
                    <td class="p-2">{{ $record->venue }}</td>
                    <td class="p-2">{{ $record->start_date }}</td>
                    <td class="p-2">{{ $record->end_date }}</td>
                    <td class="p-2">{{ $record->hours }}</td>
                    <td class="p-2">{{ $record->ld_type }}</td>
                    <td class="p-2">{{ $record->conducted_sponsored }}</td>
                    <td class="p-2">{{ $record->service_return }}</td>
                    <td class="p-2">{{ $record->remark }}</td>
            @endforeach
        </tbody>
    </table>
</div>
