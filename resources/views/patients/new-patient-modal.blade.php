{{-- SECTION?: Modals --}}

<x-modal-garic id="patient-add" title="New Patient" maxWidth="max-w-[680px]">
  <div x-data="{
    step: 1,
    init() {
      const modal = document.getElementById('patient-add');
      new MutationObserver(() => {
        if (modal.style.display !== 'none' && !modal.classList.contains('hidden')) {
          this.step = 1;
        }
      }).observe(modal, { attributes: true, attributeFilter: ['style', 'class'] });
    },
    nextStep() {
      const section = document.getElementById('patient-step-' + this.step);
      for (const input of section.querySelectorAll('input[required], select[required]')) {
        if (!input.checkValidity()) {
          input.reportValidity();
          return;
        }
      }
      this.step++;
    },
    prevStep() {
      if (this.step > 1) this.step--;
    },
  }">

    {{-- Logo + Title --}}
    <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
      <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
      <h1 class="font-semibold text-xl">NEW PATIENT</h1>
    </div>

    {{-- Step indicator --}}
    @php
      $steps = ['Basic Information', 'Personal / Social History', 'Family History'];
    @endphp
    <div class="flex items-start justify-center mb-6">
      @foreach ($steps as $i => $label)
        @php $n = $i + 1; @endphp
        <div class="flex items-center">
          <div class="flex flex-col items-center">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all duration-200"
              :class="step >= {{ $n }}
                ? 'bg-blue-950 border-blue-950 text-white'
                : 'bg-white border-gray-300 text-gray-400'">
              <template x-if="step > {{ $n }}">
                <i class="fa-solid fa-check text-xs"></i>
              </template>
              <template x-if="step <= {{ $n }}">
                <span>{{ $n }}</span>
              </template>
            </div>
            <p class="text-[10px] mt-1 font-medium text-center w-20 leading-tight"
              :class="step >= {{ $n }} ? 'text-blue-950' : 'text-gray-400'">
              {{ $label }}
            </p>
          </div>
          @if ($i < count($steps) - 1)
            <div class="w-14 h-0.5 mb-5 mx-1 transition-colors duration-200"
              :class="step > {{ $n }} ? 'bg-blue-950' : 'bg-gray-200'">
            </div>
          @endif
        </div>
      @endforeach
    </div>

    <form id="patient_add_form" method="POST" action="{{ route("patients.store") }}">
      @csrf

      {{-- ── Step 1: Basic Information ────────────────────────────── --}}
      <div id="patient-step-1" x-show="step === 1" class="flex flex-col gap-3">


        <div class="flex gap-2">
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Last Name <span class="text-red-700">*</span></label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="last_name" type="text"
              name="last_name" placeholder="Rizal" required />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">First Name <span class="text-red-700">*</span></label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="first_name" type="text"
              name="first_name" placeholder="Jose" required />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Middle Name</label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="middle_name" type="text"
              name="middle_name" placeholder="Protacio" />
          </div>
        </div>

        {{-- DOB / Gender / Nationality --}}
        <div class="flex gap-2">
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Date of Birth <span class="text-red-700">*</span></label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="date_of_birth" type="date"
              name="date_of_birth" required />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Gender <span class="text-red-700">*</span></label>
            <select class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="gender" name="gender" required>
              <option disabled selected hidden>Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
            </select>
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Nationality <span class="text-red-700">*</span></label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="nationality" type="text"
              name="nationality" placeholder="Filipino" required />
          </div>
        </div>

        {{-- Contact / Address --}}
        <div class="flex gap-2">
          <div class="flex flex-col gap-1 w-48">
            <label class="text-xs font-medium text-blue-950">Contact Number <span class="text-red-700">*</span></label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="contact_number" type="text"
              name="contact_number" placeholder="09123456789"  maxlength="11" required />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Address <span class="text-red-700">*</span></label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="address" type="text"
              name="address" placeholder="Brgy. 5, Calamba, Laguna" required />
          </div>
        </div>

        {{-- Guardian --}}
        <div class="flex gap-2">
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Guardian Name <span class="text-red-700"></span></label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="guardian_name" type="text"
              name="guardian_name" placeholder="Teodora Alonso"  />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Relationship <span class="text-red-700"></span></label>
            <select class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="guardian_relation"
              name="guardian_relation" >
              <option disabled selected>Select Relationship</option>
              <option value="grandfather">Grandfather</option>
              <option value="grandmother">Grandmother</option>
              <option value="father">Father</option>
              <option value="mother">Mother</option>
              <option value="mother">Brother</option>
              <option value="mother">Sister</option>
              <option value="mother">Cousin</option>
              <option value="uncle">Uncle</option>
              <option value="aunt">Aunt</option>
              <option value="grandson">Grandson</option>
              <option value="granddaughter">Granddaughter</option>
              <option value="son">Son</option>
              <option value="daughter">Daughter</option>
              <option value="nephew">Nephew</option>
              <option value="niece">Niece</option>
            </select>
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Guardian Contact <span class="text-red-700"></span></label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="guardian_contact" type="text"
              name="guardian_contact" placeholder="09987654321" maxlength="11" />
          </div>
        </div>

      
      </div>

      {{-- ── Step 2: Personal / Social History ───────────────────── --}}
      <div id="patient-step-2" x-show="step === 2" class="flex flex-col gap-3">

        <div class="flex gap-2">
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Allergy / Allergies</label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="allergy" type="text"
              name="allergy" placeholder="e.g. Peanuts, Milk, Soy" />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Alcohol</label>
            <select class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="alcohol" name="alcohol">
              <option value="" disabled selected>Select Frequency</option>
              <option value="never">Never</option>
              <option value="occasional">Occasional</option>
              <option value="heavy">Heavy Drinker</option>
            </select>
          </div>
        </div>

        <div class="flex gap-2">
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Years of Smoking</label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="years_of_smoking" type="number"
              name="years_of_smoking" placeholder="0" min="0" />
          </div>
          <div class="flex flex-col gap-1 flex-1">
            <label class="text-xs font-medium text-blue-950">Illicit Drug Use</label>
            <select class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="illicit_drug_use"
              name="illicit_drug_use">
              <option value="" disabled selected>Select</option>
              <option value="none">None</option>
              <option value="coccaine">Cocaine</option>
              <option value="heroin">Heroin</option>
              <option value="methamphetamine">Methamphetamine</option>
              <option value="cannabis">Cannabis / Marijuana</option>
              <option value="mdma">MDMA / Ecstasy</option>
              <option value="lsd">LSD / Acid</option>
              <option value="psilocybin">Psilocybin / Magic Mushroom</option>
              <option value="pcpketamine">Phencyclidine & Ketamine</option>
            </select>
          </div>
        </div>

      </div>

      {{-- ── Step 3: Family History ───────────────────────────────── --}}
      <div id="patient-step-3" x-show="step === 3" class="flex flex-col gap-4">

        <p class="text-xs font-medium text-blue-950">Check all conditions that apply in the family:</p>

        <div class="grid grid-cols-3 gap-3">
          @foreach (['hypertension' => 'Hypertension', 'asthma' => 'Asthma', 'diabetes' => 'Diabetes', 'cancer' => 'Cancer', 'thyroid' => 'Thyroid'] as $name => $label)
            <label class="flex items-center gap-2 cursor-pointer p-2 border border-gray-200 rounded-md hover:bg-blue-50 transition">
              <input class="w-4 h-4 accent-blue-950" type="checkbox" id="{{ $name }}" name="{{ $name }}" />
              <span class="text-sm font-medium text-blue-950">{{ $label }}</span>
            </label>
          @endforeach
        </div>

        <div class="flex flex-col gap-1">
          <label class="text-xs font-medium text-blue-950">Others</label>
          <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="others" type="text"
            name="others" placeholder="e.g. Tuberculosis" />
        </div>

      </div>

      {{-- ── Navigation ───────────────────────────────────────────── --}}
      <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">

        {{-- Back --}}
        <button class="px-5 py-2 border-2 border-gray-300 text-gray-600 rounded-md text-sm hover:bg-gray-50 transition flex items-center gap-2"
          type="button" x-show="step > 1" @click="prevStep()">
          <i class="fa-solid fa-arrow-left fa-xs"></i> Back
        </button>
        <span x-show="step === 1"></span>

        {{-- Right side --}}
        <div class="flex gap-2">
          <button class="px-5 py-2 bg-gray-600 text-gray-100 rounded-md text-sm hover:bg-gray-500 transition"
            type="button" data-modal-close="patient-add" x-show="step === 1">
            Cancel
          </button>
          <button class="px-5 py-2 bg-blue-950 text-white rounded-md text-sm hover:bg-blue-900 transition flex items-center gap-2"
            type="button" x-show="step < 3" @click="nextStep()">
            Continue <i class="fa-solid fa-arrow-right fa-xs"></i>
          </button>
          <button class="px-5 py-2 bg-blue-950 text-white rounded-md text-sm hover:bg-blue-900 transition flex items-center gap-2"
            type="submit" x-show="step === 3">
            <i class="fa-solid fa-floppy-disk fa-xs"></i> Save
          </button>
        </div>

      </div>
    </form>

  </div>
</x-modal-garic>
