{{-- SECTION?: Modals --}}

<x-modal-garic id="delete-patient" title="Delete Patient" maxWidth="max-w-sm">
  @csrf
  <div class="flex flex-col items-center gap-4 text-center py-2">
    <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100">
      <i class="fa-solid fa-triangle-exclamation fa-lg text-red-700"></i>
    </div>
    <div class="flex flex-col gap-1">
      <p class="text-blue-950 font-semibold">
        Are you sure you want to delete
        <span class="text-red-700" id="deleteViewName">Loading...</span>?
      </p>
      <p class="text-sm text-gray-400 font-mono italic" id="deleteViewPid">Loading...</p>
      <p class="text-sm text-gray-500">This action cannot be undone.</p>
    </div>
  </div>
  <div class="mt-6 flex justify-end gap-2">
    <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
      id="deleteCloseButton" data-modal-close="delete-patient" type="button">Cancel</button>
    <form id="patient_delete_form">
      <button class="px-6 py-2 bg-red-700 text-red-100 rounded-md hover:bg-red-700/90" type="submit">
        <i class="fa-solid fa-trash fa-xs me-2"></i>Delete
      </button>
    </form>
  </div>
</x-modal-garic>
