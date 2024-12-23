<x-app-layout>
    <x-slot name="title">Payroll</x-slot>
    <x-slot name="header">Payroll</x-slot>

    <div x-data="employees">
        <div class="relative mx-auto max-w-7xl" x-data="payrollSchedules">
            <div class="mx-auto max-w-lg">
                <x-card>
                    @include('_partials.search-employees')
                    <div class="mb-2">
                        <x-label>Salary Month</x-label>
                        <div class="mb-2 grid grid-cols-2 gap-2">
                            <div>
                                <x-label>Month</x-label>
                                <x-text-input-select name="month" x-model="month">
                                    @foreach ($months as $month)
                                        <option value="{{ $month }}">
                                            {{ $month }}</option>
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
                        <x-primary-button @click="viewComputation()">View</x-primary-button>
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

                    <div class="mt-4 flex justify-between text-sm">
                        <span>
                            <span class="font-semibold">Basic:</span>
                            <span
                                x-text="`${formatNumber(selectedEmployee.employee_setting.monthly_basic_salary)}`"></span>
                        </span>

                        <span>
                            <span class="font-semibold">Rate/Hr:</span> <span
                                x-text="`${formatNumber(selectedEmployee.employee_setting.hourly_rate)}`"></span>
                        </span>
                        <span>
                            <span class="font-semibold">Teaching Rate:</span> <span
                                x-text="`${formatNumber(selectedEmployee.employee_setting.teaching_rate)}`"></span>
                        </span>
                    </div>

                    <div>
                        <x-primary-button class="bg-green-500 p-1 text-xs hover:bg-green-300"
                            @click="openViewDtrModal()">View DTR</x-primary-button>
                        <x-primary-button class="bg-teal-500 p-1 text-xs hover:bg-teal-300"
                            @click="openWorkingDtrModal()">View Working
                            Time</x-primary-button>
                    </div>

                    <div class="grid grid-cols-4 gap-4 text-sm">
                        <div class="col-span-2">
                            <h2 class="font-semibold">Salary Compensation</h2>
                            <table class="w-full table-auto">
                                <tr x-show="salary.total_expected_staff_hours != 0">

                                    <td class="whitespace-nowrap pr-4 text-left">Basic Salary:</td>
                                    <td class=""
                                        x-text="`${formatNumber(salary.total_staff_hours)}/${formatNumber(salary.total_expected_staff_hours)}` ">
                                    </td>
                                    <td class="w-60 text-right" x-text="`${formatNumber(salary.expected_staff_pay)}`">
                                    </td>
                                </tr>

                                <tr x-show="salary.total_expected_teaching_hours != 0">
                                    <td class="whitespace-nowrap pr-4 text-left">Teaching Salary:</td>
                                    <td class=""
                                        x-text="`${formatNumber(salary.total_expected_teaching_hours)} x ${selectedEmployee.employee_setting.teaching_rate}` ">
                                    </td>
                                    <td class="w-60 text-right"
                                        x-text="`${formatNumber(salary.expected_teaching_pay)}`"></td>
                                </tr>

                                <tr x-show="salary.overtimes.length > 0">
                                    <td class="whitespace-nowrap pr-4 text-left">Overtime:</td>
                                    <td class=""">
                                    </td>
                                    <td class="w-60 text-right" x-text="`${formatNumber(salary.overtime_pay)}`"></td>
                                </tr>

                                <template x-if="salary.included_additionals.length > 0">
                                    <template x-for="(ia, index) in salary.included_additionals">
                                        <tr>
                                            <td class="whitespace-nowrap pr-4 text-left" x-text="ia.additional.name">
                                            </td>
                                            <td class="text-left">&nbsp;</td>
                                            <td class="w-60 text-right" x-text="formatNumber(ia.amount)">
                                            </td>
                                        </tr>
                                    </template>
                                </template>

                                <template x-if="salary.taxable_deductions.length > 0">
                                    <template x-for="(td, index) in salary.taxable_deductions">
                                        <tr>
                                            <td class="whitespace-nowrap pr-4 text-left" x-text="td.deduction.name">
                                            </td>
                                            <td class="text-left">&nbsp;</td>
                                            <td class="w-60 text-right" x-text="`(${formatNumber(td.amount)})`">
                                            </td>
                                        </tr>
                                    </template>
                                </template>

                                <tr>
                                    <td class="whitespace-nowrap pr-4 text-left">Absent:</td>
                                    <td class=""
                                        x-text="`${formatNumber(salary.absent_days)} (${formatNumber(salary.absent_hours_staff)})`">
                                    </td>
                                    <td class="w-60 text-right" x-text="`(${formatNumber(salary.absent_deduction)})`">
                                    </td>
                                </tr>

                                <tr>

                                    <td class="whitespace-nowrap pr-4 text-left">Tardiness: (>15mins = 1hr)</td>
                                    <td class="" x-text="`${formatNumber(salary.total_tardiness)} hrs`"></td>
                                    <td class="w-60 text-right" x-text="`(${formatNumber(salary.late_deduction)})`">
                                    </td>
                                </tr>

                                <tr>
                                    <td class="whitespace-nowrap pr-4 text-left">Gross Salary:</td>
                                    <td class="">&nbsp;</td>
                                    <td class="w-60 text-right" x-text="`${formatNumber(salary.gross_salary)}`"></td>
                                </tr>
                            </table>

                            <hr>


                            <div>
                                <div class="flex items-stretch justify-between">
                                    <span>Net Pay:</span>
                                    <span x-text="`Php ${formatNumber(salary.net_pay)}`"></span>
                                </div>
                            </div>


                        </div>

                        <div>
                            <h2 class="font-semibold">Additionals</h2>
                            <table class="w-full table-auto">
                                <template x-if="salary.not_included_additionals.length > 0">
                                    <template x-for="(nia, index) in salary.not_included_additionals">
                                        <tr>
                                            <td class="whitespace-nowrap" x-text="nia.additional.name"></td>
                                            <td class="w-60 text-right" x-text="formatNumber(nia.amount)"></td>
                                        </tr>
                                    </template>
                                </template>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="w-60 text-right"
                                        x-text="formatNumber(salary.not_included_additionals.reduce((sum, a) => sum + a.amount,0 ))">
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <div>
                            <h2 class="font-semibold">Deduction</h2>
                            <table class="w-full table-auto">
                                <template x-if="salary.non_taxable_deductions.length > 0">
                                    <template x-for="(ntd, index) in salary.non_taxable_deductions">
                                        <tr>
                                            <td class="whitespace-nowrap" x-text="ntd.name"></td>
                                            <td class="w-60 text-right" x-text="formatNumber(ntd.amount)"></td>
                                        </tr>
                                    </template>

                                </template>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td class="w-60 text-right"
                                        x-text="formatNumber(salary.non_taxable_deductions.reduce((sum, a) => sum + a.amount,0 ))">
                                    </td>
                                </tr>
                            </table>
                        </div>


                    </div>
                    <hr class="my-2">

                    <div>
                        <x-primary-button @click="createPayslip()">Create Payslip</x-primary-button>
                    </div>
                </div>

            </template>
            @include('payroll.computations._partials.working-dtr')
            @include('payroll.computations._partials.view-dtr')
            {{-- @include('payroll.computations._partials.sample') --}}
        </div>
    </div>

    @include('_partials.search-employees-js')
</x-app-layout>

<script>
    function payrollSchedules() {
        return {
            month: '',
            year: '',
            schedules: [],
            schedule_id: '',
            schedule: null,
            selectedEmployee: null,
            salary: null,
            show_results: false,
            open_working_dtr_modal: false,
            open_view_dtr_modal: false,
            working_schedules: [],
            total_hours_per_day: [],
            total_hours: 0,
            attendances: [],
            init() {
                const date = new Date();

                this.year = date.getFullYear();
                this.month = date.toLocaleString('default', {
                    month: 'long'
                });

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
                    // this.show_results = false
                    this.schedule_id = this.schedules[0].id
                    // this.salary = null
                }).catch(err => {
                    console.warn(err.response)
                })
            },
            viewComputation() {
                this.schedule = this.schedules.find((s) => s.id == this.schedule_id)
                console.log(this.schedule)
                axios.get('/payroll/individual/computations', {
                        params: {
                            employee_id: this.employee.id,
                            schedule_id: this.schedule.id,
                        },
                        validateStatus: function(status) {
                            return status >= 200; // default
                        },
                    }).then(res => {
                        console.log(res.data)

                        this.selectedEmployee = res.data.employee
                        this.salary = res.data.compensations
                        this.show_results = true
                    })
                    .catch(error => {
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            console.log(error.response.data);
                            console.log(error.response.status);
                            console.log(error.response.headers);
                        } else if (error.request) {
                            // The request was made but no response was received
                            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                            // http.ClientRequest in node.js
                            console.log(error.request);
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            console.log('Error', error.message);
                        }
                        console.log(error.config);
                    })
            },
            openWorkingDtrModal() {

                axios.get('/dtr/get-working-schedules', {
                    params: {
                        employee_id: this.employee.id,
                        schedule_id: this.schedule.id,
                    },
                    validateStatus: function(status) {
                        return status >= 200; // default
                    },
                }).then(res => {
                    console.log(res.data);
                    this.open_working_dtr_modal = true;
                    this.working_schedules = res.data.schedules
                    this.total_hours_per_day = res.data.total_hours_per_day
                    this.total_hours = res.data.total_hours
                }).catch(error => {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log(error.response.data);
                        console.log(error.response.status);
                        console.log(error.response.headers);
                    } else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log(error.request);
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                    }
                    console.log(error.config);
                })
            },
            openViewDtrModal() {
                axios.get('/get-attendances', {
                    params: {
                        employee_id: this.employee.id,
                        start_date: this.schedule.cutoff_start_date,
                        end_date: this.schedule.cutoff_end_date,
                    }
                }).then(res => {
                    console.log(res.data)
                    this.attendances = res.data
                    this.open_view_dtr_modal = true
                }).catch(error => {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log(error.response.data);
                        console.log(error.response.status);
                        console.log(error.response.headers);
                    } else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        console.log(error.request);
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        console.log('Error', error.message);
                    }
                    console.log(error.config);
                })
            },
            createPayslip() {
                const formData = new FormData()

                // formData.append('salary', this.salary)
                formData.append('payroll_schedule_id', this.schedule.id)
                formData.append('employee_id', this.employee.id)
                formData.append('basic_rate', this.salary.basic_rate)
                formData.append('teaching_rate', this.salary.teaching_rate)
                formData.append('total_hours', this.salary.total_staff_hours)
                formData.append('working_hours', this.salary.total_expected_staff_hours)
                formData.append('teaching_hours', this.salary.total_teaching_hours)
                formData.append('total_teaching_hours', this.salary.total_expected_teaching_hours)
                formData.append('absent_days', this.salary.absent_days)
                formData.append('absent_amount', this.salary.absent_deduction)
                formData.append('tardiness_hours', this.salary.lateness_hours_staff)
                formData.append('tardiness_amount', this.salary.late_deduction)
                formData.append('overtime_hours', this.salary.overtime_hours)
                formData.append('overtime_amount', this.salary.overtime_pay)
                formData.append('holiday_hours', this.salary.holiday_hours)
                formData.append('holiday_amount', this.salary.holiday_pay)
                formData.append('gross_salary', this.salary.gross_salary)
                formData.append('included_additionals', JSON.stringify(this.salary.included_additionals) ?? null)
                formData.append('not_included_additionals', JSON.stringify(this.salary.not_included_additionals) ??
                    null)
                formData.append('taxable_deductions', JSON.stringify(this.salary.taxable_deductions) ?? null)
                formData.append('not_taxable_deductions', JSON.stringify(this.salary.not_taxable_deductions) ?? null)
                formData.append('wtax', this.salary.wTax)
                formData.append('sss', this.salary.sss)
                formData.append('philhealth', this.salary.philhealth)
                formData.append('pagibig', this.salary.pagibig)
                formData.append('net_salary', this.salary.net_pay)

                axios.post('/payroll/individual/create-payslip', formData)
                    .then(res => {
                        alert(res.data.message)
                    }).catch(err => {
                        console.log(err.response);
                    })
            },
        }
    }
</script>
