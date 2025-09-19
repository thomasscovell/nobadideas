<?php
require 'db_connect.php';

// Set the correct timezone
date_default_timezone_set('Pacific/Auckland');

// Fetch image URLs from all three tables: cards, roles, and phases.
// We use UNION ALL to combine the results from the three queries into a single result set.
// An alias 'url' is used for the column name to keep it consistent across all queries.
$sql = "(SELECT image_url COLLATE utf8mb4_unicode_ci as url FROM nobadideas_cards WHERE image_url IS NOT NULL AND image_url != '')
        UNION ALL
        (SELECT image_front COLLATE utf8mb4_unicode_ci as url FROM nobadideas_roles WHERE image_front IS NOT NULL AND image_front != '')
        UNION ALL
        (SELECT image_front COLLATE utf8mb4_unicode_ci as url FROM nobadideas_phases WHERE image_front IS NOT NULL AND image_front != '')";

$result = $conn->query($sql);

$image_urls = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Prepend the path to the uploads folder and add to our list
        $image_urls[] = BASE_URL . '/uploads/' . $row['url'];
    }
}

$conn->close();

// Return the complete list of image URLs as a JSON object
header('Content-Type: application/json');
echo json_encode($image_urls);
?>