<?php
require_once '../db_connect.php';

// Fetch all decks for the dropdown menu
$decks_result = $conn->query("SELECT id, title FROM nobadideas_decks ORDER BY title ASC");

// Check if the form was submitted successfully
$show_thank_you = isset($_GET['success']) && $_GET['success'] == 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suggest a Card - No, Bad Ideas!</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">

    <div class="container mx-auto p-4 md:p-8 max-w-2xl">
        <header class="text-center my-8">
            <h1 class="text-4xl md:text-5xl font-bold tracking-tight">Advertising Anecdotes</h1>
            <p class="text-lg text-gray-400 mt-4">Please suggest things that might happen during a fictional campaign development process - at a media or creative agency - that help or hinder successful outcomes. They can be serious or silly, real or imagined, mundane or mad!</p>
        </header>

        <main class="bg-gray-800 p-6 md:p-8 rounded-lg shadow-2xl">
            <?php if ($show_thank_you): ?>
                <div class="text-center">
                    <h2 class="text-3xl font-bold text-green-400 mb-4">Thank You!</h2>
                    <p class="text-gray-300 text-lg mb-6">Your suggestion has been received. We appreciate your contribution!</p>
                    <a href="index.php" class="inline-flex justify-center py-2 px-6 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Submit Another
                    </a>
                </div>
            <?php else: ?>
                <form action="submit_suggestion.php" method="POST" class="space-y-6">
                    <div>
                        <label for="idea_text" class="block text-sm font-medium text-gray-300">Your advertising anecdotes</label>
                        <textarea name="idea_text" id="idea_text" rows="5" required class="mt-1 block w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-white" placeholder="e.g. Client comes down with covid and feedback is delayed a week."></textarea>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Idea
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </main>
    </div>

</body>
</html>
