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

                <div class="mb-2">
                    <x-label>Date</x-label>
                    <x-text-input type="date" name="date" x-model="date" />
                </div>
                <div class="mb-2">
                    <x-label>Time</x-label>
                    <x-text-input type="time" name="time" x-model="time" />
                </div>
                <div class="flex justify-end">
                    <x-primary-button @click="saveAttendance()">Save</x-primary-button>
                </div>
                <template x-for="(err, index) in errors" :key="index">
                    <div class="text-xs italic text-red-500" x-text="err"></div>
                </template>
            </x-card>

            <div class="mt-5" x-show="attendances.length > 0" x-cloak>
                <x-card>
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="px-3 py-2">Time</th>
                                <th class="px-3 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(a,i) in attendances">
                                <tr>
                                    <td class="px-2 py-1 text-center" x-text="window.convertTime(a.time)"></td>
                                    <td class="px-2 py-1 text-center">
                                        <x-secondary-button @click="deleteAttendance(a.id)">Delete</x-secondary-button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </x-card>
            </div>

        </div>
    </div>

    @include('_partials.search-employees-js')
</x-app-layout>

<script>
    function attendances() {
        return {
            date: '',
            time: '',
            attendances: [],
            errors: [],
            init() {
                this.$watch('employee', (value) => {
                    this.getAttendances()
                })

                this.$watch('date', (value) => {
                    this.getAttendances()
                })
            },
            getAttendances() {
                if (this.employee.id && this.date) {
                    axios.get('/get-attendances', {
                        params: {
                            employee_id: this.employee.id,
                            date: this.date
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
                    date: this.date,
                    time: this.time
                }).then(response => {
                    console.log(response.data)
                    this.getAttendances()
                    this.time = ''
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
