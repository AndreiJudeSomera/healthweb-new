// import DataTable from "datatables.net-dt";
// import "datatables.net-responsive-dt";

// function formatDate(dateStr) {
//   if (!dateStr) return "";
//   const d = new Date(dateStr);
//   if (Number.isNaN(d.getTime())) return dateStr;
//   return d.toLocaleDateString("en-GB", { day: "2-digit", month: "long", year: "numeric" });
// }

// function formatTime(timeStr) {
//   if (!timeStr) return "";
//   const d = new Date(`1970-01-01T${timeStr}`);
//   if (Number.isNaN(d.getTime())) return timeStr;
//   return d.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit", hour12: true });
// }

// function statusBadge(status) {
//   const s = status?.toLowerCase() ?? "";
//   const map = {
//     pending:   "bg-amber-100 text-amber-800",
//     approved:  "bg-emerald-100 text-emerald-800",
//     completed: "bg-blue-100 text-blue-800",
//     cancelled: "bg-red-100 text-red-800",
//   };
//   const cls = map[s] ?? "bg-gray-100 text-gray-700";
//   return `<span class="px-2 py-0.5 rounded text-xs font-semibold ${cls}">${status?.toUpperCase() ?? ""}</span>`;
// }

// const pid = document.getElementById("patientPid")?.value ?? "";

// const table = new DataTable("#patientAppointmentsTable", {
//   retrieve: true,
//   ajax: {
//     url: `/appointments/patient/${pid}`,
//     dataSrc: "",
//     headers: { Accept: "application/json" },
//   },
//   layout: {
//     topStart: null,
//     topEnd: null,
//     bottomStart: "pageLength",
//     bottom2Start: "info",
//     bottomEnd: "paging",
//   },
//   responsive: true,
//   searching: true,
//   columns: [
//     {
//       data: null,
//       title: "#",
//       orderable: false,
//       searchable: false,
//       className: "rounded-tl-md",
//       render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1,
//     },
//     {
//       data: "appointment_type",
//       title: "Type",
//       render: (row) => row?.toUpperCase() ?? "",
//     },
//     {
//       data: "appointment_date",
//       title: "Date",
//       render: (row) => formatDate(row),
//     },
//     {
//       data: "appointment_time",
//       title: "Time",
//       render: (row) => formatTime(row),
//     },
//     {
//       data: "status",
//       title: "Status",
//       render: (row, type) => {
//         if (type === "display") return statusBadge(row);
//         return row?.toUpperCase() ?? "";
//       },
//     },
//     {
//       data: null,
//       title: "Actions",
//       orderable: false,
//       searchable: false,
//       className: "rounded-tr-md",
//       render: (data, type, row) => {
//         const cancellable = ["pending", "approved"].includes(row.status?.toLowerCase());
//         if (!cancellable) return `<span class="text-xs text-gray-400">—</span>`;
//         return `
//           <button type="button"
//             class="flex items-center justify-center border-2 border-red-700 text-red-700 hover:bg-red-50 rounded-md px-3 py-1 text-xs font-medium transition-colors"
//             data-cancel-id="${row.id}">
//             Cancel
//           </button>`;
//       },
//     },
//   ],
// });

// // Search
// document.getElementById("patientSearch")?.addEventListener("input", (e) => {
//   table.search(e.target.value).draw();
// });

// // Cancel action
// document.getElementById("patientAppointmentsTable")?.addEventListener("click", (e) => {
//   const btn = e.target.closest("[data-cancel-id]");
//   if (!btn) return;

//   const id = btn.dataset.cancelId;
//   if (!confirm("Are you sure you want to cancel this appointment?")) return;

//   const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
//   fetch(`/appointments/${id}`, {
//     method: "DELETE",
//     headers: {
//       "Content-Type": "application/json",
//       "X-CSRF-TOKEN": csrfToken,
//       Accept: "application/json",
//     },
//   })
//     .then(async (res) => {
//       const data = await res.json();
//       if (!res.ok) throw new Error(data.message || "Something went wrong.");
//       return data;
//     })
//     .then((data) => {
//       toastr.success(data.message);
//       table.ajax.reload(null, false);
//     })
//     .catch((err) => toastr.error(err.message));
// });

// // Filter globals (used by filter-modal.blade.php)
// window.filterAppointmentsByStatus = (value) => {
//   table.column(4).search(value ? `^${value}$` : "", true, false).draw();
// };

// window.filterAppointmentsByType = (value) => {
//   table.column(1).search(value ? `^${value}$` : "", true, false).draw();
// };

// window.refreshAppointmentsTable = () => table.ajax.reload(null, false);

import DataTable from "datatables.net-dt";
import "datatables.net-responsive-dt";

function formatDate(dateStr) {
  if (!dateStr) return "";
  const d = new Date(dateStr);
  if (Number.isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString("en-GB", { day: "2-digit", month: "long", year: "numeric" });
}

function formatTime(timeStr) {
  if (!timeStr) return "";
  const d = new Date(`1970-01-01T${timeStr}`);
  if (Number.isNaN(d.getTime())) return timeStr;
  return d.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit", hour12: true });
}

function statusBadge(status) {
  const s = status?.toLowerCase() ?? "";
  const map = {
    pending:   "bg-amber-100 text-amber-800",
    approved:  "bg-emerald-100 text-emerald-800",
    completed: "bg-blue-100 text-blue-800",
    cancelled: "bg-red-100 text-red-800",
  };
  const cls = map[s] ?? "bg-gray-100 text-gray-700";
  return `<span class="px-2 py-0.5 rounded text-xs font-semibold ${cls}">${status?.toUpperCase() ?? ""}</span>`;
}

const pid = document.getElementById("patientPid")?.value ?? "";

const table = new DataTable("#patientAppointmentsTable", {
  retrieve: true,
  ajax: {
    url: `/appointments/patient/${pid}`,
    dataSrc: "",
    headers: { Accept: "application/json" },
  },
  layout: {
    topStart: null,
    topEnd: null,
    bottomStart: "pageLength",
    bottom2Start: "info",
    bottomEnd: "paging",
  },
  responsive: true,
  searching: true,
  columns: [
    {
      data: null,
      title: "#",
      orderable: false,
      searchable: false,
      className: "rounded-tl-md",
      render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1,
    },
    {
      data: "appointment_type",
      title: "Type",
      render: (row) => row?.toUpperCase() ?? "",
    },
    {
      data: "appointment_date",
      title: "Date",
      render: (row) => formatDate(row),
    },
    {
      data: "appointment_time",
      title: "Time",
      render: (row) => formatTime(row),
    },
    {
      data: "status",
      title: "Status",
      render: (row, type) => {
        if (type === "display") return statusBadge(row);
        return row?.toUpperCase() ?? "";
      },
    },
    {
      data: null,
      title: "Actions",
      orderable: false,
      searchable: false,
      className: "rounded-tr-md",
      render: (data, type, row) => {
        const cancellable = ["pending", "approved"].includes(row.status?.toLowerCase());
        if (!cancellable) return `<span class="text-xs text-gray-400">—</span>`;
        return `
          <button type="button"
            class="flex items-center justify-center border-2 border-red-700 text-red-700 hover:bg-red-50 rounded-md px-3 py-1 text-xs font-medium transition-colors"
            data-cancel-id="${row.id}">
            Cancel
          </button>`;
      },
    },
  ],
});

// Search
document.getElementById("patientSearch")?.addEventListener("input", (e) => {
  table.search(e.target.value).draw();
});

// Cancel action
document.getElementById("patientAppointmentsTable")?.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-cancel-id]");
  if (!btn) return;

  const id = btn.dataset.cancelId;
  if (!confirm("Are you sure you want to cancel this appointment?")) return;

  const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
  fetch(`/appointments/${id}`, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrfToken,
      Accept: "application/json",
    },
  })
    .then(async (res) => {
      const data = await res.json();
      if (!res.ok) throw new Error(data.message || "Something went wrong.");
      return data;
    })
    .then((data) => {
      toastr.success(data.message);
      table.ajax.reload(null, false);
    })
    .catch((err) => toastr.error(err.message));
});

// Filter globals (used by filter-modal.blade.php)
window.filterAppointmentsByStatus = (value) => {
  table.column(4).search(value ? `^${value}$` : "", true, false).draw();
};

window.filterAppointmentsByType = (value) => {
  table.column(1).search(value ? `^${value}$` : "", true, false).draw();
};

window.refreshAppointmentsTable = () => table.ajax.reload(null, false);