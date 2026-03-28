async function fetchConsultation(id) {
  const res = await fetch(`/consultations/${encodeURIComponent(id)}`, {
    headers: { Accept: "application/json" },
    credentials: "same-origin",
  });
  if (!res.ok) throw new Error("Fetch failed");
  return res.json();
}

export async function editDocumentModalInit(id) {
  try {
    const doc = await fetchConsultation(id);

    const modal = document.getElementById("edit-document-sec");
    if (!modal) {
      console.warn("edit-document-sec modal not found in DOM");
      return;
    }

    modal.dispatchEvent(
      new CustomEvent("edit-ready", { detail: doc, bubbles: false })
    );
  } catch (err) {
    toastr?.error("Failed to load document data.");
    console.error(err);
  }
}
