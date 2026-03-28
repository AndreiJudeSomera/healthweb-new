@extends("layouts.app")

@section("content")
  <div class="w-full flex flex-col gap-4">

    {{-- Header --}}
    <h1 class="text-xl font-bold text-blue-950">Audit Logs</h1>

    {{-- Search + Filter --}}
    <div class="w-full flex flex-wrap items-center justify-between gap-3">
      <div class="w-full md:w-auto flex flex-row gap-2 items-center">
        {{-- Search --}}
        <div class="relative flex-1 min-w-[260px] md:min-w-[320px]">
          <input
            class="w-full rounded-md text-sm p-2 ps-10 border border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent"
            id="logSearch" type="text" placeholder="Search logs ..." />
          <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
          </svg>
        </div>
        {{-- Filter button --}}
        <button class="border border-blue-950 hover:bg-blue-50 py-2 px-4 rounded-md text-blue-950"
          data-modal-open="audit-filter" type="button">
          <div class="flex gap-2 items-center">
            <i class="fa-solid fa-filter text-sm"></i>
            <p class="text-sm font-medium">Filter</p>
          </div>
        </button>
        {{-- Clear filter --}}
        @if (request()->hasAny(['action', 'role', 'date_from', 'date_to']))
          <a href="{{ route('audit.index') }}"
            class="border border-blue-950 text-blue-950 hover:bg-blue-50 text-sm font-medium px-4 py-2 rounded-md">
            Clear
          </a>
        @endif
      </div>
    </div>

    {{-- Filter Modal --}}
    <x-modal-garic id="audit-filter" title="Filter" maxWidth="max-w-[420px]">
      <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
        <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
        <h1 class="font-semibold text-xl">FILTER LOGS</h1>
      </div>

      <form method="GET" action="{{ route('audit.index') }}" class="flex flex-col gap-4">

        {{-- Role --}}
        <div x-data="auditRoleFilter()" class="flex flex-col gap-1">
          <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1">Role</p>
          <input type="hidden" name="role" x-ref="roleInput" value="{{ request('role') }}">
          @php
            $roles = [
              ['label' => 'All',       'value' => '',  'badge_class' => 'bg-gray-100 text-gray-700',       'icon' => 'fa-solid fa-layer-group'],
              ['label' => 'Patient',   'value' => '0', 'badge_class' => 'bg-emerald-100 text-emerald-800', 'icon' => 'fa-solid fa-user'],
              ['label' => 'Secretary', 'value' => '1', 'badge_class' => 'bg-amber-100 text-amber-800',     'icon' => 'fa-solid fa-id-card'],
              ['label' => 'Doctor',    'value' => '2', 'badge_class' => 'bg-indigo-100 text-indigo-800',   'icon' => 'fa-solid fa-user-doctor'],
            ];
          @endphp
          @foreach ($roles as $r)
            <button type="button"
              class="w-full flex items-center justify-between px-4 py-3 rounded-lg border transition-colors"
              :class="active === '{{ $r['value'] }}'
                ? 'border-blue-950 bg-blue-950/5 text-blue-950'
                : 'border-transparent hover:bg-gray-50 text-gray-700'"
              @click="pick('{{ $r['value'] }}')">
              <div class="flex items-center gap-3">
                <i class="{{ $r['icon'] }} fa-sm w-4 text-center"></i>
                <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $r['badge_class'] }}">{{ $r['label'] }}</span>
              </div>
              <i class="fa-solid fa-check fa-sm text-blue-950 transition-opacity"
                :class="active === '{{ $r['value'] }}' ? 'opacity-100' : 'opacity-0'"></i>
            </button>
          @endforeach
        </div>

        {{-- Action Type --}}
        <div class="flex flex-col gap-1">
          <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1" for="audit_action">Action Type</label>
          <select name="action" id="audit_action"
            class="w-full rounded-md text-sm p-2 border border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-900 bg-white">
            <option value="">All Actions</option>
            <option value="patient_record.create"       {{ request('action') === 'patient_record.create'       ? 'selected' : '' }}>Added Record (Patient)</option>
            <option value="patient_record.create_staff" {{ request('action') === 'patient_record.create_staff' ? 'selected' : '' }}>Added Record (Staff)</option>
            <option value="patient_record.update"       {{ request('action') === 'patient_record.update'       ? 'selected' : '' }}>Updated Record</option>
            <option value="patient_record.bind"         {{ request('action') === 'patient_record.bind'         ? 'selected' : '' }}>Linked Record</option>
            <option value="patient.bind_user"           {{ request('action') === 'patient.bind_user'           ? 'selected' : '' }}>Linked User Account</option>
            <option value="appointment_created"         {{ request('action') === 'appointment_created'         ? 'selected' : '' }}>Created Appointment</option>
            <option value="appointment_updated"         {{ request('action') === 'appointment_updated'         ? 'selected' : '' }}>Updated Appointment</option>
          </select>
        </div>

        {{-- Date Range --}}
        <div class="grid grid-cols-2 gap-3">
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1" for="audit_date_from">From</label>
            <input type="date" name="date_from" id="audit_date_from" value="{{ request('date_from') }}"
              class="w-full rounded-md text-sm p-2 border border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-900" />
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1" for="audit_date_to">To</label>
            <input type="date" name="date_to" id="audit_date_to" value="{{ request('date_to') }}"
              class="w-full rounded-md text-sm p-2 border border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-900" />
          </div>
        </div>

        {{-- Apply --}}
        <button type="submit"
          class="w-full bg-blue-950 hover:bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-md mt-1">
          Apply Filter
        </button>

      </form>
    </x-modal-garic>

    <script>
      document.addEventListener("alpine:init", () => {
        Alpine.data("auditRoleFilter", () => ({
          active: "{{ request('role') }}",
          pick(value) {
            this.active = value;
            this.$refs.roleInput.value = value;
          },
        }));
      });
    </script>

    {{-- Table --}}
    <div class="w-full min-w-[500px] [&_tr]:border-x [&_tr]:border-gray-200">
      <table class="min-w-full text-blue-950 hover order-column compact" id="auditLogsTable"></table>
    </div>

  </div>
@endsection

@push("scripts")
  @vite(["resources/js/pages/audit-index.js"])
@endpush
