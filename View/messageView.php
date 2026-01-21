<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Chat Layout</title>
  <style>
    /* layout only — no colors, no rounding */
    html, body { height: 100%; margin: 0; }
    body { font-family: Arial, sans-serif; }

    main { height: 100%; display: flex; }

    /* Left: 1/4 */
    aside {
      width: 25%;
      border-right: 1px solid #000;
      display: flex;
      flex-direction: column;
    }

    /* Right: 3/4 */
    section.chat {
      width: 75%;
      display: flex;
      flex-direction: column;
    }

    header, footer { padding: 8px; border-bottom: 1px solid #000; }
    aside header { border-bottom: 1px solid #000; }
    section.chat header { border-bottom: 1px solid #000; }

    /* users list area */
    nav.users {
      flex: 1;
      overflow: auto;
      padding: 8px;
    }
    nav.users ul { margin: 0; padding-left: 18px; }
    nav.users li { margin: 6px 0; }

    /* messages area */
    article.messages {
      flex: 1;
      overflow: auto;
      padding: 8px;
      border-bottom: 1px solid #000;
    }

    /* input area */
    form.composer {
      padding: 8px;
      display: flex;
      gap: 8px;
      align-items: center;
    }
    form.composer input[type="text"] { flex: 1; }
  </style>
</head>

<body>
  <main>
    <!-- LEFT (1/4) -->
    <aside>
      <header>
        <button type="button">My Profile</button>
        <form action="#" method="get">
          <label>
            Search user
            <input type="search" name="q" />
          </label>
          <button type="submit">Search</button>
        </form>
      </header>

      <nav class="users" aria-label="Connected users">
        <h2>Users</h2>
        <ul>
          <li><a href="#">User 1</a></li>
          <li><a href="#">User 2</a></li>
          <li><a href="#">User 3</a></li>
          <!-- add more connected users -->
        </ul>
      </nav>
    </aside>

    <!-- RIGHT (3/4) -->
    <section class="chat" aria-label="Chat panel">
      <header>
        <h1>Receiver Name</h1>
      </header>

      <article class="messages" aria-label="Messages">
        <p><strong>Receiver:</strong> Hello</p>
        <p><strong>Me:</strong> Hi</p>
        <!-- messages go here -->
      </article>

      <footer>
        <form class="composer" action="#" method="post" enctype="multipart/form-data">
          <label>
            Message
            <input type="text" name="message" autocomplete="off" />
          </label>

          <label>
            File
            <input type="file" name="attachment" />
          </label>

          <button type="submit">Send</button>
        </form>
      </footer>
    </section>
  </main>
</body>
</html>
