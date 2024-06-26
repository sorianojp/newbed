<x-app-layout>
    <x-slot name="title">
        {{ __('Overtime') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Add Overtime') }}
    </x-slot>

    <div class="mx-auto max-w-3xl" x-data="employees">
        <x-auth-session-status :status="session()->get('success')" />
        <div class="my-2 overflow-x-auto sm:rounded-lg" x-data="overtimes">
            <x-card>

                <div class="mb-2">
                    <x-text-input-select name="overtime_types" x-model="overtime_type">
                        <option value="">Select</option>
                        @foreach ($overtimeTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }} {{ $type->rate }}</option>
                        @endforeach
                    </x-text-input-select>
                </div>

                @include('_partials.search-employees')

                <div class="mb-2">
                    <div class="h-40 rounded-md border bg-gray-50">
                        <template x-for="(e, index) in selectedEmployees">
                            <div class="p-1">
                                <div class="z-10 cursor-pointer rounded bg-red-500 p-1 text-xs" x-text="e.full_name"
                                    @click="removeEmployee(index)"></div>
                            </div>
                        </template>
                    </div>
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
                <div class="mb-2">
                    <x-label>Reason</x-label>
                    <x-text-input type="text" name="reason" x-model="reason" />
                </div>
                <div class="flex justify-end">
                    <x-primary-button @click="saveOvertime()">Save</x-primary-button>
                </div>
                <template x-for="(err, index) in errors" :key="index">
                    <div class="text-xs italic text-red-500" x-text="err"></div>
                </template>
            </x-card>

        </div>
    </div>

    @include('_partials.search-employees-js')
</x-app-layout>

<script>
    function overtimes() {
        return {
            overtime_type: '',
            start_time: '',
            end_time: '',
            effective_date: '',
            selectedEmployees: [],
            reason: '',
            errors: [],
            init() {
                this.$watch('employee', value => {
                    if (value) {
                        this.selectedEmployees.push(value)
                        this.search = ''
                        this.employee = ''
                    }
                })
            },
            getEmployees() {
                if (this.search.length >= 2) {
                    console.log(this.search)

                    axios.get('/search-employees', {
                        params: {
                            search: this.search,
                        }
                    }).then(response => {
                        const employees = response.data
                        // Create a set of selected employee IDs for quick lookup
                        const selectedEmployeeIds = new Set(this.selectedEmployees.map(employee => employee
                            .id));

                        // Filter out selected employees from the employees array
                        const filteredEmployees = employees.filter(employee => !selectedEmployeeIds
                            .has(employee
                                .id));
                        this.employees = filteredEmployees

                    })
                } else {
                    this.employees = [];
                }
            },
            clear() {
                this.start_time = ''
                this.end_time = ''
                this.effective_date = ''
            },
            saveOvertime() {
                axios.post('/overtimes', {
                    overtime_type_id: this.overtime_type,
                    employees: this.selectedEmployees.map(e => e.id),
                    date: this.effective_date,
                    start_time: this.start_time,
                    end_time: this.end_time,
                    reason: this.reason,
                }).then(res => {
                    // console.log(res)
                    location.href = '/overtimes'
                }).catch(err => {
                    console.warn(err.response.data)
                })
            },
            removeEmployee(id) {
                this.selectedEmployees.splice(id, 1)
            },
            clearErrors() {
                this.errors = []
            },
        }
    }
</script>
