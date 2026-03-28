
@extends('layouts.app')

@section('title', 'Consultation Form')

@section('content')
<main class="min-h-screen flex flex-col gap-8 justify-center items-center bg-white font-poppins py-6">
  
    <div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-12">
        </div>

        <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">CONSULTATION FORM</h2>

        <form action="#" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">CONSULTATION DATE</label>
                <input type="date" name="consultation_date" value="2025-03-25"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>

            <div class="grid grid-cols-6 gap-2">
                @foreach (['WT', 'BP', 'CR', 'RR', 'T', 'SPO2'] as $vital)
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">{{ $vital }}</label>
                    <input type="text" name="{{ strtolower($vital) }}" value=""
                        class="text-center border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs w-full">
                </div>
                @endforeach
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">HISTORY / PHYSICAL EXAM</label>
                <textarea name="history" rows="2" class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">DIAGNOSIS</label>
                <textarea name="diagnosis" rows="2" class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">TREATMENT</label>
                <textarea name="treatment" rows="2" class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"></textarea>
            </div>

            <div class="flex justify-between pt-2 gap-2">
                <button type="reset"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
                <button type="submit"
                        class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">SAVE</button>
            </div>
        </form>
    </div>


    

    <div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-12">
        </div>

        <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">MEDICAL CERTIFICATE FORM</h2>

        <form action="#" method="POST" class="space-y-4">        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">DOCUMENT TYPE</label>
            <select name="document_type" class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                <option>MEDICAL CERTIFICATE</option>
            </select>
        </div>
        

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">DATE ISSUED</label>
            <input type="date" name="date_issued" value="{{ date('Y-m-d') }}"
                class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">REMARKS</label>
            <textarea name="remarks" rows="2"
                class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"
                placeholder="Enter remarks here...">Rest for 2 days</textarea>
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">REQUESTOR</label>
            <input type="text" name="requestor" value="School"
                class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">REASON FOR REQUEST</label>
            <input type="text" name="reason" value="Absence Verification"
                class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <div class="flex justify-between pt-2 gap-2">
                <button type="reset"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
                <button type="submit"
                        class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">SAVE</button>
            </div>
    </form>

     
    </div>

   <div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-12">
        </div>

        <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">REFFERAL FORM</h2>

        <form action="#" method="POST" class="space-y-4">        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">DOCUMENT TYPE</label>
            <select name="document_type" class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                <option>REFERRAL LETTER</option>
            </select>
        </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">DATE ISSUED</label>
                <input type="date" name="date_issued" value="2025-03-25"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">RECIPIENT</label>
                <input type="text" name="recipient" value="DR. JOHN DOE"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700">REASON FOR REFERRAL</label>
                <textarea name="reason" rows="2" class="w-full border rounded px-4 py-2 mt-1 resize-none focus:outline-none focus:ring-2 focus:ring-blue-400">Lack of equipment</textarea>
            </div>

            <div class="flex justify-between pt-2 gap-2">
                <button type="reset"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
                <button type="submit"
                        class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">SAVE</button>
            </div>
        </form>
    </div>
    <div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-full max-w-md shadow-lg">
    <div class="flex justify-center mb-4">
        <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-12">
    </div>

    <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-4">CREATE PRESCRIPTION</h2>

    <form action="#" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-xs font-semibold text-gray-700 mb-1">Prescription Date</label>
            <input type="date" name="prescription_date" value="2025-03-25"
                class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <h3 class="text-base font-semibold text-[#1f2b5b] mt-4">MEDICINES</h3>

        <div class="flex gap-2">
    <!-- Box 1 -->
    <div class="w-1/2 border border-gray-300 rounded-md p-2 space-y-1 text-[10px]">
        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Medicine</label>
            <input type="text" name="medicine_name[]" value="Paracetamol"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Dosage</label>
            <input type="text" name="dosage[]" value="500mg"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Freq.</label>
            <input type="text" name="frequency[]" value="3x a day"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Duration</label>
            <input type="text" name="duration[]" value="7 days"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Instructions</label>
            <textarea name="instructions[]" rows="1"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] resize-none focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">After Meals</textarea>
        </div>
    </div>

    <!-- Box 2 -->
    <div class="w-1/2 border border-gray-300 rounded-md p-2 space-y-1 text-[10px]">
        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Medicine</label>
            <input type="text" name="medicine_name[]" value="Amoxicillin"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Dosage</label>
            <input type="text" name="dosage[]" value="250mg"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Freq.</label>
            <input type="text" name="frequency[]" value="2x a day"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Duration</label>
            <input type="text" name="duration[]" value="5 days"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">
        </div>

        <div>
            <label class="block font-semibold text-gray-700 mb-0.5">Instructions</label>
            <textarea name="instructions[]" rows="1"
                class="w-full border px-1.5 py-[2px] rounded text-[9px] resize-none focus:outline-none focus:ring-[0.5px] focus:ring-blue-400">Before Meals</textarea>
        </div>
    </div>
</div>



        <div class="flex justify-center">
            <button type="button"
                class="bg-green-700 hover:bg-green-800 text-white font-semibold px-4 py-2 rounded text-sm">+ MEDICINE</button>
        </div>

        <div class="flex justify-between pt-2 gap-2">
            <button type="reset"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
            <button type="submit"
                class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">SAVE</button>
        </div>
    </form>
</div>


<div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-full max-w-md shadow-lg">
    <div class="flex justify-center mb-4">
        <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-12">
    </div>

    <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">CREATE USER</h2>

    <form action="#" method="POST" class="space-y-4">
        @csrf

        <!-- Role Dropdown -->
        <div>
            <select name="role" class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
                <option>SECRETARY</option>
                <option>PATIENT</option>
                
            </select>
        </div>

        <!-- Email -->
        <div>
            <input type="email" name="email" placeholder="Email"
                class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <!-- Username -->
        <div>
            <input type="text" name="username" placeholder="Username"
                class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <!-- Password -->
        <div>
            <input type="password" name="password" placeholder="Password"
                class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <!-- Confirm Password -->
        <div>
            <input type="password" name="password_confirmation" placeholder="Confirm Password"
                class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between pt-2 gap-2">
            <button type="reset"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
            <button type="submit"
                class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">NEXT</button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-full max-w-md shadow-lg">
    <!-- Logo -->
    <div class="flex justify-center mb-4">
        <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-14">
    </div>

    <!-- Title -->
    <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">CREATE USER</h2>

    <!-- Form -->
    <form action="#" method="POST" class="space-y-4">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <input type="text" name="first_name" placeholder="First Name"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>
            <div>
                <input type="text" name="middle_name" placeholder="Middle Name"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>
            <div>
                <input type="text" name="last_name" placeholder="Last Name"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>
            <div>
                <input type="text" name="gender" placeholder="Gender"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>
            <div>
                <input type="date" name="birthdate" placeholder="Birthdate"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>
            <div>
                <input type="number" name="age" placeholder="Age"
                    class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>
        </div>

        <div>
            <input type="text" name="address" placeholder="Address"
                class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <div>
            <input type="text" name="sec_id" placeholder="SEC Assigned ID"
                class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
        </div>

        <!-- Buttons -->
        <div class="flex justify-between pt-2 gap-2">
            <button type="reset"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
            <button type="submit"
                class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">CREATE</button>
        </div>
    </form>
</div>



</div>




</main>
@endsection