// resources/js/pages/appointment-create-modal.js
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("record_add_form");
  const doctorSelect = document.getElementById("attended_by");
  const dateInput = document.getElementById("appointment_date");
  const timeSelect = document.getElementById("appointment_time");
  const submitBtn = form?.querySelector('button[type="submit"]');

  if (!form || !doctorSelect || !dateInput || !timeSelect) return;

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
  const setMinTomorrow = () => {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    // local-safe YYYY-MM-DD
    dateInput.min = tomorrow.toLocaleDateString("en-CA");
  };

  const isWeekend = (yyyyMmDd) => {
    const d = new Date(yyyyMmDd);
    const day = d.getDay(); // 0 Sun, 6 Sat
    return day === 0 || day === 6;
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
  setMinTomorrow();
  loadDoctors();
  resetSelect(timeSelect, "Select Appointment Time");

  // date change -> validate -> load slots
  dateInput.addEventListener("change", (e) => {
    const selectedDate = e.target.value;

    // clear old options whenever date changes
    resetSelect(timeSelect, "Select Appointment Time");

    if (!selectedDate) return;

    if (isWeekend(selectedDate)) {
      dateInput.setCustomValidity("Appointments are weekdays only.");
      dateInput.reportValidity();
      dateInput.setCustomValidity("");
      dateInput.value = "";
      return;
    }

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

      toastr?.success?.("Appointment created!");

      form.reset();
      setMinTomorrow();
      resetSelect(timeSelect, "Select Appointment Time");

      // close modal
      document.querySelector('[data-modal-close="record-add"]')?.click();

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
