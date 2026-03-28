{{-- CREATE PRESCRIPTION MODAL (NEW FRONTEND)
   - Loads medicines from GET /medicines
   - Submits to POST /consultations with:
     patient_pid, document_type=prescription, created_at, medicine_list(JSON)
--}}

<x-modal-garic id="create-prescription" title="Create Prescription" maxWidth="max-w-[750px]">
  <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]">
    <div class="w-full flex flex-col items-center justify-center gap-6">
      <img class="w-[200px] -my-12" src="{{ asset("assets/images/logo2.png") }}" alt="Logo">
      <h1 class="font-semibold text-xl">PRESCRIPTION FORM</h1>
    </div>

    <form class="flex flex-col gap-4" id="create-prescription-form" action="" x-data="prescriptionForm(@js($patient->pid))"
      x-init="init()" @submit.prevent="submit($event)" novalidate>
      @csrf

      <input type="hidden" name="patient_pid" value="{{ $patient->pid }}" readonly />
      <input type="hidden" name="document_type" value="prescription" readonly />
      <input type="hidden" name="medicine_list" :value="JSON.stringify(medicine_list)" />

      {{-- Prescription Date (maps to created_at) --}}
      <div class="flex flex-col gap-1">
        <label class="font-semibold text-sm" for="created_at">PRESCRIPTION DATE</label>

        <input id="created_at" type="date" name="created_at" x-model="form.created_at"
          :class="inputClass('created_at')" @blur="touch('created_at'); validate('created_at')"
          @input="if (touched.created_at) validate('created_at')" required />

        <p class="text-xs text-red-600" x-show="showError('created_at')" x-text="errors.created_at"></p>
      </div>

      {{-- Medicines List --}}
      <div class="flex flex-col gap-3">
        <div class="flex items-center justify-between">
          <h2 class="font-semibold text-sm">MEDICINES</h2>

          <button class="px-4 py-2 border border-blue-950 rounded-md hover:bg-blue-100/90 flex items-center gap-2"
            type="button" @click="open = true">
            <i class="fa-solid fa-plus fa-xs"></i>
            <span class="text-sm font-medium">Add Medicine Form</span>
          </button>
        </div>

        <p class="text-xs text-red-600" x-show="showError('medicine_list')" x-text="errors.medicine_list"></p>

        <template x-if="medicine_list.length === 0">
          <p class="text-sm text-gray-500">No medicines added yet.</p>
        </template>

        <template x-for="(m, idx) in medicine_list" :key="m._key">
          <div class="w-full border border-blue-950 rounded-md p-3 flex flex-col gap-1">
            <div class="flex items-center justify-between">
              <div class="flex gap-2 items-center">
                <i class="fa-solid fa-prescription"></i>
                <p class="font-semibold" x-text="m.medicine_name"></p>
              </div>

              <button class="text-sm text-red-600 hover:underline" type="button" @click="removeMedicine(idx)">
                Remove
              </button>
            </div>

            <p class="text-sm font-medium">Dosage: <span class="font-normal" x-text="m.dosage || '—'"></span></p>
            <p class="text-sm font-medium">Frequency: <span class="font-normal" x-text="m.frequency || '—'"></span></p>
            <p class="text-sm font-medium">Duration: <span class="font-normal" x-text="m.duration || '—'"></span></p>
            <p class="text-sm font-medium">
              Instructions: <span class="font-normal" x-text="(m.instructions || '').trim() || '—'"></span>
            </p>
          </div>
        </template>
      </div>

      {{-- Add Medicine Panel --}}
      <div class="flex flex-col gap-4 border border-blue-950/30 rounded-md p-3" x-show="open" x-transition>
      <div class="flex items-center justify-between">
          <h3 class="font-semibold text-sm">Add Medicine</h3>   <p class="text-xs text-gray-500 mt-1">
          Scroll down and click <span class="font-medium">Add</span> button to include a medicine.
        </p>

          <button 
            class="text-sm text-gray-600 hover:underline" 
            type="button" 
            @click="discardMedicine()"
          >
            Close
          </button>
        </div>

     
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm" for="medicine_id">MEDICINE NAME</label>

          <select id="medicine_id" x-model="medicine.medicine_id" :class="inputClass('medicine_id')"
            @blur="touch('medicine_id'); validate('medicine_id')"
            @change="touch('medicine_id'); validate('medicine_id')">
            <option value="" disabled selected>Select medicine</option>
            <template x-for="med in medicines" :key="med.id">
              <option :value="med.id" x-text="med.medicine_name"></option>
            </template>
            <option value="__other__">Others (type manually)</option>
          </select>

          <template x-if="medicines_loading">
            <p class="text-xs text-gray-500 mt-1">Loading medicines…</p>
          </template>
          <template x-if="medicines_error">
            <p class="text-xs text-red-600 mt-1" x-text="medicines_error"></p>
          </template>

          <p class="text-xs text-red-600" x-show="showError('medicine_id')" x-text="errors.medicine_id"></p>
        </div>

        <div class="flex flex-col gap-1" x-show="medicine.medicine_id === '__other__'">
          <label class="font-semibold text-sm" for="custom_name">MEDICINE NAME (CUSTOM)</label>
          <input id="custom_name" type="text" placeholder="e.g. Amoxicillin 500mg" x-model.trim="medicine.custom_name"
            :class="inputClass('custom_name')" @blur="touch('custom_name'); validate('custom_name')"
            @input="if (touched.custom_name) validate('custom_name')" />
          <p class="text-xs text-red-600" x-show="showError('custom_name')" x-text="errors.custom_name"></p>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm" for="dosage">DOSAGE</label>
          <input id="dosage" type="text" placeholder="500mg" x-model.trim="medicine.dosage"
            :class="inputClass('dosage')" @blur="touch('dosage'); validate('dosage')"
            @input="if (touched.dosage) validate('dosage')" />
          <p class="text-xs text-red-600" x-show="showError('dosage')" x-text="errors.dosage"></p>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm" for="frequency">FREQUENCY</label>
          <select id="frequency" x-model="medicine.frequency" :class="inputClass('frequency')"
            @blur="touch('frequency'); validate('frequency')"
            @change="touch('frequency'); validate('frequency')">
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
          <p class="text-xs text-red-600" x-show="showError('frequency')" x-text="errors.frequency"></p>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm" for="duration">DURATION</label>
          <input id="duration" type="text" placeholder="7 days" x-model.trim="medicine.duration"
            :class="inputClass('duration')" @blur="touch('duration'); validate('duration')"
            @input="if (touched.duration) validate('duration')" />
          <p class="text-xs text-red-600" x-show="showError('duration')" x-text="errors.duration"></p>
        </div>

        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm" for="instructions">INSTRUCTIONS</label>
          <textarea id="instructions" rows="3" placeholder="After meals." x-model.trim="medicine.instructions"
            :class="inputClass('instructions')" @blur="touch('instructions'); validate('instructions')"
            @input="if (touched.instructions) validate('instructions')"></textarea>
          <p class="text-xs text-red-600" x-show="showError('instructions')" x-text="errors.instructions"></p>
        </div>

        <div class="w-full flex flex-col md:flex-row gap-2 md:justify-end">
          <button
            class="w-full md:w-auto px-4 py-2 border border-red-700 text-red-700 rounded-md hover:bg-red-100/90 flex items-center gap-2 justify-center"
            type="button" @click="discardMedicine()">
            <i class="fa-solid fa-trash fa-xs"></i>
            <span class="text-sm font-medium">Discard</span>
          </button>

          <button
            class="w-full md:w-auto px-4 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90 flex items-center gap-2 justify-center"
            type="button" @click="addMedicine()">
            <i class="fa-solid fa-plus fa-xs"></i>
            <span class="text-sm font-medium">Add</span>
          </button>
        </div>
      </div>
    </form>
  </div>

  <div class="mt-6 flex justify-end gap-2">
    <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
      data-modal-close="create-prescription" type="button">
      Cancel
    </button>

    <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90" type="submit"
      form="create-prescription-form">
      Create Prescription
    </button>
  </div>
</x-modal-garic>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data("prescriptionForm", (patientPid) => ({
      open: false,

      // header fields
      form: {
        created_at: "",
      },

      // dropdown medicines loaded from /medicines
      medicines: [],
      medicines_loading: false,
      medicines_error: null,

      // list to submit
      medicine_list: [],

      // current panel entry
      medicine: {
        medicine_id: "",
        custom_name: "",
        dosage: "",
        frequency: "",
        duration: "",
        instructions: "",
      },

      touched: {},
      errors: {},

      rules: {
        created_at: [(v) => (v ? "" : "Prescription date is required.")],

        medicine_id: [(v) => (v ? "" : "Please select a medicine.")],
        custom_name: [
          (v) => (v ? "" : "Please enter the medicine name."),
          (v) => (v.length >= 2 ? "" : "Name is too short."),
        ],
        dosage: [],
        frequency: [(v) => (v ? "" : "Frequency is required.")],
        duration: [(v) => (v ? "" : "Duration is required.")],

        medicine_list: [(_, ctx) => (ctx.medicine_list.length ? "" : "Add at least one medicine.")],
      },

      async init() {
        await this.loadMedicines();
      },

      async loadMedicines() {
        this.medicines_loading = true;
        this.medicines_error = null;

        try {
          const res = await fetch("{{ route('prescription_items.index') }}", {
            headers: {
              Accept: "application/json"
            },
            credentials: "same-origin",
          });

          const data = await res.json().catch(() => null);

          if (!res.ok) {
            console.error("Failed to load medicines:", res.status, data);
            this.medicines_error = "Failed to load medicines.";
            return;
          }

          this.medicines = Array.isArray(data) ? data : [];
        } catch (e) {
          console.error("Medicines network error:", e);
          this.medicines_error = "Network error while loading medicines.";
        } finally {
          this.medicines_loading = false;
        }
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

        // pick value source
        const value =
          (this.form[name] ?? this.medicine[name] ?? "").toString().trim();

        for (const fn of validators) {
          const msg = fn(value, this);
          if (msg) {
            this.errors[name] = msg;
            return false;
          }
        }

        this.errors[name] = "";
        return true;
      },

      resetMedicine() {
        this.medicine = {
          medicine_id: "",
          custom_name: "",
          dosage: "",
          frequency: "",
          duration: "",
          instructions: "",
        };

        ["medicine_id", "custom_name", "dosage", "frequency", "duration", "instructions"].forEach((k) => {
          this.errors[k] = "";
          this.touched[k] = false;
        });
      },

      addMedicine() {
        const isCustom = this.medicine.medicine_id === "__other__";
        const fields = ["medicine_id", "dosage", "frequency", "duration"];
        if (isCustom) fields.push("custom_name");
        fields.forEach((k) => (this.touched[k] = true));

        const ok = fields.every((k) => this.validate(k));
        if (!ok) return;

        const med = !isCustom && this.medicines.find((m) => Number(m.id) === Number(this.medicine.medicine_id));
        const name = isCustom ? this.medicine.custom_name : (med?.medicine_name ?? "MEDICINE");

        this.medicine_list.push({
          _key: crypto?.randomUUID ? crypto.randomUUID() : `${Date.now()}-${Math.random()}`,
          medicine_id: isCustom ? null : Number(this.medicine.medicine_id),
          medicine_name: name,
          dosage: this.medicine.dosage,
          frequency: this.medicine.frequency,
          duration: this.medicine.duration,
          instructions: this.medicine.instructions,
        });

        // validate list requirement
        this.touched.medicine_list = true;
        this.validate("medicine_list");

        this.resetMedicine();
        this.open = false;
      },

      removeMedicine(idx) {
        this.medicine_list.splice(idx, 1);
        this.touched.medicine_list = true;
        this.validate("medicine_list");
      },

      discardMedicine() {
        this.resetMedicine();
        this.open = false;
      },

      validateAll() {
        this.touched.created_at = true;
        this.touched.medicine_list = true;

        const a = this.validate("created_at");
        const b = this.validate("medicine_list");
        return a && b;
      },

      async submit(e) {
        if (!this.validateAll()) return;

        const formEl = e.target;

        // force correct keys/values no matter what is in DOM
        formEl.querySelector('[name="patient_pid"]')?.setAttribute("value", "{{ $patient->pid }}");
        formEl.querySelector('[name="patient_pid"]') && (formEl.querySelector('[name="patient_pid"]')
          .value = "{{ $patient->pid }}");

        formEl.querySelector('[name="document_type"]')?.setAttribute("value", "prescription");
        formEl.querySelector('[name="document_type"]') && (formEl.querySelector('[name="document_type"]')
          .value = "prescription");

        const fd = new FormData(formEl);
        console.log([...fd.entries()]);

        try {
          console.log("form data:", fd);
          const res = await fetch("{{ route('consultations.store') }}", {
            method: "POST",
            body: fd,
            headers: {
              "X-Requested-With": "XMLHttpRequest",
              Accept: "application/json",
            },
            credentials: "same-origin",
          });

          if (res.status === 422) {
            const data = await res.json().catch(() => null);
            const fieldErrors = data?.errors ?? {};

            Object.keys(fieldErrors).forEach((key) => {
              this.errors[key] = fieldErrors[key]?.[0] ?? "Invalid value.";
              this.touched[key] = true;
            });

            toastr.error("Please fix the highlighted fields.");
            return;
          }

          if (!res.ok) {
            const text = await res.text().catch(() => "");
            console.error("Server error:", res.status, text);

            toastr.error(
              res.status === 419 ?
              "Session expired. Refresh the page and try again." :
              "Something went wrong. Please try again."
            );
            return;
          }

          await res.json().catch(() => ({}));

          toastr.success("Prescription created!");
          window.refreshDocumentsTable?.();

          // reset everything
          formEl.reset();

          this.form.created_at = "";
          this.medicine_list = [];
          this.resetMedicine();
          this.errors = {};
          this.touched = {};
          this.open = false;

          if (window.documentsTable) {
            documentsTable.ajax.reload(null, false);
          }

          document
            .querySelector('[data-modal-close="create-prescription"]')
            ?.click();
        } catch (err) {
          console.error("Network error:", err);
          toastr.error("Network error. Check your connection.");
        }
      },
    }));
  });
</script>
