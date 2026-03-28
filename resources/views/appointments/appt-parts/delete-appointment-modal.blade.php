<x-modal-garic id="delete-record" title="Cancel Appointment" maxWidth="max-w-sm">
  <form id="delete_appointment_form">
    @csrf
    <input id="delete_appt_id" type="hidden" value="" />

    <div class="flex flex-col items-center gap-4 text-center py-2">
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100">
        <i class="fa-solid fa-ban fa-lg text-red-700"></i>
      </div>
      <div class="flex flex-col gap-1">
        <p class="text-blue-950 font-semibold">Cancel this appointment?</p>
        <p class="text-sm text-gray-500">
          <span class="font-bold" id="delete_appt_date">—</span>
          at
          <span class="font-bold" id="delete_appt_time">—</span>
        </p>
        <p class="text-sm text-gray-500">The patient will be notified via SMS.</p>
      </div>
    </div>

    <div class="flex justify-end gap-2 mt-6">
      <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
        data-modal-close="delete-record" type="button">Go Back</button>
      <button class="px-6 py-2 bg-red-700 text-red-100 rounded-md hover:bg-red-700/90" type="submit">
        <i class="fa-solid fa-ban fa-xs me-2"></i>Cancel Appointment
      </button>
    </div>
  </form>
</x-modal-garic>
