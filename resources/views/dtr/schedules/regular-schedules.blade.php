<x-app-layout>
    <x-slot name="title">
        {{ __('Regular Schedules') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Regular Schedules') }}
    </x-slot>

    <div class="mx-auto max-w-7xl" x-data="employees">
        <x-auth-session-status :status="session()->get('success')" />
        <div class="my-2 grid grid-cols-3 gap-4 overflow-x-auto sm:rounded-lg" x-data="regularSchedules">
            <x-card>
                @include('_partials.search-employees')
                <div class="mb-2 flex flex-col space-y-0.5">

                    <template x-for="(day, i) in days" :key="i">
                        <div>
                            <div x-show="!is_editing" x-cloak>
                                <label class="inline-flex cursor-pointer items-center">
                                    <input type="checkbox" :value="day" name="days[]" x-model="selected_days"
                                        class="peer sr-only">
                                    <div
                                        class="peer relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rtl:peer-checked:after:-translate-x-full dark:border-gray-600 dark:bg-gray-700 dark:peer-focus:ring-blue-800">
                                    </div>
                                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300"
                                        x-text="day"></span>
                                </label>
                            </div>
                            <div x-show="is_editing" x-cloak>
                                <div class="flex items-center">
                                    <input :id="`default-radio-${i}`" type="radio" :value="day"
                                        name="day_of_week" x-model="selected_day" name="default-radio"
                                        class="h-4 w-4 border-gray-300 bg-gray-100 text-blue-600 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-blue-600">
                                    <label :for="`default-radio-${i}`"
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300"
                                        x-text="day">
                                    </label>
                                </div>
                            </div>
                        </div>


                    </template>
                </div>
                <div class="mb-2">
                    <x-label>Effective Date</x-label>
                    <x-text-input type="date" name="start_date" x-model="effective_date" />
                </div>
                <div class="mb-2">
                    <x-label>Start Time</x-label>
                    <x-text-input type="time" name="start_time" x-model="start_time" />
                </div>
                <div class="mb-2">
                    <x-label>End Time</x-label>
                    <x-text-input type="time" name="end_time" x-model="end_time" />
                </div>
                <div class="flex justify-end">
                    <x-primary-button @click="saveSchedule()">Save</x-primary-button>
                    <x-secondary-button x-show="is_editing" x-cloak @click="cancelEdit()">Cancel</x-secondary-button>
                </div>
                <template x-for="(err, index) in errors" :key="index">
                    <div class="text-xs italic text-red-500" x-text="err"></div>
                </template>
            </x-card>

            <div class="col-span-2" x-show="schedules.length > 0" x-cloak>
                <x-card>
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-3 py-2 uppercase tracking-tight">Day</th>
                                <th class="px-3 py-2 uppercase tracking-tight">Time</th>
                                <th class="px-3 py-2 uppercase tracking-tight">Hours</th>
                                <th class="px-3 py-2 uppercase tracking-tight">Effective Date</th>
                                <th class="px-3 py-2 uppercase tracking-tight">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(sched, index) in schedules" :key="index">
                                <tr>
                                    <td class="px-2 py-1 text-center text-sm" x-text="sched.day_of_week"></td>
                                    <td class="px-2 py-1 text-center text-sm" x-text="sched.time_range"></td>
                                    <td class="px-2 py-1 text-center text-sm" x-text="sched.hours"></td>
                                    <td class="px-2 py-1 text-center text-sm" x-text="sched.start_date"></td>
                                    <td>
                                        <x-primary-button @click="editSchedule(sched)"
                                            class="text-xs">Edit</x-primary-button>
                                        <x-primary-button @click="deleteSchedule(sched.id)"
                                            class="text-xs">Delete</x-primary-button>

                                    </td>
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

    @include('_partials.search-employees-js')
</x-app-layout>

<script>
    function regularSchedules() {
        return {
            is_editing: false,
            days: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            start_time: '',
            end_time: '',
            effective_date: '',
            selected_days: [],
            selected_day: '',
            errors: [],
            schedules: [],
            total_hours_per_day: [],
            total_hours: 0,
            sched_for_editing: '',
            init() {
                this.$watch('employee', value => {
                    this.getSchedules()
                })
            },
            clear() {
                this.is_editing = false;
                this.sched_for_editing = ''
                this.start_time = ''
                this.end_time = ''
                this.effective_date = ''
                this.selected_day = ''
            },
            editSchedule(sched) {
                this.is_editing = true;


                this.sched_for_editing = sched.id
                this.start_time = sched.start_time
                this.end_time = sched.end_time
                this.effective_date = sched.start_date
                this.selected_day = sched.day_of_week
            },
            cancelEdit() {
                this.clear()
            },
            saveSchedule() {
                if (this.is_editing) {
                    axios.put(`/regular-schedules/${this.sched_for_editing}`, {
                        day_of_week: this.selected_day,
                        start_time: this.start_time,
                        end_time: this.end_time,
                        effective_date: this.effective_date,
                    }).then(res => {
                        this.getSchedules()
                        this.clear()
                    }).catch(err => {
                        this.errors = err.response.data.errors
                        setTimeout(() => this.clearErrors(), 3000)
                    })
                } else {
                    axios.post('/regular-schedules', {
                        days: this.selected_days,
                        start_time: this.start_time,
                        end_time: this.end_time,
                        employee_id: this.employee.id,
                        effective_date: this.effective_date,
                    }).then(res => {
                        this.getSchedules()
                    }).catch(err => {
                        this.errors = err.response.data.errors
                        setTimeout(() => this.clearErrors(), 3000)
                    })
                }
            },
            getSchedules() {
                axios.get('/get-regular-schedules', {
                    params: {
                        employee_id: this.employee.id,
                    }
                }).then(res => {
                    console.log(res.data)
                    this.schedules = res.data.schedules
                    this.total_hours_per_day = res.data.total_hours_per_day
                    this.total_hours = res.data.total_hours

                }).catch(err => {
                    console.warn(err.response.data)
                })
            },
            deleteSchedule(id) {
                console.log(id)
                if (!confirm('Are you sure you want to delete this?')) return

                axios.delete(`/regular-schedules/${id}`).then(response => {
                    this.getSchedules()
                }).catch(err => {
                    console.warn(err.response.data)
                })
            },
            clearErrors() {
                this.errors = []
            },
        }
    }
</script>
