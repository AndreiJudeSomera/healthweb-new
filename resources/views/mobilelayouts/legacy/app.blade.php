<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config("app.name", "Laravel") }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

  <!-- Page-specific styles -->
  @stack("styles")

  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>

  <!-- Scripts -->
  @vite(["resources/css/app.css", "resources/js/app.js"])
</head>

<body class="font-sans antialiased">
  <div class="flex h-screen">

    <!-- Sidebar -->
    @include("mobilelayouts.navigation")

    <!-- Overlay (for mobile) -->
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden md:hidden z-40" id="overlay"></div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">

      <!-- Header -->
      <header
        class="fixed top-0 left-0 md:left-64 w-full md:w-[calc(100%-16rem)] bg-white shadow z-30 flex justify-between items-center px-4 py-3">

        <!-- Left: Title + Hamburger -->
        <div class="flex items-center space-x-3">
          <!-- Hamburger button (mobile only) -->
          <button class="md:hidden p-2 rounded-md" id="menuBtn">
            <svg class="w-6 h-6 text-[#1f2b5b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <h2 class="text-xl font-semibold text-blue-900">
            <span class="text-2xl">Dashboard</span>
          </h2>
        </div>

        <!-- Right: Icons -->
        <div class="flex items-center space-x-3">
          <button
            class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-200">
            <!-- Notification Icon -->
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
              </path>
            </svg>
          </button>
          <button
            class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-gray-700 hover:bg-gray-200">
            <!-- Message Icon -->
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path
                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
              </path>
            </svg>
          </button>
        </div>
      </header>

      <!-- Main Section -->
      <main class="pt-20 px-6 bg-white flex-1 overflow-y-auto">
        @yield("content")
      </main>
    </div>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.getElementById('menuBtn');
    const overlay = document.getElementById('overlay');

    if (menuBtn && sidebar && overlay) {
      menuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
      });

      overlay.addEventListener('click', () => {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
      });
    }
  </script>

  <!-- Page-specific scripts -->
  @stack("scripts")

  <!-- Alpine.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>
