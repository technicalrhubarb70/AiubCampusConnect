let t = null;

window.addEventListener("load", function () {

  /* ===== LIVE SEARCH ===== */
  const input = document.getElementById("searchKey");
  if (input) {
    input.addEventListener("keyup", function () {
      clearTimeout(t);
      t = setTimeout(liveSearch, 150);
    });
  }

  document.addEventListener("click", function (e) {
    const wrap = document.querySelector(".search-wrap");
    if (wrap && !wrap.contains(e.target)) {
      hideDrop();
    }
  });

  /* ===== THEME TOGGLE ===== */
  const btn = document.getElementById("themeBtn");
  if (btn) {

    const saved = localStorage.getItem("adminTheme");
    if (saved === "dark") {
      document.body.classList.add("dark");
    }

    btn.textContent = document.body.classList.contains("dark") ? "Light" : "Dark";

    btn.addEventListener("click", function () {
      document.body.classList.toggle("dark");

      const isDark = document.body.classList.contains("dark");
      localStorage.setItem("adminTheme", isDark ? "dark" : "light");

      btn.textContent = isDark ? "Light" : "Dark";
    });
  }

});

function hideDrop() {
  const d = document.getElementById("dropdown");
  if (d) {
    d.style.display = "none";
    d.innerHTML = "";
  }
}

function liveSearch() {
  const input = document.getElementById("searchKey");
  const drop = document.getElementById("dropdown");

  if (!input || !drop) return;

  const key = input.value.trim();

  if (key.length === 0) {
    hideDrop();
    return;
  }

  const xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "../../Controller/admin/searchUser.php?key=" + encodeURIComponent(key),
    true
  );

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {
      const res = xhr.responseText.trim();

      if (res === "NO_USER") {
        drop.innerHTML = "<div class='item'>No user found</div>";
        drop.style.display = "block";
      } else if (res === "ERR") {
        drop.innerHTML = "<div class='item'>Server error</div>";
        drop.style.display = "block";
      } else {
        drop.innerHTML = res;
        drop.style.display = "block";
      }
    }
  };

  xhr.send();
}

function pickUser(id) {
  window.location.href = "adminHome.php?searchId=" + encodeURIComponent(id);
}

function toggleUserStatus(id, currentStatus) {

  const xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "../../Controller/admin/toggleUser.php?id=" +
      encodeURIComponent(id) +
      "&status=" +
      encodeURIComponent(currentStatus),
    true
  );

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4 && xhr.status === 200) {

      const newStatus = parseInt(xhr.responseText.trim(), 10);
      if (!newStatus && newStatus !== 0) return;

      const cell = document.getElementById("status_" + id);
      if (cell) {
        if (newStatus === 1) {
          cell.innerHTML = "Active";
          cell.className = "status-active";
        } else {
          cell.innerHTML = "Inactive";
          cell.className = "status-inactive";
        }
      }

      const btn = document.getElementById("btn_" + id);
      if (btn) {
        btn.innerHTML = newStatus === 1 ? "Turn Off" : "Turn On";
        btn.setAttribute(
          "onclick",
          "toggleUserStatus('" + id + "'," + newStatus + ")"
        );
      }
    }
  };

  xhr.send();
}
