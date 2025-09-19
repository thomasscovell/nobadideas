<?php
// config-example.php
// This is an example configuration file.
// To get the application working, copy this file to config.php and fill in your details.

// Set the default timezone for the application.
// A list of supported timezones can be found here: https://www.php.net/manual/en/timezones.php
date_default_timezone_set('Pacific/Auckland');

// --- Database Connection Details ---
// Replace the placeholder values below with your actual database credentials.

$servername = "your_database_host";    // e.g., "localhost" or "mysql.yourdomain.com"
$username = "your_database_username";  // Your MySQL username
$password = "your_database_password";  // Your MySQL password
$dbname = "your_database_name";        // The name of the database for this application

// --- Application URL ---
// Define the base URL of the application.
// This is used to create absolute links for sharing, APIs, etc.
// Make sure there is no trailing slash.
// For example: define('BASE_URL', 'http://localhost/nobadideas');
define('BASE_URL', 'https://yourdomain.com/your_app_path');

?>