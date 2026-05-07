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
  <title>Settings — CS-ClassB</title>
  <meta name="description" content="Manage your CS-ClassB settings" />
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

      <h1>⚙️ Settings</h1>
      <p class="subtitle">Manage your account preferences and configurations.</p>

      <div class="card" style="margin-bottom: 20px;">
        <div class="settings-section">
          <h3>Account</h3>
          <div class="info-row">
            <span class="info-label">Username</span>
            <span class="info-value"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Session ID</span>
            <span class="info-value masked"><?php echo substr(session_id(), 0, 8) . '••••'; ?></span>
          </div>
        </div>
      </div>

      <div class="card" style="margin-bottom: 20px;">
        <div class="settings-section">
          <h3>Quick Actions</h3>
          <div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:12px;">
            <a href="profile.php" class="btn btn-secondary">👤 Edit Profile</a>
            <a href="description.php" class="btn btn-secondary">✏️ Edit Description</a>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="settings-section">
          <h3>Danger Zone</h3>
          <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 16px;">
            Signing out will end your current session.
          </p>
          <form method="POST" action="logout.php">
            <button type="submit" class="btn" style="background: rgba(255,107,107,0.12); color: var(--danger); border: 1px solid rgba(255,107,107,0.25);">
              ↗ Sign Out
            </button>
          </form>
        </div>
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
