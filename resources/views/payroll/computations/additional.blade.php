<x-app-layout>
    <x-slot name="title">Post Additional</x-slot>
    <x-slot name="header">Post Additional</x-slot>

    <div x-data="employees">
        <div class="mx-auto max-w-7xl" x-data="additionals">
            <div class="mx-auto max-w-3xl">
                <x-card>
                    @include('_partials.search-employees')
                    <div class="mb-2">
                        <x-label>Salary Month</x-label>
                        <div class="mb-2 grid grid-cols-2 gap-2">
                            <div>
                                <x-label>Month</x-label>
                                <x-text-input-select name="month" x-model="month">
                                    @foreach ($months as $month)
                                        <option value="{{ $month }}">{{ $month }}</option>
                                    @endforeach
                                </x-text-input-select>
                            </div>
                            <div>
                                <x-label>Year</x-label>
                                <x-text-input type="number" name="year" value="{{ date('Y') }}"
                                    x-model="year" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <x-label>Schedule</x-label>
                        <x-text-input-select x-model="schedule_id">
                            <template x-for="(sched, i) in schedules" :key="i">
                                <option :value="sched.id" x-text="sched.schedule_name"></option>
                            </template>
                        </x-text-input-select>
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button @click="view()">View</x-primary-button>
                    </div>
                </x-card>
            </div>

            <template x-if="show_results">
                <div>
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('image/placeholder.png') }}" alt="" class="h-24 w-24 border">
                        <div class="text-sm tracking-tighter">
                            <div>Salary Period: <span x-text="`${schedule.cutoff} (${schedule.period})`"></span> </div>
                            <div>Name: <span x-text="selectedEmployee.full_name"></span> </div>
                            <div>Office/Department: </div>
                        </div>
                    </div>

                    <div>
                        <h2>Add Additionals</h2>
                        <div class="mb-2">
                            <x-label>Additional</x-label>
                            <x-text-input-select x-model="additional_id">
                                <option value="">Select One</option>
                                @foreach ($additionals as $additional)
                                    <option value="{{ $additional->id }}">
                                        {{ "{$additional->name} ({$additional->code})" }}</option>
                                @endforeach
                            </x-text-input-select>
                        </div>

                        <div class="mb-2">
                            <x-label>Amount</x-label>
                            <x-text-input type="number" x-model="amount"></x-text-input>
                        </div>

                        <div class="mb-2">
                            <x-label>Remark</x-label>
                            <x-text-input x-model="remark"></x-text-input>
                        </div>

                        <div class="mb-2">
                            <x-primary-button @click="save()">Save</x-primary-button>
                        </div>
                    </div>

                    <div>
                        <h2>Posted Additionals</h2>
                        <table class="w-full table-auto">
                            <thead>
                                <tr>
                                    <th class="px-6 text-left">Date</th>
                                    <th class="px-6 text-left">Additional(s)</th>
                                    <th class="px-6 text-left">Amount</th>
                                    <th class="px-6 text-left">Encoded by</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(addition, index) in additionals">
                                    <tr>
                                        <td class="px-6"></td>
                                        <td class="px-6"
                                            x-text="`${addition.additional.name} (${addition.additional.code})`"></td>
                                        <td class="px-6" x-text="formatNumber(addition.amount)"></td>
                                        <td class="px-6"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

        </div>
    </div>


    @include('_partials.search-employees-js')

</x-app-layout>

<script>
    function additionals() {
        return {
            month: 'January',
            year: new Date().getFullYear(),
            schedules: [],
            schedule_id: '',
            schedule: null,
            selectedEmployee: null,
            additionals: [],
            show_results: false,
            additional_id: '',
            amount: '',
            remark: '',
            init() {
                this.$watch('employee', value => {
                    this.getSchedules();
                })

                this.$watch('month', value => {
                    if (this.employee)
                        this.getSchedules();
                })

                this.$watch('year', value => {
                    if (this.employee)
                        this.getSchedules();
                })
            },
            getSchedules() {

                axios.get('/payroll/schedules/get', {
                    params: {
                        month: this.month,
                        year: this.year,
                        employee: this.employee.id,
                    }
                }).then(res => {
                    this.schedules = res.data
                    console.log(this.schedules)
                    this.schedule_id = this.schedules[0].id
                    this.additions = []
                    this.show_results = false
                }).catch(err => {
                    console.warn(err.response)
                })
            },
            view() {
                this.schedule = this.schedules.find((s) => s.id == this.schedule_id)

                axios.get('/payroll/additionals/get', {
                    params: {
                        employee_id: this.employee.id,
                        payroll_schedule_id: this.schedule_id,
                    }
                }).then(res => {
                    console.log(res.data)
                    this.selectedEmployee = res.data.employee
                    this.additionals = res.data.additionals
                    this.show_results = true
                }).catch(err => {
                    console.warn(err.response)
                })
            },
            save() {
                const formData = new FormData()

                formData.append('employee_id', this.employee.id)
                formData.append('payroll_schedule_id', this.schedule_id)
                formData.append('additional_id', this.additional_id)
                formData.append('amount', this.amount)
                formData.append('remark', this.remark)

                axios.post('/payroll/additionals/employee', formData)
                    .then(res => {
                        console.log(res)
                        this.additionals.push(res.data.additional)
                    }).catch(err => {
                        console.log(err.response)
                    })
            }
        }
    }
</script>
