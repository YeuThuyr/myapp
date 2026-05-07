<?php
session_start();
require_once __DIR__ . '/config/database.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = "";
$error = "";

// Fetch current description
$stmt = $conn->prepare("SELECT description FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$currentDescription = $row['description'] ?? '';
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $description = trim($_POST['description'] ?? '');

        $stmt = $conn->prepare("UPDATE users SET description = ? WHERE id = ?");
        $stmt->bind_param("si", $description, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();

        $currentDescription = $description;
        $success = "Description updated successfully!";
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Description — CS-ClassB</title>
  <meta name="description" content="Edit your profile description" />
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

      <div class="auth-header">
        <div class="auth-icon">✏️</div>
        <h2>Edit Description</h2>
        <p>Tell people a bit about yourself</p>
      </div>

      <?php if ($success): ?>
        <div class="alert alert-success">
          <span>✅</span> <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="alert alert-error">
          <span>⚠️</span> <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label for="description">Your Description</label>
          <textarea id="description" name="description" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($currentDescription); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">💾 Save Description</button>
      </form>

      <div style="display:flex; gap:12px; margin-top:20px;">
        <a href="profile.php" class="btn btn-secondary" style="flex:1;">👤 View Profile</a>
        <a href="index.php" class="btn btn-secondary" style="flex:1;">🏠 Back to Home</a>
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
