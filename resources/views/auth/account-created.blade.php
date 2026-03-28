<x-guest-layout>
  <div class="flex flex-col gap-6 text-center">

    {{-- Success Icon --}}
    <div class="mx-auto w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center">
      <i class="fa-solid fa-check text-emerald-600 text-xl"></i>
    </div>

    {{-- Header --}}
    <div>
      <h2 class="text-2xl font-bold text-blue-950">Account Created Successfully</h2>
      <p class="text-sm text-gray-500 mt-2">
        Your account has been created.
        Please sign in to continue.
      </p>
    </div>

    {{-- Login Button --}}
    <div>
      <a href="{{ route('login') }}"
         class="inline-block w-full py-3 bg-blue-950 hover:bg-blue-900 text-white text-sm font-semibold rounded-md transition-colors">
        Back to Sign In
      </a>
    </div>

  </div>
</x-guest-layout>