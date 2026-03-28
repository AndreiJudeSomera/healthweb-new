@extends('mobilelayouts.app')

@section('title', 'Patient Registration')

@section('content')
<main class="min-h-screen flex justify-center px-4 bg-white font-poppins pt-6 sm:pt-8">
    <div class="w-full max-w-full sm:max-w-md md:max-w-[530px] space-y-5">

        <!-- Title -->
        <h2 id="form-title" class="text-center text-2xl sm:text-3xl md:text-4xl font-bold text-[#1F2B5B]">
            Patient Basic Info
        </h2>
        <p id="form-subtitle" class="text-center text-gray-500 text-sm -mt-2">
            Please fill up this to proceed
        </p>

        <!-- Form -->
           <form action="{{ route('userinfo.store') }}" method="POST" class="space-y-5">
        @csrf

            <!-- STEP 1 (visible on load) -->
            <div id="step1" class="space-y-5">
                <!-- Last Name -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/user.png') }}" alt="User Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="Lname" placeholder="Last Name" required
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- First Name -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/user.png') }}" alt="User Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="Fname" placeholder="First Name" required
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Middle Name -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/user.png') }}" alt="User Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="Mname" placeholder="Middle Name"
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Age -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/age.png') }}" alt="Age Icon" class="w-5 h-5">
                    </span>
                    <input type="number" name="Age" placeholder="Age"
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Birthdate -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/calendar.png') }}" alt="Calendar Icon" class="w-5 h-5">
                    </span>
                    <input type="date" name="DateofBirth"
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                 <div class="relative w-full">
                    <select name="Gender" required
                        class="w-full p-4 border border-[#1F2B5B] rounded-[15px]
                            text-[#1F2B5B] text-[15px] font-poppins
                            focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                        <option value="">Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <!-- Nationality -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/flag.png') }}" alt="Flag Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="Nationality" placeholder="Nationality"
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Contact No -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/phone.png') }}" alt="Phone Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="ContactNumber" placeholder="Contact No."
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Buttons -->
                <div class="flex justify-end">
                    <button type="button" onclick="nextStep(1)"
                        class="w-full bg-[#1F2B5B] hover:bg-[#142342] text-white py-3 rounded-[12px] 
                            shadow-md font-semibold font-poppins text-base sm:text-[18px] transition">
                        NEXT
                    </button>
                </div>
            </div>

           <!-- STEP 2 -->
            <div id="step2" class="space-y-5 hidden">
                <!-- Address -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/home.png') }}" alt="Home Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="Address" placeholder="Address"
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Guardian Name -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/user.png') }}" alt="Guardian Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="GuardianName" placeholder="Guardian Name"
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Guardian Relationship -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/relation.png') }}" alt="Relation Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="GuardianRelation" placeholder="Guardian Relationship"
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Guardian Contact -->
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-3 flex items-center">
                        <img src="{{ asset('assets/images/icons/phone.png') }}" alt="Phone Icon" class="w-5 h-5">
                    </span>
                    <input type="text" name="GuardianContact" placeholder="Guardian Contact No."
                        class="w-full pl-12 p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>
        <!-- STEP 2 Buttons -->
            <div class="flex justify-between gap-3">
                <button type="button" onclick="prevStep(2)"   
                    class="w-1/2 bg-gray-200 hover:bg-gray-300 text-[#1F2B5B] py-3 rounded-[12px] 
                        shadow font-semibold font-poppins text-base sm:text-[18px] transition">
                    BACK
                </button>
                <button type="button" onclick="nextStep(2)"
                    class="w-1/2 bg-[#1F2B5B] hover:bg-[#142342] text-white py-3 rounded-[12px] 
                        shadow-md font-semibold font-poppins text-base sm:text-[18px] transition">
                    NEXT
                </button>
            </div>
            </div>

            <!-- STEP 3 -->
            <div id="step3" class="space-y-5 hidden">
                <!-- Allergy -->
                <div class="relative w-full">
                    <input type="text" name="Allergy" placeholder="Allergy"
                        class="w-full p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Alcohol -->
               <div class="relative w-full">
                    <select name="Alcohol" required
                        class="w-full p-4 border border-[#1F2B5B] rounded-[15px]
                            text-[#1F2B5B] text-[15px] font-poppins
                            focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                        <option value="">Alcohol</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <!-- Years of Smoking -->
                <div class="relative w-full">
                    <input type="number" name="Years_of_Smoking" placeholder="Years of Smoking"
                        class="w-full p-4 border border-[#1F2B5B] rounded-[15px]
                               text-[#1F2B5B] text-[15px] font-poppins
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

                <!-- Illicit Drug Use -->
                <div class="relative w-full">
                    <select name="IllicitDrugUse" required
                        class="w-full p-4 border border-[#1F2B5B] rounded-[15px]
                            text-[#1F2B5B] text-[15px] font-poppins
                            focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                        <option value="">Illicit Drug</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>

                <!-- STEP 3 Buttons -->
                <div class="flex justify-between gap-3">
                    <button type="button" onclick="prevStep(3)" 
                        class="w-1/2 bg-gray-200 hover:bg-gray-300 text-[#1F2B5B] py-3 rounded-[12px] 
                            shadow font-semibold font-poppins text-base sm:text-[18px] transition">
                        BACK
                    </button>
                    <button type="button" onclick="nextStep(3)"
                        class="w-1/2 bg-[#1F2B5B] hover:bg-[#142342] text-white py-3 rounded-[12px] 
                            shadow-md font-semibold font-poppins text-base sm:text-[18px] transition">
                        NEXT
                    </button>
                </div>
            </div>

            <!-- STEP 4 -->
            <div id="step4" class="space-y-6 hidden">
                <!-- Checkbox Grid -->
                <div class="grid grid-cols-3 gap-4 text-sm text-[#1F2B5B]">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="Hypertension" value="1"
                            class="w-4 h-4 text-[#1F2B5B] border-gray-300 rounded">
                        Hypertension
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="Asthma" value="1"
                            class="w-4 h-4 text-[#1F2B5B] border-gray-300 rounded">
                        Asthma
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="Diabetes" value="1"
                            class="w-4 h-4 text-[#1F2B5B] border-gray-300 rounded">
                        Diabetes
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="Cancer" value="1"
                            class="w-4 h-4 text-[#1F2B5B] border-gray-300 rounded">
                        Cancer
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="Thyroid" value="1"
                            class="w-4 h-4 text-[#1F2B5B] border-gray-300 rounded">
                        Thyroid
                    </label>
                </div>

                <!-- Others -->
                <div>
                    <label for="other_conditions" class="block text-sm font-medium text-[#1F2B5B] mb-1">Others</label>
                    <input type="text" name="Others" id="other_conditions"
                        placeholder="Specify other conditions"
                        class="w-full p-3 border border-[#1F2B5B] rounded-[12px] text-[15px] text-[#1F2B5B]
                               focus:outline-none focus:ring-2 focus:ring-[#1F2B5B]">
                </div>

               <!-- STEP 4 Buttons -->
                <div class="flex justify-between gap-3">
                    <button type="button" onclick="prevStep(4)" 
                        class="w-1/2 bg-gray-200 hover:bg-gray-300 text-[#1F2B5B] py-3 rounded-[12px] 
                            shadow font-semibold font-poppins text-base sm:text-[18px] transition">
                        BACK
                    </button>
                    <button type="submit"
                        class="w-1/2 bg-[#1F2B5B] hover:bg-[#142342] text-white py-3 rounded-[12px] 
                            shadow-md font-semibold font-poppins text-base sm:text-[18px] transition">
                        SUBMIT
                    </button>
                </div>
        </form>
    </div>
</main>

<script>
    function nextStep(step) {
        document.getElementById("step" + step).classList.add("hidden");
        document.getElementById("step" + (step + 1)).classList.remove("hidden");
    }

    function prevStep(step) {
        document.getElementById("step" + step).classList.add("hidden");
        document.getElementById("step" + (step - 1)).classList.remove("hidden");
    }
</script>

@endsection
