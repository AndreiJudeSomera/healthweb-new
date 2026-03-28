@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<main class="p-6 bg-gray-50 min-h-screen">
  <div class="max-w-lg mx-auto">

    <div class="mb-5 flex items-center gap-3">
      <a href="{{ route('settings.setting') }}" class="text-gray-500 hover:text-blue-700 transition">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <h1 class="text-xl font-semibold text-[#1F2B5B]">Change Password</h1>
    </div>

    @if ($errors->updatePassword->any())
      <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm space-y-1">
        @foreach ($errors->updatePassword->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <div class="bg-white shadow-md rounded-2xl p-6">
      <form method="POST" action="{{ route('settings.password.update') }}">
        @csrf
        @method('PUT')

        <div class="space-y-5">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input type="password" name="current_password" required autocomplete="current-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input type="password" name="password" required autocomplete="new-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <button type="submit"
            class="px-5 py-2 bg-[#1F2B5B] text-white text-sm font-medium rounded-lg hover:bg-[#162046] transition">
            Update Password
          </button>
        </div>
      </form>
    </div>

  </div>
</main>
@endsection
