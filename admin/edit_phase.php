<?php
session_start(); // Start the session

// Security check: Ensure the user is logged in.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}
require_once '../db_connect.php';

$page_title = 'Edit Phase Card';

// Initialize variables
$phase = [
    'id' => '',
    'title' => '',
    'image_front' => '',
    'what_goes_in' => '',
    'what_comes_out' => '',
    'pain_points' => '',
    'lead_roles' => '',
    'display_order' => 0
];
$is_edit_mode = false;

// Check if an ID is provided for editing
if (isset($_GET['id'])) {
    $is_edit_mode = true;
    $phase_id = (int)$_GET['id'];
    $page_title = 'Edit Phase Card';

    $stmt = $conn->prepare("SELECT * FROM nobadideas_phases WHERE id = ?");
    $stmt->bind_param("i", $phase_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $phase = $result->fetch_assoc();
    } else {
        header("Location: manage_phases.php");
        exit;
    }
    $stmt->close();
} else {
    $page_title = 'Add New Phase Card';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $what_goes_in = $_POST['what_goes_in'] ?? '';
    $what_comes_out = $_POST['what_comes_out'] ?? '';
    $pain_points = $_POST['pain_points'] ?? '';
    $lead_roles = $_POST['lead_roles'] ?? '';
    $display_order = (int)($_POST['display_order'] ?? 0);
    $current_image = $_POST['current_image'] ?? '';
    $new_image_filename = $current_image;

    // --- Image Upload Logic ---
    if (isset($_FILES['image_front']) && $_FILES['image_front']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['image_front']['tmp_name']);

        if (in_array($file_type, $allowed_types)) {
            $new_image_filename = uniqid('phase_', true) . '-' . basename($_FILES['image_front']['name']);
            $target_path = $upload_dir . $new_image_filename;

            if (move_uploaded_file($_FILES['image_front']['tmp_name'], $target_path)) {
                if ($current_image && file_exists($upload_dir . $current_image)) {
                    unlink($upload_dir . $current_image);
                }
            } else {
                $error_message = "Error: Could not move uploaded file.";
                $new_image_filename = $current_image;
            }
        } else {
            $error_message = "Error: Invalid file type. Only JPG, PNG, and GIF are allowed.";
            $new_image_filename = $current_image;
        }
    }
    // --- End Image Upload Logic ---

    if (!isset($error_message)) {
        if ($is_edit_mode) {
            $phase_id = (int)$_POST['id'];
            $stmt = $conn->prepare("UPDATE nobadideas_phases SET title = ?, what_goes_in = ?, what_comes_out = ?, pain_points = ?, lead_roles = ?, display_order = ?, image_front = ? WHERE id = ?");
            $stmt->bind_param("sssssisi", $title, $what_goes_in, $what_comes_out, $pain_points, $lead_roles, $display_order, $new_image_filename, $phase_id);
        } else {
            $stmt = $conn->prepare("INSERT INTO nobadideas_phases (title, what_goes_in, what_comes_out, pain_points, lead_roles, display_order, image_front) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssis", $title, $what_goes_in, $what_comes_out, $pain_points, $lead_roles, $display_order, $new_image_filename);
        }

        if ($stmt->execute()) {
            header("Location: manage_phases.php");
            exit;
        } else {
            $error_message = "Database Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

include 'header.php';
?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6"><?php echo $page_title; ?></h1>

    <?php if (isset($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="edit_phase.php<?php echo $is_edit_mode ? '?id=' . $phase['id'] : ''; ?>" method="POST" enctype="multipart/form-data">
            <?php if ($is_edit_mode): ?>
                <input type="hidden" name="id" value="<?php echo $phase['id']; ?>">
            <?php endif; ?>
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($phase['image_front']); ?>">

            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($phase['title']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label for="what_goes_in" class="block text-gray-700 text-sm font-bold mb-2">What Goes In</label>
                <textarea id="what_goes_in" name="what_goes_in" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($phase['what_goes_in']); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="what_comes_out" class="block text-gray-700 text-sm font-bold mb-2">What Comes Out</label>
                <textarea id="what_comes_out" name="what_comes_out" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($phase['what_comes_out']); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="pain_points" class="block text-gray-700 text-sm font-bold mb-2">Pain Points</label>
                <textarea id="pain_points" name="pain_points" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($phase['pain_points']); ?></textarea>
            </div>
            
            <div class="mb-4">
                <label for="lead_roles" class="block text-gray-700 text-sm font-bold mb-2">Lead Roles</label>
                <textarea id="lead_roles" name="lead_roles" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?php echo htmlspecialchars($phase['lead_roles']); ?></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Card Image</label>
                <?php if ($is_edit_mode && !empty($phase['image_front'])): ?>
                    <div class="mb-2">
                        <img src="../uploads/<?php echo htmlspecialchars($phase['image_front']); ?>" alt="Current Image" class="w-32 h-32 object-cover rounded-md shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Current image</p>
                    </div>
                <?php endif; ?>
                <label for="image_front" class="block text-gray-700 text-sm font-bold mb-2"><?php echo ($is_edit_mode && !empty($phase['image_front'])) ? 'Upload New Image (Replaces Current)' : 'Upload Image'; ?></label>
                <input type="file" id="image_front" name="image_front" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <p class="text-xs text-gray-500 mt-1">Allowed file types: JPG, PNG, GIF.</p>
            </div>

            <div class="mb-6">
                <label for="display_order" class="block text-gray-700 text-sm font-bold mb-2">Display Order</label>
                <input type="number" id="display_order" name="display_order" value="<?php echo htmlspecialchars($phase['display_order']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    <?php echo $is_edit_mode ? 'Update Phase' : 'Save New Phase'; ?>
                </button>
                <a href="manage_phases.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php
include 'footer.php';
$conn->close();
?>
