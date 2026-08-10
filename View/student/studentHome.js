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

function postData(action, key, value) {
  const body = new URLSearchParams();
  body.append("action", action);
  body.append(key, value);

  return fetch("../../Controller/studentDashboardController.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: body.toString()
  }).then(r => r.json());
}

const btnSkill = document.getElementById("btnAddSkill");
const btnCourse = document.getElementById("btnAddCourse");
const btnFree = document.getElementById("btnAddFreeTime");

if (btnSkill) {
  btnSkill.addEventListener("click", function () {
    const skill = prompt("Enter a skill (e.g., C++, Java, Photoshop):");
    if (!skill) return;

    postData("addSkill", "skill", skill).then(data => {
      alert(data.msg);
      if (data.ok) location.reload();
    });
  });
}

if (btnCourse) {
  btnCourse.addEventListener("click", function () {
    const course = prompt("Enter course (e.g., CSC 1102):");
    if (!course) return;

    postData("addCourse", "course", course).then(data => {
      alert(data.msg);
      if (data.ok) location.reload();
    });
  });
}

if (btnFree) {
  btnFree.addEventListener("click", function () {
    const ft = prompt("Enter free time (e.g., Sun 2pm-4pm):");
    if (!ft) return;

    postData("addFreeTime", "free_time", ft).then(data => {
      alert(data.msg);
      if (data.ok) location.reload();
    });
  });
}

