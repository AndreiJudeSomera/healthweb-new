<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>HealthWeb</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <script>
window.authUserId = {{ auth()->id() ?? 'null' }};
</script>

@vite(['resources/js/app.js'])
  <!-- Vite CSS -->
  @vite(["resources/css/app.css"])

  <!-- Imports ni Garic -->
  <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
  {{-- @vite(["resources/js/app.js"]) --}}
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="icon" href="{{ asset("favicon.ico") }}" type="image/x-icon" />

  <!-- jQuery (only one version) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  

  <!-- Page-specific styles -->
  @stack("styles")
  <style>
    [x-cloak] {
      display: none !important;
    }
    #toast-container > .toast {
      opacity: 1 !important;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
    }
    #toast-container > .toast-success { background-color: #166534; }
    #toast-container > .toast-error   { background-color: #991b1b; }
    #toast-container > .toast-warning { background-color: #92400e; }
    #toast-container > .toast-info    { background-color: #1e3a5f; }
  </style>
</head>

<body class="font-roboto antialiased bg-gray-100 h-screen overflow-hidden">
  <div class="h-full flex flex-row overflow-hidden">
    <div class="w-[300px] shrink-0 overflow-hidden overflow-y-auto transition-[width] duration-300" id="sideBar">
      @if(request()->cookie('layout_style') === 'legacy')
        @include("layouts.sidebar-legacy")
      @else
        @include("layouts.sidebar")
      @endif
    </div>

    <div class="flex-1 flex flex-col overflow-hidden">
      <div class="flex-shrink-0">
        @if(request()->cookie('layout_style') === 'legacy')
          @include("layouts.navbar-legacy")
        @else
          @include("layouts.navbar")
        @endif
      </div>

      <main class="p-6 bg-white flex-1 overflow-y-auto">
        @yield("content")
      </main>
    </div>
  </div>
</body>

@stack("scripts")
<script>
  function messageBell(messagesUrl) {
    return {
      open: false,
      unreadCount: 0,
      messages: [],
      messagesUrl,

      async init() {
        await this.fetchMessages();
        setInterval(() => this.fetchMessages(), 30000);
      },

      async fetchMessages() {
        try {
          const res = await fetch('/messages/unread', { headers: { Accept: 'application/json' } });
          if (!res.ok) return;
          const data = await res.json();
          this.unreadCount = data.unread_count;
          this.messages = data.messages;
        } catch {}
      },

      toggle() { this.open = !this.open; },

      async markRead(msg) {
        if (msg.id) {
          await fetch(`/messages/${msg.id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          });
          this.unreadCount = Math.max(0, this.unreadCount - 1);
          this.messages = this.messages.filter(m => m.id !== msg.id);
        }
        this.open = false;
        window.location.href = this.messagesUrl + (msg.conversation_id ? '?conversation_id=' + msg.conversation_id : '');
      },
    };
  }

  function notificationBell() {
    return {
      open: false,
      unreadCount: 0,
      notifications: [],

      async init() {
        await this.fetchNotifications();
        setInterval(() => this.fetchNotifications(), 30000);
      },

      async fetchNotifications() {
        try {
          const res = await fetch('/notifications', { headers: { Accept: 'application/json' } });
          if (!res.ok) return;
          const data = await res.json();
          this.unreadCount = data.unread_count;
          this.notifications = data.notifications;
        } catch {}
      },

      toggle() { this.open = !this.open; },

      async markRead(notification) {
        if (!notification.read) {
          await fetch(`/notifications/${notification.id}/read`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
          });
          notification.read = true;
          this.unreadCount = Math.max(0, this.unreadCount - 1);
        }
        this.open = false;
        if (notification.link) window.location.href = notification.link;
      },

      async markAllRead() {
        await fetch('/notifications/read-all', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        });
        this.notifications.forEach(n => n.read = true);
        this.unreadCount = 0;
      },
    };
  }
</script>

<script>
  let sideBarOpen = true;
  const sidebar = document.getElementById("sideBar");
  const sidebarTrigger = document.getElementById("sidebarTrigger");
  if (sidebarTrigger) {
  sidebarTrigger.addEventListener("click", (e) => {
    if (sideBarOpen) {
      sideBarOpen = false;
      sidebar.classList.add('w-0');
      sidebar.classList.remove('w-[300px]');
    } else {
      sideBarOpen = true;
      sidebar.classList.remove('w-0');
      sidebar.classList.add('w-[300px]');
    }
  })};
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
  toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: "toast-bottom-right",
    preventDuplicates: true,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut",
  };
</script>
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>


</html>
