@extends("layouts.app")
@section("content")
  <div class="w-full flex flex-col gap-4">
    <div class="w-full flex flex-wrap items-center justify-between gap-3">
      {{-- Group: Table Controls --}}
      <div class="w-full md:w-auto flex flex-row gap-2 items-center">
        {{-- Field: Search --}}
        <div class="relative flex-1 min-w-[260px] md:min-w-[320px]">
          <input
            class="w-full rounded-md text-sm p-2 ps-10 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent"
            id="searchPatient" type="text" name="searchPatient" placeholder="Search patients ..." />
          <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
          </svg>
        </div>
        {{-- Group: Buttons --}}
        <div class="flex flex-row gap-2">
          {{-- Button: Filter --}}
          <button class="bg-gray-200 hover:bg-gray-300 py-2 px-4 rounded-md text-blue-950" id="filterButton"
            name="filterButton">
            <div class="flex flex-row gap-2 items-center">
              <i class="fa-solid fa-filter fa-xs"></i>
              <p class="text-sm font-medium">Filter</p>
            </div>
          </button>
        </div>
      </div>
      {{-- Group: Buttons --}}
      <div class="flex flex-row gap-2">
        {{-- Button: New Patient --}}
        <button class="bg-gray-600 hover:bg-gray-500 py-2 px-4 rounded-md text-blue-100" id="bindPatientButton"
          name="bindPatientButton">
          <div class="flex flex-row gap-2 items-center">
            <i class="fa-solid fa-link fa-xs"></i>
            <p class="text-sm font-medium">Bind Old Patient</p>
          </div>
        </button>
        <button class="bg-blue-950 hover:bg-blue-900 py-2 px-4 rounded-md text-blue-100" id="newPatientButton"
          data-modal-open="patient-add" name="newPatientButton">
          <div class="flex flex-row gap-2 items-center">
            <i class="fa-solid fa-plus fa-xs"></i>
            <p class="text-sm font-medium">New Patient</p>
          </div>
        </button>
      </div>
    </div>
    {{-- Table: Patient Table --}}
    <div class="w-full flex flex-col gap-4" id="" name="">
      <table class="w-full overflow-hidden table-auto bg-gray-100 rounded-md" id="patientsTableEl">
        <thead class="bg-blue-950 text-blue-100">
          <tr class="">
            <th class="text-start px-4 py-3 font-medium">Last Name</th>
            <th class="text-start px-4 py-3 font-medium">First Name</th>
            <th class="text-start px-4 py-3 font-medium">Age</th>
            <th class="text-start px-4 py-3 font-medium">Sex</th>
            <th class="text-start px-4 py-3 font-medium">Date Added</th>
            <th class="text-center px-4 py-3 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody class="text-gray-700" id="patientsTbody">
        </tbody>
      </table>
      {{-- Table Section: Pagination --}}
      <div class="flex flex-row justify-between items-start">
        {{-- Pagination -> Row Counter --}}
        <div class="flex flex-row gap-2 items-center">
          <p class="text-sm text-gray-700">Rows:</p>
          <select
            class="border border-blue-950 rounded-md ps-2 pe-10 text-xs focus:outline-none focus:ring-2 focus:ring-blue-900"
            id="rowCount" name="rowCount">
            <option value="5">5</option>
            <option value="10" selected>1</option>
            <option value="15">15</option>
            <option value="20">20</option>
          </select>
        </div>
        {{-- Pagination -> Selector --}}
        <div class="flex flex-row gap-2">
          <button class= "bg-blue-950 text-blue-100 hover:bg-blue-900 rounded-md size-7">
            <i class="fa-solid fa-angles-left fa-xs"></i>
          </button>
          <button class= "bg-blue-950 text-blue-100 hover:bg-blue-900 rounded-md size-7">
            <i class="fa-solid fa-angle-left fa-xs"></i>
          </button>
          <button class="border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-7">
            <p class="text-sm">1</p>
          </button>
          <button class= "bg-blue-950 text-blue-100 hover:bg-blue-900 rounded-md size-7">
            <i class="fa-solid fa-angle-right fa-xs"></i>
          </button>
          <button class= "bg-blue-950 text-blue-100 hover:bg-blue-900 rounded-md size-7">
            <i class="fa-solid fa-angles-right fa-xs"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  {{-- SECTION?: Modals --}}

  <x-modal-garic id="patient-add" title="New Patient">
    <form id="patient_add_form" method="POST" action="{{ route("patients.store") }}">
      @csrf
      {{-- MODAL: Form (MAIN) --}}
      <div class="w-full flex flex-col gap-2">
        {{-- MODAL: Form (ROW): Last, First, Middle names --}}
        <div class="w-full flex flex-row gap-2">
          <div class="flex flex-col gap-1 w-full" id="field_last_name">
            <label class="font-medium text-blue-950" for="last_name">Last Name<span
                class="text-red-800 font-bold ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="last_name" type="text"
              name="last_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Rizal Mercado y Alonso Realonda" required />
            <p class="hidden text-red-700" id="error_last_name"></p>
          </div>

          <div class="flex flex-col gap-1 w-full" id="field_first_name">
            <label class="font-medium text-blue-950" for="first_name">First Name<span
                class="text-red-800 font-bold ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="first_name" type="text"
              name="first_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Jose" required />
            <p class="hidden text-red-700" id="error_first_name"></p>
          </div>

          <div class="flex flex-col gap-1 w-full" id="field_middle_name">
            <label class="font-medium text-blue-950" for="middle_name">Middle Name</label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="middle_name" type="text"
              name="middle_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Protacio" />
            <p class="hidden text-red-700" id="error_middle_name"></p>
          </div>
        </div>
        {{-- MODAL: Form (ROW): DOB, Gender, Nationality --}}
        <div class="w-full flex flex-row gap-2">
          <div class="flex flex-col gap-1 w-full" id="field_date_of_birth">
            <label class="font-medium text-blue-950" for="date_of_birth">Date of Birth<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="date_of_birth" type="date"
              name="date_of_birth" placeholder="Protacio" required />
            <p class="hidden text-red-700" id="error_date_of_birth"></p>
          </div>

          <div class="flex flex-col gap-1 w-full" id="field_gender">
            <label class="font-medium text-blue-950" for="gender">Gender<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="gender" name="gender"
              required>
              <option disabled selected hidden>Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
            <p class="hidden text-red-700" id="error_gender"></p>
          </div>

          <div class="flex flex-col gap-1 w-full" id="field_nationality">
            <label class="font-medium text-blue-950" for="nationality">Nationality<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="nationality" type="nationality"
              name="nationality" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Filipino" required />
            <p class="hidden text-red-700" id="error_nationality"></p>
          </div>
        </div>
        {{-- MODAL: Form (ROW): Contact#, Address --}}
        <div class="w-full flex flex-row gap-2">
          <div class="flex flex-col gap-1 w-full max-w-[320px]" id="field_contact_number">
            <label class="font-medium text-blue-950" for="contact_number">Contact Number<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="contact_number" type="text"
              name="contact_number" placeholder="0912 345 6789" required />
            <p class="hidden text-red-700" id="error_contact_number"></p>
          </div>
          <div class="flex flex-col gap-1 w-full" id="field_address">
            <label class="font-medium text-blue-950" for="address">Address<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="address" type="text"
              name="address" placeholder="Francisco Mercado St., Brgy. 5, Poblacion, Calamba, Laguna" required />
            <p class="hidden text-red-700" id="error_address"></p>
          </div>
        </div>
        {{-- MODAL: Form (ROW): Guardian Name, Relation, Address --}}
        <div class="w-full flex flex-row gap-2">
          <div class="flex flex-col gap-1 w-full" id="field_guardian_name">
            <label class="font-medium text-blue-950" for="guardian_name">Guardian Name<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="guardian_name" type="text"
              name="guardian_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Teodora Alonso Realonda" required />
            <p class="hidden text-red-700" id="error_guardian_name"></p>
          </div>
          <div class="flex flex-col gap-1 w-full" id="field_guardian_relation">
            <label class="font-medium text-blue-950" for="guardian_relation">Relationship<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="guardian_relation"
              name="guardian_relation" required>
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
            <p class="hidden text-red-700" id="error_guardian_relation"></p>
          </div>
          <div class="flex flex-col gap-1 w-full" id="field_guardian_contact">
            <label class="font-medium text-blue-950" for="guardian_contact">Contact Number<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="guardian_contact" type="text"
              name="guardian_contact" placeholder="0998 765 4321" required />
            <p class="hidden text-red-700" id="error_guardian_contact"></p>
          </div>
        </div>

        {{-- MODAL: Form (ROW): Allergy, Alcohol, Years of Smoking, Illicit Drug Use --}}
        <div class="w-full flex flex-row gap-2">
          <div class="flex flex-col gap-1 w-full" id="field_allergy">
            <label class="font-medium text-blue-950" for="allergy">Allergy/Allergies<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="allergy" type="text"
              name="allergy" pattern="^[A-Za-z\s'.\-,]{2,100}$" placeholder="Peanuts, Milk, Soy" required />
            <p class="hidden text-red-700" id="error_allergy"></p>
          </div>
          <div class="flex flex-col gap-1 w-full" id="field_alcohol">
            <label class="font-medium text-blue-950" for="alcohol">Alcohol<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="alcohol" name="alcohol"
              required>
              <option disabled selected hidden>Select Frequency</option>
              <option value="never">Never</option>
              <option value="occasional">Occasional</option>
              <option value="heavy">Heavy Drinker</option>
            </select>
            <p class="hidden text-red-700" id="error_alcohol"></p>
          </div>
          <div class="flex flex-col gap-1 w-full" id="field_years_of_smoking">
            <label class="font-medium text-blue-950" for="years_of_smoking">Years of Smoking<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="years_of_smoking" type="number"
              name="years_of_smoking" placeholder="0" required />
            <p class="hidden text-red-700" id="error_years_of_smoking"></p>
          </div>
          <div class="flex flex-col gap-1 w-full" id="field_illicit_drug_use">
            <label class="font-medium text-blue-950" for="allergy">Illicit Drug Use<span
                class="font-bold text-red-800 ms-1">*</span></label>
            <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="illicit_drug_use"
              name="illicit_drug_use" required>
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

        {{-- MODAL: Separator (ROW): Instruction for field below --}}
        <div class="w-full flex flex-row my-1">
          <p class="font-medium text-blue-950">Please check which apply:</p>
        </div>

        {{-- MODAL: Form (ROW): Hypertension, Asthma, Diabetes, Cancer, Thyroid, Others --}}
        <div class="w-full flex flex-row gap-6">
          <div>
            <input class="border-2 border-blue-950 rounded" id="hypertension" type="checkbox" name="hypertension" />
            <label class="font-medium text-blue-950" for="hypertension">Hypertension</label>
          </div>

          <div>
            <input class="border-2 border-blue-950 rounded" id="asthma" type="checkbox" name="asthma" />
            <label class="font-medium text-blue-950" for="asthma">Asthma</label>
          </div>

          <div>
            <input class="border-2 border-blue-950 rounded" id="diabetes" type="checkbox" name="diabetes" />
            <label class="font-medium text-blue-950" for="diabetes">Diabetes</label>
          </div>

          <div>
            <input class="border-2 border-blue-950 rounded" id="cancer" type="checkbox" name="cancer" />
            <label class="font-medium text-blue-950" for="cancer">Cancer</label>
          </div>

          <div>
            <input class="border-2 border-blue-950 rounded" id="thyroid" type="checkbox" name="thyroid" />
            <label class="font-medium text-blue-950" for="thyroid">Thyroid</label>
          </div>
        </div>
      </div>

      {{-- MODAL: Form (ROW): Others --}}
      <div class="w-full mt-2 flex flex-col gap-1 flex-2 align-center justify-center" id="field_others">
        <label class="font-medium text-blue-950" for="others">Others:</label>
        <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2" id="others" type="text"
          name="others" placeholder="Tuberculosis" />
        <p class="hidden text-red-700" id="error_others"></p>
      </div>

      {{-- MODAL: FOOTER (CANCEL/SAVE) --}}
      <div class="flex justify-end gap-2 mt-4">
        <button class="px-6 py-2 border-2 bg-gray-600 text-gray-100 rounded-md" data-modal-close="patient-add"
          type="button">
          <span class="text-sm">
            Cancel
          </span>
        </button>
        <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md" type="submit">
          <i class="fa-solid fa-floppy-disk me-1 fa-sm"></i>
          <span class="text-sm">
            Save
          </span>
        </button>
      </div>
    </form>
  </x-modal-garic>
@endsection
@push("scripts")
  @vite(["resources/js/pages/patients-index.js"])
  <script>
    const form = document.getElementById("patient_add_form");
    if (!form) console.warn("patiend_add_form not found");

    document.addEventListener("DOMContentLoaded", () => {
      console.log("LOADING");
      form.addEventListener("submit", (e) => {

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        form.querySelectorAll("input[type='checkbox']").forEach(cb => {
          if (!formData.has(cb.name)) {
            data[cb.name] = false;
          } else {
            // checked checkboxes come as "on" by default
            data[cb.name] = true;
          }
        });

        console.log(data);
      });
    })
  </script>
@endpush
