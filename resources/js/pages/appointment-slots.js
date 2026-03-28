document.addEventListener("DOMContentLoaded", () => {
  const dateInput = document.getElementById("appointment_date");
  const timeSelect = document.getElementById("appointment_time");

  if (!dateInput || !timeSelect) return;

  const today = new Date();
  dateInput.setAttribute("min", today.toLocaleDateString("en-CA"));

  const resetSelect = (message = "Select Appointment Time") => {
    timeSelect.innerHTML = "";
    const opt = document.createElement("option");
    opt.textContent = message;
    opt.disabled = true;
    opt.selected = true;
    timeSelect.appendChild(opt);
  };

  const populateSlots = async (date) => {
    resetSelect("Loading available slots...");

    try {
      const res = await fetch(
        `/appointments/available-slots?date=${encodeURIComponent(date)}`
      );

      const data = await res.json();

      if (!data.slots || data.slots.length === 0) {
        resetSelect("No available slots");
        return;
      }

      resetSelect();

      data.slots.forEach((slot) => {
        const option = document.createElement("option");

        option.value = slot.time;
        option.textContent = `${slot.label} (${slot.remaining}/${slot.capacity} available)`;

        if (slot.is_full) {
          option.disabled = true;
          option.textContent += " - FULL";
        }

        timeSelect.appendChild(option);
      });
    } catch (error) {
      console.error(error);
      resetSelect("Error loading slots");
    }
  };

  // 🔥 Validate date selection
  dateInput.addEventListener("change", (e) => {
    const selectedDate = e.target.value;

    if (!selectedDate) {
      resetSelect();
      return;
    }

    populateSlots(selectedDate);
  });

  resetSelect();
});
