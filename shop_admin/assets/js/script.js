function openNav() {
  const sidebar = document.getElementById("mySidebar");
  const main = document.getElementById("main");
  const mainContent = document.getElementById("main-content");
  if (sidebar) sidebar.style.width = "260px";
  if (main) {
    main.style.marginLeft = "260px";
    main.style.display = "none";
  }
  if (mainContent) mainContent.style.marginLeft = "260px";
}

function closeNav() {
  const sidebar = document.getElementById("mySidebar");
  const main = document.getElementById("main");
  const mainContent = document.getElementById("main-content");
  if (sidebar) sidebar.style.width = "0";
  if (main) {
    main.style.marginLeft = "0";
    main.style.display = "block";
  }
  if (mainContent) mainContent.style.marginLeft = "0";
}