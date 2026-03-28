document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("patient_bind_record");
  const submitBtn = form?.querySelector('button[type="submit"]');

  if (!form) return;

  const csrf = () =>
    document.querySelector('meta[name="csrf-token"]')?.content ?? "";

  const userSelectEl = document.querySelector("#bind_user");
  const recordSelectEl = document.querySelector("#bind_record");

  const userSelect = new TomSelect(userSelectEl, {
    create: false,
    placeholder: "Search user ...",
    valueField: "value",
    labelFiled: "text",
    searchField: ["text"],
  });

  const recordSelect = new TomSelect(recordSelectEl, {
    create: false,
    placeholder: "Search record ...",
    valueField: "value",
    labelFiled: "text",
    searchField: ["text"],
  });

  function resetSelect(ts, label) {
    ts.clear(true);
    ts.clearOptions();
    ts.addOption({ value: "", text: label });
    ts.refreshOptions(false);
    ts.setValue("", true);
  }

  function extractErrorMessage(data) {
    return data?.message ?? data?.error ?? "Something went wrong.";
  }

  async function loadUsers() {
    resetSelect(userSelect, "Loading users...");

    try {
      const res = await fetch("/patients/newusers", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        toastr?.error?.(extractErrorMessage(data));
        resetSelect(userSelect, "Failed to load users");
        return;
      }

      const users = Array.isArray(data) ? data : (data?.data ?? []);
      if (!Array.isArray(users) || users.length === 0) {
        resetSelect(userSelect, "No users found");
        return;
      }

      resetSelect(userSelect, "Select User");

      userSelect.clearOptions();

      userSelect.addOption({ value: "", text: "Select User" });

      users.forEach((u) => {
        const name = u?.user?.username ?? "User";
        const id = u?.user_id ?? "00";

        userSelect.addOption({
          value: String(id),
          text: `${id} | ${name}`,
        });
      });
    } catch (err) {
      console.error(err);
      resetSelect(userSelect, "Error loading users");
    }
  }

  async function loadRecords() {
    resetSelect(recordSelect, "Loading records...");

    try {
      const res = await fetch("/patients/old", {
        headers: { Accept: "application/json" },
        credentials: "same-origin",
      });

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        toastr?.error?.(extractErrorMessage(data));
        resetSelect(recordSelect, "Failed to load records");
        return;
      }

      const records = Array.isArray(data) ? data : (data?.data ?? []);

      if (!Array.isArray(records) || records.length === 0) {
        resetSelect(recordSelect, "No records found");
        return;
      }

      resetSelect(recordSelect, "Select Record");

      recordSelect.clearOptions();

      recordSelect.addOption({ value: "", text: "Select Record" });

      records.forEach((r) => {
        recordSelect.addOption({
          value: String(r.id),
          text: `${r.pid} | ${r.p_first_name} ${r.p_last_name}`,
        });
      });
    } catch (err) {
      console.error(err);
      resetSelect(recordSelect, "Error loading records");
    }
  }

  loadUsers();
  loadRecords();

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const payload = new FormData(form);
    payload.append("_method", "PUT");

    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.dataset.originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = "Saving...";
    }

    try {
      const res = await fetch(
        `/patients/bind/${encodeURIComponent(userSelect.value)}`,
        {
          method: "POST",
          headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": csrf(),
          },
          credentials: "same-origin",
          body: payload,
        }
      );

      const data = await res.json().catch(() => null);

      if (!res.ok) {
        toastr?.error?.(extractErrorMessage(data));
        return;
      }

      toastr?.success?.("Patient bound to record!");

      form.reset();
      resetSelect(userSelect, "Select User");
      resetSelect(recordSelect, "Select Record");

      document.querySelector('[data-modal-close="bind-patient"]')?.click();

      window.refreshPatientsTable?.();
    } catch (err) {
      console.error(err);
      toastr?.error?.("Unexpected error");
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        if (submitBtn.dataset.originalText) {
          submitBtn.innerHTML = submitBtn.dataset.originalText;
          delete submitBtn.dataset.originalText;
        }
      }
    }
  });
});
