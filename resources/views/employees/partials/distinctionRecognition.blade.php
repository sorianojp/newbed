<x-card>
    <form method="POST" action="{{ route('employees.storeDistinctionRecognition', ['employee' => $employee]) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 my-2">
            <div>
                <x-label>Distinction/Recongnition Title</x-label>
                <x-text-input type="text" name="title" :value="old('title')" placeholder="Title" />
            </div>
            <div>
                <x-label>Place</x-label>
                <x-text-input type="text" name="place" :value="old('place')" placeholder="Place" />
            </div>
            <div>
                <x-label>Date</x-label>
                <x-text-input type="date" name="date" :value="old('date')" />
            </div>
            <div>
                <x-label>Granting Agency/Organization</x-label>
                <x-text-input type="text" name="agency_org" :value="old('agency_org')" placeholder="Agency/Organization" />
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
                <th scope="col" class="p-2">Place</th>
                <th scope="col" class="p-2">Date</th>
                <th scope="col" class="p-2">Granting</th>
                <th scope="col" class="p-2">Remark</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->distinctionRecognitions as $record)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="p-2">{{ $record->title }}</td>
                    <td class="p-2">{{ $record->place }}</td>
                    <td class="p-2">{{ $record->date }}</td>
                    <td class="p-2">{{ $record->agency_org }}</td>
                    <td class="p-2">{{ $record->remark }}</td>
            @endforeach
        </tbody>
    </table>
</div>
