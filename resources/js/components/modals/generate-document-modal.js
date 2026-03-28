export function initGenerateDocumentModal() {
  const gdForm = document.getElementById("create_document_form");
  const gdSubmit = gdForm?.querySelector('button[type="submit"]');

  const gdPatientPid = document.getElementById("patient_pid");

  const gdDocumentType = document.getElementById("document_type");
  const gdWt = document.getElementById("wt");
  const gdBp = document.getElementById("bp");
  const gdCr = document.getElementById("cr");
  const gdRr = document.getElementById("rr");
  const gdTemperature = document.getElementById("temperature");
  const gdSp02 = document.getElementById("spo2");
  const gdHistoryPhysicalExam = document.getElementById(
    "history_physical_exam"
  );
  const gdDiagnosis = document.getElementById("diagnosis");

  const gdReferralTo = document.getElementById("referral_to");
  const gdReferralReason = document.getElementById("referral_reason");
  const gdPrescriptionMeds = document.getElementById("prescription_meds");

  const gdRemarks = document.getElementById("remarks");

  gdDocumentType.addEventListener("change", () => {
    if (gdDocumentType.value !== "referral-letter") {
      gdReferralTo.disabled = true;
      gdReferralReason.disabled = true;
      gdReferralTo.classList.add("opacity-25");
      gdReferralReason.classList.add("opacity-25");
    } else {
      gdReferralTo.disabled = false;
      gdReferralReason.disabled = false;
      gdReferralTo.classList.remove("opacity-25");
      gdReferralReason.classList.remove("opacity-25");
    }

    if (gdDocumentType.value !== "prescription") {
      gdPrescriptionMeds.disabled = true;
      gdPrescriptionMeds.classList.add("opacity-25");
    } else {
      gdPrescriptionMeds.disabled = false;
      gdPrescriptionMeds.classList.remove("opacity-25");
    }
  });

  gdForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const payload = new FormData(gdForm);

    if (gdSubmit) {
      gdSubmit.disabled = true;
      gdSubmit.dataset.originalText = gdSubmit.innerHTML;
      gdSubmit.innerHTML = "Saving...";
    }

    const csrf = () =>
      document.querySelector('meta[name="csrf-token"]')?.content ?? "";

    const extractErrorMessage = (data) => {
      if (!data) return "Request failed";
      if (data.message) return data.message;

      if (data.errors && typeof data.errors === "object") {
        const firstKey = Object.keys(data.errors)[0];
        const firstMsg = data.errors[firstKey]?.[0];
        if (firstMsg) return firstMsg;
      }

      return "Request failed";
    };

    try {
      const res = await fetch("/consultations", {
        method: "POST",
        headers: {
          Accept: "application/json",
          "X-CSRF-TOKEN": csrf(),
        },
        credentials: "same-origin",
        body: payload,
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        toastr?.error?.(extractErrorMessage(data));
        return;
      }

      toastr?.success?.("Document created!");

      gdForm.reset();

      // close modal
      document.querySelector('[data-modal-close="create-document"]')?.click();

      // refresh table (if you expose this globally)
      window.refreshDocumentsTable?.();
    } catch (err) {
      console.error(err);
      toastr?.error?.("Unexpected error");
    } finally {
      if (gdSubmit) {
        gdSubmit.disabled = false;
        if (gdSubmit.dataset.originalText) {
          gdSubmit.innerHTML = gdSubmit.dataset.originalText;
          delete gdSubmit.dataset.originalText;
        }
      }
    }
  });
}
