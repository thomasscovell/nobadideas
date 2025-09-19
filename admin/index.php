<?php
// The session must be started before any HTML is sent to the browser.
// header.php also starts the session, but for this page's logic to work,
// we need to ensure it's started *before* the redirect check.
session_start();

// If the user is already logged in, redirect them to the dashboard immediately.
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// If the script gets this far, the user is not logged in.
// Now it's safe to include the HTML header and show the login form.
require_once 'header.php';
?>

<div class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center text-gray-900">Admin Login</h1>

        <?php
        // Check if there is a login error message to display
        if (isset($_SESSION['login_error'])) {
            echo '<div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">';
            echo htmlspecialchars($_SESSION['login_error']);
            echo '</div>';
            // Unset the error message so it doesn't show again on refresh
            unset($_SESSION['login_error']);
        }
        ?>

        <form action="auth.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="text-sm font-semibold text-gray-700">Username</label>
                <input type="text" id="username" name="username" required
                       class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 focus:outline-none focus:ring focus:ring-opacity-40">
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                       class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md focus:border-blue-500 focus:ring-blue-500 focus:outline-none focus:ring focus:ring-opacity-40">
            </div>

            <div>
                <button type="submit"
                        class="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Log In
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'footer.php'; ?>