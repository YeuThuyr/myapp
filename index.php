<?php
session_start();
require_once __DIR__ . '/config/database.php';

// If not logged in → redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all users
$users = [];
$dbError = null;
try {
    $stmt = $conn->prepare("SELECT id, fullname, username, email, description FROM users ORDER BY id ASC");
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        $stmt->close();
    } else {
        $dbError = $conn->error;
    }
} catch (Exception $e) {
    $dbError = $e->getMessage();
}

// Determine active page for nav highlight
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home — CS-ClassB</title>
  <meta name="description" content="Welcome to CS-ClassB dashboard" />
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
    <div class="card card-wide" style="animation: fadeInUp 0.5s ease;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <div>
          <h2 style="font-size: 24px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px;">User Directory</h2>
          <p style="color: var(--text-muted); font-size: 14px;">A list of all users registered in CS-ClassB.</p>
        </div>
        <div style="background: var(--surface-hover); padding: 8px 16px; border-radius: var(--radius-xl); font-size: 14px; font-weight: 600; color: var(--primary-light);">
          Total Users: <?php echo count($users); ?>
        </div>
      </div>

      <?php if (!empty($dbError)): ?>
        <div class="alert alert-error" style="margin-bottom: 24px;">
          <span>⚠️</span> Error fetching users: <?php echo htmlspecialchars($dbError); ?>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>User</th>
              <th>Email</th>
              <th>Description</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $u): ?>
              <tr>
                <td class="muted">#<?php echo htmlspecialchars($u['id']); ?></td>
                <td>
                  <div class="user-cell">
                    <div class="user-cell-avatar">
                      <?php echo strtoupper(substr($u['username'] ?? '?', 0, 1)); ?>
                    </div>
                    <div class="user-cell-info">
                      <span class="user-cell-name"><?php echo htmlspecialchars($u['fullname'] ?: $u['username']); ?></span>
                      <span class="user-cell-username">@<?php echo htmlspecialchars($u['username']); ?></span>
                    </div>
                  </div>
                </td>
                <td>
                  <?php
                    $email = $u['email'];
                    if (empty($email)) {
                        echo '<span class="muted">—</span>';
                    } else {
                        echo htmlspecialchars($email);
                    }
                  ?>
                </td>
                <td>
                  <?php
                    $desc = $u['description'];
                    if (empty($desc)) {
                        echo '<span class="muted" style="font-style:italic;">No description</span>';
                    } else {
                        $shortDesc = strlen($desc) > 50 ? substr($desc, 0, 47) . '...' : $desc;
                        echo htmlspecialchars($shortDesc);
                    }
                  ?>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($users)): ?>
              <tr>
                <td colspan="4" style="text-align: center; padding: 32px; color: var(--text-muted);">
                  No users found in the database.
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
      document.getElementById('main-navbar')
        .classList.toggle('scrolled', window.scrollY > 10);
    });
  </script>

</body>
</html>