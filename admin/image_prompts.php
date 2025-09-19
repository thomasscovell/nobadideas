<?
require_once 'header.php';
require_once '../db_connect.php';

// Security check: Ensure the user is logged in.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Fetch all cards and their associated deck titles
$sql = "SELECT c.id, c.title AS card_title, c.description, d.title AS deck_title
        FROM nobadideas_cards c
        LEFT JOIN nobadideas_decks d ON c.deck_id = d.id
        ORDER BY d.title ASC, c.title ASC";
$cards_result = $conn->query($sql);

?>
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold">AI Image Prompt Generator</h1>
    <a href="dashboard.php" class="px-4 py-2 font-semibold text-white bg-gray-600 rounded-md hover:bg-gray-700">Back to Dashboard</a>
</div>

<div class="bg-white p-6 rounded-lg shadow-md mb-8">
    <h2 class="text-2xl font-semibold mb-4">AI Image Generation Meta-Prompt</h2>
    <p class="text-gray-700 mb-4">Copy the text below and provide it to your favorite image generation AI (e.g., Midjourney, DALL-E, Stable Diffusion). This meta-prompt sets the style and context for the card illustrations.</p>
    <div class="bg-gray-100 p-4 rounded-md border border-gray-200 text-gray-800 font-mono text-sm leading-relaxed">
        <pre>
1960s advertising illustration, Mad Men aesthetic. Graphic, clean lines, stylized. [Scene Description]. Mid-century modern office environment. Limited, muted color palette: teal, mustard yellow, charcoal grey. Bold, sans-serif text overlay says "[Text]". **AVOID:** photorealism, 3D, modern tech.
        </pre>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Card Descriptions for AI</h2>
    <p class="text-gray-700 mb-4">Copy the descriptions below and paste them into your AI image generator, one by one, after the meta-prompt above.</p>
    <div class="bg-gray-100 p-4 rounded-md border border-gray-200 text-gray-800 font-mono text-sm leading-relaxed">
        <pre>
<?php 
if ($cards_result && $cards_result->num_rows > 0):
    $current_deck = null;
    while($card = $cards_result->fetch_assoc()):
        $deck_title = htmlspecialchars($card['deck_title'] ?? 'Unassigned');

        // Check if the deck has changed to print the header
        if ($current_deck !== $deck_title) {
            if ($current_deck !== null) {
                echo "\n"; // Add a space between deck groups
            }
            echo "--- Deck: " . $deck_title . " ---\
";
            $current_deck = $deck_title;
        }
        
        // Remove gameplay instructions from description. They appear after a double newline.
        $description_parts = explode("\n\n", $card['description']);
        $cleaned_description = trim($description_parts[0]);
?>
ID: <?php echo $card['id']; ?>
Card Title: <?php echo htmlspecialchars($card['card_title']); ?>
Card Description: <?php echo htmlspecialchars($cleaned_description); ?>

----

<?php 
    endwhile;
else:
?>
No cards found. Please add some cards in the "Manage Cards" section first.
<?php endif; ?>
        </pre>
    </div>
</div>

<?php require_once 'footer.php'; ?>
