<?php
// dashboard.php - Main admin interface for managing decks and cards.

require_once 'header.php';
require_once '../db_connect.php'; // We need the database connection

// Security check: Ensure the user is logged in.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

// Fetch all decks from the database to display them
$decks_result = $conn->query("SELECT * FROM nobadideas_decks ORDER BY title ASC");

?>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold">Admin Dashboard</h1>
    <div class="flex items-center">
        <span class="mr-4 text-gray-600">Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
        <a href="image_prompts.php" class="px-4 py-2 font-semibold text-white bg-teal-600 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 mr-4">Image Prompts</a>
        <a href="manage_suggestions.php" class="px-4 py-2 font-semibold text-white bg-purple-600 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 mr-4">Manage Suggestions</a>
        <a href="logout.php" class="px-4 py-2 font-semibold text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">Log Out</a>
    </div>
</div>

<?php
// Fetch the current game status
$stmt = $conn->prepare("SELECT setting_value FROM nobadideas_settings WHERE setting_name = 'game_status'");
$stmt->execute();
$stmt->bind_result($game_status);
$stmt->fetch();
$stmt->close();
?>

<!-- Game Status Section -->
<div class="mb-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Game Status</h2>
    <p class="mb-4 text-gray-600">Control whether the game is live to the public or shows a "Coming Soon" page.</p>
    <form action="update_game_status.php" method="POST">
        <div class="flex items-center space-x-4">
            <select name="game_status" class="block w-full md:w-1/3 px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none focus:ring focus:ring-opacity-40">
                <option value="on" <?php echo ($game_status === 'on') ? 'selected' : ''; ?>>On (Live)</option>
                <option value="off" <?php echo ($game_status === 'off') ? 'selected' : ''; ?>>Off (Coming Soon)</option>
            </select>
            <button type="submit" class="px-6 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Save Status
            </button>
        </div>
    </form>
</div>

<!-- Bespoke Deck Management Section -->
<div class="mb-8 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">Bespoke Decks</h2>
    <p class="mb-4 text-gray-600">Manage the content for the sequential card decks.</p>
    <div class="flex items-center space-x-4">
        <a href="manage_roles.php" class="px-6 py-2 font-semibold text-white bg-sky-600 rounded-md hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
            Manage Role Cards
        </a>
        <a href="manage_phases.php" class="px-6 py-2 font-semibold text-white bg-amber-600 rounded-md hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
            Manage Phase Cards
        </a>
    </div>
</div>

<?php
// Display success or error messages passed via session
if (isset($_SESSION['message'])) {
    $message_type = isset($_SESSION['message_type']) && $_SESSION['message_type'] == 'error' ? 'red' : 'green';
    echo '<div class="bg-'.$message_type.'-100 border-l-4 border-'.$message_type.'-500 text-'.$message_type.'-700 p-4 mb-6" role="alert">';
    echo '<p class="font-bold">' . ($message_type == 'red' ? 'Error' : 'Success') . '</p>';
    echo '<p>' . htmlspecialchars($_SESSION['message']) . '</p>';
    echo '</div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<!-- Deck Management Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Add New Deck Form -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-md h-full">
            <h2 class="text-2xl font-semibold mb-4">Add a New Deck</h2>
            <form action="manage_deck.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="add">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Deck Title</label>
                    <input type="text" name="title" id="title" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Deck Image</label>
                    <input type="file" name="image" id="image" required accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add Deck
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Display Existing Decks -->
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-lg shadow-md h-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold">Existing Decks</h2>
                <a href="reorder_decks.php" class="px-4 py-2 font-semibold text-sm text-white bg-gray-600 rounded-md hover:bg-gray-700">Reorder Decks</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                <?php if ($decks_result && $decks_result->num_rows > 0): ?>
                    <?php while($deck = $decks_result->fetch_assoc()): ?>
                        <div class="relative group bg-gray-50 rounded-lg shadow-sm overflow-hidden">
                            <img src="../uploads/<?php echo htmlspecialchars($deck['image_url']); ?>" alt="<?php echo htmlspecialchars($deck['title']); ?>" class="w-full h-40 object-cover">
                            <div class="p-4">
                                <h3 class="font-semibold text-lg truncate"><?php echo htmlspecialchars($deck['title']); ?></h3>
                            </div>
                            <div class="absolute inset-0 bg-black bg-opacity-60 flex flex-col items-center justify-center space-y-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="manage_cards.php?deck_id=<?php echo $deck['id']; ?>" class="text-white text-sm bg-green-600 hover:bg-green-700 px-3 py-1 rounded w-28 text-center">Manage Cards</a>
                                <a href="manage_deck.php?action=edit_form&id=<?php echo $deck['id']; ?>" class="text-white text-sm bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded w-28 text-center">Edit Deck</a>
                                <a href="manage_deck.php?action=delete&id=<?php echo $deck['id']; ?>" onclick="return confirm('Are you sure you want to delete this deck? This will also delete all cards within it.');" class="text-white text-sm bg-red-600 hover:bg-red-700 px-3 py-1 rounded w-28 text-center">Delete Deck</a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-500 col-span-full">No decks found. Add one using the form!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>