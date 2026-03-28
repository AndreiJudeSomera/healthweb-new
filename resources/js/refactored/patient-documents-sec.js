import DataTable from "datatables.net-buttons-dt";
import responsive from "datatables.net-responsive-dt";
import { editDocumentModalInit } from "../components/modals/edit-document-modal-sec";


const TYPE_LABELS = {
  
  "consultation":        { label: "Consultation",        cls: "bg-blue-100 text-blue-800" },
  "medical-certificate": { label: "Medical Certificate", cls: "bg-emerald-100 text-emerald-800" },
  "referral-letter":     { label: "Referral Letter",     cls: "bg-amber-100 text-amber-800" },
  "prescription":        { label: "Prescription",        cls: "bg-purple-100 text-purple-800" },
};

function typeBadge(type) {
  const t = TYPE_LABELS[type] ?? { label: type ?? "-", cls: "bg-gray-100 text-gray-700" };
  return `<span class="px-2 py-0.5 rounded text-xs font-semibold ${t.cls}">${t.label}</span>`;
}

function formatDate(dateStr) {
  if (!dateStr) return "—";
  const d = new Date(dateStr);
  if (Number.isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString("en-US", {  // ✅ Changed to en-US
    month: "long",     // March
    day: "numeric",    // 25
    year: "numeric",   // 2026
  });
}

function truncate(str, len = 40) {
  if (!str) return "—";
  return str.length > len ? str.slice(0, len) + "..." : str;
}

document.addEventListener("DOMContentLoaded", function () {
  const segments = window.location.pathname.split("/");
  const pid = segments[segments.length - 1];

  const documentsTable = new DataTable("#patientDocumentsTablesSecView", {
    retrieve: true,
    ajax: {
      url: `/consultations/patient/${pid}`,
      dataSrc: "",
      headers: { Accept: "application/json" },
    },

  initComplete: function () {
    // ✅ Default filter = Consultation
    this.api().column(1).search("Consultation").draw();
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
    filtering: true,
    paging: true,
    fixedColumns: true,
      columnDefs: [
          {
            targets: -1,
            className: "flex justify-end",
            width: 10,
          },
          {
            targets: 0,
            width: 10,
            className: "text-center flex justify-end w-10 ",
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
    data: "document_type",
    title: `<div class="ml-5">Record Type</div>`,
    render: (row) => `<div class="ml-5">${typeBadge(row)}</div>`
}
,
      {
        data: "created_at",
        title: "Date",
        render: (row) => formatDate(row),
      },
//      {
//   data: "diagnosis",
//   title: "Diagnosis",
//   render: (row) => {
//     const diagnosis = row ?? "";
//     if (!diagnosis || diagnosis.trim() === "") {
//       return "N/A";
//     }
//     return `<span title="${diagnosis}">${truncate(diagnosis)}</span>`;
//   },
// },
      {
       
  data: null,
  title: "Actions",
  orderable: false,
  searchable: false,
  className: "rounded-tr-md flex justify-left",
  render: (data, type, row) => {
    const docType = row.document_type ?? "consultation";

    // Show the "View PDF" link always
    let actionsHtml = `
      <a type="button"
        class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8 flex justify-center items-center"
        href="/consultations/${row.id ?? ""}/${docType}"
        target="_blank"
        rel="noopener noreferrer"
        title="View PDF">
        <i class="fa-solid fa-eye fa-sm"></i>
      </a>
    `;

    // Only add "Edit" button if docType is consultation
    if (docType === "consultation") {
      actionsHtml += `
        <button type="button"
          class="dt-action px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8 flex justify-center items-center"
          data-modal-open="edit-document-sec"
          data-action="edit"
          data-id="${row.id ?? ""}"
          title="Edit">
          <i class="fa-solid fa-pen fa-sm"></i>
        </button>
      `;
    }

    return `<div class="w-full flex flex-row justify-left items-center gap-2">${actionsHtml}</div>`;
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
    .querySelector("#patientDocumentsTablesSecView tbody")
    .addEventListener("click", async (e) => {
      const btn = e.target.closest(".dt-action");
      if (!btn) return;

      const action = btn.dataset.action;
      const id = btn.dataset.id;


      if (action === "edit") {
        editDocumentModalInit(id);
        window.Modal?.open("edit-document-sec");
      }

      if (action === "add-document") {
        await createConsultationDocumentModalInit(id);
        window.Modal?.open("create-consultation-document");
      }
    });

  const documentSearch = document.getElementById("searchRecords");
  if (documentSearch) {
    documentSearch.addEventListener("input", (e) => {
      documentsTable.search(e.target.value).draw();
    });

    documentSearch.addEventListener("change", (e) => {
      documentsTable.search(e.target.value).draw();
    });
  }

  window.refreshDocumentsTable = () => documentsTable.ajax.reload(null, false);

  // column(1) = Type — used by the filter modal
  window.filterDocumentsTable = (label) => {
    documentsTable.column(1).search(label ? `^${label}$` : "", true, false).draw();
  };
});
