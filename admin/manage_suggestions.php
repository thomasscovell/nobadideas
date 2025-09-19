<?php
// --- Action Handling & Setup ---
// This block must be before any HTML output to ensure redirects work correctly.
require_once '../db_connect.php';

// Start session if not already started. This is crucial before accessing $_SESSION.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if an action is being performed via GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $suggestion_id = (int)$_GET['id'];

    // Security check: Ensure the user is logged in before processing any action.
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        // Stop execution if not authenticated.
        die('Authentication required to perform this action.');
    }

    if ($suggestion_id > 0) {
        $new_status = '';
        if ($action === 'reject') {
            $new_status = 'rejected';
        } elseif ($action === 'approve') {
            // This status is set after the card is successfully created
            $new_status = 'approved';
        }

        if ($new_status) {
            $stmt = $conn->prepare("UPDATE nobadideas_suggestions SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $new_status, $suggestion_id);
            if ($stmt->execute()) {
                $_SESSION['message'] = "Suggestion status updated to '$new_status'.";
            } else {
                $_SESSION['message'] = "Failed to update suggestion status.";
                $_SESSION['message_type'] = 'error';
            }
            $stmt->close();
            // Redirect to avoid re-processing on refresh and to show the message.
            header('Location: manage_suggestions.php');
            exit;
        }
    }
}

// --- Page Display ---
require_once 'header.php';

// Security check for viewing the page.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}


// --- Display Logic ---
// Fetch all 'new' suggestions, joining with the decks table to get the deck title.
$sql = "SELECT s.id, s.idea_text, s.deck_id, d.title as deck_title 
        FROM nobadideas_suggestions s 
        LEFT JOIN nobadideas_decks d ON s.deck_id = d.id 
        WHERE s.status = 'new' 
        ORDER BY s.created_at ASC";
$suggestions_result = $conn->query($sql);

?>

<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold">Manage New Suggestions</h1>
    <a href="dashboard.php" class="px-4 py-2 font-semibold text-white bg-gray-600 rounded-md hover:bg-gray-700">Back to Dashboard</a>
</div>

<?php
// Display success or error messages
if (isset($_SESSION['message'])) {
    $message_type = isset($_SESSION['message_type']) && $_SESSION['message_type'] == 'error' ? 'red' : 'green';
    echo '<div class="bg-'.$message_type.'-100 border-l-4 border-'.$message_type.'-500 text-'.$message_type.'-700 p-4 mb-6" role="alert">';
    echo '<p>' . htmlspecialchars($_SESSION['message']) . '</p>';
    echo '</div>';
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}
?>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-4">New Suggestions</h2>
    <div class="space-y-4">
        <?php if ($suggestions_result && $suggestions_result->num_rows > 0): ?>
            <?php while($suggestion = $suggestions_result->fetch_assoc()): ?>
                <div class="flex items-center justify-between border p-4 rounded-lg">
                    <div>
                        <p class="text-gray-800 text-lg">"<?php echo nl2br(htmlspecialchars($suggestion['idea_text'])); ?>"</p>
                        <p class="text-sm text-gray-500 mt-1">Suggested for Deck: 
                            <span class="font-semibold"><?php echo $suggestion['deck_title'] ? htmlspecialchars($suggestion['deck_title']) : 'None'; ?></span>
                        </p>
                    </div>
                    <div class="flex flex-shrink-0 space-x-2 ml-4">
                        <?php
                            // Prepare the data for the link
                            $approve_link = 'manage_cards.php?deck_id=' . $suggestion['deck_id'];
                            $approve_link .= '&suggestion_id=' . $suggestion['id'];
                            $approve_link .= '&suggestion_text=' . urlencode($suggestion['idea_text']);
                        ?>
                        <a href="<?php echo $approve_link; ?>" class="text-center text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md">Approve & Add</a>
                        <a href="manage_suggestions.php?action=reject&id=<?php echo $suggestion['id']; ?>" onclick="return confirm('Are you sure you want to reject this suggestion?');" class="text-center text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md">Reject</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-gray-500">No new suggestions found.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'footer.php'; ?>