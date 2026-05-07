<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user info
$stmt = $conn->prepare("SELECT id, fullname, username, email, password, description FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Mask sensitive fields
function maskValue($value) {
    if (empty($value)) return '—';
    $len = strlen($value);
    if ($len <= 2) return str_repeat('*', $len);
    return substr($value, 0, 1) . str_repeat('*', $len - 2) . substr($value, -1);
}

function maskEmail($email) {
    if (empty($email)) return '—';
    $parts = explode('@', $email);
    if (count($parts) !== 2) return maskValue($email);
    $name = $parts[0];
    $domain = $parts[1];
    $maskedName = strlen($name) <= 2
        ? str_repeat('*', strlen($name))
        : substr($name, 0, 1) . str_repeat('*', strlen($name) - 2) . substr($name, -1);
    return $maskedName . '@' . $domain;
}

$maskedPassword = '•••••••';
$maskedEmail = maskEmail($user['email'] ?? '');

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>My Profile — CS-ClassB</title>
  <meta name="description" content="View your CS-ClassB profile" />
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
    <div class="card card-centered" style="animation: fadeInUp 0.5s ease;">

      <div class="profile-hero">
        <div class="profile-avatar-lg">
          <?php echo strtoupper(substr($user['username'] ?? '?', 0, 1)); ?>
        </div>
        <h2><?php echo htmlspecialchars($user['fullname'] ?? $user['username']); ?></h2>
        <div class="profile-username">@<?php echo htmlspecialchars($user['username']); ?></div>
      </div>

      <div class="info-table">
        <div class="info-row">
          <span class="info-label">ID</span>
          <span class="info-value"><?php echo htmlspecialchars($user['id']); ?></span>
        </div>

        <div class="info-row">
          <span class="info-label">Full Name</span>
          <span class="info-value"><?php echo htmlspecialchars($user['fullname'] ?? '—'); ?></span>
        </div>

        <div class="info-row">
          <span class="info-label">Username</span>
          <span class="info-value"><?php echo htmlspecialchars($user['username']); ?></span>
        </div>

        <div class="info-row">
          <span class="info-label">Email</span>
          <span class="info-value masked"><?php echo htmlspecialchars($maskedEmail); ?></span>
        </div>

        <div class="info-row">
          <span class="info-label">Password</span>
          <span class="info-value masked"><?php echo $maskedPassword; ?></span>
        </div>
      </div>

      <div class="description-section">
        <div class="description-label">Description</div>
        <?php if (!empty($user['description'])): ?>
          <div class="description-box"><?php echo htmlspecialchars($user['description']); ?></div>
        <?php else: ?>
          <div class="description-box empty">No description added yet.</div>
        <?php endif; ?>
      </div>

      <div class="profile-actions">
        <a href="description.php" class="btn btn-accent">✏️ Edit Description</a>
        <a href="index.php" class="btn btn-secondary">🏠 Back to Home</a>
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
