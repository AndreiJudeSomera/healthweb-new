<header class="w-full bg-[#f6f6f6] border-b-2 pe-6 ps-4 py-4 flex-shrink-0">
  <div class="flex justify-between items-center w-full ">
    <div class="flex flex-row gap-2 items-center justify-start">
      <div class="flex items-center justify-center hover:bg-blue-100 hover:cursor-pointer rounded-md h-9 w-9"
        id="sidebarTrigger">
        <i class="fa-solid fa-bars flex text-blue-950"></i>
      </div>
      <h2 class="text-xl font-bold text-blue-950">
        @php
          $routes = [
              "dashboard" => ["display" => "Dashboard", "link" => "", "icon" => ""],
              "patients.index" => ["display" => "Patients", "link" => "", "icon" => ""],
              "patients.show" => ["display" => "Patients", "link" => "/patients", "icon" => ""],
              "patients.documentShow" => ["display" => "Patient Document", "link" => "/patients", "icon" => ""],
              "appointments.index" => ["display" => "Appointments", "link" => "", "icon" => ""],
              "appointments.queue" => ["display" => "Appointments", "link" => "", "icon" => ""],
              "reports.reports" => ["display" => "Data Visualization", "link" => "", "icon" => ""],
              "accounts.index" => ["display" => "Accounts", "link" => "", "icon" => ""],
              "settings.setting" => ["display" => "Settings", "link" => "", "icon" => ""],
              "messages.index" => ["display" => "Messages", "link" => "", "icon" => ""],
              "messages.show" => ["display" => "Messages", "link" => "/messages", "icon" => ""],
              "message.legacy" => ["display" => "Messages", "link" => "/messages", "icon" => ""],
          ];
          $currentRoute = Route::currentRouteName();
          $route = $routes[$currentRoute] ?? $routes["dashboard"];
        @endphp
        <a href="{{ $route["link"] }}">{{ $route["display"] }}</a>
      </h2>
    </div>
    <div class="flex flex-row gap-2 md:gap-10 items-center">
      @php
        $currentUser = \App\Models\User::where("id", auth()->id())->firstOrFail();
      @endphp
      <a href="{{ route('settings.setting') }}" class="flex flex-row items-center gap-2 hover:opacity-75 transition-opacity">
        <x-user-avatar :role="$currentUser->role" size="w-7 h-7 md:w-9 md:h-9" iconSize="text-xs md:text-sm" />
        <div class="flex-col gap-0 hidden md:flex">
          @php
            function formatRoleLegacy(int $role) {
                return match($role) { 0 => "Patient", 1 => "Secretary", 2 => "Doctor", default => "Undefined" };
            }
          @endphp
          <span class="text-sm font-semibold">{{ $currentUser->username }}</span>
          <span class="text-xs text-gray-500">{{ formatRoleLegacy($currentUser->role) }}</span>
        </div>
      </a>
      <div class="flex flex-row items-center gap-2">
        {{-- Notification Bell --}}
        <div class="relative" x-data="notificationBell()" x-init="init()">
          <button
            class="relative w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-200 transition-colors"
            @click="toggle()">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-0.5 leading-none"
              x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" x-cloak></span>
          </button>
          <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
            x-show="open" @click.outside="open = false" x-cloak>
            <div class="flex justify-between items-center px-4 py-2.5 border-b border-gray-100">
              <span class="font-semibold text-sm text-blue-950">Notifications</span>
              <button class="text-xs text-blue-600 hover:underline" @click="markAllRead()" x-show="unreadCount > 0">Mark all read</button>
            </div>
            <ul class="max-h-72 overflow-y-auto divide-y divide-gray-100">
              <template x-if="notifications.length === 0">
                <li class="px-4 py-8 text-center text-sm text-gray-400">No notifications</li>
              </template>
              <template x-for="n in notifications" :key="n.id">
                <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-start gap-3" @click="markRead(n)" :class="{ 'bg-blue-50/60': !n.read }">
                  <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-800 leading-snug" x-text="n.message"></p>
                    <p class="text-[10px] text-gray-400 mt-1" x-text="n.time"></p>
                  </div>
                  <span class="mt-1 w-2 h-2 rounded-full bg-blue-500 flex-shrink-0" x-show="!n.read"></span>
                </li>
              </template>
            </ul>
          </div>
        </div>
        {{-- Message Bell --}}
        <div class="relative" x-data="messageBell('/messages')" x-init="init()">
          <button
            class="relative w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-200 transition-colors"
            @click="toggle()">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-0.5 leading-none"
              x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" x-cloak></span>
          </button>
          <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
            x-show="open" @click.outside="open = false" x-cloak>
            <div class="flex justify-between items-center px-4 py-2.5 border-b border-gray-100">
              <span class="font-semibold text-sm text-blue-950">Messages</span>
              <a class="text-xs text-blue-600 hover:underline" :href="messagesUrl" @click="open = false">View all</a>
            </div>
            <ul class="max-h-72 overflow-y-auto divide-y divide-gray-100">
              <template x-if="messages.length === 0">
                <li class="px-4 py-8 text-center text-sm text-gray-400">No unread messages</li>
              </template>
              <template x-for="msg in messages" :key="msg.id">
                <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-start gap-3 bg-blue-50/60" @click="markRead(msg)">
                  <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-blue-950" x-text="msg.sender"></p>
                    <p class="text-xs text-gray-600 leading-snug truncate" x-text="msg.preview"></p>
                    <p class="text-[10px] text-gray-400 mt-1" x-text="msg.time"></p>
                  </div>
                  <span class="mt-1 w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                </li>
              </template>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>
