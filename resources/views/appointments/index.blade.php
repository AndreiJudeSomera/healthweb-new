@extends("layouts.app")
@section("content")
  @include("appointments.appt-parts.patient-appointments")

  @include("appointments.appt-parts.add-appointment-modal")
  @include("appointments.appt-parts.view-appointment-modal")
  @include("appointments.appt-parts.edit-appointment-modal")
  @include("appointments.appt-parts.delete-appointment-modal")
@endsection
@push("scripts")
  @vite(["resources/js/pages/appointment-all-create.js"])
  @vite(["resources/js/components/modals/modal.js"])
  @vite(["resources/js/pages/appointment-table.js"])
@endpush
