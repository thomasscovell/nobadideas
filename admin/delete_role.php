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
    header("Location: manage_roles.php");
    exit;
}

$role_id = (int)$_GET['id'];

// Prepare and execute the deletion query
$stmt = $conn->prepare("DELETE FROM nobadideas_roles WHERE id = ?");
$stmt->bind_param("i", $role_id);

if ($stmt->execute()) {
    // Deletion successful
    // You could set a success message in a session variable here if you want to display one
} else {
    // Handle error
    // You could set an error message in a session variable
    error_log("Failed to delete role with ID: " . $role_id . " Error: " . $stmt->error);
}

$stmt->close();
$conn->close();

// Redirect back to the management page
header("Location: manage_roles.php");
exit;
?>
