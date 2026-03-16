const modal      = document.getElementById("profile-modal-shared");
const imageEl    = document.getElementById("profile-modal-image");
const titleEl    = document.getElementById("profile-modal-title");
const roleEl     = document.getElementById("profile-modal-role");
const credentialsEl = document.getElementById("profile-modal-credentials");
const contentEl  = document.getElementById("profile-modal-content");

document.addEventListener("click", async (e) => {
  const trigger = e.target.closest("[data-profile-id]");
  if (!trigger || !modal) return;

  const id      = trigger.dataset.profileId;
  const restUrl = window.fneslData?.restUrl;
  if (!restUrl) return;

  // Clear previous content while loading
  titleEl.textContent   = "";
  roleEl.textContent    = "";
  credentialsEl.textContent = "";
  imageEl.innerHTML     = "";
  contentEl.innerHTML   = "";
  modal.showModal();

  try {
    const res  = await fetch(restUrl + id);
    if (!res.ok) throw new Error("Failed to load profile");
    const data = await res.json();

    titleEl.textContent  = data.title;
    roleEl.textContent   = data.role;
    credentialsEl.textContent = data.credentials;
    imageEl.innerHTML    = data.image
      ? data.image.replace("<img", '<img class="w-full h-full object-cover"')
      : "";
    contentEl.innerHTML  = data.content;
  } catch (err) {
    contentEl.innerHTML = "<p>Could not load profile.</p>";
  }
});
