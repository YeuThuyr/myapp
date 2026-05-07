<?php
/**
 * Profile Page
 * URL: /socialnet/profile.php
 *       /socialnet/profile.php?owner=username
 *
 * If ?owner=<username> is provided, show that user's profile.
 * Otherwise show the logged-in user's own profile.
 */

require_once __DIR__ . '/includes/auth.php';
requireLogin();

require_once __DIR__ . '/includes/db.php';

$isOwnProfile = true;
$profileUser  = null;

if (isset($_GET['owner']) && trim($_GET['owner']) !== '') {
    // Show another user's profile
    $ownerUsername = trim($_GET['owner']);

    $stmt = $conn->prepare(
        "SELECT id, username, fullname, description FROM account WHERE username = ? LIMIT 1"
    );
    $stmt->bind_param("s", $ownerUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    $profileUser = $result->fetch_assoc();
    $stmt->close();

    if (!$profileUser) {
        $notFound = true;
    } else {
        $isOwnProfile = ($profileUser['id'] == $_SESSION['user_id']);
    }
} else {
    // Show own profile
    $stmt = $conn->prepare(
        "SELECT id, username, fullname, description FROM account WHERE id = ? LIMIT 1"
    );
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $profileUser = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    <?php
      if (!empty($notFound)) {
          echo "User Not Found";
      } else {
          echo htmlspecialchars($profileUser['fullname']) . "'s Profile";
      }
    ?> — CS-ClassB
  </title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <?php include __DIR__ . '/includes/menubar.php'; ?>

  <main class="page-content">
    <div class="card card-centered" style="animation: fadeInUp 0.5s ease;">

      <?php if (!empty($notFound)): ?>
        <!-- User not found -->
        <div style="text-align:center; padding:40px 0;">
          <div style="font-size:48px; margin-bottom:16px;">🔍</div>
          <h2 style="margin-bottom:8px;">User Not Found</h2>
          <p style="color:var(--text-muted);">
            The user "<strong><?php echo htmlspecialchars($ownerUsername); ?></strong>" does not exist.
          </p>
          <a href="index.php" class="btn btn-secondary" style="margin-top:20px;">← Back to Home</a>
        </div>

      <?php else: ?>
        <!-- Profile content -->
        <div class="profile-hero">
          <div class="profile-avatar-lg">
            <?php echo strtoupper(substr($profileUser['username'], 0, 1)); ?>
          </div>
          <h2><?php echo htmlspecialchars($profileUser['fullname']); ?></h2>
          <div class="profile-username">@<?php echo htmlspecialchars($profileUser['username']); ?></div>
          <?php if ($isOwnProfile): ?>
            <div style="margin-top:8px;">
              <span style="background:var(--surface-hover); padding:4px 12px; border-radius:var(--radius-xl); font-size:12px; color:var(--accent); font-weight:600;">
                ✦ This is your profile
              </span>
            </div>
          <?php endif; ?>
        </div>

        <div class="info-table">
          <div class="info-row">
            <span class="info-label">Username</span>
            <span class="info-value">@<?php echo htmlspecialchars($profileUser['username']); ?></span>
          </div>
          <div class="info-row">
            <span class="info-label">Full Name</span>
            <span class="info-value"><?php echo htmlspecialchars($profileUser['fullname']); ?></span>
          </div>
        </div>

        <div class="description-section">
          <div class="description-label">Profile Content</div>
          <?php if (!empty($profileUser['description'])): ?>
            <div class="description-box"><?php echo htmlspecialchars($profileUser['description']); ?></div>
          <?php else: ?>
            <div class="description-box empty">No description added yet.</div>
          <?php endif; ?>
        </div>

        <div class="profile-actions">
          <?php if ($isOwnProfile): ?>
            <a href="setting.php" class="btn btn-accent">✏️ Edit Profile Content</a>
          <?php endif; ?>
          <a href="index.php" class="btn btn-secondary">🏠 Back to Home</a>
        </div>

      <?php endif; ?>

    </div>
  </main>

</body>
</html>
