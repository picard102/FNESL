document.addEventListener("click", (e) => {
  const trigger = e.target.closest("[data-profile-modal]");
  if (!trigger) return;

  const modal = document.getElementById(trigger.dataset.profileModal);
  if (modal) modal.showModal();
});
