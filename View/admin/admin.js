let t = null;

window.addEventListener("load", function(){
  const input = document.getElementById("searchKey");
  if(input){
    input.addEventListener("keyup", function(){
      clearTimeout(t);
      t = setTimeout(liveSearch, 150);
    });
  }

  document.addEventListener("click", function(e){
    const wrap = document.querySelector(".search-wrap");
    if(wrap && !wrap.contains(e.target)){
      hideDrop();
    }
  });
});

function hideDrop(){
  const d = document.getElementById("dropdown");
  if(d){
    d.style.display = "none";
    d.innerHTML = "";
  }
}

function liveSearch(){
  const key = document.getElementById("searchKey").value.trim();
  const drop = document.getElementById("dropdown");

  if(key.length === 0){
    hideDrop();
    return;
  }

  const xhr = new XMLHttpRequest();
  xhr.open("GET", "../../Controller/admin/searchUser.php?key=" + encodeURIComponent(key), true);

  xhr.onreadystatechange = function(){
    if(xhr.readyState === 4 && xhr.status === 200){
      const res = xhr.responseText.trim();

      if(res === "NO_USER"){
        drop.innerHTML = "<div class='item'>No user found</div>";
        drop.style.display = "block";
      }else if(res === "ERR"){
        drop.innerHTML = "<div class='item'>Server error</div>";
        drop.style.display = "block";
      }else{
        drop.innerHTML = res;   // your PHP already prints onclick="pickUser('id')"
        drop.style.display = "block";
      }
    }
  };

  xhr.send();
}

// When you click a suggestion: just fill input + close dropdown (keeps your behavior)
function pickUser(id){
  document.getElementById("searchKey").value = id;
  hideDrop();
}

/* =========================
   TOGGLE STATUS (FIXED)
   ========================= */
function toggleUserStatus(id, currentStatus)
{
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "../../Controller/admin/toggleUser.php?id=" + encodeURIComponent(id) + "&status=" + currentStatus, true);

  xhr.onreadystatechange = function(){
    if(xhr.readyState === 4 && xhr.status === 200){
      const res = xhr.responseText.trim();

      if(res === "ERR" || res === ""){
        alert("Toggle failed");
        return;
      }

      const newStatus = parseInt(res);

      // Update status cell text + class
      const cell = document.getElementById("status_" + id);
      if(cell){
        if(newStatus === 1){
          cell.innerHTML = "Active";
          cell.classList.remove("status-inactive");
          cell.classList.add("status-active");
        }else{
          cell.innerHTML = "Inactive";
          cell.classList.remove("status-active");
          cell.classList.add("status-inactive");
        }
      }

      // Update button text AND IMPORTANT: update onclick to pass newStatus next time
      const btn = document.getElementById("btn_" + id);
      if(btn){
        btn.innerHTML = (newStatus === 1) ? "Turn Off" : "Turn On";
        btn.setAttribute("onclick", "toggleUserStatus('" + id + "'," + newStatus + ")");
      }
    }
  };

  xhr.send();
}
