<x-modal-garic id="create-consultation-document" title="Generate Document" maxWidth="max-w-[700px]">
  <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]"
    x-data="consultationDocumentModal()"
    x-init="init()">

    <div class="w-full flex flex-col items-center justify-center gap-6">
      <img class="w-[200px] -my-12" src="{{ asset("assets/images/logo2.png") }}" alt="Logo">
      <h1 class="font-semibold text-xl">GENERATE DOCUMENT</h1>
    </div>

    <form id="create-consultation-document-form" class="flex flex-col gap-4"
      @submit.prevent="submit($event)" novalidate>
      @csrf

      {{-- Hidden fields populated by JS --}}
      <input type="hidden" name="patient_pid" :value="patient_pid" />
      <input type="hidden" name="linked_consultation_id" :value="linked_consultation_id" />

      {{-- Document Type --}}
      <div class="flex flex-col gap-1">
        <label class="font-semibold text-sm" for="cdm_document_type">DOCUMENT TYPE</label>
        <select id="cdm_document_type" name="document_type"
          :class="inputClass('document_type')"
          x-model="form.document_type"
          @change="touch('document_type'); validate('document_type')"
          required>
          <option value="" disabled>Select document type…</option>
          <option value="medical-certificate">Medical Certificate</option>
          <option value="referral-letter">Referral Letter</option>
        </select>
        <p class="text-xs text-red-600" x-show="showError('document_type')" x-text="errors.document_type"></p>
      </div>

      {{-- Date Issued --}}
      <div class="flex flex-col gap-1">
        <label class="font-semibold text-sm" for="cdm_created_at">DATE ISSUED</label>
        <input id="cdm_created_at" type="date" name="created_at"
          :class="inputClass('created_at')"
          x-model="form.created_at"
          @blur="touch('created_at'); validate('created_at')"
          @input="if (touched.created_at) validate('created_at')"
          required />
        <p class="text-xs text-red-600" x-show="showError('created_at')" x-text="errors.created_at"></p>
      </div>

      {{-- Date Examined — Medical Certificate only --}}
      <div class="flex flex-col gap-1" x-show="form.document_type === 'medical-certificate'">
        <label class="font-semibold text-sm" for="cdm_consultation_date">DATE EXAMINED</label>
        <input id="cdm_consultation_date" type="date" name="consultation_date"
          class="w-full border border-blue-950/30 rounded-md px-3 py-2 bg-gray-50 text-gray-500 cursor-not-allowed"
          x-model="form.consultation_date"
          readonly />
      </div>

      {{-- Diagnosis — pre-filled, both types --}}
      <div class="flex flex-col gap-1" x-show="form.document_type !== ''">
        <label class="font-semibold text-sm" for="cdm_diagnosis">DIAGNOSIS</label>
        <textarea id="cdm_diagnosis" name="diagnosis" rows="3"
          class="w-full border border-blue-950/30 rounded-md px-3 py-2 bg-gray-50 text-gray-500 cursor-not-allowed"
          x-model.trim="form.diagnosis"
          readonly></textarea>
      </div>

      {{-- Recipient / Request For (referral_to) — both types, label changes --}}
      <div class="flex flex-col gap-1" x-show="form.document_type !== ''">
        <label class="font-semibold text-sm" for="cdm_referral_to"
          x-text="form.document_type === 'referral-letter' ? 'RECIPIENT' : 'REQUESTOR'"></label>
        <input id="cdm_referral_to" type="text" name="referral_to"
          :class="inputClass('referral_to')"
          x-model.trim="form.referral_to"
          :placeholder="form.document_type === 'referral-letter' ? 'Type recipient here...' : 'Type requestor here...'"
          @blur="touch('referral_to'); validate('referral_to')"
          @input="if (touched.referral_to) validate('referral_to')" />
          
          <p class="text-xs text-gray-500 mt-1" x-show="form.document_type === 'referral-letter'">
            Examples: <span class="font-medium">Dr. Jose Rizal</span>, <span class="font-medium">PJG Hospital</span>, <span class="font-medium">City Clinic</span>
          </p>

                
        <p class="text-xs text-gray-500 mt-1" x-show="form.document_type !== 'referral-letter'">
          Examples: <span class="font-medium">School</span>, <span class="font-medium">Employer</span>, <span class="font-medium">Parent/Guardian</span>, <span class="font-medium">Insurance Company</span>, <span class="font-medium">HR Department</span>, <span class="font-medium">Government Office</span>
        </p>
              <p class="text-xs text-red-600" x-show="showError('referral_to')" x-text="errors.referral_to"></p>
            </div>

      

      {{-- Reason (referral_reason) — both types, label changes --}}
      <div class="flex flex-col gap-1" x-show="form.document_type !== ''">
        <label class="font-semibold text-sm" for="cdm_referral_reason"
          x-text="form.document_type === 'referral-letter' ? 'REASON FOR REFERRAL' : 'REASON FOR REQUEST'"></label>
        <textarea id="cdm_referral_reason" name="referral_reason" rows="3"
          :class="inputClass('referral_reason')"
          x-model.trim="form.referral_reason"
          :placeholder="form.document_type === 'referral-letter' ? 'Type reason...' : 'Type reason...'"
          @blur="touch('referral_reason'); validate('referral_reason')"
          @input="if (touched.referral_reason) validate('referral_reason')"></textarea>
          <!-- Visual options -->
  <p class="text-xs text-gray-500 mt-1" x-show="form.document_type === 'referral-letter'">
    Examples: <span class="font-medium">Specialist Consultation</span>, <span class="font-medium">Lack of Equipments</span>
  </p>

  <!-- Request Examples -->
  <p class="text-xs text-gray-500 mt-1" x-show="form.document_type !== 'referral-letter'">
    Examples:  <span class="font-medium">Absence</span>, <span class="font-medium">Work Leave</span>, <span class="font-medium">Insurance Claim</span>, <span class="font-medium">Travel Clearance</span>, <span class="font-medium">Medical Requirement</span>, <span class="font-medium">Return-to-Work Clearance</span>
  </p>

  <p class="text-xs text-red-600" x-show="showError('referral_to')" x-text="errors.referral_to"></p>
        <p class="text-xs text-red-600" x-show="showError('referral_reason')" x-text="errors.referral_reason"></p>
      </div>

      {{-- Remarks — Medical Certificate only --}}
      <div class="flex flex-col gap-1" x-show="form.document_type === 'medical-certificate'">
        <label class="font-semibold text-sm" for="cdm_remarks">REMARKS</label>
        <textarea id="cdm_remarks" name="remarks" rows="3"
          :class="inputClass('remarks')"
          x-model.trim="form.remarks"
          placeholder="Remarks…"
          @blur="touch('remarks'); validate('remarks')"
          @input="if (touched.remarks) validate('remarks')"></textarea>
        <p class="text-xs text-red-600" x-show="showError('remarks')" x-text="errors.remarks"></p>
      </div>
    </form>
  </div>

  {{-- Footer outside scroll area --}}
  <div class="mt-6 flex justify-end gap-2"
    x-data
    x-ref="footer">
    <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
      data-modal-close="create-consultation-document" type="button">
      Cancel
    </button>
    <button class="px-6 py-2 bg-emerald-700 text-emerald-100 rounded-md hover:bg-emerald-700/90"
      type="submit" form="create-consultation-document-form">
      <i class="fa-solid fa-file-medical fa-xs me-2"></i>
      Generate Document
    </button>
  </div>
</x-modal-garic>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data("consultationDocumentModal", () => ({
      linked_consultation_id: "",
      patient_pid: "",

      form: {
        document_type: "",
        created_at: "",
        consultation_date: "",
        diagnosis: "",
        referral_to: "",
        referral_reason: "",
        remarks: "",
      },

      touched: {},
      errors: {},

      init() {
        const modalRoot = document.getElementById("create-consultation-document");
        modalRoot?.addEventListener("consultation-ready", (e) => {
          const c = e.detail;
          this.linked_consultation_id = c.id ?? "";
          this.patient_pid = c.patient_pid ?? "";
          // Pre-fill from consultation
          this.form.diagnosis      = c.diagnosis       ?? "";
          this.form.referral_to    = c.referral_to     ?? "";
          this.form.referral_reason = c.referral_reason ?? "";
          this.form.consultation_date = c.consultation_date ?? "";
          // Reset user-supplied fields
          this.form.document_type = "";
          this.form.created_at    = "";
          this.form.remarks       = "";
          this.touched = {};
          this.errors  = {};
        });
      },

      rules: {
        document_type:     [(v) => (v ? "" : "Please select a document type.")],
        created_at:        [(v) => (v ? "" : "Date issued is required.")],
        consultation_date: [(v) => (v ? "" : "Date examined is required.")],
        diagnosis: [
          (v) => (v ? "" : "Diagnosis is required."),
          (v) => (v.length >= 2 ? "" : "Diagnosis is too short."),
        ],
        referral_to: [
          (v) => (v ? "" : "This field is required."),
          (v) => (v.length >= 2 ? "" : "Too short."),
        ],
        referral_reason: [
          (v) => (v ? "" : "This field is required."),
          (v) => (v.length >= 5 ? "" : "Please add a bit more detail."),
        ],
        remarks: [
          (v) => (v ? "" : "Remarks are required."),
          (v) => (v.length >= 2 ? "" : "Too short."),
        ],
      },

      touch(name) { this.touched[name] = true; },

      showError(name) { return !!this.touched[name] && !!this.errors[name]; },

      inputClass(name) {
        const base = "w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 ";
        const ok   = "border-blue-950 focus:ring-blue-950";
        const bad  = "border-red-600 focus:ring-red-600";
        return base + (this.showError(name) ? bad : ok);
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

      validateAll() {
        const common = ["document_type", "created_at", "referral_to", "referral_reason"];
        const medCert = this.form.document_type === "medical-certificate"
          ? ["remarks"]
          : [];
        const fields = [...common, ...medCert];
        fields.forEach((k) => (this.touched[k] = true));
        return fields.every((k) => this.validate(k));
      },

      async submit(e) {
        if (!this.validateAll()) return;

        const fd = new FormData(e.target);

        try {
          const res = await fetch("{{ route('consultations.store') }}", {
            method: "POST",
            body: fd,
            headers: {
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
              "X-Requested-With": "XMLHttpRequest",
              Accept: "application/json",
            },
            credentials: "same-origin",
          });

          if (res.status === 422) {
            const data = await res.json().catch(() => null);
            Object.entries(data?.errors ?? {}).forEach(([k, v]) => {
              this.errors[k] = v?.[0] ?? "Invalid value.";
              this.touched[k] = true;
            });
            toastr?.error("Please fix the highlighted fields.");
            return;
          }

          if (!res.ok) {
            toastr?.error(res.status === 419
              ? "Session expired. Refresh and try again."
              : "Something went wrong. Please try again.");
            return;
          }

          const data = await res.json().catch(() => ({}));
          const docId = data?.consultation?.id ?? data?.id;
          const type  = this.form.document_type;

          toastr?.success(
            type === "medical-certificate" ? "Medical certificate generated!" : "Referral letter generated!"
          );

          // Offer quick link to view the PDF
          if (docId) {
            setTimeout(() => {
              toastr?.info(
                `<a href="/consultations/${docId}/${type}" target="_blank" class="underline">Click to view PDF</a>`,
                "View Generated Document",
                { timeOut: 8000, closeButton: true, escapeHtml: false }
              );
            }, 400);
          }

          document.querySelector('[data-modal-close="create-consultation-document"]')?.click();
          window.refreshDocumentsTable?.();
        } catch (err) {
          console.error("Network error:", err);
          toastr?.error("Network error. Check your connection.");
        }
      },
    }));
  });
</script>
