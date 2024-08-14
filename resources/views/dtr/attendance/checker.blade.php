<x-app-layout>
    <x-slot name="title">
        {{ __('Checker Reports') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Checker Reports') }}
    </x-slot>

    <div class="mx-auto max-w-3xl" x-data="employees">
        <x-auth-session-status :status="session()->get('success')" />
        <div class="my-2 overflow-x-auto sm:rounded-lg" x-data="checker">
            <x-card>
                @include('_partials.search-employees')

                <div class="mb-2">
                    <x-label>Date</x-label>
                    <x-text-input type="date" name="date" x-model="date" />


                </div>

                <div class="flex justify-end">
                    <x-primary-button @click="view()">View</x-primary-button>
                </div>
                <template x-for="(err, index) in errors" :key="index">
                    <div class="text-xs italic text-red-500" x-text="err"></div>
                </template>
            </x-card>

            <template x-if="show_results">
                <div class="mt-5">
                    <div>
                        Attendance: <span x-text="`${time_in} - ${time_out}`"></span>
                    </div>
                    <x-card>
                        <div>
                            <table class="w-full table-auto">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(schedule, i) in schedules">
                                        <tr>
                                            <td>
                                                <span
                                                    x-text="`${convertTime(schedule.start_time)} - ${convertTime(schedule.end_time)}`"></span>
                                            </td>
                                            <td>
                                                <span x-text="schedule.teaching_attendances[0]?.status"></span>
                                            </td>
                                            <td>
                                                <template x-for="(status) in status_array">
                                                    <x-primary-button class="text-xs" @click="save(i, status)"
                                                        x-text="status"></x-primary-button>
                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </x-card>
                </div>
            </template>
        </div>
    </div>

    @include('_partials.search-employees-js')
</x-app-layout>

<script>
    function checker() {
        return {
            date: '',
            schedules: [],
            time_in: '',
            time_out: '',
            show_results: false,
            status_array: ['Present', 'Late', 'Absent'],
            errors: [],
            view() {
                axios.get('/get-schedules', {
                    params: {
                        employee_id: this.employee.id,
                        date: this.date,
                    }
                }).then(res => {
                    console.log(res.data)
                    const attendances = res.data.attendances
                    if (attendances.length > 0) {
                        this.time_in = convertTime(attendances[0].time);
                        this.time_out = attendances.length > 1 ? convertTime(attendances[attendances.length - 1]
                                .time) :
                            'No Time out';
                    } else {
                        this.time_in = 'No Time in';
                        this.time_out = 'No Time out';
                    }

                    this.schedules = res.data.schedules
                    this.show_results = true
                }).catch(err => {
                    console.log(err.response);
                })
            },
            save(index, status) {
                const formData = new FormData()

                const schedule = this.schedules[index]

                formData.append('teaching_schedule_id', schedule.id)
                formData.append('status', status)
                formData.append('date', this.date)

                axios.post('/checker-attendance', formData)
                    .then(res => {
                        console.log(res.data)
                        this.schedules[index].teaching_attendances[0] = res.data.attendance
                    }).catch(err => {
                        console.log(err.response)
                    })

            },
            clearErrors() {
                this.errors = []
            }
        }
    }
</script>
