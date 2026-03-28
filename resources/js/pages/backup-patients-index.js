import { GaricTable } from "../components/garic-table/GaricTable";
import "../components/modals/modal";

async function fetchJson(url) {
  const res = await fetch(url, { headers: { Accept: "application/json" } });
  if (!res.ok) throw new Error(`HTTP ${res.status}`);
  return await res.json();
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

function calcAge(dobStr) {
  if (!dobStr) return "";
  const dob = new Date(dobStr);
  if (Number.isNaN(dob.getTime())) return "";
  const diff = Date.now() - dob.getTime();
  return Math.floor(diff / (365.25 * 24 * 60 * 60 * 1000));
}

export function initPatientsIndex() {
  const table = document.getElementById("patientsTableEl");
  const tbody = document.getElementById("patientsTbody");

  if (!table || !tbody) return;

  const dt = new GaricTable({
    tableId: "patientsTableEl",
    tbodyId: "patientsTbody",
    searchInputId: "searchPatient",
    emptyMessage: "No patients found.",

    fetchIndex: async () => {
      const data = await fetchJson("/patients/records");
      console.log("INDEX DATA:", data);
      console.log("IS ARRAY?", Array.isArray(data));
      return data;
    },
    fetchSearch: async (q) => {
      const data = await fetchJson(
        `/patients/search?q=${encodeURIComponent(q)}`
      );
      return data;
    },

    columns: [
      {
        key: "last_name",
        render: (r) => r.user_last_name ?? r.last_name ?? "",
      },
      {
        key: "first_name",
        render: (r) => r.user_first_name ?? r.first_name ?? "",
      },
      {
        key: "age",
        render: (r) => r.age ?? calcAge(r.date_of_birth),
      },
      {
        key: "gender",
        render: (r) => r.gender ?? "",
      },
      {
        key: "created_at",
        render: (r) => formatDate(r.created_at),
      },
    ],

    actions: [
      {
        key: "view",
        label: "View",
        className:
          "px-2 py-1 border-2 border-blue-950 text-blue-950 hover:bg-indigo-100 rounded-md",
        iconHtml: '<i class="fa-solid fa-eye fa-sm"></i>',
        onClick: (row) => alert("view: " + row),
      },
      {
        key: "edit",
        label: "Edit",
        className:
          "px-2 py-1 border-2 border-amber-800 text-amber-800 hover:bg-amber-100 rounded-md",
        iconHtml: '<i class="fa-solid fa-pencil fa-sm"></i>',
        onClick: (row) => alert("edit: " + row),
      },
      {
        key: "delete",
        label: "Delete",
        className:
          "px-2 py-1 border-2 border-red-800 text-red-800 hover:bg-red-100 rounded-md",
        iconHtml: '<i class="fa-solid fa-trash fa-sm"></i>',
        onClick: (row) => alert("delete: " + row),
      },
    ],
  });

  dt.load();
}

document.addEventListener("DOMContentLoaded", initPatientsIndex);
