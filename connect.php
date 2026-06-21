<?php
    $db_url = getenv('DATABASE_URL');
    if ($db_url) {
        $parsed = parse_url($db_url);
        $host   = $parsed['host'];
        $port   = isset($parsed['port']) ? $parsed['port'] : 5432;
        $dbname = ltrim($parsed['path'], '/');
        $user   = $parsed['user'];
        $pass   = $parsed['pass'];
        $dsn    = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    } else {
        // Локальная разработка
        $dsn    = 'pgsql:host=localhost;port=5432;dbname=restaurant_website';
        $user   = 'postgres';
        $pass   = '';
    }

    try {
        $con = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $ex) {
        echo "Failed to connect with database! " . $ex->getMessage();
        die();
    }
?>
