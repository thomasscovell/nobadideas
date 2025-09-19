<?php
session_start(); // Start the session

// Security check: Ensure the user is logged in.
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

require_once '../db_connect.php'; // Database connection

// Fetch all roles from the database, ordered by their display order
$stmt = $conn->prepare("SELECT * FROM nobadideas_roles ORDER BY display_order ASC, title ASC");
$stmt->execute();
$result = $stmt->get_result();
$roles = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$page_title = 'Manage Role Cards';
include 'header.php'; // Include the admin header
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manage Role Cards</h1>
        <a href="edit_role.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow transition-colors">+ Add New Role</a>
    </div>

    <!-- Roles Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Key Phase</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($roles)): ?>
                    <tr>
                        <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">No roles found. Add one to get started!</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($roles as $role): ?>
                        <tr>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($role['display_order']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap font-semibold"><?php echo htmlspecialchars($role['title']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                <p class="text-gray-900 whitespace-no-wrap"><?php echo htmlspecialchars($role['key_phase']); ?></p>
                            </td>
                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">
                                <a href="edit_role.php?id=<?php echo $role['id']; ?>" class="text-indigo-600 hover:text-indigo-900 font-semibold mr-4">Edit</a>
                                <a href="delete_role.php?id=<?php echo $role['id']; ?>" class="text-red-600 hover:text-red-900 font-semibold" onclick="return confirm('Are you sure you want to delete this role? This action cannot be undone.');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include 'footer.php'; // Include the admin footer
$conn->close();
?>
