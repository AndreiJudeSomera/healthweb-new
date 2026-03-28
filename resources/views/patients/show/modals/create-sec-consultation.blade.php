<x-modal-garic id="create-sec-consultation" title="Create Consultation" maxWidth="max-w-[750px]">
  @php
    $form_items = [
        "consultation_date" => [
            "name" => "consultation_date",
            "label" => "CONSULTATION DATE",
        ],
        "vitals" => [
            [
                "name" => "wt",
                "label" => "WT",
                "placeholder" => "00",
            ],
            [
                "name" => "bp",
                "label" => "BP",
                "placeholder" => "00/00",
            ],
            [
                "name" => "cr",
                "label" => "CR",
                "placeholder" => "00",
            ],
            [
                "name" => "rr",
                "label" => "RR",
                "placeholder" => "00",
            ],
            [
                "name" => "temperature",
                "label" => "TEMP",
                "placeholder" => "00",
            ],
            [
                "name" => "sp02",
                "label" => "SPO2",
                "placeholder" => "00",
            ],
        ],
        
    ];
  @endphp
  <div class="w-full max-h-[500px] overflow-y-auto flex flex-col gap-4 text-blue-950 pe-6 ps-2 pb-[50px]">
    <div class="w-full flex flex-col items-center justify-center gap-6">
      <img class="w-[200px] -my-12" src="{{ asset("assets/images/logo2.png") }}" alt="Logo">
      <h1 class="font-semibold text-xl">CONSULTATION FORM</h1>
    </div>
    <form class="flex flex-col gap-4" id="create-sec-consultation-form" action="" x-data="consultationForm(@js($patient->pid))"
      @submit.prevent="submit($event)" novalidate>
      @csrf
      <input name="patient_pid" value="{{ $patient->pid }}" hidden readonly>
      <input name="document_type" value="consultation" hidden readonly>
      {{-- Consultation Date --}}
      <div class="flex flex-col gap-1">
        <label class="font-semibold text-sm" for="consultation_date">
          {{ $form_items["consultation_date"]["label"] }}
        </label>

        <input id="consultation_date" :class="inputClass('consultation_date')" type="date" name="consultation_date"
          x-model="form.consultation_date" @blur="touch('consultation_date'); validate('consultation_date')"
          @input="if (touched.consultation_date) validate('consultation_date')" required />

        <p class="text-xs text-red-600" x-show="showError('consultation_date')" x-text="errors.consultation_date"></p>
      </div>

      {{-- Vitals --}}
      <div class="flex flex-row justify-between gap-4">
        @foreach ($form_items["vitals"] as $v)
          <div class="flex flex-col gap-1">
            <label class="font-semibold text-sm" for="{{ $v["name"] }}">{{ $v["label"] }}</label>

            <input id="{{ $v["name"] }}" :class="inputClass('{{ $v["name"] }}')" type="text"
              name="{{ $v["name"] }}" placeholder="{{ $v["placeholder"] ?? "" }}"
              x-model.trim="form.{{ $v["name"] }}"
              @blur="touch('{{ $v["name"] }}'); validate('{{ $v["name"] }}')"
              @input="if (touched['{{ $v["name"] }}']) validate('{{ $v["name"] }}')" required />

            <p class="text-xs text-red-600" x-show="showError('{{ $v["name"] }}')"
              x-text="errors['{{ $v["name"] }}']"></p>
          </div>
        @endforeach
      </div>

    </form>
  </div>
  <div class="mt-6 flex justify-end gap-2">
    <button class="px-6 py-2 bg-gray-600 text-gray-100 rounded-md hover:bg-gray-600/90"
      data-modal-close="create-sec-consultation-form">
      Cancel
    </button>
    <button class="px-6 py-2 bg-blue-950 text-blue-100 rounded-md hover:bg-blue-950/90" type="submit"
      form="create-sec-consultation-form">
      <i class="fa-solid fa-stethoscope me-2"></i>
      Create Consultation
    </button>
  </div>
</x-modal-garic>

<script>
  document.addEventListener("alpine:init", () => {
    Alpine.data("consultationForm", (pid) => ({
      form: {
        patient_pid: pid ?? "",
        document_type: "consultation",
        consultation_date: "",

        wt: "",
        bp: "",
        cr: "",
        rr: "",
        temperature: "",
        sp02: "",

      
      },

      touched: {},
      errors: {},

      // regex + rule config
      rules: {
        consultation_date: [
          (v) => v ? "" : "Consultation date is required.",
        ],

        // weight (kg) allow e.g. 60, 60.5
        wt: [
          (v) => v ? "" : "WT is required.",
          (v) => (/^\d{1,3}(\.\d{1,2})?$/.test(v) ? "" : "WT must be a number (e.g., 60 or 60.5)."),
          (v) => {
            const n = Number(v);
            return (n >= 1 && n <= 500) ? "" : "WT seems out of range.";
          },
        ],

        // BP allow 120/80 up to 3 digits each side
        bp: [
          (v) => v ? "" : "BP is required.",
          (v) => (/^\d{2,3}\/\d{2,3}$/.test(v) ? "" : "BP must be like 120/80."),
        ],

        // CR (pulse) / RR numeric
        cr: [
          (v) => v ? "" : "CR is required.",
          (v) => (/^\d{2,3}$/.test(v) ? "" : "CR must be a whole number."),
          (v) => {
            const n = Number(v);
            return (n >= 20 && n <= 250) ? "" : "CR seems out of range.";
          },
        ],
        rr: [
          (v) => v ? "" : "RR is required.",
          (v) => (/^\d{1,3}$/.test(v) ? "" : "RR must be a whole number."),
          (v) => {
            const n = Number(v);
            return (n >= 5 && n <= 80) ? "" : "RR seems out of range.";
          },
        ],

        // temp allow 36.6
        temperature: [
          (v) => v ? "" : "TEMP is required.",
          (v) => (/^\d{2}(\.\d{1,2})?$/.test(v) ? "" : "TEMP must be like 36.6."),
          (v) => {
            const n = Number(v);
            return (n >= 30 && n <= 45) ? "" : "TEMP seems out of range.";
          },
        ],

        // SpO2 allow 0-100
        sp02: [
          (v) => v ? "" : "SPO2 is required.",
          (v) => (/^\d{1,3}$/.test(v) ? "" : "SPO2 must be a whole number."),
          (v) => {
            const n = Number(v);
            return (n >= 0 && n <= 100) ? "" : "SPO2 must be 0–100.";
          },
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
          "w-full border rounded-md px-3 py-2 focus:outline-none focus:ring-2 ";
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
        // mark all touched, then validate
        Object.keys(this.form).forEach((k) => (this.touched[k] = true));
        return Object.keys(this.form).every((k) => this.validate(k));
      },

      // submit() {
      //   if (!this.validateAll()) return;

      //   // fetch/ajax
      //   console.log("valid payload:", this.form);
      // },
      async submit(e) {
        if (!this.validateAll()) return;

        const formEl = e.target;
        const fd = new FormData(formEl);

        // (optional) clear old server-side errors
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

          // ✅ Laravel validation errors
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

          // ❌ other server errors
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

          // ✅ success
          const data = await res.json().catch(() => ({}));
          toastr.success("Consultation created!");
          window.refreshDocumentsTable?.();

          // reset form UI
          formEl.reset();
          this.errors = {};
          this.touched = {};

          // reset alpine model (optional)
          Object.keys(this.form).forEach((k) => (this.form[k] = ""));

          // ✅ refresh DataTables
          // if (window.documentsTable) {
          //   // if using ajax source:
          //   documentsTable.ajax.reload(null, false); // false = keep current page
          //   // if NOT ajax source (server-rendered rows), then you need to refetch HTML instead
          // } else {
          //   console.warn("documentsTable not found on window.");
          // }

          // optional: close modal
          document
            .querySelector('[data-modal-close="create-sec-consultation"]')
            ?.click();

        } catch (err) {
          console.error("Network error:", err);
          toastr.error("Network error. Check your connection.");
        }
      }
    }));
  });
</script>
