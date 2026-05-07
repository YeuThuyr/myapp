<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About — CS-ClassB</title>
  <meta name="description" content="About the CS-ClassB application" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <!-- ── Navbar ── -->
  <nav class="navbar" id="main-navbar">
    <a href="index.php" class="navbar-brand">
      <div class="brand-icon">CB</div>
      <span>CS-ClassB</span>
    </a>

    <ul class="navbar-links">
      <li>
        <a href="index.php" class="<?php echo $currentPage === 'index.php' ? 'active' : ''; ?>">
          <span class="nav-icon">🏠</span>
          <span class="nav-label">Home</span>
        </a>
      </li>
      <li>
        <a href="profile.php" class="<?php echo $currentPage === 'profile.php' ? 'active' : ''; ?>">
          <span class="nav-icon">👤</span>
          <span class="nav-label">Profile</span>
        </a>
      </li>
      <li>
        <a href="settings.php" class="<?php echo $currentPage === 'settings.php' ? 'active' : ''; ?>">
          <span class="nav-icon">⚙️</span>
          <span class="nav-label">Settings</span>
        </a>
      </li>
      <li>
        <a href="about.php" class="<?php echo $currentPage === 'about.php' ? 'active' : ''; ?>">
          <span class="nav-icon">ℹ️</span>
          <span class="nav-label">About</span>
        </a>
      </li>
    </ul>

    <div class="navbar-user">
      <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'] ?? '?', 0, 1)); ?></div>
      <span class="user-name"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
      <form method="POST" action="logout.php" style="margin:0;">
        <button type="submit" class="btn-signout">
          <span>↗</span> Sign Out
        </button>
      </form>
    </div>
  </nav>

  <!-- ── Page Content ── -->
  <main class="page-content">
    <div class="simple-page" style="animation: fadeInUp 0.5s ease;">

      <h1>ℹ️ About</h1>
      <p class="subtitle">Learn more about this application.</p>

      <div class="card" style="margin-bottom: 20px;">
        <h3 style="font-size:18px; margin-bottom:12px; color: var(--text-primary);">CS-ClassB</h3>
        <p style="color: var(--text-secondary); font-size:14px; line-height:1.7; margin-bottom:16px;">
          CS-ClassB is a web-based user management platform built with PHP and MySQL.
          It provides user authentication, profile management, and a clean dashboard experience
          with a modern, responsive design.
        </p>

        <div class="info-row">
          <span class="info-label">Version</span>
          <span class="info-value">1.0.0</span>
        </div>
        <div class="info-row">
          <span class="info-label">Stack</span>
          <span class="info-value">PHP · MySQL · CSS</span>
        </div>
        <div class="info-row">
          <span class="info-label">Author</span>
          <span class="info-value">CS-ClassB Team</span>
        </div>
      </div>

      <div class="card">
        <h3 style="font-size:16px; margin-bottom:12px; color: var(--text-primary);">Features</h3>
        <ul style="list-style:none; padding:0;">
          <li style="padding:8px 0; color: var(--text-secondary); font-size:14px; border-bottom: 1px solid var(--border);">
            🔐 Secure login & registration with hashed passwords
          </li>
          <li style="padding:8px 0; color: var(--text-secondary); font-size:14px; border-bottom: 1px solid var(--border);">
            👤 Profile management with masked sensitive data
          </li>
          <li style="padding:8px 0; color: var(--text-secondary); font-size:14px; border-bottom: 1px solid var(--border);">
            ✏️ Editable user descriptions
          </li>
          <li style="padding:8px 0; color: var(--text-secondary); font-size:14px; border-bottom: 1px solid var(--border);">
            🔒 Session-based authentication
          </li>
          <li style="padding:8px 0; color: var(--text-secondary); font-size:14px;">
            🎨 Modern glassmorphism UI design
          </li>
        </ul>
      </div>

    </div>
  </main>

  <script>
    window.addEventListener('scroll', () => {
      document.getElementById('main-navbar')
        .classList.toggle('scrolled', window.scrollY > 10);
    });
  </script>

</body>
</html>
