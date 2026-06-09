<?php
    session_start();
    $pageTitle = 'Мой аккаунт';

    include 'connect.php';
    include 'Includes/functions/functions.php';
    include 'Includes/templates/header.php';
    include 'Includes/templates/navbar.php';

    if (!isset($_SESSION['user_id'])) {
        echo "<div class='alert alert-warning'>Пожалуйста, войдите или зарегистрируйтесь, чтобы просмотреть профиль.</div>";
        include 'Includes/templates/footer.php';
        exit;
    }

    $user_id = (int)$_SESSION['user_id'];
    $stmtUser = $con->prepare("SELECT username, email, full_name, first_name, last_name, phone, dob, bonus_points FROM users WHERE user_id = ?");
    $stmtUser->execute(array($user_id));
    $user = $stmtUser->fetch();

?>

<div class="container" style="max-width:900px; margin:40px auto;">
    <div class="card">
        <div class="card-header">
            Мой аккаунт
        </div>
        <div class="card-body">
            <h4>Добро пожаловать, <?php echo htmlspecialchars(($user['first_name'] ?: '') . ' ' . ($user['last_name'] ?: $user['full_name'] ?: $user['username'])); ?></h4>
            <p><strong>Электронная почта:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Имя:</strong> <?php echo htmlspecialchars($user['first_name'] ?: ''); ?></p>
            <p><strong>Фамилия:</strong> <?php echo htmlspecialchars($user['last_name'] ?: ''); ?></p>
            <p><strong>Телефон:</strong> <?php echo htmlspecialchars($user['phone'] ?: ''); ?></p>
            <p><strong>Дата рождения:</strong> <?php echo htmlspecialchars($user['dob'] ?: ''); ?></p>
            <p><strong>Баланс бонусов:</strong> <?php echo number_format((float)$user['bonus_points'], 2, ',', ' '); ?> бонусов</p>

            <hr />

            <h5>История заказов</h5>

            <?php
                $stmtOrders = $con->prepare("SELECT * FROM placed_orders WHERE user_id = ? ORDER BY order_time DESC");
                $stmtOrders->execute(array($user_id));
                $orders = $stmtOrders->fetchAll();

                if (!$orders) {
                    echo "<div class='alert alert-info'>Заказов пока нет.</div>";
                } else {
                    echo "<table class='table table-bordered'><thead><tr><th>Номер</th><th>Время</th><th>Сумма</th><th>Бонусы начислены</th><th>Бонусы списаны</th><th>Статус</th><th>Позиции</th></tr></thead><tbody>";
                    foreach ($orders as $order) {
                        $order_id = $order['order_id'];

                        // calculate order total from in_order
                        $stmtItems = $con->prepare("SELECT m.menu_name, m.menu_price, io.quantity FROM in_order io JOIN menus m ON io.menu_id = m.menu_id WHERE io.order_id = ?");
                        $stmtItems->execute(array($order_id));
                        $items = $stmtItems->fetchAll();

                        $total = 0;
                        $items_list = [];
                        foreach ($items as $it) {
                            $qty = isset($it['quantity']) ? $it['quantity'] : 1;
                            $subtotal = $it['menu_price'] * $qty;
                            $total += $subtotal;
                            $items_list[] = htmlspecialchars($it['menu_name']) . " x" . $qty;
                        }

                        $status = ($order['delivered']) ? 'Доставлен' : (($order['canceled']) ? 'Отменён' : 'В обработке');

                        echo "<tr>";
                        echo "<td>" . $order_id . "</td>";
                        echo "<td>" . $order['order_time'] . "</td>";
                        echo "<td>" . number_format((float)$total,2,',',' ') . "₽</td>";
                        echo "<td>" . number_format((float)$order['bonuses_earned'],2,',',' ') . "</td>";
                        echo "<td>" . number_format((float)$order['bonuses_spent'],2,',',' ') . "</td>";
                        echo "<td>" . $status . "</td>";
                        echo "<td>" . implode(', ', $items_list) . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table>";
                }
            ?>
        </div>
    </div>
</div>

<?php include 'Includes/templates/footer.php'; ?>
