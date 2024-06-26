<!-- Employee -->
<div class="relative mb-2">
    <x-label>Employee</x-label>
    <x-text-input type="text" name="employee_name" placeholder="Doe, John/00000-00000" x-model="search"
        @input="getEmployees()" x-ref="emp" @focus="$refs.emp.select()" autocomplete="off" />

    <ul class="absolute inset-x-0 z-50 max-h-40 overflow-auto border bg-white dark:border-gray-600 dark:bg-gray-700"
        x-show="employees.length > 0">
        <template x-for="(e, i) in employees" :key="i">
            <li class="block w-full cursor-pointer p-2 text-xs hover:bg-green-300" x-text="e.full_name"
                @click="selectEmployee(e)">
            </li>
        </template>
    </ul>
</div>
