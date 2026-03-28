<!-- Sidebar Layout with Top Header -->
<div class="h-full w-[300px] min-w[300px] whitespace-nowrap">
  <!-- Sidebar -->
  <aside class="w-full bg-[#F6F6F6] flex flex-col gap-6 border-r h-full py-6 px-2">
    <!-- Logo Section -->
    <div class="flex flex-col items-center gap-4">
      
      <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
      <span class="text-2xl font-bold text-blue-950 ">HealthWeb</span>
    </div>

    @php
      $active   = 'w-full py-2 px-4 flex flex-row items-center gap-3 rounded-md text-sm font-medium bg-blue-950 text-white';
      $inactive = 'w-full py-2 px-4 flex flex-row items-center gap-3 text-blue-950 rounded-md text-sm font-medium transition hover:bg-blue-500 hover:text-blue-900 hover:bg-opacity-25';
    @endphp

    <!-- Navigation -->
    <div class="flex justify-center items-center w-full mt-10">
      <nav class="flex flex-col gap-1 text-sm w-[190px] mx-auto ">
        {{-- Dashboard --}}
        <a class="{{ request()->routeIs('*dashboard*') ? $active : $inactive }}"
          href="{{ route('dashboard') }}">
          <div class="size-[25px] flex items-center justify-center">
            <i class="fa-solid fa-grip"></i>
          </div>
          <span class="flex-1">Dashboard</span>
        </a>
        {{-- Patients --}}
        <a class="{{ request()->routeIs('patients.*') ? $active : $inactive }}"
          href="{{ route('patients.index') }}">
          <div class="size-[25px] flex items-center justify-center">
            <i class="fa-solid fa-hospital-user"></i>
          </div>
          <span>Patients</span>
        </a>
        {{-- Appointments --}}
        <a class="{{ request()->routeIs('appointments.*') ? $active : $inactive }}"
          href="{{ route('appointments.index') }}">
          <div class="size-[25px] flex items-center justify-center">
            <i class="fa-solid fa-user-clock"></i>
          </div>
          <span class="flex-1">Appointments</span>
        </a>
        {{-- Messages --}}
        <a class="{{ request()->routeIs('messages.*') ? $active : $inactive }}"
          href="{{ route('messages.index') }}">
          <div class="size-[25px] flex items-center justify-center">
            <i class="fa-solid fa-paper-plane"></i>
          </div>
          <span class="flex-1">Messages</span>
        </a>
      
        {{-- Reports --}}
        <a class="{{ request()->routeIs('reports.*') ? $active : $inactive }}"
          href="{{ route('reports.reports') }}">
          <div class="size-[25px] flex items-center justify-center">
            <i class="fa-solid fa-chart-simple"></i>
          </div>
          <span class="flex-1">Data Visualization</span>
        </a>
        
        {{-- Accounts --}}
        <a class="{{ request()->routeIs('accounts.*') ? $active : $inactive }}"
          href="{{ route('accounts.index') }}">
          <div class="size-[25px] flex items-center justify-center">
            <i class="fa-solid fa-users"></i>
          </div>
          <span class="flex-1">Accounts</span>
        </a>
          @if (auth()->user()->role !== 1)
        <a class="{{ request()->routeIs('audit.*') ? $active : $inactive }}"
          href="{{ route('audit.index') }}">
          <div class="size-[25px] flex items-center justify-center">
            <i class="fa-solid fa-scroll"></i>
          </div>
          <span class="flex-1">Audit Logs</span>
        </a>
        @endif
        {{-- Settings --}}
        <a class="{{ request()->routeIs('settings.*') ? $active : $inactive }}"
          href="{{ url('/settings') }}">
          <div class="size-[25px] flex items-center justify-center">
            <i class="fa-solid fa-gear"></i>
          </div>
          <span class="flex-1">Settings</span>
        </a>
      </nav>
    </div>

    
    <!-- Logout Section -->
    <div class="flex justify-center items-center w-full mt-auto ">
      <form method="POST" action="{{ route('logout') }}" class="w-[190px]">
        @csrf
        <button class="w-full" type="submit" style="text-align: left;">
          <div
            class="flex flex-row items-center gap-3 py-2 px-4 text-red-700 rounded-md hover:bg-red-100 font-medium hover:font-semibold transition">
            <div class="size-[25px] flex items-center justify-center">
              <i class="fa-solid fa-power-off"></i>
            </div>
            <span class="text-sm">Log Out</span>
          </div>
        </button>
      </form>
    </div>
  </aside>
</div>

