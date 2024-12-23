<div class="fixed inset-0 h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50"
    x-show="open_view_dtr_modal && selectedEmployee" x-cloak>

    <div class="relative left-20 top-20 mx-auto max-w-3xl rounded-md border bg-white p-5 shadow-lg"
        @click.away="open_view_dtr_modal = false">
        <div class="mt-3 text-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('image/placeholder.png') }}" alt="" class="h-24 w-24 border">
                <div class="text-sm tracking-tighter">
                    <div>Name: <span x-text="selectedEmployee.full_name"></span> </div>
                </div>

            </div>
            <div x-show="attendances.length > 0">
                <x-card>
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 uppercase tracking-tight">Day</th>

                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(attendance, index) in attendances" :key="index">
                                <tr>
                                    <td class="px-2 py-1 text-center text-sm" x-text="attendance.date"></td>

                                </tr>
                            </template>
                        </tbody>
                    </table>

                </x-card>
            </div>
        </div>
    </div>

</div>
