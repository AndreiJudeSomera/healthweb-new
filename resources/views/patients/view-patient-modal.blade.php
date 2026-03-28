{{-- Modals: Row Actions --}}

{{-- MODAL: View Patient --}}
<x-modal-garic id="view-patient" title="Patient Record" maxWidth="max-w-[900px]">
  <div class="w-full flex flex-col gap-4 text-blue-950">
    {{-- MODAL: BODY --}}
    <div class="w-full flex flex-row gap-2 text-blue-950">
      {{-- MODAL: SECTION: COL1: Photo + PID --}}
      <div class="flex flex-col gap-2 text-blue-950">
        <div class="flex flex-col gap-2 w-full p-2 border-2 border-blue-950 rounded-md mt-[.5em]">
          <i id="viewPatientAvatar" class="fa-solid fa-person text-[200px] mt-[2px] text-blue-700"></i>
          <p class="w-full font-mono text-center text-xl font-black text-blue-950">- PATIENT -</p>
        </div>
        <p class="w-full text-center text-italic font-bold font-mono text-blue-950" id="viewPid">Loading ...</p>
        <div>
          <button class="w-full bg-gray-500 hover:bg-gray-600 rounded-md text-white text-sm py-2" id="copyPidButton">
            <i class="fa-regular fa-copy me-2"></i>
            Copy PID
          </button>
        </div>
        <div class="my-2 pe-1 text-sm">
          <div class="w-full flex flex-row justify-between">
            <p class="">Patient Type</p>
            <p class="font-medium" id="viewPatientType">Loading...</p>
          </div>
          <div class="w-full flex flex-row justify-between">
            <p class="">Bound to User</p>
            <p class="font-medium" id="viewIsBound">Loading...</p>
          </div>
        </div>
        <div class="pe-1 text-sm">
          <div class="w-full flex flex-row justify-between">
            <p class="">Nationality</p>
            <p class="font-medium" id="viewNationality">Loading...</p>
          </div>
        </div>
        <div class="">
          <div class="text-sm w-full flex flex-col gap-2 pe-1">
            <div class="w-full flex flex-col ">
              <p class="">Date Created</p>
              <p class="font-medium" id="viewCreatedAt">Loading...</p>
            </div>
            <div class="w-full flex flex-col ">
              <p class="">Last Updated</p>
              <p class="font-medium" id="viewUpdatedAt">Loading...</p>
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
            <p class=" font-bold" id="viewFirstName">Loading ...</p>
          </div>
          <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Middle Name</p>
            <p class=" font-bold" id="viewMiddleName">Loading ...</p>
          </div>
          <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Last Name</p>
            <p class=" font-bold" id="viewLastName">Loading ...</p>
          </div>
        </div>
        {{-- MODAL: SECTION: COL2: ROW2: Contact #, Address --}}
        <div class="w-full flex flex-row gap-2 text-blue-950">
          <div class="flex-none min-w-[216.6px] flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Contact #</p>
            <p class=" font-bold" id="viewContactNumber">Loading ...</p>
          </div>
          <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Address</p>
            <p class=" font-bold" id="viewAddress">Loading ...</p>
          </div>
        </div>
        {{-- MODAL: SECTION: COL2: ROW3: Gender, DoB, Age --}}
        <div class="w-full flex flex-row gap-2 text-blue-950">
          <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Gender</p>
            <p class=" font-bold" id="viewGender">Loading ...</p>
          </div>
          <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Date of Birth</p>
            <p class=" font-bold" id="viewDob">Loading ...</p>
          </div>
          <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Age</p>
            <p class=" font-bold" id="viewAge">Loading ...</p>
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
            <p class=" font-bold" id="viewGuardianName">Loading ...</p>
          </div>
        </div>
        <div class="w-full flex flex-row gap-2 text-blue-950">
          <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Guardian Contact #</p>
            <p class=" font-bold" id="viewGuardianContact">Loading ...</p>
          </div>
          <div class="w-full flex flex-col text-blue-950 border-2 border-blue-950 rounded-md p-2">
            <p class=" text-xs">Guardian is Patient's</p>
            <p class=" font-bold" id="viewGuardianRelation">Loading ...</p>
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
              <p class="font-medium" id="viewAllergies">Loading...</p>
            </div>
            <div class="w-full flex flex-row justify-between">
              <p class="">Alcohol</p>
              <p class="font-medium" id="viewAlcohol">Loading...</p>
            </div>
            <div class="w-full flex flex-row justify-between">
              <p class="">Years of Smoking</p>
              <p class="font-medium" id="viewYearsOfSmoking">Loading...</p>
            </div>
            <div class="w-full flex flex-row justify-between">
              <p class="">Illicit Drug Use</p>
              <p class="font-medium" id="viewDrugUse">Loading...</p>
            </div>
            <div class="w-full flex flex-row justify-between">
              <p class="">Others</p>
              <p class="font-medium" id="viewOthers">Loading...</p>
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
              <p class="font-medium" id="viewHypertension">Loading...</p>
            </div>
            <div class="w-full flex flex-row justify-between">
              <p class="">Asthma</p>
              <p class="font-medium" id="viewAsthma">Loading...</p>
            </div>
            <div class="w-full flex flex-row justify-between">
              <p class="">Diabetes</p>
              <p class="font-medium" id="viewDiabetes">Loading...</p>
            </div>
            <div class="w-full flex flex-row justify-between">
              <p class="">Cancer</p>
              <p class="font-medium" id="viewCancer">Loading...</p>
            </div>
            <div class="w-full flex flex-row justify-between">
              <p class="">Thyroid</p>
              <p class="font-medium" id="viewThyroid">Loading...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- MODAL: FOOTER (CANCEL/SAVE) --}}
    <div class="flex justify-end gap-2">
      <button class="px-6 py-2 bg-gray-700 text-gray-100 rounded-md hover:bg-gray-600"
        data-modal-close="view-patient">
        <span class="text-sm">
          Exit View
        </span>
      </button>
      <a class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:cursor-pointer hover:bg-blue-900"
        id="view-patient-history">
        <span class="text-sm">
          View Patient History
        </span>
        <i class="fa-solid fa-arrow-right ms-2"></i>
      </a>
    </div>
  </div>
</x-modal-garic>
