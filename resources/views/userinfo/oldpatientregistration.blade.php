@extends('mobilelayouts.app')

@section('title', 'Patient Registration')

@section('content')
<main class="min-h-screen flex justify-center px-4 bg-white font-poppins pt-6 sm:pt-8">
    <div class="w-full max-w-full sm:max-w-md md:max-w-[530px] space-y-5">

        <!-- Title -->
        <h2 id="form-title" class="text-center text-2xl sm:text-3xl md:text-4xl font-bold text-[#1F2B5B]">
            Welcome
        </h2>
        <p id="form-subtitle" class="text-center text-gray-500 text-sm -mt-2">
            Please enter your Patient ID to sync your record
        </p>

        <!-- Form -->
        <form action="" method="POST" class="space-y-5">
            @csrf

            <!-- Patient ID -->
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-3 flex items-center">
                    <img src="{{ asset('assets/images/icons/id.png') }}" alt="ID Icon" class="w-5 h-5">
                </span>
                <input type="text" name="patient_id" placeholder="Enter your Patient ID"
                    class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                           text-[#1F2B5B] text-[15px] font-poppins
                           focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-[#1F2B5B] hover:bg-[#142342] text-white py-3 rounded-[12px] 
                       shadow-md font-semibold font-poppins text-base sm:text-[18px] transition">
                SYNC RECORD
            </button>
        </form>

    </div>
</main>
@endsection
