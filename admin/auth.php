<?php
// auth.php - Handles admin login authentication.

session_start();

// We need the database connection. The `../` moves up one directory level.
require_once '../db_connect.php';

// Only process POST requests.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare a statement to prevent SQL injection.
    $stmt = $conn->prepare("SELECT * FROM nobadideas_admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, now verify the password.
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password_hash'])) {
            // Password is correct.
            // Regenerate session ID for security.
            session_regenerate_id(true);

            // Store session data.
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $user['username'];

            // Redirect to the admin dashboard.
            header("Location: dashboard.php");
            exit();
        } else {
            // Incorrect password.
            $_SESSION['login_error'] = "Invalid username or password.";
            header("Location: index.php");
            exit();
        }
    } else {
        // No user found with that username.
        $_SESSION['login_error'] = "Invalid username or password.";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    // If not a POST request, redirect to login page.
    header("Location: index.php");
    exit();
}
?>