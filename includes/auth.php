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

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
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

/**
 * Verify CSRF token from POST request.
 * Dies if validation fails.
 */
function verifyCsrfToken() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'], $token)) {
            die("CSRF token validation failed.");
        }
    }
}

/**
 * Get hidden input field for CSRF token to include in forms.
 */
function getCsrfField() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
}
