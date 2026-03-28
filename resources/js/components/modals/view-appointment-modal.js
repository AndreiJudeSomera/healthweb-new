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

export async function viewAppointmentModalInit(id) {
  const appt = await fetchAppointment(id);
  console.log("triggeredf");

  document.getElementById("view_appt_date").textContent = formatDate(
    appt.appointment_date
  );
  document.getElementById("view_appt_time").textContent = formatTime(
    appt.appointment_time
  );
  document.getElementById("view_appt_type").textContent = (
    appt.appointment_type || "—"
  ).toUpperCase();
  document.getElementById("view_appt_status").textContent = (
    appt.status || "—"
  ).toUpperCase();
}
