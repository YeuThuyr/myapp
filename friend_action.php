<?php
/**
 * Handle friend requests actions (add, accept)
 */
require_once __DIR__ . '/includes/auth.php';
requireLogin();

require_once __DIR__ . '/includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verifyCsrfToken();
    $action = $_POST['action'] ?? '';
    
    $user_id = $_SESSION['user_id'];
    
    if ($action === 'add' && !empty($_POST['target_id'])) {
        $target_id = (int)$_POST['target_id'];
        
        // Ensure they aren't adding themselves
        if ($target_id !== $user_id) {
            try {
                // Insert a pending request (ignore if it already exists due to UNIQUE key)
                $stmt = $conn->prepare("INSERT IGNORE INTO friendship (sender_id, receiver_id, status) VALUES (?, ?, 'pending')");
                $stmt->bind_param("ii", $user_id, $target_id);
                $stmt->execute();
                $stmt->close();
            } catch (Exception $e) {
                // Ignore duplicate or DB errors
            }
        }
    } elseif ($action === 'accept' && !empty($_POST['sender_id'])) {
        $sender_id = (int)$_POST['sender_id'];
        
        try {
            // Update the pending request to accepted
            // Make sure the receiver is the logged-in user
            $stmt = $conn->prepare("UPDATE friendship SET status = 'accepted' WHERE sender_id = ? AND receiver_id = ? AND status = 'pending'");
            $stmt->bind_param("ii", $sender_id, $user_id);
            $stmt->execute();
            $stmt->close();
        } catch (Exception $e) {
            // Ignore errors
        }
    }
}

// Redirect back to home
header("Location: index.php");
exit();
