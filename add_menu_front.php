<?php
header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

require_once __DIR__ . '/connect.php';

$uid = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0;
if (!$uid) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

try {
    $stmt = $con->prepare("SELECT role FROM users WHERE user_id = ? LIMIT 1");
    $stmt->execute([$uid]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r || !in_array($r['role'], ['admin', 'manager'])) {
        echo json_encode(['success' => false, 'message' => 'Permission denied']);
        exit;
    }

    $menu_id     = isset($_POST['menu_id'])         ? (int)$_POST['menu_id']          : 0;
    $menu_name   = isset($_POST['menu_name'])        ? trim($_POST['menu_name'])        : '';
    $category_id = isset($_POST['category_id'])      ? (int)$_POST['category_id']      : 0;
    $description = isset($_POST['menu_description']) ? trim($_POST['menu_description']) : '';

    if ($menu_name === '' || $category_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
        exit;
    }

    // Проверка категории
    $stmtc = $con->prepare("SELECT category_id FROM menu_categories WHERE category_id = ? LIMIT 1");
    $stmtc->execute([$category_id]);
    if ($stmtc->rowCount() == 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid category']);
        exit;
    }

    // Цена
    $menu_price_raw = isset($_POST['menu_price']) ? trim($_POST['menu_price']) : '';
    if ($menu_price_raw === '') {
        $price = 0;
    } else {
        $menu_price_raw = str_replace(',', '.', $menu_price_raw);
        $menu_price_raw = preg_replace('/[^0-9.\-]/', '', $menu_price_raw);
        $price = is_numeric($menu_price_raw) ? number_format((float)$menu_price_raw, 2, '.', '') : 0;
    }

    // КБЖУ — необязательные, NULL если не заполнено
    $calories = (isset($_POST['calories']) && $_POST['calories'] !== '') ? (float)$_POST['calories'] : null;
    $proteins = (isset($_POST['proteins']) && $_POST['proteins'] !== '') ? (float)$_POST['proteins'] : null;
    $fats     = (isset($_POST['fats'])     && $_POST['fats']     !== '') ? (float)$_POST['fats']     : null;
    $carbs    = (isset($_POST['carbs'])    && $_POST['carbs']    !== '') ? (float)$_POST['carbs']    : null;

    // Загрузка изображения
    $uploadName = '';
    if (isset($_FILES['menu_image']['tmp_name']) && is_uploaded_file($_FILES['menu_image']['tmp_name'])) {
        $file = $_FILES['menu_image'];
        if ($file['error'] === 0 && $file['size'] > 0) {
            $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ext, $allowed)) {
                echo json_encode(['success' => false, 'message' => 'Invalid image type']);
                exit;
            }
            $targetDir = __DIR__ . '/admin/Uploads/images/';
            if (!is_dir($targetDir)) @mkdir($targetDir, 0755, true);
            $uploadName = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
            if (!move_uploaded_file($file['tmp_name'], $targetDir . $uploadName)) {
                echo json_encode(['success' => false, 'message' => 'Failed to save image']);
                exit;
            }
        }
    }

    if ($menu_id > 0) {
        // Редактирование существующей позиции
        if ($uploadName === '') {
            $stmtUpd = $con->prepare("
                UPDATE menus
                SET category_id = ?, menu_name = ?, menu_description = ?, menu_price = ?,
                    calories = ?, proteins = ?, fats = ?, carbs = ?
                WHERE menu_id = ?
            ");
            $stmtUpd->execute([$category_id, $menu_name, $description, $price,
                                $calories, $proteins, $fats, $carbs, $menu_id]);
        } else {
            $stmtUpd = $con->prepare("
                UPDATE menus
                SET category_id = ?, menu_name = ?, menu_description = ?, menu_price = ?,
                    menu_image = ?, calories = ?, proteins = ?, fats = ?, carbs = ?
                WHERE menu_id = ?
            ");
            $stmtUpd->execute([$category_id, $menu_name, $description, $price,
                                $uploadName, $calories, $proteins, $fats, $carbs, $menu_id]);
        }
        echo json_encode(['success' => true, 'message' => 'Menu updated']);
    } else {
        // Добавление новой позиции
        $stmtIns = $con->prepare("
            INSERT INTO menus (category_id, menu_name, menu_description, menu_price, menu_image,
                               calories, proteins, fats, carbs)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtIns->execute([$category_id, $menu_name, $description, $price, $uploadName,
                           $calories, $proteins, $fats, $carbs]);
        echo json_encode(['success' => true, 'message' => 'Menu added']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
