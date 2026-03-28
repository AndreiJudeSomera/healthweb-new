<x-modal-garic id="patient-filter" title="Filter" maxWidth="max-w-[380px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">FILTER PATIENTS</h1>
  </div>

  {{-- Sex section --}}
  <div class="flex flex-col gap-1" x-data="patientSexFilter()">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1 mb-1">Sex</p>

    @php
      $sexFilters = [
        ['label' => 'All',    'value' => '',       'badge_class' => 'bg-gray-100 text-gray-700',  'icon' => 'fa-solid fa-layer-group'],
        ['label' => 'Male',   'value' => 'Male',   'badge_class' => 'bg-blue-100 text-blue-800',  'icon' => 'fa-solid fa-mars'],
        ['label' => 'Female', 'value' => 'Female', 'badge_class' => 'bg-pink-100 text-pink-800',  'icon' => 'fa-solid fa-venus'],
      ];
    @endphp

    @foreach ($sexFilters as $f)
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

  {{-- Patient Type section --}}
  <div class="flex flex-col gap-1" x-data="patientTypeFilter()">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1 mb-1">Patient Type</p>

    @php
      $typeFilters = [
        ['label' => 'All',       'value' => '',    'badge_class' => 'bg-gray-100 text-gray-700',    'icon' => 'fa-solid fa-layer-group'],
        ['label' => 'New',       'value' => 'new', 'badge_class' => 'bg-emerald-100 text-emerald-800', 'icon' => 'fa-solid fa-user-plus'],
        ['label' => 'Returning', 'value' => 'old', 'badge_class' => 'bg-blue-100 text-blue-800',    'icon' => 'fa-solid fa-rotate-left'],
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
    Alpine.data("patientSexFilter", () => ({
      active: "",
      apply(value) {
        this.active = value;
        window.filterPatientsBySex?.(value);
        window.Modal?.close("patient-filter");
      },
    }));

    Alpine.data("patientTypeFilter", () => ({
      active: "",
      apply(value) {
        this.active = value;
        window.filterPatientsByType?.(value);
        window.Modal?.close("patient-filter");
      },
    }));
  });
</script>
