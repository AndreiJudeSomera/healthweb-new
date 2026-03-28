@extends('layouts.app')

@section('title', 'Patient Info')

@section('content')
<main class="flex-1 p-4 text-sm text-[#1f2b5b] font-sans space-y-4">

    <!-- Back Button -->
   <div class="flex justify-between items-center mb-2">

    <!-- LEFT SIDE: Patient ID -->
    <span class="text-lg font-bold mb-2 text-[#1f2b5b]">
        Patient ID #: <span id="pid-label"></span>
    </span>

        
    <!-- RIGHT SIDE: Back Button -->
    <a href="{{ route('patients.index') }}" 
       class="bg-[#1f2b5b] hover:bg-blue-950 text-white px-4 py-2 rounded text-sm h-[35px] w-[175px] flex items-center justify-center">
        Back to Patients
    </a>

</div>


    <!-- Patient Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Basic Info -->
        <div class="border rounded-lg overflow-hidden">
            <div class="bg-[#1f2b5b] text-white px-4 py-2 flex justify-between items-center font-semibold">
                <span>Basic Information</span>
                <div class="flex gap-2 text-base">
                    <button id="edit-basic-info-btn" class="hover:text-gray-300">
                        <img src="{{ asset('assets/images/icons/editrec.png') }}" alt="edit" class="w-5 h-5">
                    </button>
                    <button id="save-basic-info-btn" class="hover:text-gray-300">
                        <img src="{{ asset('assets/images/icons/saverec.png') }}" alt="edit" class="w-5 h-5">
                    </button>
                    
                </div>
            </div>
            <div class="p-4 space-y-3" id="basic-info">
                <!-- AJAX loads basic info here -->
            </div>
        </div>

        <div id="basic-info-form-container" class="hidden">
            <!-- form will be injected here via JS -->
        </div>

        <!-- Personal & Family History -->
        <div class="border rounded-lg overflow-hidden">
            <div class="bg-[#1f2b5b] text-white px-4 py-2 flex justify-between items-center font-semibold">
                <span>Personal/Social History</span>
               
                <div class="flex gap-2 text-base">
                    <button id="edit-history-btn" class="hover:text-gray-300">
                        <img src="{{ asset('assets/images/icons/editrec.png') }}" alt="edit" class="w-5 h-5">
                    </button>
                    <button id="save-history-btn" class="hover:text-gray-300">
                        <img src="{{ asset('assets/images/icons/saverec.png') }}" alt="edit" class="w-5 h-5">
                    </button>
                    
                </div>
            </div>
            <div class="p-4 space-y-3" id="personal-history">
                <!-- AJAX loads personal history here -->
            </div>

            <div class="bg-[#1f2b5b] text-white px-4 py-2 font-semibold">Family History</div>
            <div class="p-4 grid grid-cols-3 sm:grid-cols-6 gap-2 text-center" id="family-history">
                <!-- AJAX loads family history here -->
            </div>
        </div>
    </div>

    <!-- Records Section -->
    <div class="bg-white rounded-lg shadow p-4">
        <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
            <!-- Search + Filter -->
            <div class="flex items-center gap-4 flex-wrap">
                <div class="relative">
                    <input type="text" placeholder="Search Records" id="search-records"
                        class="pl-10 pr-4 border focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"
                        style="width: 400px; height: 40px; background-color: #FAFAFA; border-radius: 4px;">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-base">
                        <img src="{{ asset('assets/images/icons/search.png') }}" alt="search" class="w-4 h-4">
                    </span>
                </div>

                <select class="border rounded text-sm w-[50px] h-[30px] px-1 py-0.5 bg-[#e6e6e6] rounded-[10px]" id="records-per-page">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                </select>

                <button class="border hover:bg-gray-100 flex items-center justify-center gap-2"
                    style="width: 80px; height: 40px; border-radius: 4px; font-size: 12px;" id="filter-records">
                    <img src="{{ asset('assets/images/icons/filter.png') }}" alt="view" class="w-4 h-4">
                    <span>Filter</span>
                </button>
            </div>

            <!-- Actions -->
            <div class="flex gap-2">
                <button class="bg-[#1f2b5b] hover:bg-blue-950 text-white px-4 py-2 rounded text-sm h-[35px] w-[175px] flex items-center justify-center">Create Prescription</button>
              <button id="create-consultation-btn" class="bg-[#1f2b5b] hover:bg-blue-950 text-white px-4 py-2 rounded text-sm h-[35px] w-[175px] flex items-center justify-center">Create Consultation</button>
            </div>
        </div>

        <!-- Records Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto text-sm">
                <thead class="bg-[#1f2b5b] text-white">
                    <tr>
                        <th class="py-2 px-4 text-left">#</th>
                        <th class="py-2 px-4 text-left">Date</th>
                        <th class="py-2 px-4 text-left">Record Type</th>
                        <th class="py-2 px-4 text-left">Action</th>
                    </tr>
                </thead>
                <tbody id="records-table-body">
                    <!-- AJAX loads records here -->
                </tbody>
            </table>
        </div>

        <!-- Add Consultation Modal/Form -->
<div id="consultation-form-container" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl border-2 border-blue-900 p-6 w-full max-w-md shadow-lg relative">
        <button id="close-consultation-form" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">&times;</button>
        <h2 class="text-center text-lg font-semibold text-[#1f2b5b] mb-6">PATIENT FORM</h2>
        <form id="consultation-form" method="POST" action="{{ route('consultations.store', ['patientId' => $patient->PatientRecord_ID]) }}">
    @csrf
            <input type="hidden" name="PatientRecord_ID" value="{{ $patient->PatientRecord_ID }}">

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Consultation Date</label>
                <input type="date" name="ConsultationDate" id="ConsultationDate" value="{{ date('Y-m-d') }}" class="w-full border px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm">
            </div>

            <div class="grid grid-cols-6 gap-2">
                <!-- Vitals typed individually -->
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">WT</label>
                    <input type="text" name="WT" id="WT" class="text-center border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs w-full">
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">BP</label>
                    <input type="text" name="BP" id="BP" class="text-center border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs w-full">
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">CR</label>
                    <input type="text" name="CR" id="CR" class="text-center border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs w-full">
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">RR</label>
                    <input type="text" name="RR" id="RR" class="text-center border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs w-full">
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">Temperature</label>
                    <input type="text" name="Temperature" id="Temperature" class="text-center border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs w-full">
                </div>
                <div class="flex flex-col items-center">
                    <label class="text-xs font-semibold text-gray-700 mb-1">SP02</label>
                    <input type="text" name="SP02" id="SP02" class="text-center border px-2 py-1 rounded focus:outline-none focus:ring-2 focus:ring-blue-400 text-xs w-full">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">History / Physical Exam</label>
                <textarea name="History_PhysicalExam" id="History_PhysicalExam" rows="2" class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Diagnosis</label>
                <textarea name="Diagnosis" rows="2" id="Diagnosis" class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Treatment</label>
                <textarea name="Treatment" rows="2" id="Treatment" class="w-full border px-4 py-2 rounded resize-none focus:outline-none focus:ring-2 focus:ring-blue-400 text-sm"></textarea>
            </div>

            <div class="flex justify-between pt-2 gap-2">
                <button type="reset" class="bg-red-600 hover:bg-red-700 text-white font-semibold w-full py-2 rounded">CANCEL</button>
                <button type="submit" class="bg-[#1f2b5b] hover:bg-blue-900 text-white font-semibold w-full py-2 rounded">SAVE</button>
            </div>
        </form>
    </div>
</div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4 text-sm text-gray-600" id="records-pagination">
            <!-- AJAX pagination here -->
        </div>
    </div>
</main>
@endsection

@push('scripts')
@push('scripts')
<script>
$(document).ready(function() {
    const patientId = "{{ $patient->PatientRecord_ID }}";
    let patientData = {};
    let isEditingBasic = false;
    let isEditingHistory = false;
    let editingId = null;

    // CSRF setup for AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // -----------------------------
    // Load Patient Info
    // -----------------------------
    function loadPatientInfo() {
        $.getJSON(`/patients/${patientId}/data`, function(data) {
            patientData = data;

            // Update PID label
            $('#pid-label').text(data.PID_Number ?? 'N/A');

            // Basic Info
            const basicHTML = `
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div><div class="text-[11px] text-[#1f2b5b]">Last Name</div><div class="text-black text-[13px] font-medium uppercase">${data.Lname}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">First Name</div><div class="text-black text-[13px] font-medium uppercase">${data.Fname}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Middle Name</div><div class="text-black text-[13px] font-medium uppercase">${data.Mname || ''}</div></div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div><div class="text-[11px] text-[#1f2b5b]">Age</div><div class="text-black text-[13px] font-medium uppercase">${data.Age} Y.O</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Sex</div><div class="text-black text-[13px] font-medium uppercase">${data.Gender}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Birthdate</div><div class="text-black text-[13px] font-medium uppercase">${data.DateofBirth ? new Date(data.DateofBirth).toLocaleDateString() : 'N/A'}</div></div>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div><div class="text-[11px] text-[#1f2b5b]">Nationality</div><div class="text-black text-[13px] font-medium uppercase">${data.Nationality}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Contact No.</div><div class="text-black text-[13px] font-medium uppercase">${data.ContactNumber}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Address</div><div class="text-black text-[13px] font-medium uppercase">${data.Address}</div></div>
                </div>
                ${data.Age < 18 ? `
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <div><div class="text-[11px] text-[#1f2b5b]">Guardian Name</div><div class="text-black text-[13px] font-medium uppercase">${data.GuardianName}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Guardian Relationship</div><div class="text-black text-[13px] font-medium uppercase">${data.GuardianRelation}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Guardian Contact</div><div class="text-black text-[13px] font-medium uppercase">${data.GuardianContact}</div></div>
                </div>` : ''}
            `;
            $('#basic-info').html(basicHTML);

            // Personal History
            const personalHTML = `
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div><div class="text-[11px] text-[#1f2b5b]">Allergy</div><div class="text-black text-[13px] font-medium uppercase">${data.Allergy || 'None'}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Alcohol</div><div class="text-black text-[13px] font-medium uppercase">${data.Alcohol || 'None'}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Smoking</div><div class="text-black text-[13px] font-medium uppercase">${data.Years_of_Smoking || '0.0 pack years'}</div></div>
                    <div><div class="text-[11px] text-[#1f2b5b]">Illicit Drug Use</div><div class="text-black text-[13px] font-medium uppercase">${data.IllicitDrugUse || 'None'}</div></div>
                </div>
            `;
            $('#personal-history').html(personalHTML);

            // Family History
            const familyKeys = ['Hypertension','Asthma','Diabetes','Cancer','Thyroid'];
            let fhHTML = '';
            $.each(familyKeys, function(i, key) {
                fhHTML += `
                    <div>
                        <div class="text-[11px] text-[#1f2b5b]">${key}</div>
                        <div class="mt-1">
                            <img src="${data[key] ? '{{ asset('assets/images/icons/check.png') }}' : '{{ asset('assets/images/icons/x.png') }}'}" class="w-5 h-5 mx-auto">
                        </div>
                    </div>
                `;
            });
            fhHTML += `<div><div class="text-[11px] text-[#1f2b5b]">Others</div><div class="text-black text-[13px] font-medium mt-1">${data.Others || 'NONE'}</div></div>`;
            $('#family-history').html(fhHTML);
        });
    }

    // Initial load
    loadPatientInfo();

    // -----------------------------
    // Toggle Basic Info Edit
    // -----------------------------
    $('#edit-basic-info-btn').click(function() {
        const btnImg = $(this).find('img');
        if(!isEditingBasic) {
            isEditingBasic = true;
            btnImg.attr('src', "{{ asset('assets/images/icons/cancel.png') }}").attr('alt','cancel');

            const data = patientData;
            const birthdateInput = data.DateofBirth ? new Date(data.DateofBirth).toISOString().slice(0,10) : '';
            const formHTML = `
                <form id="basic-info-form" class="space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div><label>Last Name</label><input type="text" name="Lname" value="${data.Lname}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>First Name</label><input type="text" name="Fname" value="${data.Fname}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Middle Name</label><input type="text" name="Mname" value="${data.Mname || ''}" class="w-full border px-2 py-1 rounded"></div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div><label>Age</label><input type="number" name="Age" value="${data.Age}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Sex</label>
                            <select name="Gender" class="w-full border px-2 py-1 rounded">
                                <option ${data.Gender=='Male'?'selected':''}>Male</option>
                                <option ${data.Gender=='Female'?'selected':''}>Female</option>
                            </select>
                        </div>
                        <div><label>Birthdate</label><input type="date" name="DateofBirth" value="${birthdateInput}" class="w-full border px-2 py-1 rounded"></div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div><label>Nationality</label><input type="text" name="Nationality" value="${data.Nationality}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Contact No.</label><input type="text" name="ContactNumber" value="${data.ContactNumber}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Address</label><input type="text" name="Address" value="${data.Address}" class="w-full border px-2 py-1 rounded"></div>
                    </div>
                    ${data.Age < 18 ? `
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div><label>Guardian Name</label><input type="text" name="GuardianName" value="${data.GuardianName}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Guardian Relationship</label><input type="text" name="GuardianRelation" value="${data.GuardianRelation}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Guardian Contact</label><input type="text" name="GuardianContact" value="${data.GuardianContact}" class="w-full border px-2 py-1 rounded"></div>
                    </div>` : ''}
                </form>
            `;
            $('#basic-info').html(formHTML);

            // Save button
            $('#save-basic-info-btn').off('click').click(function() {
                const formData = $('#basic-info-form').serialize();
                $.post(`/patients/${patientId}/update-basic`, formData, function(res){
                    if(res.success) {
                        alert('✅ Basic info updated!');
                        isEditingBasic = false;
                        btnImg.attr('src', "{{ asset('assets/images/icons/editrec.png') }}").attr('alt','edit');
                        loadPatientInfo();
                    } else {
                        alert('❌ Failed to update!');
                    }
                });
            });

        } else {
            isEditingBasic = false;
            btnImg.attr('src', "{{ asset('assets/images/icons/editrec.png') }}").attr('alt','edit');
            loadPatientInfo();
        }
    });

    // -----------------------------
    // Toggle History Edit
    // -----------------------------
    $('#edit-history-btn').click(function() {
        const btnImg = $(this).find('img');
        if(!isEditingHistory) {
            isEditingHistory = true;
            btnImg.attr('src', "{{ asset('assets/images/icons/cancel.png') }}").attr('alt','cancel');

            const data = patientData;
            const formHTML = `
                <form id="history-form" class="space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div><label>Allergy</label><input type="text" name="Allergy" value="${data.Allergy || ''}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Alcohol</label><input type="text" name="Alcohol" value="${data.Alcohol || ''}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Smoking</label><input type="text" name="Years_of_Smoking" value="${data.Years_of_Smoking || ''}" class="w-full border px-2 py-1 rounded"></div>
                        <div><label>Illicit Drug Use</label><input type="text" name="IllicitDrugUse" value="${data.IllicitDrugUse || ''}" class="w-full border px-2 py-1 rounded"></div>
                    </div>
                    <div class="grid grid-cols-6 gap-2 text-center mt-2">
                        ${['Hypertension','Asthma','Diabetes','Cancer','Thyroid'].map(f => `
                            <div>
                                <label>${f}</label>
                                <input type="checkbox" name="${f}" ${data[f] ? 'checked' : ''} class="mt-1">
                            </div>
                        `).join('')}
                    </div>
                    <div class="mt-2">
                        <label>Others</label>
                        <input type="text" name="Others" value="${data.Others || ''}" class="w-full border px-2 py-1 rounded">
                    </div>
                </form>
            `;
            $('#personal-history').html(formHTML);

            // Save button
            $('#save-history-btn').off('click').click(function() {
                const formData = $('#history-form').serialize();
                $.post(`/patients/${patientId}/update-history`, formData, function(res){
                    if(res.success) {
                        alert('✅ History updated!');
                        isEditingHistory = false;
                        btnImg.attr('src', "{{ asset('assets/images/icons/editrec.png') }}").attr('alt','edit');
                        loadPatientInfo();
                    } else {
                        alert('❌ Failed to update!');
                    }
                });
            });

        } else {
            isEditingHistory = false;
            btnImg.attr('src', "{{ asset('assets/images/icons/editrec.png') }}").attr('alt','edit');
            loadPatientInfo();
        }
    });

    // -----------------------------
    // Consultation Modal
    // -----------------------------
    const $formContainer = $('#consultation-form-container');
    const $form = $('#consultation-form');

    $('#create-consultation-btn').click(function() {
        $formContainer.removeClass('hidden');
    });

    $('#close-consultation-form').click(function() {
        $formContainer.addClass('hidden');
        editingId = null;
        $form[0].reset();
    });

    $formContainer.click(function(e){
        if(e.target === this){
            $(this).addClass('hidden');
            editingId = null;
            $form[0].reset();
        }
    });

    // Load consultations
    function loadConsultations() {
        $.getJSON(`/patients/${patientId}/consultations`, function(data){
            const tbody = $('#records-table-body');
            tbody.empty();
            data.forEach(c => {
                tbody.append(`
                    <tr>
                        <td class="border p-2">${c.ConsultationDate}</td>
                        <td class="border p-2">${c.WT ?? ''}</td>
                        <td class="border p-2">${c.BP ?? ''}</td>
                        <td class="border p-2">${c.CR ?? ''}</td>
                        <td class="border p-2">${c.RR ?? ''}</td>
                        <td class="border p-2">
                            <button class="edit-consultation bg-yellow-400 px-2 py-1" data-id="${c.id}">Edit</button>
                            <button class="delete-consultation bg-red-500 text-white px-2 py-1" data-id="${c.id}">Delete</button>
                        </td>
                    </tr>
                `);
            });
        });
    }

    loadConsultations();

    // Submit consultation form (create/update)
    $form.submit(function(e){
        e.preventDefault();
        const method = editingId ? 'PUT' : 'POST';
        const url = editingId ? `/consultations/${editingId}/update` : `/patients/${patientId}/consultations/store`;

        $.ajax({
            url: url,
            type: method,
            data: $form.serialize(),
            success: function(res){
                if(res.success){
                    alert(editingId ? "Consultation updated!" : "Consultation added!");
                    $form[0].reset();
                    editingId = null;
                    $formContainer.addClass('hidden');
                    loadConsultations();
                } else {
                    alert("Error occurred!");
                }
            }
        });
    });

    // Edit consultation
    $(document).on('click','.edit-consultation', function(){
        const id = $(this).data('id');
        editingId = id;
        $.getJSON(`/consultations/${id}`, function(c){
            $('#ConsultationDate').val(c.ConsultationDate);
            $('#WT').val(c.WT ?? '');
            $('#BP').val(c.BP ?? '');
            $('#CR').val(c.CR ?? '');
            $('#RR').val(c.RR ?? '');
            $('#Temperature').val(c.Temperature ?? '');
            $('#SP02').val(c.SP02 ?? '');
            $('#History_PhysicalExam').val(c.History_PhysicalExam ?? '');
            $('#Diagnosis').val(c.Diagnosis ?? '');
            $('#Treatment').val(c.Treatment ?? '');
            $formContainer.removeClass('hidden');
        });
    });

    // Delete consultation
    $(document).on('click','.delete-consultation', function(){
        const id = $(this).data('id');
        if(confirm("Are you sure you want to delete this consultation?")){
            $.ajax({
                url: `/consultations/${id}/delete`,
                type: 'DELETE',
                success: function(res){
                    if(res.success){
                        alert('Consultation deleted!');
                        loadConsultations();
                    }
                }
            });
        }
    });

});
</script>
@endpush

@endpush