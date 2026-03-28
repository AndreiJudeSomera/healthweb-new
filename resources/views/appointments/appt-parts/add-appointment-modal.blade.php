<x-modal-garic id="appointment-add" title="New Appointment" maxWidth="max-w-[480px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">NEW APPOINTMENT</h1>
  </div>
  <form id="record_add_form" method="POST" action="" x-data="{ isGuest: false }">
    @csrf
    <input type="hidden" name="is_guest" :value="isGuest ? '1' : ''">

    <div class="w-full flex flex-col gap-3">

      {{-- Toggle: no existing record --}}
      <label class="flex items-center gap-2 cursor-pointer select-none w-fit">
        <div class="relative">
          <input type="checkbox" x-model="isGuest" id="appt_no_record" class="sr-only peer">
          <div class="w-10 h-5 bg-gray-200 rounded-full peer-checked:bg-blue-950 transition-colors"></div>
          <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
        </div>
        <span class="text-sm text-blue-950">Patient has no existing record</span>
      </label>

      {{-- Existing patient select --}}
      <div x-show="!isGuest" x-cloak class="flex flex-col gap-1 w-full text-blue-950">
        <label class="text-sm" for="patient_pid">For Patient</label>
        <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="patient_pid"
          name="patient_pid">
        </select>
      </div>

      {{-- Guest patient fields --}}
      <div x-show="isGuest" x-cloak class="flex flex-col gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="guest_name">Patient Name</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="guest_name"
            type="text" name="guest_name" placeholder="Full name" />
        </div>
        <div class="flex flex-row gap-2">
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="guest_age">Age</label>
            <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="guest_age"
              type="number" name="guest_age" min="1" max="150" placeholder="Age" />
          </div>
          <div class="flex flex-col gap-1 w-full">
            <label class="text-sm" for="guest_sex">Sex</label>
            <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="guest_sex"
              name="guest_sex">
              <option value="" disabled selected>Select Sex</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
        </div>
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="guest_contact">Cellphone Number</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="guest_contact"
            type="tel" name="guest_contact" placeholder="e.g. 09171234567" maxlength="11" />
        </div>
      </div>

      {{-- Appointment Type + Status --}}
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="appointment_type">Appointment Type</label>
          <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="appointment_type"
            name="appointment_type">
            <option default disabled>Select Appointment Type</option>
            <option value="consultation">Consultation</option>
            <option value="follow-up">Follow Up</option>
            <option value="prescription">Prescription</option>
            <option value="medical-certificate">Medical Certificate</option>
            <option value="referral-letter">Referral Letter</option>
            <option value="other">Other</option>
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

      {{-- Attended By --}}
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="attended_by">Attended By</label>
          <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="attended_by"
            name="attended_by">
            <option disabled selected>Select Attended By</option>
          </select>
        </div>
      </div>

      {{-- Date --}}
      <div class="flex flex-row w-full gap-2 text-blue-950">
        <div class="flex flex-col gap-1 w-full">
          <label class="text-sm" for="appointment_date">Appointment Date</label>
          <input class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="appointment_date" type="date"
            name="appointment_date" required />
        </div>
      </div>

      {{-- Time --}}
      <div class="flex flex-col gap-2 w-full">
        <p class="text-sm text-blue-950">Appointment Time</p>
        <select class="p-2 w-full border-blue-950 border-2 rounded-md font-medium" id="appointment_time"
          name="appointment_time" required>
          <option default disabled>Select Appointment Time</option>
        </select>
      </div>
    </div>

    {{-- Footer --}}
    <div class="flex justify-end gap-2 mt-6">
      <div
        class="px-6 h-[2.5rem] bg-gray-700 text-blue-100 rounded-md hover:cursor-pointer hover:bg-gray-600 flex items-center"
        data-modal-close="appointment-add" type="button">
        <span class="text-sm">Cancel</span>
      </div>
      <button
        class="px-6 h-[2.5rem] bg-blue-950 text-blue-100 rounded-md hover:cursor-pointer hover:bg-blue-900 flex items-center"
        type="submit">
        <span class="text-sm">Continue</span>
        <i class="fa-solid fa-arrow-right ms-2 fa-sm"></i>
      </button>
    </div>
  </form>
</x-modal-garic>
