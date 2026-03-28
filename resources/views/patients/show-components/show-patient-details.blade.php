<div class="w-full flex flex-row gap-4 text-blue-950">
  {{-- PAGE: SECTION: COL1: Photo + PID --}}
  <div class="flex flex-col gap-2 text-blue-950 w-[360px]">
    <div class="flex flex-col gap-2 items-center w-full p-2 border-2 border-blue-950 rounded-md mt-[.5em]">
      @if(strtolower($patient->gender ?? '') === 'female')
        <i class="fa-solid fa-person-dress text-[220px] mt-[.5rem] text-pink-600"></i>
      @else
        <i class="fa-solid fa-person text-[220px] mt-[.5rem] text-blue-700"></i>
      @endif
      <p class="w-full font-mono text-center text-xl font-black text-blue-950">- PATIENT -</p>
    </div>
    <p class="w-full text-center text-italic font-bold font-mono text-blue-950" id="viewPid">{{ $patient->pid }}</p>
    <div>
      <button class="btn shadow-none w-full bg-gray-600 rounded-md text-gray-100 font-medium py-2" id="copyPidButtonx">
        <i class="fa-regular fa-copy me-2"></i>
        Copy PID
      </button>
    </div>
    <div class="my-2 pe-1 text-sm">
      <div class="w-full flex flex-row justify-between">
        <p class="">Patient Type</p>
        <p class="font-medium" id="viewPatientType">{{ strtoupper($patient->patient_type) }}</p>
      </div>
      <div class="w-full flex flex-row justify-between">
        <p class="">Bound to User</p>
        <p class="font-medium {{ $patient->is_bound ? "text-green-700" : "text-yellow-600" }}" id="viewIsBound">
          {{ $patient->is_bound ? "YES" : "NO" }}</p>
      </div>
    </div>
    <div class="">
      <div class="text-sm w-full flex flex-col gap-2 pe-1">
        <div class="w-full flex flex-col ">
          <p class="">Date Created</p>
          <p class="font-medium" id="viewCreatedAt">{{ $patient->created_at->format('d F Y \a\t H:i') }}</p>
        </div>
        <div class="w-full flex flex-col ">
          <p class="">Last Updated</p>
          <p class="font-medium" id="viewUpdatedAt">
            {{ $patient->updated_at == $patient->created_at ? "Never" : $patient->updated_at->format('d F Y \a\t H:i') }}
          </p>
        </div>
      </div>
    </div>
  </div>
  {{-- MODAL: SECTION: COL2: Patient Details --}}
  <div class="w-full flex flex-col gap-2 text-blue-950">
    <div class="flex flex-row items-center gap-2">
      <div class="h-[2px] bg-blue-950 w-full"></div>
      <h1 class="font-medium flex-none">Patient Details</h1>
      <div class="h-[2px] bg-blue-950 w-full"></div>
    </div>
    {{-- MODAL: SECTION: COL2: ROW1: Name --}}
    <div class="w-full flex flex-row gap-2 text-blue-950">
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">First Name</p>
        <p class=" font-bold" id="viewFirstName">{{ $patient->first_name }}</p>
      </div>
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Middle Name</p>
        <p class=" font-bold" id="viewMiddleName">{{ $patient->middle_name }}</p>
      </div>
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Last Name</p>
        <p class=" font-bold" id="viewLastName">{{ $patient->last_name }}</p>
      </div>
    </div>
    {{-- MODAL: SECTION: COL2: ROW2: Contact #, Address --}}
    <div class="w-full flex flex-row gap-2 text-blue-950">
      <div class="flex-none min-w-[216.6px] flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Contact #</p>
        <p class=" font-bold" id="viewContactNumber">{{ $patient->contact_number }}</p>
      </div>
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Address</p>
        <p class=" font-bold" id="viewAddress">{{ $patient->address }}</p>
      </div>
    </div>
    {{-- MODAL: SECTION: COL2: ROW3: Gender, DoB, Age --}}
    <div class="w-full flex flex-row gap-2 text-blue-950">
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Gender</p>
        <p class=" font-bold" id="viewGender">{{ strtoupper($patient->gender) }}</p>
      </div>
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Nationality</p>
        <p class=" font-bold" id="viewNationality">{{ strtoupper($patient->nationality) }}</p>
      </div>
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Date of Birth</p>
        <p class=" font-bold" id="viewDob">{{ $patient->date_of_birth->format("d F Y") }}</p>
      </div>
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Age</p>
        <p class=" font-bold" id="viewAge">{{ $patient->age }}</p>
      </div>
    </div>
    {{-- MODAL: SECTION: COL2: ROW4: Guardian Details --}}
    <div class="w-full flex flex-col gap-2 text-blue-950">
      <div class="flex flex-row items-center gap-2">
        <div class="h-[2px] bg-blue-950 w-full"></div>
        <h1 class="font-medium flex-none">Guardian Details</h1>
        <div class="h-[2px] bg-blue-950 w-full"></div>
      </div>
    </div>
    <div class="w-full flex flex-row gap-2 text-blue-950">
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Guardian Name</p>
        <p class=" font-bold" id="viewGuardianName">{{ $patient->guardian_name }}</p>
      </div>
    </div>
    <div class="w-full flex flex-row gap-2 text-blue-950">
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Guardian Contact #</p>
        <p class=" font-bold" id="viewGuardianContact">{{ $patient->guardian_contact }}</p>
      </div>
      <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
        <p class=" text-xs">Guardian is Patient's</p>
        <p class=" font-bold" id="viewGuardianRelation">{{ strtoupper($patient->guardian_relation) }}</p>
      </div>
    </div>
    {{-- MODAL: SECTION: COL2: ROW5: Medical Details --}}
    <div class="text-sm w-full flex flex-row gap-2 text-blue-950">
      <div class="w-full flex flex-col pe-2">
        <div class="w-full flex flex-col gap-2 text-blue-950 my-1">
          <div class="flex flex-row items-center gap-2">
            <div class="h-[2px] bg-blue-950 w-full"></div>
            <h1 class="font-medium flex-none">Personal/Social History</h1>
            <div class="h-[2px] bg-blue-950 w-full"></div>
          </div>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Allergies</p>
          <p class="font-medium" id="viewAllergies">{{ strtoupper($patient->allergy) }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Alcohol</p>
          <p class="font-medium" id="viewAlcohol">{{ strtoupper($patient->alcohol) }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Years of Smoking</p>
          <p class="font-medium" id="viewYearsOfSmoking">{{ $patient->years_of_smoking }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Illicit Drug Use</p>
          <p class="font-medium" id="viewDrugUse">{{ strtoupper($patient->illicit_drug_use) }}</p>
        </div>
      </div>
      <div class="w-full flex flex-col ps-2">
        <div class="w-full flex flex-col gap-2 text-blue-950 my-1">
          <div class="flex flex-row items-center gap-2">
            <div class="h-[2px] bg-blue-950 w-full"></div>
            <h1 class="font-medium flex-none">Family History</h1>
            <div class="h-[2px] bg-blue-950 w-full"></div>
          </div>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Hypertension</p>
          <p class="font-medium {{ $patient->hypertension ? "text-green-700" : "text-gray-500" }}"
            id="viewHypertension">
            {{ $patient->hypertension ? "YES" : "NO" }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Asthma</p>
          <p class="font-medium {{ $patient->asthma ? "text-green-700" : "text-gray-500" }}" id="viewAsthma">
            {{ $patient->asthma ? "YES" : "NO" }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Diabetes</p>
          <p class="font-medium {{ $patient->diabetes ? "text-green-700" : "text-gray-500" }}" id="viewDiabetes">
            {{ $patient->diabetes ? "YES" : "NO" }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Cancer</p>
          <p class="font-medium {{ $patient->cancer ? "text-green-700" : "text-gray-500" }}" id="viewCancer">
            {{ $patient->cancer ? "YES" : "NO" }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Thyroid</p>
          <p class="font-medium {{ $patient->thyroid ? "text-green-700" : "text-gray-500" }}" id="viewThyroid">
            {{ $patient->thyroid ? "YES" : "NO" }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Others</p>
          <p class="font-medium" id="viewOthers">{{ $patient->others ? strtoupper($patient->others) : "[ - ]" }}
          </p>
        </div>
      </div>
    </div>
    {{-- PAGE:  RECORDS TABLE --}}
  </div>
</div>

