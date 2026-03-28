@extends("layouts.app")
@section("content")
  @include("patients.show-documents.show-doc-components.doc-show-info")
@endsection
@push("scripts")
  @vite(["resources/js/pages/patient-documents-index.js"])
  @vite(["resources/js/components/modals/modal.js"])
@endpush
