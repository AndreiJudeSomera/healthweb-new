document.addEventListener("DOMContentLoaded", function () {
  const buttons = document.querySelectorAll("[data-appointment-id]");
  const csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute("content");

  buttons.forEach((button) => {
    button.addEventListener("click", function () {
      const appointmentId = this.dataset.appointmentId;

      if (!confirm("Are you sure you want to cancel this appointment?")) {
        return;
      }

      fetch(`/appointments/${appointmentId}`, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
          Accept: "application/json",
        },
      })
        .then(async (response) => {
          const data = await response.json();

          if (!response.ok) {
            throw new Error(data.message || "Something went wrong.");
          }

          return data;
        })
        .then((data) => {
          // Remove card
          this.closest("div.bg-gray-100").remove();

          // Show success toast
          toastr.success(data.message);
        })
        .catch((error) => {
          toastr.error(error.message);
        });
    });
  });
});
