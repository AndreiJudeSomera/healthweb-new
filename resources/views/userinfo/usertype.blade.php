@extends("mobilelayouts.app")
@section("content")
  <main
    class="min-h-[calc(100vh-200px)] w-full flex flex-col md:max-w-[530px] mx-auto items-center justify-center gap-8 overflow-auto px-2">
    <img class="w-[200px] -my-12" src="{{ asset("assets/images/logo2.png") }}" alt="Logo">
    <div class="flex flex-col gap-2 items-center">
      <h2 class="text-center text-2xl sm:text-3xl md:text-4xl font-bold text-[#1F2B5B]" id="form-title">
        Welcome, Patient
      </h2>
      <p class="text-center text-gray-500 text-sm -mt-2" id="form-subtitle">
        Please select patient type
      </p>
    </div>
    <div class="w-full flex flex-col gap-2">
      {{--
        If: patient has record in the system, and is bound to user
          Then:
          redirect immediately to /patient/dashboard/{id}

        If: patient has record in the system, but fresh account
          Then:
          go to bind record formm
        Else:
          go to create record form
      --}}
      <a class="w-full bg-gray-600 hover:bg-gray-500 rounded-md py-4 text-gray-100 font-medium flex flex-col items-center justify-center"
        href="/p/onboarding/old">
        <span>Old Patient</span>
        <span class="text-xs text-gray-300">
          (Has existing clinical records)
        </span>
      </a>

      <a class="w-full bg-blue-950 hover:bg-blue-900 rounded-md py-4 text-blue-100 font-medium flex flex-col items-center justify-center"
        href="/p/onboarding/new">
        <span>New Patient</span>
        <span class="text-xs text-gray-300 opacity-80">
          (Not visited the clinic yet)
        </span>
      </a>

    </div>
  </main>
@endsection
