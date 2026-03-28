import jszip from "jszip";
import pdfmake from "pdfmake";
import DataTable from "datatables.net-dt";
import "datatables.net-buttons-dt";
import "datatables.net-buttons/js/buttons.colVis.mjs";
import "datatables.net-buttons/js/buttons.html5.mjs";
import "datatables.net-buttons/js/buttons.print.mjs";
import "datatables.net-responsive-dt";
import "../components/modals/modal";
import { viewAppointmentModalInit } from "../components/modals/view-appointment-modal";
import { editAppointmentModalInit } from "../components/modals/edit-appointment-modal";
import { deleteAppointmentModalInit } from "../components/modals/delete-appointment-modal";

DataTable.Buttons.jszip(jszip);
DataTable.Buttons.pdfMake(pdfmake);
export function formatDate(dateStr) {
  if (!dateStr) return "";

  const d = new Date(dateStr);
  if (Number.isNaN(d.getTime())) return dateStr;

  let month = d.toLocaleString("en-US", { month: "long" });
  // Convert to SentenceCase: first letter uppercase, rest lowercase
  month = month.charAt(0).toUpperCase() + month.slice(1).toLowerCase();

  const day = String(d.getDate()).padStart(2, "0");
  const year = d.getFullYear();

  return `${month} ${day}, ${year}`;
}

export function formatTime(timeStr) {
  if (!timeStr) return "";

  const d = new Date(`1970-01-01T${timeStr}`);

  if (Number.isNaN(d.getTime())) return timeStr;

  return d.toLocaleTimeString("en-US", {
    hour: "2-digit",
    minute: "2-digit",
    hour12: true,
  });
}

const patientsTable = new DataTable("#patientAppointmentsTable", {
  retrieve: true,
  ajax: {
    url: `/appointments`,
    dataSrc: "",
    headers: { Accept: "application/json" },
  },  initComplete: function () {
    // ✅ Default filter = Consultation
    this.api().column(5).search("pending").draw();
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
  lengthChange: true,
  paging: true,
  order: [[2, "desc"]],
  columnDefs: [
    {
      targets: -1,
      className: "flex justify-center",
    },
  ],
  columns: [
      {
      data: null,
      title: "#",
      orderable: false,
      searchable: false,
     className: "text-center bg-gray-100 rounded-tl-md",
  render: (data, type, row, meta) => `
    <div class="mx-1 my-1">${meta.row + meta.settings._iDisplayStart + 1}</div>
  `,
    },
    {
    data: "for_patient",
  title: `<div class="ml-3">Patient</div>`,
  render: (row) => `<div class="ml-3">${row}</div>`,
    },
    {
      data: "appointment_date",
      title: "Appointment Date",
      render: (row) => formatDate(row),
    },
    {
      data: "appointment_time",
      title: "Time",
      render: (row) => formatTime(row),
    },
    {
      data: "appointment_type",
      title: "Type",
      render: (row) => row.charAt(0).toUpperCase() + row.slice(1),
    },
{
  data: "status",
  title: "Status",
  render: (row, type) => {

    const statusStyles = {
      pending: "bg-amber-100 text-amber-800",
      approved: "bg-emerald-100 text-emerald-800",
      completed: "bg-blue-100 text-blue-800",
      cancelled: "bg-red-100 text-red-800"
    };

    // For filtering/searching
    if (type === "filter" || type === "search") {
      return row?.toUpperCase() ?? "";
    }

    
    // For display
    const status = row.charAt(0).toUpperCase() + row.slice(1);
    const cls = statusStyles[row] || "bg-gray-100 text-gray-800";

    return `<span class="px-2 py-1 rounded-md text-xs font-medium ${cls}">
              ${status}
            </span>`;
  }
},

    {
  data: "doctor",
  title: "Attended By",
  render: (row) => {
    if (!row?.user?.username) return "Unassigned";

    const firstName = row.user.username.split(" ")[0];
    return `Dr. ${firstName}`;
  }
},

    {
      data: null,
      title: "Actions",
      orderable: false,
      searchable: false,
      className: "rounded-tr-md",
      render: (data, type, row) => {
        // row contains your record
        // store identifiers in data-*
        return `
          <div class="w-full flex flex-row justify-start items-center gap-2">
            <button type="button"
              class="dt-action flex items-center justify-center border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8"
              data-modal-open="view-record"
              data-action="view"
              data-pid="${row.id ?? ""}"
              >
              <i class="fa-solid fa-eye fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action flex items-center justify-center border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8"
              data-modal-open="edit-record"
              data-action="edit"
              data-pid="${row.id ?? ""}">
              <i class="fa-solid fa-pencil fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action flex items-center justify-center border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8"
              data-modal-open="delete-record"
              data-action="delete"
              title="Cancel appointment"
              data-pid="${row.id ?? ""}">
              <i class="fa-solid fa-ban fa-sm"></i>
            </button>
          </div>
        `;
      },
    },
  ],
   drawCallback: function () {
    this.api()
      .column(0, { search: "applied", order: "applied" })
      .nodes()
      .each((cell, i) => {
        cell.innerHTML = `
        <div class="flex items-center justify-end h-full mx-1 my-1">
          ${i + 1}
        </div>
      `;
      });
  },
   rowCallback: function(row, data, index) {
    $(row).addClass('border-b border-blue-200 last:border-b-0');
  },
});


const recordsTable = document.getElementById("patientAppointmentsTable");

if (recordsTable) {
  document
    .querySelector("#patientAppointmentsTable tbody")
    .addEventListener("click", (e) => {
      const btn = e.target.closest(".dt-action");
      if (!btn) return;

      const action = btn.dataset.action;
      const pid = btn.dataset.pid;

      if (action === "view") {
        viewAppointmentModalInit(pid);
      } else if (action === "edit") {
        editAppointmentModalInit(pid);
      } else if (action === "delete") {
        deleteAppointmentModalInit(pid);
      }
    });
}

const patientSearch = document.getElementById("patientSearch");

if (patientSearch) {
  patientSearch.addEventListener("input", (e) => {
    patientsTable.search(e.target.value).draw();
  });
}

const newRecordForm = document.getElementById("record_add_form");

if (newRecordForm) {
  newRecordForm.addEventListener("submit", (e) => {
    toastr.success("New record successfully added", "Success");
  });
}

window.refreshAppointmentsTable = () => patientsTable.ajax.reload(null, false);

window.filterAppointmentsByStatus = (value) => {
  patientsTable.column(5).search(value ? `^${value}$` : "", true, false).draw();
};

window.filterAppointmentsByType = (value) => {
  patientsTable.column(4).search(value ? `^${value}$` : "", true, false).draw();
};

toastr.options = {
  closeButton: true,
  debug: true,
  newestOnTop: true,
  progressBar: true,
  positionClass: "toast-bottom-right",
  preventDuplicates: true,
  onclick: null,
  showDuration: "300",
  hideDuration: "1000",
  timeOut: "5000",
  extendedTimeOut: "1000",
  showEasing: "swing",
  hideEasing: "linear",
  showMethod: "fadeIn",
  hideMethod: "fadeOut",
};
