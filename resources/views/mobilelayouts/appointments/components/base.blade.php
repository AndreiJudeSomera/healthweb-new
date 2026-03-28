
@php
  $statusFilters = ['', 'pending', 'approved', 'completed', 'cancelled'];
  $typeFilters   = ['', 'consultation', 'follow-up', 'prescription', 'medical-certificate', 'referral-letter', 'other'];
@endphp

<div
  x-data="{
    search: '',
    statusFilter: '', 
    typeFilter: '',
    appointments: {{ Js::from($appointments) }},
    get filtered() {
      const s = this.search.toLowerCase();
      return this.appointments.filter(a => {
        const matchSearch = !s
          || a.appointment_type?.toLowerCase().includes(s)
          || a.appointment_date?.includes(s)
          || a.status?.toLowerCase().includes(s);
        const matchStatus = !this.statusFilter || a.status?.toLowerCase() === this.statusFilter;
        const matchType   = !this.typeFilter   || a.appointment_type?.toLowerCase() === this.typeFilter;
        return matchSearch && matchStatus && matchType;
      });
    },
    cancel(id) {
      if (!confirm('Are you sure you want to cancel this appointment?')) return;
      const csrf = document.querySelector('meta[name=csrf-token]')?.content;
      fetch(`/appointments/${id}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
      })
        .then(r => r.json().then(d => ({ ok: r.ok, d })))
        .then(({ ok, d }) => {
          if (!ok) throw new Error(d.message || 'Something went wrong.');
          toastr.success(d.message);
          this.appointments = this.appointments.filter(a => a.id !== id);
        })
        .catch(e => toastr.error(e.message));
    },
  }"
  class="h-full w-full max-w-[800px] min-w-[200px] flex-none mx-auto px-2 pb-6"
>

  {{-- Toolbar --}}
  <div class="flex gap-2 mb-4">
    <div class="relative flex-1">
      <input x-model="search" type="text" placeholder="Search appointments…"
        class="w-full rounded-md text-sm px-3 py-2.5 ps-9 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />
      <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
      </svg>
    </div>

    <button type="button" data-modal-open="appointment-filter"
      class="flex items-center gap-2 px-3 py-2.5 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
      <i class="fa-solid fa-sliders fa-sm"></i>
      <span class="hidden sm:inline">Filter</span>
      <span x-show="statusFilter || typeFilter"
        class="w-2 h-2 rounded-full bg-blue-950 inline-block"></span>
    </button>

    <button type="button" data-modal-open="create-appointment"
      class="flex items-center gap-2 px-3 py-2.5 rounded-md bg-blue-950 hover:bg-blue-950/90 text-white text-sm font-medium transition-colors">
      <i class="fa-solid fa-plus fa-sm"></i>
      <span class="hidden sm:inline">New Appointment</span>
    </button>
  </div>

  {{-- Active filter chips --}}
  <div class="flex flex-wrap gap-2 mb-3">
    <template x-if="statusFilter">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
        <span x-text="statusFilter.toUpperCase()"></span>
        <button @click="statusFilter = ''; window.filterAppointmentsByStatus?.('')" class="hover:text-blue-950">
          <i class="fa-solid fa-xmark fa-xs"></i>
        </button>
      </span>
    </template>
    <template x-if="typeFilter">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
        <span x-text="typeFilter.toUpperCase()"></span>
        <button @click="typeFilter = ''; window.filterAppointmentsByType?.('')" class="hover:text-blue-950">
          <i class="fa-solid fa-xmark fa-xs"></i>
        </button>
      </span>
    </template>
  </div>

  {{-- List --}}
  <div class="flex flex-col gap-2">

    <template x-if="filtered.length === 0">
      <div class="w-full bg-white border border-gray-200 rounded-md px-4 py-10 flex flex-col gap-3 items-center text-center">
        <img class="size-16 opacity-50" src="{{ asset('assets/illustrations/no-data.svg') }}" alt="">
        <p class="text-sm text-gray-400">No appointments found.</p>
      </div>
    </template>

    <template x-for="a in filtered" :key="a.id">
      <div class="w-full bg-white border border-gray-200 rounded-md p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        {{-- Info --}}
        <div class="flex flex-col gap-1.5">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="text-sm font-semibold text-blue-950 uppercase" x-text="a.appointment_type"></span>
            <span class="px-2 py-0.5 rounded text-xs font-semibold"
              :class="{
                'bg-amber-100 text-amber-800':   a.status === 'pending',
                'bg-emerald-100 text-emerald-800': a.status === 'approved',
                'bg-blue-100 text-blue-800':     a.status === 'completed',
                'bg-red-100 text-red-800':       a.status === 'cancelled',
                'bg-gray-100 text-gray-700':     !['pending','approved','completed','cancelled'].includes(a.status),
              }"
              x-text="a.status?.toUpperCase()">
            </span>
          </div>
          <div class="flex flex-wrap gap-x-4 gap-y-0.5 text-xs text-gray-500">
            <span>
              <i class="fa-regular fa-calendar fa-xs mr-1"></i>
              <span x-text="a.appointment_date ? new Date(a.appointment_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' }) : '—'"></span>
            </span>
            <span>
              <i class="fa-regular fa-clock fa-xs mr-1"></i>
              <span x-text="a.appointment_time ? new Date('1970-01-01T' + a.appointment_time).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }) : '—'"></span>
            </span>
          </div>
        </div>

        {{-- Cancel button --}}
        <template x-if="['pending', 'approved'].includes(a.status)">
          <button type="button" @click="cancel(a.id)"
            class="self-start sm:self-auto flex items-center gap-1.5 border-2 border-red-700 text-red-700 hover:bg-red-50 rounded-md px-3 py-1.5 text-xs font-medium transition-colors">
            <i class="fa-solid fa-xmark fa-xs"></i>
            Cancel
          </button>
        </template>

      </div>
    </template>

  </div>

</div>

{{-- Filter modal --}}
<x-modal-garic id="appointment-filter" title="Filter" maxWidth="max-w-[380px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">FILTER APPOINTMENTS</h1>
  </div>

  {{-- Status --}}
  <div class="flex flex-col gap-1" x-data="{ get active() { return $root.statusFilter ?? '' } }">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1 mb-1">Status</p>
    @php
      $statusOpts = [
        ['label' => 'All',       'value' => '',          'badge' => 'bg-gray-100 text-gray-700',      'icon' => 'fa-solid fa-layer-group'],
        ['label' => 'Pending',   'value' => 'pending',   'badge' => 'bg-amber-100 text-amber-800',    'icon' => 'fa-solid fa-clock'],
        ['label' => 'Approved',  'value' => 'approved',  'badge' => 'bg-emerald-100 text-emerald-800','icon' => 'fa-solid fa-circle-check'],
        ['label' => 'Completed', 'value' => 'completed', 'badge' => 'bg-blue-100 text-blue-800',      'icon' => 'fa-solid fa-clipboard-check'],
        ['label' => 'Cancelled', 'value' => 'cancelled', 'badge' => 'bg-red-100 text-red-800',        'icon' => 'fa-solid fa-ban'],
      ];
    @endphp
    @foreach ($statusOpts as $opt)
      <button type="button"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg border transition-colors"
        :class="$root.statusFilter === '{{ $opt['value'] }}'
          ? 'border-blue-950 bg-blue-950/5 text-blue-950'
          : 'border-transparent hover:bg-gray-50 text-gray-700'"
        @click="$root.statusFilter = '{{ $opt['value'] }}'; window.Modal?.close('appointment-filter')">
        <div class="flex items-center gap-3">
          <i class="{{ $opt['icon'] }} fa-sm w-4 text-center"></i>
          <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $opt['badge'] }}">{{ $opt['label'] }}</span>
        </div>
        <i class="fa-solid fa-check fa-sm text-blue-950 transition-opacity"
          :class="$root.statusFilter === '{{ $opt['value'] }}' ? 'opacity-100' : 'opacity-0'"></i>
      </button>
    @endforeach
  </div>

  <hr class="my-3 border-gray-100">

  {{-- Type --}}
  <div class="flex flex-col gap-1">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1 mb-1">Appointment Type</p>
    @php
      $typeOpts = [
        ['label' => 'All',                 'value' => '',                   'badge' => 'bg-gray-100 text-gray-700',      'icon' => 'fa-solid fa-layer-group'],
        ['label' => 'Consultation',        'value' => 'consultation',       'badge' => 'bg-blue-100 text-blue-800',      'icon' => 'fa-solid fa-stethoscope'],
        ['label' => 'Follow-up',           'value' => 'follow-up',          'badge' => 'bg-teal-100 text-teal-800',      'icon' => 'fa-solid fa-rotate-right'],
        ['label' => 'Prescription',        'value' => 'prescription',       'badge' => 'bg-purple-100 text-purple-800',  'icon' => 'fa-solid fa-pills'],
        ['label' => 'Medical Certificate', 'value' => 'medical-certificate','badge' => 'bg-emerald-100 text-emerald-800','icon' => 'fa-solid fa-notes-medical'],
        ['label' => 'Referral Letter',     'value' => 'referral-letter',    'badge' => 'bg-amber-100 text-amber-800',    'icon' => 'fa-solid fa-user-doctor'],
        ['label' => 'Other',               'value' => 'other',              'badge' => 'bg-gray-100 text-gray-700',      'icon' => 'fa-solid fa-ellipsis'],
      ];
    @endphp
    @foreach ($typeOpts as $opt)
      <button type="button"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg border transition-colors"
        :class="$root.typeFilter === '{{ $opt['value'] }}'
          ? 'border-blue-950 bg-blue-950/5 text-blue-950'
          : 'border-transparent hover:bg-gray-50 text-gray-700'"
        @click="$root.typeFilter = '{{ $opt['value'] }}'; window.Modal?.close('appointment-filter')">
        <div class="flex items-center gap-3">
          <i class="{{ $opt['icon'] }} fa-sm w-4 text-center"></i>
          <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $opt['badge'] }}">{{ $opt['label'] }}</span>
        </div>
        <i class="fa-solid fa-check fa-sm text-blue-950 transition-opacity"
          :class="$root.typeFilter === '{{ $opt['value'] }}' ? 'opacity-100' : 'opacity-0'"></i>
      </button>
    @endforeach
  </div>
</x-modal-garic>