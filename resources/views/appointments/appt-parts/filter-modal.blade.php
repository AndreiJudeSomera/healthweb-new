<x-modal-garic id="appointment-filter" title="Filter" maxWidth="max-w-[380px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">FILTER APPOINTMENTS</h1>
  </div>

  {{-- Status section --}}
  <div class="flex flex-col gap-1" x-data="appointmentStatusFilter()">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1 mb-1">Status</p>

    @php
      $statusFilters = [
        ['label' => 'All',       'value' => '',          'badge_class' => 'bg-gray-100 text-gray-700',    'icon' => 'fa-solid fa-layer-group'],
        ['label' => 'Pending',   'value' => 'PENDING',   'badge_class' => 'bg-amber-100 text-amber-800',  'icon' => 'fa-solid fa-clock'],
        ['label' => 'Approved',  'value' => 'APPROVED',  'badge_class' => 'bg-emerald-100 text-emerald-800', 'icon' => 'fa-solid fa-circle-check'],
        ['label' => 'Completed', 'value' => 'COMPLETED', 'badge_class' => 'bg-blue-100 text-blue-800',    'icon' => 'fa-solid fa-clipboard-check'],
        ['label' => 'Cancelled', 'value' => 'CANCELLED', 'badge_class' => 'bg-red-100 text-red-800',      'icon' => 'fa-solid fa-ban'],
      ];
    @endphp

    @foreach ($statusFilters as $f)
      <button type="button"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg border transition-colors"
        :class="active === '{{ $f['value'] }}'
          ? 'border-blue-950 bg-blue-950/5 text-blue-950'
          : 'border-transparent hover:bg-gray-50 text-gray-700'"
        @click="apply('{{ $f['value'] }}')">
        <div class="flex items-center gap-3">
          <i class="{{ $f['icon'] }} fa-sm w-4 text-center"></i>
          <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $f['badge_class'] }}">{{ $f['label'] }}</span>
        </div>
        <i class="fa-solid fa-check fa-sm text-blue-950 transition-opacity"
          :class="active === '{{ $f['value'] }}' ? 'opacity-100' : 'opacity-0'"></i>
      </button>
    @endforeach
  </div>

  <hr class="my-3 border-gray-100">

  {{-- Type section --}}
  <div class="flex flex-col gap-1" x-data="appointmentTypeFilter()">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1 mb-1">Appointment Type</p>

    @php
      $typeFilters = [
        ['label' => 'All',                 'value' => '',                  'badge_class' => 'bg-gray-100 text-gray-700',    'icon' => 'fa-solid fa-layer-group'],
        ['label' => 'Consultation',        'value' => 'CONSULTATION',      'badge_class' => 'bg-blue-100 text-blue-800',    'icon' => 'fa-solid fa-stethoscope'],
        ['label' => 'Follow-up',           'value' => 'FOLLOW-UP',         'badge_class' => 'bg-teal-100 text-teal-800',    'icon' => 'fa-solid fa-rotate-right'],
        ['label' => 'Prescription',        'value' => 'PRESCRIPTION',      'badge_class' => 'bg-purple-100 text-purple-800','icon' => 'fa-solid fa-pills'],
        ['label' => 'Medical Certificate', 'value' => 'MEDICAL-CERTIFICATE','badge_class' => 'bg-emerald-100 text-emerald-800','icon' => 'fa-solid fa-notes-medical'],
        ['label' => 'Referral Letter',     'value' => 'REFERRAL-LETTER',   'badge_class' => 'bg-amber-100 text-amber-800',  'icon' => 'fa-solid fa-user-doctor'],
        ['label' => 'Other',               'value' => 'OTHER',             'badge_class' => 'bg-gray-100 text-gray-700',    'icon' => 'fa-solid fa-ellipsis'],
      ];
    @endphp

    @foreach ($typeFilters as $f)
      <button type="button"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg border transition-colors"
        :class="active === '{{ $f['value'] }}'
          ? 'border-blue-950 bg-blue-950/5 text-blue-950'
          : 'border-transparent hover:bg-gray-50 text-gray-700'"
        @click="apply('{{ $f['value'] }}')">
        <div class="flex items-center gap-3">
          <i class="{{ $f['icon'] }} fa-sm w-4 text-center"></i>
          <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $f['badge_class'] }}">{{ $f['label'] }}</span>
        </div>
        <i class="fa-solid fa-check fa-sm text-blue-950 transition-opacity"
          :class="active === '{{ $f['value'] }}' ? 'opacity-100' : 'opacity-0'"></i>
      </button>
    @endforeach
  </div>
</x-modal-garic>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data("appointmentStatusFilter", () => ({
      active: "",
      apply(value) {
        this.active = value;
        window.filterAppointmentsByStatus?.(value);
        window.Modal?.close("appointment-filter");
      },
    }));

    Alpine.data("appointmentTypeFilter", () => ({
      active: "",
      apply(value) {
        this.active = value;
        window.filterAppointmentsByType?.(value);
        window.Modal?.close("appointment-filter");
      },
    }));
  });
</script>
