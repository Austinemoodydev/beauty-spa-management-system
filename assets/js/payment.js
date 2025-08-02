document.addEventListener("DOMContentLoaded", () => {
    const methodField = document.getElementById("method");
    const mpesaField = document.getElementById("mpesaField");

    if (methodField && mpesaField) {
        methodField.addEventListener("change", () => {
            mpesaField.style.display = methodField.value === "Mpesa" ? "block" : "none";
        });
        methodField.dispatchEvent(new Event("change")); // Show/hide on page load
    }
});
