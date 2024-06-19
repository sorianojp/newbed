<x-app-layout>
    <x-slot name="header">
        {{ $employee->lastname }}
    </x-slot>
    <div class="mx-auto max-w-full" x-data="{ activeSection: '{{ session('activeSection', 'personalData') }}' }">
        <x-auth-validation-errors />
        <x-auth-session-status :status="session()->get('success')" />
        <x-auth-session-status-warning :status="session()->get('note')" />
        <div class="md:flex" x-data="{ activeSection: '{{ session('activeSection', 'personalData') }}' }">
            <ul class="flex-column space-y-2 text-sm font-medium text-gray-500 dark:text-gray-400 md:me-4 mb-4 md:mb-0">
                <li>
                    <button @click="activeSection = 'personalData'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'personalData', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'personalData' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Personal Data
                    </button>
                </li>
                <li>
                    <button @click="activeSection = 'employmentRecord'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'employmentRecord', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'employmentRecord' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Employment Records
                    </button>
                </li>
                <li>
                    <button @click="activeSection = 'educationalAttainment'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'educationalAttainment', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'educationalAttainment' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Educational Attainment
                    </button>
                </li>
                <li>
                    <button @click="activeSection = 'civilService'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'civilService', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'civilService' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Civil Services
                    </button>
                </li>
                <li>
                    <button @click="activeSection = 'seminarTraining'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'seminarTraining', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'seminarTraining' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Seminar/Trainings
                    </button>
                </li>
                <li>
                    <button @click="activeSection = 'distinctionRecognition'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'distinctionRecognition', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'distinctionRecognition' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Distinction/Recognition
                    </button>
                </li>
                <li>
                    <button @click="activeSection = 'groupAffiliation'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'groupAffiliation', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'groupAffiliation' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Group Affiliation
                    </button>
                </li>
                <li>
                    <button @click="activeSection = 'jobSkill'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'jobSkill', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'jobSkill' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Job Skill
                    </button>
                </li>
                <li>
                    <button @click="activeSection = 'childrenData'"
                        :class="{ 'text-white bg-blue-700 dark:bg-blue-600': activeSection === 'childrenData', 'bg-white hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700': activeSection !== 'childrenData' }"
                        class="inline-flex items-center px-4 py-3 rounded-lg w-full">
                        Children Data
                    </button>
                </li>
            </ul>
            <div class="w-full">
                <!-- Sections -->
                <div x-show="activeSection === 'personalData'" x-cloak>
                    @include('employees.partials.personalData')
                </div>
                <div x-show="activeSection === 'employmentRecord'" x-cloak>
                    @include('employees.partials.employmentRecord')
                </div>
                <div x-show="activeSection === 'educationalAttainment'" x-cloak>
                    @include('employees.partials.educationalAttainment')
                </div>
                <div x-show="activeSection === 'civilService'" x-cloak>
                    @include('employees.partials.civilService')
                </div>
                <div x-show="activeSection === 'seminarTraining'" x-cloak>
                    @include('employees.partials.seminarTraining')
                </div>
                <div x-show="activeSection === 'distinctionRecognition'" x-cloak>
                    @include('employees.partials.distinctionRecognition')
                </div>
                <div x-show="activeSection === 'groupAffiliation'" x-cloak>
                    @include('employees.partials.groupAffiliation')
                </div>
                <div x-show="activeSection === 'jobSkill'" x-cloak>
                    @include('employees.partials.jobSkill')
                </div>
                <div x-show="activeSection === 'childrenData'" x-cloak>
                    @include('employees.partials.childrenData')
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
