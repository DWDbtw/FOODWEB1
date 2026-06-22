<?php
session_start();
require_once 'Includes/db_connect.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID not provided']);
    exit;
}

$id = (int)$_GET['id'];

$query = "SELECT id, name, description, price, image_url, protein, fat, carbs, calories 
          FROM menu_items 
          WHERE id = $id";
$result = pg_query($conn, $query);

if (!$result || pg_num_rows($result) === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Item not found']);
    exit;
}

$item = pg_fetch_assoc($result);
echo json_encode($item);
?>