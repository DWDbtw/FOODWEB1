<?php
include 'connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$date = isset($_POST['date']) ? $_POST['date'] : '';
$time = isset($_POST['time']) ? $_POST['time'] : '';
$guests = isset($_POST['guests']) ? (int)$_POST['guests'] : 0;

if (empty($date) || empty($time) || $guests <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
    exit;
}

$selectedDateTime = $date . ' ' . $time;

try {
    // Query to find available tables for the selected date and time
    $stmt = $con->prepare("
        SELECT table_id FROM tables
        WHERE table_id NOT IN (
            SELECT table_id FROM reservations
            WHERE selected_time = ?
            AND liberated = 0
            AND canceled = 0
        )
        LIMIT 1
    ");

    $stmt->execute(array($selectedDateTime));
    $result = $stmt->fetch();

    if ($result) {
        echo json_encode([
            'success' => true,
            'table_id' => $result['table_id'],
            'message' => 'Table available'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'No tables available'
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
