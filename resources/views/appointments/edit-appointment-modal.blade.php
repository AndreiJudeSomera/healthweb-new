<x-modal-garic id="edit-record" title="Edit Appointment" maxWidth="max-w-[520px]">
  <form id="edit_appointment_form">
    @csrf

    <input id="edit_appt_id" type="hidden" name="id" value="" />
    <input id="edit_appt_pid" type="hidden" name="patient_id" value="" />

    <div class="w-full flex flex-col gap-3 text-blue-950">
      <div class="flex flex-col gap-1">
        <label class="text-sm" for="edit_appointment_type">Appointment Type</label>
        <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="edit_appointment_type"
          name="appointment_type" required>
          <option disabled selected hidden>Select Appointment Type</option>
          <option value="consultation">Consultation</option>
          <option value="follow-up">Follow Up</option>
          <option value="prescription">Prescription</option>
          <option value="medical-certificate">Medical Certificate</option>
          <option value="referral-letter">Referral Letter</option>
          <option value="other">Other</option>
        </select>
      </div>

      <div class="flex flex-col gap-1">
        <label class="text-sm" for="edit_status">Status</label>
        <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="edit_status" name="status"
          required>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
        </select>
      </div>

      <div class="flex flex-col gap-1">
        <label class="text-sm" for="edit_attended_by">Attended By</label>
        <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="edit_attended_by"
          name="attended_by">
          <option value="">—</option>
          <option value="1">Doctor 1</option>
        </select>
      </div>

      <div class="flex justify-end gap-2 mt-4">
        <button class="px-6 py-2 bg-gray-700 text-blue-100 rounded-md hover:bg-gray-600" id="editAppointmentCloseBtn"
          data-modal-close="edit-record" type="button">
          Discard
        </button>

        <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-900" type="submit">
          Save
        </button>
      </div>
    </div>
  </form>
</x-modal-garic>
