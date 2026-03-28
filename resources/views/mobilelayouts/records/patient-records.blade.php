@extends("mobilelayouts.app")
@section("content")
<div
  x-data="{
    search: '',
    typeFilter: '',
    records: {{ Js::from($consultations) }},
    get filtered() {
      const s = this.search.toLowerCase();
      return this.records.filter(r => {
        const matchSearch = !s
          || r.document_type?.toLowerCase().includes(s)
          || r.created_at?.toLowerCase().includes(s);
        const matchType = !this.typeFilter ||  r.document_type?.toLowerCase().trim() === this.typeFilter;
        return matchSearch && matchType;
      });
    },
  }"
  class="h-full w-full max-w-[800px] min-w-[200px] flex-none mx-auto px-2 pb-6"
>

  {{-- Toolbar --}}
  <div class="flex gap-2 mb-4">
    <div class="relative flex-1">
      <input x-model="search" type="text" placeholder="Search documents…"
        class="w-full rounded-md text-sm px-3 py-2.5 ps-9 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400" />
      <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
      </svg>
    </div>

    <button type="button" data-modal-open="records-filter"
      class="flex items-center gap-2 px-3 py-2.5 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
      <i class="fa-solid fa-sliders fa-sm"></i>
      <span class="hidden sm:inline">Filter</span>
      <span x-show="typeFilter" class="w-2 h-2 rounded-full bg-blue-950 inline-block"></span>
    </button>
  </div>

  {{-- Active filter chip --}}
  <div class="flex flex-wrap gap-2 mb-3">
    <template x-if="typeFilter">
      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
        <span x-text="typeFilter.toUpperCase()"></span>
        <button @click="typeFilter = ''" class="hover:text-blue-950">
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
        <p class="text-sm text-gray-400">No documents found.</p>
      </div>
    </template>

    <template x-for="r in filtered" :key="r.id">
      <div class="w-full bg-white border border-gray-200 rounded-md p-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

        {{-- Info --}}
        <div class="flex flex-col gap-1.5">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="px-2 py-0.5 rounded text-xs font-semibold"
              :class="{
                'bg-blue-100 text-blue-800':      r.document_type === 'consultation',
                'bg-emerald-100 text-emerald-800': r.document_type === 'medical-certificate',
                'bg-amber-100 text-amber-800':    r.document_type === 'referral-letter',
                'bg-purple-100 text-purple-800':  r.document_type === 'prescription',
                'bg-gray-100 text-gray-700':      !['consultation','medical-certificate','referral-letter','prescription'].includes(r.document_type),
              }"
              x-text="r.document_type?.toUpperCase()">
            </span>
          </div>
          <p class="text-xs text-gray-400">
            <i class="fa-regular fa-clock fa-xs mr-1"></i>
            <span x-text="r.created_at ? new Date(r.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'long', year: 'numeric' }) + ' · ' + new Date(r.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }) : '—'"></span>
          </p>
        </div>

        {{-- Download --}}
        <a :href="`/consultations/${r.id}/${r.document_type}`"
          class="self-start sm:self-auto flex items-center gap-1.5 px-3 py-1.5 rounded-md bg-blue-950 hover:bg-blue-950/90 text-white text-xs font-medium transition-colors">
          <i class="fa-regular fa-file-pdf fa-sm"></i>
          View PDF
        </a>

      </div>
    </template>

  </div>

</div>

{{-- Filter modal --}}
<x-modal-garic id="records-filter" title="Filter" maxWidth="max-w-[380px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">FILTER DOCUMENTS</h1>
  </div>

  <div class="flex flex-col gap-1">
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide px-1 mb-1">Document Type</p>
    @php
      $typeOpts = [
  [
    'label' => 'All',
    'value' => '',
    'badge' => 'bg-gray-100 text-gray-700',
    'icon' => 'fa-solid fa-layer-group'
  ],
  [
    'label' => 'Consultation',
    'value' => 'consultation',
    'badge' => 'bg-blue-100 text-blue-800',
    'icon' => 'fa-solid fa-stethoscope'
  ],
];
    @endphp
    @foreach ($typeOpts as $opt)
      <button type="button"
        class="w-full flex items-center justify-between px-4 py-3 rounded-lg border transition-colors"
        :class="$root.typeFilter === '{{ $opt['value'] }}'
          ? 'border-blue-950 bg-blue-950/5 text-blue-950'
          : 'border-transparent hover:bg-gray-50 text-gray-700'"
        @click="$root.typeFilter = '{{ $opt['value'] }}'; window.Modal?.close('records-filter')">
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
@endsection
@push("scripts")
  @vite(["resources/js/components/modals/modal.js"])
@endpush
