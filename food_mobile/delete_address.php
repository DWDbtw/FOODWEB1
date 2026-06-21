<?php
session_start();
include 'connect.php';
include 'Includes/functions/functions.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); exit;
}
$user_id = (int)$_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address_id'])) {
    $address_id = (int)$_POST['address_id'];
    $stmt = $con->prepare('DELETE FROM user_addresses WHERE address_id = ? AND user_id = ?');
    $stmt->execute(array($address_id, $user_id));
}
header('Location: user_profile.php?tab=addresses');
exit;
?>