<div class="h-full w-[300px] min-w-[300px]">
  <aside class="w-full bg-white flex flex-col border-r border-gray-200 h-full">

    {{-- Logo --}}
    <a class="px-3 py-3 flex items-center gap-0.5 hover:bg-gray-50 transition-colors" href="{{ route("dashboard") }}">
      <div class="w-[72px] h-[72px] flex-shrink-0 flex items-center justify-center">
        <img class="w-[40px] h-[40px] object-contain" src="{{ asset("assets/images/logo2.png") }}" alt="Logo">
      </div>
      <span class="text-xl font-bold text-blue-950 tracking-tight leading-none ms-[-10px]">HealthWeb</span>
    </a>

    {{-- Navigation --}}
    @php
      $active = "flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium bg-blue-950 text-white";
      $inactive =
          "flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-blue-950 transition-colors";
    @endphp

    <nav class="flex-1 px-3 py-4 flex flex-col gap-0.5 overflow-y-auto ">

      <a class="{{ request()->routeIs("*dashboard*") ? $active : $inactive }}" href="{{ route("dashboard") }}">
        <i class="fa-solid fa-grip w-4 text-center text-[13px]"></i>
        <span>Dashboard</span>
      </a>

      <a class="{{ request()->routeIs("patients.*") ? $active : $inactive }}" href="{{ route("patients.index") }}">
        <i class="fa-solid fa-hospital-user w-4 text-center text-[13px]"></i>
        <span>Patients</span>
      </a>

      <a class="{{ request()->routeIs("appointments.*") ? $active : $inactive }}"
        href="{{ route("appointments.index") }}">
        <i class="fa-solid fa-user-clock w-4 text-center text-[13px]"></i>
        <span>Appointments</span>
      </a>

      <a class="{{ request()->routeIs("messages.*") ? $active : $inactive }}" href="{{ route("messages.index") }}">
        <i class="fa-solid fa-paper-plane w-4 text-center text-[13px]"></i>
        <span>Messages</span>
      </a>

    
        <a class="{{ request()->routeIs("reports.*") ? $active : $inactive }}" href="{{ route("reports.reports") }}">
          <i class="fa-solid fa-chart-simple w-4 text-center text-[13px]"></i>
          <span>Data Visualization</span>
        </a>

        <a class="{{ request()->routeIs("accounts.*") ? $active : $inactive }}" href="{{ route("accounts.index") }}">
          <i class="fa-solid fa-users w-4 text-center text-[13px]"></i>
          <span>Accounts</span>
        </a>
  @if (auth()->user()->role !== 1)
        <a class="{{ request()->routeIs("audit.*") ? $active : $inactive }}" href="{{ route("audit.index") }}">
          <i class="fa-solid fa-scroll w-4 text-center text-[13px]"></i>
          <span>Audit Logs</span>
        </a>
      @endif

      <a class="{{ request()->routeIs("settings.*") ? $active : $inactive }}" href="{{ url("/settings") }}">
        <i class="fa-solid fa-gear w-4 text-center text-[13px]"></i>
        <span>Settings</span>
      </a>

    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-gray-100">
      <form method="POST" action="{{ route("logout") }}">
        @csrf
        <button
          class="w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-red-600 hover:bg-red-50 transition-colors"
          type="submit">
          <i class="fa-solid fa-power-off w-4 text-center text-[13px]"></i>
          <span>Log Out</span>
        </button>
      </form>
    </div>

  </aside>
</div>
