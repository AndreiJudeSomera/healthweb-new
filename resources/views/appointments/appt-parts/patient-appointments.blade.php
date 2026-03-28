<div class="flex flex-col gap-2">
  <div class="w-full flex flex-row justify-between">
    <div class="w-full md:w-auto flex flex-row gap-2 items-center">
      {{-- Field: Search --}}
      <div class="relative flex-1 min-w-[260px] md:min-w-[320px]">
        <input
          class="w-full rounded-md text-sm p-2 ps-10 border border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent"
          id="patientSearch" type="text" name="patientSearch" placeholder="Search appointments ..." />
        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
        </svg>
      </div>
      {{-- Button: Filter --}}
      <button class="border border-blue-950 hover:bg-blue-50 py-2 px-4 rounded-md text-blue-950"
        data-modal-open="appointment-filter" type="button">
        <div class="flex gap-2 items-center">
          <i class="fa-solid fa-filter text-sm"></i>
          <p class="text-sm font-medium">Filter</p>
        </div>
      </button>
    </div>
    {{-- Group: Buttons --}}
    <div class="flex flex-row gap-2">
      {{-- <a class="bg-gray-700 hover:bg-gray-700 py-2 px-4 rounded-md text-blue-100 hover:cursor-pointer" href="#">
        <div class="flex flex-row gap-2 items-center">
          <i class="fa-solid fa-file fa-xs"></i>
          <p class="text-sm font-medium">Patient Documents</p>
        </div>
      </a> --}}
      <a class="border border-blue-950 hover:border-blue-950/75 hover:text-blue-950/75 py-2 px-4 rounded-md text-blue-950"
        href="{{ route("appointments.queue") }}">
        <div class="flex gap-2 items-center">
          <i class="fa-solid fa-rectangle-list text-sm"></i>
          <p class="text-sm font-medium">Queue View</p>
        </div>
      </a>
      <button class="bg-blue-950 hover:bg-blue-950/90 py-2 px-4 rounded-md text-blue-100" id="newAppointmentButton"
        data-modal-open="appointment-add" type="button">
        <div class="flex flex-row gap-2 items-center">
          <i class="fa-solid fa-plus fa-xs"></i>
          <p class="text-sm font-medium">New Appointment</p>
        </div>
      </button>
    </div>
  </div>
  @include("appointments.appt-parts.filter-modal")
  {{-- Group: Table -> Appointments --}}
  <div class="w-full min-w-[500px] [&_tr]:border-x [&_tr]:border-gray-200">
    <table class="w-full text-blue-950 hover order-column compact overflow-auto" id="patientAppointmentsTable">
    </table>
  </div>
</div>
