<x-app-layout>
    <x-slot name="title">
        {{ __('Attendances') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Attendances') }}
    </x-slot>

    <div class="mx-auto max-w-7xl" x-data="employees">
        <x-auth-session-status :status="session()->get('success')" />
        <div class="my-2 grid grid-cols-2 gap-4 overflow-x-auto sm:rounded-lg" x-data="attendances">
            <x-card>
                @include('_partials.search-employees')

                <div class="mb-2 grid grid-cols-2 gap-2">

                    <div>
                        <x-label>Date</x-label>
                        <x-text-input type="date" name="start_date" x-model="start_date" />
                    </div>
                    <div>
                        <x-label>End Date (optional)</x-label>
                        <x-text-input type="date" name="end_date" x-model="end_date" />
                    </div>
                </div>
                <div class="mb-2 grid grid-cols-2 gap-2">
                    <div>
                        <x-label>Time</x-label>
                        <x-text-input type="time" name="start_time" x-model="start_time" />
                    </div>
                    <div>

                        <x-label>End Time (optional)</x-label>
                        <x-text-input type="time" name="end_time" x-model="end_time" />
                    </div>
                </div>
                <div class="mb-2 flex flex-col space-y-0.5">
                    <template x-for="(day, i) in days" :key="i">
                        <label class="inline-flex cursor-pointer items-center">
                            <input type="checkbox" :value="day" name="days[]" x-model="selected_days"
                                class="peer sr-only">
                            <div
                                class="peer relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rtl:peer-checked:after:-translate-x-full dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-blue-800">
                            </div>
                            <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                x-text="day"></span>
                        </label>
                    </template>
                </div>
                <div class="flex justify-end">
                    <x-primary-button @click="saveAttendance()">Save</x-primary-button>
                </div>
                <template x-for="(err, index) in errors" :key="index">
                    <div class="text-xs italic text-red-500" x-text="err"></div>
                </template>
            </x-card>

            <div x-show="attendances.length > 0" x-cloak>
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
            days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            selected_days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
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
                    end_time: this.end_time,
                    days: this.selected_days,
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
