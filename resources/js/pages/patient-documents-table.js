import DataTable from "datatables.net-dt";
import { formatDate } from "./patient-show-index";
import { deleteDocumentModalInit } from "../components/modals/delete-document-modal";
import { initGenerateDocumentModal } from "../components/modals/generate-document-modal";
import { editDocumentModalInit } from "../components/modals/edit-document-modal";

export function initDocumentsTable(pid) {
  initGenerateDocumentModal();
  const documentsTable = new DataTable("#patientDocumentsTable", {
    retrieve: true,
    ajax: {
      url: `/consultations/patient/${pid}`,
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
  order: [[2, "desc"]],
  columnDefs: [
      {
        targets: -1,
        className: "flex justify-center",
        width: 10,
      },
    ],
    columns: [

      {
        data: "document_type",
        title: "Document Type",
        render: (row) => (row ? row.toUpperCase() : ""),
      },
      {
        data: "created_at",
        title: "Date Added",
        render: (row) => formatDate(row),
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
          <div class="w-full flex flex-row justify-start items-center gap-2">
            <a type="button"
              class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md size-8"
              href="/consultations/${row.id ?? ""}/${row.document_type ?? ""}"
              target="_blank"
              rel="noopener noreferrer"
              >
              <i class="fa-solid fa-file fa-sm"></i>
              <span class="text-xs font-mono font-bold">PDF</span>
            </a>
             ${window.currentUserRole === 2 ? `
              <button type="button"
                class="dt-action px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md size-8"
                data-modal-open="edit-document-p"
                data-action="edit"
                data-id="${row.id ?? ""}">
                <i class="fa-solid fa-pen fa-sm"></i>
              </button>
              <button type="button"
                class="dt-action px-2 py-1 border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md size-8"
                data-modal-open="delete-document"
                data-action="delete"
                data-id="${row.id ?? ""}">
                <i class="fa-solid fa-trash fa-sm"></i>
              </button>
            ` : ''}
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
        cell.innerHTML = i + 1;
      });
  },
  });

  document
    .querySelector("#patientDocumentsTable tbody")
    .addEventListener("click", (e) => {
      const btn = e.target.closest(".dt-action");
      if (!btn) return;

      const action = btn.dataset.action;
      const patientId = btn.dataset.id;

      if (action === "delete") {
        deleteDocumentModalInit(patientId);
      }

      if (action === "edit") {
        editDocumentModalInit(patientId);
        window.openModal?.("edit-document-p");
      }
    });

  const documentSearch = document.getElementById("documentSearch");
  if (documentSearch) {
    documentSearch.addEventListener("input", (e) => {
      documentsTable.search(e.target.value).draw();
    });
  }

  window.refreshDocumentsTable = () => documentsTable.ajax.reload(null, false);
}
