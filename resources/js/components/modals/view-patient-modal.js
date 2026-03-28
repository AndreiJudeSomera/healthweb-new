// FUNCTION: Fill view modal with data
async function fetchData(pid) {
  const res = await fetch(`/patients/search?pid=${encodeURIComponent(pid)}`, {
    headers: { Accept: "application/json" },
    credentials: "same-origin",
  });
  const data = await res.json();
  return data;
}

function populateFields(fieldList) {
  fieldList.forEach(({ id, content }) => {
    const el = document.getElementById(id);
    if (el) {
      el.innerHTML = content ?? "[ - ]";
    }
  });
}

function formatDate(dateStr) {
  if (!dateStr) return "";
  const d = new Date(dateStr);
  if (Number.isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString("en-GB", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
}

function formatDateWithTime(dateStr) {
  if (!dateStr) return "";
  const d = new Date(dateStr);
  if (Number.isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString("en-GB", {
    hour: "2-digit",
    minute: "2-digit",
    hour12: false,
    day: "2-digit",
    month: "long",
    year: "numeric",
  });
}

async function copyText(text) {
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

export async function viewModalInit(pid) {
  const patient = await fetchData(pid);

  if (!patient || patient.length === 0) {
    alert("No data found");
  }

  const avatarIcon = document.getElementById("viewPatientAvatar");
  if (avatarIcon) {
    const isFemale = patient.gender?.toLowerCase() === "female";
    avatarIcon.className = isFemale
      ? "fa-solid fa-person-dress text-[200px] mt-[2px] text-pink-600"
      : "fa-solid fa-person text-[200px] mt-[2px] text-blue-700";
  }

  const link = document.getElementById("view-patient-history");
  link.href = `/patients/${encodeURIComponent(patient.pid)}`;

  const copyPidButton = document.getElementById("copyPidButton");

  async function onCopyPidClick(e) {
    if (copyPidButton.dataset.busy === "1") return;

    copyPidButton.dataset.busy = "1";
    copyPidButton.disabled = true;

    try {
      const ok = await copyText(patient.pid);
      toastr.clear();
      ok
        ? toastr.success(patient.pid, "Copied to clipboard")
        : toastr.error(patient.pid, "Failed to copy PID");
    } finally {
      copyPidButton.dataset.busy = "0";
      copyPidButton.disabled = false;
    }
  }

  copyPidButton.removeEventListener("click", onCopyPidClick);
  copyPidButton.addEventListener("click", onCopyPidClick);

  const fields = [
    {
      id: "viewPid",
      content: patient.pid,
    },
    {
      id: "viewFirstName",
      content: patient.first_name,
    },
    {
      id: "viewMiddleName",
      content: patient.middle_name,
    },
    {
      id: "viewLastName",
      content: patient.last_name,
    },
    {
      id: "viewAddress",
      content: patient.address,
    },
    {
      id: "viewDob",
      content: formatDate(patient.date_of_birth),
    },
    {
      id: "viewAge",
      content: patient.age,
    },
    {
      id: "viewGender",
      content: patient.gender.charAt(0).toUpperCase() + patient.gender.slice(1),
    },
    {
      id: "viewNationality",
      content:
        patient.nationality.charAt(0).toUpperCase() +
        patient.nationality.slice(1),
    },
    {
      id: "viewContactNumber",
      content: patient.contact_number,
    },
    {
      id: "viewGuardianName",
      content: patient.guardian_name,
    },
    {
      id: "viewGuardianRelation",
      content:
        patient.guardian_relation.charAt(0).toUpperCase() +
        patient.guardian_relation.slice(1),
    },
    {
      id: "viewGuardianContact",
      content: patient.guardian_contact,
    },
    {
      id: "viewAllergies",
      content: patient.allergy.toUpperCase(),
    },
    {
      id: "viewAlcohol",
      content: patient.alcohol.toUpperCase(),
    },
    {
      id: "viewYearsOfSmoking",
      content: patient.years_of_smoking,
    },
    {
      id: "viewDrugUse",
      content: patient.illicit_drug_use.toUpperCase(),
    },
    {
      id: "viewHypertension",
      content: patient.hypertension
        ? `<span class="text-green-600">Yes</span>`
        : `<span class="text-gray-600">No</span>`,
    },
    {
      id: "viewAsthma",
      content: patient.asthma
        ? `<span class="text-green-600">Yes</span>`
        : `<span class="text-gray-600">No</span>`,
    },
    {
      id: "viewDiabetes",
      content: patient.diabetes
        ? `<span class="text-green-600">Yes</span>`
        : `<span class="text-gray-600">No</span>`,
    },
    {
      id: "viewCancer",
      content: patient.cancer
        ? `<span class="text-green-600">Yes</span>`
        : `<span class="text-gray-600">No</span>`,
    },
    {
      id: "viewThyroid",
      content: patient.thyroid
        ? `<span class="text-green-600">Yes</span>`
        : `<span class="text-gray-600">No</span>`,
    },
    {
      id: "viewOthers",
      content: patient.others ? patient.others.toUpperCase() : null,
    },
    {
      id: "viewCreatedAt",
      content: formatDateWithTime(patient.created_at),
    },
    {
      id: "viewUpdatedAt",
      content: formatDateWithTime(patient.updated_at),
    },
    {
      id: "viewPatientType",
      content:
        patient.patient_type === "new"
          ? `<span class="text-green-600">NEW</span>`
          : `<span class="text-amber-600">OLD</span>`,
    },
    {
      id: "viewIsBound",
      content:
        patient.patient_type === "new"
          ? `<span class="text-green-600">YES</span>`
          : `<span class="text-amber-600">NO</span>`,
    },
  ];

  populateFields(fields);
}
