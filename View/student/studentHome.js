document.addEventListener("DOMContentLoaded", function () {
  // Theme toggle via DOM manipulation
  const themeToggle = document.getElementById("themeToggle");
  themeToggle.addEventListener("click", function () {
    document.body.classList.toggle("light");
    themeToggle.textContent = document.body.classList.contains("light")
      ? "☀️ Light"
      : "🌙 Dark";
  });

  // Close profile dropdown when clicking outside + ESC
  const profileBox = document.getElementById("profileBox");

  document.addEventListener("click", function (e) {
    if (!profileBox.contains(e.target)) {
      profileBox.removeAttribute("open");
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      profileBox.removeAttribute("open");
    }
  });
});
