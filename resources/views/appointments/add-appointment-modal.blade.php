<x-modal-garic id="appointment-add" title="New Appointment" maxWidth="max-w-[480px]">
  <form id="record_add_form" method="POST" action="">
    @csrf
    <div class="w-full flex flex-col gap-2">
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="patient_pid">For Patient</label>
          <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="patient_pid"
            name="patient_pid">
          </select>
        </div>
      </div>
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="appointment_type">Appointment Type</label>
          <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="appointment_type"
            name="appointment_type">
            <div class="max-h-[50px] overflow-y-auto">
              <option default disabled>Select Appointment Type</option>
              <option value="consultation">Consultation</option>
              <option value="follow-up">Follow Up</option>
              <option value="prescription">Prescription</option>
              <option value="medical-certificate">Medical Certificate</option>
              <option value="referral-letter">Referral Letter</option>
              <option value="other">Other</option>
            </div>
          </select>
        </div>
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="status">Status</label>
          <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="status" name="status">
            <option disabled>Select Appointment Status</option>
            <option value="pending" default>Pending</option>
            <option value="approved" default>Approved</option>
            <option value="completed" default>Completed</option>
            <option value="cancelled" default>Cancelled</option>
          </select>
        </div>
      </div>
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="attended_by">Attended By</label>
          <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="attended_by"
            name="attended_by">
            <option disabled selected>Select Attended By</option>
          </select>
        </div>
      </div>
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="appointment_date">Appointment Date</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="appointment_date" type="date"
            name="appointment_date" required />
        </div>
      </div>
      <div class="flex flex-col gap-2 w-full">
        <p class="text-sm">Appointment Time</p>
        <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="appointment_time"
          name="appointment_time" required>
          <option default disabled>Select Appointment Time</option>
        </select>
      </div>
    </div>
    {{-- MODAL: FOOTER (CANCEL/SAVE) --}}
    <div class="flex justify-end gap-2 mt-6">
      <div
        class="px-6 h-[2.5rem] bg-gray-700 text-blue-100 rounded-md hover:cursor-pointer hover:bg-gray-600 flex items-center"
        data-modal-close="appointment-add" type="button">
        <span class="text-sm">
          Cancel
        </span>
      </div>
      <button
        class="px-6 h-[2.5rem] bg-blue-950 text-blue-100 rounded-md hover:cursor-pointer hover:bg-blue-900 flex items-center"
        type="submit">
        <span class="text-sm">
          Continue
        </span>
        <i class="fa-solid fa-arrow-right ms-2 fa-sm"></i>
      </button>
    </div>
  </form>
</x-modal-garic>
