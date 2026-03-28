document.addEventListener("DOMContentLoaded", function() {
    //const patientId = "{{ $patient->PatientRecord_ID }}";
    const patientId = window.patientId;

    let patientData = {};
    let isEditingBasic = false;
    let isEditingHistory = false;

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // -------------------
    // Load Patient Info
    // -------------------
  function loadPatientInfo() {
    fetch(`/patients/${patientId}/data`)
    .then(res => res.json())
    .then(data => {
        patientData = data;
        const birthdateInputFormat = data.DateofBirth ? new Date(data.DateofBirth).toISOString().slice(0,10) : '';

        // Basic Info Display (unchanged)
        const basic = `
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
        document.getElementById('basic-info').innerHTML = basic;
        document.getElementById("pid-label").textContent = data.PID_Number ?? "N/A";

        // -------------------
        // Personal History Display
        // -------------------
        const personal = `
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div><div class="text-[11px] text-[#1f2b5b]">Allergy</div><div class="text-black text-[13px] font-medium uppercase">${data.Allergy || 'None'}</div></div>
                <div><div class="text-[11px] text-[#1f2b5b]">Alcohol</div><div class="text-black text-[13px] font-medium uppercase">${data.Alcohol || 'None'}</div></div>
                <div><div class="text-[11px] text-[#1f2b5b]">Smoking</div><div class="text-black text-[13px] font-medium uppercase">${data.Years_of_Smoking || '0.0 pack years'}</div></div>
                <div><div class="text-[11px] text-[#1f2b5b]">Illicit Drug Use</div><div class="text-black text-[13px] font-medium uppercase">${data.IllicitDrugUse || 'None'}</div></div>
            </div>
        `;
        document.getElementById('personal-history').innerHTML = personal;

        // -------------------
        // Family History Display
        // -------------------
        const familyHistoryKeys = ['Hypertension','Asthma','Diabetes','Cancer','Thyroid'];
        const fh = familyHistoryKeys.map(f => `
            <div>
                <div class="text-[11px] text-[#1f2b5b]">${f}</div>
                <div class="mt-1">
                    <img src="${data[f] ? '{{ asset("assets/images/icons/check.png") }}' : '{{ asset("assets/images/icons/x.png") }}'}" class="w-5 h-5 mx-auto">
                </div>
            </div>
        `).join('') + `
        <div>
            <div class="text-[11px] text-[#1f2b5b]">Others</div>
            <div class="text-black text-[13px] font-medium mt-1">${data.Others || 'NONE'}</div>
        </div>`;
        document.getElementById('family-history').innerHTML = fh;
    })
    .catch(err => console.error(err));
}

    // -------------------
    // Toggle Basic Info Edit
    // -------------------
    function toggleEditBasic(edit = true) {
        const btn = document.getElementById("edit-basic-info-btn").querySelector("img");
        if(edit){
            isEditingBasic = true;
            btn.src = "{{ asset('assets/images/icons/cancel.png') }}";
            btn.alt = "cancel";

            const data = patientData;
            const birthdateInputFormat = data.DateofBirth ? new Date(data.DateofBirth).toISOString().slice(0,10) : '';
            const container = document.getElementById("basic-info");

            container.innerHTML = `
                <form id="basic-info-form" class="space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div><label class="text-[11px] text-[#1f2b5b]">Last Name</label><input type="text" name="Lname" value="${data.Lname}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">First Name</label><input type="text" name="Fname" value="${data.Fname}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Middle Name</label><input type="text" name="Mname" value="${data.Mname || ''}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div><label class="text-[11px] text-[#1f2b5b]">Age</label><input type="number" name="Age" value="${data.Age}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Sex</label>
                            <select name="Gender" class="w-full border px-2 py-1 rounded text-[13px]">
                                <option ${data.Gender=='Male'?'selected':''}>Male</option>
                                <option ${data.Gender=='Female'?'selected':''}>Female</option>
                            </select>
                        </div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Birthdate</label><input type="date" name="DateofBirth" value="${birthdateInputFormat}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div><label class="text-[11px] text-[#1f2b5b]">Nationality</label><input type="text" name="Nationality" value="${data.Nationality}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Contact No.</label><input type="text" name="ContactNumber" value="${data.ContactNumber}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Address</label><input type="text" name="Address" value="${data.Address}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                    </div>
                    ${data.Age < 18 ? `
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        <div><label class="text-[11px] text-[#1f2b5b]">Guardian Name</label><input type="text" name="GuardianName" value="${data.GuardianName}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Guardian Relationship</label><input type="text" name="GuardianRelation" value="${data.GuardianRelation}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Guardian Contact</label><input type="text" name="GuardianContact" value="${data.GuardianContact}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                    </div>` : ``}
                </form>
            `;

            document.getElementById("save-basic-info-btn").onclick = () => {
                const form = document.getElementById("basic-info-form");
                const formData = new FormData(form);
                formData.append('_method','PUT');

                fetch(`/patients/${patientId}/update-basic`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': csrfToken},
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    if(res.success){
                        alert("✅ Basic info updated!");
                        isEditingBasic = false;
                        btn.src = "{{ asset('assets/images/icons/editrec.png') }}";
                        btn.alt = "edit";
                        loadPatientInfo();
                    } else {
                        alert("❌ Failed to update");
                    }
                })
                .catch(err => console.error(err));
            };

        } else {
            isEditingBasic = false;
            btn.src = "{{ asset('assets/images/icons/editrec.png') }}";
            btn.alt = "edit";
            loadPatientInfo();
        }
    }

    document.getElementById("edit-basic-info-btn").addEventListener("click", () => toggleEditBasic(!isEditingBasic));

    // -------------------
    // Toggle History Edit
    // -------------------
    function toggleEditHistory(edit = true){
        const btn = document.getElementById("edit-history-btn").querySelector("img");
        const container = document.getElementById("personal-history");
        const familyContainer = document.getElementById("family-history");

        if(edit){
            isEditingHistory = true;
            btn.src = "{{ asset('assets/images/icons/cancel.png') }}";
            btn.alt = "cancel";

            const data = patientData;

            container.innerHTML = `
                <form id="history-form" class="space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div><label class="text-[11px] text-[#1f2b5b]">Allergy</label><input type="text" name="Allergy" value="${data.Allergy || ''}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Alcohol</label><input type="text" name="Alcohol" value="${data.Alcohol || ''}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Smoking</label><input type="text" name="Years_of_Smoking" value="${data.Years_of_Smoking || ''}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                        <div><label class="text-[11px] text-[#1f2b5b]">Illicit Drug Use</label><input type="text" name="IllicitDrugUse" value="${data.IllicitDrugUse || ''}" class="w-full border px-2 py-1 rounded text-[13px]"></div>
                    </div>
                    <div class="grid grid-cols-6 gap-2 text-center mt-2">
                        ${['Hypertension','Asthma','Diabetes','Cancer','Thyroid'].map(f => `
                            <div>
                                <label class="text-[11px] text-[#1f2b5b]">${f}</label>
                                <input type="checkbox" name="${f}" ${data[f] ? 'checked' : ''} class="mt-1">
                            </div>
                        `).join('')}
                    </div>
                    <div class="mt-2">
                        <label class="text-[11px] text-[#1f2b5b]">Others</label>
                        <input type="text" name="Others" value="${data.Others || ''}" class="w-full border px-2 py-1 rounded text-[13px]">
                    </div>
                </form>
            `;

            document.getElementById("save-history-btn").onclick = () => {
                const form = document.getElementById("history-form");
                const formData = new FormData(form);
                formData.append('_method','PUT');

                fetch(`/patients/${patientId}/update-history`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': csrfToken},
                    body: formData
                })
                .then(res => res.json())
                .then(res => {
                    alert("✅ Basic info updated!");
                    isEditingHistory = false;
                    btn.src = "{{ asset('assets/images/icons/editrec.png') }}";
                    btn.alt = "edit";
                    loadPatientInfo();
                })
                .catch(err => console.error(err));
            };

        } else {
            isEditingHistory = false;
            btn.src = "{{ asset('assets/images/icons/editrec.png') }}";
            btn.alt = "edit";
            loadPatientInfo();
        }
    }

    document.getElementById("edit-history-btn").addEventListener("click", () => toggleEditHistory(!isEditingHistory));

    // Initial load
    loadPatientInfo();
});
