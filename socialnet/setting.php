<?php
/**
 * Setting Page
 * URL: /socialnet/setting.php
 *
 * Edit the logged-in user's description (profile content).
 */

require_once __DIR__ . '/includes/auth.php';
requireLogin();

require_once __DIR__ . '/includes/db.php';

$success = "";
$error   = "";

// Fetch current description
$stmt = $conn->prepare("SELECT description FROM account WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$currentDescription = $row['description'] ?? '';
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description'] ?? '');

    $stmt = $conn->prepare("UPDATE account SET description = ? WHERE id = ?");
    $stmt->bind_param("si", $description, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $currentDescription = $description;
        $success = "Description updated successfully!";
    } else {
        $error = "Failed to update description.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Settings — CS-ClassB</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>

  <?php include __DIR__ . '/includes/menubar.php'; ?>

  <main class="page-content">
    <div class="card card-centered" style="animation: fadeInUp 0.5s ease;">

      <div class="auth-header">
        <div class="auth-icon">✏️</div>
        <h2>Edit Profile Content</h2>
        <p>Update the description shown on your profile page</p>
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
          <textarea id="description" name="description"
                    placeholder="Tell us about yourself..."><?php echo htmlspecialchars($currentDescription); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">💾 Save Description</button>
      </form>

      <div style="display:flex; gap:12px; margin-top:20px;">
        <a href="profile.php" class="btn btn-secondary" style="flex:1;">👤 View Profile</a>
        <a href="index.php" class="btn btn-secondary" style="flex:1;">🏠 Back to Home</a>
      </div>

    </div>
  </main>

</body>
</html>
