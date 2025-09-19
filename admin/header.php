<?php
session_start(); // Start the session at the very beginning of the script
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No, Bad Ideas! - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-white shadow-md mb-8">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex justify-between items-center py-4">
                <a href="dashboard.php" class="text-xl font-bold text-gray-800">No, Bad Ideas! - Admin</a>
                <div class="flex items-center space-x-4">
                    <a href="dashboard.php" class="text-gray-600 hover:text-blue-600">Dashboard</a>
                    <a href="manage_suggestions.php" class="text-gray-600 hover:text-blue-600">Suggestions</a>
                    <a href="image_prompts.php" class="text-gray-600 hover:text-blue-600">Image Prompts</a>
                    <a href="logout.php" class="text-gray-600 hover:text-blue-600">Log Out</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-auto p-4 md:p-8">
