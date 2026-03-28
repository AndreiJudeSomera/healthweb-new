import DataTable from "datatables.net-dt";
import "../components/modals/modal";

const ROLES = { 0: "Patient", 1: "Secretary", 2: "Doctor/Superadmin" };

// ─── Table ───────────────────────────────────────────────────────────────────

const table = new DataTable("#accountsTable", {
  retrieve: true,
  ajax: {
    url: "/accounts/list",
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
  searching: true,
  lengthChange: true,
  paging: true,
  columnDefs: [
    { targets: -1, className: "text-center" },
    { targets: 4, className: "text-center" },
  ],
  columns: [
    {
      data: null,
      title: "#",
      orderable: false,
      searchable: false,
     className: "text-center bg-gray-100 rounded-tl-md",
  render: (data, type, row, meta) => `
    <div class="mx-1 my-1">${meta.row + meta.settings._iDisplayStart + 1}</div>
  `,
    },
    { data: "email", title: "Email" },
    { data: "username", title: "Username" },
    {
      data: "role",
      title: "Role",
      render: (val) => ROLES[val] ?? "Unknown",
    },
    {
      data: "is_active",
      title: "Status",
      render: (val) =>
        val
          ? `<span class="px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">Active</span>`
          : `<span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">Inactive</span>`,
    },
    {
      data: null,
      title: "Actions",
      orderable: false,
      searchable: false,
      className: "rounded-tr-md",
      render: (data, type, row) => {
        const addInfoBtn =
          row.role === 0
            ? `<button type="button"
                class="dt-action px-2 py-1 border-2 border-emerald-700 text-emerald-700 hover:bg-emerald-50 rounded-md size-8 flex justify-center items-center"
                data-action="add-info" data-role="patient" data-id="${row.id}"
                title="Patient Info">
                <i class="fa-solid fa-file-medical fa-sm"></i>
              </button>`
            : row.role === 1
              ? `<button type="button"
                  class="dt-action px-2 py-1 border-2 border-amber-700 text-amber-700 hover:bg-amber-50 rounded-md size-8 flex justify-center items-center"
                  data-action="add-info" data-role="secretary" data-id="${row.id}"
                  title="Secretary Info">
                  <i class="fa-solid fa-id-card fa-sm"></i>
                </button>`
              : row.role === 2
                ? `<button type="button"
                    class="dt-action px-2 py-1 border-2 border-indigo-700 text-indigo-700 hover:bg-indigo-50 rounded-md size-8 flex justify-center items-center"
                    data-action="add-info" data-role="doctor" data-id="${row.id}"
                    title="Doctor Info">
                    <i class="fa-solid fa-user-doctor fa-sm"></i>
                  </button>`
                : "";

        return `
          <div class="flex flex-row justify-center items-center gap-2">
            <button type="button"
              class="dt-action px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-50 rounded-md size-8 flex justify-center items-center"
              data-action="edit" data-id="${row.id}" title="Edit">
              <i class="fa-solid fa-pencil fa-sm"></i>
            </button>
            ${addInfoBtn}
            <button type="button"
              class="dt-action px-2 py-1 border-2 border-red-800 text-red-800 hover:bg-red-50 rounded-md size-8 flex justify-center items-center"
              data-action="delete" data-id="${row.id}" data-name="${row.username}" title="Delete">
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
        <div class="flex items-center justify-end h-full mx-1 my-1">
          ${i + 1}
        </div>
      `;
      });
  },
  rowCallback: function(row, data, index) {
    $(row).addClass('border-b border-blue-200 last:border-b-0');
  },
});

// ─── Role filter ─────────────────────────────────────────────────────────────

window.filterAccountsTable = (value) => {
  table.column(3).search(value ? `^${value}$` : "", true, false).draw();
};

// Default to Patient filter on load
table.on("init", () => window.filterAccountsTable("Patient"));

// ─── Search ──────────────────────────────────────────────────────────────────

const searchInput = document.getElementById("patientSearch");
if (searchInput) {
  searchInput.addEventListener("input", (e) =>
    table.search(e.target.value).draw()
  );
}

// ─── Action delegation ───────────────────────────────────────────────────────

document.getElementById("accountsTable")?.addEventListener("click", (e) => {
  const btn = e.target.closest(".dt-action");
  if (!btn) return;

  const { action, id, role, name } = btn.dataset;

  if (action === "edit") editInit(id);
  else if (action === "delete") deleteInit(id, name);
  else if (action === "add-info") addInfoInit(id, role);
});

// ─── Create ──────────────────────────────────────────────────────────────────

const createForm = document.getElementById("account-create-form");

createForm?.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearErrors("account-create-form");

  const res = await fetch("/accounts", {
    method: "POST",
    headers: { "X-CSRF-TOKEN": csrfToken(), Accept: "application/json" },
    body: new FormData(createForm),
  });

  const data = await res.json();
  if (!res.ok) return showErrors(data.errors, "account-create-form");

  toastr.success(data.message);
  createForm.reset();
  Modal.close("account-create");
  table.ajax.reload(null, false);
});

// ─── Edit ────────────────────────────────────────────────────────────────────

async function editInit(id) {
  const res = await fetch(`/accounts/${id}/edit`, {
    headers: { Accept: "application/json" },
  });
  const user = await res.json();

  const form = document.getElementById("account-edit-form");
  form.querySelector("[name=email]").value = user.email;
  form.querySelector("[name=username]").value = user.username;
  form.querySelector("[name=role]").value = user.role;
  form.querySelector("[name=is_active]").checked = user.is_active;
  form.querySelector("[name=password]").value = "";
  form.querySelector("[name=password_confirmation]").value = "";
  form.dataset.userId = id;

  clearErrors("account-edit-form");
  Modal.open("account-edit");
}

const editForm = document.getElementById("account-edit-form");

editForm?.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearErrors("account-edit-form");

  const id = editForm.dataset.userId;
  const formData = new FormData(editForm);
  formData.append("_method", "PUT");

  const res = await fetch(`/accounts/${id}`, {
    method: "POST",
    headers: { "X-CSRF-TOKEN": csrfToken(), Accept: "application/json" },
    body: formData,
  });

  const data = await res.json();
  if (!res.ok) return showErrors(data.errors, "account-edit-form");

  toastr.success(data.message);
  Modal.close("account-edit");
  table.ajax.reload(null, false);
});

// ─── Delete ──────────────────────────────────────────────────────────────────

let pendingDeleteId = null;

function deleteInit(id, name) {
  pendingDeleteId = id;
  const label = document.getElementById("delete-account-name");
  if (label) label.textContent = name;
  Modal.open("account-delete");
}

document
  .getElementById("confirm-delete-btn")
  ?.addEventListener("click", async () => {
    if (!pendingDeleteId) return;

    const res = await fetch(`/accounts/${pendingDeleteId}`, {
      method: "DELETE",
      headers: { "X-CSRF-TOKEN": csrfToken(), Accept: "application/json" },
    });

    const data = await res.json();
    toastr.success(data.message);
    pendingDeleteId = null;
    Modal.close("account-delete");
    table.ajax.reload(null, false);
  });

// ─── Add Info ────────────────────────────────────────────────────────────────

async function addInfoInit(userId, role) {
  if (role === "patient") {
    // 🔥 FIX GENDER DROPDOWN

    const form = document.getElementById("patient-info-form");
    form.reset();
    document.getElementById("patient_user_id").value = userId;

    const res = await fetch(`/accounts/${userId}/patient-info`, {
      headers: { Accept: "application/json" },
    });
    const existing = await res.json();

    const title = document.querySelector("#account-patient-info h1");
    if (existing) {
      form.dataset.mode = "update";
      form.dataset.userId = userId;
      if (title) title.textContent = "Edit Patient Information";

      setField(form, "Fname", existing.Fname);
      setField(form, "Lname", existing.Lname);
      setField(form, "Mname", existing.Mname);
      setField(form, "Gender", existing.Gender);
      setField(form, "DateofBirth", existing.DateofBirth);
      setField(form, "Nationality", existing.Nationality);
      setField(form, "ContactNumber", existing.ContactNumber);
      setField(form, "Address", existing.Address);
      setField(form, "GuardianName", existing.GuardianName);
      setField(form, "GuardianRelation", existing.GuardianRelation);
      setField(form, "GuardianContact", existing.GuardianContact);
      setField(form, "Allergy", existing.Allergy);
      setField(form, "Alcohol", existing.Alcohol);
      setField(form, "Years_of_Smoking", existing.Years_of_Smoking);
      setField(form, "IllicitDrugUse", existing.IllicitDrugUse);
      setField(form, "family_history_other", existing.family_history_other);

      form.querySelectorAll("[name='family_history[]']").forEach((cb) => {
        cb.checked = (existing.family_history ?? []).includes(cb.value);
      });
    } else {
      form.dataset.mode = "create";
      delete form.dataset.userId;
      if (title) title.textContent = "Patient Information";
    }

    Modal.open("account-patient-info");

  } else if (role === "secretary") {
    const form = document.getElementById("secretary-info-form");
    form.reset();
    document.getElementById("secretary_user_id").value = userId;

    const res = await fetch(`/accounts/${userId}/secretary-info`, {
      headers: { Accept: "application/json" },
    });
    const existing = await res.json();

    const title = document.querySelector("#account-secretary-info h1");
    if (existing) {
      form.dataset.mode = "update";
      form.dataset.userId = userId;
      if (title) title.textContent = "Edit Secretary Information";

      setField(form, "Fname", existing.Fname);
      setField(form, "Lname", existing.Lname);
      setField(form, "Mname", existing.Mname);
      setField(form, "DateofBirth", existing.DateofBirth);
      setField(form, "Gender", existing.Gender);
      setField(form, "ContactNumber", existing.ContactNumber);
      setField(form, "Address", existing.Address);
      setField(form, "SecAssignedID", existing.SecAssignedID);
    } else {
      form.dataset.mode = "create";
      delete form.dataset.userId;
      if (title) title.textContent = "Secretary Information";
    }

    Modal.open("account-secretary-info");

  } else if (role === "doctor") {
    const form = document.getElementById("doctor-info-form");
    form.reset();
    document.getElementById("doctor_user_id").value = userId;

    const res = await fetch(`/accounts/${userId}/doctor-info`, {
      headers: { Accept: "application/json" },
    });
    const existing = await res.json();

    const title = document.querySelector("#account-doctor-info h1");
    if (existing) {
      form.dataset.mode = "update";
      form.dataset.userId = userId;
      if (title) title.textContent = "Edit Doctor Information";

      setField(form, "Fname", existing.Fname);
      setField(form, "Lname", existing.Lname);
      setField(form, "Mname", existing.Mname);
      setField(form, "DateofBirth", existing.DateofBirth);
      setField(form, "Gender", existing.Gender);
      setField(form, "ContactNumber", existing.ContactNumber);
      setField(form, "Address", existing.Address);
      setField(form, "dr_license_no", existing.dr_license_no);
      setField(form, "ptr_no", existing.ptr_no);
    } else {
      form.dataset.mode = "create";
      delete form.dataset.userId;
      if (title) title.textContent = "Doctor Information";
    }

    clearErrors("doctor-info-form");
    Modal.open("account-doctor-info");
  }
}

// ─── Patient info submit ──────────────────────────────────────────────────────

const patientInfoForm = document.getElementById("patient-info-form");

patientInfoForm?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const isUpdate = patientInfoForm.dataset.mode === "update";
  const url = isUpdate
    ? `/accounts/${patientInfoForm.dataset.userId}/patient-info`
    : "/accounts/patient-info";

  const formData = new FormData(patientInfoForm);
  if (isUpdate) formData.append("_method", "PUT");

  const res = await fetch(url, {
    method: "POST",
    headers: { "X-CSRF-TOKEN": csrfToken(), Accept: "application/json" },
    body: formData,
  });

  const data = await res.json();
  if (!res.ok) {
    toastr.error(data.message ?? "Failed to save patient info.");
    return;
  }

  toastr.success(data.message);
  patientInfoForm.reset();
  Modal.close("account-patient-info");
});

// ─── Secretary info submit ────────────────────────────────────────────────────

const secretaryInfoForm = document.getElementById("secretary-info-form");

secretaryInfoForm?.addEventListener("submit", async (e) => {
  e.preventDefault();

  const isUpdate = secretaryInfoForm.dataset.mode === "update";
  const url = isUpdate
    ? `/accounts/${secretaryInfoForm.dataset.userId}/secretary-info`
    : "/accounts/secretary-info";

  const formData = new FormData(secretaryInfoForm);
  if (isUpdate) formData.append("_method", "PUT");

  const res = await fetch(url, {
    method: "POST",
    headers: { "X-CSRF-TOKEN": csrfToken(), Accept: "application/json" },
    body: formData,
  });

  const data = await res.json();
  if (!res.ok) {
    toastr.error(data.message ?? "Failed to save secretary info.");
    return;
  }

  toastr.success(data.message);
  secretaryInfoForm.reset();
  Modal.close("account-secretary-info");
});

// ─── Doctor info submit ───────────────────────────────────────────────────────

const doctorInfoForm = document.getElementById("doctor-info-form");

doctorInfoForm?.addEventListener("submit", async (e) => {
  e.preventDefault();
  clearErrors("doctor-info-form");

  const isUpdate = doctorInfoForm.dataset.mode === "update";
  const url = isUpdate
    ? `/accounts/${doctorInfoForm.dataset.userId}/doctor-info`
    : "/accounts/doctor-info";

  const formData = new FormData(doctorInfoForm);
  if (isUpdate) formData.append("_method", "PUT");

  const res = await fetch(url, {
    method: "POST",
    headers: { "X-CSRF-TOKEN": csrfToken(), Accept: "application/json" },
    body: formData,
  });

  const data = await res.json();
  if (!res.ok) {
    if (data.errors) return showErrors(data.errors, "doctor-info-form");
    toastr.error(data.message ?? "Failed to save doctor info.");
    return;
  }

  toastr.success(data.message);
  doctorInfoForm.reset();
  Modal.close("account-doctor-info");
});

// ─── Helpers ─────────────────────────────────────────────────────────────────

function csrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content ?? "";
}

function clearErrors(formId) {
  document.querySelectorAll(`#${formId} [data-error]`).forEach((el) => {
    el.textContent = "";
    el.classList.add("hidden");
  });
}

function showErrors(errors, formId) {
  Object.entries(errors).forEach(([field, messages]) => {
    const el = document.querySelector(`#${formId} [data-error="${field}"]`);
    if (el) {
      el.textContent = messages[0];
      el.classList.remove("hidden");
    }
  });
}

function setField(form, name, value) {
  const el = form.querySelector(`[name="${name}"]`);
  if (el) el.value = value ?? "";
}

window.refreshAccountsTable = () => table.ajax.reload(null, false);
