<?php
// submit_suggestion.php - Handles the submission of the public suggestion form.

// We need to connect to the database.
// The `../` moves up one directory level to find the file.
require_once '../db_connect.php';

// We only want to process POST requests, which is what our form uses.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Get the submitted data. Use trim() to remove any accidental whitespace.
    $idea_text = trim($_POST['idea_text']);
    $deck_id = $_POST['deck_id'] ?? null;

    // --- Validation ---
    // The idea text is required. If it's empty, just redirect back to the form.
    if (empty($idea_text)) {
        header('Location: index.php');
        exit;
    }

    // The deck ID is optional. If the user selected "No specific set", the value will be empty.
    // In that case, we want to store NULL in the database.
    if (empty($deck_id)) {
        $deck_id = null;
    }

    // --- Database Insertion ---
    // Prepare an SQL statement to prevent SQL injection.
    $stmt = $conn->prepare("INSERT INTO nobadideas_suggestions (deck_id, idea_text) VALUES (?, ?)");
    
    // Bind the parameters. The types are 'is' for Integer and String.
    // If $deck_id is null, bind_param correctly handles it.
    $stmt->bind_param("is", $deck_id, $idea_text);

    // Execute the statement.
    if ($stmt->execute()) {
        // If the insertion was successful, redirect to the thank you page.
        header('Location: index.php?success=1');
    } else {
        // If there was a database error, just send them back to the form.
        // In a more complex app, we might show an error message.
        header('Location: index.php');
    }

    // Close the statement and the connection.
    $stmt->close();
    $conn->close();

} else {
    // If someone tries to access this file directly without submitting the form,
    // just send them back to the main suggestions page.
    header('Location: index.php');
    exit();
}
?>
