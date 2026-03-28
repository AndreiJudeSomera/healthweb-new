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

export async function deleteModalInit(pid) {
  const patient = await fetchData(pid);

  if (!patient || patient.length === 0) {
    alert("No data found");
  }

  const fields = [
    {
      id: "deleteViewPid",
      content: patient.pid,
    },
    {
      id: "deleteViewName",
      content: patient.first_name + " " + patient.last_name,
    },
  ];

  populateFields(fields);

  const deleteForm = document.getElementById("patient_delete_form");

  if (deleteForm._deleteSubmitHandler) {
    editForm.removeEventListener("submit", deleteForm._deleteSubmitHandler);
    delete deleteForm._deleteSubmitHandler;
  }

  const handler = async (e) => {
    e.preventDefault();

    try {
      const res = await fetch(`/patients/${encodeURIComponent(pid)}`, {
        method: "DELETE",
        headers: {
          Accept: "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
        },
        credentials: "same-origin",
      });

      if (!res.ok) throw Error("Delete failed");
      toastr.success("Patient record deleted", "Delete successful");
      document.getElementById("deleteCloseButton").click();
    } catch (err) {
      toastr.error("Patient was not deleted", "Delete failed");
    }

    setTimeout(() => location.reload(), 2000);
  };

  deleteForm._deleteSubmitHandler = handler;
  deleteForm.addEventListener("submit", handler);
}
