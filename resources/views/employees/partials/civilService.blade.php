<x-card>
    <form method="POST" action="{{ route('employees.storeCivilService', ['employee' => $employee]) }}">
        @csrf
        <div class="grid grid-cols-2 gap-2 my-2">
            <div>
                <x-label>Title</x-label>
                <x-text-input type="text" name="title" :value="old('title')" placeholder="Title" />
            </div>
            <div>
                <x-label>Rating</x-label>
                <x-text-input type="number" name="rating" :value="old('rating')" placeholder="0.00" min="0"
                    step="any" />
            </div>
            <div>
                <x-label>Exam Date</x-label>
                <x-text-input type="date" name="exam_date" :value="old('exam_date')" />
            </div>
            <div>
                <x-label>Exam Place</x-label>
                <x-text-input type="text" name="exam_place" :value="old('exam_place')" placeholder="Exam Place" />
            </div>
            <div>
                <x-label>License No</x-label>
                <x-text-input type="text" name="license_no" :value="old('license_no')" placeholder="Licens No" />
            </div>
            <div>
                <x-label>Validity Date</x-label>
                <x-text-input type="date" name="validity_date" :value="old('validity_date')" />
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
                <th scope="col" class="p-2">Rating</th>
                <th scope="col" class="p-2">Exam Date</th>
                <th scope="col" class="p-2">Exam Place</th>
                <th scope="col" class="p-2">Licens No</th>
                <th scope="col" class="p-2">Validity Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employee->civilServices as $record)
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="p-2">{{ $record->title }}</td>
                    <td class="p-2">{{ $record->rating }}</td>
                    <td class="p-2">{{ $record->exam_date }}</td>
                    <td class="p-2">{{ $record->exam_place }}</td>
                    <td class="p-2">{{ $record->license_no }}</td>
                    <td class="p-2">{{ $record->validity_date }}</td>
            @endforeach
        </tbody>
    </table>
</div>
