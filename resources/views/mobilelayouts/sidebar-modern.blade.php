<div class="h-full w-[300px] min-w-[300px]">
  <aside class="w-full bg-white flex flex-col border-r border-gray-200 h-full">

    {{-- Logo --}}
    <a href="/p/dashboard" class="px-3 py-3 flex items-center gap-0.5 hover:bg-gray-50 transition-colors">
      <div class="w-[72px] h-[72px] flex-shrink-0 flex items-center justify-center">
        <img class="w-[72px] h-[72px] object-contain" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
      </div>
      <span class="text-xl font-bold text-blue-950 tracking-tight leading-none">HealthWeb</span>
    </a>

    {{-- Navigation --}}
    @php
      $active   = 'flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium bg-blue-950 text-white';
      $inactive = 'flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-blue-950 transition-colors';
    @endphp

    <nav class="flex-1 px-3 py-4 flex flex-col gap-0.5 overflow-y-auto">
      <a href="/p/dashboard" class="{{ request()->routeIs('patient.dashboard') ? $active : $inactive }}">
        <i class="fa-solid fa-grip w-4 text-center text-[13px]"></i>
        <span>Dashboard</span>
      </a>
      <a href="/p/records" class="{{ request()->routeIs('patient.records') ? $active : $inactive }}">
        <i class="fa-solid fa-hospital-user w-4 text-center text-[13px]"></i>
        <span>Records</span>
      </a>
      <a href="/p/appointments" class="{{ request()->routeIs('patient.appointments') ? $active : $inactive }}">
        <i class="fa-solid fa-user-clock w-4 text-center text-[13px]"></i>
        <span>Appointments</span>
      </a>
      <a href="/p/messages" class="{{ request()->routeIs('patient.messages') ? $active : $inactive }}">
        <i class="fa-solid fa-paper-plane w-4 text-center text-[13px]"></i>
        <span>Messages</span>
      </a>
      <a href="/settings" class="{{ request()->routeIs('settings.*') ? $active : $inactive }}">
        <i class="fa-solid fa-gear w-4 text-center text-[13px]"></i>
        <span>Settings</span>
      </a>
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-gray-100">
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
          class="w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 transition-colors">
          <i class="fa-solid fa-power-off w-4 text-center text-[13px]"></i>
          <span>Log Out</span>
        </button>
      </form>
    </div>

  </aside>
</div>
