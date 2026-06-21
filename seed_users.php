<?php
// seed_users.php — add/update three default accounts (client, manager, admin)
// Usage: php seed_users.php

require_once 'connect.php';

function ensureRoleColumn($con) {
    // PostgreSQL way to check if column exists
    $sql = "SELECT column_name FROM information_schema.columns 
            WHERE table_name = 'users' AND column_name = 'role'";
    $stmt = $con->prepare($sql);
    $stmt->execute();
    $col = $stmt->fetchColumn();
    if (!$col) {
        echo "Adding 'role' column to users table...\n";
        $con->exec("ALTER TABLE users ADD COLUMN role VARCHAR(20) NOT NULL DEFAULT 'client'");
    }
}

try {
    $con->beginTransaction();

    ensureRoleColumn($con);

    $accounts = [
        ['username' => 'client_user',  'email' => 'client@example.com',  'full_name' => 'Client User',   'password' => 'Client123!',  'role' => 'client'],
        ['username' => 'manager_user', 'email' => 'manager@example.com', 'full_name' => 'Manager User',  'password' => 'Manager123!', 'role' => 'manager'],
        ['username' => 'admin_user',   'email' => 'admin@example.com',   'full_name' => 'Administrator', 'password' => 'Admin123!',   'role' => 'admin'],
    ];

    $checkStmt  = $con->prepare("SELECT user_id FROM users WHERE username = ? OR email = ? LIMIT 1");
    $insertStmt = $con->prepare("INSERT INTO users (username, email, full_name, password, role) VALUES (?, ?, ?, ?, ?)");
    $updateStmt = $con->prepare("UPDATE users SET email = ?, full_name = ?, password = ?, role = ? WHERE user_id = ?");

    foreach ($accounts as $acc) {
        $checkStmt->execute([$acc['username'], $acc['email']]);
        $found = $checkStmt->fetch(PDO::FETCH_ASSOC);
        $passwordHash = password_hash($acc['password'], PASSWORD_DEFAULT);

        if ($found) {
            $user_id = $found['user_id'];
            echo "User exists (id={$user_id}, username={$acc['username']}) — updating...\n";
            $updateStmt->execute([$acc['email'], $acc['full_name'], $passwordHash, $acc['role'], $user_id]);
        } else {
            echo "Inserting user {$acc['username']} ({$acc['role']})...\n";
            $insertStmt->execute([$acc['username'], $acc['email'], $acc['full_name'], $passwordHash, $acc['role']]);
        }
    }

    $con->commit();
    echo "Done. Default passwords: Client123!, Manager123!, Admin123!\n";
    echo "Please change these passwords immediately after first login.\n";
} catch (Exception $e) {
    $con->rollBack();
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
