<?php
session_start();
$pageTitle = 'Вход';
include 'connect.php';
include 'Includes/functions/functions.php';
include 'Includes/templates/header.php';
include 'Includes/templates/navbar.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    $hashed = sha1($password);

    $stmt = $con->prepare("SELECT user_id, username, email FROM users WHERE username = ? AND password = ?");
    $stmt->execute(array($username, $hashed));
    $user = $stmt->fetch();
    if ($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        header('Location: user_profile.php');
        exit;
    } else {
        $error = 'Неверное имя пользователя или пароль';
    }
}
?>

<div class="container" style="max-width:420px; margin:40px auto;">
    <div class="card">
        <div class="card-header">Вход</div>
        <div class="card-body">
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post" action="login.php">
                <div class="form-group">
                    <label>Имя пользователя</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Войти</button>
                <a href="register.php" class="btn btn-link">Регистрация</a>
            </form>
        </div>
    </div>
</div>

<?php include 'Includes/templates/footer.php'; ?>
