<?php
$errors = [];

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

    // 3. ONLY connect if no validation errors
    if (empty($errors)) {

        $conn = new mysqli("localhost", "myapp_user", "your_strong_password", "grade_management");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // 4. Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // 5. Insert
        $stmt = $conn->prepare(
            "INSERT INTO users (fullname, username, email, password) VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param("ssss", $fullname, $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Register successful!');</script>";
        } else {
            $errors[] = "Username or email already exists.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register User</title>

  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #6a11cb, #2575fc);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background: #fff;
      padding: 30px 40px;
      border-radius: 10px;
      width: 350px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 10px;
      margin-bottom: 5px;
      color: #555;
    }

    input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      outline: none;
      transition: 0.3s;
    }

    input:focus {
      border-color: #2575fc;
      box-shadow: 0 0 5px rgba(37, 117, 252, 0.5);
    }

    button {
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      background: #2575fc;
      border: none;
      color: white;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #1a5edb;
    }

    .error-box {
      background: #ffe0e0;
      color: #b30000;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
    }

    .error-box p {
      margin: 5px 0;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>Register</h2>

    <?php if (!empty($errors)): ?>
      <div class="error-box">
        <?php foreach ($errors as $error): ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <label for="fullname">Full Name</label>
      <input type="text" id="fullname" name="fullname" required>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>

      <label for="email">Email</label>
      <input type="email" id="email" name="email" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <label for="confirm_password">Confirm Password</label>
      <input type="password" id="confirm_password" name="confirm_password" required>

      <button type="submit">Register</button>
    </form>
  </div>

</body>
</html>