<?php
/**
 * Shared MenuBar included on all protected pages.
 * Expects: $_SESSION['username'] to be set.
 */
$_menuCurrentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar" id="main-navbar">
  <a href="index.php" class="navbar-brand">
    <div class="brand-icon">CB</div>
    <span>CS-ClassB</span>
  </a>

  <ul class="navbar-links">
    <li>
      <a href="index.php" class="<?php echo $_menuCurrentPage === 'index.php' ? 'active' : ''; ?>">
        <span class="nav-icon">🏠</span>
        <span class="nav-label">Home</span>
      </a>
    </li>
    <li>
      <a href="setting.php" class="<?php echo $_menuCurrentPage === 'setting.php' ? 'active' : ''; ?>">
        <span class="nav-icon">⚙️</span>
        <span class="nav-label">Setting</span>
      </a>
    </li>
    <li>
      <a href="profile.php" class="<?php echo $_menuCurrentPage === 'profile.php' ? 'active' : ''; ?>">
        <span class="nav-icon">👤</span>
        <span class="nav-label">Profile</span>
      </a>
    </li>
    <li>
      <a href="about.php" class="<?php echo $_menuCurrentPage === 'about.php' ? 'active' : ''; ?>">
        <span class="nav-icon">ℹ️</span>
        <span class="nav-label">About</span>
      </a>
    </li>
  </ul>

  <div class="navbar-user">
    <div class="user-avatar"><?php echo strtoupper(substr($_SESSION['username'] ?? '?', 0, 1)); ?></div>
    <span class="user-name"><?php echo htmlspecialchars($_SESSION['username'] ?? ''); ?></span>
    <a href="signout.php" class="btn-signout">
      <span>↗</span> Sign Out
    </a>
  </div>
</nav>

<script>
  window.addEventListener('scroll', function() {
    document.getElementById('main-navbar')
      .classList.toggle('scrolled', window.scrollY > 10);
  });
</script>
