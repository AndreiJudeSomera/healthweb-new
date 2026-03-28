import { formatDate } from "../../pages/patient-show-index";

async function fetchConsultation(id) {
  const res = await fetch(`/consultations/${encodeURIComponent(id)}`, {
    headers: { Accept: "application/json" },
    credentials: "same-origin",
  });
  if (!res.ok) throw new Error("Fetch failed");
  return res.json();
}

export async function deleteDocumentModalInit(id) {
  const patientDocument = await fetchConsultation(id);

  document.getElementById("delete_doc_id").value = patientDocument.id;
  document.getElementById("delete_doc_date").textContent = formatDate(
    patientDocument.created_at
  );
  document.getElementById("delete_doc_type").textContent =
    patientDocument.document_type?.toUpperCase();

  const form = document.getElementById("delete_document_form");
  if (!form) return;

  if (form._handler) {
    form.removeEventListener("submit", form._handler);
    delete form._handler;
  }

  const handler = async (e) => {
    e.preventDefault();

    try {
      const documentId = document.getElementById("delete_doc_id").value;

      const res = await fetch(
        `/consultations/${encodeURIComponent(documentId)}`,
        {
          method: "DELETE",
          headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
              .content,
          },
          credentials: "same-origin",
        }
      );

      const data = await res.json().catch(() => ({}));

      if (!res.ok) throw new Error(data.message || "Delete failed");

      toastr.success("Document deleted!");
      document.querySelector('[data-modal-close="delete-document"]')?.click();
      window.refreshDocumentsTable?.();
    } catch (err) {
      toastr.error(err.message || "Delete failed");
    }
  };

  form._handler = handler;
  form.addEventListener("submit", handler);
}
