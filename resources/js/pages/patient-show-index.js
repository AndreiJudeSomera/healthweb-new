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
  return d.toLocaleDateString("en-GB", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
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

export async function copyText(text) {
  if (navigator.clipboard && navigator.clipboard.writeText) {
    await navigator.clipboard.writeText(text);
    return true;
  }

  const ta = document.createElement("textarea");
  ta.value = text;
  ta.setAttribute("readonly", "");
  ta.style.position = "fixed";
  ta.style.left = "-9999px";
  document.body.appendChild(ta);
  ta.select();
  ta.setSelectionRange(0, ta.value.length);

  const ok = document.execCommand("copy");
  document.body.removeChild(ta);

  return ok;
}

const copyPidButton = document.getElementById("copyPidButtonx");
const patientPid = document.getElementById("viewPid")?.getHTML() ?? "";

async function onCopyPidClick(e) {
  if (!patientPid) {
    return;
  }
  if (copyPidButton.dataset.busy === "1") return;

  copyPidButton.dataset.busy = "1";
  copyPidButton.disabled = true;

  try {
    const ok = await copyText(patientPid);
    toastr.clear();
    ok
      ? toastr.success(patientPid, "Copied to clipboard")
      : toastr.error(patientPid, "Failed to copy PID");
  } finally {
    copyPidButton.dataset.busy = "0";
    copyPidButton.disabled = false;
  }
}

if (copyPidButton) {
  copyPidButton.removeEventListener("click", onCopyPidClick);
  copyPidButton.addEventListener("click", onCopyPidClick);
}

const patientsTable = new DataTable("#patientRecordsTable", {
  retrieve: true,
  ajax: {
    url: `/appointments/patient/${patientPid}`,
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
  lengthChange: true,
  paging: true,
  columnDefs: [
    {
      targets: -1,
      className: "flex justify-center",
    },
  ],
  columns: [
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
      render: (row) => row.toUpperCase(),
    },
    {
      data: "status",
      title: "Status",
      render: (row) => row.toUpperCase(),
    },
    {
      data: "doctor",
      title: "Attended By",
      render: (row) =>
        row?.user?.username ? `Dr. ${row.user.username}` : "Unassigned",
    },

    {
      data: null,
      title: "Actions",
      orderable: false,
      searchable: false,
      render: (data, type, row) => {
        // row contains your record
        // store identifiers in data-*
        return `
          <div class="w-full flex flex-row justify-center items-center gap-2">
            <button type="button"
              class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8"
              data-modal-open="view-record"
              data-action="view"
              data-pid="${row.id ?? ""}"
              >
              <i class="fa-solid fa-eye fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8"
              data-modal-open="edit-record"
              data-action="edit"
              data-pid="${row.id ?? ""}">
              <i class="fa-solid fa-pencil fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action px-2 py-1 border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8"
              data-modal-open="delete-record"
              data-action="delete"
              data-pid="${row.id ?? ""}">
              <i class="fa-solid fa-trash fa-sm"></i>
            </button>
          </div>
        `;
      },
    },
  ],
});

const recordsTable = document.getElementById("patientRecordsTable");

if (recordsTable) {
  document
    .querySelector("#patientRecordsTable tbody")
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
