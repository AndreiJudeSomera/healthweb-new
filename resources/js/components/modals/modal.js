function openModal(id) {
  const el = document.getElementById(id);
  if (!el) return;

  // ensure modal becomes visible even if CSS/stacking contexts interfere
  el.classList.remove("hidden");
  el.classList.add("flex");
  el.style.display = "block";
  el.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
}

function closeModal(id) {
  const el = document.getElementById(id);
  if (!el) return;

  el.classList.add("hidden");
  el.classList.remove("flex");
  el.style.display = "none";
  el.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";
}

document.addEventListener("click", (e) => {
  const openBtn = e.target.closest("[data-modal-open]");
  if (openBtn) {
    openModal(openBtn.getAttribute("data-modal-open"));
    return;
  }

  const closeBtn = e.target.closest("[data-modal-close]");
  if (closeBtn) {
    closeModal(closeBtn.getAttribute("data-modal-close"));
  }
});

document.addEventListener("keydown", (e) => {
  if (e.key !== "Escape") return;

  const openEl = document.querySelector(".fixed.inset-0.z-50:not(.hidden)");
  if (openEl) closeModal(openEl.id);
});

// Optional: if you want to call in console
window.Modal = { open: openModal, close: closeModal };
