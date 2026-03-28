{{-- Modal: Bind Patient Data --}}
<x-modal-garic id="bind-patient" title="Bind Old Patient Record to New User" maxWidth="max-w-[500px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">BIND PATIENT RECORD</h1>
  </div>
  <form id="patient_bind_record" method="POST" action="">
    <div class="w-full flex flex-col gap-2 text-blue-950">
      <div class="flex flex-col gap-1">
        <label class="font-medium" for="pid">Existing Patient Record</label>
        <select class="w-full border-2 border-blue-950 rounded-md p-2" id="bind_record" name="record_id">
          <option default disabled>Select Record</option>
        </select>
      </div>
      <div class="flex flex-col gap-1">
        <label class="font-medium" for="user">Old Patient <span class="text-xs text-gray-500 mt-1"> ( User that has existing records in the clinic)</span>
         </label>
        <select class="w-full border-2 border-blue-950 rounded-md p-2" id="bind_user" name="id">
          <option value="" disabled selected>Select User</option>
        </select>
      </div>
    </div>
    {{-- MODAL: FOOTER (CANCEL/SAVE) --}}
    <div class="flex justify-end gap-2 mt-4">
      <button class="px-6 py-2 border-2 bg-gray-600 text-gray-100 rounded-md" data-modal-close="bind-patient"
        type="button">
        <span class="text-sm">
          Cancel
        </span>
      </button>
      <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md" type="submit">
        <i class="fa-solid fa-link me-1 fa-sm"></i>
        <span class="text-sm">
          Bind
        </span>
      </button>
    </div>
  </form>
</x-modal-garic>
