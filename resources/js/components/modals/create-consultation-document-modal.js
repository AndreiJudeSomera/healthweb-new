async function fetchConsultation(id) {
  const res = await fetch(`/consultations/${encodeURIComponent(id)}`, {
    headers: { Accept: "application/json" },
    credentials: "same-origin",
  });
  if (!res.ok) throw new Error("Failed to load consultation");
  return res.json();
}

export async function createConsultationDocumentModalInit(id) {
  try {
    const consultation = await fetchConsultation(id);

    const modal = document.getElementById("create-consultation-document");
    if (!modal) {
      console.warn("create-consultation-document modal not found in DOM");
      return;
    }

    modal.dispatchEvent(
      new CustomEvent("consultation-ready", {
        detail: consultation,
        bubbles: false,
      })
    );
  } catch (err) {
    toastr?.error("Failed to load consultation data.");
    console.error(err);
  }
}
