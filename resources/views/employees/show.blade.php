<x-app-layout>
    <x-slot name="header">
        {{ $employee->lastname }}
    </x-slot>
    <div class="mx-auto max-w-5xl" x-data="{ activeSection: '{{ session('activeSection', 'personalData') }}' }">
        <x-auth-validation-errors />
        <x-auth-session-status :status="session()->get('success')" />
        <div class="mt-4 mr-2">
            <button @click="activeSection = 'personalData'" :class="{ 'underline': activeSection === 'personalData' }">
                Personal Data
            </button>
            <button @click="activeSection = 'employmentRecord'"
                :class="{ 'underline': activeSection === 'employmentRecord' }">
                Employment Records
            </button>
            <button @click="activeSection = 'educationalAttainment'"
                :class="{ 'underline': activeSection === 'educationalAttainment' }">
                Educational Attainment
            </button>
            <button @click="activeSection = 'civilService'" :class="{ 'underline': activeSection === 'civilService' }">
                Civil Services
            </button>
            <button @click="activeSection = 'seminarTraining'"
                :class="{ 'underline': activeSection === 'seminarTraining' }">
                Seminar or Trainings Attended
            </button>
            <button @click="activeSection = 'distinctionRecognition'"
                :class="{ 'underline': activeSection === 'distinctionRecognition' }">
                Distinction Recognition
            </button>
            <button @click="activeSection = 'groupAffiliation'"
                :class="{ 'underline': activeSection === 'groupAffiliation' }">
                Group Affiliation
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
        <!-- Seminar or Training Attended -->
        <div x-show="activeSection === 'seminarTraining'" x-cloak>
            @include('employees.partials.seminarTraining')
        </div>
        <!-- Seminar or Training Attended -->
        <div x-show="activeSection === 'distinctionRecognition'" x-cloak>
            @include('employees.partials.distinctionRecognition')
        </div>
        <!-- Seminar or Training Attended -->
        <div x-show="activeSection === 'groupAffiliation'" x-cloak>
            @include('employees.partials.groupAffiliation')
        </div>
    </div>
</x-app-layout>
