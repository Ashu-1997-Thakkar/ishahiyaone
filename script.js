document.addEventListener("DOMContentLoaded", () => {
  // Get buttons
  const menBtn = document.getElementById("men-btn");
  const womenBtn = document.getElementById("women-btn");
  const kidsBtn = document.getElementById("kids-btn");

  // Get clothing sections
  const menClothing = document.getElementById("men-clothing");
  const womenClothing = document.getElementById("women-clothing");
  const kidsClothing = document.getElementById("kids-clothing");

  // Function to show one section and hide others
  function showCategory(category) {
    if (menClothing) menClothing.classList.remove("visible");
    if (womenClothing) womenClothing.classList.remove("visible");
    if (kidsClothing) kidsClothing.classList.remove("visible");

    if (menBtn) menBtn.classList.remove("active");
    if (womenBtn) womenBtn.classList.remove("active");
    if (kidsBtn) kidsBtn.classList.remove("active");

    if (category === "men" && menClothing && menBtn) {
      menClothing.classList.add("visible");
      menBtn.classList.add("active");
    } else if (category === "women" && womenClothing && womenBtn) {
      womenClothing.classList.add("visible");
      womenBtn.classList.add("active");
    } else if (category === "kids" && kidsClothing && kidsBtn) {
      kidsClothing.classList.add("visible");
      kidsBtn.classList.add("active");
    }
  }

  // Attach event listeners
  if (menBtn) menBtn.addEventListener("click", () => showCategory("men"));
  if (womenBtn) womenBtn.addEventListener("click", () => showCategory("women"));
  if (kidsBtn) kidsBtn.addEventListener("click", () => showCategory("kids"));

  // Show default category only if elements exist
  if (menBtn || womenBtn || kidsBtn) {
    showCategory("men");
  }

  // Optional: Profile dropdown logic
});
