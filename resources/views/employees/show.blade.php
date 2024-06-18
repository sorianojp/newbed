<x-app-layout>
    <x-slot name="header">
        {{ $employee->lastname }}
    </x-slot>
    <div class="mx-auto max-w-5xl" x-data="{ activeSection: '{{ session('activeSection', 'personalData') }}' }">
        <x-auth-validation-errors />
        <x-auth-session-status :status="session()->get('success')" />
        <div class="mt-4 space-x-2">
            <button @click="activeSection = 'personalData'"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Personal Data
            </button>
            <button @click="activeSection = 'employmentRecord'"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Employment Records
            </button>
            <button @click="activeSection = 'educationalAttainment'"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Educational Attainment
            </button>
            <button @click="activeSection = 'civilService'"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Civil Services
            </button>
        </div>
        <!-- Personal Data Section (default view) -->
        <div x-show="activeSection === 'personalData'" x-cloak>
            @include('employees.partials.personalData')
        </div>
        <!-- Employment Record Section -->
        <div x-show="activeSection === 'employmentRecord'" x-cloak>
            @include('employees.partials.employmentRecord')
        </div>
        <!-- Educational Attainment -->
        <div x-show="activeSection === 'educationalAttainment'" x-cloak>
            @include('employees.partials.educationalAttainment')
        </div>
        <!-- Civil Service -->
        <div x-show="activeSection === 'civilService'" x-cloak>
            @include('employees.partials.civilService')
        </div>
    </div>
</x-app-layout>
