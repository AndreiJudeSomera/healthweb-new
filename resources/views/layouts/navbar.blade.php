<header class="w-full bg-white border-b border-gray-200 pe-6 ps-4 py-3 flex-shrink-0">
  <div class="flex justify-between items-center w-full ">
    <div class="flex flex-row gap-2 items-center justify-start">
      <div class="flex items-center justify-center hover:bg-gray-100 hover:cursor-pointer rounded-lg h-9 w-9 transition-colors"
        id="sidebarTrigger">
        <i class="fa-solid fa-bars text-blue-950"></i>
      </div>
      <h2 class="text-base font-semibold text-blue-950">
        @php
          $routes = [
              "dashboard" => [
                  "display" => "Dashboard",
                  "link" => "",
                  "icon" => "",
              ],
              "patients.index" => [
                  "display" => "Patients",
                  "link" => "",
                  "icon" => "",
              ],
              "patients.show" => [
                  "display" => "Patients",
                  "link" => "/patients",
                  "icon" => "",
              ],
              "patients.documentShow" => [
                  "display" => "Patient Document",
                  "link" => "/patients",
                  "icon" => "",
              ],
              "appointments.index" => [
                  "display" => "Appointments",
                  "link" => "",
                  "icon" => "",
              ],
              "appointments.queue" => [
                  "display" => "Appointments",
                  "link" => "",
                  "icon" => "",
              ],
              "reports.reports" => [
                  "display" => "Reports",
                  "link" => "",
                  "icon" => "",
              ],
              "accounts.index" => [
                  "display" => "Accounts",
                  "link" => "",
                  "icon" => "",
              ],
              "settings.setting" => [
                  "display" => "Settings",
                  "link" => "",
                  "icon" => "",
              ],
              "messages.index" => [
                  "display" => "Messages",
                  "link" => "",
                  "icon" => "",
              ],
              "messages.show" => [
                  "display" => "Messages",
                  "link" => "/messages",
                  "icon" => "",
              ],
              "message.legacy" => [
                  "display" => "Messages",
                  "link" => "/messages",
                  "icon" => "",
              ],
              "audit.index" => [
                  "display" => "Audit Logs",
                  "link" => "",
                  "icon" => "",
              ],
          ];

          $currentRoute = Route::currentRouteName();
          $route = $routes[$currentRoute] ?? $routes["dashboard"];
        @endphp
        <a href="{{ $route["link"] }}">{{ $route["display"] }}</a>
      </h2>
    </div>
    <div class="flex flex-row gap-4 md:gap-6 items-center">
      @php
        $currentUser = \App\Models\User::where("id", auth()->id())->firstOrFail();
      @endphp
      <a href="{{ route('settings.setting') }}" class="flex flex-row items-center gap-2 hover:opacity-75 transition-opacity">
        <x-user-avatar :role="$currentUser->role" size="w-7 h-7 md:w-9 md:h-9" iconSize="text-xs md:text-sm" />
        <div class="flex-col gap-0 hidden md:flex">
          @php

            function formatRole(int $role)
            {
                switch ($role) {
                    case 0:
                        return "Patient";
                    case 1:
                        return "Secretary";
                    case 2:
                        return "Doctor";
                    default:
                        return "Undefined";
                }
            }
          @endphp
          <span class="text-sm font-semibold">
            {{ $currentUser->username }}
          </span>
          <span class="text-xs text-gray-500">{{ formatRole($currentUser->role) }}</span>
        </div>
      </a>
      <div class="flex flex-row items-center gap-2">
        {{-- Notification Bell --}}
        <div class="relative" x-data="notificationBell()" x-init="init()">
          <button
            class="relative w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center text-blue-950 hover:bg-gray-200 transition-colors"
            @click="toggle()">
            <i class="fa-regular fa-bell text-base"></i>
            <span
              class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-0.5 leading-none"
              x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" x-cloak></span>
          </button>

          {{-- Dropdown --}}
          <div class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
            x-show="open" @click.outside="open = false" x-cloak>
            <div class="flex justify-between items-center px-4 py-2.5 border-b border-gray-100">
              <span class="font-semibold text-sm text-blue-950">Notifications</span>
              <button class="text-xs text-blue-600 hover:underline" @click="markAllRead()" x-show="unreadCount > 0">Mark
                all read</button>
            </div>
            <ul class="max-h-72 overflow-y-auto divide-y divide-gray-100">
              <template x-if="notifications.length === 0">
                <li class="px-4 py-8 text-center text-sm text-gray-400">No notifications</li>
              </template>
              <template x-for="n in notifications" :key="n.id">
                <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-start gap-3" @click="markRead(n)"
                  :class="{ 'bg-blue-50/60': !n.read }">
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
            class="relative w-9 h-9 bg-gray-100 rounded-lg flex items-center justify-center text-blue-950 hover:bg-gray-200 transition-colors"
            @click="toggle()">
            <i class="fa-regular fa-envelope text-base"></i>
            <span
              class="absolute -top-1 -right-1 bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-0.5 leading-none"
              x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" x-cloak></span>
          </button>

          {{-- Dropdown --}}
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
                <li class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-start gap-3 bg-blue-50/60"
                  @click="markRead(msg)">
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
