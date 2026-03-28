<x-modal-garic id="create-referral" title="Create Referral" maxWidth="max-w-[750px]">
  @php
    $form_items = [
        [
            "name" => "created_at",
            "label" => "DATE ISSUED",
            "type" => "date",
        ],
        [
            "name" => "referral_to",
            "label" => "RECEPIENT",
            "placeholder" => "Dr. Jose Rizal - Opthalmologist",
            "type" => "text",
        ],
        [
            "name" => "diagnosis",
            "label" => "DIAGNOSIS",
            "placeholder" => "Viral Fever: Differential includes influenza, dengue, and COVID-19.",
            "type" => "text_area",
        ],
        [
            "name" => "referral_reason",
            "label" => "REASON FOR REFERRAL",
            "placeholder" => "Lack of equipment to perform necessary operation.",
            "type" => "text_area",
        ],
    ];
  @endphp

  <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]">
    <div class="w-full flex flex-col items-center justify-center gap-6">
      <img class="w-[200px] -my-12" src="{{ asset("assets/images/logo2.png") }}" alt="Logo">
      <h1 class="font-semibold text-xl">REFERRAL FORM</h1>
    </div>

    <form class="flex flex-col gap-4" id="create-referral-form" action="" x-data="referralForm(@js($patient->pid))"
      @submit.prevent="submit($event)" novalidate>
      @csrf

      <input type="hidden" name="patient_pid" value="{{ $patient->pid }}" />
      <input type="hidden" name="document_type" value="referral-letter" />

      @foreach ($form_items as $fi)
        <div class="flex flex-col gap-1">
          <label class="font-semibold text-sm" for="{{ $fi["name"] }}">{{ $fi["label"] }}</label>

          @if ($fi["type"] === "text_area")
            <textarea id="{{ $fi["name"] }}" :class="inputClass('{{ $fi["name"] }}')" name="{{ $fi["name"] }}"
              placeholder="{{ $fi["placeholder"] }}" rows="4" x-model.trim="form.{{ $fi["name"] }}"
              @blur="touch('{{ $fi["name"] }}'); validate('{{ $fi["name"] }}')"
              @input="if (touched['{{ $fi["name"] }}']) validate('{{ $fi["name"] }}')" required></textarea>
          @else
            <input id="{{ $fi["name"] }}" :class="inputClass('{{ $fi["name"] }}')" type="{{ $fi["type"] }}"
              name="{{ $fi["name"] }}" placeholder="{{ $fi["placeholder"] ?? "" }}"
              x-model.trim="form.{{ $fi["name"] }}"
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
      data-modal-close="create-referral">
      Cancel
    </button>
    <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90" type="submit"
      form="create-referral-form">
      Create Referral
    </button>
  </div>
</x-modal-garic>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data("referralForm", (pid) => ({
      form: {
        patient_id: pid ?? "",
        created_at: "",
        referral_to: "",
        referral_reason: "",
      },

      touched: {},
      errors: {},
      serverError: null,

      rules: {
        created_at: [
          (v) => (v ? "" : "Date issued is required."),
        ],
        referral_to: [
          (v) => (v ? "" : "Recipient is required."),
          (v) => (v.length >= 3 ? "" : "Recipient is too short."),
          (v) => (v.length <= 120 ? "" : "Recipient is too long."),
        ],
        referral_reason: [
          (v) => (v ? "" : "Reason for referral is required."),
          (v) => (v.length >= 5 ? "" : "Please add a bit more detail."),
          (v) => (v.length <= 1000 ? "" : "Reason is too long."),
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
          "w-full border rounded-md px-3 py-2 text-sm text-blue-950/90 focus:outline-none focus:ring-2 ";
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
        Object.keys(this.form).forEach((k) => (this.touched[k] = true));
        return Object.keys(this.form).every((k) => this.validate(k));
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
          toastr.success("Referral created!");

          // if you have a global reload hook
          window.refreshDocumentsTable?.();

          // reset UI
          formEl.reset();
          this.errors = {};
          this.touched = {};
          Object.keys(this.form).forEach((k) => (this.form[k] = ""));

          // refresh DataTables (if you use ajax)
          if (window.documentsTable) {
            documentsTable.ajax.reload(null, false);
          } else {
            console.warn("documentsTable not found on window.");
          }

          // close modal
          document
            .querySelector('[data-modal-close="create-referral"]')
            ?.click();
        } catch (err) {
          console.error("Network error:", err);
          toastr.error("Network error. Check your connection.");
        }
      },
    }));
  });
</script>
