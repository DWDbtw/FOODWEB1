<?php
session_start();
$pageTitle = 'Регистрация';
include 'connect.php';
include 'Includes/functions/functions.php';
include 'Includes/templates/header.php';
include 'Includes/templates/navbar.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = test_input($_POST['username']);
    $email = test_input($_POST['email']);
    $password = $_POST['password'];
    $first_name = test_input($_POST['first_name']);
    $last_name = test_input($_POST['last_name']);
    $phone = test_input($_POST['phone']);
    $dob = !empty($_POST['dob']) ? $_POST['dob'] : null;

    if (strlen($username) < 3) $errors[] = 'Имя пользователя минимум 3 символа';
    if (strlen($password) < 6) $errors[] = 'Пароль минимум 6 символов';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Неверный email';

    if (empty($errors)) {
        $res = register_user($con, $username, $email, $password, trim($first_name . ' ' . $last_name), $phone, $dob);
        if ($res['success']) {
            $_SESSION['user_id'] = $res['user_id'];
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            header('Location: user_profile.php');
            exit;
        } else {
            $errors[] = $res['message'];
        }
    }
}
?>

<div class="container" style="max-width:600px; margin:40px auto;">
    <div class="card">
        <div class="card-header">Регистрация</div>
        <div class="card-body">
            <?php if ($errors): ?>
                <div class="alert alert-danger"><?php echo implode('<br>', $errors); ?></div>
            <?php endif; ?>
            <form method="post" action="register.php">
                <div class="form-group">
                    <label>Имя пользователя</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>Имя</label>
                        <input type="text" name="first_name" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Фамилия</label>
                        <input type="text" name="last_name" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label>Дата рождения</label>
                    <input type="date" name="dob" class="form-control">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary">Зарегистрироваться</button>
            </form>
        </div>
    </div>
</div>

<?php include 'Includes/templates/footer.php'; ?>
