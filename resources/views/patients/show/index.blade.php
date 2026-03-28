@extends("layouts.app")
@section("content")
  <div class="w-full flex flex-col gap-2">

    <div class="w-full flex flex-col-reverse gap-4 md:gap-0 md:flex-row justify-between items-start md:items-center">
      <p class="font-semibold text-blue-950 text-sm">PATIENT ID: {{ $patient->pid }}</p>
      <a class="bg-blue-950 text-sm text-blue-100 hover:bg-blue-950/90 py-2 px-4 rounded-md flex items-center"
        href="{{ route("patients.index") }}">
        <i class="fa-solid fa-arrow-left-long me-2"></i>
        Back to Patients
      </a>
    </div>

    @php
      $render = [
          "Basic Information" => [
              [
                  "Last Name" => $patient->last_name,
                  "First Name" => $patient->first_name,
                  "Middle Name" => $patient->middle_name ?? "-",
                  "Age" => $patient->age . " YEARS OLD" ?? "-",
              ],
              [
                  "Sex" => $patient->gender ?? "-",
                  "Birth Date" => $patient->date_of_birth?->format("F j, Y") ?? "-",
                  "Nationality" => $patient->nationality,
                  "Contact No." => $patient->contact_number ?? "-",
              ],
              [
                  "Address" => $patient->address ?? "-",
              ],
          ],
          "Personal/Social History" => [
              [
                  "Allergy" => $patient->allergy ?? "-",
                  "Alcohol" => $patient->alcohol ?? "-",
                  "Years of Smoking" => $patient->years_of_smoking ?? "-",
                  "Illicit Drug Use" => $patient->illicit_drug_use ?? "-",
              ],
          ],
          "Family History" => [
              [
                  "Hypertension" => $patient->hypertension ? "Yes" : null,
                  "Asthma" => $patient->asthma ? "Yes" : null,
                  "Diabetes" => $patient->diabetes ? "Yes" : null,
                  "Cancer" => $patient->cancer ? "Yes" : null,
                  "Thyroid" => $patient->thyroid ? "Yes" : null,
                  "Others" => $patient->others ?? "-",
              ],
          ],
      ];
    @endphp

    <div class="w-full flex flex-col md:flex-row gap-2">

      <div class="flex flex-col gap-4 border rounded-md w-full">
        <div
          class="w-full bg-blue-950 text-blue-100 text-sm font-medium p-2 rounded-t-md flex justify-center items-center gap-2">
          <p>Basic Information</p>

        </div>
        <div class="px-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-2 h-full gap-y-auto">
          @foreach ($render["Basic Information"] as $row)
            @foreach ($row as $label => $value)
              <div class="flex flex-col {{ $label === "Address" ? "col-span-1 md:col-span-full col-start-1" : "" }}">
                <p class="text-blue-950 text-xs font-bold w-full">{{ $label }}</p>
                <p class="text-blue-950/90 text-sm font-medium w-full break-words whitespace-normal">
                  {{ strtoupper((string) $value) }}</p>
              </div>
            @endforeach
          @endforeach
        </div>
      </div>

      <div class="w-full flex flex-col gap-2">
        <div class="flex flex-col gap-4 border rounded-md">
          <div
            class="w-full bg-blue-950 text-blue-100 text-sm font-medium p-2 rounded-t-md flex justify-center items-center gap-2">
            <p>Personal/Social History</p>

          </div>
          <div class="px-4 mb-4 grid grid-cols-1 md:grid-cols-4 gap-2">
            @foreach ($render["Personal/Social History"] as $row)
              @foreach ($row as $label => $value)
                <div
                  class="flex flex-col gap-1 {{ $label === "Address" ? "col-span-1 md:col-span-full col-start-1" : "" }}">
                  <p class="text-blue-950 text-xs font-bold w-full">{{ $label }}</p>
                  <p class="text-blue-950/90 text-sm font-medium w-full break-words whitespace-normal">
                    {{ strtoupper((string) $value) }}</p>
                </div>
              @endforeach
            @endforeach
          </div>
        </div>
        <div class="flex flex-col gap-4 border rounded-md">
          <div
            class="w-full bg-blue-950 text-blue-100 text-sm font-medium p-2 rounded-t-md flex justify-center items-center gap-2">
            <p>Family History</p>

          </div>
          <div class="px-4 mb-4 grid grid-cols-1 md:grid-cols-6 gap-2">
            @foreach ($render["Family History"] as $row)
              @foreach ($row as $label => $value)
                <div
                  class="flex flex-col items-center gap-1 {{ $label === "Address" ? "col-span-1 md:col-span-full col-start-1" : "" }}">
                  <p class="text-blue-950 text-center text-xs font-bold w-full">{{ $label }}</p>
                  @if ($label === "Others")
                    <p class="text-blue-950/90 text-sm font-medium w-full break-words whitespace-normal">
                      {{ strtoupper((string) $value) }}</p>
                  @else
                    <input class="h-full accent-blue-950 pointer-events-none" type="checkbox"
                      {{ $value ? "checked" : "" }} />
                  @endif
                </div>
              @endforeach
            @endforeach
          </div>
        </div>
      </div>

    </div>
    <div class="mt-1">
      <div class="w-full flex flex-wrap items-center justify-between gap-3">
        {{-- Group: Table Controls --}}
        <div class="w-full md:w-auto flex flex-row gap-2 items-center" x-data = "{searchQuery: '' }">
          {{-- Field: Search --}}
          <div class="relative flex-1 min-w-[260px] md:min-w-[320px]">
            <input
              class="w-full rounded-md text-sm p-2 ps-10 border border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent"
              id="searchRecords" type="text" name="searchRecords" placeholder="Search records ..."
              x-ref="searchRecords" />
            <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
            </svg>
          </div>
          @include("patients.show.modals.filter-modal")
        </div>
        {{-- Group: Buttons --}}
        <div class="w-full md:w-auto flex flex-col md:flex-row gap-2">
          {{-- Button: New Patient --}}
          <button class="w-full md:w-auto bg-gray-200 hover:bg-gray-200/90 py-2 px-4 rounded-md text-gray-700"
            id="recordFilterButton" data-modal-open="record-filter">
            <div class="flex flex-row gap-2 items-center">
              <i class="fa-solid fa-filter fa-xs"></i>
              <p class="text-sm font-medium">Filter</p>
            </div>
          </button>
        @php
            $buttons = []; // Initialize an empty array

            if (auth()->user()->role == 2) {
                // Role 2: both buttons
                $buttons[] = [
                    "label" => "Create Prescription",
                    "icon" => "fa-solid fa-pills fa-xs",
                    "modal" => "create-prescription",
                    "color" => "bg-blue-950 hover:bg-blue-950/90 text-blue-100",
                ];
                $buttons[] = [
                    "label" => "Create Consultation",
                    "icon" => "fa-solid fa-stethoscope fa-xs",
                    "modal" => "create-consultation",
                    "color" => "bg-blue-950 hover:bg-blue-950/90 text-blue-100",
                ];
            } elseif (auth()->user()->role == 1) {
                // Role 1: only consultation
                $buttons[] = [
                    "label" => "Create Consultation",
                    "icon" => "fa-solid fa-stethoscope fa-xs",
                    "modal" => "create-sec-consultation",
                    "color" => "bg-blue-950 hover:bg-blue-950/90 text-blue-100",
                ];
            }
            @endphp

            @if(!empty($buttons))
                @foreach ($buttons as $b)
                    <button class="{{ $b['color'] }} w-full md:w-auto py-2 px-4 rounded-md"
                        data-modal-open="{{ $b['modal'] }}">
                        <div class="flex flex-row gap-2 items-center">
                            <i class="{{ $b['icon'] }}"></i>
                            <p class="text-sm font-medium">{{ $b['label'] }}</p>
                        </div>
                    </button>
                @endforeach
            @endif
          @include("patients.show.modals.create-consultation")
          @include("patients.show.modals.create-sec-consultation")
          @include("patients.show.modals.create-prescription")
          @include("patients.show.modals.create-consultation-document-modal")
          @php
             $userRole = auth()->user()?->role ?? null;
            @endphp

            @if ($userRole === 2)
                @include("patients.show-components.document-modals.edit-document-modal", ['document' => null])
            @elseif ($userRole === 1)
                @include("patients.show-components.document-modals.edit-document-modal-sec", ['document' => null])
            @endif
          @include("patients.show-components.document-modals.delete-document-modal")
        </div>
      </div>

      <div class="w-full [&_tr]:border-x [&_tr]:border-gray-200">
      @if(auth()->user()->role == 2)
          <table class="w-full text-blue-950 hover order-column compact overflow-auto"
                id="patientDocumentsTable">
          </table>
      @elseif(auth()->user()->role == 1)
          <table class="w-full text-blue-950 hover order-column compact overflow-auto"
                id="patientDocumentsTablesSecView">
          </table>
      @endif
    </div>
    </div>
  </div>
@endsection
@push("scripts")
@php
  $role = auth()->user()->role ?? null;
@endphp

@if($role == 2)
  @vite(["resources/js/refactored/patient-documents.js"])
@elseif($role == 1)
  @vite(["resources/js/refactored/patient-documents-sec.js"])
@endif
  @vite(["resources/js/components/modals/modal.js"])

  

@endpush
