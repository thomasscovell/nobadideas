<?php
session_start();
require_once 'db_connect.php';

// Fetch game status from settings
$game_status = 'off'; // Default to off
$stmt = $conn->prepare("SELECT setting_value FROM nobadideas_settings WHERE setting_name = 'game_status'");
if ($stmt) {
    $stmt->execute();
    $stmt->bind_result($status);
    if ($stmt->fetch()) {
        $game_status = $status;
    }
    $stmt->close();
}

// --- Game Session Logic ---
$action = $_GET['action'] ?? null;

if ($action === 'new_game') {
    // 1. Fetch all cards from the database
    $all_cards_result = $conn->query("SELECT * FROM nobadideas_cards");
    
    $game_decks = [];
    if ($all_cards_result && $all_cards_result->num_rows > 0) {
        // 2. Group cards by their deck_id
        while ($card = $all_cards_result->fetch_assoc()) {
            $game_decks[$card['deck_id']][] = $card;
        }

        // 3. Shuffle each individual deck
        foreach ($game_decks as $deck_id => &$cards) {
            shuffle($cards);
        }
    }

    // 4. Store the shuffled decks in the session
    $_SESSION['game_decks'] = $game_decks;

    // 5. Redirect to the main page to start the game
    header('Location: index.php');
    exit;
}

// Check if a game is currently active
$game_in_progress = isset($_SESSION['game_decks']);

// We still need to fetch the deck info for display purposes
$decks_result = $conn->query("SELECT * FROM nobadideas_decks ORDER BY deck_order ASC");

?>
<?php if ($game_status === 'on'): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No, Bad Ideas! - The Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-gray-900 text-white">

    <div id="deck-selection-screen" class="container mx-auto p-4 md:p-8">
        <header class="text-center my-8 md:my-12">
            <h1 class="text-4xl md:text-6xl font-bold tracking-tight">No, Bad Ideas!</h1>
            <p class="text-lg md:text-xl text-gray-400 mt-2">Select a deck to draw a card</p>
            <a href="index.php?action=new_game" class="mt-6 inline-block px-8 py-3 bg-green-600 hover:bg-green-700 rounded-lg font-semibold text-lg transition-transform transform hover:scale-105">
                Start New Game / Reshuffle All Decks
            </a>
        </header>

        <?php if ($game_in_progress): ?>
            <main id="deck-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-8">
                <?php if ($decks_result && $decks_result->num_rows > 0): ?>
                    <?php while($deck = $decks_result->fetch_assoc()): ?>
                        <div class="deck-card" data-deck-id="<?php echo $deck['id']; ?>">
                            <div class="deck-card-inner">
                                <div class="deck-card-front">
                                    <img src="<?php echo BASE_URL; ?>/uploads/<?php echo htmlspecialchars($deck['image_url']); ?>" alt="" class="w-full">
                                    <div class="deck-title-overlay">
                                        <h2 class="text-lg font-bold"><?php echo htmlspecialchars($deck['title']); ?></h2>
                                    </div>
                                </div>
                                <div class="deck-card-back"></div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center col-span-full text-gray-500">The game master hasn't added any decks yet.</p>
                <?php endif; ?>
            </main>
        <?php else: ?>
            <div class="text-center bg-gray-800 p-8 rounded-lg">
                <h2 class="text-2xl font-semibold">Welcome!</h2>
                <p class="mt-2 text-gray-400">Click the button above to start a new game and shuffle the decks.</p>
            </div>
        <?php endif; ?>

        <div class="mt-12 text-center border-t border-gray-700 pt-8">
            <div class="flex justify-center gap-x-4">
                <a href="roles/" class="px-6 py-2 bg-sky-600 hover:bg-sky-700 rounded-lg font-semibold transition-transform transform hover:scale-105">
                    Role Cards
                </a>
                <a href="phases/" class="px-6 py-2 bg-amber-600 hover:bg-amber-700 rounded-lg font-semibold transition-transform transform hover:scale-105">
                    Phase Cards
                </a>
                <a href="instructions.php" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg font-semibold transition-transform transform hover:scale-105">
                    How to Play
                </a>
            </div>
            <div class="mt-8 text-center text-sm text-gray-500">
                <p>Released under the GPL 3.0 license. Find the code on <a href="https://github.com/thomasscovell/nobadideas" target="_blank" class="text-green-400 hover:underline">GitHub</a>.</p>
            </div>
        </div>
    </div>

    <!-- Card Display Overlay -->
    <div id="card-display-overlay" style="display: none;">
        <div id="drawn-card-container">
            <div id="drawn-card" class="bg-white text-gray-900 rounded-lg shadow-2xl p-8 relative text-center">
                <img id="drawn-card-image" src="" alt="" class="w-full rounded-t-lg mb-6 hidden">
                <h2 id="drawn-card-title" class="text-3xl font-bold mb-4"></h2>
                <p id="drawn-card-description" class="text-lg"></p>
                <button id="reshuffle-btn" style="display: none;" class="mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 rounded-lg font-semibold text-white">Reshuffle This Deck</button>
                <button id="done-btn" class="absolute top-4 right-4 text-2xl font-bold text-gray-500 hover:text-gray-900">&times;</button>
            </div>
        </div>
    </div>

    <audio id="shuffle-sound" src="assets/audio/shuffle.mp3" preload="auto"></audio>
    <script src="assets/script.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fetch the list of image URLs from our new PHP script
        fetch('get_image_urls.php')
            .then(response => response.json()) // Parse the JSON response
            .then(imageUrls => {
                // This is the pre-caching part
                console.log(`Pre-caching ${imageUrls.length} images...`);
                imageUrls.forEach(url => {
                    const img = new Image(); // Create a new image object
                    img.src = url; // Set the source, which triggers the browser to download and cache it
                });
            })
            .catch(error => console.error('Error fetching image list for pre-caching:', error));
    });
    </script>
</body>
</html>
<?php else: ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No, Bad Ideas! - Coming Soon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen">
    <div class="text-center p-8 bg-gray-800 rounded-lg shadow-xl">
        <h1 class="text-5xl md:text-7xl font-bold tracking-tight mb-4">Game Coming Soon!</h1>
        <p class="text-xl md:text-2xl text-gray-400">Stay tuned for some bad ideas.</p>
    </div>
</body>
</html>
<?php endif; ?>