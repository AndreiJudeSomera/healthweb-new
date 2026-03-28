<x-guest-layout>
  <div class="flex flex-col gap-6 text-center">

    {{-- Icon --}}
    <div class="flex justify-center">
      <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">
        <i class="fa-solid fa-circle-check text-green-600 text-2xl"></i>
      </div>
    </div>

    {{-- Message --}}
    <div>
      <h2 class="text-2xl font-bold text-blue-950">Password updated</h2>
      <p class="text-sm text-gray-500 mt-2">
        Your password has been changed successfully. You can now sign in with your new password.
      </p>
    </div>

    {{-- Go to login --}}
    <a href="{{ route('login') }}"
      class="w-full py-3 bg-blue-950 hover:bg-blue-900 text-white text-sm font-semibold rounded-md transition-colors text-center">
      Go to sign in
    </a>

  </div>
</x-guest-layout>
