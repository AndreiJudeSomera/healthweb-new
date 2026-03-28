@extends("mobilelayouts.app")
@section("content")
<div class="h-full w-full max-w-[600px] min-w-[200px] flex-none mx-auto px-2 pb-6 flex flex-col gap-4">

  {{-- Header --}}
  <div>
    <p class="text-gray-500 text-sm">Welcome back,</p>
    <h1 class="text-blue-950 font-bold text-2xl">{{ $first_name ?? 'Patient' }}</h1>
  </div>

  {{-- KPI cards --}}
  <div class="grid grid-cols-2 gap-3">
    <div class="bg-white border border-gray-200 rounded-md p-4 flex flex-col gap-1">
      <div class="flex items-center justify-between mb-1">
        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Upcoming</span>
        <div class="w-7 h-7 rounded-full bg-amber-100 flex items-center justify-center">
          <i class="fa-solid fa-user-clock text-amber-600 text-xs"></i>
        </div>
      </div>
      <p class="text-2xl font-bold text-blue-950">{{ $upcomingCount ?? 0 }}</p>
      <p class="text-xs text-gray-400">appointment{{ ($upcomingCount ?? 0) !== 1 ? 's' : '' }}</p>
    </div>

    <div class="bg-white border border-gray-200 rounded-md p-4 flex flex-col gap-1">
      <div class="flex items-center justify-between mb-1">
        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Documents</span>
        <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
          <i class="fa-regular fa-file-lines text-blue-600 text-xs"></i>
        </div>
      </div>
      <p class="text-2xl font-bold text-blue-950">{{ $totalDocuments ?? 0 }}</p>
      <p class="text-xs text-gray-400">total record{{ ($totalDocuments ?? 0) !== 1 ? 's' : '' }}</p>
    </div>
  </div>

  {{-- Next appointment --}}
  @if (!empty($nextAppointment))
    <div class="bg-blue-950 rounded-md p-4 text-white flex flex-col gap-2">
      <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-blue-300 uppercase tracking-wide">Next Appointment</span>
        <span class="px-2 py-0.5 rounded text-xs font-semibold
          {{ $nextAppointment->status === 'approved' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
          {{ strtoupper($nextAppointment->status) }}
        </span>
      </div>
      <p class="text-lg font-bold">{{ strtoupper($nextAppointment->appointment_type) }}</p>
      <div class="flex flex-wrap gap-x-4 gap-y-0.5 text-sm text-blue-200">
        <span>
          <i class="fa-regular fa-calendar fa-xs mr-1"></i>
          {{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('d F Y') }}
        </span>
        <span>
          <i class="fa-regular fa-clock fa-xs mr-1"></i>
          {{ \Carbon\Carbon::parse($nextAppointment->appointment_time)->format('h:i A') }}
        </span>
      </div>
      <a href="{{ route('patient.appointments') }}"
        class="mt-1 self-start text-xs text-blue-300 hover:text-white transition-colors">
        View all appointments <i class="fa-solid fa-arrow-right fa-xs ml-0.5"></i>
      </a>
    </div>
  @else
    <div class="bg-white border border-gray-200 rounded-md p-4 flex items-center gap-4">
      <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-user-clock text-gray-400"></i>
      </div>
      <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-blue-950">No upcoming appointments</p>
        <p class="text-xs text-gray-400">Book one anytime from the Appointments page.</p>
      </div>
      <a href="{{ route('patient.appointments') }}"
        class="flex-shrink-0 text-xs font-medium text-blue-950 hover:underline">
        Book <i class="fa-solid fa-arrow-right fa-xs ml-0.5"></i>
      </a>
    </div>
  @endif

  {{-- Recent documents --}}
  @if (!empty($recentDocuments) && $recentDocuments->isNotEmpty())
    <div class="bg-white border border-gray-200 rounded-md overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <span class="text-sm font-semibold text-blue-950">Recent Documents</span>
        <a href="{{ route('patient.records') }}" class="text-xs text-blue-700 hover:underline">View all</a>
      </div>
      @foreach ($recentDocuments as $doc)
        @php
          $badgeClass = match($doc->document_type) {
            'consultation'        => 'bg-blue-100 text-blue-800',
            'prescription'        => 'bg-purple-100 text-purple-800',
            'medical-certificate' => 'bg-emerald-100 text-emerald-800',
            'referral-letter'     => 'bg-amber-100 text-amber-800',
            default               => 'bg-gray-100 text-gray-700',
          };
        @endphp
        <a href="/consultations/{{ $doc->id }}/{{ $doc->document_type }}"
          class="flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0">
          <div class="flex items-center gap-3 min-w-0">
            <span class="px-2 py-0.5 rounded text-xs font-semibold flex-shrink-0 {{ $badgeClass }}">
              {{ strtoupper($doc->document_type) }}
            </span>
            <span class="text-xs text-gray-400 truncate">
              {{ \Carbon\Carbon::parse($doc->created_at)->format('d F Y') }}
            </span>
          </div>
          <i class="fa-regular fa-file-pdf text-gray-400 fa-sm flex-shrink-0 ml-2"></i>
        </a>
      @endforeach
    </div>
  @endif

  {{-- Clinic Hours --}}
  <div class="bg-white border border-gray-200 rounded-md p-4" x-data="clinicHoursWidget()" x-init="init()">
    <div class="flex items-center gap-2 text-blue-950 mb-3">
      <i class="fa-regular fa-clock text-sm"></i>
      <h2 class="font-semibold text-sm">Clinic Hours</h2>
    </div>

    <template x-if="loading">
      <p class="text-sm text-gray-400 py-2">Loading schedule...</p>
    </template>

    <template x-if="!loading">
      <div>
        <template x-for="day in schedule" :key="day.id">
          <div class="flex items-center justify-between text-sm py-2 border-b border-gray-100 last:border-0">
            <span class="text-gray-600 font-medium w-28 text-xs" x-text="day.day_name"></span>
            <template x-if="day.is_open">
              <span class="text-blue-700 text-xs font-medium"
                x-text="formatTime(day.open_time) + ' – ' + formatTime(day.close_time)"></span>
            </template>
            <template x-if="!day.is_open">
              <span class="text-red-400 text-xs font-medium flex items-center gap-1">
                <i class="fa-solid fa-xmark text-xs"></i> No Clinic
              </span>
            </template>
          </div>
        </template>
      </div>
    </template>
  </div>

  {{-- Bottom spacer --}}
  <div class="h-16 flex-shrink-0"></div>

</div>

<script>
  function clinicHoursWidget() {
    return {
      schedule: [],
      loading: true,
      async init() {
        try {
          const res = await fetch('/api/clinic-schedule');
          const json = await res.json();
          this.schedule = json.data;
        } catch (e) {
          console.error('Failed to load clinic schedule', e);
        } finally {
          this.loading = false;
        }
      },
      formatTime(time) {
        if (!time) return '';
        const [h, m] = time.split(':');
        const hour = parseInt(h);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${m} ${ampm}`;
      },
    };
  }
</script>
@endsection
