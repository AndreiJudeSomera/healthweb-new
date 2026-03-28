@extends("layouts.app")

@section("content")
  <div class="w-full flex flex-col gap-4 text-blue-950">
    {{-- Page Components  --}}
    @include("patients.show-components.show-patient-details")
    @include("patients.show-components.show-patient-appointments")

    {{-- Page Modals  --}}
    @include("patients.new-record-modal")
    @include("appointments.view-appointment-modal")
    @include("appointments.edit-appointment-modal")
    @include("appointments.delete-appointment-modal")
  </div>
@endsection
@push("scripts")
  @vite(["resources/js/pages/patient-show-index.js"])
  @vite(["resources/js/pages/appointment-slots.js"])
  @vite(["resources/js/pages/appointment-create.js"])
@endpush
