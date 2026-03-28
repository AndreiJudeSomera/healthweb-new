async function fetchAppointment(id) {
  const res = await fetch(`/appointments/${encodeURIComponent(id)}`, {
    headers: { Accept: "application/json" },
    credentials: "same-origin",
  });
  if (!res.ok) throw new Error("Fetch failed");
  return res.json();
}

function setValue(id, value) {
  const el = document.getElementById(id);
  if (!el) return;
  el.value = value === "" || value === null ? "" : value;
}

const doctorSelect = document.getElementById("edit_attended_by");

async function loadDoctors() {
  const resetSelect = (selectEl, message) => {
    selectEl.innerHTML = "";
    const opt = document.createElement("option");
    opt.textContent = message;
    opt.disabled = true;
    opt.selected = true;
    selectEl.appendChild(opt);
  };

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
    resetSelect(doctorSelect, "Select Doctor");

    doctors.forEach((d) => {
      const username = d?.user?.username;
      const opt = document.createElement("option");
      opt.value = String(d.user_id);
      opt.textContent = username ? `Dr. ${username}` : `Doctor #${d.user_id}`;
      doctorSelect.appendChild(opt);
    });

    const opt = document.createElement("option");
    opt.value = "";
    opt.textContent = "Unassigned";
    opt.id = "unassigned_doctor";
    doctorSelect.appendChild(opt);
  } catch (err) {
    console.error(err);
    resetSelect(doctorSelect, "Error loading doctors");
  }
}

export async function editAppointmentModalInit(id) {
  const appt = await fetchAppointment(id);

  setValue("edit_appt_id", appt.id);
  setValue("edit_appt_pid", appt.patient_pid);
  setValue("edit_appointment_type", appt.appointment_type);
  setValue("edit_status", appt.status);

  await loadDoctors();
  setValue("edit_attended_by", appt.attended_by ?? "");

  const form = document.getElementById("edit_appointment_form");
  if (!form) return;

  // remove previous handler if exists
  if (form._handler) {
    form.removeEventListener("submit", form._handler);
    delete form._handler;
  }

  const handler = async (e) => {
    e.preventDefault();

    const payload = Object.fromEntries(new FormData(form));
    const apptId = payload.id;

    // remove id from payload (optional)
    delete payload.id;

    try {
      const res = await fetch(`/appointments/${encodeURIComponent(apptId)}`, {
        method: "PUT",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        credentials: "same-origin",
        body: JSON.stringify(payload),
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok) throw new Error(data.message || "Update failed");

      toastr.success("Appointment updated!");
      document.getElementById("editAppointmentCloseBtn")?.click();
      window.refreshAppointmentsTable?.();
    } catch (err) {
      toastr.error(err.message || "Update failed");
    }
  };

  form._handler = handler;
  form.addEventListener("submit", handler);
}
