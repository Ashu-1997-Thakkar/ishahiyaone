document.addEventListener("DOMContentLoaded", () => {
  // Get buttons
  const menBtn = document.getElementById("men-btn");
  const womenBtn = document.getElementById("women-btn");
  const kidsBtn = document.getElementById("kids-btn");
  const coupleBtn = document.getElementById("Couple-btn");

  // Get clothing sections
  const menClothing = document.getElementById("men-clothing");
  const womenClothing = document.getElementById("women-clothing");
  const kidsClothing = document.getElementById("kids-clothing");
  const coupleClothing = document.getElementById("couple-combo");

  // Function to show one section and hide others
  function showCategory(category) {
    if (menClothing) menClothing.classList.remove("visible");
    if (womenClothing) womenClothing.classList.remove("visible");
    if (kidsClothing) kidsClothing.classList.remove("visible");
    if (coupleClothing) coupleClothing.classList.remove("visible");

    if (menBtn) menBtn.classList.remove("active");
    if (womenBtn) womenBtn.classList.remove("active");
    if (kidsBtn) kidsBtn.classList.remove("active");
    if (coupleBtn) coupleBtn.classList.remove("active");

    if (category === "men" && menClothing && menBtn) {
      menClothing.classList.add("visible");
      menBtn.classList.add("active");
    } else if (category === "women" && womenClothing && womenBtn) {
      womenClothing.classList.add("visible");
      womenBtn.classList.add("active");
    } else if (category === "kids" && kidsClothing && kidsBtn) {
      kidsClothing.classList.add("visible");
      kidsBtn.classList.add("active");
    } else if (category === "couple" && coupleClothing && coupleBtn) {
      coupleClothing.classList.add("visible");
      coupleBtn.classList.add("active");
    }
  }

  // Attach event listeners
  if (menBtn) menBtn.addEventListener("click", () => showCategory("men"));
  if (womenBtn) womenBtn.addEventListener("click", () => showCategory("women"));
  if (kidsBtn) kidsBtn.addEventListener("click", () => showCategory("kids"));
  if (coupleBtn) coupleBtn.addEventListener("click", () => showCategory("couple"));

  // Show default category
  if (menBtn || womenBtn || kidsBtn || coupleBtn) {
    showCategory("men");
  }
});
