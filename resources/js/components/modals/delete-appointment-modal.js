function formatDate(dateStr) {
  if (!dateStr) return "—";
  const d = new Date(dateStr);
  if (Number.isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString("en-GB", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
}

function formatTime(timeStr) {
  if (!timeStr) return "—";
  const d = new Date(`1970-01-01T${timeStr}`);
  if (Number.isNaN(d.getTime())) return timeStr;
  return d.toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    hour12: true,
  });
}

async function fetchAppointment(id) {
  const res = await fetch(`/appointments/${encodeURIComponent(id)}`, {
    headers: { Accept: "application/json" },
    credentials: "same-origin",
  });
  if (!res.ok) throw new Error("Fetch failed");
  return res.json();
}

export async function deleteAppointmentModalInit(id) {
  const appt = await fetchAppointment(id);

  document.getElementById("delete_appt_id").value = appt.id;
  document.getElementById("delete_appt_date").textContent = formatDate(
    appt.appointment_date
  );
  document.getElementById("delete_appt_time").textContent = formatTime(
    appt.appointment_time
  );

  const form = document.getElementById("delete_appointment_form");
  if (!form) return;

  if (form._handler) {
    form.removeEventListener("submit", form._handler);
    delete form._handler;
  }

  const handler = async (e) => {
    e.preventDefault();

    try {
      const apptId = document.getElementById("delete_appt_id").value;

      const res = await fetch(`/appointments/${encodeURIComponent(apptId)}`, {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        credentials: "same-origin",
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok) throw new Error(data.message || "Delete failed");

      toastr.success("Appointment cancelled!");
      document.querySelector('[data-modal-close="delete-record"]')?.click();
      window.refreshAppointmentsTable?.();
    } catch (err) {
      toastr.error(err.message || "Delete failed");
    }
  };

  form._handler = handler;
  form.addEventListener("submit", handler);
}
