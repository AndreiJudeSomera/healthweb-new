// resources/js/pages/appointment-create-modal.js
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("record_add_form");
  const doctorSelect = document.getElementById("attended_by");
  const patientSelect = document.getElementById("patient_pid");
  const dateInput = document.getElementById("appointment_date");
  const timeSelect = document.getElementById("appointment_time");
  const submitBtn = form?.querySelector('button[type="submit"]');

  const guestToggle = document.getElementById("appt_no_record");
  let patientTomSelect = null;

  if (!form || !doctorSelect || !dateInput || !timeSelect) return;

  const isGuestMode = () => guestToggle?.checked ?? false;

  const resetGuestToggle = () => {
    if (guestToggle && guestToggle.checked) {
      guestToggle.checked = false;
      guestToggle.dispatchEvent(new Event("change"));
    }
  };

  // ---------- helpers ----------
  const resetSelect = (selectEl, message) => {
    selectEl.innerHTML = "";
    const opt = document.createElement("option");
    opt.textContent = message;
    opt.disabled = true;
    opt.selected = true;
    selectEl.appendChild(opt);
  };

  const csrf = () =>
    document.querySelector('meta[name="csrf-token"]')?.content ?? "";

  const extractErrorMessage = (data) => {
    if (!data) return "Request failed";
    if (data.message) return data.message;

    if (data.errors && typeof data.errors === "object") {
      const firstKey = Object.keys(data.errors)[0];
      const firstMsg = data.errors[firstKey]?.[0];
      if (firstMsg) return firstMsg;
    }

    return "Request failed";
  };

  // ---------- date rules ----------
  const setMinToday = () => {
    dateInput.min = new Date().toLocaleDateString("en-CA");
  };

  // ---------- doctors ----------
  async function loadDoctors() {
    resetSelect(doctorSelect, "Loading doctors...");

    try {
      const res = await fetch("/doctors", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        toastr?.error?.(extractErrorMessage(data));
        resetSelect(doctorSelect, "Failed to load doctors");
        return;
      }

      const doctors = Array.isArray(data) ? data : (data?.data ?? []);

      if (!Array.isArray(doctors) || doctors.length === 0) {
        resetSelect(doctorSelect, "No doctors found");
        return;
      }

      // allow unassigned (attended_by is nullable)
      doctorSelect.innerHTML = `<option value="" selected>Unassigned</option>`;

      doctors.forEach((d) => {
        const username = d?.user?.username;
        const opt = document.createElement("option");
        opt.value = String(d.user_id);
        opt.textContent = username ? `Dr. ${username}` : `Doctor #${d.user_id}`;
        doctorSelect.appendChild(opt);
      });
    } catch (err) {
      console.error(err);
      resetSelect(doctorSelect, "Error loading doctors");
    }
  }

  // load patients

  async function loadPatients() {
    if (patientTomSelect) {
      patientTomSelect.destroy();
      patientTomSelect = null;
    }

    patientSelect.innerHTML = `<option value="" disabled selected>Loading patients...</option>`;

    try {
      const res = await fetch("/patients/records", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        toastr?.error?.(extractErrorMessage(data));
        patientSelect.innerHTML = `<option value="" disabled selected>Failed to load patients</option>`;
        return;
      }

      const patients = Array.isArray(data) ? data : (data?.data ?? []);

      patientSelect.innerHTML = `<option value="" disabled selected>Select Patient</option>`;

      patients.forEach((p) => {
        const opt = document.createElement("option");
        opt.value = String(p?.pid);
        opt.textContent = `${p.user_first_name} ${p.user_last_name} — ${p.age}y, ${p.gender?.charAt(0).toUpperCase() ?? "?"}`;
        patientSelect.appendChild(opt);
      });

      patientTomSelect = new TomSelect("#patient_pid", {
        placeholder: "Search patient...",
        allowEmptyOption: false,
        create: false,
        sortField: { field: "text", direction: "asc" },
      });
    } catch (err) {
      console.error(err);
      patientSelect.innerHTML = `<option value="" disabled selected>Error loading patients</option>`;
    }
  }

  // ---------- slots ----------
  let lastSlotsRequest = 0;

  async function populateSlots(date) {
    const reqId = ++lastSlotsRequest;
    resetSelect(timeSelect, "Loading available slots...");

    try {
      const res = await fetch(
        `/appointments/available-slots?date=${encodeURIComponent(date)}`,
        { headers: { Accept: "application/json" }, credentials: "same-origin" }
      );

      // ignore stale response
      if (reqId !== lastSlotsRequest) return;

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        toastr?.error?.(extractErrorMessage(data));
        resetSelect(timeSelect, "Failed to load slots");
        return;
      }

      if (!data?.slots?.length) {
        resetSelect(timeSelect, "No available slots");
        return;
      }

      resetSelect(timeSelect, "Select Appointment Time");

      data.slots.forEach((slot) => {
        const opt = document.createElement("option");
        opt.value = slot.time; // "08:00:00"
        opt.textContent = `${slot.label} (${slot.remaining}/${slot.capacity} available)`;

        if (slot.is_full) {
          opt.disabled = true;
          opt.textContent += " - FULL";
        }

        timeSelect.appendChild(opt);
      });
    } catch (err) {
      console.error(err);
      resetSelect(timeSelect, "Error loading slots");
    }
  }

  // ---------- init ----------
  setMinToday();
  loadPatients();
  loadDoctors();
  resetSelect(timeSelect, "Select Appointment Time");

  // date change -> validate -> load slots
  dateInput.addEventListener("change", (e) => {
    const selectedDate = e.target.value;

    // clear old options whenever date changes
    resetSelect(timeSelect, "Select Appointment Time");

    if (!selectedDate) return;

    populateSlots(selectedDate);
  });

  // submit -> create appointment
  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const payload = new FormData(form);

    if (!payload.get("appointment_date") || !payload.get("appointment_time")) {
      toastr?.error?.("Please select a date and time.");
      return;
    }

    if (!isGuestMode() && !payload.get("patient_pid")) {
      toastr?.error?.("Please select a patient.");
      return;
    }

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.dataset.originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = "Saving...";
    }

    try {
      const res = await fetch("/appointments", {
        method: "POST",
        headers: {
          Accept: "application/json",
          "X-CSRF-TOKEN": csrf(),
        },
        credentials: "same-origin",
        body: payload,
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        toastr?.error?.(extractErrorMessage(data));
        return;
      }

      form.reset();
      patientTomSelect?.clear();
      setMinToday();
      resetSelect(timeSelect, "Select Appointment Time");
      resetGuestToggle();

      // close modal
      document.querySelector('[data-modal-close="appointment-add"]')?.click();

      // refresh table (if you expose this globally)
      window.refreshAppointmentsTable?.();
    } catch (err) {
      console.error(err);
      toastr?.error?.("Unexpected error");
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        if (submitBtn.dataset.originalText) {
          submitBtn.innerHTML = submitBtn.dataset.originalText;
          delete submitBtn.dataset.originalText;
        }
      }
    }
  });
});
