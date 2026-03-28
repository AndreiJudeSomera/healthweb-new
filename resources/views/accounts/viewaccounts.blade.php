@extends("layouts.app")
@section("content")
  <div class="flex flex-col">
    <div class="w-full flex justify-start items-center gap-3 flex-wrap">
      {{-- Search --}}
      <div class="relative flex-1 min-w-[260px] md:min-w-[320px] max-w-[320px]">
        <input
          class="w-full rounded-md text-sm p-2 ps-10 border border-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-transparent"
          id="patientSearch" type="text" name="patientSearch" placeholder="Search accounts ..." />
        <svg class="absolute left-3 top-2.5 h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
        </svg>
      </div>

      {{-- Filter Button --}}
      <button class="bg-gray-200 hover:bg-gray-200/90 py-2 px-4 rounded-md text-gray-700"
        data-modal-open="account-role-filter" type="button">
        <div class="flex flex-row gap-2 items-center">
          <i class="fa-solid fa-filter fa-xs"></i>
          <p class="text-sm font-medium">Filter</p>
        </div>
      </button>

      {{-- Add User --}}
      <div class="flex gap-2 items-center ms-auto">
        <button class="bg-blue-950 hover:bg-blue-950/90 py-2 px-4 rounded-md text-blue-100"
          data-modal-open="account-create" type="button">
          <div class="flex flex-row gap-2 items-center">
            <i class="fa-solid fa-plus fa-xs"></i>
            <p class="text-sm font-medium">Add User</p>
          </div>
        </button>
      </div>
    </div>

    <div class="w-full [&_tr]:border-x [&_tr]:border-gray-200 mt-3">
      <table class="w-full text-blue-950 hover order-column compact" id="accountsTable"></table>
    </div>
  </div>

  {{-- ── Create Modal ──────────────────────────────────────────────────────── --}}
  <x-modal-garic id="account-create" title="Create User" maxWidth="max-w-[480px]">
    <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]">
      <div class="w-full flex flex-col items-center justify-center gap-6">
        <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
        <h1 class="font-semibold text-xl">CREATE USER</h1>
      </div>

      <form id="account-create-form" class="flex flex-col gap-4" novalidate>
        @csrf
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">ROLE</label>
          <select class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="role">
            <option value="0">Patient</option>
            @if(auth()->user()->role == 2)
            <option value="1">Secretary</option>
            <option value="2">Doctor / Superadmin</option>
              @endif
          </select>
          <p class="hidden text-red-600 text-xs" data-error="role"></p>
        </div>
       <div id="patient-type-wrapper" class="hidden flex flex-col gap-1">
        <label class="font-semibold text-sm">PATIENT TYPE</label>

        <div class="flex gap-4">
          <label class="flex items-center gap-2">
            <input type="radio" name="patient_type" value="new">
            New Patient
          </label>

          <label class="flex items-center gap-2">
            <input type="radio" name="patient_type" value="old">
            Old Patient
            <span class="text-xs text-gray-400">(Has existing clinical records)</span>
          </label>
        </div>

        <p class="hidden text-red-600 text-xs" data-error="patient_type"></p>
      </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">EMAIL</label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            type="email" name="email" placeholder="user@example.com">
          <p class="hidden text-red-600 text-xs" data-error="email"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">USERNAME</label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            type="text" name="username" placeholder="username">
          <p class="hidden text-red-600 text-xs" data-error="username"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">PASSWORD</label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            type="password" name="password" placeholder="••••••">
          <p class="hidden text-red-600 text-xs" data-error="password"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">CONFIRM PASSWORD</label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            type="password" name="password_confirmation" placeholder="••••••">
        </div>
      </form>
    </div>
    <div class="mt-6 flex justify-end gap-2">
      <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
        data-modal-close="account-create" type="button">Cancel</button>
      <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90"
        type="submit" form="account-create-form">
        <i class="fa-solid fa-plus fa-xs me-2"></i>Create User
      </button>
    </div>
  </x-modal-garic>

  {{-- ── Edit Modal ────────────────────────────────────────────────────────── --}}
  <x-modal-garic id="account-edit" title="Edit User" maxWidth="max-w-[480px]">
    <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]">
      <div class="w-full flex flex-col items-center justify-center gap-6">
        <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
        <h1 class="font-semibold text-xl">EDIT USER</h1>
      </div>

      <form id="account-edit-form" class="flex flex-col gap-4" novalidate>
        @csrf
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">ROLE</label>
          <select class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="role">
            <option value="0">Patient</option>
            <option value="1">Secretary</option>
            <option value="2">Doctor / Superadmin</option>
          </select>
          <p class="hidden text-red-600 text-xs" data-error="role"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">EMAIL</label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            type="email" name="email">
          <p class="hidden text-red-600 text-xs" data-error="email"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">USERNAME</label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            type="text" name="username">
          
          <p class="hidden text-red-600 text-xs" data-error="username"></p>
        </div>
        <div class="flex items-center justify-between py-1">
          <div>
            <label class="font-semibold text-sm">ACCOUNT STATUS</label>
            <p class="text-xs text-gray-400 mt-0.5">Inactive accounts cannot log in.</p>
          </div>
          <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="is_active" class="sr-only peer" value="1">
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer
              peer-checked:after:translate-x-full peer-checked:after:border-white
              after:content-[''] after:absolute after:top-[2px] after:left-[2px]
              after:bg-white after:border-gray-300 after:border after:rounded-full
              after:h-5 after:w-5 after:transition-all
              peer-checked:bg-emerald-600"></div>
          </label>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">
            NEW PASSWORD
            <span class="text-blue-950/40 font-normal text-xs ms-1">(leave blank to keep)</span>
          </label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            type="password" name="password" placeholder="••••••">
          <p class="hidden text-red-600 text-xs" data-error="password"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">CONFIRM NEW PASSWORD</label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            type="password" name="password_confirmation" placeholder="••••••">
        </div>
      </form>
    </div>
    <div class="mt-6 flex justify-end gap-2">
      <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
        data-modal-close="account-edit" type="button">Cancel</button>
      <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90"
        type="submit" form="account-edit-form">
        <i class="fa-solid fa-pen fa-xs me-2"></i>Save Changes
      </button>
    </div>
  </x-modal-garic>

  {{-- ── Delete Modal ──────────────────────────────────────────────────────── --}}
  <x-modal-garic id="account-delete" title="Delete User" maxWidth="max-w-sm">
    <div class="flex flex-col items-center gap-4 text-center py-2">
      <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-100">
        <i class="fa-solid fa-triangle-exclamation fa-lg text-red-700"></i>
      </div>
      <div class="flex flex-col gap-1">
        <p class="text-blue-950 font-semibold">
          Are you sure you want to delete
          <span class="text-red-700" id="delete-account-name"></span>?
        </p>
        <p class="text-sm text-gray-500">This action cannot be undone.</p>
      </div>
    </div>
    <div class="mt-6 flex justify-end gap-2">
      <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
        data-modal-close="account-delete" type="button">Cancel</button>
      <button class="px-6 py-2 bg-red-700 text-red-100 rounded-md hover:bg-red-700/90"
        id="confirm-delete-btn" type="button">
        <i class="fa-solid fa-trash fa-xs me-2"></i>Delete
      </button>
    </div>
  </x-modal-garic>

  {{-- ── Doctor Info Modal ─────────────────────────────────────────────────── --}}
  <x-modal-garic id="account-doctor-info" title="Doctor Information" maxWidth="max-w-2xl">
    <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
      <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
      <h1 class="font-semibold text-xl">DOCTOR INFORMATION</h1>
    </div>
    <form class="flex flex-col gap-4 overflow-y-auto max-h-[65vh] pe-2" id="doctor-info-form" novalidate>
      @csrf
      <input id="doctor_user_id" type="hidden" name="user_id">

      <p class="text-xs font-bold uppercase tracking-widest text-blue-950/50">Personal Details</p>
      <div class="grid grid-cols-3 gap-3">
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">LAST NAME<span class="text-red-700">*</span></label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="Lname" type="text" placeholder="Dela Cruz">
          <p class="hidden text-red-600 text-xs" data-error="Lname"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">FIRST NAME<span class="text-red-700">*</span></label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="Fname" type="text" placeholder="Juan">
          <p class="hidden text-red-600 text-xs" data-error="Fname"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">MIDDLE NAME<span class="text-red-700">*</span></label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="Mname" type="text" placeholder="Santos">
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">BIRTHDATE<span class="text-red-700">*</span></label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="DateofBirth" type="date">
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">SEX<span class="text-red-700">*</span></label>
          <select class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="Gender">
            <option value="">— Select —</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">CONTACT<span class="text-red-700">*</span></label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="ContactNumber" type="text" placeholder="09123456789" maxlength="11" required>
        </div>
        <div class="flex flex-col gap-1 col-span-3">
          <label class="font-semibold text-sm">ADDRESS <span class="text-red-700">*</span></label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="Address" type="text" placeholder="Brgy. 5, Calamba, Laguna">
        </div>
      </div>

      <p class="text-xs font-bold uppercase tracking-widest text-blue-950/50 mt-1">Professional Details</p>
      <div class="grid grid-cols-2 gap-3">
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">LICENSE NO. <span class="text-red-700">*</span></label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="dr_license_no" type="text" placeholder="e.g. 0123456">
          <p class="hidden text-red-600 text-xs" data-error="dr_license_no"></p>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">PTR NO.</label>
          <input class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            name="ptr_no" type="text" placeholder="e.g. 7654321">
          <p class="hidden text-red-600 text-xs" data-error="ptr_no"></p>
        </div>
      </div>
    </form>

    <div class="mt-6 flex justify-end gap-2">
      <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
        data-modal-close="account-doctor-info" type="button">Cancel</button>
      <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90"
        type="submit" form="doctor-info-form">
        <i class="fa-solid fa-floppy-disk fa-xs me-2"></i>Save
      </button>
    </div>
  </x-modal-garic>

  {{-- ── Patient Info Modal ────────────────────────────────────────────────── --}}
  <x-modal-garic id="account-patient-info" title="Patient Information" maxWidth="max-w-[680px]">
    <div x-data="{
      step: 1,
      init() {
        const modal = document.getElementById('account-patient-info');
        new MutationObserver(() => {
          if (modal.style.display !== 'none' && !modal.classList.contains('hidden')) {
            this.step = 1;
          }
        }).observe(modal, { attributes: true, attributeFilter: ['style', 'class'] });
      },
      nextStep() {
        const section = document.getElementById('patient-info-step-' + this.step);
        for (const input of section.querySelectorAll('input[required], select[required]')) {
          if (!input.checkValidity()) { input.reportValidity(); return; }
        }
        this.step++;
      },
      prevStep() { if (this.step > 1) this.step--; },
    }">

      {{-- Logo + Title --}}
      <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
        <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
        <h1 class="font-semibold text-xl">PATIENT INFORMATION</h1>
      </div>

      {{-- Step indicator --}}
      @php $steps = ['Basic Information', 'Personal / Social History', 'Family History']; @endphp
      <div class="flex items-start justify-center mb-6">
        @foreach ($steps as $i => $label)
          @php $n = $i + 1; @endphp
          <div class="flex items-center">
            <div class="flex flex-col items-center">
              <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all duration-200"
                :class="step >= {{ $n }} ? 'bg-blue-950 border-blue-950 text-white' : 'bg-white border-gray-300 text-gray-400'">
                <template x-if="step > {{ $n }}"><i class="fa-solid fa-check text-xs"></i></template>
                <template x-if="step <= {{ $n }}"><span>{{ $n }}</span></template>
              </div>
              <p class="text-[10px] mt-1 font-medium text-center w-20 leading-tight"
                :class="step >= {{ $n }} ? 'text-blue-950' : 'text-gray-400'">{{ $label }}</p>
            </div>
            @if ($i < count($steps) - 1)
              <div class="w-14 h-0.5 mb-5 mx-1 transition-colors duration-200"
                :class="step > {{ $n }} ? 'bg-blue-950' : 'bg-gray-200'"></div>
            @endif
          </div>
        @endforeach
      </div>

      <form id="patient-info-form" class="flex flex-col gap-3">
        @csrf
        <input id="patient_user_id" type="hidden" name="user_id">

        {{-- Step 1: Basic Information --}}
        <div id="patient-info-step-1" x-show="step === 1" class="flex flex-col gap-3">
          <div class="flex gap-2">
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Last Name <span class="text-red-700">*</span></label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="Lname" placeholder="Rizal" required />
            </div>
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">First Name <span class="text-red-700">*</span></label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="Fname" placeholder="Jose" required />
            </div>
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Middle Name</label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="Mname" placeholder="Protacio" />
            </div>
          </div>
          <div class="flex gap-2">
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Date of Birth <span class="text-red-700">*</span></label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="date" name="DateofBirth" required />
            </div>
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Sex <span class="text-red-700">*</span></label>
              <select class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" name="Gender" required>
                <option value="" disabled selected hidden>Select Sex</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                
              </select>
              
            </div>
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Nationality <span class="text-red-700">*</span></label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="Nationality" placeholder="Filipino" required />
            </div>
          </div>
          <div class="flex gap-2">
            <div class="flex flex-col gap-1 w-48">
              <label class="text-xs font-medium text-blue-950">Contact Number <span class="text-red-700">*</span></label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="ContactNumber" placeholder="09123456789" maxlength="11" required />
            </div>
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Address <span class="text-red-700">*</span></label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="Address" placeholder="Brgy. 5, Calamba, Laguna" required />
            </div>
          </div>
          <div class="flex gap-2">
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Guardian Name <span class="text-red-700"></span></label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="GuardianName" placeholder="Teodora Alonso" />
            </div>
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Relationship <span class="text-red-700"></span></label>
              <select class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" name="GuardianRelation" >
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
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="GuardianContact" placeholder="09987654321" maxlength="11" />
            </div>
          </div>
        </div>

        {{-- Step 2: Personal / Social History --}}
        <div id="patient-info-step-2" x-show="step === 2" class="flex flex-col gap-3">
          <div class="flex gap-2">
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Allergy / Allergies</label>
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="Allergy" placeholder="e.g. Peanuts, Milk, Soy" />
            </div>
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Alcohol</label>
              <select class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" name="Alcohol">
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
              <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="number" name="Years_of_Smoking" placeholder="0" min="0" />
            </div>
            <div class="flex flex-col gap-1 flex-1">
              <label class="text-xs font-medium text-blue-950">Illicit Drug Use</label>
              <select class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" name="IllicitDrugUse">
                <option value="" disabled selected>Select</option>
                <option value="none">None</option>
                <option value="cocaine">Cocaine</option>
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

        {{-- Step 3: Family History --}}
        <div id="patient-info-step-3" x-show="step === 3" class="flex flex-col gap-4">
          <p class="text-xs font-medium text-blue-950">Check all conditions that apply in the family:</p>
          <div class="grid grid-cols-3 gap-3">
            @foreach (['Hypertension' => 'Hypertension', 'Asthma' => 'Asthma', 'Diabetes' => 'Diabetes', 'Cancer' => 'Cancer', 'Thyroid' => 'Thyroid'] as $value => $label)
              <label class="flex items-center gap-2 cursor-pointer p-2 border border-gray-200 rounded-md hover:bg-blue-50 transition">
                <input class="w-4 h-4 accent-blue-950" type="checkbox" name="family_history[]" value="{{ $value }}" />
                <span class="text-sm font-medium text-blue-950">{{ $label }}</span>
              </label>
            @endforeach
          </div>
          <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-blue-950">Others</label>
            <input class="border-2 border-blue-950 rounded-md px-3 py-2 text-sm" type="text" name="family_history_other" placeholder="e.g. Tuberculosis" />
          </div>
        </div>

        {{-- Navigation --}}
        <div class="flex justify-between items-center mt-6 pt-4 border-t border-gray-100">
          <button class="px-5 py-2 border-2 border-gray-300 text-gray-600 rounded-md text-sm hover:bg-gray-50 transition flex items-center gap-2"
            type="button" x-show="step > 1" @click="prevStep()">
            <i class="fa-solid fa-arrow-left fa-xs"></i> Back
          </button>
          <span x-show="step === 1"></span>
          <div class="flex gap-2">
            <button class="px-5 py-2 bg-gray-600 text-gray-100 rounded-md text-sm hover:bg-gray-500 transition"
              type="button" data-modal-close="account-patient-info" x-show="step === 1">Cancel</button>
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

  {{-- ── Secretary Info Modal ──────────────────────────────────────────────── --}}
  <x-modal-garic id="account-secretary-info" title="Secretary Information" maxWidth="max-w-lg">
    <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
      <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
      <h1 class="font-semibold text-xl">SECRETARY INFORMATION</h1>
    </div>
    <form class="flex flex-col gap-3 overflow-y-auto max-h-[65vh] text-xs pe-2" id="secretary-info-form">
      @csrf
      <input id="secretary_user_id" type="hidden" name="user_id">

      <div class="grid grid-cols-3 gap-3">
        @foreach ([
          ['name' => 'Lname',         'label' => 'LAST NAME',      'type' => 'text',  'span' => ''],
          ['name' => 'Fname',         'label' => 'FIRST NAME',     'type' => 'text',  'span' => ''],
          ['name' => 'Mname',         'label' => 'MIDDLE NAME',    'type' => 'text',  'span' => ''],
          ['name' => 'DateofBirth',   'label' => 'BIRTHDATE',      'type' => 'date',  'span' => ''],
          ['name' => 'ContactNumber', 'label' => 'CONTACT',        'type' => 'text',  'span' => ''],
          ['name' => 'SecAssignedID', 'label' => 'ASSIGNED ID',    'type' => 'text',  'span' => ''],
        ] as $f)
          <div class="{{ $f['span'] }}">
            <label class="block mb-1 font-semibold text-blue-950/70">{{ $f['label'] }}</label>
            <input class="w-full p-1.5 border border-blue-950/30 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-950 text-xs"
              name="{{ $f['name'] }}" type="{{ $f['type'] }}">
          </div>
        @endforeach
        <div class="col-span-2">
          <label class="block mb-1 font-semibold text-blue-950/70">ADDRESS</label>
          <input class="w-full p-1.5 border border-blue-950/30 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-950 text-xs"
            name="Address" type="text">
        </div>
        <div>
          <label class="block mb-1 font-semibold text-blue-950/70">SEX</label>
          <select class="w-full p-1.5 border border-blue-950/30 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-950 text-xs"
            name="Gender">
            <option value="">— Select —</option>
            <option value="Male">Male

            </option>
            <option value="Female">Female</option>
          </select>
        </div>
      </div>
    </form>

    <div class="mt-4 flex justify-end gap-2">
      <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
        data-modal-close="account-secretary-info" type="button">Cancel</button>
      <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90"
        type="submit" form="secretary-info-form">
        <i class="fa-solid fa-floppy-disk fa-xs me-2"></i>Save
      </button>
    </div>
  </x-modal-garic>
  {{-- ── Role Filter Modal ──────────────────────────────────────────────────── --}}
  <x-modal-garic id="account-role-filter" title="Filter by Role" maxWidth="max-w-[380px]">
    <div class="w-full flex flex-col items-center justify-center gap-6 mb-4">
      <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
      <h1 class="font-semibold text-xl">FILTER ACCOUNTS</h1>
    </div>
    <div class="flex flex-col gap-1" x-data="accountRoleFilter()">
      @php
$roleFilters = [];

// Only non-role 2 users can see "All"
if (auth()->user()->role == 2) {
    $roleFilters[] = [
        'label' => 'All',
        'value' => '',
        'badge_class' => 'bg-gray-100 text-gray-700',
        'icon' => 'fa-solid fa-layer-group'
    ];
}

// Everyone can see Patient
$roleFilters[] = [
    'label' => 'Patient',
    'value' => 'Patient',
    'badge_class' => 'bg-blue-100 text-blue-800',
    'icon' => 'fa-solid fa-hospital-user'
];

// Only role 2 can see these
if (auth()->user()->role == 2) {
    $roleFilters[] = [
        'label' => 'Secretary',
        'value' => 'Secretary',
        'badge_class' => 'bg-amber-100 text-amber-800',
        'icon' => 'fa-solid fa-id-card',
    ];

    $roleFilters[] = [
        'label' => 'Doctor/Superadmin',
        'value' => 'Doctor/Superadmin',
        'badge_class' => 'bg-indigo-100 text-indigo-800',
        'icon' => 'fa-solid fa-user-doctor',
    ];
}
@endphp
      @foreach ($roleFilters as $f)
        <button type="button"
          class="w-full flex items-center justify-between px-4 py-3 rounded-lg border transition-colors"
          :class="active === '{{ $f['value'] }}'
            ? 'border-blue-950 bg-blue-950/5 text-blue-950'
            : 'border-transparent hover:bg-gray-50 text-gray-700'"
          @click="apply('{{ $f['value'] }}')">
          <div class="flex items-center gap-3">
            <i class="{{ $f['icon'] }} fa-sm w-4 text-center"></i>
            <span class="px-2 py-0.5 rounded text-xs font-semibold {{ $f['badge_class'] }}">
              {{ $f['label'] }}
            </span>
          </div>
          <i class="fa-solid fa-check fa-sm text-blue-950 transition-opacity"
            :class="active === '{{ $f['value'] }}' ? 'opacity-100' : 'opacity-0'"></i>
        </button>
      @endforeach
    </div>
  </x-modal-garic>

@endsection
@push("scripts")
  @vite(["resources/js/components/modals/modal.js"])
  @vite(["resources/js/pages/accounts-table.js"])
  <script>
    document.addEventListener("alpine:init", () => {
      Alpine.data("accountRoleFilter", () => ({
        active: "Patient",

        apply(value) {
          this.active = value;
          window.filterAccountsTable?.(value);
          window.Modal?.close("account-role-filter");
        },
      }));
    });


document.addEventListener("DOMContentLoaded", () => {

  const roleSelect = document.querySelector("select[name='role']");
  const patientTypeWrapper = document.getElementById("patient-type-wrapper");

  function togglePatientType() {
      if(roleSelect.value === "0"){ // patient
          patientTypeWrapper.classList.remove("hidden");
      } else {
          patientTypeWrapper.classList.add("hidden");
      }
  }

  roleSelect.addEventListener("change", togglePatientType);

  togglePatientType(); // run on load

});

  </script>
@endpush
