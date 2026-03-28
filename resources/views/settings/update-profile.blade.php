@extends('layouts.app')

@section('title', 'Update Profile')

@section('content')
<main class="p-6 bg-gray-50 min-h-screen">
  <div class="max-w-lg mx-auto">

    <div class="mb-5 flex items-center gap-3">
      <a href="{{ route('settings.setting') }}" class="text-gray-500 hover:text-blue-700 transition">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <h1 class="text-xl font-semibold text-[#1F2B5B]">Update Profile</h1>
    </div>

    @if ($errors->any())
      <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm space-y-1">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    {{-- Account info --}}
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Account Info</p>
    <div class="bg-white shadow-md rounded-2xl p-6 mb-6">
      <form method="POST" action="{{ route('settings.profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="space-y-5">
         <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input 
              type="text"
              name="username"
              value="{{ old('username', $user->username) }}"
              required
              pattern="[A-Za-zÑñ ]{3,30}"
              title="Only letters (A–Z, Ñ/ñ) and spaces, 3–30 characters"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
            >
            @error('username')
              <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <button type="submit"
            class="px-5 py-2 bg-[#1F2B5B] text-white text-sm font-medium rounded-lg hover:bg-[#162046] transition">
            Save Changes
          </button>
        </div>
      </form>
    </div>
    
    {{-- Personal details --}}
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Personal Details</p>
    <div class="bg-white shadow-md rounded-2xl p-6">
      <form method="POST" action="{{ route('settings.record.update') }}">
        @csrf
        @method('PATCH')

        <div class="space-y-5">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
              <input type="text" name="Fname" value="{{ old('Fname', $clinicStaff?->Fname) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
              <input type="text" name="Lname" value="{{ old('Lname', $clinicStaff?->Lname) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
            <input type="text" name="Mname" value="{{ old('Mname', $clinicStaff?->Mname) }}"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
              <input type="date" name="DateofBirth"
                value="{{ old('DateofBirth', $clinicStaff?->DateofBirth ? \Carbon\Carbon::parse($clinicStaff->DateofBirth)->format('Y-m-d') : '') }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
              <select name="Gender"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">— Select —</option>
                <option value="Male" @selected(old('Gender', $clinicStaff?->Gender) === 'Male')>Male</option>
                <option value="Female" @selected(old('Gender', $clinicStaff?->Gender) === 'Female')>Female</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
            <input type="text" name="ContactNumber" value="{{ old('ContactNumber', $clinicStaff?->ContactNumber) }}" maxlength="11" required
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <textarea name="Address" rows="2"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('Address', $clinicStaff?->Address) }}</textarea>
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <button type="submit"
            class="px-5 py-2 bg-[#1F2B5B] text-white text-sm font-medium rounded-lg hover:bg-[#162046] transition">
            Save Personal Details
          </button>
        </div>
      </form>
    </div>

  </div>
</main>
@endsection
