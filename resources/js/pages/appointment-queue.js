import "../components/modals/modal";
import "./appointment-all-create";
import { editAppointmentModalInit } from "../components/modals/edit-appointment-modal";
import { deleteAppointmentModalInit } from "../components/modals/delete-appointment-modal";

// After any create/edit/delete, reload the queue to reflect changes
window.refreshAppointmentsTable = () => location.reload();

toastr.options = {
  closeButton: true,
  newestOnTop: true,
  progressBar: true,
  positionClass: "toast-bottom-right",
  preventDuplicates: true,
  timeOut: "4000",
};

// Queue card action buttons
document.addEventListener("click", (e) => {
  const btn = e.target.closest("[data-queue-action]");
  if (!btn) return;

  const action = btn.dataset.queueAction;
  const id = btn.dataset.apptId;

  if (action === "edit") {
    editAppointmentModalInit(id);
  } else if (action === "delete") {
    deleteAppointmentModalInit(id);
  }
});
