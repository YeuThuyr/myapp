<?php
/**
 * Sign In Page
 * URL: /socialnet/signin.php
 */

require_once __DIR__ . '/includes/auth.php';

// Already logged in → go home
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/includes/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $inputUsername = trim($_POST['username'] ?? '');
    $inputPassword = $_POST['password'] ?? '';

    $stmt = $conn->prepare(
        "SELECT id, username, fullname, password FROM account WHERE username = ? LIMIT 1"
    );
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    $row    = $result->fetch_assoc();

    if ($row && password_verify($inputPassword, $row['password'])) {
        $_SESSION['user_id']  = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['fullname'] = $row['fullname'];

        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid username or password.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In — CS-ClassB</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="auth-wrapper">
    <div class="card auth-card">

      <div class="auth-header">
        <div class="auth-icon">🔐</div>
        <h2>Welcome Back</h2>
        <p>Sign in to continue to CS-ClassB</p>
      </div>

      <?php if ($error): ?>
        <div class="alert alert-error">
          <span>⚠️</span> <?php echo htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <?php echo getCsrfField(); ?>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required
                 value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required />
        </div>

        <button type="submit" class="btn btn-primary">Sign In</button>
      </form>

      <div class="auth-footer">
        Don't have an account? Contact the administrator.
      </div>

    </div>
  </div>

</body>
</html>
