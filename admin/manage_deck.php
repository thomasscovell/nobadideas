<?php
// This file has two main responsibilities:
// 1. Handle backend actions (add, edit, delete) which only process data and redirect.
// 2. Display HTML pages (the edit form) for the user.
// To prevent "headers already sent" errors, all backend actions are handled first.

session_start();
require_once '../db_connect.php';

// Security check: Ensure the user is logged in for all actions.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    $_SESSION['message'] = 'You must be logged in to manage decks.';
    $_SESSION['message_type'] = 'error';
    header('Location: index.php');
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;

// --- ACTION HANDLERS (NO HTML OUTPUT) ---

// === ADD DECK ==============================================================
if ($action === 'add' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    if (empty($title) || !isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['message'] = 'Title and a valid image are required.';
        $_SESSION['message_type'] = 'error';
        header('Location: dashboard.php');
        exit;
    }

    $upload_dir = '../uploads/';
    $file = $_FILES['image'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (in_array($file_ext, $allowed_exts) && $file['size'] <= 5000000) {
        $new_file_name = uniqid('deck_', true) . '.' . $file_ext;
        $destination = $upload_dir . $new_file_name;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $stmt = $conn->prepare("INSERT INTO nobadideas_decks (title, image_url) VALUES (?, ?)");
            $stmt->bind_param("ss", $title, $new_file_name);
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Deck added successfully!';
            } else {
                $_SESSION['message'] = 'Database error: Could not add deck.';
                $_SESSION['message_type'] = 'error';
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = 'Failed to move uploaded file.';
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = 'Invalid file type or size.';
        $_SESSION['message_type'] = 'error';
    }
    header('Location: dashboard.php');
    exit;
}

// === DELETE DECK ===========================================================
if ($action === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT image_url FROM nobadideas_decks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $deck = $result->fetch_assoc();
        $image_to_delete = '../uploads/' . $deck['image_url'];
        if (file_exists($image_to_delete)) { unlink($image_to_delete); }
    }
    $stmt->close();
    $stmt = $conn->prepare("DELETE FROM nobadideas_decks WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) { $_SESSION['message'] = 'Deck and all its cards have been deleted.'; }
    else { $_SESSION['message'] = 'Error: Could not delete deck.'; $_SESSION['message_type'] = 'error'; }
    $stmt->close();
    header('Location: dashboard.php');
    exit;
}

// === EDIT DECK (Process Submission) ========================================
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $title = trim($_POST['title']);
    if (empty($title)) {
        $_SESSION['message'] = 'Title cannot be empty.';
        $_SESSION['message_type'] = 'error';
        header("Location: manage_deck.php?action=edit_form&id=$id");
        exit;
    }
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $file = $_FILES['image'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_exts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($file_ext, $allowed_exts) && $file['size'] <= 5000000) {
            $stmt = $conn->prepare("SELECT image_url FROM nobadideas_decks WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            if ($result && $result['image_url']) {
                $old_image_path = $upload_dir . $result['image_url'];
                if (file_exists($old_image_path)) { unlink($old_image_path); }
            }
            $stmt->close();
            $new_file_name = uniqid('deck_', true) . '.' . $file_ext;
            $destination = $upload_dir . $new_file_name;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $stmt = $conn->prepare("UPDATE nobadideas_decks SET title = ?, image_url = ? WHERE id = ?");
                $stmt->bind_param("ssi", $title, $new_file_name, $id);
                $stmt->execute();
                $stmt->close();
            } else {
                 $_SESSION['message'] = 'Failed to upload new image.'; $_SESSION['message_type'] = 'error';
                 header("Location: manage_deck.php?action=edit_form&id=$id"); exit;
            }
        } else {
            $_SESSION['message'] = 'Invalid file type or size.'; $_SESSION['message_type'] = 'error';
            header("Location: manage_deck.php?action=edit_form&id=$id"); exit;
        }
    } else {
        $stmt = $conn->prepare("UPDATE nobadideas_decks SET title = ? WHERE id = ?");
        $stmt->bind_param("si", $title, $id);
        $stmt->execute();
        $stmt->close();
    }
    $_SESSION['message'] = 'Deck updated successfully!';
    header('Location: dashboard.php');
    exit;
}


// --- HTML DISPLAY (IF NO ACTION WAS PROCESSED) ---

// If we get this far, we are displaying a page. Now we can include the header.
require_once 'header.php';

// === EDIT DECK (Display Form) ==============================================
if ($action === 'edit_form' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM nobadideas_decks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $deck = $result->fetch_assoc();
        ?>
        <div class="bg-white p-6 rounded-lg shadow-md max-w-lg mx-auto">
            <h2 class="text-2xl font-semibold mb-4">Edit Deck</h2>
            <form action="manage_deck.php" method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $deck['id']; ?>">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">Deck Title</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($deck['title']); ?>" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Current Image</label>
                    <img src="../uploads/<?php echo htmlspecialchars($deck['image_url']); ?>" alt="Current Image" class="mt-2 w-32 h-32 object-cover rounded-md">
                </div>
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Upload New Image (Optional)</label>
                    <p class="text-xs text-gray-500 mb-1">If you upload a new image, it will replace the old one.</p>
                    <input type="file" name="image" id="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="dashboard.php" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">Save Changes</button>
                </div>
            </form>
        </div>
        <?php
    } else {
        $_SESSION['message'] = 'Deck not found.';
        $_SESSION['message_type'] = 'error';
        header('Location: dashboard.php');
        exit;
    }
    $stmt->close();
} else {
    // Fallback for any other case or if no action is specified.
    echo "<p>Invalid action specified or page accessed directly.</p><a href='dashboard.php'>Return to Dashboard</a>";
}

require_once 'footer.php';
?>
