@props(["id", "title" => "", "maxWidth" => "max-w-lg"])

<div class="fixed inset-0 z-50 hidden" id="{{ $id }}" aria-hidden="true">
  {{-- Backdrop --}}
  <div class="absolute inset-0 bg-black/50" data-modal-close="{{ $id }}"></div>

  {{-- Dialog --}}
  <div class="relative min-h-full flex items-center justify-center p-4">
    <div class="bg-white w-full {{ $maxWidth }} rounded-lg shadow-lg overflow-hidden max-h-[90vh] flex flex-col">
      <div class="flex justify-end items-center px-4 py-3 border-b flex-shrink-0">
        <button class="text-lg text-gray-300 hover:text-gray-500" data-modal-close="{{ $id }}" type="button">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <div class="p-4 overflow-y-auto">
        {{ $slot }}
      </div>
    </div>
  </div>
</div>
