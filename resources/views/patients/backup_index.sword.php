@extends('layouts.app')

@section('title', 'Patients')

@section('content')
<main class="flex-1 p-4">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <!-- Search + Filter -->
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" id="searchPatient" placeholder="Search Patients"
                    class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm w-72">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
            </div>

            <div class="flex items-center gap-2 text-sm">
                <span>Showing</span>
                <select id="showCount" class="border rounded px-2 py-1">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <button id="filterBtn"
                    class="px-3 py-1 border rounded flex items-center gap-1">
                <svg id="filterIcon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
                <span id="filterText">Filter</span>
            </button>

                        </div>
        </div>
<!-- Filter Options (Hidden initially) -->
<div id="filterOptions" class="mt-2 p-4 border rounded bg-gray-50 text-sm shadow-md relative flex gap-4 items-center">

    <!-- Age -->
    <div>
        <label class="block mb-0.5 font-medium">Age</label>
        <select id="filterAge" class="border rounded px-2 py-1">
            <option value="">All</option>
            <option value="0-10">0-10</option>
            <option value="11-20">11-20</option>
            <option value="21-30">21-30</option>
            <option value="31-40">31-40</option>
            <option value="41-50">41-50</option>
            <option value="51-60">51-60</option>
            <option value="61+">61+</option>
        </select>
    </div>

    <!-- Gender -->
    <div>
        <label class="block mb-0.5 font-medium">Gender</label>
        <select id="filterGender" class="border rounded px-2 py-1">
            <option value="">All</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>

    <!-- You can add more filters like date here -->
</div>
        <!-- Add New -->
        <button id="addPatientBtn"
            class="bg-blue-900 hover:bg-blue-950 text-white px-4 py-2 rounded text-sm flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4" />
            </svg>
            Add New Patient
        </button>
    </div>

    <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            
        
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded text-sm">
                    <thead>
                        <tr class="bg-[#1a2e4c] text-white text-xs">
                            <th class="px-4 py-2 hidden">ID</th>
                            <th class="px-4 py-2 text-left">Last Name</th>
                            <th class="px-4 py-2 text-left">First Name</th>
                            <th class="px-4 py-2 text-center">Age</th>
                            <th class="px-4 py-2 text-center">Sex</th>
                            <th class="px-4 py-2 text-center">Date Added</th>
                        </tr>
                    </thead>
                    <tbody id="patientsTable" class="text-gray-700 text-xs">
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-gray-400 italic">
                                Loading patients...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4 text-sm text-gray-600">
        <span>Previous page</span>
        <div class="flex gap-1">
            @for ($p = 1; $p <= 5; $p++)
                <button class="px-2 py-1 rounded @if($p == 1) bg-blue-700 text-white @else bg-gray-200 @endif">
                    {{ $p }}
                </button>
            @endfor
        </div>
        <span>Next page</span>
    </div>
</main>

<!-- Hidden Modal Form -->
<div id="patientFormModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-start p-6 overflow-y-auto z-50">
    <div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-[1200px] shadow-lg relative">
        <!-- Close Button -->
        <button id="closePatientForm" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
            ✕
        </button>

        <div class="flex justify-center mb-4">
            <img src="{{ asset('assets/images/logo2.png') }}" alt="Logo" class="h-12">
        </div>

        <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">PATIENT FORM</h2>

        <form action="{{ route('patients.store') }}" method="POST" class="space-y-4">
            @csrf
            <!-- Patient Basic Info -->
            <section class="mb-4 border border-gray-200 rounded">
                <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Patient Basic Information</div>
                <div class="p-3 grid grid-cols-3 gap-2 text-xs">
                    <div>
                        <label class="block mb-0.5">Last Name</label>
                        <input name="Lname" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div>
                        <label class="block mb-0.5">First Name</label>
                        <input name="Fname" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div>
                        <label class="block mb-0.5">Middle Name</label>
                        <input name="Mname" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div>
                        <label class="block mb-0.5">Age</label>
                        <input name="Age" type="number" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div>
                        <label class="block mb-0.5">Birthdate</label>
                        <input name="DateofBirth" type="date" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div> <label class="block mb-0.5">Sex</label>
                        <input list="gender" name="Gender" type="text" 
                            class="w-full p-1.5 border rounded text-xs" placeholder="">
                            <datalist id="gender">
                            <option value="Male">
                            <option value="Female">
                    </div>
                    
                    <div>
                        <label class="block mb-0.5">Nationality</label>
                        <input name="Nationality" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    
                    <div>
                        <label class="block mb-0.5">Contact Number</label>
                        <input name="ContactNumber" type="text" maxlength="11" pattern="\d{11}" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div class="col-span-3">
                        <label class="block mb-0.5">Address</label>
                        <input name="Address" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div>
                        <label class="block mb-0.5">Guardian Name</label>
                        <input name="GuardianName" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div>
                        <label class="block mb-0.5">Relationship</label>
                        <input name="GuardianRelation" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
                    <div>
                        <label class="block mb-0.5">Guardian Contact</label>
                        <input name="GuardianContact" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
                </div>
            </section>

            <!-- Personal/Social History -->
            <section class="mb-4 border border-gray-200 rounded">
                <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Personal / Social History</div>
                <div class="p-3 grid grid-cols-4 gap-2 text-xs">
                   <div>
                        <label class="block mb-0.5">Allergy</label>
                        <input list="allergyOptions" name="Allergy" type="text" 
                            class="w-full p-1.5 border rounded text-xs" placeholder="Type or select">
                        <datalist id="allergyOptions">
                            <option value="None">
                        </datalist>
                    </div>
                    <div>
                        <label class="block mb-0.5">Alcohol</label>
                        <input list="AlcoholOptions" name="Alcohol" type="text" 
                            class="w-full p-1.5 border rounded text-xs" placeholder="">
                        <datalist id="AlcoholOptions">
                            <option value="None">
                            <option value="Occasional">
                            <option value="Moderate">
                            <option value="Heavy">
                                
                        </datalist>
                    </div>
                    <div>
                       <label class="block mb-0.5">Years of Smoking</label>
                        <input list="smoking" name="Years_of_Smoking" type="text" 
                            class="w-full p-1.5 border rounded text-xs" placeholder="">
                        <datalist id="smoking">
                            <option value="0">
                            <option value="1">
                            <option value="2">
                        </datalist>
                    </div>
                    <div>
                        <label class="block mb-0.5">Illicit Drug Use</label>
                         <input list="druguse" name="IllicitDrugUse" type="text" 
                            class="w-full p-1.5 border rounded text-xs" placeholder="Type or select">
                        <datalist id="druguse">
                            <option value="Yes">
                            <option value="None">
                        </datalist>
                    </div>
                </div>
            </section>

            <!-- Family History -->
            <section class="border border-gray-200 rounded">
                <div class="bg-[#1a2e4c] text-white px-3 py-2 font-medium text-xs">Family History</div>
                <div class="p-3 grid grid-cols-6 gap-2 text-xs">
                    <label class="flex items-center gap-1 col-span-1">
                        <input name="Hypertension" type="checkbox" class="w-4 h-4" value="1">
                        Hypertension
                    </label>
                    <label class="flex items-center gap-1 col-span-1">
                         <input name="Asthma" type="checkbox" class="w-4 h-4" value="1">
                        Asthma
                    </label>
                    <label class="flex items-center gap-1 col-span-1">
                        <input name="Diabetes" type="checkbox" class="w-4 h-4" value="1">
                        Diabetes
                    </label>
                    <label class="flex items-center gap-1 col-span-1">
                        <input name="Cancer" type="checkbox" class="w-4 h-4" value="1">
                        Cancer
                    </label>
                    <label class="flex items-center gap-1 col-span-1">
                        <input name="Thyroid" type="checkbox" class="w-4 h-4" value="1">
                        Thyroid
                    </label>
                    <div class="col-span-1">
                        <label class="block mb-0.5">Others</label>
                        <input name="Others" type="text" class="w-full p-1.5 border rounded text-xs">
                    </div>
        
                </div>
                 
            </section>
            <div class="flex justify-between pt-2 gap-2">
                <button type="reset"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
                <button type="submit"
                        class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">SAVE</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const patientsTable = document.getElementById('patientsTable');
    const addPatientBtn = document.getElementById('addPatientBtn');
    const patientFormModal = document.getElementById('patientFormModal');
    const closePatientForm = document.getElementById('closePatientForm');
    const patientForm = patientFormModal.querySelector('form');
    const searchInput = document.getElementById('searchPatient');
    const showCountSelect = document.getElementById('showCount');
    const filterBtn = document.getElementById('filterBtn');

    // Filter elements
    const filterOptions = document.getElementById('filterOptions');
    const filterAge = document.getElementById('filterAge');
    const filterGender = document.getElementById('filterGender');
    const filterDate = document.getElementById('filterDate');

    let allPatients = []; // Store all fetched patients
    let filteredPatients = []; // Store filtered patients

    // -------------------------
    // Load Patients via AJAX
    // -------------------------
    function loadPatients() {
        fetch("{{ route('patients.index') }}", { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            allPatients = data;
            filteredPatients = allPatients;
            renderPatients();
        })
        .catch(err => {
            console.error(err);
            patientsTable.innerHTML = `<tr><td colspan="6" class="px-4 py-3 text-center text-red-500">Failed to load patients.</td></tr>`;
        });
    }

    // -------------------------
    // Render Patients to Table
    // -------------------------
    function renderPatients() {
        const showCount = parseInt(showCountSelect.value) || filteredPatients.length;
        patientsTable.innerHTML = '';

        if(filteredPatients.length === 0){
            patientsTable.innerHTML = `<tr><td colspan="6" class="px-4 py-3 text-center text-gray-400 italic">No patients found.</td></tr>`;
            return;
        }

        filteredPatients.slice(0, showCount).forEach(p => {
            const row = document.createElement('tr');
            row.classList.add('cursor-pointer', 'hover:bg-gray-100');
            row.innerHTML = `
                <td class="hidden">${p.PatientRecord_ID}</td>
                <td class="px-4 py-2">${p.Lname}</td>
                <td class="px-4 py-2">${p.Fname}</td>
                <td class="px-4 py-2 text-center">${p.Age}</td>
                <td class="px-4 py-2 text-center">${p.Gender}</td>
                <td class="px-4 py-2 text-center">${new Date(p.created_at).toLocaleString()}</td>
            `;
            row.addEventListener('click', () => {
                window.location.href = `/patients/${p.PatientRecord_ID}`;
            });
            patientsTable.appendChild(row);
        });
    }

    // -------------------------
    // Apply Filters (Real-time)
    // -------------------------
    function applyFilters() {
        const term = searchInput.value.toLowerCase();
        const ageRange = filterAge.value;
        const gender = filterGender.value;
        const selectedDate = filterDate ? filterDate.value : '';

        filteredPatients = allPatients.filter(p => {
            // Search filter
            let searchMatch = p.Lname.toLowerCase().includes(term) || p.Fname.toLowerCase().includes(term);

            // Age filter
            let ageMatch = true;
            if(ageRange){
                const [minAge, maxAge] = ageRange.split('-');
                if(maxAge){
                    ageMatch = p.Age >= parseInt(minAge) && p.Age <= parseInt(maxAge);
                } else {
                    ageMatch = p.Age >= parseInt(minAge);
                }
            }

            // Gender filter
            let genderMatch = true;
            if(gender){
                genderMatch = p.Gender.toLowerCase() === gender.toLowerCase();
            }

            // Date Added filter
            let dateMatch = true;
            if(selectedDate){
                const patientDate = new Date(p.created_at).toISOString().split('T')[0];
                dateMatch = patientDate === selectedDate;
            }

            return searchMatch && ageMatch && genderMatch && dateMatch;
        });

        renderPatients();
    }

    // -------------------------
    // Real-time event listeners
    // -------------------------
    searchInput.addEventListener('input', applyFilters);
    filterAge.addEventListener('change', applyFilters);
    filterGender.addEventListener('change', applyFilters);
    if(filterDate) filterDate.addEventListener('change', applyFilters);
    showCountSelect.addEventListener('change', renderPatients);

    // -------------------------
    // Toggle filter panel
    // -------------------------
   // Toggle filter panel & icon
filterBtn.addEventListener('click', () => {
    const isHidden = filterOptions.classList.toggle('hidden'); // show/hide filter panel
    const filterIcon = document.getElementById('filterIcon');
    const filterText = document.getElementById('filterText');

    if (isHidden) {
        // Panel is now hidden → show magnifying glass
        filterIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
        `;
        filterText.textContent = 'Filter';
    } else {
        // Panel is now visible → show X icon
        filterIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        `;
        filterText.textContent = 'Close';
    }
});

    // -------------------------
    // Modal open & close
    // -------------------------
    addPatientBtn.addEventListener('click', () => {
        patientFormModal.classList.remove('hidden');
    });
    closePatientForm.addEventListener('click', () => {
        patientFormModal.classList.add('hidden');
        patientForm.reset();
    });

    // -------------------------
    // Submit Patient Form via AJAX
    // -------------------------
    patientForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(patientForm);
        fetch(patientForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                alert('Patient added successfully!');
                patientFormModal.classList.add('hidden');
                patientForm.reset();
                loadPatients();
            } else {
                alert('Failed to add patient.');
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while saving the patient.');
        });
    });

    loadPatients(); // Initial load
});
</script>

@endpush
