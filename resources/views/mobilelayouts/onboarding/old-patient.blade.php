@extends("mobilelayouts.app")
@section("content")
  <div class="h-full max-h-[calc(100vh-75px)] w-full max-w-[530px] min-w-[200px] flex-none pb-6 mx-auto px-2">
    <form class="flex flex-col justify-between h-full" id="old_patient_onboarding_form" action="/p/bindrecord" method="POST">
      @csrf
      <div class="flex flex-col gap-4">
        <div>
          <h1 class="text-blue-950 font-bold text-2xl">Bind your existing record</h1>
          <p class="text-blue-950/50 text-sm">Please enter your PID</p>
        </div>
        <div class="w-full flex flex-col gap-1">
          <input type="text" name="user_id" value="{{ $authId }}" hidden>
          <input type="text" name="patient_type" value="old" hidden>
          <input class="border-2 border-blue-950 w-full py-4 rounded-md text-center text-lg font-bold font-mono"
            type="text" name="pid" placeholder="PID-XXXXXXXXX-XXXX">
          <p class="text-xs text-blue-950/50 text-center mt-1">
            Don't know your PID? Reach out to us at
            <a href="mailto:healthweb2026@gmail.com" class="text-blue-700 hover:underline">healthweb2026@gmail.com </a>
           or call/text phone 
           number: <a class="text-blue-700 hover:underline"> 09330117725 </a> and we'll be happy to assist you.
          </p>
        </div>
      </div>
      <div class="flex flex-col gap-2">
        <a class="w-full py-4 font-medium bg-gray-600 hover:bg-gray-500 hover:cursor-pointer text-blue-100 rounded-md flex justify-center"
          href="{{ route('patient.onboarding.usertype') }}">
          Cancel
        </a>
        <button class="w-full py-4 font-medium bg-blue-950 hover:blue-900 text-blue-100 rounded-md" type="submit">
          Continue
          <i class="fa-solid fa-arrow-right ms-2"></i>
        </button>
      </div>
    </form>
  </div>
@endsection
