<x-app-layout>
    <x-slot name="title">
        {{ __('Overtime') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Overtime') }}
    </x-slot>

    <div class="mx-auto max-w-3xl" x-data="employees">
        <div class="flex justify-end space-x-2">
            <a href="{{ route('overtimes.create') }}">
                <x-primary-button>Create Overtime</x-primary-button>
            </a>
        </div>
        <x-auth-session-status :status="session()->get('success')" />

        <div class="relative my-2 overflow-x-auto sm:rounded-lg" x-data="overtime">
            <x-card>

                @include('_partials.search-employees')

                <div class="mb-2">
                    <x-label>Start Date</x-label>
                    <x-text-input type="date" name="start_date" x-model="start_date" />
                </div>
                <div class="mb-2">
                    <x-label>End Date</x-label>
                    <x-text-input type="date" name="start_date" x-model="end_date" />
                </div>
                <div class="flex justify-end">
                    <x-primary-button @click="getOvertimes()">Search</x-primary-button>
                </div>
            </x-card>

            <div class="mt-2" x-show="overtimes.length > 0" x-cloak>
                <x-card>
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-slate-300">
                                <th class="px-3 py-2">Date</th>
                                <th class="px-3 py-2">Employees</th>
                                <th class="px-3 py-2">Reason</th>
                                <th class="px-3 py-2">
                                    <span class="sr-only">Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(o, index) in overtimes" :key="index">
                                <tr>
                                    <td class="px-2 py-1 text-center" x-text="o.date"></td>
                                    <td class="px-2 py-1 text-center">
                                        <ul class="list-disc">
                                            <template x-for="(e, i) in o.employees" :key="`e` + e.id">
                                                <li class="text-xs" x-text="e.full_name"></li>
                                            </template>
                                        </ul>
                                    </td>
                                    <td class="px-2 py-1 text-center" x-text="o.reason"></td>
                                    <td>
                                        <a :href="`/overtimes/${o.id}/edit`">
                                            <x-primary-button>Edit</x-primary-button>
                                        </a>
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
    function overtime() {
        return {
            start_date: "",
            end_date: "",
            overtimes: [],
            getOvertimes() {
                if (this.start_date && this.employee) {

                    axios.get('/get-overtime', {
                        params: {
                            employee_id: this.employee.id,
                            start_date: this.start_date,
                            end_date: this.end_date,
                        }
                    }).then(res => {
                        console.log(res.data)
                        this.overtimes = res.data
                    }).catch(err => {
                        console.warn(err.response.data)
                    })
                }
            }
        }
    }
</script>
