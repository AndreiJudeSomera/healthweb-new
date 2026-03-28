import DataTable from "datatables.net-dt";
import "../components/modals/modal";

const ROLE_LABELS = {
  0: "Patient",
  1: "Secretary",
  2: "Doctor",
};

function formatDateTime(iso) {
  if (!iso) return { date: "", time: "" };
  const d = new Date(iso);
  return {
    date: d.toLocaleDateString("en-US", { month: "short", day: "numeric", year: "numeric" }),
    time: d.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit", hour12: true }),
  };
}

// Forward current page filter params to the data endpoint
const params = new URLSearchParams(window.location.search);
const ajaxUrl = `/audit-logs/data${params.toString() ? `?${params}` : ""}`;

const auditTable = new DataTable("#auditLogsTable", {
  ajax: {
    url: ajaxUrl,
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
  order: [[1, "desc"]],
  searching: true,
  lengthChange: true,
  paging: true,
  initComplete: function () {
    const ths = this.api().table().header().querySelectorAll("th");
    if (ths.length) {
      ths[0].classList.add("rounded-tl-md");
      ths[ths.length - 1].classList.add("rounded-tr-md");
    }
  },
  columnDefs: [
    {
      targets: -1,
      className: "flex justify-center",
    },
  ],
 
  columns: [
     {
      data: null,
      title: "#",
      orderable: false,
      searchable: false,
    className: "bg-gray-100 rounded-tl-md",
  render: (data, type, row, meta) => `
  <div >${meta.row + meta.settings._iDisplayStart + 1}</div>
`,
    },
    {
      data: "created_at",
      title: "Date",
      className: "whitespace-nowrap",
      render: (val, type) => {
        if (type === "sort" || type === "type") return val;
        return formatDateTime(val).date;
      },
    },
    {
      data: "created_at",
      title: "Time",
      className: "whitespace-nowrap text-gray-500",
      orderable: false,
      render: (val, type) => {
        if (type === "sort" || type === "type") return val;
        return formatDateTime(val).time;
      },
    },
    {
      data: "user_role",
      title: "Role",
      className: "whitespace-nowrap",
      render: (val) => ROLE_LABELS[val] ?? "Unknown",
    },
    {
      data: "user_name",
      title: "User",
      className: "whitespace-nowrap",
    },
    {
      data: "description",
      title: "Action",
    },
  ],
   drawCallback: function () {
    this.api()
      .column(0, { search: "applied", order: "applied" })
      .nodes()
      .each((cell, i) => {
        cell.innerHTML = `
        <div class="flex justify-end h-full mx-3 my-2 ">
          ${i + 1}
        </div>
      `;
      });
  }, rowCallback: function(row, data, index) {
    $(row).addClass('border-b border-blue-200 last:border-b-0');
  },
});

const searchInput = document.getElementById("logSearch");
if (searchInput) {
  searchInput.addEventListener("input", (e) => {
    auditTable.search(e.target.value).draw();
  });
}
