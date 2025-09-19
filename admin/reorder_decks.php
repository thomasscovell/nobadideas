<?php
require_once '../db_connect.php';

// Fetch all decks in their current order
$decks_result = $conn->query("SELECT id, title FROM nobadideas_decks ORDER BY deck_order ASC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reorder Decks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .sortable-ghost {
            background: #4a5568;
            opacity: 0.5;
        }
        .sortable-chosen {
            cursor: grabbing;
        }
    </style>
</head>
<body class="bg-gray-900 text-white">

    <div class="container mx-auto p-8">
        <header class="text-center mb-8">
            <h1 class="text-4xl font-bold">Reorder Decks</h1>
            <p class="text-lg text-gray-400 mt-2">Drag and drop the decks into your desired order.</p>
            <a href="../" class="mt-4 inline-block text-green-400 hover:text-green-300">&larr; Back to Main Game</a>
        </header>

        <div id="deck-list" class="max-w-md mx-auto">
            <?php if ($decks_result && $decks_result->num_rows > 0): ?>
                <?php while($deck = $decks_result->fetch_assoc()): ?>
                    <div data-id="<?php echo $deck['id']; ?>" class="bg-gray-800 p-4 rounded-lg mb-3 cursor-move shadow-md flex items-center">
                        <svg class="w-6 h-6 mr-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <span class="text-lg font-semibold"><?php echo htmlspecialchars($deck['title']); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-gray-500">No decks found.</p>
            <?php endif; ?>
        </div>

        <div id="save-status" class="text-center mt-8 text-green-400 font-semibold"></div>

    </div>

    <!-- CDN for SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const deckList = document.getElementById('deck-list');
            const saveStatus = document.getElementById('save-status');

            new Sortable(deckList, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                onEnd: function (evt) {
                    // Get the new order of deck IDs
                    const deckIds = Array.from(deckList.children).map(child => child.dataset.id);
                    
                    // Save the new order
                    saveOrder(deckIds);
                }
            });

            async function saveOrder(deckIds) {
                saveStatus.textContent = 'Saving...';
                try {
                    const response = await fetch('../api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'reorder_decks',
                            order: deckIds
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const result = await response.json();

                    if (result.success) {
                        saveStatus.textContent = 'Order saved successfully!';
                    } else {
                        throw new Error(result.message || 'Unknown error');
                    }

                } catch (error) {
                    console.error('Error saving order:', error);
                    saveStatus.textContent = `Error saving order: ${error.message}`;
                    saveStatus.classList.remove('text-green-400');
                    saveStatus.classList.add('text-red-500');
                }

                setTimeout(() => {
                    saveStatus.textContent = '';
                    saveStatus.classList.remove('text-red-500');
                    saveStatus.classList.add('text-green-400');
                }, 3000);
            }
        });
    </script>

</body>
</html>
