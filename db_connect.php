<?php
/*
 * db_connect.php
 *
 * This file establishes the connection to the MySQL database and sets the
 * default timezone for the application. It will be included in any PHP
 * file that needs to interact with the database.
 */

// Include the configuration file
// This file holds the database credentials and other settings.
require_once 'config.php';

// Create a new MySQLi connection object
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If the connection fails, we stop the script and display a generic
    // error message. This prevents sensitive error details from being
    // shown to the end-user.
    die("Database connection failed. Please try again later.");
}

// Set the character set to utf8mb4 to support a wide range of characters
$conn->set_charset("utf8mb4");

?>