<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>AIUB CampusConnect | Home</title>

  <link rel="stylesheet" href="studentHome.css" />
</head>

<body>
  <header class="topbar">
    <a class="brand" href="#">
      <span class="brand-mark">AC</span>
      <span class="brand-text">
        <strong class="brand-title">AIUB CampusConnect</strong>
        <small class="brand-sub">Home</small>
      </span>
    </a>

    <nav class="actions">
      <button class="icon-btn" id="themeToggle" type="button">🌙 Dark</button>

      <details class="profile" id="profileBox">
        <summary class="profile-btn">
          <span class="avatar" aria-hidden="true"></span>
          <span class="profile-meta">
            <strong class="profile-name">Student Name</strong>
            <small class="profile-role">23-50723-1</small>
          </span>
          <span class="chev" aria-hidden="true"></span>
        </summary>

        <menu class="dropdown" aria-label="Profile menu">
          <li><button class="menu-item" type="button">Edit picture</button></li>

          <li class="menu-row">
            <span class="menu-label">Teacher status</span>
            <label class="switch">
              <input type="checkbox" id="teacherToggle" />
              <span class="slider"></span>
            </label>
          </li>

          <li><button class="menu-item" type="button">Add skills</button></li>
          <li><button class="menu-item" type="button">Add course</button></li>
          <li><button class="menu-item" type="button">Add breaktime</button></li>

          <li class="menu-sep"></li>
          <li><button class="menu-item danger" type="button">Logout</button></li>
        </menu>
      </details>
    </nav>
  </header>

  <main class="layout">
    <section class="hero">
      <h1>Welcome back 👋</h1>
      <p>Match breaks, skills, or find course help — all in one place.</p>

      <form class="search" action="#" method="get">
        <input type="search" placeholder="Search by course (e.g., CSC 1102) or name..." />
        <button type="submit">Search</button>
      </form>
    </section>

    <section class="grid">
      <article class="card">
        <header class="card-head">
          <h2>Break Match</h2>
          <p>Find someone free at the same time for adda.</p>
        </header>
        <button class="card-btn" type="button">Suggest matches</button>
      </article>

      <article class="card">
        <header class="card-head">
          <h2>Skill Match</h2>
          <p>Connect with people with similar skillsets.</p>
        </header>
        <button class="card-btn" type="button">Suggest matches</button>
      </article>

      <article class="card">
        <header class="card-head">
          <h2>Course Help</h2>
          <p>Find tutors by course — Free / Treat / Paid.</p>
        </header>
        <button class="card-btn" type="button">Suggest tutors</button>
      </article>
    </section>

    <section class="content">
      <article class="panel">
        <header class="panel-head">
          <h2>Your Profile</h2>
          <p>Public/private • active/inactive • tutor profile controls</p>
        </header>

        <ul class="list">
          <li><span class="k">Name</span><span class="v">Student Name</span></li>
          <li><span class="k">Help type</span><span class="v">Free / Treat / Paid</span></li>
          <li><span class="k">Visibility</span><span class="v">Public / Private</span></li>
          <li><span class="k">Status</span><span class="v">Active / Inactive</span></li>
        </ul>

        <footer class="panel-foot">
          <button class="ghost" type="button">Open profile editor</button>
        </footer>
      </article>

      <aside class="panel">
        <header class="panel-head">
          <h2>How Chat Works</h2>
          <p>Only after you connect</p>
        </header>

        <ul class="bullets">
          <li>Text messages</li>
          <li>Submit files</li>
          <li>Send voice</li>
          <li>No call/video for now</li>
        </ul>

        <footer class="panel-foot">
          <button class="ghost" type="button">Open chats</button>
        </footer>
      </aside>
    </section>

    <footer class="footer">
      <small>© AIUB CampusConnect</small>
    </footer>
  </main>

  <script src="studentHome.js"></script>
</body>
</html>
