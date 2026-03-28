@extends("mobilelayouts.app")
@section("content")
  <div class="h-full w-full max-w-[530px] min-w-[200px] flex-none mx-auto px-2 pb-6"
    x-data="{
      step: 1,
      nextStep() {
        const section = document.getElementById('ob-step-' + this.step);
        for (const input of section.querySelectorAll('input[required], select[required]')) {
          if (!input.checkValidity()) {
            input.reportValidity();
            return;
          }
        }
        this.step++;
        window.scrollTo({ top: 0, behavior: 'smooth' });
      },
      prevStep() {
        if (this.step > 1) {
          this.step--;
          window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      },
    }">

    {{-- Header --}}
    <div class="mb-5">
      <h1 class="text-blue-950 font-bold text-2xl">Create new record</h1>
      <p class="text-blue-950/50 text-sm">Please fill out the form below</p>
    </div>

    {{-- Step indicator --}}
    @php $steps = ['Basic Information', 'Personal / Social History', 'Family History']; @endphp
    <div class="flex items-start mb-6">
      @foreach ($steps as $i => $label)
        @php $n = $i + 1; @endphp

        {{-- Connector (between steps) --}}
        @if ($i > 0)
          <div class="flex-1 h-0.5 mx-1 mt-4 transition-colors duration-200"
            :class="step > {{ $n - 1 }} ? 'bg-blue-950' : 'bg-gray-200'">
          </div>
        @endif

        {{-- Step circle + label --}}
        <div class="flex flex-col items-center flex-shrink-0">
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
          <p class="text-[9px] mt-1 font-medium text-center w-16 leading-tight"
            :class="step >= {{ $n }} ? 'text-blue-950' : 'text-gray-400'">
            {{ $label }}
          </p>
        </div>

      @endforeach
    </div>

    <form id="patient_add_form" method="POST" action="/p/createrecord">
      @csrf

      {{-- ── Step 1: Basic Information ──────────────────────────── --}}
      <div id="ob-step-1" x-show="step === 1" class="flex flex-col gap-3">

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Last Name <span class="text-red-800 font-bold">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="last_name" type="text"
            name="last_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Mercado" required />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">First Name <span class="text-red-800 font-bold">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="first_name" type="text"
            name="first_name" pattern="^[A-Za-zñÑ\s'.\-]{2,100}$"  placeholder="Jose" required />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Middle Name</label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="middle_name" type="text"
            name="middle_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Protacio" />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Date of Birth <span class="text-red-800 font-bold">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="date_of_birth" type="date"
            name="date_of_birth" required />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Gender <span class="text-red-800 font-bold">*</span></label>
          <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="gender" name="gender" required>
            <option disabled selected hidden>Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Nationality <span class="text-red-800 font-bold">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="nationality" type="text"
            name="nationality" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Filipino" required />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Contact Number <span class="text-red-800 font-bold">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="contact_number" type="text"
            name="contact_number" placeholder="09123456789" maxlength="11" required />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Address <span class="text-red-800 font-bold">*</span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="address" type="text"
            name="address" placeholder="Francisco Mercado St., Brgy. 5, Calamba, Laguna" required />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Guardian Name <span class="text-red-800 font-bold"></span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="guardian_name" type="text"
            name="guardian_name" pattern="^[A-Za-z\s'.\-]{2,100}$" placeholder="Teodora Alonso Realonda" />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Relationship <span class="text-red-800 font-bold"></span></label>
          <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="guardian_relation"
            name="guardian_relation" >
            <option disabled selected>Select Guardian Relationship</option>
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

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Guardian Contact Number <span class="text-red-800 font-bold"></span></label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="guardian_contact" type="text"
            name="guardian_contact" placeholder="09123456789" maxlength="11" />
        </div>

      </div>

      {{-- ── Step 2: Personal / Social History ──────────────────── --}}
      <div id="ob-step-2" x-show="step === 2" class="flex flex-col gap-3">

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Allergy / Allergies</label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="allergy" type="text"
            name="allergy" placeholder="e.g. Peanuts, Milk, Soy or None" />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Alcohol</label>
          <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="alcohol" name="alcohol">
            <option value="" disabled selected>Select Frequency</option>
            <option value="never">Never</option>
            <option value="occasional">Occasional</option>
            <option value="heavy">Heavy Drinker</option>
          </select>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Years of Smoking</label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="years_of_smoking" type="number"
            name="years_of_smoking" placeholder="0" min="0" />
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Illicit Drug Use</label>
          <select class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="illicit_drug_use"
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

      {{-- ── Step 3: Family History ───────────────────────────────── --}}
      <div id="ob-step-3" x-show="step === 3" class="flex flex-col gap-4">

        <p class="font-medium text-blue-950 text-sm">Check all conditions that apply in the family:</p>

        <div class="flex flex-col gap-3">
          @foreach (['hypertension' => 'Hypertension', 'asthma' => 'Asthma', 'diabetes' => 'Diabetes', 'cancer' => 'Cancer', 'thyroid' => 'Thyroid'] as $name => $label)
            <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-md hover:bg-blue-50 transition cursor-pointer">
              <input class="w-5 h-5 accent-blue-950" type="checkbox" id="{{ $name }}" name="{{ $name }}" />
              <span class="font-medium text-blue-950">{{ $label }}</span>
            </label>
          @endforeach
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-medium text-blue-950 text-sm">Others</label>
          <input class="w-full border-2 border-blue-950 rounded-md px-3 py-2 text-sm" id="others" type="text"
            name="others" placeholder="e.g. Tuberculosis" />
        </div>

      </div>

      {{-- ── Navigation ───────────────────────────────────────────── --}}
      <div class="flex flex-col gap-2 mt-8 pb-8">

        {{-- Continue / Save --}}
        
        {{-- Cancel (step 1 only) --}}
        <a class="w-full px-6 py-4 border-2 flex justify-center font-medium bg-gray-600 hover:bg-gray-500 text-gray-100 rounded-md"
          href="{{ route('patient.onboarding.usertype') }}" x-show="step === 1">
          Cancel
        </a>
        <button
          class="w-full px-6 py-4 bg-blue-950 hover:bg-blue-900 text-blue-100 rounded-md font-medium flex justify-center items-center gap-2"
          type="button" x-show="step < 3" @click="nextStep()">
          Continue <i class="fa-solid fa-arrow-right fa-sm"></i>
        </button>
        <button
          class="w-full px-6 py-4 bg-blue-950 hover:bg-blue-900 text-blue-100 rounded-md font-medium flex justify-center items-center gap-2"
          type="submit" x-show="step === 3">
          <i class="fa-solid fa-floppy-disk fa-sm"></i> Save
        </button>

        {{-- Back --}}
        <button
          class="w-full px-6 py-4 border-2 border-blue-950 text-blue-950 hover:bg-blue-50 rounded-md font-medium flex justify-center items-center gap-2"
          type="button" x-show="step > 1" @click="prevStep()">
          <i class="fa-solid fa-arrow-left fa-sm"></i> Back
        </button>

        

      </div>
    </form>
  </div>
@endsection
