import jszip from "jszip";
import pdfmake from "pdfmake";
import DataTable from "datatables.net-dt";
import "datatables.net-buttons-dt";
import "datatables.net-buttons/js/buttons.colVis.mjs";
import "datatables.net-buttons/js/buttons.html5.mjs";
import "datatables.net-buttons/js/buttons.print.mjs";
import "../components/modals/modal";
import { editModalInit } from "../components/modals/edit-patient-modal";
import { deleteModalInit } from "../components/modals/delete-patient-modal";

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
function toTitleCase(str) {
  if (!str) return "";
  return str
    .split(" ") // split words by space
    .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join(" ");
}
const patientsTable = new DataTable("#patientsTable", {
  ajax: {
    url: "/patients/records",
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
  order: [[5, "desc"]],
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
  <div>${meta.row + meta.settings._iDisplayStart + 1}</div>
`,
    },
   {
  data: "user_last_name", 
  title: `<div class="2">Last Name</div>`,
  render: (row) => `<div class="2">${toTitleCase(row)}</div>`, 
  
},
    { data: "user_first_name",
       title: "First Name",
        render: (row) => `<div class="2">${toTitleCase(row)}</div>`, 
      
      },
    { data: "age",
       title: "Age",
       
    },
    {
      data: "gender",
      title: "Sex",
      render: (row) => row.charAt(0).toUpperCase() + row.slice(1),
    },
    {
      data: "created_at",
      title: "Date Added",
      render: (row) => formatDate(row),
    },

    {
      data: "patient_type",
      title: "Patient Type",
      visible: false,
      searchable: true,
      render: (row) => row ?? "",
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
            <a
              class="dt-action flex items-center justify-center border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8"
              href="/patients/${row.pid ?? ""}"
              >
              <i class="fa-solid fa-eye fa-sm"></i>
            </a>

            <button type="button"
              class="dt-action flex items-center justify-center border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8"
              data-modal-open="edit-patient"
              data-action="edit"
              data-pid="${row.pid ?? ""}">
              <i class="fa-solid fa-pencil fa-sm"></i>
            </button>

            <button type="button"
              class="dt-action flex items-center justify-center border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8"
              data-modal-open="delete-patient"
              data-action="delete"
              data-pid="${row.pid ?? ""}">
              <i class="fa-solid fa-trash fa-sm"></i>
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
        <div class="flex items-center justify-end h-full mx-1 my-1 ">
          ${i + 1}
        </div>
      `;
      });
  }, rowCallback: function(row, data, index) {
    $(row).addClass('border-b border-blue-200 last:border-b-0');
  },
});

document
  .querySelector("#patientsTable tbody")
  .addEventListener("click", (e) => {
    const btn = e.target.closest(".dt-action");
    if (!btn) return;

    const action = btn.dataset.action;
    const pid = btn.dataset.pid;

    if (action === "view") {
      viewModalInit(pid);
    } else if (action === "edit") {
      editModalInit(pid);
    } else if (action === "delete") {
      deleteModalInit(pid);
    }
  });

const patientSearch = document.getElementById("patientSearch");
patientSearch.addEventListener("input", (e) => {
  patientsTable.search(e.target.value).draw();
});

const newPatientForm = document.getElementById("patient_add_form");
// newPatientForm.addEventListener("submit", (e) => {
//   toastr.success("New patient successfully added", "Success");
// });

newPatientForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const formData = new FormData(newPatientForm);

  try {
    const res = await fetch("/patients", {
      method: "POST",
      headers: {
        "Accept": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: formData
    });

    const data = await res.json();

    if (!res.ok) {
      throw data;
    }

    toastr.success(data.message, "Success");
    
 
    window.location.reload();

  } catch (err) {

    if (err.errors) {
      Object.keys(err.errors).forEach(field => {
        toastr.error(err.errors[field][0], "Validation Error");
      });
      return;
    }

    toastr.error(err.message || "Something went wrong", "Error");
  }
});
window.filterPatientsBySex = (value) => {
  patientsTable.column(4).search(value ? `^${value}$` : "", true, false).draw();
};

window.filterPatientsByType = (value) => {
  patientsTable.column(6).search(value ? `^${value}$` : "", true, false).draw();
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
