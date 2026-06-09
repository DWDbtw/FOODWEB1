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

    $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'orders';

    // Handle add address POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_address') {
        $label = isset($_POST['address_label']) ? test_input($_POST['address_label']) : '';
        $address = isset($_POST['address_text']) ? test_input($_POST['address_text']) : '';
        if (!empty($address)) {
            $stmtIns = $con->prepare('INSERT INTO user_addresses (user_id, label, address) VALUES (?, ?, ?)');
            $stmtIns->execute(array($user_id, $label, $address));
            // refresh page to show new address
            header('Location: user_profile.php?tab=addresses');
            exit;
        }
    }
?>

<style>
:root {
    --accent: #7B5CF0;
    --accent-light: #EDE9FB;
    --accent-hover: #6A4DE0;
    --text-primary: #1A1A1A;
    --text-secondary: #6B6B6B;
    --border: #E8E8E8;
    --bg: #F2F2F2;
    --card-bg: #FFFFFF;
    --radius: 14px;
    --radius-sm: 8px;
}

body {
    background: var(--bg);
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: var(--text-primary);
}

.profile-wrapper {
    display: flex;
    gap: 24px;
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 20px;
    align-items: flex-start;
}

/* ── Sidebar ── */
.profile-sidebar {
    width: 240px;
    flex-shrink: 0;
    background: var(--card-bg);
    border-radius: var(--radius);
    padding: 20px 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}

.profile-user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 0 8px 16px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 12px;
}

.profile-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: var(--accent-light);
    color: var(--accent);
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.profile-user-name {
    font-weight: 600;
    font-size: 15px;
    line-height: 1.2;
}

.profile-user-phone {
    font-size: 13px;
    color: var(--text-secondary);
}

.sidebar-nav {
    list-style: none;
    margin: 0;
    padding: 0;
}

.sidebar-nav li a {
    display: block;
    padding: 10px 12px;
    border-radius: var(--radius-sm);
    font-size: 14px;
    color: var(--text-primary);
    text-decoration: none;
    transition: background .15s;
}

.sidebar-nav li a:hover {
    background: var(--bg);
}

.sidebar-nav li a.active {
    background: var(--accent-light);
    color: var(--accent);
    font-weight: 600;
}

.sidebar-divider {
    border: none;
    border-top: 1px solid var(--border);
    margin: 10px 8px;
}

.sidebar-logout a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    border-radius: var(--radius-sm);
    font-size: 14px;
    color: var(--text-secondary);
    text-decoration: none;
    transition: background .15s;
}

.sidebar-logout a:hover {
    background: var(--bg);
    color: var(--text-primary);
}

/* ── Main content ── */
.profile-content {
    flex: 1;
    min-width: 0;
}

.profile-card {
    background: var(--card-bg);
    border-radius: var(--radius);
    padding: 28px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    margin-bottom: 16px;
}

.profile-card h5 {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 20px;
}

/* ── Orders tab ── */
.orders-empty {
    color: var(--text-secondary);
    font-size: 15px;
    text-align: center;
    padding: 40px 0;
}

.orders-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.orders-table th {
    text-align: left;
    padding: 10px 12px;
    color: var(--text-secondary);
    font-weight: 500;
    border-bottom: 1px solid var(--border);
}

.orders-table td {
    padding: 12px 12px;
    border-bottom: 1px solid var(--border);
    vertical-align: top;
}

.orders-table tr:last-child td {
    border-bottom: none;
}

.status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-delivered { background: #E6F9F0; color: #1A8A4A; }
.status-canceled  { background: #FDECEA; color: #C0392B; }
.status-pending   { background: #FFF6E0; color: #B07A00; }

/* ── Addresses tab ── */
.add-address-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    width: 100%;
    padding: 16px 20px;
    border: 1.5px dashed var(--border);
    border-radius: var(--radius-sm);
    background: none;
    color: var(--accent);
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    text-align: left;
    transition: border-color .15s, background .15s;
}

.add-address-btn:hover {
    background: var(--accent-light);
    border-color: var(--accent);
}

/* ── Loyalty tab ── */
.loyalty-points-row {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 20px;
}

.loyalty-points-row .star-icon {
    color: #E05050;
    font-size: 22px;
}

.loyalty-how-title {
    font-size: 16px;
    font-weight: 700;
    margin: 0 0 14px;
}

.loyalty-items {
    list-style: none;
    margin: 0;
    padding: 0;
}

.loyalty-items li {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 14px 0;
    border-top: 1px solid var(--border);
    font-size: 14px;
    color: var(--text-primary);
    line-height: 1.5;
}

.loyalty-items li .li-icon {
    color: var(--accent);
    font-size: 18px;
    margin-top: 1px;
    flex-shrink: 0;
}

/* ── Data/Settings tab ── */
.form-group {
    margin-bottom: 14px;
}

.form-group label {
    display: block;
    font-size: 12px;
    color: var(--text-secondary);
    margin-bottom: 4px;
}

.form-control-profile {
    width: 100%;
    padding: 13px 16px;
    border: 1.5px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 15px;
    color: var(--text-primary);
    background: var(--card-bg);
    transition: border-color .15s;
    box-sizing: border-box;
}

.form-control-profile:focus {
    outline: none;
    border-color: var(--accent);
}

.dob-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 10px;
}

.btn-save {
    display: inline-block;
    padding: 14px 36px;
    background: var(--accent);
    color: #fff;
    border: none;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: .04em;
    cursor: pointer;
    transition: background .15s;
    margin-top: 6px;
}

.btn-save:hover {
    background: var(--accent-hover);
}

.btn-delete-profile {
    display: inline-block;
    margin-top: 14px;
    font-size: 14px;
    color: var(--accent);
    font-weight: 600;
    letter-spacing: .04em;
    text-decoration: none;
    cursor: pointer;
    background: none;
    border: none;
    padding: 0;
}

.btn-delete-profile:hover {
    text-decoration: underline;
}

/* Notifications */
.notifications-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0 0 6px;
}

.notifications-desc {
    font-size: 14px;
    color: var(--text-secondary);
    margin-bottom: 18px;
}

.notification-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 0;
    font-size: 15px;
}

.notification-item input[type=checkbox] {
    width: 18px;
    height: 18px;
    accent-color: var(--accent);
    cursor: pointer;
}

.notification-item.disabled {
    color: var(--text-secondary);
    opacity: .6;
}

@media (max-width: 680px) {
    .profile-wrapper { flex-direction: column; }
    .profile-sidebar { width: 100%; }
}
</style>

<?php
    $display_name = $user['first_name'] ?: $user['full_name'] ?: $user['username'];
    $avatar_letter = mb_strtoupper(mb_substr($display_name, 0, 1));
?>

<div class="profile-wrapper">

    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <div class="profile-user-info">
            <div class="profile-avatar"><?php echo htmlspecialchars($avatar_letter); ?></div>
            <div>
                <div class="profile-user-name"><?php echo htmlspecialchars($display_name); ?></div>
                <div class="profile-user-phone"><?php echo htmlspecialchars($user['phone'] ?: $user['email'] ?: ''); ?></div>
            </div>
        </div>

        <ul class="sidebar-nav">
            <li><a href="?tab=orders"    class="<?php echo $active_tab === 'orders'    ? 'active' : ''; ?>">Заказы</a></li>
            <li><a href="?tab=loyalty"   class="<?php echo $active_tab === 'loyalty'   ? 'active' : ''; ?>">Программа лояльности</a></li>
            <li><a href="?tab=data"      class="<?php echo $active_tab === 'data'      ? 'active' : ''; ?>">Данные</a></li>
        </ul>

        <hr class="sidebar-divider">

        <div class="sidebar-logout">
            <a href="logout.php">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Выйти
            </a>
        </div>
    </aside>

    <!-- Content -->
    <main class="profile-content">

        <?php if ($active_tab === 'orders'): ?>
        <div class="profile-card">
            <h5>Активные заказы</h5>
            <?php
                                // Fetch orders linked to this user directly, via clients.user_id,
                                // or where the client email/phone matches the user's contact info
                                $stmtOrders = $con->prepare(
                                        "SELECT po.* FROM placed_orders po
                                         LEFT JOIN clients c ON po.client_id = c.client_id
                                         WHERE po.user_id = ?
                                             OR c.user_id = ?
                                             OR (c.client_email = ? AND c.client_email <> '')
                                             OR (c.client_phone = ? AND c.client_phone <> '')
                                         ORDER BY po.order_time DESC"
                                );
                                $stmtOrders->execute(array($user_id, $user_id, $user['email'], $user['phone']));
                $orders = $stmtOrders->fetchAll();

                if (!$orders):
            ?>
                <div class="orders-empty">Заказов пока нет.</div>
            <?php else: ?>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Дата</th>
                            <th>Сумма</th>
                            <th>Баллы +</th>
                            <th>Баллы −</th>
                            <th>Статус</th>
                            <th>Состав</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order):
                        $order_id = $order['order_id'];
                        $stmtItems = $con->prepare("SELECT m.menu_name, m.menu_price, io.quantity FROM in_order io JOIN menus m ON io.menu_id = m.menu_id WHERE io.order_id = ?");
                        $stmtItems->execute(array($order_id));
                        $items = $stmtItems->fetchAll();

                        $total = 0;
                        $items_list = [];
                        foreach ($items as $it) {
                            $qty = isset($it['quantity']) ? $it['quantity'] : 1;
                            $total += $it['menu_price'] * $qty;
                            $items_list[] = htmlspecialchars($it['menu_name']) . ' ×' . $qty;
                        }

                        if ($order['delivered'])      { $status = 'Доставлен'; $badge = 'status-delivered'; }
                        elseif ($order['canceled'])   { $status = 'Отменён';   $badge = 'status-canceled'; }
                        else                          { $status = 'В обработке'; $badge = 'status-pending'; }
                    ?>
                        <tr>
                            <td><?php echo $order_id; ?></td>
                            <td><?php echo $order['order_time']; ?></td>
                            <td><?php echo number_format((float)$total, 2, ',', ' '); ?>₽</td>
                            <td><?php echo number_format((float)$order['bonuses_earned'], 2, ',', ' '); ?></td>
                            <td><?php echo number_format((float)$order['bonuses_spent'], 2, ',', ' '); ?></td>
                            <td><span class="status-badge <?php echo $badge; ?>"><?php echo $status; ?></span></td>
                            <td><?php echo implode(', ', $items_list); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Addresses tab removed from UI per request -->

        <?php elseif ($active_tab === 'loyalty'): ?>
        <div class="profile-card">
            <div class="loyalty-points-row">
                <span class="star-icon">★</span>
                <?php echo number_format((float)$user['bonus_points'], 0, ',', ' '); ?> баллов
            </div>
            <p class="loyalty-how-title">Как потратить баллы</p>
            <ul class="loyalty-items">
                <li>
                    <span class="li-icon">☆</span>
                    Накапливай баллы
                </li>
                <li>
                    <span class="li-icon">☆</span>
                    Заказывай чаще — чем больше сумма заказов, тем выше процент кешбэка и больше скидок
                </li>
                <li>
                    <span class="li-icon">☆</span>
                    Списывай до 20% от суммы заказа в следующую покупку
                </li>
            </ul>
        </div>

        <?php elseif ($active_tab === 'data'): ?>
        <div class="profile-card">
            <h5>Личные данные</h5>
            <form method="post" action="update_profile.php">
                <div class="form-group">
                    <label>Имя</label>
                    <input class="form-control-profile" type="text" name="first_name"
                           value="<?php echo htmlspecialchars($user['first_name'] ?: $user['full_name'] ?: $user['username']); ?>">
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input class="form-control-profile" type="tel" name="phone"
                           value="<?php echo htmlspecialchars($user['phone'] ?: ''); ?>">
                </div>
                <div class="form-group">
                    <label>Эл. почта</label>
                    <input class="form-control-profile" type="email" name="email"
                           value="<?php echo htmlspecialchars($user['email'] ?: ''); ?>">
                </div>
                <div class="form-group">
                    <?php
                        $dob = $user['dob'] ?: '';
                        $dob_day   = $dob ? (int)date('d', strtotime($dob)) : '';
                        $dob_month = $dob ? (int)date('m', strtotime($dob)) : '';
                        $dob_year  = $dob ? (int)date('Y', strtotime($dob)) : '';
                        $months = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
                    ?>
                    <div class="dob-row">
                        <select class="form-control-profile" name="dob_day">
                            <option value="">День</option>
                            <?php for ($d = 1; $d <= 31; $d++): ?>
                                <option value="<?php echo $d; ?>" <?php echo $dob_day == $d ? 'selected' : ''; ?>><?php echo $d; ?></option>
                            <?php endfor; ?>
                        </select>
                        <select class="form-control-profile" name="dob_month">
                            <option value="">Месяц</option>
                            <?php foreach ($months as $i => $m): ?>
                                <option value="<?php echo $i+1; ?>" <?php echo $dob_month == $i+1 ? 'selected' : ''; ?>><?php echo $m; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="form-control-profile" name="dob_year">
                            <option value="">Год</option>
                            <?php for ($y = date('Y'); $y >= 1940; $y--): ?>
                                <option value="<?php echo $y; ?>" <?php echo $dob_year == $y ? 'selected' : ''; ?>><?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn-save">СОХРАНИТЬ</button>
            </form>
            <br>
            <a href="delete_profile.php" class="btn-delete-profile"
               onclick="return confirm('Вы уверены, что хотите удалить профиль?')">УДАЛИТЬ ПРОФИЛЬ</a>
        </div>

        <div class="profile-card">
            <p class="notifications-title">Уведомления и рассылки</p>
            <p class="notifications-desc">Персональные предложения. Сообщения о новостях, акциях, скидках.</p>
            <div class="notification-item">
                <input type="checkbox" id="notif_push" checked>
                <label for="notif_push">Push-уведомления</label>
            </div>
            <div class="notification-item disabled">
                <input type="checkbox" id="notif_email" disabled>
                <label for="notif_email">Email-рассылки</label>
            </div>
            <div class="notification-item">
                <input type="checkbox" id="notif_sms" checked>
                <label for="notif_sms">SMS-уведомления</label>
            </div>
        </div>
        <?php endif; ?>

    </main>
</div>

<?php include 'Includes/templates/footer.php'; ?>
