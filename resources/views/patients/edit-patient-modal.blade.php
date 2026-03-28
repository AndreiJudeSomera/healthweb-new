{{-- SECTION?: Modals --}}

<x-modal-garic id="edit-patient" title="Edit Patient" maxWidth="max-w-[900px]">
  <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
    <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
    <h1 class="font-semibold text-xl">EDIT PATIENT</h1>
  </div>
  <form id="edit_patient_form">
    @csrf
    {{-- MODAL: Form (MAIN) --}}
    <div class="w-full flex flex-col gap-2">
      {{-- MODAL: Form (ROW): Last, First, Middle names --}}
      <div class="w-full flex flex-row gap-2">
        <div class="flex flex-col gap-1 w-full" id="edit_field_last_name">
          <label class="font-medium text-blue-950" for="edit_last_name">Last Name<span
              class="text-red-800 font-bold ms-1">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_last_name" type="text"
            name="last_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Rizal Mercado y Alonso Realonda" required />
          <p class="hidden text-red-700" id="edit_error_last_name"></p>
        </div>

        <div class="flex flex-col gap-1 w-full" id="edit_field_first_name">
          <label class="font-medium text-blue-950" for="edit_first_name">First Name<span
              class="text-red-800 font-bold ms-1">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_first_name" type="text"
            name="first_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Jose" required />
          <p class="hidden text-red-700" id="edit_error_first_name"></p>
        </div>

        <div class="flex flex-col gap-1 w-full" id="edit_field_middle_name">
          <label class="font-medium text-blue-950" for="edit_middle_name">Middle Name</label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_middle_name" type="text"
            name="middle_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Protacio" />
          <p class="hidden text-red-700" id="edit_error_middle_name"></p>
        </div>
      </div>
      {{-- MODAL: Form (ROW): DOB, Gender, Nationality --}}
      <div class="w-full flex flex-row gap-2">
        <div class="flex flex-col gap-1 w-full" id="edit_field_date_of_birth">
          <label class="font-medium text-blue-950" for="edit_date_of_birth">Date of Birth<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_date_of_birth" type="date"
            name="date_of_birth" placeholder="Protacio" required />
          <p class="hidden text-red-700" id="edit_error_date_of_birth"></p>
        </div>

        <div class="flex flex-col gap-1 w-full" id="edit_field_gender">
          <label class="font-medium text-blue-950" for="edit_gender">Gender<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_gender" name="gender" required>
            <option disabled selected hidden>Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
          <p class="hidden text-red-700" id="edit_error_gender"></p>
        </div>

        <div class="flex flex-col gap-1 w-full" id="edit_field_nationality">
          <label class="font-medium text-blue-950" for="edit_nationality">Nationality<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_nationality" type="nationality"
            name="nationality" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Filipino" required />
          <p class="hidden text-red-700" id="edit_error_nationality"></p>
        </div>
      </div>
      {{-- MODAL: Form (ROW): Contact#, Address --}}
      <div class="w-full flex flex-row gap-2">
        <div class="flex flex-col gap-1 w-full max-w-[320px]" id="edit_field_contact_number">
          <label class="font-medium text-blue-950" for="edit_contact_number">Contact Number<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_contact_number" type="text"
            name="contact_number" placeholder="0912 345 6789" required />
          <p class="hidden text-red-700" id="edit_error_contact_number"></p>
        </div>
        <div class="flex flex-col gap-1 w-full" id="edit_field_address">
          <label class="font-medium text-blue-950" for="edit_address">Address<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_address" type="text"
            name="address" placeholder="Francisco Mercado St., Brgy. 5, Poblacion, Calamba, Laguna" required />
          <p class="hidden text-red-700" id="edit_error_address"></p>
        </div>
      </div>
      {{-- MODAL: Form (ROW): Guardian Name, Relation, Address --}}
      <div class="w-full flex flex-row gap-2">
        <div class="flex flex-col gap-1 w-full" id="edit_field_guardian_name">
          <label class="font-medium text-blue-950" for="edit_guardian_name">Guardian Name<span
              class="font-bold text-red-800 ms-1"></span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_guardian_name" type="text"
            name="guardian_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Teodora Alonso Realonda"  />
          <p class="hidden text-red-700" id="edit_error_guardian_name"></p>
        </div>
        <div class="flex flex-col gap-1 w-full" id="edit_field_guardian_relation">
          <label class="font-medium text-blue-950" for="edit_guardian_relation">Relationship<span
              class="font-bold text-red-800 ms-1"></span></label>
          <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_guardian_relation"
            name="guardian_relation" >
            <option disabled selected hidden>Select Guardian Relationship</option>
            <option value="grandfather">Grandfather</option>
            <option value="grandmother">Grandmother</option>
            <option value="father">Father</option>
            <option value="mother">Mother</option>
            <option value="uncle">Uncle</option>
            <option value="aunt">Aunt</option>
            <option value="grandson">Grandson</option>
            <option value="granddauther">Granddaughter</option>
            <option value="son">Son</option>
            <option value="daughter">Daughter</option>
            <option value="nephew">Nephew</option>
            <option value="niece">Niece</option>
          </select>
          <p class="hidden text-red-700" id="edit_error_guardian_relation"></p>
        </div>
        <div class="flex flex-col gap-1 w-full" id="edit_field_guardian_contact">
          <label class="font-medium text-blue-950" for="edit_guardian_contact">Contact Number<span
              class="font-bold text-red-800 ms-1"></span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_guardian_contact"
            type="text" name="guardian_contact" placeholder="0998 765 4321"  />
          <p class="hidden text-red-700" id="edit_error_guardian_contact"></p>
        </div>
      </div>

      {{-- MODAL: Form (ROW): Allergy, Alcohol, Years of Smoking, Illicit Drug Use --}}
      <div class="w-full flex flex-row gap-2">
        <div class="flex flex-col gap-1 w-full" id="edit_field_allergy">
          <label class="font-medium text-blue-950" for="edit_allergy">Allergy/Allergies<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_allergy" type="text"
            name="allergy" pattern="^[A-Za-z\s'.\-,]{2,100}$" placeholder="e.g. Peanuts, Milk, Soy"  />
          <p class="hidden text-red-700" id="edit_error_allergy"></p>
        </div>
        <div class="flex flex-col gap-1 w-full" id="edit_field_alcohol">
          <label class="font-medium text-blue-950" for="edit_alcohol">Alcohol<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_alcohol" name="alcohol"
            >
            <option disabled selected hidden>Select Frequency</option>
            <option value="never">Never</option>
            <option value="occasional">Occasional</option>
            <option value="heavy">Heavy Drinker</option>
          </select>
          <p class="hidden text-red-700" id="edit_error_alcohol"></p>
        </div>
        <div class="flex flex-col gap-1 w-full" id="edit_field_years_of_smoking">
          <label class="font-medium text-blue-950" for="edit_years_of_smoking">Years of Smoking<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_years_of_smoking"
            type="number" name="years_of_smoking" placeholder="0"  />
          <p class="hidden text-red-700" id="edit_error_years_of_smoking"></p>
        </div>
        <div class="flex flex-col gap-1 w-full" id="edit_field_illicit_drug_use">
          <label class="font-medium text-blue-950" for="edit_allergy">Illicit Drug Use<span
              class="font-bold text-red-800 ms-1">*</span></label>
          <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_illicit_drug_use"
            name="illicit_drug_use" >
            <option disabled selected hidden>Select Illicit Drugs</option>
            <option value="none">None</option>
            <option value="coccaine">Coccaine</option>
            <option value="heroin">Heroin</option>
            <option value="methamphetamine">Methamphetamine</option>
            <option value="cannabis">Cannabis/Marijuana</option>
            <option value="mdma">MDMA/Ecstasy</option>
            <option value="lsd">LSD/Ecstasy</option>
            <option value="psilocybin">Psilocybin/Magic Mushroom</option>
            <option value="pcpketamine">Phencyclidine & Ketamine</option>
          </select>
          <p class="hidden text-red-700" id="illicit_drug_use"></p>
        </div>
      </div>

      {{-- MODAL: Separator (ROW): Instruction for edit_field below --}}
      <div class="w-full flex flex-row my-1">
        <p class="font-medium text-blue-950">Please check which apply:</p>
      </div>

      {{-- MODAL: Form (ROW): Hypertension, Asthma, Diabetes, Cancer, Thyroid, Others --}}
      <div class="w-full flex flex-row gap-6">
        <div>
          <input class="border-2 border-blue-950 rounded" id="edit_hypertension" type="checkbox"
            name="hypertension" />
          <label class="font-medium text-blue-950" for="edit_hypertension">Hypertension</label>
        </div>

        <div>
          <input class="border-2 border-blue-950 rounded" id="edit_asthma" type="checkbox" name="asthma" />
          <label class="font-medium text-blue-950" for="edit_asthma">Asthma</label>
        </div>

        <div>
          <input class="border-2 border-blue-950 rounded" id="edit_diabetes" type="checkbox" name="diabetes" />
          <label class="font-medium text-blue-950" for="edit_diabetes">Diabetes</label>
        </div>

        <div>
          <input class="border-2 border-blue-950 rounded" id="edit_cancer" type="checkbox" name="cancer" />
          <label class="font-medium text-blue-950" for="edit_cancer">Cancer</label>
        </div>

        <div>
          <input class="border-2 border-blue-950 rounded" id="edit_thyroid" type="checkbox" name="thyroid" />
          <label class="font-medium text-blue-950" for="edit_thyroid">Thyroid</label>
        </div>
      </div>
    </div>

    {{-- MODAL: Form (ROW): Others --}}
    <div class="w-full mt-2 flex flex-col gap-1 flex-2 align-center justify-center" id="edit_field_others">
      <label class="font-medium text-blue-950" for="edit_others">Others:</label>
      <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="edit_others" type="text"
        name="others" placeholder="e.g. Tuberculosis" />
      <p class="hidden text-red-700" id="edit_error_others"></p>
    </div>

    {{-- MODAL: FOOTER (CANCEL/SAVE) --}}
    <div class="flex justify-end gap-2 mt-4">
      <button class="px-6 py-2 border-2 bg-gray-600 text-gray-100 rounded-md" id="editModalCloseButton"
        data-modal-close="edit-patient" type="button">
        <span class="text-sm">
          Discard Changes
        </span>
      </button>
      <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md" type="submit">
        <i class="fa-solid fa-floppy-disk me-1 fa-sm"></i>
        <span class="text-sm">
          Save Changes
        </span>
      </button>
    </div>
  </form>
</x-modal-garic>
