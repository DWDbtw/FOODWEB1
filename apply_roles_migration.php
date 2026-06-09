<?php
// apply_roles_migration.php
// Creates roles table (if missing), seeds roles and aligns `users.role` values

require_once 'connect.php';

try {
    $con->beginTransaction();

    // Ensure roles table exists and seeded (use migration SQL if present)
    $migration = __DIR__ . '/migrations/create_roles_table.sql';
    if (file_exists($migration)) {
        $sql = file_get_contents($migration);
        $con->exec($sql);
        echo "Applied migration SQL: create_roles_table.sql\n";
    } else {
        // fallback: create table directly
        $con->exec("CREATE TABLE IF NOT EXISTS `roles` (
            `role_id` int(5) NOT NULL AUTO_INCREMENT,
            `role_key` varchar(50) NOT NULL UNIQUE,
            `role_name` varchar(100) NOT NULL,
            `description` text DEFAULT NULL,
            PRIMARY KEY (`role_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    }

    // Insert roles if missing
    $roles = [
        ['admin','Администратор','Полный доступ: управление сайтом, пользователями и меню.'],
        ['manager','Менеджер','Управление меню, обработка заказов, изменение изображений блюд.'],
        ['client','Клиент','Обычный клиент сайта.']
    ];
    $ins = $con->prepare("INSERT IGNORE INTO roles (role_key, role_name, description) VALUES (?, ?, ?)");
    foreach ($roles as $r) {
        $ins->execute($r);
    }

    // Ensure users.role column exists
    $colStmt = $con->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'role'");
    $colStmt->execute();
    if (!$colStmt->fetch()) {
        echo "Adding 'role' column to users table...\n";
        $con->exec("ALTER TABLE users ADD COLUMN role varchar(20) NOT NULL DEFAULT 'client' AFTER password");
    }

    // Map existing usernames to roles
    $map = [
        'admin_user' => 'admin',
        'admin2' => 'admin',
        'manager_user' => 'manager',
        'client_user' => 'client'
    ];
    $update = $con->prepare("UPDATE users SET role = ? WHERE username = ?");
    foreach ($map as $username => $role) {
        $update->execute([$role, $username]);
    }

    // For any users with missing/invalid role, set to client
    $validRoles = ['admin','manager','client'];
    $placeholders = implode(',', array_fill(0, count($validRoles), '?'));
    $stmtInvalid = $con->prepare("SELECT user_id FROM users WHERE role NOT IN ($placeholders) OR role IS NULL");
    $stmtInvalid->execute($validRoles);
    $invalid = $stmtInvalid->fetchAll(PDO::FETCH_COLUMN);
    if (!empty($invalid)) {
        $fix = $con->prepare("UPDATE users SET role = 'client' WHERE user_id = ?");
        foreach ($invalid as $uid) $fix->execute([$uid]);
    }

    $con->commit();
    echo "Roles table ensured and users updated.\n";
    echo "Inserted roles: admin, manager, client.\n";
    echo "Run 'php seed_users.php' first if needed to create default users.\n";
} catch (Exception $e) {
    $con->rollBack();
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

?>
