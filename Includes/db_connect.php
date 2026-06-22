<?php
// Подключение к PostgreSQL на Render через PDO
$host = 'dpg-d8k9m977f7vs73c41cv0-a.oregon-postgres.render.com';
$port = '5432';
$dbname = 'restaurant_website';
$user = 'restaurant_website_user';
$password = 'DGePKVjhnAxRRLKNeLTDMFcBjxRfIVbN';

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET NAMES 'UTF8'");
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Функции для совместимости со старым кодом (pg_*)
function pg_query($conn, $query) {
    try {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        error_log("Query error: " . $e->getMessage());
        return false;
    }
}

function pg_fetch_all($result) {
    if ($result === false) return [];
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

function pg_fetch_assoc($result) {
    if ($result === false) return false;
    return $result->fetch(PDO::FETCH_ASSOC);
}

function pg_num_rows($result) {
    if ($result === false) return 0;
    return $result->rowCount();
}

function pg_last_error($conn = null) {
    return error_get_last()['message'] ?? '';
}

function pg_set_client_encoding($conn, $encoding) {
    try {
        $conn->exec("SET NAMES '$encoding'");
    } catch (PDOException $e) {
        // Игнорируем ошибки
    }
}

function pg_close($conn) {
    $conn = null;
}
?>
