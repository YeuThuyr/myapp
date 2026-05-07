<?php
/**
 * Home Page
 * URL: /socialnet/index.php
 *
 * Shows the logged-in user's info and a list of other users.
 */

require_once __DIR__ . '/includes/auth.php';
requireLogin();

require_once __DIR__ . '/includes/db.php';

// Fetch all other users
$others = [];
$stmt = $conn->prepare(
    "SELECT id, username, fullname FROM account WHERE id != ? ORDER BY id ASC"
);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $others[] = $row;
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Home — CS-ClassB</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>

  <?php include __DIR__ . '/includes/menubar.php'; ?>

  <main class="page-content">

    <!-- Welcome banner -->
    <div class="home-hero">
      <div class="greeting-emoji">👋</div>
      <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?>!</h1>
      <p>Signed in as <strong>@<?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
    </div>

    <!-- Other users list -->
    <div class="card card-wide" style="animation: fadeInUp 0.5s ease;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
          <h2 style="font-size:22px; font-weight:700; margin-bottom:4px;">Other Users</h2>
          <p style="color:var(--text-muted); font-size:14px;">Click a user to view their profile.</p>
        </div>
        <div style="background:var(--surface-hover); padding:8px 16px; border-radius:var(--radius-xl); font-size:14px; font-weight:600; color:var(--primary-light);">
          <?php echo count($others); ?> user<?php echo count($others) !== 1 ? 's' : ''; ?>
        </div>
      </div>

      <?php if (empty($others)): ?>
        <p style="text-align:center; color:var(--text-muted); padding:32px 0;">No other users in the system yet.</p>
      <?php else: ?>
        <div class="table-responsive">
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Full Name</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($others as $u): ?>
                <tr>
                  <td>
                    <div class="user-cell">
                      <div class="user-cell-avatar">
                        <?php echo strtoupper(substr($u['username'], 0, 1)); ?>
                      </div>
                      <span class="user-cell-name">@<?php echo htmlspecialchars($u['username']); ?></span>
                    </div>
                  </td>
                  <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                  <td style="text-align:right;">
                    <a href="profile.php?owner=<?php echo urlencode($u['username']); ?>"
                       class="btn btn-secondary" style="padding:8px 16px; font-size:13px;">
                      View Profile →
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

  </main>

</body>
</html>
