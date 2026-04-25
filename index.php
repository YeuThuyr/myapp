<?php
session_start();

// If not logged in → redirect to login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
</head>
<body>

<h1>Home Page</h1>

<p>
    Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋
</p>

<!-- Logout button -->
<form method="POST" action="logout.php">
    <button type="submit">Logout</button>
</form>

</body>
</html>