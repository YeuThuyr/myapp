<?php
/**
 * Authentication helper.
 * Call requireLogin() at the top of any protected page.
 */

if (session_status() === PHP_SESSION_NONE) {
    // Keep sessions alive for 7 days (604800 seconds)
    ini_set('session.gc_maxlifetime', 604800);
    ini_set('session.cookie_lifetime', 604800);
    session_set_cookie_params(604800);
    session_start();
}

/**
 * Redirect to the sign-in page if the user is not logged in.
 */
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: signin.php");
        exit();
    }
}
