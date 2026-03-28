@extends("layouts.app")
@section("content")
  <div class="w-full flex flex-col gap-4">
    <div class="w-full flex flex-wrap items-center justify-between gap-3">
      {{-- Group: Table Controls --}}
      <div class="w-full md:w-auto flex flex-row gap-2 items-center">
        {{-- Field: Search --}}
        <div class="relative flex-1 min-w-[260px] md:min-w-[320px]">
          <input
            class="w-full rounded-md text-sm p-2 ps-10 border border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent"
            id="patientSearch" type="text" name="patientSearch" placeholder="Search patients ..." />
          <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
          </svg>
        </div>
        {{-- Button: Filter --}}
        <button class="border border-blue-950 hover:bg-blue-50 py-2 px-4 rounded-md text-blue-950"
          data-modal-open="patient-filter" type="button">
          <div class="flex gap-2 items-center">
            <i class="fa-solid fa-filter text-sm"></i>
            <p class="text-sm font-medium">Filter</p>
          </div>
        </button>
      </div>
      {{-- Group: Buttons --}}
      <div class="flex flex-row gap-2">
        {{-- Button: New Patient --}}
        <button class="bg-gray-600 hover:bg-gray-500 py-2 px-4 rounded-md text-gray-100" id="bindPatientButton"
          data-modal-open="bind-patient" name="bindPatientButton">
          <div class="flex flex-row gap-2 items-center">
            <i class="fa-solid fa-link fa-xs"></i>
            <p class="text-sm font-medium">Bind Old Patient</p>
          </div>
        </button>
        <button class="bg-blue-950 hover:bg-blue-900 py-2 px-4 rounded-md text-blue-100" id="newPatientButton"
          data-modal-open="patient-add" name="newPatientButton">
          <div class="flex flex-row gap-2 items-center">
            <i class="fa-solid fa-plus fa-xs"></i>
            <p class="text-sm font-medium">Add Patient</p>
          </div>
        </button>
      </div>
    </div>
    {{-- Table: Patient Table --}}
    <div class="w-full min-w-[500px] [&_tr]:border-x [&_tr]:border-gray-200">
      <table class="min-w-full text-blue-950 hover order-column compact" id="patientsTable">
      </table>
    </div>
  </div>
  @include("patients.filter-modal")
  @include("patients.new-patient-modal")
  @include("patients.bind-patient-modal")
  @include("patients.edit-patient-modal")
  @include("patients.delete-patient-modal")
@endsection
@push("scripts")
  @vite(["resources/js/pages/patients-index.js"])
  @vite(["resources/js/components/modals/bind-patient-modal.js"])
@endpush
