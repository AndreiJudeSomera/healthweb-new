<!-- Sidebar Layout with Top Header -->
<div class="h-full w-[300px] min-w[300px] whitespace-nowrap">
  <!-- Sidebar -->
  <aside class="w-full bg-[#F6F6F6] flex flex-col gap-6 border-r h-full py-6 px-2">
    <!-- Logo Section -->
    <div class="flex flex-col items-center gap-4">
      <img class="w-[200px] -my-12" src="{{ asset("assets/images/logo2.png") }}" alt="Logo">
      <span class="text-2xl font-bold text-blue-950">HealthWeb</span>
    </div>

    @php
      $active   = 'w-full py-2 px-4 flex flex-row items-center gap-3 rounded-md text-sm font-medium bg-blue-950 text-white';
      $inactive = 'w-full py-2 px-4 flex flex-row items-center gap-3 text-blue-950 rounded-md text-sm font-medium transition hover:bg-blue-500 hover:text-blue-900 hover:bg-opacity-25';

      $pages = [
          ["display" => "Dashboard",    "link" => "/p/dashboard",    "icon" => "fa-solid fa-grip",          "match" => "p/dashboard"],
          ["display" => "Records",      "link" => "/p/records",      "icon" => "fa-solid fa-hospital-user", "match" => "p/records*"],
          ["display" => "Appointments", "link" => "/p/appointments", "icon" => "fa-solid fa-user-clock",    "match" => "p/appointments*"],
          ["display" => "Messages",     "link" => "/p/messages",     "icon" => "fa-solid fa-paper-plane",   "match" => "p/messages*"],
          ["display" => "Settings",     "link" => "/settings",       "icon" => "fa-solid fa-gear",          "match" => "settings*"],
      ];
    @endphp

    <!-- Navigation -->
    <div class="flex justify-center items-center w-full">
      <nav class="flex flex-col gap-1 text-sm w-full">
        @foreach ($pages as $page)
          <a class="{{ request()->is($page['match']) ? $active : $inactive }}"
            href="{{ $page['link'] }}">
            <div class="size-[25px] flex items-center justify-center">
              <i class="{{ $page['icon'] }}"></i>
            </div>
            <span class="flex-1">{{ $page['display'] }}</span>
          </a>
        @endforeach
      </nav>
    </div>
    <!-- Logout Section -->
    <div class="w-full mt-auto">
      <form method="POST" action="{{ route("logout") }}">
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
