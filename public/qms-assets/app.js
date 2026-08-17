const navItems = document.querySelectorAll(".nav-item");
const views = document.querySelectorAll(".view");
const toast = document.getElementById("toast");
const search = document.getElementById("globalSearch");
const observationType = document.getElementById("observationType");
const flightFields = document.getElementById("flightFields");
const reportForm = document.getElementById("reportForm");
const saveDraft = document.getElementById("saveDraft");
const rtlToggle = document.getElementById("rtlToggle");

function showToast(message) {
  toast.textContent = message;
  toast.classList.add("visible");
  window.setTimeout(() => toast.classList.remove("visible"), 2600);
}

function openView(viewId) {
  views.forEach((view) => view.classList.toggle("active-view", view.id === viewId));
  navItems.forEach((item) => item.classList.toggle("active", item.dataset.view === viewId));
  window.scrollTo({ top: 0, behavior: "smooth" });
}

navItems.forEach((item) => {
  item.addEventListener("click", () => openView(item.dataset.view));
});

document.querySelectorAll("[data-view-shortcut]").forEach((button) => {
  button.addEventListener("click", () => openView(button.dataset.viewShortcut));
});

if (observationType) {
  observationType.addEventListener("change", () => {
    const isFlight = observationType.value.toLowerCase().includes("flight");
    flightFields.classList.toggle("visible", isFlight);
  });
}

if (reportForm) {
  reportForm.addEventListener("submit", (event) => {
    if (reportForm.dataset.server === "true") {
      return;
    }

    event.preventDefault();
    showToast("Report submitted to HSE Review. Workflow QMS-2026-00436 created.");
    openView("records");
  });
}

if (saveDraft) {
  saveDraft.addEventListener("click", () => {
    showToast("Draft saved locally for prototype review.");
  });
}

if (rtlToggle) {
  rtlToggle.addEventListener("click", () => {
    const next = document.documentElement.dir === "rtl" ? "ltr" : "rtl";
    document.documentElement.dir = next;
    document.documentElement.lang = next === "rtl" ? "ar" : "en";
    showToast(next === "rtl" ? "Arabic / RTL preview enabled." : "English / LTR preview enabled.");
  });
}

if (search) {
  search.addEventListener("keydown", (event) => {
    if (event.key === "Enter") {
      const query = search.value.trim();
      if (query) {
        showToast(`Search prototype: showing authorized results for "${query}".`);
        openView("records");
      }
    }
  });
}

document.querySelectorAll(".admin-grid button").forEach((button) => {
  button.addEventListener("click", () => {
    showToast(`${button.textContent} configuration panel will open in the Laravel build.`);
  });
});

document.querySelectorAll(".record-layout button, #actions .primary-button, #audit .primary-button, #risk .primary-button, #documents .primary-button, #bi .primary-button").forEach((button) => {
  button.addEventListener("click", () => {
    showToast(`${button.textContent} captured in prototype workflow.`);
  });
});
