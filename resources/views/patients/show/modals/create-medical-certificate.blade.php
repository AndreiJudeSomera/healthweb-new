<x-modal-garic id="create-medical-certificate" title="Create Medical Certificate" maxWidth="max-w-[750px]">
  @php
    $form_items = [
        [
            "name" => "created_at",
            "label" => "DATE ISSUED",
            "type" => "date",
        ],
        [
            "name" => "consultation_date",
            "label" => "DATE EXAMINED",
            "type" => "date",
        ],
        [
            "name" => "diagnosis",
            "label" => "DIAGNOSIS",
            "type" => "text",
            "placeholder" => "Enter diagnosis here...",
        ],
        [
            "name" => "remarks",
            "label" => "REMARKS",
            "type" => "text_area",
            "placeholder" => "Enter remarks here...",
        ],
        [
            "name" => "referral_to",
            "label" => "REQUESTOR",
            "type" => "text",
            "placeholder" => "Enter who request here...",
        ],
        [
            "name" => "referral_reason",
            "label" => "REASON FOR REQUEST",
            "type" => "text",
            "placeholder" => "Enter reason for request here...",
        ],
    ];
  @endphp

  <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]">
    <div class="w-full flex flex-col items-center justify-center gap-6">
      <img class="w-[200px] -my-12" src="{{ asset("assets/images/logo2.png") }}" alt="Logo">
      <h1 class="font-semibold text-xl">MEDICAL CERTIFICATE FORM</h1>
    </div>

    <form class="flex flex-col gap-4 ms-1 me-4" id="create-medical-certificate-form" action=""
      x-data="medicalCertificateForm(@js($patient->pid))" @submit.prevent="submit($event)" novalidate>
      @csrf

      <input type="hidden" name="patient_pid" value="{{ $patient->pid }}" />
      <input type="hidden" name="document_type" value="medical-certificate" />

      @foreach ($form_items as $fi)
        <div class="w-full flex flex-col gap-1">
          <label class="text-sm font-medium text-gray-700" for="{{ $fi["name"] }}">
            {{ $fi["label"] }}
          </label>

          @if ($fi["type"] === "text_area")
            <textarea class="w-full rounded-md px-2 py-3 sm:text-sm" id="{{ $fi["name"] }}"
              :class="inputClass('{{ $fi["name"] }}')" name="{{ $fi["name"] }}" placeholder="{{ $fi["placeholder"] }}"
              x-model.trim="form.{{ $fi["name"] }}" @blur="touch('{{ $fi["name"] }}'); validate('{{ $fi["name"] }}')"
              @input="if (touched['{{ $fi["name"] }}']) validate('{{ $fi["name"] }}')" required></textarea>
          @elseif ($fi["type"] === "date")
            <input class="rounded-md px-2 py-3 sm:text-sm" id="{{ $fi["name"] }}"
              :class="inputClass('{{ $fi["name"] }}')" type="date" name="{{ $fi["name"] }}"
              x-model="form.{{ $fi["name"] }}"
              @blur="touch('{{ $fi["name"] }}'); validate('{{ $fi["name"] }}')"
              @input="if (touched['{{ $fi["name"] }}']) validate('{{ $fi["name"] }}')" required />
          @else
            <input class="rounded-md px-2 py-3 sm:text-sm" id="{{ $fi["name"] }}"
              :class="inputClass('{{ $fi["name"] }}')" type="{{ $fi["type"] }}" name="{{ $fi["name"] }}"
              placeholder="{{ $fi["placeholder"] }}" x-model.trim="form.{{ $fi["name"] }}"
              @blur="touch('{{ $fi["name"] }}'); validate('{{ $fi["name"] }}')"
              @input="if (touched['{{ $fi["name"] }}']) validate('{{ $fi["name"] }}')" required />
          @endif

          <p class="text-xs text-red-600" x-show="showError('{{ $fi["name"] }}')"
            x-text="errors['{{ $fi["name"] }}']"></p>
        </div>
      @endforeach
    </form>
  </div>

  <div class="mt-6 flex justify-end gap-2">
    <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
      data-modal-close="create-medical-certificate">
      Cancel
    </button>

    <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90" type="submit"
      form="create-medical-certificate-form">
      <i class="fa-solid fa-notes-medical fa-xs me-2"></i>
      Create Medical Certificate
    </button>
  </div>
</x-modal-garic>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data("medicalCertificateForm", (pid) => ({
      form: {
        patient_pid: pid ?? "",
        document_type: "medical-certificate",

        created_at: "",
        consultation_date: "",
        diagnosis: "",
        remarks: "",
        referral_to: "",
        referral_reason: "",
      },

      touched: {},
      errors: {},
      serverError: null,

      rules: {
        created_at: [(v) => (v ? "" : "Date issued is required.")],
        consultation_date: [(v) => (v ? "" : "Date examined is required.")],

        diagnosis: [
          (v) => (v ? "" : "Diagnosis is required."),
          (v) => (v.length >= 2 ? "" : "Diagnosis is too short."),
          (v) => (v.length <= 500 ? "" : "Diagnosis is too long."),
        ],

        remarks: [
          (v) => (v ? "" : "Remarks is required."),
          (v) => (v.length >= 2 ? "" : "Remarks is too short."),
          (v) => (v.length <= 1000 ? "" : "Remarks is too long."),
        ],

        referral_to: [
          (v) => (v ? "" : "Request for is required."),
          (v) => (v.length >= 2 ? "" : "Request for is too short."),
          (v) => (v.length <= 200 ? "" : "Request for is too long."),
        ],

        referral_reason: [
          (v) => (v ? "" : "Reason for request is required."),
          (v) => (v.length >= 2 ? "" : "Reason is too short."),
          (v) => (v.length <= 500 ? "" : "Reason is too long."),
        ],
      },

      touch(name) {
        this.touched[name] = true;
      },

      showError(name) {
        return !!this.touched[name] && !!this.errors[name];
      },

      inputClass(name) {
        const base =
          "w-full border rounded-md px-2 py-3 sm:text-sm focus:outline-none focus:ring-2 ";
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

      validateAll() {
        // only validate the actual inputs, not hidden fields
        const fields = [
          "created_at",
          "consultation_date",
          "diagnosis",
          "remarks",
          "referral_to",
          "referral_reason",
        ];

        fields.forEach((k) => (this.touched[k] = true));
        return fields.every((k) => this.validate(k));
      },

      async submit(e) {
        if (!this.validateAll()) return;

        const formEl = e.target;
        const fd = new FormData(formEl);

        this.serverError = null;

        try {
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
          toastr.success("Medical certificate created!");

          window.refreshDocumentsTable?.();

          // reset UI
          formEl.reset();
          this.errors = {};
          this.touched = {};
          Object.keys(this.form).forEach((k) => (this.form[k] = ""));

          // refresh DataTables
          if (window.documentsTable) {
            documentsTable.ajax.reload(null, false);
          }

          // close modal
          document
            .querySelector('[data-modal-close="create-medical-certificate"]')
            ?.click();
        } catch (err) {
          console.error("Network error:", err);
          toastr.error("Network error. Check your connection.");
        }
      },
    }));
  });
</script>
