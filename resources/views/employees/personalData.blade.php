<x-app-layout>
    <x-slot name="header">
        {{ $employee->lastname }}'s Personal Data
    </x-slot>
    <div class="mx-auto max-w-full">
        <x-auth-validation-errors />
        <x-auth-session-status :status="session()->get('success')" />
        <x-card>
            <form method="POST" action="{{ route('employees.storePersonalData', ['employee' => $employee]) }}">
                @csrf
                <div class="space-y-2">
                    <div>
                        <x-h6>Identification Numbers</x-h6>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <x-label>Tin</x-label>
                                <x-text-input type="text" name="tin" value="{{ $employee->personalData->tin }}"
                                    placeholder="000-000-000-00000" />
                            </div>
                            <div>
                                <x-label>SSS</x-label>
                                <x-text-input type="text" name="sss" value="{{ $employee->personalData->sss }}"
                                    placeholder="00-0000000-0" />
                            </div>
                            <div>
                                <x-label>PhilHealth</x-label>
                                <x-text-input type="text" name="philhealth"
                                    value="{{ $employee->personalData->philhealth }}" placeholder="00-000000000-0" />
                            </div>
                            <div>
                                <x-label>Pag-Ibig</x-label>
                                <x-text-input type="text" name="pag_ibig"
                                    value="{{ $employee->personalData->pag_ibig }}" placeholder="00000000000" />
                            </div>
                            <div>
                                <x-label>GSIS</x-label>
                                <x-text-input type="text" name="gsis" value="{{ $employee->personalData->gsis }}"
                                    placeholder="00000000000" />
                            </div>
                            <div>
                                <x-label>CRN</x-label>
                                <x-text-input type="text" name="crn" value="{{ $employee->personalData->crn }}"
                                    placeholder="000000000000" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-h6>Personal Informations</x-h6>
                        <div class="grid grid-cols-4 gap-2">
                            <div>
                                <x-label>Religion</x-label>
                                <x-text-input type="text" name="religion"
                                    value="{{ $employee->personalData->religion }}"
                                    placeholder="Catholic, Muslim, Christian" />
                            </div>
                            <div>
                                <x-label>Nationality</x-label>
                                <x-text-input type="text" name="nationality"
                                    value="{{ $employee->personalData->nationality }}"
                                    placeholder="Filipino, American, Japanese" />
                            </div>
                            <div>
                                <x-label>Place of Birth</x-label>
                                <x-text-input type="text" name="pob" value="{{ $employee->personalData->pob }}"
                                    placeholder="Town/City, Province" />
                            </div>
                            <div>
                                <x-label>Date of Birth</x-label>
                                <x-text-input type="date" name="dob" value="{{ $employee->personalData->dob }}"
                                    placeholder="Date of Birth" />
                            </div>
                            <div>
                                <x-label>Gender</x-label>
                                <x-text-input-select name="gender">
                                    <option value="" disabled
                                        {{ is_null($employee->personalData->gender) && is_null(old('gender')) ? 'selected' : '' }}>
                                        Select Gender</option>
                                    @foreach (['Male', 'Female', 'Other'] as $option)
                                        <option value="{{ $option }}"
                                            {{ $employee->personalData->gender == $option || old('gender') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </x-text-input-select>
                            </div>
                            <div>
                                <x-label>Civil Status</x-label>
                                <x-text-input-select name="civil_status">
                                    <option value="" disabled
                                        {{ is_null($employee->personalData->civil_status) && is_null(old('civil_status')) ? 'selected' : '' }}>
                                        Select Civil Status</option>
                                    @foreach (['Single', 'Married', 'Widowed', 'Separated', 'Legally Separated'] as $option)
                                        <option value="{{ $option }}"
                                            {{ $employee->personalData->civil_status == $option || old('civil_status') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </x-text-input-select>
                            </div>
                            <div>
                                <x-label>Weight</x-label>
                                <x-text-input type="number" name="weight"
                                    value="{{ $employee->personalData->weight }}" placeholder="120" />
                            </div>
                            <div>
                                <x-label>Height</x-label>
                                <x-text-input type="text" name="height"
                                    value="{{ $employee->personalData->height }}" placeholder="5'0" />
                            </div>
                            <div>
                                <x-label>Blood Type</x-label>
                                <x-text-input-select name="blood_type">
                                    <option value="" disabled
                                        {{ is_null($employee->personalData->blood_type) && is_null(old('blood_type')) ? 'selected' : '' }}>
                                        Select Blood Type</option>
                                    @foreach (['AB+', 'AB-', 'A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'Not Specified'] as $option)
                                        <option value="{{ $option }}"
                                            {{ $employee->personalData->blood_type == $option || old('blood_type') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </x-text-input-select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-h6>Addresses</x-h6>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <x-label>Residential Address</x-label>
                                <x-text-input type="text" name="residential_address"
                                    value="{{ $employee->personalData->residential_address }}"
                                    placeholder="House No, Street, Barangay, Town/City, Province, Country" />
                            </div>
                            <div>
                                <x-label>Permanent Address</x-label>
                                <x-text-input type="text" name="permanent_address"
                                    value="{{ $employee->personalData->permanent_address }}"
                                    placeholder="House No, Street, Barangay, Town/City, Province, Country" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-h6>Family Background</x-h6>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <x-label>Spouse Full Name</x-label>
                                <x-text-input type="text" name="spouse_full_name"
                                    value="{{ $employee->personalData->spouse_full_name }}"
                                    placeholder="Lastname, Firstname MI" />
                            </div>
                            <div>
                                <x-label>Spouse Occupation</x-label>
                                <x-text-input type="text" name="spouse_occupation"
                                    value="{{ $employee->personalData->spouse_occupation }}"
                                    placeholder="Architect, Engineer, Factory Worker, Not Specified" />
                            </div>
                            <div>
                                <x-label>Spouse Mobile No</x-label>
                                <x-text-input type="text" name="spouse_mobile_no"
                                    value="{{ $employee->personalData->spouse_mobile_no }}"
                                    placeholder="09000000000" />
                            </div>
                            <div>
                                <x-label>Spouse Occupation Employer</x-label>
                                <x-text-input type="text" name="spouse_occupation_employer"
                                    value="{{ $employee->personalData->spouse_occupation_employer }}"
                                    placeholder="Employer Name" />
                            </div>
                            <div>
                                <x-label>Spouse Occupation Business Address</x-label>
                                <x-text-input type="text" name="spouse_occupation_business_address"
                                    value="{{ $employee->personalData->spouse_occupation_business_address }}"
                                    placeholder="Bldg. No, Street, Barangay, Town/City, Province, Country" />
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 my-2">
                            <div>
                                <x-label>Father Full Name</x-label>
                                <x-text-input type="text" name="father_full_name"
                                    value="{{ $employee->personalData->father_full_name }}"
                                    placeholder="Lastname, Firstname MI" />
                            </div>
                            <div>
                                <x-label>Father Occupation</x-label>
                                <x-text-input type="text" name="father_occupation"
                                    value="{{ $employee->personalData->father_occupation }}"
                                    placeholder="Architect, Engineer, Factory Worker, Not Specified" />
                            </div>
                            <div>
                                <x-label>Father Mobile No</x-label>
                                <x-text-input type="text" name="father_mobile_no"
                                    value="{{ $employee->personalData->father_mobile_no }}"
                                    placeholder="09000000000" />
                            </div>
                            <div>
                                <x-label>Father Occupation Employer</x-label>
                                <x-text-input type="text" name="father_occupation_employer"
                                    value="{{ $employee->personalData->father_occupation_employer }}"
                                    placeholder="Employer Name" />
                            </div>
                            <div>
                                <x-label>Father Occupation Business Address</x-label>
                                <x-text-input type="text" name="father_occupation_business_address"
                                    value="{{ $employee->personalData->father_occupation_business_address }}"
                                    placeholder="Bldg. No, Street, Barangay, Town/City, Province, Country" />
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <x-label>Mother Full Name</x-label>
                                <x-text-input type="text" name="mother_full_name"
                                    value="{{ $employee->personalData->mother_full_name }}"
                                    placeholder="Lastname, Firstname MI" />
                            </div>
                            <div>
                                <x-label>Mother Occupation</x-label>
                                <x-text-input type="text" name="mother_occupation"
                                    value="{{ $employee->personalData->mother_occupation }}"
                                    placeholder="Architect, Engineer, Factory Worker, Not Specified" />
                            </div>
                            <div>
                                <x-label>Mother Mobile No</x-label>
                                <x-text-input type="text" name="mother_mobile_no"
                                    value="{{ $employee->personalData->mother_mobile_no }}"
                                    placeholder="09000000000" />
                            </div>
                            <div>
                                <x-label>Mother Occupation Employer</x-label>
                                <x-text-input type="text" name="mother_occupation_employer"
                                    value="{{ $employee->personalData->mother_occupation_employer }}"
                                    placeholder="Employer Name" />
                            </div>
                            <div>
                                <x-label>Mother Occupation Business Address</x-label>
                                <x-text-input type="text" name="mother_occupation_business_address"
                                    value="{{ $employee->personalData->mother_occupation_business_address }}"
                                    placeholder="Bldg. No, Street, Barangay, Town/City, Province, Country" />
                            </div>
                        </div>
                    </div>
                    <div>
                        <x-h6>Contact Info in Case of Emergency</x-h6>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <x-label>Emergency Contact Full Name</x-label>
                                <x-text-input type="text" name="emergency_contact_full_name"
                                    value="{{ $employee->personalData->emergency_contact_full_name }}"
                                    placeholder="Lastname, Firstname MI" />
                            </div>
                            <div>
                                <x-label>Emergency Contact Address</x-label>
                                <x-text-input type="text" name="emergency_contact_address"
                                    value="{{ $employee->personalData->emergency_contact_address }}"
                                    placeholder="House No, Street, Barangay, Town/City, Province, Country" />
                            </div>
                            <div>
                                <x-label>Emergency Contact Mobile No</x-label>
                                <x-text-input type="text" name="emergency_contact_mobile_no"
                                    value="{{ $employee->personalData->emergency_contact_mobile_no }}"
                                    placeholder="0900000000" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-2">
                    <x-primary-button>Save</x-primary-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
