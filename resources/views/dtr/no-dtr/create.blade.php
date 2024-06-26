<x-app-layout>
    <x-slot name="title">
        {{ __('Create No DTR') }}
    </x-slot>
    <x-slot name="header">
        {{ __('Create No DTR') }}
    </x-slot>
    <div class="mx-auto max-w-xl" x-data="employees">
        <x-auth-session-status :status="session()->get('success')" />
        <x-auth-validation-errors />
        <x-card>
            <form method="POST" action="{{ route('no-dtr.store') }}">
                @csrf
                <div class="space-y-2" x-data="noDTR">
                    @include('_partials.search-employees')
                    <input type="hidden" name="employee_id" x-model="selectedEmployeeId" required>
                    <div>
                        <x-label>Effective Date</x-label>
                        <x-text-input type="date" name="effective_date" />
                    </div>
                    <div>
                        <x-label>End Date</x-label>
                        <x-text-input type="date" name="end_date" />
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button>{{ __('Submit') }}</x-primary-button>
                    </div>
                </div>
            </form>
        </x-card>
    </div>
    @include('_partials.search-employees-js')
    <script>
        function noDTR() {
            return {
                selectedEmployeeId: '',
                init() {
                    this.$watch('employee', value => {
                        this.selectedEmployeeId = value.id
                    })
                },
            }
        }
    </script>
</x-app-layout>
