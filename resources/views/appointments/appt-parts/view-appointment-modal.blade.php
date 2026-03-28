<x-modal-garic id="view-record" title="View Appointment" maxWidth="max-w-[520px]">
  <div class="w-full flex flex-col gap-3 text-blue-950">
    <div class="flex justify-between">
      <p class="text-sm">Date</p>
      <p class="font-medium" id="view_appt_date">—</p>
    </div>

    <div class="flex justify-between">
      <p class="text-sm">Time</p>
      <p class="font-medium" id="view_appt_time">—</p>
    </div>

    <div class="flex justify-between">
      <p class="text-sm">Type</p>
      <p class="font-medium" id="view_appt_type">—</p>
    </div>

    <div class="flex justify-between">
      <p class="text-sm">Status</p>
      <p class="font-medium" id="view_appt_status">—</p>
    </div>

    <div class="flex justify-end mt-4">
      <button class="px-6 py-2 bg-gray-700 text-blue-100 rounded-md hover:bg-gray-600" data-modal-close="view-record"
        type="button">
        Close
      </button>
    </div>
  </div>
</x-modal-garic>
