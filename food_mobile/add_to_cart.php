<?php
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$menu_id = isset($_POST['menu_id']) ? (int)$_POST['menu_id'] : 0;

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        if ($menu_id > 0) {
            if (isset($_SESSION['cart'][$menu_id])) {
                $_SESSION['cart'][$menu_id]++;
            } else {
                $_SESSION['cart'][$menu_id] = 1;
            }
            $total = array_sum($_SESSION['cart']);
            echo json_encode(['success' => true, 'cart_total' => $total, 'message' => 'блюдо было добавлено в корзину']);
        }
        break;

    case 'remove':
        if ($menu_id > 0 && isset($_SESSION['cart'][$menu_id])) {
            $_SESSION['cart'][$menu_id]--;
            if ($_SESSION['cart'][$menu_id] <= 0) {
                unset($_SESSION['cart'][$menu_id]);
            }
            $total = array_sum($_SESSION['cart']);
            echo json_encode(['success' => true, 'cart_total' => $total]);
        }
        break;

    case 'update':
        if ($menu_id > 0 && isset($_POST['qty'])) {
            $qty = (int)$_POST['qty'];
            if ($qty <= 0) {
                unset($_SESSION['cart'][$menu_id]);
            } else {
                $_SESSION['cart'][$menu_id] = $qty;
            }
            $total = array_sum($_SESSION['cart']);
            echo json_encode(['success' => true, 'cart_total' => $total]);
        }
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        echo json_encode(['success' => true, 'cart_total' => 0]);
        break;

    case 'count':
        $total = array_sum($_SESSION['cart']);
        echo json_encode(['success' => true, 'cart_total' => $total]);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
}