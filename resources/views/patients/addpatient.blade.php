@extends('layouts.app')

@section('title', 'Add Patient')

@section('content')
<main class="p-4 font-[Poppins] text-sm">
<div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-[1200px] shadow-lg">

        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-12">
        </div>

        <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">ADD NEW PATIENT</h2>

        <form action="#" method="POST" class="space-y-4">
            
            @csrf

            <section class="mb-4 border border-gray-200 rounded">
            <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Patient Basic Information</div>
            <div class="p-3 grid grid-cols-3 gap-2 text-xs">
                <div>
                    <label class="block mb-0.5">Last Name</label>
                    <input name="last_name" type="text" class="w-full p-1.5 border rounded text-xs" value="SOMERA">
                </div>
                <div>
                    <label class="block mb-0.5">First Name</label>
                    <input name="first_name" type="text" class="w-full p-1.5 border rounded text-xs" value="ANDREI JUDE">
                </div>
                <div>
                    <label class="block mb-0.5">Middle Name</label>
                    <input name="middle_name" type="text" class="w-full p-1.5 border rounded text-xs" value="GARCIA">
                </div>
                <div>
                    <label class="block mb-0.5">Sex</label>
                    <input name="sex" type="text" class="w-full p-1.5 border rounded text-xs" value="Male">
                </div>
                <div>
                    <label class="block mb-0.5">Birthdate</label>
                    <input name="birthdate" type="date" class="w-full p-1.5 border rounded text-xs" value="2003-09-25">
                </div>
                <div>
                    <label class="block mb-0.5">Nationality</label>
                    <input name="nationality" type="text" class="w-full p-1.5 border rounded text-xs" value="Filipino">
                </div>
                <div>
                    <label class="block mb-0.5">Age</label>
                    <input name="age" type="number" class="w-full p-1.5 border rounded text-xs" value="21">
                </div>
                <div>
                    <label class="block mb-0.5">Contact Number</label>
                    <input name="contact_number" type="text" class="w-full p-1.5 border rounded text-xs" value="09559354390">
                </div>
                <div class="col-span-3">
                    <label class="block mb-0.5">Address</label>
                    <input name="address" type="text" class="w-full p-1.5 border rounded text-xs" value="SAN JOSE CITY">
                </div>
                <div>
                    <label class="block mb-0.5">Guardian Name</label>
                    <input name="guardian_name" type="text" class="w-full p-1.5 border rounded text-xs" value="JOHN DOE">
                </div>
                <div>
                    <label class="block mb-0.5">Relationship</label>
                    <input name="relationship" type="text" class="w-full p-1.5 border rounded text-xs" value="FATHER">
                </div>
                <div>
                    <label class="block mb-0.5">Guardian Contact</label>
                    <input name="guardian_contact" type="text" class="w-full p-1.5 border rounded text-xs" value="09553564722">
                </div>
            </div>
        </section>

        <!-- Personal/Social History -->
        <section class="mb-4 border border-gray-200 rounded">
            <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Personal / Social History</div>
            <div class="p-3 grid grid-cols-4 gap-2 text-xs">
                <div>
                    <label class="block mb-0.5">Allergy</label>
                    <input name="allergy" type="text" class="w-full p-1.5 border rounded text-xs" value="None">
                </div>
                <div>
                    <label class="block mb-0.5">Alcohol</label>
                    <input name="alcohol" type="text" class="w-full p-1.5 border rounded text-xs" value="None">
                </div>
                <div>
                    <label class="block mb-0.5">Smoking Years</label>
                    <input name="smoking_years" type="text" class="w-full p-1.5 border rounded text-xs">
                </div>
                <div>
                    <label class="block mb-0.5">Drug Use</label>
                    <input name="drug_use" type="text" class="w-full p-1.5 border rounded text-xs">
                </div>
            </div>
        </section>

        <!-- Family History -->
        <section class="border border-gray-200 rounded">
            <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Family History</div>
            <div class="p-3 grid grid-cols-6 gap-2 text-xs">
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Hypertension" checked>
                    Hypertension
                </label>
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Asthma">
                    Asthma
                </label>
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Diabetes">
                    Diabetes
                </label>
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Cancer">
                    Cancer
                </label>
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Thyroid">
                    Thyroid
                </label>
                <div class="col-span-1">
                    <label class="block mb-0.5">Others</label>
                    <input name="family_history_other" type="text" class="w-full p-1.5 border rounded text-xs">
                </div>
            </div>
        </section>
    </form>
    </div>

    
    
</main>
@endsection
<!-- 
<form action="#" method="POST">
        
        <div class="flex justify-end mb-3 space-x-2">
            <button type="submit" class="bg-[#1f2b5b] text-white px-3 py-1 rounded text-xs hover:bg-[#273b5c]">
                + Save Patient
            </button>
            <a href="#" class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                Cancel
            </a>
        </div>

       
        <section class="mb-4 border border-gray-200 rounded">
            <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Patient Basic Information</div>
            <div class="p-3 grid grid-cols-3 gap-2 text-xs">
                <div>
                    <label class="block mb-0.5">Last Name</label>
                    <input name="last_name" type="text" class="w-full p-1.5 border rounded text-xs" value="SOMERA">
                </div>
                <div>
                    <label class="block mb-0.5">First Name</label>
                    <input name="first_name" type="text" class="w-full p-1.5 border rounded text-xs" value="ANDREI JUDE">
                </div>
                <div>
                    <label class="block mb-0.5">Middle Name</label>
                    <input name="middle_name" type="text" class="w-full p-1.5 border rounded text-xs" value="GARCIA">
                </div>
                <div>
                    <label class="block mb-0.5">Sex</label>
                    <input name="sex" type="text" class="w-full p-1.5 border rounded text-xs" value="Male">
                </div>
                <div>
                    <label class="block mb-0.5">Birthdate</label>
                    <input name="birthdate" type="date" class="w-full p-1.5 border rounded text-xs" value="2003-09-25">
                </div>
                <div>
                    <label class="block mb-0.5">Nationality</label>
                    <input name="nationality" type="text" class="w-full p-1.5 border rounded text-xs" value="Filipino">
                </div>
                <div>
                    <label class="block mb-0.5">Age</label>
                    <input name="age" type="number" class="w-full p-1.5 border rounded text-xs" value="21">
                </div>
                <div>
                    <label class="block mb-0.5">Contact Number</label>
                    <input name="contact_number" type="text" class="w-full p-1.5 border rounded text-xs" value="09559354390">
                </div>
                <div class="col-span-3">
                    <label class="block mb-0.5">Address</label>
                    <input name="address" type="text" class="w-full p-1.5 border rounded text-xs" value="SAN JOSE CITY">
                </div>
                <div>
                    <label class="block mb-0.5">Guardian Name</label>
                    <input name="guardian_name" type="text" class="w-full p-1.5 border rounded text-xs" value="JOHN DOE">
                </div>
                <div>
                    <label class="block mb-0.5">Relationship</label>
                    <input name="relationship" type="text" class="w-full p-1.5 border rounded text-xs" value="FATHER">
                </div>
                <div>
                    <label class="block mb-0.5">Guardian Contact</label>
                    <input name="guardian_contact" type="text" class="w-full p-1.5 border rounded text-xs" value="09553564722">
                </div>
            </div>
        </section>

        
        <section class="mb-4 border border-gray-200 rounded">
            <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Personal / Social History</div>
            <div class="p-3 grid grid-cols-4 gap-2 text-xs">
                <div>
                    <label class="block mb-0.5">Allergy</label>
                    <input name="allergy" type="text" class="w-full p-1.5 border rounded text-xs" value="None">
                </div>
                <div>
                    <label class="block mb-0.5">Alcohol</label>
                    <input name="alcohol" type="text" class="w-full p-1.5 border rounded text-xs" value="None">
                </div>
                <div>
                    <label class="block mb-0.5">Smoking Years</label>
                    <input name="smoking_years" type="text" class="w-full p-1.5 border rounded text-xs">
                </div>
                <div>
                    <label class="block mb-0.5">Drug Use</label>
                    <input name="drug_use" type="text" class="w-full p-1.5 border rounded text-xs">
                </div>
            </div>
        </section>

       
        <section class="border border-gray-200 rounded">
            <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Family History</div>
            <div class="p-3 grid grid-cols-6 gap-2 text-xs">
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Hypertension" checked>
                    Hypertension
                </label>
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Asthma">
                    Asthma
                </label>
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Diabetes">
                    Diabetes
                </label>
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Cancer">
                    Cancer
                </label>
                <label class="flex items-center gap-1 col-span-1">
                    <input name="family_history[]" type="checkbox" class="w-4 h-4" value="Thyroid">
                    Thyroid
                </label>
                <div class="col-span-1">
                    <label class="block mb-0.5">Others</label>
                    <input name="family_history_other" type="text" class="w-full p-1.5 border rounded text-xs">
                </div>
            </div>
        </section>
    </form> -->