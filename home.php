<?php
require_once("auth.php");
require_admin();
$adminName = $_SESSION['admin_name'] ?? 'Admin';
$isDemoAdmin = is_demo_admin();
?>
<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Control Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    body { font-family: 'Poppins', sans-serif; overflow-x: hidden; margin: 0; }
    .main-wrapper { display: flex; min-height: 100vh; width: 100%; }
    .sidebar {
      background-color: #1e2a38;
      color: white;
      width: 260px;
      height: 100vh;
      position: sticky;
      top: 0;
      transition: all 0.3s ease-in-out;
      flex-shrink: 0;
      z-index: 1060;
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    .sidebar.collapsed { margin-left: -260px; }
    .sidebar .nav-link {
      color: rgba(255, 255, 255, 0.8);
      padding: 12px 20px;
      border-radius: 8px;
      margin: 4px 10px;
      transition: all 0.2s;
      cursor: pointer;
      display: block;
      text-decoration: none;
    }
    .sidebar .nav-link:hover, .sidebar .nav-link.active { background-color: #34495e; color: white; }
    .sidebar .emoji { margin-right: 12px; }
    #main-content { flex-grow: 1; display: flex; flex-direction: column; min-width: 0; transition: all 0.3s ease-in-out; }
    header {
      background-color: #1e2a38;
      color: white;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 60px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }
    .toggle-btn { font-size: 1.5rem; background: none; border: none; color: white; cursor: pointer; }
    iframe { width: 100%; flex-grow: 1; border: none; height: calc(100vh - 60px); }
    .demo-pill {
      border: 1px solid rgba(255,255,255,.22);
      background: rgba(255,255,255,.08);
      color: #fff;
      border-radius: 999px;
      font-size: .78rem;
      padding: 6px 12px;
      margin: 0 16px 0 auto;
      white-space: nowrap;
    }
    @media (max-width: 1010px) {
      .sidebar { position: fixed; left: -260px; margin-left: 0 !important; }
      .sidebar.show { left: 0; }
      #main-content { width: 100%; }
      .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1050;
      }
      .sidebar-overlay.active { display: block; }
      .demo-pill { display: none; }
    }
  </style>
</head>

<body>
  <div id="sidebarOverlay" class="sidebar-overlay"></div>

  <div class="main-wrapper">
    <nav id="sidebar" class="sidebar">
      <div class="p-4 text-center">
        <h5 class="fw-bold mb-0 text-uppercase letter-spacing-1">Admin Panel</h5>
        <?php if ($isDemoAdmin): ?>
          <div class="badge text-bg-info mt-3">Read-only Demo</div>
        <?php endif; ?>
        <hr class="border-light opacity-25">
      </div>

      <ul class="nav flex-column">
        <li class="nav-item"><a class="nav-link active" onclick="loadPage('dashboard.php', this)"><span class="emoji">📊</span>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" onclick="loadPage('analytics.php', this)"><span class="emoji">📈</span>Analytics</a></li>
        <li class="nav-item"><a class="nav-link" onclick="loadPage('smart_search.php', this)"><span class="emoji">🔎</span>Smart Search</a></li>
        <li class="nav-item"><a class="nav-link" onclick="loadPage('book_stock.php', this)"><span class="emoji">📚</span>Book Stock</a></li>
        <li class="nav-item"><a class="nav-link" onclick="loadPage('issue.html', this)"><span class="emoji">📤</span>Book Issue</a></li>
        <li class="nav-item"><a class="nav-link" onclick="loadPage('return.html', this)"><span class="emoji">📥</span>Book Return</a></li>
        <li class="nav-item"><a class="nav-link" onclick="loadPage('review_pyq.php', this)"><span class="emoji">📑</span>Review PYQs</a></li>
        <li class="nav-item"><a class="nav-link" onclick="loadPage('review_notes.php', this)"><span class="emoji">📝</span>Review Notes</a></li>
        <hr class="border-light opacity-10 mx-3">
        <li class="nav-item"><a class="nav-link" onclick="loadPage('register.html', this)"><span class="emoji">🎓</span>Register Student</a></li>
        <li class="nav-item"><a class="nav-link" onclick="loadPage('add_book.html', this)"><span class="emoji">📘</span>Add Book</a></li>
      </ul>
    </nav>

    <div id="main-content">
      <header>
        <button id="sidebarToggle" class="toggle-btn">☰</button>
        <?php if ($isDemoAdmin): ?>
          <span class="demo-pill">Demo Mode: editing actions are disabled</span>
        <?php endif; ?>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
            <img src="https://ui-avatars.com/api/?name=<?= urlencode($adminName) ?>&background=fff&color=1e2a38&bold=true"
                 width="35" height="35" class="rounded-circle me-2 border border-2 border-white">
            <span class="d-none d-sm-inline fw-bold text-uppercase" style="font-size: 0.85rem;"><?= htmlspecialchars($adminName) ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li><h6 class="dropdown-header">Theme Mode</h6></li>
            <li><button class="dropdown-item" onclick="setTheme('light')">Light</button></li>
            <li><button class="dropdown-item" onclick="setTheme('dark')">Dark</button></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger fw-bold" href="logout.php">Sign out</a></li>
          </ul>
        </div>
      </header>

      <iframe id="content-frame" src="dashboard.php"></iframe>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggleBtn = document.getElementById('sidebarToggle');
    const frame = document.getElementById('content-frame');

    toggleBtn.addEventListener('click', () => {
      if (window.innerWidth <= 1010) {
        sidebar.classList.toggle('show');
        overlay.classList.toggle('active');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    });

    overlay.addEventListener('click', () => {
      sidebar.classList.remove('show');
      overlay.classList.remove('active');
    });

    function loadPage(page, el) {
      frame.src = page;
      document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
      if (el.classList) el.classList.add('active');
      if (window.innerWidth <= 1010) {
        sidebar.classList.remove('show');
        overlay.classList.remove('active');
      }
    }

    function setTheme(theme) {
      document.documentElement.setAttribute('data-bs-theme', theme);
      localStorage.setItem('admin-theme', theme);
      syncIframeTheme(theme);
    }

    function syncIframeTheme(theme) {
      if (frame.contentDocument?.documentElement) {
        frame.contentDocument.documentElement.setAttribute('data-bs-theme', theme);
      }
    }

    frame.onload = () => {
      const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'dark';
      syncIframeTheme(currentTheme);
    };

    setTheme(localStorage.getItem('admin-theme') || 'dark');
  </script>
</body>
</html>
