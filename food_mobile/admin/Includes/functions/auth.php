<?php
if (session_status() == PHP_SESSION_NONE) session_start();

function admin_map_legacy_session() {
    // Map legacy session keys to new admin session keys for backward compatibility
    if (isset($_SESSION['userid_restaurant_qRewacvAqzA']) && !isset($_SESSION['admin_user_id'])) {
        $_SESSION['admin_user_id'] = $_SESSION['userid_restaurant_qRewacvAqzA'];
    }
    if (isset($_SESSION['username_restaurant_qRewacvAqzA']) && !isset($_SESSION['admin_username'])) {
        $_SESSION['admin_username'] = $_SESSION['username_restaurant_qRewacvAqzA'];
    }
}

function admin_require_login($con) {
    admin_map_legacy_session();
    if (empty($_SESSION['admin_user_id'])) {
        header('Location: index.php');
        exit();
    }
    // Ensure role is loaded
    if (empty($_SESSION['admin_role'])) {
        $stmt = $con->prepare("SELECT role FROM users WHERE user_id = ? LIMIT 1");
        $stmt->execute(array((int)$_SESSION['admin_user_id']));
        $role = $stmt->fetchColumn();
        $_SESSION['admin_role'] = $role ?: 'client';
    }
}

function admin_require_role($con, $allowed = array()) {
    admin_require_login($con);
    $role = isset($_SESSION['admin_role']) ? $_SESSION['admin_role'] : '';
    if (!in_array($role, (array)$allowed)) {
        http_response_code(403);
        echo "<div style='padding:30px;'><h2>Доступ запрещён</h2><p>У вас нет прав для просмотра этой страницы.</p></div>";
        exit();
    }
}

function admin_current_user($con) {
    admin_map_legacy_session();
    if (empty($_SESSION['admin_user_id'])) return null;
    static $cached = null;
    if ($cached) return $cached;
    $stmt = $con->prepare("SELECT user_id, username, email, role, first_name, last_name FROM users WHERE user_id = ? LIMIT 1");
    $stmt->execute(array((int)$_SESSION['admin_user_id']));
    $cached = $stmt->fetch(PDO::FETCH_ASSOC);
    return $cached;
}

?>
