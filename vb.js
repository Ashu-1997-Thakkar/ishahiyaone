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
    menClothing.classList.remove("visible");
    womenClothing.classList.remove("visible");
    kidsClothing.classList.remove("visible");
    coupleClothing.classList.remove("visible");

    menBtn.classList.remove("active");
    womenBtn.classList.remove("active");
    kidsBtn.classList.remove("active");
    coupleBtn.classList.remove("active");

    if (category === "men") {
      menClothing.classList.add("visible");
      menBtn.classList.add("active");
    } else if (category === "women") {
      womenClothing.classList.add("visible");
      womenBtn.classList.add("active");
    } else if (category === "kids") {
      kidsClothing.classList.add("visible");
      kidsBtn.classList.add("active");
    } else if (category === "couple") {
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
  showCategory("men");
});
