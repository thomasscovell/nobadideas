<?php
// This file has two main responsibilities:
// 1. Handle backend actions (add, edit, delete) which only process data and redirect.
// 2. Display HTML pages (the main card list, the edit form) for the user.
// To prevent "headers already sent" errors, all backend actions are handled first.

session_start();
require_once '../db_connect.php';

// Security check: Ensure the user is logged in for all actions.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['message'] = 'You must be logged in to manage cards.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php');
    exit;
}

$action = $_REQUEST['action'] ?? null;
$deck_id = (int)($_REQUEST['deck_id'] ?? 0);

// --- ACTION HANDLERS (NO HTML OUTPUT) ---

// === ADD CARD ==============================================================
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $deck_id = (int)$_POST['deck_id']; // Get deck_id from the form post
    $redirect_location = "manage_cards.php?deck_id=$deck_id"; // Default redirect

    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $new_file_name = null;

    if (empty($title) || empty($description) || $deck_id <= 0) {
        $_SESSION['message'] = 'Card title, description, and a valid deck are required.';
        $_SESSION['message_type'] = 'error';
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            $file = $_FILES['image'];
            $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($file_ext, $allowed_exts) && $file['size'] <= 5000000) {
                $new_file_name = uniqid('card_', true) . '.' . $file_ext;
                $destination = $upload_dir . $new_file_name;
                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                    $new_file_name = null;
                    $_SESSION['message'] = 'Failed to upload image.';
                    $_SESSION['message_type'] = 'error';
                }
            } else {
                $_SESSION['message'] = 'Invalid file type or size for image.';
                $_SESSION['message_type'] = 'error';
            }
        }

        if (!isset($_SESSION['message_type']) || $_SESSION['message_type'] !== 'error') {
            $stmt = $conn->prepare("INSERT INTO nobadideas_cards (deck_id, title, description, image_url) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $deck_id, $title, $description, $new_file_name);
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Card added successfully!';
                if (isset($_POST['suggestion_id']) && (int)$_POST['suggestion_id'] > 0) {
                    $approved_suggestion_id = (int)$_POST['suggestion_id'];
                    $update_stmt = $conn->prepare("UPDATE nobadideas_suggestions SET status = 'approved' WHERE id = ?");
                    $update_stmt->bind_param("i", $approved_suggestion_id);
                    $update_stmt->execute();
                    $update_stmt->close();
                    $redirect_location = "manage_suggestions.php";
                }
            } else {
                $_SESSION['message'] = 'Database error: Could not add card.';
                $_SESSION['message_type'] = 'error';
            }
            $stmt->close();
        }
    }
    header("Location: $redirect_location");
    exit;
}

// === DELETE CARD ===========================================================
if ($action === 'delete' && isset($_GET['id'])) {
    $card_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT image_url FROM nobadideas_cards WHERE id = ? AND deck_id = ?");
    $stmt->bind_param("ii", $card_id, $deck_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $card = $result->fetch_assoc();
        if ($card['image_url']) {
            $image_to_delete = '../uploads/' . $card['image_url'];
            if (file_exists($image_to_delete)) { unlink($image_to_delete); }
        }
    }
    $stmt->close();
    $stmt = $conn->prepare("DELETE FROM nobadideas_cards WHERE id = ? AND deck_id = ?");
    $stmt->bind_param("ii", $card_id, $deck_id);
    if ($stmt->execute()) { $_SESSION['message'] = 'Card deleted successfully.'; }
    else { $_SESSION['message'] = 'Error: Could not delete card.'; $_SESSION['message_type'] = 'error'; }
    $stmt->close();
    header("Location: manage_cards.php?deck_id=$deck_id");
    exit;
}

// === EDIT CARD (Process Submission) ========================================
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $card_id = (int)$_POST['id'];
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    if (empty($title) || empty($description)) {
        $_SESSION['message'] = 'Title and description cannot be empty.';
        $_SESSION['message_type'] = 'error';
        header("Location: manage_cards.php?action=edit_form&id=$card_id&deck_id=$deck_id");
        exit;
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $file = $_FILES['image'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed_exts) && $file['size'] <= 5000000) {
            $stmt = $conn->prepare("SELECT image_url FROM nobadideas_cards WHERE id = ?");
            $stmt->bind_param("i", $card_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            if ($result && $result['image_url']) {
                $old_image_path = $upload_dir . $result['image_url'];
                if (file_exists($old_image_path)) { unlink($old_image_path); }
            }
            $stmt->close();
            $new_file_name = uniqid('card_', true) . '.' . $file_ext;
            $destination = $upload_dir . $new_file_name;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $stmt = $conn->prepare("UPDATE nobadideas_cards SET title = ?, description = ?, image_url = ? WHERE id = ?");
                $stmt->bind_param("sssi", $title, $description, $new_file_name, $card_id);
                $stmt->execute();
                $stmt->close();
            } else {
                 $_SESSION['message'] = 'Failed to upload new image.'; $_SESSION['message_type'] = 'error';
                 header("Location: manage_cards.php?action=edit_form&id=$card_id&deck_id=$deck_id"); exit;
            }
        } else {
            $_SESSION['message'] = 'Invalid file type or size.'; $_SESSION['message_type'] = 'error';
            header("Location: manage_cards.php?action=edit_form&id=$card_id&deck_id=$deck_id"); exit;
        }
    } else {
        $stmt = $conn->prepare("UPDATE nobadideas_cards SET title = ?, description = ? WHERE id = ?");
        $stmt->bind_param("ssi", $title, $description, $card_id);
        $stmt->execute();
        $stmt->close();
    }
    $_SESSION['message'] = 'Card updated successfully!';
    header("Location: manage_cards.php?deck_id=$deck_id");
    exit;
}


// --- HTML DISPLAY (IF NO ACTION WAS PROCESSED) ---

// If we get this far, we are displaying a page. Now we can include the header.
require_once 'header.php';

// Check if we are approving a suggestion from the manage_suggestions.php page
$suggestion_id_to_approve = (int)($_GET['suggestion_id'] ?? 0);
$suggestion_text_to_add = urldecode($_GET['suggestion_text'] ?? '');

// Fetch deck title for the header
$deck_title = '';
if ($deck_id > 0) {
    $deck_stmt = $conn->prepare("SELECT title FROM nobadideas_decks WHERE id = ?");
    $deck_stmt->bind_param("i", $deck_id);
    $deck_stmt->execute();
    $deck_result = $deck_stmt->get_result();
    if ($deck_result->num_rows > 0) { $deck = $deck_result->fetch_assoc(); $deck_title = $deck['title']; }
    $deck_stmt->close();
}

// === EDIT CARD (Display Form) ==============================================
if ($action === 'edit_form' && isset($_GET['id'])) {
    $card_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM nobadideas_cards WHERE id = ? AND deck_id = ?");
    $stmt->bind_param("ii", $card_id, $deck_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $card = $result->fetch_assoc();
        // Display the edit form
        ?>
        <a href="manage_cards.php?deck_id=<?php echo $deck_id; ?>" class="text-indigo-600 hover:text-indigo-800">&larr; Back to Card List for "<?php echo htmlspecialchars($deck_title); ?>"</a>
        <div class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto mt-4">
            <h2 class="text-2xl font-semibold mb-4">Edit Card</h2>
            <form action="manage_cards.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $card['id']; ?>">
                <input type="hidden" name="deck_id" value="<?php echo $deck_id; ?>">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Card Title</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($card['title']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo htmlspecialchars($card['description']); ?></textarea>
                </div>
                <?php if ($card['image_url']): ?>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Image</label>
                    <img src="../uploads/<?php echo htmlspecialchars($card['image_url']); ?>" alt="Current Image" class="mt-2 w-32 h-32 object-cover rounded-md">
                </div>
                <?php endif; ?>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Upload New Image (Optional)</label>
                    <p class="text-xs text-gray-500 mb-1">If you upload a new image, it will replace the old one.</p>
                    <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0">
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="manage_cards.php?deck_id=<?php echo $deck_id; ?>" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Save Changes</button>
                </div>
            </form>
        </div>
        <?php
    } else {
        $_SESSION['message'] = 'Card not found.';
        $_SESSION['message_type'] = 'error';
        header("Location: manage_cards.php?deck_id=$deck_id");
        exit;
    }
    $stmt->close();
}

// === Main Page Display (if no other action) =================================
else {
    $all_decks_result = $conn->query("SELECT id, title FROM nobadideas_decks ORDER BY title ASC");
    $cards_result = null;
    if ($deck_id > 0) {
        $cards_stmt = $conn->prepare("SELECT * FROM nobadideas_cards WHERE deck_id = ? ORDER BY created_at DESC");
        $cards_stmt->bind_param("i", $deck_id);
        $cards_stmt->execute();
        $cards_result = $cards_stmt->get_result();
    }
    ?>
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="dashboard.php" class="text-indigo-600 hover:text-indigo-800">&larr; Back to Dashboard</a>
            <h1 class="text-3xl font-bold mt-2">Managing Cards for: <span class="text-indigo-600"><?php echo $deck_id > 0 ? htmlspecialchars($deck_title) : 'a New Suggestion'; ?></span></h1>
        </div>
    </div>
    <?php
    if (isset($_SESSION['message'])) {
        $message_type = isset($_SESSION['message_type']) && $_SESSION['message_type'] == 'error' ? 'red' : 'green';
        echo '<div class="bg-'.$message_type.'-100 border-l-4 border-'.$message_type.'-500 text-'.$message_type.'-700 p-4 mb-6" role="alert"><p>' . htmlspecialchars($_SESSION['message']) . '</p></div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-lg shadow-md h-full">
                <h2 class="text-2xl font-semibold mb-4">Add a New Card</h2>
                <form action="manage_cards.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="action" value="add">
                    <?php if ($suggestion_id_to_approve > 0): ?>
                        <input type="hidden" name="suggestion_id" value="<?php echo $suggestion_id_to_approve; ?>">
                    <?php endif; ?>
                    <div>
                        <label for="deck_id" class="block text-sm font-medium text-gray-700">Deck</label>
                        <select name="deck_id" id="deck_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">Select a deck...</option>
                            <?php mysqli_data_seek($all_decks_result, 0); while($deck_row = $all_decks_result->fetch_assoc()): ?>
                                <option value="<?php echo $deck_row['id']; ?>" <?php echo ($deck_id == $deck_row['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($deck_row['title']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Card Title</label>
                        <input type="text" name="title" id="title" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo htmlspecialchars($suggestion_text_to_add); ?></textarea>
                    </div>
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700">Image (Optional)</label>
                        <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                    </div>
                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Add Card</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md h-full">
                <h2 class="text-2xl font-semibold mb-4">Existing Cards</h2>
                <div class="space-y-4">
                    <?php if ($cards_result && $cards_result->num_rows > 0): ?>
                        <?php while($card = $cards_result->fetch_assoc()): ?>
                            <div class="flex items-start space-x-4 border p-4 rounded-lg">
                                <?php if ($card['image_url']): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($card['image_url']); ?>" alt="" class="w-24 h-24 object-cover rounded-md flex-shrink-0">
                                <?php else: ?><div class="w-24 h-24 bg-gray-100 rounded-md flex-shrink-0 flex items-center justify-center text-gray-400">No Image</div><?php endif; ?>
                                <div class="flex-grow">
                                    <h3 class="font-bold text-lg"><?php echo htmlspecialchars($card['title']); ?></h3>
                                    <p class="text-gray-600 text-sm"><?php echo nl2br(htmlspecialchars($card['description'])); ?></p>
                                </div>
                                <div class="flex flex-col space-y-2 flex-shrink-0">
                                    <a href="manage_cards.php?action=edit_form&id=<?php echo $card['id']; ?>&deck_id=<?php echo $deck_id; ?>" class="text-center text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">Edit</a>
                                    <a href="manage_cards.php?action=delete&id=<?php echo $card['id']; ?>&deck_id=<?php echo $deck_id; ?>" onclick="return confirm('Are you sure you want to delete this card?');" class="text-center text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">Delete</a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?><p class="text-gray-500">No cards found for this deck yet.</p><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    if($deck_id > 0) { $cards_stmt->close(); }
    require_once 'footer.php';
    exit;
}
?>