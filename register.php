<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/database.php';

$errors = [];
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1. Get data
    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // 2. Validate
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match!";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // 3. Insert if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $fullname, $username, $email, $hashed_password);

        if ($stmt->execute()) {
            $success = "Account created successfully! You can now sign in.";
        } else {
            $errors[] = "Username or email already exists.";
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
  <title>Create Account — CS-ClassB</title>
  <meta name="description" content="Create a new CS-ClassB account" />
  <link rel="stylesheet" href="style.css" />
</head>
<body>

  <div class="auth-wrapper">
    <div class="card auth-card">

      <div class="auth-header">
        <div class="auth-icon">🚀</div>
        <h2>Create Account</h2>
        <p>Join CS-ClassB today</p>
      </div>

      <?php if ($success): ?>
        <div class="alert alert-success">
          <span>✅</span> <?php echo htmlspecialchars($success); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
          <div class="alert alert-error">
            <span>⚠️</span> <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="form-group">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" name="fullname" placeholder="Your full name" required />
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Choose a username" required />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="you@example.com" required />
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Min. 6 characters" required />
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required />
        </div>

        <button type="submit" class="btn btn-primary">Create Account</button>
      </form>

      <div class="auth-footer">
        Already have an account? <a href="login.php">Sign in</a>
      </div>

    </div>
  </div>

</body>
</html>
