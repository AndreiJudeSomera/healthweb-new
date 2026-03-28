@php
  $isAdmin = auth()->user()?->role === 2;
@endphp

<x-modal-garic id="edit-document-p" title="Edit Document" maxWidth="max-w-[700px]">
  <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]"
    x-data="editDocumentModal()"
    x-init="init()">

    <div class="w-full flex flex-col items-center justify-center gap-6">
      <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
      <h1 class="font-semibold text-xl" x-text="title"></h1>
    </div>

    <form id="edit_document_form" class="flex flex-col gap-4"
      @submit.prevent="submit()" novalidate>

     {{-- === CONSULTATION === --}}
            <div x-show="form.document_type === 'consultation'" class="flex flex-col gap-4">

              {{-- DATE --}}
              <div class="flex flex-col gap-1">
                <label class="font-semibold text-sm">CONSULTATION DATE</label>
                <input type="date"
                  :class="inputClass('consultation_date')"
                  x-model="form.consultation_date"
                  @input="validate('consultation_date')"
                  @blur="touch('consultation_date')" />

                <p x-show="showError('consultation_date')" 
                  class="text-xs text-red-600" 
                  x-text="errors.consultation_date"></p>
              </div>

              {{-- VITALS --}}
              <div class="flex flex-row w-full gap-2">
                <template x-for="v in vitals" :key="v.name">
                  <div class="flex flex-col gap-1 w-full">
                    <label class="font-semibold text-sm" x-text="v.label"></label>

                    <input type="text"
                      placeholder="—"
                      :class="inputClass(v.name)"
                      x-model="form[v.name]"
                      @input="validate(v.name)"
                      @blur="touch(v.name)" />

                    <p x-show="showError(v.name)" 
                      class="text-xs text-red-600" 
                      x-text="errors[v.name]"></p>
                  </div>
                </template>
              </div>

              {{-- HISTORY --}}

              <div class="flex flex-col gap-1">
                <label class="font-semibold text-sm">HISTORY / PHYSICAL EXAM</label>
                <textarea rows="3"
                  :class="inputClass('history_physical_exam')"
                  x-model="form.history_physical_exam"
                  @input="validate('history_physical_exam')"
                  @blur="touch('history_physical_exam')"></textarea>

                <p x-show="showError('history_physical_exam')" 
                  class="text-xs text-red-600" 
                  x-text="errors.history_physical_exam"></p>
              </div>


              {{-- DIAGNOSIS + TREATMENT --}}
              <div class="flex flex-row gap-2">

                <div class="flex flex-col gap-1 w-full">
                  <label class="font-semibold text-sm">DIAGNOSIS</label>
                  <textarea rows="3"
                    :class="inputClass('diagnosis')"
                    x-model="form.diagnosis"
                    @input="validate('diagnosis')"
                    @blur="touch('diagnosis')"></textarea>

                  <p x-show="showError('diagnosis')" 
                    class="text-xs text-red-600" 
                    x-text="errors.diagnosis"></p>
                </div>

                <div class="flex flex-col gap-1 w-full">
                  <label class="font-semibold text-sm">TREATMENT</label>
                  <textarea rows="3"
                    :class="inputClass('treatment')"
                    x-model="form.treatment"
                    @input="validate('treatment')"
                    @blur="touch('treatment')"></textarea>

                  <p x-show="showError('treatment')" 
                    class="text-xs text-red-600" 
                    x-text="errors.treatment"></p>
                </div>

              </div>
            </div>

      {{-- === MEDICAL CERTIFICATE === --}}
      <div x-show="form.document_type === 'medical-certificate'" class="flex flex-col gap-4">
        <div class="flex flex-row gap-2">
          <div class="flex flex-col gap-1 w-full">
            <label class="font-semibold text-sm">DATE ISSUED</label>
            <input type="date"
              class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
              x-model="form.created_at" />
          </div>
          <div class="flex flex-col gap-1 w-full">
            <label class="font-semibold text-sm">DATE EXAMINED</label>
            <input type="date"
              class="w-full border border-blue-950/30 rounded-md px-3 py-2 bg-gray-50 text-gray-500 cursor-not-allowed"
              x-model="form.consultation_date"
              readonly />
          </div>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">DIAGNOSIS</label>
          <textarea rows="3"
            class="w-full border border-blue-950/30 rounded-md px-3 py-2 bg-gray-50 text-gray-500 cursor-not-allowed"
            x-model="form.diagnosis"
            readonly></textarea>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">REQUEST FOR</label>
          <input type="text"
            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            placeholder="2 days sick leave, absence from school…"
            x-model="form.referral_to" />
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">REASON FOR REQUEST</label>
          <textarea rows="3"
            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            x-model="form.referral_reason"></textarea>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">REMARKS</label>
          <textarea rows="3"
            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            x-model="form.remarks"></textarea>
        </div>
      </div>

      {{-- === REFERRAL LETTER === --}}
      <div x-show="form.document_type === 'referral-letter'" class="flex flex-col gap-4">
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">DATE ISSUED</label>
          <input type="date"
            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            x-model="form.created_at" />
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">DIAGNOSIS</label>
          <textarea rows="3"
            class="w-full border border-blue-950/30 rounded-md px-3 py-2 bg-gray-50 text-gray-500 cursor-not-allowed"
            x-model="form.diagnosis"
            readonly></textarea>
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">RECIPIENT</label>
          <input type="text"
            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            placeholder="Dr. Jose Rizal – Ophthalmologist"
            x-model="form.referral_to" />
        </div>
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">REASON FOR REFERRAL</label>
          <textarea rows="3"
            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            x-model="form.referral_reason"></textarea>
        </div>
      </div>

      {{-- === PRESCRIPTION === --}}
      <div x-show="form.document_type === 'prescription'" class="flex flex-col gap-4">
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm">DATE</label>
          <input type="date"
            class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"
            x-model="form.created_at" />
        </div>

        {{-- Medicine list --}}
        <div class="flex flex-col gap-3">
          <div class="flex items-center justify-between">
            <h2 class="font-semibold text-sm">MEDICINES</h2>
            <button class="px-4 py-2 border border-blue-950 rounded-md hover:bg-blue-100/90 flex items-center gap-2"
              type="button" @click="rxOpen = true">
              <i class="fa-solid fa-plus fa-xs"></i>
              <span class="text-sm font-medium">Add Medicine Form</span>
            </button>
          </div>

          <template x-if="form.medicine_list.length === 0">
            <p class="text-sm text-gray-500">No medicines added yet.</p>
          </template>

          <template x-for="(m, idx) in form.medicine_list" :key="m._key">
            <div class="w-full border border-blue-950 rounded-md p-3 flex flex-col gap-1">
              <div class="flex items-center justify-between">
                <div class="flex gap-2 items-center">
                  <i class="fa-solid fa-prescription"></i>
                  <p class="font-semibold" x-text="m.medicine_name"></p>
                </div>
                <button class="text-sm text-red-600 hover:underline" type="button" @click="rxRemove(idx)">Remove</button>
              </div>
              <p class="text-sm font-medium">Dosage: <span class="font-normal" x-text="m.dosage || '—'"></span></p>
              <p class="text-sm font-medium">Frequency: <span class="font-normal" x-text="m.frequency || '—'"></span></p>
              <p class="text-sm font-medium">Duration: <span class="font-normal" x-text="m.duration || '—'"></span></p>
              <p class="text-sm font-medium">Instructions: <span class="font-normal" x-text="(m.instructions || '').trim() || '—'"></span></p>
            </div>
          </template>
        </div>

        {{-- Add medicine panel --}}
        <div class="flex flex-col gap-4 border border-blue-950/30 rounded-md p-3" x-show="rxOpen" x-transition>
          <div class="flex items-center justify-between">
            <h3 class="font-semibold text-sm">ADD MEDICINE</h3> <p class="text-xs text-gray-500 mt-1">
          Scroll down and click <span class="font-medium">Add</span> button to include a medicine.
        </p>
            <button class="text-sm text-gray-600 hover:underline" type="button" @click="rxDiscard()">Close</button>
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-semibold text-sm">MEDICINE NAME</label>
            <select x-model="rxMed.medicine_id"
              class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950">
              <option value="" disabled selected>Select medicine</option>
              <template x-for="med in rxMedicines" :key="med.id">
                <option :value="med.id" x-text="med.medicine_name"></option>
              </template>
              <option value="__other__">Others (type manually)</option>
            </select>
          </div>

          <div class="flex flex-col gap-1" x-show="rxMed.medicine_id === '__other__'">
            <label class="font-semibold text-sm">MEDICINE NAME (CUSTOM)</label>
            <input type="text" placeholder="e.g. Amoxicillin 500mg" x-model.trim="rxMed.custom_name"
              class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950" />
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-semibold text-sm">DOSAGE</label>
            <input type="text" placeholder="500mg" x-model.trim="rxMed.dosage"
              class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950" />
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-semibold text-sm">FREQUENCY</label>
            <select x-model="rxMed.frequency"
              class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950">
              <option value="" disabled selected>Select frequency</option>
              <option value="Once a day (OD)">Once a day (OD)</option>
              <option value="Twice a day (BID)">Twice a day (BID)</option>
              <option value="Three times a day (TID)">Three times a day (TID)</option>
              <option value="Four times a day (QID)">Four times a day (QID)</option>
              <option value="Every 6 hours (Q6H)">Every 6 hours (Q6H)</option>
              <option value="Every 8 hours (Q8H)">Every 8 hours (Q8H)</option>
              <option value="Every 12 hours (Q12H)">Every 12 hours (Q12H)</option>
              <option value="As needed (PRN)">As needed (PRN)</option>
              <option value="At bedtime (HS)">At bedtime (HS)</option>
              <option value="Once a week">Once a week</option>
            </select>
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-semibold text-sm">DURATION</label>
            <input type="text" placeholder="7 days" x-model.trim="rxMed.duration"
              class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950" />
          </div>

          <div class="flex flex-col gap-1">
            <label class="font-semibold text-sm">INSTRUCTIONS</label>
            <textarea rows="2" placeholder="After meals." x-model.trim="rxMed.instructions"
              class="w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 border-blue-950 focus:ring-blue-950"></textarea>
          </div>

          <div class="w-full flex flex-col md:flex-row gap-2 md:justify-end">
            <button class="w-full md:w-auto px-4 py-2 border border-red-700 text-red-700 rounded-md hover:bg-red-100/90 flex items-center gap-2 justify-center"
              type="button" @click="rxDiscard()">
              <i class="fa-solid fa-trash fa-xs"></i>
              <span class="text-sm font-medium">Discard</span>
            </button>
            <button class="w-full md:w-auto px-4 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90 flex items-center gap-2 justify-center"
              type="button" @click="rxAdd()">
              <i class="fa-solid fa-plus fa-xs"></i>
              <span class="text-sm font-medium">Add</span>
            </button>
          </div>
        </div>

      </div>
    </form>
  </div>

  {{-- Footer outside scroll area --}}
  <div class="mt-6 flex justify-end gap-2">
    <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
      data-modal-close="edit-document-p" type="button">
      Cancel
    </button>
    <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90"
      type="submit" form="edit_document_form">
      <i class="fa-solid fa-pen fa-xs me-2"></i>
      Save Changes
    </button>
  </div>
</x-modal-garic>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data("editDocumentModal", () => ({
      title: "EDIT DOCUMENT",

      form: {
        id: "",
        document_type: "",
        patient_pid: "",
        consultation_date: "",
        created_at: "",
        wt: "", bp: "", cr: "", rr: "", temperature: "", sp02: "",
        history_physical_exam: "",
        diagnosis: "",
        treatment: "",
        referral_to: "",
        referral_reason: "",
        remarks: "",
        prescription_meds: "",
        medicine_list: [],
      },

      // =========================
    // ✅ VALIDATION STATE
    // =========================
    touched: {},
    errors: {},

    rules: {
      consultation_date: [
        v => v ? "" : "Consultation date is required.",
      ],

      wt: [
        v => v ? "" : "WT is required.",
        v => (/^\d{1,3}(\.\d{1,2})?$/.test(v) ? "" : "WT must be a number."),
        v => {
          const n = Number(v);
          return (n >= 1 && n <= 500) ? "" : "WT seems out of range.";
        },
      ],

      bp: [
        v => v ? "" : "BP is required.",
        v => (/^\d{2,3}\/\d{2,3}$/.test(v) ? "" : "BP must be like 120/80."),
      ],

      cr: [
        v => v ? "" : "CR is required.",
        v => (/^\d{2,3}$/.test(v) ? "" : "CR must be a number."),
        v => {
          const n = Number(v);
          return (n >= 20 && n <= 250) ? "" : "CR seems out of range.";
        },
      ],

      rr: [
        v => v ? "" : "RR is required.",
        v => (/^\d{1,3}$/.test(v) ? "" : "RR must be a number."),
        v => {
          const n = Number(v);
          return (n >= 5 && n <= 80) ? "" : "RR seems out of range.";
        },
      ],

      temperature: [
        v => v ? "" : "TEMP is required.",
        v => (/^\d{2}(\.\d{1,2})?$/.test(v) ? "" : "TEMP must be like 36.6."),
        v => {
          const n = Number(v);
          return (n >= 30 && n <= 45) ? "" : "TEMP seems out of range.";
        },
      ],

      sp02: [
        v => v ? "" : "SPO2 is required.",
        v => (/^\d{1,3}$/.test(v) ? "" : "SPO2 must be a number."),
        v => {
          const n = Number(v);
          return (n >= 0 && n <= 100) ? "" : "SPO2 must be 0–100.";
        },
      ],

      history_physical_exam: [
        v => v ? "" : "History is required.",
        v => (v.length >= 5 ? "" : "Add more detail."),
      ],

      diagnosis: [
        v => v ? "" : "Diagnosis is required.",
        v => (v.length >= 2 ? "" : "Too short."),
      ],

      treatment: [
        v => v ? "" : "Treatment is required.",
        v => (v.length >= 2 ? "" : "Too short."),
      ],
    },

    touch(name) {
      this.touched[name] = true;
    },

    showError(name) {
      return !!this.touched[name] && !!this.errors[name];
    },

    inputClass(name) {
      const base = "w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 ";
      const ok = "border-blue-950 focus:ring-blue-950";
      const bad = "border-red-600 focus:ring-red-600";
      return base + (this.showError(name) ? bad : ok);
    },

    validate(name) {
      const validators = this.rules[name] || [];
      const value = (this.form[name] ?? "").toString().trim();

      for (const fn of validators) {
        const msg = fn(value);
        if (msg) {
          this.errors[name] = msg;
          return false;
        }
      }

      this.errors[name] = "";
      return true;
    },

    // validateConsultation() {
    //   const fields = [
    //     "consultation_date", "wt", "bp", "cr", "rr",
    //     "temperature", "sp02", "history_physical_exam",
    //     "diagnosis", "treatment"
    //   ];

    //   fields.forEach(f => this.touched[f] = true);
    //   return fields.every(f => this.validate(f));
    // },

    validateConsultation() {
  const fields = [
    "consultation_date", "wt", "bp", "cr", "rr",
    "temperature", "sp02", "history_physical_exam",
    "diagnosis", "treatment"
  ];

  let isValid = true;

  fields.forEach(f => {
    this.touched[f] = true;

    const valid = this.validate(f); // force validation

    if (!valid) {
      isValid = false;
    }
  });

  return isValid;
},

      // Prescription panel state
      rxOpen: false,
      rxMedicines: [],
      rxMed: { medicine_id: "", custom_name: "", dosage: "", frequency: "", duration: "", instructions: "" },

      vitals: [
        { name: "wt",          label: "WT" },
        { name: "bp",          label: "BP" },
        { name: "cr",          label: "CR" },
        { name: "rr",          label: "RR" },
        { name: "temperature", label: "TEMP" },
        { name: "sp02",        label: "SPO2" },
      ],

      async init() {
        // Load medicine dropdown once
        try {
          const res = await fetch("/medicines", { headers: { Accept: "application/json" }, credentials: "same-origin" });
          const data = await res.json().catch(() => []);
          this.rxMedicines = Array.isArray(data) ? data : [];
        } catch (_) {}

        const TITLES = {
          "consultation":        "EDIT CONSULTATION",
          "medical-certificate": "EDIT MEDICAL CERTIFICATE",
          "referral-letter":     "EDIT REFERRAL LETTER",
          "prescription":        "EDIT PRESCRIPTION",
        };

        const modalRoot = document.getElementById("edit-document-p");
        modalRoot?.addEventListener("edit-ready", (e) => {
          const d = e.detail;

          this.title = TITLES[d.document_type] ?? "EDIT DOCUMENT";
          this.rxOpen = false;
          this.rxMed = { medicine_id: "", custom_name: "", dosage: "", frequency: "", duration: "", instructions: "" };

          const h2 = modalRoot.querySelector("h2");
          if (h2) h2.textContent = TITLES[d.document_type]
            ?.replace("EDIT ", "Edit ") ?? "Edit Document";

          // Build medicine_list: use structured array from backend, or fall back to empty
          const rawList = Array.isArray(d.medicine_list) ? d.medicine_list : [];
          const medicine_list = rawList.map(m => ({
            _key:          crypto?.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`,
            medicine_id:   m.medicine_id ?? null,
            medicine_name: m.medicine_name ?? "Unknown",
            dosage:        m.dosage ?? "",
            frequency:     m.frequency ?? "",
            duration:      m.duration ?? "",
            instructions:  m.instructions ?? "",
          }));

          this.form = {
            id:                    d.id ?? "",
            document_type:         d.document_type ?? "",
            patient_pid:           d.patient_pid ?? "",
            consultation_date:     d.consultation_date ?? "",
            created_at:            d.created_at ? d.created_at.split("T")[0] : "",
            wt:                    d.wt ?? "",
            bp:                    d.bp ?? "",
            cr:                    d.cr ?? "",
            rr:                    d.rr ?? "",
            temperature:           d.temperature ?? "",
            sp02:                  d.sp02 ?? "",
            history_physical_exam: d.history_physical_exam ?? "",
            diagnosis:             d.diagnosis ?? "",
            treatment:             d.treatment ?? "",
            referral_to:           d.referral_to ?? "",
            referral_reason:       d.referral_reason ?? "",
            remarks:               d.remarks ?? "",
            prescription_meds:     d.prescription_meds ?? "",
            medicine_list,
          };
        });
      },

      rxResetMed() {
        this.rxMed = { medicine_id: "", custom_name: "", dosage: "", frequency: "", duration: "", instructions: "" };
      },

      rxAdd() {
        const isCustom = this.rxMed.medicine_id === "__other__";
        if (!this.rxMed.medicine_id) { toastr?.error?.("Please select a medicine."); return; }
        // if (!this.rxMed.dosage)      { toastr?.error?.("Dosage is required."); return; }
        if (!this.rxMed.frequency)   { toastr?.error?.("Frequency is required."); return; }
        if (!this.rxMed.duration)    { toastr?.error?.("Duration is required."); return; }
        if (isCustom && !this.rxMed.custom_name) { toastr?.error?.("Please enter the medicine name."); return; }

        const med = !isCustom && this.rxMedicines.find(m => Number(m.id) === Number(this.rxMed.medicine_id));
        const name = isCustom ? this.rxMed.custom_name : (med?.medicine_name ?? "MEDICINE");

        this.form.medicine_list.push({
          _key:         crypto?.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`,
          medicine_id:  isCustom ? null : Number(this.rxMed.medicine_id),
          medicine_name: name,
          dosage:       this.rxMed.dosage,
          frequency:    this.rxMed.frequency,
          duration:     this.rxMed.duration,
          instructions: this.rxMed.instructions,
        });

        this.rxResetMed();
        this.rxOpen = false;
      },

      rxRemove(idx) {
        this.form.medicine_list.splice(idx, 1);
      },

      rxDiscard() {
        this.rxResetMed();
        this.rxOpen = false;
      },

      // async submit() {
      //   const docId = this.form.id;
      //   if (!docId) return;

      //   const type = this.form.document_type;
      //   const common = { document_type: type };\
    async submit() {
  const docId = this.form.id;
  if (!docId) return;

  const type = this.form.document_type;

  // 🔥 ADD THIS BLOCK
  if (type === "consultation") {
    const valid = this.validateConsultation();

    if (!valid) {
      toastr?.error("Please fix the highlighted fields.");
      return; // ❌ STOP submission
    }
  }

  const common = { document_type: type };

        const payloads = {
          "consultation": {
            ...common,
            consultation_date:     this.form.consultation_date,
            wt:                    this.form.wt,
            bp:                    this.form.bp,
            cr:                    this.form.cr,
            rr:                    this.form.rr,
            temperature:           this.form.temperature,
            sp02:                  this.form.sp02,
            history_physical_exam: this.form.history_physical_exam,
            diagnosis:             this.form.diagnosis,
            treatment:             this.form.treatment,
          },
          "medical-certificate": {
            ...common,
            created_at:        this.form.created_at,
            consultation_date: this.form.consultation_date,
            diagnosis:         this.form.diagnosis,
            referral_to:       this.form.referral_to,
            referral_reason:   this.form.referral_reason,
            remarks:           this.form.remarks,
          },
          "referral-letter": {
            ...common,
            created_at:      this.form.created_at,
            diagnosis:       this.form.diagnosis,
            referral_to:     this.form.referral_to,
            referral_reason: this.form.referral_reason,
          },
          "prescription": {
            ...common,
            created_at:   this.form.created_at,
            medicine_list: JSON.stringify(this.form.medicine_list),
            remarks:      this.form.remarks,
          },
        };

        const payload = payloads[type] ?? common;

        try {
          const res = await fetch(`/consultations/${encodeURIComponent(docId)}`, {
            method: "PUT",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
            },
            credentials: "same-origin",
            body: JSON.stringify(payload),
          });

          if (res.status === 422) {
            const data = await res.json().catch(() => null);
            const first = Object.values(data?.errors ?? {})[0]?.[0];
            toastr?.error(first ?? "Please fix the highlighted fields.");
            return;
          }

          if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            toastr?.error(data.message || "Update failed.");
            return;
          }

          toastr?.success("Document updated!");
          document.querySelector('[data-modal-close="edit-document-p"]')?.click();
          window.refreshDocumentsTable?.();
        } catch (err) {
          console.error(err);
          toastr?.error("Network error. Check your connection.");
        }
      },
    }));
  });
</script>
