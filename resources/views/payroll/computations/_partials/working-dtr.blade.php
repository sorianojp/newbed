<div class="fixed inset-0 h-full w-full overflow-y-auto bg-gray-600 bg-opacity-50"
    x-show="open_working_dtr_modal && selectedEmployee" x-cloak>
    <div class="relative left-20 top-20 mx-auto max-w-3xl rounded-md border bg-white p-5 shadow-lg"
        @click.away="open_working_dtr_modal = false">
        <div class="mt-3 text-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('image/placeholder.png') }}" alt="" class="h-24 w-24 border">
                <div class="text-sm tracking-tighter">
                    <div>Name: <span x-text="selectedEmployee.full_name"></span> </div>
                </div>

            </div>
            <div x-show="working_schedules.length > 0">
                <x-card>
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 uppercase tracking-tight">Day</th>
                                <th class="px-3 py-2 uppercase tracking-tight">Time</th>
                                <th class="px-3 py-2 uppercase tracking-tight">Hours</th>
                                <th class="px-3 py-2 uppercase tracking-tight">Effective Date</th>
                                <th class="px-3 py-2 uppercase tracking-tight">
                                    <span class="sr-only">TL</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(sched, index) in working_schedules" :key="index">
                                <tr>
                                    <td class="px-2 py-1 text-center text-sm" x-text="sched.day_of_week"></td>
                                    <td class="px-2 py-1 text-center text-sm" x-text="sched.time_range"></td>
                                    <td class="px-2 py-1 text-center text-sm" x-text="sched.hours"></td>
                                    <td class="px-2 py-1 text-center text-sm" x-text="sched.effective_date"></td>
                                    <td class="px-2 py-1 text-center text-sm"
                                        x-text="sched.type == 'Teaching' ? '*TL' : ''"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <div class="mt-2 flex justify-between">
                        <template x-for="(hour, i) in total_hours_per_day">
                            <span class="text-sm" x-text="i + ': ' + hour"></span>
                        </template>
                    </div>
                    <div>
                        Total: <span x-text="total_hours"></span>
                    </div>
                </x-card>
            </div>

        </div>
    </div>
</div>
