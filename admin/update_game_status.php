<?php
session_start();
require_once '../db_connect.php';

// Security check: Ensure the user is logged in and is making a POST request.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['error'] = "You must be logged in to perform this action.";
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_status = $_POST['game_status'];

    // Validate the input
    if ($game_status === 'on' || $game_status === 'off') {
        // Prepare and execute the update statement
        $stmt = $conn->prepare("UPDATE nobadideas_settings SET setting_value = ? WHERE setting_name = 'game_status'");
        $stmt->bind_param('s', $game_status);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Game status updated successfully.";
        } else {
            $_SESSION['error'] = "Failed to update game status.";
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Invalid game status value.";
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
}

$conn->close();
header('Location: dashboard.php');
exit;
?>