<x-app-layout>
    <x-slot name="title">
        {{ __('Attendances') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Attendances') }}
    </x-slot>

    <div class="mx-auto max-w-xl" x-data="employees">
        <x-auth-session-status :status="session()->get('success')" />
        <div class="my-2 overflow-x-auto sm:rounded-lg" x-data="attendances">
            <x-card>
                @include('_partials.search-employees')

                <div class="mb-2 grid grid-cols-2 gap-2">

                    <div>
                        <x-label>Start Date</x-label>
                        <x-text-input type="date" name="start_date" x-model="start_date" />
                    </div>
                    <div>
                        <x-label>End Date (optional)</x-label>
                        <x-text-input type="date" name="end_date" x-model="end_date" />
                    </div>
                </div>
                <div class="mb-2 grid grid-cols-2 gap-2">
                    <div>
                        <x-label>Start Time</x-label>
                        <x-text-input type="time" name="start_time" x-model="start_time" />
                    </div>
                    <div>

                        <x-label>End Time (optional)</x-label>
                        <x-text-input type="time" name="end_time" x-model="end_time" />
                    </div>
                </div>
                <div class="flex justify-end">
                    <x-primary-button @click="saveAttendance()">Save</x-primary-button>
                </div>
                <template x-for="(err, index) in errors" :key="index">
                    <div class="text-xs italic text-red-500" x-text="err"></div>
                </template>
            </x-card>

            <div class="mt-5" x-show="attendances.length > 0" x-cloak>
                <div class="max-h-80 space-y-2 overflow-y-auto">
                    <template x-for="(a,i) in attendances">
                        <x-card>
                            <div>
                                <h2 x-text="a.date"></h2>
                                <table class="w-full table-auto">
                                    <thead>
                                        <tr>
                                            <th class="px-3 py-2">Time</th>
                                            <th class="px-3 py-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="(attendance,i) in a.attendances">
                                            <tr>
                                                <td class="px-2 py-1 text-center"
                                                    x-text="window.convertTime(attendance.time)">
                                                </td>
                                                <td class="px-2 py-1 text-center">
                                                    <x-secondary-button
                                                        @click="deleteAttendance(attendance.id)">Delete</x-secondary-button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </x-card>
                    </template>
                </div>
            </div>
        </div>
    </div>

    @include('_partials.search-employees-js')
</x-app-layout>

<script>
    function attendances() {
        return {
            start_date: '',
            end_date: '',
            start_time: '',
            end_time: '',
            attendances: [],
            errors: [],
            init() {
                this.$watch('employee', (value) => {
                    this.getAttendances()
                })

                this.$watch('start_date', (value) => {
                    this.getAttendances()
                })

                this.$watch('end_date', (value) => {
                    this.getAttendances()
                })
            },
            getAttendances() {
                if (this.employee.id && (this.start_date || (this.start_date && this.end_date))) {
                    axios.get('/get-attendances', {
                        params: {
                            employee_id: this.employee.id,
                            start_date: this.start_date,
                            end_date: this.end_date,
                        }
                    }).then(response => {
                        console.log(response.data)
                        this.attendances = response.data
                    }).catch(err => {
                        console.log(err.response.data)
                    })
                }
            },
            saveAttendance() {
                axios.post('/attendances', {
                    employee_id: this.employee.id,
                    start_date: this.start_date,
                    end_date: this.end_date,
                    start_time: this.start_time,
                    end_time: this.end_time
                }).then(response => {
                    console.log(response.data)
                    this.getAttendances()
                    this.end_date = ''
                    this.start_time = ''
                    this.end_time = ''
                }).catch(err => {
                    this.errors = err.response.data.errors
                    setTimeout(() => this.clearErrors(), 3000)
                })
            },
            deleteAttendance(attendanceId) {

                if (!confirm('Are you sure you want to delete this?')) return

                axios.delete(`/attendances/${attendanceId}`).then(response => {
                    this.getAttendances()
                }).catch(err => {
                    console.warn(err.response.data)
                })
            },
            clearErrors() {
                this.errors = []
            }
        }
    }
</script>
