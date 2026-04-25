<?php
session_start();
require_once __DIR__ . '/config/database.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT id, username, password FROM account WHERE username = ? LIMIT 1"
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
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit">Login</button>
</form>

</body>
</html>