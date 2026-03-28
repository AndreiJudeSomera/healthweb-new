import { initDocumentsTable } from "./patient-documents-table";

const pid = document.getElementById("viewPid").getHTML();

if (pid) {
  initDocumentsTable(pid);
}
