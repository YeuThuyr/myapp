<?php
/**
 * Sign Out
 * URL: /socialnet/signout.php
 *
 * Destroys the session and redirects to the sign-in page.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();

header("Location: signin.php");
exit();
