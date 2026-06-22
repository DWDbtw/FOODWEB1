<?php
// Подключение к PostgreSQL на Render
$host = 'dpg-d8k9m977f7vs73c41cv0-a.oregon-postgres.render.com';
$port = '5432';
$dbname = 'restaurant_website';
$user = 'restaurant_website_user';
$password = 'DGePKVjhnAxRRLKNeLTDMFcBjxRfIVbN';

// Строка подключения
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Подключение
$conn = pg_connect($conn_string);

if (!$conn) {
    die("Ошибка подключения к базе данных: " . pg_last_error());
}

// Устанавливаем кодировку UTF-8
pg_set_client_encoding($conn, "UTF8");

// Для отладки (можно удалить после проверки)
// echo "Подключение к БД успешно!";
?>
