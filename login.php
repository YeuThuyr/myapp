<?php
// ── Temporary debug: show errors in browser ──
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/config/database.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $inputUsername = $_POST['username'] ?? '';
        $inputPassword = $_POST['password'] ?? '';

        $stmt = $conn->prepare(
            "SELECT id, username, password FROM users WHERE username = ? LIMIT 1"
        );

        $stmt->bind_param("s", $inputUsername);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row && password_verify($inputPassword, $row['password'])) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            header("Location: index.php"); // redirect homepage
            exit();

        } else {
            $error = "Invalid username or password";
        }

        $stmt->close();
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign In — CS-ClassB</title>
  <meta name="description" content="Sign in to your CS-ClassB account" />
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
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required />
        </div>

        <button type="submit" class="btn btn-primary">Sign In</button>
      </form>

      <div class="auth-footer">
        Don't have an account? <a href="register.php">Create one</a>
      </div>

    </div>
  </div>

</body>
</html>