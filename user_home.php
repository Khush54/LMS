<?php
session_start();
if (!isset($_SESSION['student_name'])) {
    header("Location: index.html"); // Redirect if not logged in
    exit();
}
$studentName = $_SESSION['student_name'];
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>User Control Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      overflow-x: hidden;
      font-size: 1rem;
    }

    .main-wrapper {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar styles */
    .sidebar {
      background-color: #1e293b;
      color: white;
      width: 250px;
      transition: all 0.3s ease;
    }

    .sidebar.collapsed {
      width: 0;
      overflow: hidden;
    }

    .sidebar .nav-link {
      color: white;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      transition: background-color 0.2s ease;
      font-size: 1rem;
    }

    .sidebar .nav-link:hover {
      background-color: #334155;
    }

    .sidebar .emoji {
      margin-right: 8px;
    }

    /* Main content */
    #main-content {
      flex-grow: 1;
      padding: 1rem;
      transition: all 0.3s ease;
    }

    iframe {
      width: 100%;
      height: calc(100vh - 70px);
      border: none;
    }

    /* Header */
    header {
      background-color: #1e293b;
      color: white;
      padding: 0.75rem 1rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 1050;
    }

    .toggle-btn {
      font-size: 1.5rem;
      cursor: pointer;
      color: white;
      border: none;
      background: none;
    }

    /* Responsive for small screens */
    @media (max-width: 768px) {
      .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        padding-top: 70px;
        height: 100vh;
        z-index: 1040;
      }

      .toggle-btn {
        font-size: 1.3rem;
      }

      header {
        font-size: 0.95rem;
      }

      #username-placeholder {
        font-size: 0.95rem;
      }

      .dropdown-menu {
        font-size: 0.9rem;
      }
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header>
    <button id="sidebarToggle" class="toggle-btn" aria-label="Toggle sidebar">☰</button>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-uppercase fs-7 fw-bold text-light text-decoration-none dropdown-toggle"
        data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="User" width="32" height="32"
          class="rounded-circle me-2" />
        <span id="username-placeholder"><?php echo htmlspecialchars($studentName); ?></span>
      </a>
      <ul class="dropdown-menu dropdown-menu-end shadow">
        <li><h6 class="dropdown-header">Theme</h6></li>
        <li><button class="dropdown-item" data-bs-theme-value="light">🌞 Light</button></li>
        <li><button class="dropdown-item" data-bs-theme-value="dark">🌙 Dark</button></li>
        <li><button class="dropdown-item" data-bs-theme-value="auto">🌓 Auto</button></li>
        <li><hr class="dropdown-divider" /></li>
        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
      </ul>
    </div>
  </header>

  <!-- Layout -->
  <div class="main-wrapper">
    <!-- Sidebar -->
    <nav id="sidebar" class="sidebar">
      <ul class="nav nav-pills flex-column p-3">
        <li class="nav-item mb-2"><a class="nav-link" onclick="loadPage('user_dashboard.php')"><span class="emoji">📊</span>Dashboard</a></li>
        <li class="nav-item mb-2"><a class="nav-link" onclick="loadPage('book_stock.php')"><span class="emoji">📚</span>Book Stock</a></li>
        <li class="nav-item mb-2"><a class="nav-link" onclick="loadPage('issuedbooks.php')"><span class="emoji">📤</span>Issued Books</a></li>
        <li class="nav-item mb-2"><a class="nav-link" onclick="loadPage('returnedbooks.php')"><span class="emoji">📥</span>Returned Books</a></li>
        <li class="nav-item mb-2"><a class="nav-link" onclick="loadPage('upload_pyq.html')"><span class="emoji">📑</span>Add PYQs</a></li>
        <li class="nav-item mb-2"><a class="nav-link" onclick="loadPage('upload_notes.html')"><span class="emoji">📝</span>Add Notes</a></li>
      </ul>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
      <iframe id="content-frame" src="user_dashboard.php"></iframe>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');

    // Toggle sidebar collapse on every toggle button click (all screen sizes)
    toggleBtn.addEventListener('click', () => {
      sidebar.classList.toggle('collapsed');
    });

    

    // Theme toggling
    document.querySelectorAll('[data-bs-theme-value]').forEach(button => {
      button.addEventListener('click', () => {
        const theme = button.getAttribute('data-bs-theme-value');
        document.documentElement.setAttribute('data-bs-theme', theme);

        const iframe = document.getElementById('content-frame');
        if (iframe?.contentDocument?.documentElement) {
          iframe.contentDocument.documentElement.setAttribute('data-bs-theme', theme);
        }
      });
    });

    // Load page into iframe
    function loadPage(page) {
      const frame = document.getElementById('content-frame');
      frame.src = page;
      frame.onload = () => {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        if (frame?.contentDocument?.documentElement) {
          frame.contentDocument.documentElement.setAttribute('data-bs-theme', currentTheme);
        }
      };
    }
  </script>
</body>

</html>
