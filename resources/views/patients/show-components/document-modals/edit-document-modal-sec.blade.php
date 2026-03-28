@php
  $isAdmin = auth()->user()?->role === 1;
@endphp

<x-modal-garic id="edit-document-sec" title="Edit Document" maxWidth="max-w-[700px]">
  <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]"
    x-data="editDocumentModal()"
    x-init="init()">

    <div class="w-full flex flex-col items-center justify-center gap-6">
      <img class="w-[200px] -my-12" src="{{ asset('assets/images/logo2.png') }}" alt="Logo">
      <h1 class="font-semibold text-xl" x-text="title"></h1>
    </div>

    <form id="edit_document_form" class="flex flex-col gap-4"
      @submit.prevent="submit()" novalidate>

      {{-- CONSULTATION FIELDS --}}
      <div class="flex flex-col gap-4">
        {{-- Consultation Date --}}
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

        {{-- Vitals --}}
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
      </div>
    </form>

    {{-- Footer --}}
    <div class="mt-6 flex justify-end gap-2">
      <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
              data-modal-close="edit-document-sec" type="button">
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

    // Form data
    form: {
      id: "",
      document_type: "consultation",
      patient_pid: "",
      consultation_date: "",
      created_at: "",
      wt: "", bp: "", cr: "", rr: "", temperature: "", sp02: "",
      doctor_id: "",
    },

    // Validation
    touched: {},
    errors: {},

    vitals: [
      { name: "wt", label: "WT" },
      { name: "bp", label: "BP" },
      { name: "cr", label: "CR" },
      { name: "rr", label: "RR" },
      { name: "temperature", label: "TEMP" },
      { name: "sp02", label: "SPO2" },
    ],

    rules: {
      consultation_date: [v => v ? "" : "Consultation date is required."],
      wt: [
        v => v ? "" : "WT is required.",
        v => (/^\d{1,3}(\.\d{1,2})?$/.test(v) ? "" : "WT must be a number."),
        v => (Number(v) >= 1 && Number(v) <= 500 ? "" : "WT seems out of range."),
      ],
      bp: [
        v => v ? "" : "BP is required.",
        v => (/^\d{2,3}\/\d{2,3}$/.test(v) ? "" : "BP must be like 120/80."),
      ],
      cr: [
        v => v ? "" : "CR is required.",
        v => (/^\d{2,3}$/.test(v) ? "" : "CR must be a number."),
        v => (Number(v) >= 20 && Number(v) <= 250 ? "" : "CR seems out of range."),
      ],
      rr: [
        v => v ? "" : "RR is required.",
        v => (/^\d{1,3}$/.test(v) ? "" : "RR must be a number."),
        v => (Number(v) >= 5 && Number(v) <= 80 ? "" : "RR seems out of range."),
      ],
      temperature: [
        v => v ? "" : "TEMP is required.",
        v => (/^\d{2}(\.\d{1,2})?$/.test(v) ? "" : "TEMP must be like 36.6."),
        v => (Number(v) >= 30 && Number(v) <= 45 ? "" : "TEMP seems out of range."),
      ],
      sp02: [
        v => v ? "" : "SPO2 is required.",
        v => (/^\d{1,3}$/.test(v) ? "" : "SPO2 must be a number."),
        v => (Number(v) >= 0 && Number(v) <= 100 ? "" : "SPO2 must be 0–100."),
      ],
    },

    touch(name) { this.touched[name] = true; },
    showError(name) { return !!this.touched[name] && !!this.errors[name]; },

    inputClass(name) {
      const base = "w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 ";
      return base + (this.showError(name) ? "border-red-600 focus:ring-red-600" : "border-blue-950 focus:ring-blue-950");
    },

    validate(name) {
      const validators = this.rules[name] || [];
      const value = (this.form[name] ?? "").toString().trim();
      for (const fn of validators) {
        const msg = fn(value);
        if (msg) { this.errors[name] = msg; return false; }
      }
      this.errors[name] = "";
      return true;
    },

    validateConsultation() {
      const fields = ["consultation_date", "wt", "bp", "cr", "rr", "temperature", "sp02"];
      let isValid = true;
      fields.forEach(f => { this.touched[f] = true; if (!this.validate(f)) isValid = false; });
      return isValid;
    },

    init() {
      const modalRoot = document.getElementById("edit-document-sec");
      modalRoot?.addEventListener("edit-ready", (e) => {
        const d = e.detail;
        this.title = "EDIT CONSULTATION";

        this.form = {
          id: d.id ?? "",
          document_type: d.document_type ?? "consultation",
          patient_pid: d.patient_pid ?? "",
          consultation_date: d.consultation_date ?? "",
          created_at: d.created_at ? d.created_at.split("T")[0] : "",
          wt: d.wt ?? "", bp: d.bp ?? "", cr: d.cr ?? "", rr: d.rr ?? "",
          temperature: d.temperature ?? "", sp02: d.sp02 ?? "",
          doctor_id: d.doctor_id ?? "",
        };

        this.touched = {};
        this.errors = {};
      });
    },

    async submit() {
      if (!this.form.id) return;

      if (!this.validateConsultation()) {
        toastr?.error("Please fix the highlighted fields.");
        return;
      }

      const payload = {
        document_type: "consultation",
        consultation_date: this.form.consultation_date,
        wt: this.form.wt,
        bp: this.form.bp,
        cr: this.form.cr,
        rr: this.form.rr,
        temperature: this.form.temperature,
        sp02: this.form.sp02,
        doctor_id: this.form.doctor_id,
      };

      try {
        const res = await fetch(`/consultations/${encodeURIComponent(this.form.id)}`, {
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
        document.querySelector('[data-modal-close="edit-document-sec"]')?.click();
        window.refreshDocumentsTable?.();
      } catch (err) {
        console.error(err);
        toastr?.error("Network error. Check your connection.");
      }
    },

  }));
});
</script>