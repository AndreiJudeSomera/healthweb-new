<div class="flex flex-col mt-[-15px]">
  <a class="p-2 underline" href="/patients/{{ $patient->pid }}">
    <i class="fa-solid fa-arrow-left me-1"></i>
    Back to patient
    '{{ $patient->last_name }}'
  </a>
  <div class="w-full flex flex-row gap-4 text-blue-950">
    {{-- PAGE: SECTION: COL1: Photo + PID --}}
    <div class="flex flex-col gap-2 text-blue-950 w-[360px]">
      <div class="flex flex-col gap-2 items-center w-full p-2 border-2 border-blue-950 rounded-md mt-[.5em]">
        <i class="fa-solid fa-user text-[220px] mt-[.5rem]"></i>
        <p class="w-full font-mono text-center text-xl font-black text-blue-950">- PATIENT -</p>
      </div>
      <p class="w-full text-center text-italic font-bold font-mono text-blue-950" id="viewPid">{{ $patient->pid }}</p>
      <div class="flex justify-center">
        <p class="font-bold text-xl w-full text-center overflow-auto" id="viewPatientType">
          {{ strtoupper($patient->first_name) }}
          {{ strtoupper($patient->middle_name) }}
          {{ strtoupper($patient->last_name) }}
        </p>
      </div>
      <div class="my-2 pe-1 text-sm">
        <div class="w-full flex flex-row justify-between">
          <p class="">Patient Type</p>
          <p class="font-medium" id="viewPatientType">{{ strtoupper($patient->patient_type) }}</p>
        </div>
        <div class="w-full flex flex-row justify-between">
          <p class="">Bound to User</p>
          <p class="font-medium" id="viewIsBound">{{ $patient->is_bound ? "YES" : "NO" }}</p>
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
      @include("patients.show-components.show-patient-documents")
    </div>
  </div>
</div>
