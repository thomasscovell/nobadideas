<?php
// --- Handle POST requests for reordering ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'db_connect.php';
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action']) && $data['action'] === 'reorder_decks') {
        $ordered_ids = $data['order'] ?? [];
        
        if (empty($ordered_ids)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No order data provided.']);
            exit;
        }

        // Prepare the statement for updating
        $stmt = $conn->prepare("UPDATE nobadideas_decks SET deck_order = ? WHERE id = ?");
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement: ' . $conn->error]);
            exit;
        }

        $order_value = 10;
        foreach ($ordered_ids as $deck_id) {
            $stmt->bind_param('ii', $order_value, $deck_id);
            $stmt->execute();
            $order_value += 10; // Increment for the next deck
        }

        $stmt->close();
        $conn->close();

        echo json_encode(['success' => true, 'message' => 'Deck order updated successfully.']);
        exit;
    }
}

session_start();
require_once 'db_connect.php';

// --- Response Headers ---
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$action = $_GET['action'] ?? null;

// --- NEW: Handle sequential decks (Roles and Phases) ---
if ($action === 'get_roles' || $action === 'get_phases') {
    // Determine table name from action
    $table_name = ($action === 'get_roles') ? 'nobadideas_roles' : 'nobadideas_phases';

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM {$table_name} ORDER BY display_order ASC");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(['error' => 'Database query preparation failed: ' . $conn->error]);
        exit;
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $cards = $result->fetch_all(MYSQLI_ASSOC);

    $stmt->close();
    $conn->close();

    echo json_encode($cards);
    exit; // Stop script execution here, as the request is handled.
}


// --- EXISTING LOGIC FOR MAIN GAME (Random Draw) ---

// --- Response Headers ---
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// --- Input Validation ---
$action = $_GET['action'] ?? 'draw'; // Default action is to draw a card
$deck_id = (int)($_GET['deck_id'] ?? 0);

if ($deck_id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'A valid deck_id is required.']);
    exit;
}

if (!isset($_SESSION['game_decks'])) {
    http_response_code(400);
    echo json_encode(['error' => 'No game in progress. Please start a new game.']);
    exit;
}

// --- Action Router ---

if ($action === 'reshuffle_deck') {
    // --- Reshuffle a single deck ---
    // We'll ask the database to randomize the order for us directly.
    $stmt = $conn->prepare("SELECT * FROM nobadideas_cards WHERE deck_id = ? ORDER BY RAND()");
    $stmt->bind_param("i", $deck_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $newly_shuffled_deck = [];
    if ($result && $result->num_rows > 0) {
        while ($card = $result->fetch_assoc()) {
            $newly_shuffled_deck[] = $card;
        }
        // No need for PHP shuffle() anymore.
    }

    // Replace the deck in the session with the newly shuffled one
    $_SESSION['game_decks'][$deck_id] = $newly_shuffled_deck;
    $stmt->close();
    $conn->close();

    echo json_encode(['success' => true, 'message' => 'Deck has been reshuffled.']);
    exit;

} else {
    // --- Default action: Draw a card ---
    if (isset($_SESSION['game_decks'][$deck_id]) && !empty($_SESSION['game_decks'][$deck_id])) {
        
        $card = array_shift($_SESSION['game_decks'][$deck_id]);

        if ($card['image_url']) {
            $card['image_url'] = 'uploads/' . $card['image_url'];
        }

        echo json_encode($card);
        exit;

    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'No more cards in this deck!']);
        exit;
    }
}
?>
