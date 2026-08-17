const observationType = document.getElementById("observationType");
const flightFields = document.getElementById("flightFields");

if (observationType && flightFields) {
  const syncFlightFields = () => {
    const isFlight = observationType.value.toLowerCase().includes("flight");
    flightFields.classList.toggle("visible", isFlight);
  };

  observationType.addEventListener("change", syncFlightFields);
  syncFlightFields();
}


document.querySelectorAll('[data-preview]').forEach((field) => {
  const output = document.querySelector(`[data-preview-output="${field.dataset.preview}"]`);
  const update = () => {
    if (!output) return;
    const value = field.value && field.value.trim() ? field.value.trim() : 'Not entered';
    output.textContent = value;
  };
  field.addEventListener('input', update);
  field.addEventListener('change', update);
  update();
});
