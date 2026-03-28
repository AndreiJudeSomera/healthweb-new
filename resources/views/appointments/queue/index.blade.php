@extends("layouts.app")

@section("content")
  @php
    $selectedDate = \Carbon\Carbon::parse($date);
    $prevDate = $selectedDate->copy()->subWeekday()->toDateString();
    $nextDate = $selectedDate->copy()->addWeekday()->toDateString();
    $isToday = $selectedDate->isToday();
  @endphp

  <div class="flex flex-col gap-4">

    {{-- Top bar --}}
    <div class="w-full flex flex-wrap justify-between items-center gap-3">
      {{-- Live clock --}}
      <div class="flex gap-6 rounded-md border border-blue-950">
        <div class="px-4 py-2 bg-blue-950 text-blue-100 rounded-l-md font-semibold text-sm">
          CURRENT TIME
        </div>
        <input class="rounded-md text-blue-950 font-semibold text-sm pr-4 bg-transparent" type="text" readonly
          x-data="clock()" x-init="start()" x-model="formatted">
      </div>

      {{-- Actions --}}
      <div class="flex gap-2 items-center">
        <a class="border border-blue-950 hover:border-blue-950/75 hover:text-blue-950/75 py-2 px-4 rounded-md text-blue-950"
          href="{{ route("appointments.index") }}">
          <div class="flex gap-2 items-center">
            <i class="fa-solid fa-table-cells-large text-sm"></i>
            <p class="text-sm font-medium">Table View</p>
          </div>
        </a>
        <button class="bg-blue-950 hover:bg-blue-900 py-2 px-4 rounded-md text-blue-100"
          data-modal-open="appointment-add" type="button">
          <div class="flex flex-row gap-2 items-center">
            <i class="fa-solid fa-plus fa-xs"></i>
            <p class="text-sm font-medium">New Appointment</p>
          </div>
        </button>
      </div>
    </div>

    {{-- Date navigation --}}
    <div class="flex items-center gap-3">
      <a href="{{ route('appointments.queue', ['date' => $prevDate]) }}"
        class="w-8 h-8 flex items-center justify-center rounded-md border border-blue-950 text-blue-950 hover:bg-blue-50">
        <i class="fa-solid fa-chevron-left text-xs"></i>
      </a>

      <form method="GET" action="{{ route('appointments.queue') }}" class="flex items-center gap-2">
        <input type="date" name="date" value="{{ $date }}"
          onchange="this.form.submit()"
          class="border border-blue-950 rounded-md px-3 py-1.5 text-sm text-blue-950 font-semibold focus:outline-none cursor-pointer">
      </form>

      <a href="{{ route('appointments.queue', ['date' => $nextDate]) }}"
        class="w-8 h-8 flex items-center justify-center rounded-md border border-blue-950 text-blue-950 hover:bg-blue-50">
        <i class="fa-solid fa-chevron-right text-xs"></i>
      </a>

      @if (!$isToday)
        <a href="{{ route('appointments.queue') }}"
          class="text-xs text-blue-600 hover:underline">Today</a>
      @else
        <span class="text-xs font-semibold text-blue-950 bg-blue-100 px-2 py-0.5 rounded-full">Today</span>
      @endif

      <span class="text-sm text-gray-500">{{ $selectedDate->format('l, F j, Y') }}</span>
    </div>

    {{-- Queue board --}}
    <div class="flex flex-col border border-gray-200 rounded-md">
      <div class="flex justify-between bg-blue-950 text-blue-100 rounded-t-md py-2 px-5">
        <h2 class="font-semibold text-lg">Appointments Queue</h2>
        <span class="text-sm text-blue-300 self-center">
          {{ $appointments->flatten()->count() }} appointment{{ $appointments->flatten()->count() !== 1 ? 's' : '' }}
        </span>
      </div>

      <div class="w-full overflow-x-auto p-4">
        <div class="inline-grid grid-flow-col grid-rows-5 gap-2" style="grid-auto-columns: 280px;">
          @foreach ($slots as $slot)
            @php
              $slotAppts = $appointments->get($slot, collect());
              $timeLabel = \Carbon\Carbon::createFromFormat('H:i:s', $slot)->format('h:i A');
            @endphp

            @forelse ($slotAppts as $appt)
              @php
                $patientName = $appt->patient_pid
                   ? trim(($appt->patient?->first_name ?? '') . ' ' . ($appt->patient?->last_name ?? ''))
                     : ($appt->guest_name ?? '—'); // use guest_name if no patient
                $patientName = $patientName ?: $appt->patient_pid;
                $statusColor = match($appt->status) {
                  'approved' => 'bg-green-100 text-green-700',
                  'pending'  => 'bg-yellow-100 text-yellow-700',
                  default    => 'bg-gray-100 text-gray-600',
                };
              @endphp
              <div class="flex flex-row items-stretch border border-blue-950 rounded-md text-sm overflow-hidden">
                <div class="flex items-center justify-center px-3 bg-blue-950 text-blue-100 font-semibold text-xs text-center min-w-[64px]">
                  {{ $timeLabel }}
                </div>
                <div class="flex flex-col gap-1 p-2 flex-1 min-w-0">
                  <div class="flex items-start justify-between gap-1">
                    <p class="font-bold text-blue-950 truncate flex-1">{{ $patientName }}</p>
                    <div class="flex gap-1 shrink-0">
                      <button type="button"
                        class="w-6 h-6 flex items-center justify-center rounded border border-amber-700 text-amber-700 hover:bg-amber-50 transition"
                        data-queue-action="edit"
                        data-appt-id="{{ $appt->id }}"
                        data-modal-open="edit-record"
                        title="Edit">
                        <i class="fa-solid fa-pencil fa-xs"></i>
                      </button>
                      <button type="button"
                        class="w-6 h-6 flex items-center justify-center rounded border border-red-700 text-red-700 hover:bg-red-50 transition"
                        data-queue-action="delete"
                        data-appt-id="{{ $appt->id }}"
                        data-modal-open="delete-record"
                        title="Delete">
                        <i class="fa-solid fa-trash fa-xs"></i>
                      </button>
                    </div>
                  </div>
                  <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $appt->appointment_type }}</p>
                  @if ($appt->doctor?->user)
                    <p class="text-xs text-gray-500 truncate">
                      <i class="fa-solid fa-user-doctor mr-1"></i>{{ $appt->doctor->user->username }}
                    </p>
                  @endif
                  <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full self-start {{ $statusColor }}">
                    {{ ucfirst($appt->status) }}
                  </span>
                </div>
              </div>
            @empty
              <div class="flex flex-row items-stretch border border-dashed border-gray-300 rounded-md text-sm overflow-hidden opacity-60">
                <div class="flex items-center justify-center px-3 bg-gray-100 text-gray-500 font-semibold text-xs text-center min-w-[64px]">
                  {{ $timeLabel }}
                </div>
                <div class="flex items-center p-2 text-xs text-gray-400">Available</div>
              </div>
            @endforelse
          @endforeach
        </div>
      </div>
    </div>

  </div>

  {{-- Modals --}}
  @include("appointments.appt-parts.add-appointment-modal")
  @include("appointments.appt-parts.edit-appointment-modal")
  @include("appointments.appt-parts.delete-appointment-modal")

@endsection

@push("scripts")
  @vite(["resources/js/pages/appointment-queue.js"])
  <script>
    function clock() {
      return {
        formatted: '',

        start() {
          this.update();
          setInterval(() => this.update(), 1000);
        },

        update() {
          const now = new Date();
          const hours   = String(now.getHours()).padStart(2, '0');
          const minutes = String(now.getMinutes()).padStart(2, '0');
          const seconds = String(now.getSeconds()).padStart(2, '0');
          const datePart = now.toLocaleDateString('en-US', { month: 'long', day: '2-digit', year: 'numeric' });
          this.formatted = `${hours}:${minutes}:${seconds} ${datePart}`;
        }
      }
    }
  </script>
@endpush
