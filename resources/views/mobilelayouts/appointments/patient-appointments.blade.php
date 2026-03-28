@extends("mobilelayouts.app")

@section("content")
  @include("mobilelayouts.appointments.components.base")
  @include("mobilelayouts.appointments.components.modal-create")
@endsection
@push("scripts")
  @vite(["resources/js/components/modals/modal.js"])
  @vite(["resources/js/pages/appointment-create.js"])
  @vite(["resources/js/pages/appointment-slots.js"])
@endpush
