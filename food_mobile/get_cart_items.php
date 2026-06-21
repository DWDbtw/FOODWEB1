<?php
session_start();
include 'connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$cart = isset($data['cart']) ? $data['cart'] : array();

if (empty($cart)) {
    echo json_encode(['success' => false, 'items' => []]);
    exit;
}

$items = [];
$total = 0;

foreach ($cart as $menu_id => $qty) {
    $stmt = $con->prepare("SELECT menu_id, menu_name, menu_price, category_id FROM menus WHERE menu_id = ?");
    $stmt->execute(array($menu_id));
    $menu_data = $stmt->fetch();
    
    if ($menu_data) {
        // Get category name
        $stmt_cat = $con->prepare("SELECT category_name FROM menu_categories WHERE category_id = ?");
        $stmt_cat->execute(array($menu_data['category_id']));
        $cat_data = $stmt_cat->fetch();
        
        $category_name = $cat_data ? $cat_data['category_name'] : 'Без категории';
        $item_subtotal = $menu_data['menu_price'] * $qty;
        $total += $item_subtotal;
        
        $items[] = array(
            'category' => $category_name,
            'menu_id' => $menu_id,
            'name' => $menu_data['menu_name'],
            'price' => $menu_data['menu_price'],
            'qty' => $qty,
            'subtotal' => $item_subtotal
        );
    }
}

echo json_encode(['success' => true, 'items' => $items, 'total' => $total]);
?>
