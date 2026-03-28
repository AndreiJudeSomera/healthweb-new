<x-modal-garic id="create-appointment" title="New Appointment" maxWidth="max-w-[480px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">NEW APPOINTMENT</h1>
  </div>

  <form id="record_add_form" method="POST" action="">
    @csrf
    <input type="text" value="{{ $patient['pid'] }}" name="patient_pid" hidden />
    <input id="attended_by" name="attended_by" value="" hidden />
    <input id="status" name="status" value="pending" hidden />

    <div class="w-full flex flex-col gap-4 text-blue-950">
      <div class="flex flex-col gap-1.5">
        <label class="text-sm font-medium" for="appointment_type">Appointment Type</label>
        <select class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          id="appointment_type" name="appointment_type">
          <option default disabled selected>Select Appointment Type</option>
          <option value="consultation">Consultation</option>
          <option value="follow-up">Follow Up</option>
          <option value="prescription">Prescription</option>
          <option value="medical-certificate">Medical Certificate</option>
          <option value="referral-letter">Referral Letter</option>
          <option value="other">Other</option>
        </select>
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="text-sm font-medium" for="appointment_date">Appointment Date</label>
        <input class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          id="appointment_date" type="date" name="appointment_date" required />
      </div>

      <div class="flex flex-col gap-1.5">
        <label class="text-sm font-medium" for="appointment_time">Appointment Time</label>
        <select class="w-full border border-gray-300 rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
          id="appointment_time" name="appointment_time" required>
          <option default disabled selected>Select Appointment Time</option>
        </select>
      </div>
    </div>

    <div class="flex justify-end gap-2 mt-6">
      <div data-modal-close="create-appointment"
        class="px-5 py-2.5 text-sm font-medium bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 cursor-pointer transition-colors flex items-center">
        Cancel
      </div>
      <button type="submit"
        class="px-5 py-2.5 text-sm font-medium bg-blue-950 text-white rounded-md hover:bg-blue-950/90 transition-colors flex items-center gap-2">
        Continue
        <i class="fa-solid fa-arrow-right fa-xs"></i>
      </button>
    </div>
  </form>
</x-modal-garic>
