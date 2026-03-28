<x-modal-garic id="delete-document" title="Delete Document" maxWidth="max-w-[520px]">
  <form id="delete_document_form">
    @csrf
    <input id="delete_doc_id" type="hidden" value="" />

    <div class="flex flex-col items-center gap-4 py-2 text-blue-950">
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100">
        <i class="fa-solid fa-triangle-exclamation fa-lg text-red-700"></i>
      </div>
      <div class="text-center">
        <p class="font-semibold text-base">Delete this document?</p>
        <p class="text-sm text-gray-600 mt-1">
          <span class="font-semibold" id="delete_doc_type">—</span>
          &mdash;
          <span id="delete_doc_date">—</span>
        </p>
        <p class="text-xs text-gray-500 mt-2">This action cannot be undone.</p>
      </div>
    </div>

    <div class="flex justify-end gap-2 mt-6">
      <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
        data-modal-close="delete-document" type="button">
        Cancel
      </button>
      <button class="px-6 py-2 bg-red-700 text-red-100 rounded-md hover:bg-red-700/90" type="submit">
        <i class="fa-solid fa-trash fa-xs me-2"></i>
        Delete
      </button>
    </div>
  </form>
</x-modal-garic>
