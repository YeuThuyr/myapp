<?php
/**
 * Admin — Create New User
 * URL: /admin/newuser.php
 */

require_once __DIR__ . '/../includes/db.php';

$errors  = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username    = trim($_POST['username'] ?? '');
    $fullname    = trim($_POST['fullname'] ?? '');
    $password    = $_POST['password'] ?? '';
    $description = trim($_POST['description'] ?? '');

    // Validate
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    if (empty($fullname)) {
        $errors[] = "Full name is required.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    // Insert if valid
    if (empty($errors)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $desc   = $description !== '' ? $description : null;

        $stmt = $conn->prepare(
            "INSERT INTO account (username, fullname, password, description) VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $username, $fullname, $hashed, $desc);

        if ($stmt->execute()) {
            $success = "User \"" . htmlspecialchars($username) . "\" created successfully!";
        } else {
            $errors[] = "Username already exists.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin — Create New User</title>
  <link rel="stylesheet" href="../style.css" />
</head>
<body>

  <div class="auth-wrapper">
    <div class="card auth-card">

      <div class="auth-header">
        <div class="auth-icon">🛡️</div>
        <h2>Admin — New User</h2>
        <p>Create a new account in the system</p>
      </div>

      <?php if ($success): ?>
        <div class="alert alert-success">
          <span>✅</span> <?php echo $success; ?>
        </div>
      <?php endif; ?>

      <?php foreach ($errors as $err): ?>
        <div class="alert alert-error">
          <span>⚠️</span> <?php echo htmlspecialchars($err); ?>
        </div>
      <?php endforeach; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="e.g. johndoe" required
                 value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />
        </div>

        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" name="fullname" placeholder="e.g. John Doe" required
                 value="<?php echo htmlspecialchars($_POST['fullname'] ?? ''); ?>" />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Min. 6 characters" required />
        </div>

        <div class="form-group">
          <label for="description">Description <span style="color:var(--text-muted);">(optional)</span></label>
          <textarea id="description" name="description" placeholder="A short bio..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Create User</button>
      </form>

      <div class="auth-footer">
        <a href="../signin.php">← Go to Sign In</a>
      </div>

    </div>
  </div>

</body>
</html>
