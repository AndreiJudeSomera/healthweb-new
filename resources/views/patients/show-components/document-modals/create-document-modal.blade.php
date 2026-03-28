@php
  $currentUser = \App\Models\User::where("id", auth()->id())->firstOrFail();
  $isAdmin = $currentUser->role === 2 ? true : false;
@endphp

<x-modal-garic id="create-document" title="Generate Document" maxWidth="max-w-[700px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">GENERATE DOCUMENT</h1>
  </div>
  <form id="create_document_form">
    @csrf
    <div class="w-full flex flex-col gap-2">
      {{-- Row 1 --}}
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <input type="text" value="{{ $patient->pid }}" name="patient_pid" hidden />
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="document_type">Document Type</label>
          <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="document_type"
            name="document_type" required>
            <option value="" default disabled>Select Document Type</option>
            <option value="prescription">Prescription</option>
            <option value="medical-certificate">Medical Certificate</option>
            <option value="referral-letter">Referral Letter</option>
          </select>
        </div>
      </div>
      {{-- Row 2 --}}
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="wt">WT</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="wt" name="wt"
            placeholder="00" />
        </div>
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="bp">BP</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="bp" name="bp"
            placeholder="00" />
        </div>
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="cr">CR</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="cr" name="cr"
            placeholder="00" />
        </div>
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="rr">RR</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="rr" name="rr"
            placeholder="00" />
        </div>
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="temperature">Temperature</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="temperature" name="temperature"
            placeholder="00" />
        </div>
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="sp02">SP02</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="sp02" name="sp02"
            placeholder="00" />
        </div>
      </div>
      {{-- Row 3 --}}
      @if ($isAdmin)
        <div class="flex flex-row w-full gap-2 text-blue-950">
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="history_physical_exam">History Physical Exam</label>
            <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="history_physical_exam"
              name="history_physical_exam" placeholder="History" />
          </div>
        </div>
        {{-- Row 4 --}}
        <div class="flex flex-row w-full gap-2 text-blue-950">
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="diagnosis">Diagnosis</label>
            <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="diagnosis" name="diagnosis"
              placeholder="Diagnosis" />
          </div>
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="treatment">Treatment</label>
            <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="treatment" name="treatment"
              placeholder="Treatment" />
          </div>
        </div>
        {{-- Row 5 --}}
        <div class="flex flex-row w-full gap-2 text-blue-950">
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="prescription_meds">Prescription Meds</label>
            <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="prescription_meds"
              name="prescription_meds" placeholder="Prescription Medicine" />
          </div>
        </div>
        {{-- Row 6 --}}
        <div class="flex flex-row w-full gap-2 text-blue-950">
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="referral_to">Referral To</label>
            <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="referral_to"
              name="referral_to" placeholder="Referral To" />
          </div>
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="referral_reason">Referral Reason</label>
            <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="referral_reason"
              name="referral_reason" placeholder="Referral Reason" />
          </div>
        </div>
        {{-- Row 7 --}}
        <div class="flex flex-row w-full gap-2 text-blue-950">
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="remarks">Remarks</label>
            <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="remarks" name="remarks"
              placeholder="Remarks" />
          </div>
        </div>
      @endif
      {{-- Footer --}}
      <div class="flex justify-end gap-2 mt-6">
        <button class="px-6 py-2 bg-gray-700 text-gray-100 rounded-md hover:bg-gray-600"
          data-modal-close="create-document" type="button">
          Cancel
        </button>
        <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-900" type="submit">
          Save
        </button>
      </div>
  </form>
</x-modal-garic>
