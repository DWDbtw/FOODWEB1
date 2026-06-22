<?php
session_start();

// Простая заглушка - замените на реальную логику получения количества из БД или сессии
$count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;

echo json_encode(['count' => $count]);
?>