<?php
require_once 'Includes/db_connect.php';

if ($conn) {
    echo "✅ Подключение к БД успешно!<br>";
    echo "Версия PostgreSQL: " . $conn->getAttribute(PDO::ATTR_SERVER_VERSION) . "<br>";
    
    // Проверяем таблицы
    $result = pg_query($conn, "SELECT table_name FROM information_schema.tables WHERE table_schema='public' LIMIT 5");
    if ($result) {
        echo "<br>📋 Таблицы в БД:<br>";
        while ($row = pg_fetch_assoc($result)) {
            echo "- " . $row['table_name'] . "<br>";
        }
    }
} else {
    echo "❌ Ошибка подключения!";
}
?>
