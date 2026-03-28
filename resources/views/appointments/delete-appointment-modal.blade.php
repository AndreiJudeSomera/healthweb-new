<x-modal-garic id="delete-record" title="Cancel Appointment" maxWidth="max-w-[520px]">
  <form id="delete_appointment_form">
    @csrf
    <input id="delete_appt_id" type="hidden" value="" />

    <p class="text-blue-950">
      Cancel appointment on <span class="font-bold" id="delete_appt_date">—</span>
      at <span class="font-bold" id="delete_appt_time">—</span>? The patient will be notified via SMS.
    </p>

    <div class="flex justify-end gap-2 mt-6">
      <button class="px-6 py-2 bg-gray-700 text-blue-100 rounded-md hover:bg-gray-600" data-modal-close="delete-record"
        type="button">
        Go Back
      </button>

      <button class="px-6 py-2 bg-red-800 text-red-100 rounded-md hover:bg-red-700" type="submit">
        Cancel Appointment
      </button>
    </div>
  </form>
</x-modal-garic>
