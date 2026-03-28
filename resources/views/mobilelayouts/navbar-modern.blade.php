<header class="w-full bg-white border-b border-gray-200 pe-6 ps-4 py-3 flex-shrink-0">
  <div class="flex justify-between items-center w-full">
    <div class="flex flex-row gap-2 items-center justify-start">
      <div class="flex items-center justify-center hover:bg-gray-100 hover:cursor-pointer rounded-md h-9 w-9 transition-colors"
        id="sidebarTrigger">
        <i class="fa-solid fa-bars text-blue-950"></i>
      </div>
      <h2 class="text-base font-semibold text-blue-950">
        @php
          $routes = [
              'patient.dashboard'    => 'Dashboard',
              'patient.onboarding.usertype' => 'Onboarding',
              'patient.onboarding.old'      => 'Onboarding',
              'patient.onboarding.new'      => 'Onboarding',
              'patient.appointments' => 'Appointments',
              'patient.records'      => 'Records',
              'patient.messages'     => 'Messages',
              'settings.setting'     => 'Settings',
          ];
          $currentRoute = Route::currentRouteName();
          echo $routes[$currentRoute] ?? 'Dashboard';
        @endphp
      </h2>
    </div>
    <div class="flex flex-row items-center gap-2">
      {{-- User Avatar --}}
      @php
        $navPatient = \App\Models\Patient::with('record')->where('user_id', auth()->id())->first();
        $navGender  = $navPatient?->record?->gender;
      @endphp
      <a href="{{ url('/settings') }}"
        class="flex items-center justify-center w-9 h-9 rounded-md hover:bg-gray-100 transition-colors">
        <x-user-avatar :role="0" :gender="$navGender" size="w-7 h-7" iconSize="text-xs" />
      </a>

      {{-- Notification Bell --}}
      <div x-data="notificationBell()" x-init="init()" class="relative">
        <button @click="toggle()"
          class="relative flex-none w-9 h-9 rounded-md text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center">
          <i class="fa-regular fa-bell text-base"></i>
          <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" x-cloak
            class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-0.5 leading-none"></span>
        </button>
        <div x-show="open" @click.outside="open = false" x-cloak
          class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
          <div class="flex justify-between items-center px-4 py-2.5 border-b border-gray-100">
            <span class="font-semibold text-sm text-blue-950">Notifications</span>
            <button @click="markAllRead()" x-show="unreadCount > 0"
              class="text-xs text-blue-600 hover:underline">Mark all read</button>
          </div>
          <ul class="max-h-72 overflow-y-auto divide-y divide-gray-100">
            <template x-if="notifications.length === 0">
              <li class="px-4 py-8 text-center text-sm text-gray-400">No notifications</li>
            </template>
            <template x-for="n in notifications" :key="n.id">
              <li @click="markRead(n)"
                class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-start gap-3"
                :class="{ 'bg-blue-50/60': !n.read }">
                <div class="flex-1 min-w-0">
                  <p class="text-xs text-gray-800 leading-snug" x-text="n.message"></p>
                  <p class="text-[10px] text-gray-400 mt-1" x-text="n.time"></p>
                </div>
                <span x-show="!n.read" class="mt-1 w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
              </li>
            </template>
          </ul>
        </div>
      </div>

      {{-- Message Bell --}}
      <div x-data="messageBell('/p/messages')" x-init="init()" class="relative">
        <button @click="toggle()"
          class="relative flex-none w-9 h-9 rounded-md text-gray-600 hover:bg-gray-100 transition-colors flex items-center justify-center">
          <i class="fa-regular fa-envelope text-base"></i>
          <span x-show="unreadCount > 0" x-text="unreadCount > 99 ? '99+' : unreadCount" x-cloak
            class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-[9px] font-bold rounded-full min-w-[16px] h-4 flex items-center justify-center px-0.5 leading-none"></span>
        </button>
        <div x-show="open" @click.outside="open = false" x-cloak
          class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
          <div class="flex justify-between items-center px-4 py-2.5 border-b border-gray-100">
            <span class="font-semibold text-sm text-blue-950">Messages</span>
            <a :href="messagesUrl" @click="open = false" class="text-xs text-blue-600 hover:underline">View all</a>
          </div>
          <ul class="max-h-72 overflow-y-auto divide-y divide-gray-100">
            <template x-if="messages.length === 0">
              <li class="px-4 py-8 text-center text-sm text-gray-400">No unread messages</li>
            </template>
            <template x-for="msg in messages" :key="msg.id">
              <li @click="markRead(msg)"
                class="px-4 py-3 hover:bg-gray-50 cursor-pointer flex items-start gap-3 bg-blue-50/60">
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
</header>
