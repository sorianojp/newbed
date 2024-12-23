<x-app-layout>
    <x-slot name="title">DTR</x-slot>
    <x-slot name="header">Employee's DTR</x-slot>

    <div class="mx-auto max-w-7xl">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th class="px-3 py-2 tracking-tight">Employee ID</th>
                    <th class="px-3 py-2 tracking-tight">Employee Name</th>

                    <th class="px-3 py-2 tracking-tight">
                        <span class="sr-only">Action</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $employee->employee_id_no }}</td>
                        <td>{{ $employee->full_name_only }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
