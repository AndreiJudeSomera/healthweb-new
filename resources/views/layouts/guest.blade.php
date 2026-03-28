<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config("app.name", "HealthWeb") }}</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  @vite(["resources/css/app.css", "resources/js/app.js"])
</head>

<body class="antialiased bg-white">

  <div class="min-h-screen flex">

    {{-- Left branding panel (desktop only) --}}
    <div
      class="hidden lg:flex lg:w-[420px] mt-[-38px] xl:w-[460px] flex-shrink-0 relative flex-col justify-center px-12 pb-16 text-white overflow-hidden"
      style="background-image: url('{{ asset("assets/images/bg.avif") }}'); background-size: cover; background-position: center;">
      {{-- Navy overlay --}}
      <div class="absolute inset-0 bg-blue-950/80"></div>
      {{-- Content sits above overlay --}}
      <div class="relative z-10 flex flex-col gap-6">
        <div class="w-full flex items-center justify-center">
          <a href="{{ route('login') }}">
            <img class="w-[-300px] mb-[50]" src="{{ asset("assets/images/logo2.png") }}" alt="HealthWeb Logo">
          </a>
        </div>
        <div class="flex flex-col gap-2">
          <h1 class="text-3xl font-bold tracking-tight">HealthWeb</h1>
          <p class="text-blue-100 text-sm leading-relaxed">
            Your complete clinic management system for appointments, records, and more.
          </p>
        </div>
        <div class="flex flex-col gap-3 w-full">
          <div class="flex items-center gap-3 text-blue-100 text-sm">
            <div class="w-8 h-8 rounded-full bg-blue-900 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-calendar-check text-xs"></i>
            </div>
            <span>Appointment scheduling</span>
          </div>
          <div class="flex items-center gap-3 text-blue-100 text-sm">
            <div class="w-8 h-8 rounded-full bg-blue-900 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-file-medical text-xs"></i>
            </div>
            <span>Medical records and documents</span>
          </div>
          <div class="flex items-center gap-3 text-blue-100 text-sm">
            <div class="w-8 h-8 rounded-full bg-blue-900 flex items-center justify-center flex-shrink-0">
              <i class="fa-solid fa-shield-halved text-xs"></i>
            </div>
            <span>Secure and private health data</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Right form panel --}}
    <div class="flex-1 flex flex-col items-center justify-center px-6 py-12">

      {{-- Mobile logo --}}
      <div class="lg:hidden mb-8 text-center">
        <img class="w-[152px] mb-[-32px] mx-auto" src="{{ asset("assets/images/logo2.png") }}" alt="HealthWeb Logo">
        <p class="font-bold text-blue-950 text-xl mt-2">HealthWeb</p>
      </div>

      <div class="w-full max-w-md">
        {{ $slot }}
      </div>

    </div>
  </div>

</body>

</html>
