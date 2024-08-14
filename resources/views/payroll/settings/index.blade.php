<x-app-layout>
    <x-slot name="title">Employee Settings</x-slot>
    <x-slot name="header">Employee Settings</x-slot>

    <div x-data="employees">
        <div class="mx-auto max-w-7xl" x-data="employeeConfig">
            <x-card>
                @include('_partials.search-employees')
                <div class="flex justify-end">
                    <x-primary-button @click="viewConfig()">View</x-primary-button>
                </div>
            </x-card>

            <template x-if="result">
                <div class="mt-5">
                    <template x-if="configuration">
                        <table class="w-full table-auto">
                            <thead>
                                <tr>
                                    <th>Salary Period</th>
                                    <th>Computation</th>
                                    <th>Payroll</th>
                                    <th>Basic Rate</th>
                                    <th>Hourly</th>
                                    <th>Teaching</th>
                                    <th>Tax</th>
                                    <th>SSS</th>
                                    <th>PG</th>
                                    <th>PH</th>
                                    <th>Holi</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center capitalize" x-text="configuration.salary_period"></td>
                                    <td class="text-center capitalize" x-text="configuration.computation_basis"></td>
                                    <td class="text-center" x-text="configuration.payroll_type.name"></td>
                                    <td class="text-center"
                                        x-text="`${configuration.monthly_basic_salary} (${configuration.cut_off_days_per_month})`">
                                    </td>
                                    <td class="text-center" x-text="configuration.hourly_rate"></td>
                                    <td class="text-center" x-text="configuration.teaching_rate"></td>
                                    <td x-text="configuration.tax || 'None'"></td>
                                    <td>
                                        <template x-if="configuration.sss == 1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 text-green-500">
                                                <path fill-rule="evenodd"
                                                    d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="configuration.sss == 0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 text-red-500">
                                                <path fill-rule="evenodd"
                                                    d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </td>
                                    <td>
                                        <template x-if="configuration.pag_ibig == 1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 text-green-500">
                                                <path fill-rule="evenodd"
                                                    d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="configuration.pag_ibig == 0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 text-red-500">
                                                <path fill-rule="evenodd"
                                                    d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </td>
                                    <td>
                                        <template x-if="configuration.phil_health == 1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 text-green-500">
                                                <path fill-rule="evenodd"
                                                    d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="configuration.phil_health == 0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 text-red-500">
                                                <path fill-rule="evenodd"
                                                    d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </td>
                                    <td>
                                        <template x-if="configuration.holiday_pay == 1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 text-green-500">
                                                <path fill-rule="evenodd"
                                                    d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                        <template x-if="configuration.holiday_pay == 0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="currentColor" class="size-5 text-red-500">
                                                <path fill-rule="evenodd"
                                                    d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </template>
                                    </td>
                                    <td>
                                        <x-primary-button @click="edit()">Edit</x-primary-button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <template x-if="!configuration">
                        <div>
                            Add Configuration
                        </div>
                    </template>
                    <div class="mt-5">


                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('image/placeholder.png') }}" alt="" class="h-24 w-24 border">
                            <div class="text-sm tracking-tighter">
                                <div>Name: <span x-text="selectedEmployee.full_name"></span> </div>
                                <div>Office/Department: </div>
                            </div>
                        </div>

                        <h2>Salary Rate Info</h2>

                        <hr>

                        <div class="mt-5 space-y-2">
                            <div>
                                <x-label>Salary Period</x-label>
                                <x-text-input-select name="salary_period" x-model="salary_period">
                                    <option value="">Select One</option>
                                    <option value="bi-monthly">Bi-Monthly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="daily">Daily</option>
                                </x-text-input-select>
                            </div>
                            <div>
                                <x-label>Payroll Schedule Type</x-label>
                                <x-text-input-select name="payroll_type_id" x-model="payroll_type_id">
                                    <option value="">Select One</option>
                                    @foreach ($payrollTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </x-text-input-select>
                            </div>
                            <div>
                                <x-label>Salary Computation Basis</x-label>
                                <x-text-input-select name="computation_basis" x-model="computation_basis">
                                    <option value="">Select One</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="hourly">Hourly</option>
                                </x-text-input-select>
                            </div>
                            <div class="mb-2 grid grid-cols-2 gap-2">
                                <div>
                                    <x-label>Basic Salary (Monthly)</x-label>
                                    <x-text-input type="number" name="monthly_basic_salary" value="0"
                                        x-model="monthly_basic_salary" />
                                </div>
                                <div>
                                    <x-label>&nbsp;</x-label>
                                    <x-text-input type="number" name="cut_off_days_per_month" value="22"
                                        x-model="cut_off_days_per_month" />
                                </div>
                            </div>
                            <div>
                                <x-label>Hourly</x-label>
                                <x-text-input type="number" name="hourly_rate" value="0"
                                    x-model="hourly_rate" />
                            </div>
                            <div>
                                <x-label>Teaching Rate</x-label>
                                <x-text-input type="number" name="teaching_rate" value="0"
                                    x-model="teaching_rate" />
                            </div>
                        </div>

                        <h2>Tax Status and Contribution</h2>

                        <hr>

                        <div class="mt-5 space-y-2">
                            <div>
                                <x-label>Tax</x-label>
                                <x-text-input-select name="tax" x-model="tax">
                                    <option value="">None</option>
                                    <option value="TRAIN">TRAIN</option>
                                </x-text-input-select>
                            </div>
                            <div>
                                <x-label>SSS</x-label>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="sss" value="1" x-model="sss"
                                        id="sss_yes"><x-label for="sss_yes">Yes</x-label>
                                </div>
                                <div class="flex items-center space-x-2">

                                    <input type="radio" name="sss" value="0" x-model="sss"
                                        id="sss_no"><x-label for="sss_no">No</x-label>
                                </div>
                            </div>

                            <div>
                                <x-label>Pag-ibig</x-label>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="pagibig" value="1" x-model="pag_ibig"
                                        id="pagibig_yes"><x-label for="pagibig_yes">Yes</x-label>
                                </div>
                                <div class="flex items-center space-x-2">

                                    <input type="radio" name="pagibig" value="0" x-model="pag_ibig"
                                        id="pagibig_no"><x-label for="pagibig_no">No</x-label>
                                </div>
                            </div>

                            <div>
                                <x-label>PhilHealth</x-label>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="ph" value="1" x-model="phil_health"
                                        id="ph_yes"><x-label for="ph_yes">Yes</x-label>
                                </div>
                                <div class="flex items-center space-x-2">

                                    <input type="radio" name="ph" value="0" x-model="phil_health"
                                        id="ph_no"><x-label for="ph_no">No</x-label>
                                </div>
                            </div>

                            <div>
                                <x-label>Holiday with Pay</x-label>
                                <div class="flex items-center space-x-2">
                                    <input type="radio" name="holi" value="1" x-model="holiday_pay"
                                        id="holi_yes"><x-label for="holi_yes">Yes</x-label>
                                </div>
                                <div class="flex items-center space-x-2">

                                    <input type="radio" name="holi" value="0" x-model="holiday_pay"
                                        id="holi_no"><x-label for="holi_no">No</x-label>
                                </div>
                            </div>

                            <div class="mt-2 flex justify-end">
                                <x-primary-button @click="save()">Save</x-primary-button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    @include('_partials.search-employees-js')
</x-app-layout>

<script>
    function employeeConfig() {
        return {
            result: null,
            selectedEmployee: null,
            configuration: null,
            salary_period: '',
            payroll_type_id: '',
            computation_basis: '',
            monthly_basic_salary: 0,
            cut_off_days_per_month: 22,
            hourly_rate: 0,
            teaching_rate: 0,
            tax: '',
            sss: true,
            pag_ibig: true,
            phil_health: true,
            holiday_pay: true,
            viewConfig() {
                axios.get('/payroll/settings/get-employee-settings', {
                    params: {
                        employee_id: this.employee.id
                    }
                }).then(res => {
                    console.log(res.data)
                    this.result = true
                    this.selectedEmployee = this.employee
                    this.configuration = res.data.configuration
                }).catch(err => {
                    this.result = false
                    console.log(err.response)
                })
            },
            edit() {
                this.salary_period = this.configuration.salary_period
                this.payroll_type_id = this.configuration.payroll_type_id
                this.computation_basis = this.configuration.computation_basis
                this.monthly_basic_salary = this.configuration.monthly_basic_salary
                this.cut_off_days_per_month = this.configuration.cut_off_days_per_month
                this.hourly_rate = this.configuration.hourly_rate
                this.teaching_rate = this.configuration.teaching_rate
                this.tax = this.configuration.tax
                this.sss = this.configuration.sss
                this.pag_ibig = this.configuration.pag_ibig
                this.phil_health = this.configuration.phil_health
                this.holiday_pay = this.configuration.holiday_pay
            },
            clear() {
                this.salary_period = ''
                this.payroll_type_id = ''
                this.computation_basis = ''
                this.monthly_basic_salary = 0
                this.cut_off_days_per_month = 22
                this.hourly_rate = 0
                this.teaching_rate = 0
                this.tax = ''
                this.sss = true
                this.pag_ibig = true
                this.phil_health = true
                this.holiday_pay = true
            },
            save() {
                const formData = new FormData()
                formData.append('employee_id', this.selectedEmployee.id)
                formData.append('salary_period', this.salary_period)
                formData.append('payroll_type_id', this.payroll_type_id)
                formData.append('computation_basis', this.computation_basis)
                formData.append('monthly_basic_salary', this.monthly_basic_salary)
                formData.append('cut_off_days_per_month', this.cut_off_days_per_month)
                formData.append('hourly_rate', this.hourly_rate)
                formData.append('teaching_rate', this.teaching_rate)
                formData.append('tax', this.tax)
                formData.append('sss', this.sss)
                formData.append('pag_ibig', this.pag_ibig)
                formData.append('phil_health', this.phil_health)
                formData.append('holiday_pay', this.holiday_pay)
                formData.append('_method', 'put')

                console.log(formData)

                axios.post('/payroll/employee-settings',
                        formData
                    )
                    .then(res => {
                        console.log(res.data)
                        this.configuration = res.data.configuration
                        alert('success')
                        this.clear()
                    }).catch(err => {
                        alert('Please fill up form')
                        console.log(err.response)
                    })
            },
        }
    }
</script>
