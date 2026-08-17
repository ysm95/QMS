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
