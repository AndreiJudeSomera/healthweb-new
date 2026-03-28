// edi-patient-modal.js
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
      if (content === true || content === false) {
        el.checked = content;
      } else {
        el.value = content;
      }
    }
  });
}

function formatDate(dateStr) {
  if (!dateStr) return "";

  const d = new Date(dateStr);
  if (Number.isNaN(d.getTime())) return "";

  return d.toISOString().split("T")[0];
}

export async function editModalInit(pid) {
  const patient = await fetchData(pid);

  if (!patient || patient.length === 0) {
    alert("No data found");
  }

  const fields = [
    {
      id: "edit_first_name",
      content: patient.first_name,
    },
    {
      id: "edit_middle_name",
      content: patient.middle_name,
    },
    {
      id: "edit_last_name",
      content: patient.last_name,
    },
    {
      id: "edit_address",
      content: patient.address,
    },
    {
      id: "edit_date_of_birth",
      content: formatDate(patient.date_of_birth),
    },
    {
      id: "edit_gender",
      content: patient.gender,
    },
    {
      id: "edit_nationality",
      content: patient.nationality,
    },
    {
      id: "edit_contact_number",
      content: patient.contact_number,
    },
    {
      id: "edit_guardian_name",
      content: patient.guardian_name,
    },
    {
      id: "edit_guardian_relation",
      content: patient.guardian_relation,
    },
    {
      id: "edit_guardian_contact",
      content: patient.guardian_contact,
    },
    {
      id: "edit_allergy",
      content: patient.allergy,
    },
    {
      id: "edit_alcohol",
      content: patient.alcohol,
    },
    {
      id: "edit_years_of_smoking",
      content: patient.years_of_smoking,
    },
    {
      id: "edit_illicit_drug_use",
      content: patient.illicit_drug_use,
    },
    {
      id: "edit_hypertension",
      content: patient.hypertension,
    },
    {
      id: "edit_asthma",
      content: patient.asthma,
    },
    {
      id: "edit_diabetes",
      content: patient.diabetes,
    },
    {
      id: "edit_cancer",
      content: patient.cancer,
    },
    {
      id: "edit_thyroid",
      content: patient.thyroid,
    },
    {
      id: "edit_others",
      content: patient.others ?? "",
    },
  ];

  populateFields(fields);

  const editForm = document.getElementById("edit_patient_form");

  if (editForm._editSubmitHandler) {
    editForm.removeEventListener("submit", editForm._editSubmitHandler);
    delete editForm._editSubmitHandler;
  }

  const handler = async (e) => {
    e.preventDefault();

    const payload = Object.fromEntries(new FormData(editForm));

    try {
      const res = await fetch(`/patients/${encodeURIComponent(pid)}`, {
        method: "PUT",
        headers: {
          Accept: "application/json",
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        credentials: "same-origin",
        body: JSON.stringify(payload),
      });

      if (!res.ok) throw Error("Update failed");
      toastr.success(pid, "Patient updated!");
      document.getElementById("editModalCloseButton").click();
    } catch (err) {
      toastr.error("Update failed");
    }
  };

  editForm._editSubmitHandler = handler;
  editForm.addEventListener("submit", handler);
}
