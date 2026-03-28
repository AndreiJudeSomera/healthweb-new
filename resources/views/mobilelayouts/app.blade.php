<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>HealthWeb</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  @vite(["resources/css/app.css"])
  @vite(["resources/js/components/modals/modal.js"])

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  <link rel="icon" href="{{ asset("favicon.ico") }}" type="image/x-icon" />

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

@php $layoutStyle = request()->cookie('layout_style', 'modern'); @endphp
<body class="font-roboto antialiased h-screen overflow-hidden {{ $layoutStyle === 'legacy' ? 'bg-gray-100' : 'bg-gray-50' }}">
  <div class="h-full flex flex-row overflow-hidden">

    {{-- Backdrop (mobile overlay) --}}
    <div id="sideBarBackdrop" class="fixed inset-0 bg-black/40 z-30 hidden"></div>

    {{-- Sidebar: fixed overlay on all screen sizes --}}
    <div id="sideBar" class="fixed top-0 left-0 h-full w-[300px] z-40 -translate-x-full transition-transform duration-300 overflow-y-auto">
      @if ($layoutStyle === 'legacy')
        @include('mobilelayouts.sidebar')
      @else
        @include('mobilelayouts.sidebar-modern')
      @endif
    </div>

    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
      <div class="flex-shrink-0">
        @if ($layoutStyle === 'legacy')
          @include('mobilelayouts.navbar')
        @else
          @include('mobilelayouts.navbar-modern')
        @endif
      </div>

      <main class="p-3 sm:p-6 {{ $layoutStyle === 'legacy' ? 'bg-white' : 'bg-gray-50' }} flex-1 overflow-y-auto">
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
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
  const sidebar = document.getElementById("sideBar");
  const sideBarBackdrop = document.getElementById("sideBarBackdrop");
  let sideBarOpen = false;

  function closeSidebar() {
    sideBarOpen = false;
    sidebar.classList.add('-translate-x-full');
    sidebar.classList.remove('translate-x-0');
    sideBarBackdrop.classList.add('hidden');
  }

  document.getElementById("sidebarTrigger").addEventListener("click", () => {
    if (sideBarOpen) {
      closeSidebar();
    } else {
      sideBarOpen = true;
      sidebar.classList.remove('-translate-x-full');
      sidebar.classList.add('translate-x-0');
      sideBarBackdrop.classList.remove('hidden');
    }
  });

  sideBarBackdrop.addEventListener("click", closeSidebar);
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
  @if (session("success"))
    toastr.success("{{ session("success") }}");
  @endif

  @if (session("error"))
    toastr.error("{{ session("error") }}");
  @endif

  @if ($errors->any())
    toastr.error("{{ $errors->first() }}");
  @endif

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

</html>
