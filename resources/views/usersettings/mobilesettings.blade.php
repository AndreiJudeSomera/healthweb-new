@extends('mobilelayouts.app')

@section('content')
<div x-data="settingsApp()" class="h-full w-full max-w-[530px] min-w-[200px] flex-none mx-auto px-2 pb-6">

  {{-- ===== HUB SECTION ===== --}}
  <div x-show="section === 'hub'">
    <p class="text-gray-500 text-sm">Your Account</p>
    <h1 class="text-blue-950 font-bold text-3xl mb-4">Settings</h1>

    {{-- Flash messages --}}
    @if (session('status') === 'profile-updated')
      <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        Profile updated successfully.
      </div>
    @endif
    @if (session('status') === 'password-updated')
      <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
        Password changed successfully.
      </div>
    @endif

    {{-- Profile card --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm">
      <div class="flex items-center gap-4">
        <x-user-avatar :role="$user->role" :gender="$patientGender ?? null" size="w-14 h-14" iconSize="text-2xl" />
        <div class="min-w-0">
          <p class="font-semibold text-blue-950 text-base truncate">{{ $user->username }}</p>
          <p class="text-sm text-gray-500 truncate">{{ $user->email }}</p>
          <span class="inline-block mt-1 text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full font-medium">
            {{ $roleLabel }}
          </span>
        </div>
      </div>
    </div>

    {{-- Settings options --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
      <button @click="section = 'password'"
        class="w-full flex items-center gap-4 px-4 py-4 hover:bg-gray-50 transition border-b border-gray-100">
        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
          <i class="fa-solid fa-lock text-blue-800 text-sm"></i>
        </div>
        <span class="text-sm font-medium text-gray-800 flex-1 text-left">Change Password</span>
        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
      </button>

      <button @click="section = 'profile'"
        class="w-full flex items-center gap-4 px-4 py-4 hover:bg-gray-50 transition border-b border-gray-100">
        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
          <i class="fa-solid fa-user-pen text-blue-800 text-sm"></i>
        </div>
        <span class="text-sm font-medium text-gray-800 flex-1 text-left">Update Profile</span>
        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
      </button>

      <button @click="section = 'help'"
        class="w-full flex items-center gap-4 px-4 py-4 hover:bg-gray-50 transition border-b border-gray-100">
        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
          <i class="fa-solid fa-circle-question text-blue-800 text-sm"></i>
        </div>
        <span class="text-sm font-medium text-gray-800 flex-1 text-left">App Guide</span>
        <i class="fa-solid fa-chevron-right text-gray-400 text-xs"></i>
      </button>

      {{-- Appearance toggle --}}
      @php $currentStyle = request()->cookie('layout_style', 'modern'); @endphp
      <div class="flex items-center gap-4 px-4 py-4">
        <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
          <i class="fa-solid fa-display text-blue-800 text-sm"></i>
        </div>
        <span class="text-sm font-medium text-gray-800 flex-1 text-left">Appearance</span>
        <div class="flex gap-1.5">
          <form method="POST" action="{{ route('settings.layout-style') }}">
            @csrf
            <input type="hidden" name="style" value="modern">
            <button type="submit"
              class="px-3 py-1.5 text-xs rounded-md font-medium transition-colors {{ $currentStyle !== 'legacy' ? 'bg-blue-950 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
              Modern
            </button>
          </form>
          <form method="POST" action="{{ route('settings.layout-style') }}">
            @csrf
            <input type="hidden" name="style" value="legacy">
            <button type="submit"
              class="px-3 py-1.5 text-xs rounded-md font-medium transition-colors {{ $currentStyle === 'legacy' ? 'bg-blue-950 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
              Legacy
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== CHANGE PASSWORD SECTION ===== --}}
  <div x-show="section === 'password'" x-cloak>
    <div class="flex items-center gap-3 mb-5">
      <button @click="section = 'hub'" class="text-gray-500 hover:text-blue-700 transition p-1">
        <i class="fa-solid fa-arrow-left"></i>
      </button>
      <h1 class="text-blue-950 font-bold text-2xl">Change Password</h1>
    </div>

    @if ($errors->updatePassword->any())
      <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm space-y-1">
        @foreach ($errors->updatePassword->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
      <form method="POST" action="{{ route('settings.password.update') }}">
        @csrf
        @method('PUT')

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input type="password" name="current_password" required autocomplete="current-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input type="password" name="password" required autocomplete="new-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
            <input type="password" name="password_confirmation" required autocomplete="new-password"
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
        </div>

        <button type="submit"
          class="mt-5 w-full py-2.5 bg-[#1F2B5B] text-white text-sm font-medium rounded-lg hover:bg-[#162046] transition">
          Update Password
        </button>
      </form>
    </div>
  </div>

  {{-- ===== UPDATE PROFILE SECTION ===== --}}
  <div x-show="section === 'profile'" x-cloak>
    <div class="flex items-center gap-3 mb-5">
      <button @click="section = 'hub'" class="text-gray-500 hover:text-blue-700 transition p-1">
        <i class="fa-solid fa-arrow-left"></i>
      </button>
      <h1 class="text-blue-950 font-bold text-2xl">Update Profile</h1>
    </div>

    @if ($errors->any() && !$errors->updatePassword->any())
      <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm space-y-1">
        @foreach ($errors->all() as $error)
          <p>{{ $error }}</p>
        @endforeach
      </div>
    @endif

    {{-- Account info --}}
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Account Info</p>
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm mb-5">
      <form method="POST" action="{{ route('settings.profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}" required
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
        </div>

        <button type="submit"
          class="mt-5 w-full py-2.5 bg-[#1F2B5B] text-white text-sm font-medium rounded-lg hover:bg-[#162046] transition">
          Save Changes
        </button>
      </form>
    </div>

    {{-- Personal details --}}
    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Personal Details</p>
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
      <form method="POST" action="{{ route('settings.record.update') }}">
        @csrf
        @method('PATCH')

        <div class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
              <input type="text" name="first_name" value="{{ old('first_name', $patientRecord?->first_name) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
              <input type="text" name="last_name" value="{{ old('last_name', $patientRecord?->last_name) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
            <input type="text" name="middle_name" value="{{ old('middle_name', $patientRecord?->middle_name) }}"
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
              <input type="date" name="date_of_birth"
                value="{{ old('date_of_birth', $patientRecord?->date_of_birth?->format('Y-m-d')) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
              <select name="gender"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">— Select —</option>
                @php $currentGender = strtolower(old('gender', $patientRecord?->gender) ?? ''); @endphp
                <option value="male" @selected($currentGender === 'male')>Male</option>
                <option value="female" @selected($currentGender === 'female')>Female</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nationality</label>
            <input type="text" name="nationality" value="{{ old('nationality', $patientRecord?->nationality) }}"
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
            <input type="text" name="contact_number" value="{{ old('contact_number', $patientRecord?->contact_number) }}"
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
            <textarea name="address" rows="2"
              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('address', $patientRecord?->address) }}</textarea>
          </div>
        </div>

        <button type="submit"
          class="mt-5 w-full py-2.5 bg-[#1F2B5B] text-white text-sm font-medium rounded-lg hover:bg-[#162046] transition">
          Save Personal Details
        </button>
      </form>
    </div>
  </div>

  {{-- ===== HELP SECTION ===== --}}
  <div x-show="section === 'help'" x-cloak>
    <div class="flex items-center gap-3 mb-5">
      <button @click="section = 'hub'" class="text-gray-500 hover:text-blue-700 transition p-1">
        <i class="fa-solid fa-arrow-left"></i>
      </button>
      <h1 class="text-blue-950 font-bold text-2xl">Help & Support</h1>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm space-y-6">

      <p class="text-sm text-gray-500">
        HealthWeb is your secure online patient portal. Here's a guide to everything you can do.
      </p>

      <div>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-grip text-blue-800 text-xs"></i>
          </div>
          <h2 class="text-sm font-semibold text-[#1F2B5B]">Dashboard</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside ml-2">
          <li>See a welcome overview of your patient portal.</li>
          <li>View the weekly <strong>Clinic Hours</strong> schedule to know when the clinic is open.</li>
        </ul>
      </div>

      <div>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-hospital-user text-blue-800 text-xs"></i>
          </div>
          <h2 class="text-sm font-semibold text-[#1F2B5B]">Records</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside ml-2">
          <li>View your personal medical records and health history.</li>
          <li>Download prescriptions, medical certificates, and referral documents.</li>
        </ul>
      </div>

      <div>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-user-clock text-blue-800 text-xs"></i>
          </div>
          <h2 class="text-sm font-semibold text-[#1F2B5B]">Appointments</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside ml-2">
          <li>Book a new appointment by selecting a date and available time slot.</li>
          <li>View upcoming and past appointments and their status.</li>
        </ul>
      </div>

      <div>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-paper-plane text-blue-800 text-xs"></i>
          </div>
          <h2 class="text-sm font-semibold text-[#1F2B5B]">Messages</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside ml-2">
          <li>Send and receive messages with your doctor or the clinic staff.</li>
          <li>Start a new conversation or continue an existing one.</li>
        </ul>
      </div>

      <div>
        <div class="flex items-center gap-3 mb-2">
          <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-gear text-blue-800 text-xs"></i>
          </div>
          <h2 class="text-sm font-semibold text-[#1F2B5B]">Settings</h2>
        </div>
        <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside ml-2">
          <li><strong>Change Password</strong> — Update your account password securely.</li>
          <li><strong>Update Profile</strong> — Change your username and email address.</li>
        </ul>
      </div>

      <p class="text-xs text-gray-400 border-t pt-4">
        For assistance, message the clinic through the Messages section.
      </p>
    </div>
  </div>

</div>

<script>
  function settingsApp() {
    return {
      section: '{{ $errors->updatePassword->any() ? "password" : ($errors->any() ? "profile" : "hub") }}',
    };
  }
</script>
@endsection
