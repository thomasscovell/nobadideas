<?php
// Set the header to indicate plain text, which makes it easy to copy-paste or save.
header('Content-Type: text/plain');

// Include the database connection.
require 'db_connect.php';

// Set the correct timezone, same as in the main application.
date_default_timezone_set('Pacific/Auckland');

$output = "";
$output .= "-- --------------------------------------------------------\n";
$output .= "-- No, Bad Ideas! - Database Setup Script --\n";
$output .= "-- Generated on: " . date('Y-m-d H:i:s') . " --\n";
$output .= "-- --------------------------------------------------------\n\n";

// List of tables to export
$tables = ['nobadideas_decks', 'nobadideas_cards', 'nobadideas_roles', 'nobadideas_phases', 'nobadideas_settings'];

foreach ($tables as $table) {
    // --- Get CREATE TABLE statement ---
    $output .= "--\n-- Table structure for table `$table`\n--\n";
    $result = $conn->query("SHOW CREATE TABLE `$table`");
    if ($row = $result->fetch_assoc()) {
        $output .= $row['Create Table'] . ";\n\n";
    }
    $result->free();

    // --- Get INSERT statements ---
    $output .= "--\n-- Dumping data for table `$table`\n--\n";
    $result = $conn->query("SELECT * FROM `$table`");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cols = array_keys($row);
            $cols_sql = "`" . implode("`, `", $cols) . "`";
            
            $vals_sql = [];
            foreach ($row as $key => $value) {
                if ($value === null) {
                    $vals_sql[] = "NULL";
                } else {
                    // Escape the value to handle special characters like single quotes.
                    $vals_sql[] = "'" . $conn->real_escape_string($value) . "'";
                }
            }
            $output .= "INSERT INTO `$table` ($cols_sql) VALUES (" . implode(", ", $vals_sql) . ");\n";
        }
    }
    $output .= "\n";
    $result->free();
}

$conn->close();

echo $output;

?>