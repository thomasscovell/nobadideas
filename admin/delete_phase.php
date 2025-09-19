<?php
session_start(); // Start the session

// Security check: Ensure the user is logged in.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../db_connect.php';

// Check if an ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_phases.php");
    exit;
}

$phase_id = (int)$_GET['id'];

// Prepare and execute the deletion query
$stmt = $conn->prepare("DELETE FROM nobadideas_phases WHERE id = ?");
$stmt->bind_param("i", $phase_id);

if ($stmt->execute()) {
    // Deletion successful
} else {
    // Handle error
    error_log("Failed to delete phase with ID: " . $phase_id . " Error: " . $stmt->error);
}

$stmt->close();
$conn->close();

// Redirect back to the management page
header("Location: manage_phases.php");
exit;
?>
