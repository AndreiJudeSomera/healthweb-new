@extends('layouts.app')

@section('title', 'Settings')

@section('content')

  @if (session('status') === 'profile-updated')
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">
      Profile updated successfully.
    </div>
  @endif
  @if (session('status') === 'password-updated')
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">
      Password changed successfully.
    </div>
  @endif

  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Profile Card --}}
    <div class="bg-white border border-gray-200 rounded-md p-6">
      <h2 class="text-xl font-semibold text-[#1F2B5B] mb-6 text-center">Profile</h2>

      <div class="flex justify-center mb-6">
        <x-user-avatar :role="$user->role" :gender="$patientGender ?? null" size="w-24 h-24" iconSize="text-4xl" />
      </div>

      <div class="text-[15px] text-gray-700 space-y-4">
        <div class="grid grid-cols-3">
          <span class="font-medium text-left">Username</span>
          <span class="col-span-2 text-left text-gray-600">: {{ $user->username }}</span>
        </div>
        <div class="grid grid-cols-3">
          <span class="font-medium text-left">Email</span>
          <span class="col-span-2 text-left text-gray-600 truncate">: {{ $user->email }}</span>
        </div>
        <div class="grid grid-cols-3">
          <span class="font-medium text-left">Role</span>
          <span class="col-span-2 text-left text-gray-600">: {{ $roleLabel }}</span>
        </div>
        <div class="grid grid-cols-3">
          <span class="font-medium text-left">Contact #</span>
          <span class="col-span-2 text-left text-gray-600">: {{ $contactNumber ?? '—' }}</span>
        </div>
      </div>
    </div>

    {{-- Settings Card --}}
    <div class="bg-white border border-gray-200 rounded-md p-6">
      <h2 class="text-xl font-semibold text-[#1F2B5B] mb-6 text-center">Settings</h2>

      <div class="grid grid-cols-1 gap-4">
        <a href="{{ route('settings.password.form') }}"
          class="flex items-center gap-4 px-4 py-3 bg-gray-50 rounded-md hover:bg-gray-100 transition">
          <i class="fa-solid fa-lock text-[#1F2B5B] text-xl w-7 text-center"></i>
          <span class="text-sm font-medium text-gray-700 tracking-wide">Change Password</span>
          <i class="fa-solid fa-chevron-right ml-auto text-gray-400 text-xs"></i>
        </a>

        <a href="{{ route('settings.profile.form') }}"
          class="flex items-center gap-4 px-4 py-3 bg-gray-50 rounded-md hover:bg-gray-100 transition">
          <i class="fa-solid fa-user-pen text-[#1F2B5B] text-xl w-7 text-center"></i>
          <span class="text-sm font-medium text-gray-700 tracking-wide">Update Profile</span>
          <i class="fa-solid fa-chevron-right ml-auto text-gray-400 text-xs"></i>
        </a>

        <a href="{{ route('settings.help') }}"
          class="flex items-center gap-4 px-4 py-3 bg-gray-50 rounded-md hover:bg-gray-100 transition">
          <i class="fa-solid fa-circle-question text-[#1F2B5B] text-xl w-7 text-center"></i>
          <span class="text-sm font-medium text-gray-700 tracking-wide">App Guide</span>
          <i class="fa-solid fa-chevron-right ml-auto text-gray-400 text-xs"></i>
        </a>
      </div>
    </div>

    {{-- Appearance Card --}}
    <div class="bg-white border border-gray-200 rounded-md p-6 md:col-span-2">
      <h2 class="text-xl font-semibold text-[#1F2B5B] mb-6 text-center">Appearance</h2>
      <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-md">
        <div class="flex items-center gap-4">
          <i class="fa-solid fa-display text-[#1F2B5B] text-xl w-7 text-center"></i>
          <div>
            <p class="text-sm font-medium text-gray-700">Navigation Style</p>
            <p class="text-xs text-gray-400 mt-0.5">Switch between the modern and legacy sidebar &amp; navbar</p>
          </div>
        </div>
        @php $currentStyle = request()->cookie('layout_style', 'modern'); @endphp
        <div class="flex gap-2">
          <form method="POST" action="{{ route('settings.layout-style') }}">
            @csrf
            <input type="hidden" name="style" value="modern">
            <button type="submit"
              class="px-4 py-2 text-sm rounded-md font-medium transition-colors {{ $currentStyle !== 'legacy' ? 'bg-blue-950 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
              Modern
            </button>
          </form>
          <form method="POST" action="{{ route('settings.layout-style') }}">
            @csrf
            <input type="hidden" name="style" value="legacy">
            <button type="submit"
              class="px-4 py-2 text-sm rounded-md font-medium transition-colors {{ $currentStyle === 'legacy' ? 'bg-blue-950 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
              Legacy
            </button>
          </form>
        </div>
      </div>
    </div>

  </div>

@endsection
