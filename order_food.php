<?php
    //Set page title
    $pageTitle = 'Заказ Еды';
    
    session_start();

    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
    // order page styles applied inline below; no page-level class injected
    
    // Determine login state and preload user data for prefilling
    $is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    $current_user = null;
    $user_bonuses = array('available_bonuses' => 0);
    if ($is_logged_in) {
        $current_user_id = (int)$_SESSION['user_id'];
        $stmtUser = $con->prepare("SELECT user_id, username, email, full_name, first_name, last_name, phone, dob, bonus_points FROM users WHERE user_id = ?");
        $stmtUser->execute(array($current_user_id));
        $current_user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        try {
            $user_bonuses = array('available_bonuses' => (float)get_user_bonuses($con, $current_user_id));
        } catch (Exception $e) {
            $user_bonuses = array('available_bonuses' => 0);
        }
    }

    // Initialize cart if not exists
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

?>

	<!-- ORDER FOOD PAGE STYLE -->

	<style type="text/css">
        body
        {
            background: #f7f7f7;
        }

        .text_header
        {
            margin-bottom: 5px;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.5;
            margin-top: 22px;
            text-transform: capitalize;
            color: #000;
        }

        .items_tab
        {
            border-radius: 4px;
            background-color: white;
            overflow: hidden;
            box-shadow: 0 0 5px 0 rgba(60, 66, 87, 0.04), 0 0 10px 0 rgba(0, 0, 0, 0.04);
        }

        .itemListElement
        {
            font-size: 14px;
            line-height: 1.29;
            border-bottom: solid 1px #222222;
            cursor: pointer;
            padding: 16px 12px 18px 12px;
        }

        .item_details
        {
            width: auto;
            display: -webkit-box;
            display: -moz-box;
            display: -ms-flexbox;
            display: -webkit-flex;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -webkit-flex-direction: row;
            -webkit-box-pack: justify;
            -webkit-justify-content: space-between;
            -webkit-box-align: center;
            -webkit-align-items: center;
        }

        .item_label
        {
        	color: #9e8a78;
            border-color: #9e8a78;
            background: white;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-secondary:not(:disabled):not(.disabled).active, .btn-secondary:not(:disabled):not(.disabled):active 
        {
            color: #fff;
            background-color: #9e8a78;
            border-color: #9e8a78;
        }

        .item_select_part
        {
            display: flex;
            -webkit-box-pack: justify;
            justify-content: space-between;
            -webkit-box-align: center;
            align-items: center;
            flex-shrink: 0;
        }

        .select_item_bttn
        {
            width: 55px;
            display: flex;
            margin-left: 30px;
            -webkit-box-pack: end;
            justify-content: flex-end;
        }

        .menu_price_field
        {
    		width: auto;
            display: flex;
            margin-left: 30px;
            -webkit-box-align: baseline;
            align-items: baseline;
        }

        .order_food_section
        {
            width: 900px;
            max-width: 95%;
            margin: 28px auto 60px auto;
            padding: 28px;
            box-sizing: border-box;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 24px rgba(0,0,0,0.08);
        }

        /* internal tab content spacing */
        .order_food_section .order_food_tab {
            padding: 12px 4px 24px 4px;
            box-sizing: border-box;
        }

        /* Uniform control sizes during checkout */
        .order_food_section .form-control,
        .order_food_section input[type="text"],
        .order_food_section input[type="email"],
        .order_food_section input[type="password"],
        .order_food_section input[type="date"] {
            height: 44px;
            line-height: 1.2;
            font-size: 15px;
            padding: 8px 12px;
            width: 100%;
            display: block;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #e6e6e6;
            background: #fff;
        }

        .order_food_section textarea {
            min-height: 100px;
            padding: 10px 12px;
            resize: vertical;
            width: 100%;
            box-sizing: border-box;
            border-radius: 4px;
            border: 1px solid #e6e6e6;
        }

        .order_food_section .next_prev_buttons,
        .order_food_section .select_item_bttn button {
            height: 44px;
            line-height: 44px;
            padding: 0 18px;
            border-radius: 4px;
            font-size: 15px;
        }

        /* Keep the navigation controls at the bottom of the card */
        .order_controls_wrap {
            padding-top: 12px;
            padding-bottom: 6px;
            margin-top: 10px;
            box-sizing: border-box;
        }

        /* Ensure normal page scroll and footer flow to avoid overlap */
        html, body { overflow: auto; }

        /* Make widget footer part of normal flow (not fixed) */
        .widget_section {
            position: relative;
        }

        .item_label.focus,
        .item_label:focus
        {
            outline: none;
            background:initial;
            box-shadow: none;
            color: #9e8a78;
            border-color: #9e8a78;
        }

        .item_label:hover
        {
            color: #fff;
            background-color: #9e8a78;
            border-color: #9e8a78;
        }

        /* Make circles that indicate the steps of the form: */
        .step 
        {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbbbbb;
            border: none;  
            border-radius: 50%;
            display: inline-block;
            opacity: 0.5;
        }

        .step.active 
        {
            opacity: 1;
        }

        /* Mark the steps that are finished and valid: */
        .step.finish 
        {
            background-color: #4CAF50;
        }


        .order_food_tab
        {
            display: none;
        }

        .next_prev_buttons
        {
            background-color: #4CAF50;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            font-size: 17px;
            cursor: pointer;
        }

        .client_details_tab  .form-control
        {
            background-color: #fff;
            border-radius: 0;
            padding: 25px 10px;
            box-shadow: none;
            border: 2px solid #eee;
        }

        .client_details_tab  .form-control:focus 
        {
            border-color: #AFC4D5;
            box-shadow: none;
            outline: none;
        }

	</style>

    <!-- START ORDER FOOD SECTION -->

	<section class="order_food_section">

        <?php

            if(isset($_POST['submit_order_food_form']) && $_SERVER['REQUEST_METHOD'] === 'POST')
            {
                // Get cart items
                $selected_menus = array();
                $cart_total = 0;
                
                if (!empty($_SESSION['cart'])) {
                    foreach($_SESSION['cart'] as $menu_id => $qty) {
                        $stmt_price = $con->prepare("SELECT menu_price FROM menus WHERE menu_id = ?");
                        $stmt_price->execute([$menu_id]);
                        $menu = $stmt_price->fetch();
                        
                        if ($menu) {
                            $cart_total += $menu['menu_price'] * $qty;
                            for($i = 0; $i < $qty; $i++) {
                                $selected_menus[] = $menu_id;
                            }
                        }
                    }
                }

                // If no items in cart, show error
                if (empty($selected_menus)) {
                    echo "<div class='alert alert-danger'>Корзина пуста! Пожалуйста, добавьте товары перед оформлением заказа.</div>";
                } else {

                    //Client Details
                    $client_full_name = test_input($_POST['client_full_name']);
                    $delivery_address = test_input($_POST['client_delivery_address']);
                    $client_phone_number = test_input($_POST['client_phone_number']);
                    $client_email = test_input($_POST['client_email']);
                    
                    // Bonuses
                    $bonuses_to_spend = isset($_POST['use_bonuses']) ? (float)$_POST['use_bonuses'] : 0;
                    $discount_amount = 0;
                    
                    // Registration option
                    $wants_to_register = isset($_POST['register_account']) ? true : false;
                    $register_username = isset($_POST['register_username']) ? test_input($_POST['register_username']) : '';
                    $register_password = isset($_POST['register_password']) ? $_POST['register_password'] : '';
                    
                    // Extras
                    $extras_napkins = isset($_POST['extras_napkins']) ? (int)$_POST['extras_napkins'] : 0;
                    $extras_chopsticks = isset($_POST['extras_chopsticks']) ? (int)$_POST['extras_chopsticks'] : 0;
                    $extras_soy_sauce = isset($_POST['extras_soy_sauce']) ? (int)$_POST['extras_soy_sauce'] : 0;
                    $extras_ginger = isset($_POST['extras_ginger']) ? (int)$_POST['extras_ginger'] : 0;
                    $extras_wasabi = isset($_POST['extras_wasabi']) ? (int)$_POST['extras_wasabi'] : 0;

                    $con->beginTransaction();
                    try
                    {
                        // Register user if requested
                        $registered_user_id = null;
                        if ($wants_to_register && !empty($register_username) && !empty($register_password)) {
                            $register_result = register_user($con, $register_username, $client_email, $register_password, $client_full_name, $client_phone_number);
                            if ($register_result['success']) {
                                $registered_user_id = $register_result['user_id'];
                                $_SESSION['user_id'] = $registered_user_id;
                                $_SESSION['username'] = $register_username;
                                $_SESSION['email'] = $client_email;
                            }
                        }
                        
                        // Handle bonus spending if user is logged in and wants to spend bonuses
                        if ($is_logged_in && $bonuses_to_spend > 0) {
                            // Verify user has enough bonuses
                            if ($user_bonuses['available_bonuses'] >= $bonuses_to_spend) {
                                $discount_amount = min($bonuses_to_spend, $cart_total); // Can't discount more than order total
                            } else {
                                throw new Exception('Недостаточно бонусов для использования');
                            }
                        }

                        // Get or create client
                        $stmtClient = $con->prepare("insert into clients(client_name,client_phone,client_email, user_id) values(?,?,?,?)");
                        $stmtClient->execute(array($client_full_name,$client_phone_number,$client_email, $registered_user_id));
                        $client_id_val = $con->lastInsertId();
                        
                            // Calculate bonuses earned for this order (based on cart total before discount)
                            $bonuses_earned = calculate_order_bonuses($con, $cart_total);

                            // Payment method (submitted from client-side). Default to 'cash' if not provided.
                            $payment_method = isset($_POST['payment_method']) ? test_input($_POST['payment_method']) : 'cash';

                            // Create order with bonus information
                            $stmt_order = $con->prepare("insert into placed_orders(order_time, client_id, user_id, delivery_address, bonuses_earned, bonuses_spent, discount_amount) 
                                                         values(?, ?, ?, ?, ?, ?, ?)");
                            $stmt_order->execute(array(
                                Date("Y-m-d H:i"),
                                $client_id_val,
                                $registered_user_id,
                                $delivery_address,
                                $bonuses_earned,
                                $discount_amount,
                                $discount_amount
                            ));
                            $order_id_val = $con->lastInsertId();

                        // Add items to order
                        foreach($selected_menus as $menu) {
                            $stmt = $con->prepare("insert into in_order(order_id, menu_id) values(?, ?)");
                            $stmt->execute(array($order_id_val, $menu));
                        }
                        
                        // Add bonuses to account if user is registered (new or existing)
                        $bonus_user_id = $registered_user_id ? $registered_user_id : ($is_logged_in ? $current_user_id : null);
                        if ($bonus_user_id) {
                            add_bonuses($con, $bonus_user_id, $order_id_val, $bonuses_earned);
                            
                            // Deduct bonuses if spent
                            if ($discount_amount > 0) {
                                spend_bonuses($con, $bonus_user_id, $order_id_val, $discount_amount);
                            }
                        }
                        
                        // Clear cart
                        $_SESSION['cart'] = [];
                        
                        $final_total = $cart_total - $discount_amount;
                        
                        echo "<div class='alert alert-success' style='margin-top: 20px;'>";
                            echo "<h4>✓ Отлично! Ваш заказ успешно создан.</h4>";
                            echo "<p><strong>Номер заказа:</strong> " . $order_id_val . "</p>";
                            echo "<p><strong>Время заказа:</strong> " . Date("Y-m-d H:i") . "</p>";
                            echo "<p><strong>Адрес доставки:</strong> " . htmlspecialchars($delivery_address) . "</p>";
                            echo "<p><strong>Контактный телефон:</strong> " . htmlspecialchars($client_phone_number) . "</p>";
                            
                            if ($registered_user_id) {
                                echo "<p style='color: #155724; background: #303030; padding: 10px; border-radius: 4px; margin-top: 10px;'>";
                                    echo "🎉 <strong>Вы зарегистрированы!</strong> Ваш аккаунт создан и вы будете получать бонусы за каждый заказ.";
                                echo "</p>";
                            }
                            
                            if ($bonuses_earned > 0) {
                                echo "<p style='color: #17a2b8; background: #313131; padding: 10px; border-radius: 4px; margin-top: 10px;'>";
                                    echo "💰 Вы получили <strong>" . number_format($bonuses_earned, 2, ',', ' ') . " бонусов</strong> за этот заказ!";
                                echo "</p>";
                            }
                            
                            if ($discount_amount > 0) {
                                echo "<p><strong>Скидка по бонусам:</strong> -" . number_format($discount_amount, 2, ',', ' ') . "₽</p>";
                                echo "<p><strong>Итого к оплате:</strong> " . number_format($final_total, 2, ',', ' ') . "₽</p>";
                            } else {
                                echo "<p><strong>Итого к оплате:</strong> " . number_format($final_total, 2, ',', ' ') . "₽</p>";
                            }

                            echo "<p style='margin-top:8px;'><strong>Способ оплаты:</strong> " . htmlspecialchars(ucfirst($payment_method)) . "</p>";
                            
                            echo "<p style='margin-top: 15px;'>";
                                if ($bonus_user_id) {
                                    echo "<a href='user_profile.php' class='btn btn-success'>Мой аккаунт</a> ";
                                }
                                echo "<a href='index.php' class='btn btn-primary'>Вернуться на главную</a>";
                            echo "</p>";
                        echo "</div>";

                        $con->commit();
                    }
                    catch(Exception $e)
                    {
                        $con->rollBack();
                        echo "<div class = 'alert alert-danger'>"; 
                            echo "Ошибка при создании заказа: " . $e->getMessage();
                        echo "</div>";
                    }
                }
            }

        ?>

        <!-- ORDER FOOD FORM -->

		<form method="post" id="order_food_form" action="order_food.php">
		
			<!-- CART REVIEW TAB -->

			<div class="select_menus_tab order_food_tab" id="cart_tab">

				<!-- ALERT MESSAGE -->

				<div class="alert alert-danger" role="alert" style="display: none;" id="empty_cart_alert">
					Ваша корзина пуста! <a href="index.php#menus" style="color: white; text-decoration: underline;">Выберите блюда из меню</a>
				</div>

                <div class="text_header">
                    <span>Ваша Корзина</span>
                </div>

                <div class="review_tab_content" id="cart_items_container">
                    <?php
                        if (!empty($_SESSION['cart'])) {
                            // Get all menu items in cart
                            $cart_items_display = [];
                            $cart_total = 0;
                            
                            foreach ($_SESSION['cart'] as $menu_id => $qty) {
                                $stmt = $con->prepare("SELECT menu_id, menu_name, menu_price, category_id FROM menus WHERE menu_id = ?");
                                $stmt->execute(array($menu_id));
                                $menu_data = $stmt->fetch();
                                
                                if ($menu_data) {
                                    // Get category name
                                    $stmt_cat = $con->prepare("SELECT category_name FROM menu_categories WHERE category_id = ?");
                                    $stmt_cat->execute(array($menu_data['category_id']));
                                    $cat_data = $stmt_cat->fetch();
                                    
                                    $category_name = $cat_data ? $cat_data['category_name'] : 'Без категории';
                                    $item_subtotal = $menu_data['menu_price'] * $qty;
                                    $cart_total += $item_subtotal;
                                    
                                    $cart_items_display[] = array(
                                        'category' => $category_name,
                                        'menu_id' => $menu_id,
                                        'name' => $menu_data['menu_name'],
                                        'price' => $menu_data['menu_price'],
                                        'qty' => $qty,
                                        'subtotal' => $item_subtotal
                                    );
                                }
                            }
                            
                            // Group by category
                            $grouped_items = [];
                            foreach ($cart_items_display as $item) {
                                if (!isset($grouped_items[$item['category']])) {
                                    $grouped_items[$item['category']] = [];
                                }
                                $grouped_items[$item['category']][] = $item;
                            }
                            
                            // Display items
                            foreach ($grouped_items as $category => $items) {
                                echo '<div class="review_category_name">' . htmlspecialchars($category) . '</div>';
                                foreach ($items as $item) {
                                    echo '<div class="review_item_row" id="cart_row_' . $item['menu_id'] . '">';
                                        echo '<div class="review_item_name">' . htmlspecialchars($item['name']) . '</div>';
                                        echo '<div class="review_item_qty">x ' . $item['qty'] . '</div>';
                                        echo '<div class="review_item_price">' . $item['subtotal'] . '₽</div>';
                                        echo '<div class="review_item_remove">';
                                            echo '<button type="button" onclick="removeCartItem(' . $item['menu_id'] . ')" title="Удалить">✕</button>';
                                        echo '</div>';
                                    echo '</div>';
                                }
                            }
                            
                            echo '<div class="review_total" id="cart_total_row">';
                                echo '<strong>Итого:</strong>';
                                echo '<span id="cart_total_price">' . $cart_total . '₽</span>';
                            echo '</div>';
                        } else {
                            echo '<div class="cart_empty_state">';
                                echo '<span class="cart_empty_icon">🛒</span>';
                                echo '<p>Корзина пуста</p>';
                                echo '<a href="index.php#menus" class="btn btn-primary">Выбрать блюда</a>';
                            echo '</div>';
                        }
                    ?>
                </div>

                <!-- Store cart items as hidden input for next step -->
                <input type="hidden" id="cart_json" name="cart_json" value="<?php echo htmlspecialchars(json_encode($_SESSION['cart'])); ?>">
            </div>

			<!-- EXTRAS SELECTION TAB -->

			<div class="order_food_tab" id="extras_tab">

                <div class="text_header">
                    <span>Дополнительные услуги</span>
                </div>

				<div style="padding: 8px 0 4px;">
					<p style="margin-bottom: 20px; color: #888; font-size: 14px;">Выберите желаемые дополнительные услуги:</p>

					<div class="extras_grid">

						<div class="extras_option">
							<div class="extras_icon">🧻</div>
							<div class="extras_label_text">
								<strong>Салфетки</strong>
								<span>шт</span>
							</div>
							<div class="extras_counter">
								<button type="button" onclick="changeExtra('extras_napkins', -1)">−</button>
								<input type="number" name="extras_napkins" id="extras_napkins" value="0" min="0" max="100">
								<button type="button" onclick="changeExtra('extras_napkins', 1)">+</button>
							</div>
						</div>

						<div class="extras_option">
							<div class="extras_icon">🥢</div>
							<div class="extras_label_text">
								<strong>Палочки</strong>
								<span>пара</span>
							</div>
							<div class="extras_counter">
								<button type="button" onclick="changeExtra('extras_chopsticks', -1)">−</button>
								<input type="number" name="extras_chopsticks" id="extras_chopsticks" value="0" min="0" max="100">
								<button type="button" onclick="changeExtra('extras_chopsticks', 1)">+</button>
							</div>
						</div>

						<div class="extras_option">
							<div class="extras_icon">🫙</div>
							<div class="extras_label_text">
								<strong>Соевый соус</strong>
								<span>гр</span>
							</div>
							<div class="extras_counter">
								<button type="button" onclick="changeExtra('extras_soy_sauce', -1)">−</button>
								<input type="number" name="extras_soy_sauce" id="extras_soy_sauce" value="0" min="0" max="100">
								<button type="button" onclick="changeExtra('extras_soy_sauce', 1)">+</button>
							</div>
						</div>

						<div class="extras_option">
							<div class="extras_icon">🫚</div>
							<div class="extras_label_text">
								<strong>Имбирь</strong>
								<span>гр</span>
							</div>
							<div class="extras_counter">
								<button type="button" onclick="changeExtra('extras_ginger', -1)">−</button>
								<input type="number" name="extras_ginger" id="extras_ginger" value="0" min="0" max="100">
								<button type="button" onclick="changeExtra('extras_ginger', 1)">+</button>
							</div>
						</div>

						<div class="extras_option">
							<div class="extras_icon">🌿</div>
							<div class="extras_label_text">
								<strong>Васаби</strong>
								<span>гр</span>
							</div>
							<div class="extras_counter">
								<button type="button" onclick="changeExtra('extras_wasabi', -1)">−</button>
								<input type="number" name="extras_wasabi" id="extras_wasabi" value="0" min="0" max="100">
								<button type="button" onclick="changeExtra('extras_wasabi', 1)">+</button>
							</div>
						</div>

					</div>
				</div>
			</div>

			<!-- ORDER REVIEW TAB -->

            <?php if (!$is_logged_in): ?>
            <div class="order_food_tab" id="review_tab">

                <div class="text_header">
                    <span>Бонусы и Регистрация</span>
                </div>

                <div style="background: white; padding: 20px; border-radius: 4px;">
                    <div style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                        <h4 style="color: #856404; margin-top: 0;">🎁 Регистрация и Бонусы</h4>
                        <p style="margin: 5px 0;">Зарегистрируйтесь сейчас и получайте бонусы за каждый заказ!</p>
                        <p style="margin: 5px 0; color: #666;">✓ 5% от суммы каждого заказа в виде бонусов<br/>
                        ✓ Используйте бонусы для скидок<br/>
                        ✓ Бонусы не имеют срока действия</p>

                        <div style="margin-top: 15px; padding: 15px; background: white; border-radius: 4px;">
                            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                <input type="checkbox" id="register_account" name="register_account" value="1" onchange="toggleRegistrationFields()">
                                <label for="register_account" style="margin: 0 0 0 10px; font-weight: 600;">Зарегистрироваться сейчас</label>
                            </div>

                            <div id="registration_fields" style="display: none;">
                                <div style="margin-bottom: 10px;">
                                    <label for="register_username" style="display: block; font-weight: 600; margin-bottom: 5px;">Имя пользователя:</label>
                                    <input type="text" id="register_username" name="register_username" placeholder="Минимум 3 символа" style="width: 100%; padding: 10px; border: 2px solid #eee; border-radius: 4px; box-sizing: border-box;">
                                </div>
                                <div>
                                    <label for="register_password" style="display: block; font-weight: 600; margin-bottom: 5px;">Пароль:</label>
                                    <input type="password" id="register_password" name="register_password" placeholder="Минимум 6 символов" style="width: 100%; padding: 10px; border: 2px solid #eee; border-radius: 4px; box-sizing: border-box;">
                                </div>
                                <small style="color: #666; display: block; margin-top: 10px;">Ваша почта будет использована как вторичное имя для входа</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

			<!-- CONFIRMATION TAB -->

			<div class="order_food_tab" id="confirm_tab">

                <div class="text_header"><span>Подтверждение заказа</span></div>

                <?php if ($is_logged_in): ?>
                    <div style="background: #f8f9fa; padding: 12px; border-radius: 4px; margin-bottom: 12px;">
                        <h5 style="margin:0 0 8px 0;">Использование бонусов</h5>
                        <p style="margin:0 0 8px 0; color:#666;">Доступно: <?php echo number_format($user_bonuses['available_bonuses'], 2, ',', ' '); ?> бонусов. Введите сумму для списания:</p>
                        <input type="number" id="use_bonuses" name="use_bonuses" min="0" max="<?php echo $user_bonuses['available_bonuses']; ?>" step="0.01" value="0" style="width:160px; padding:8px; border:1px solid #e6e6e6; border-radius:4px;">
                    </div>
                <?php endif; ?>

                <div class="review_tab_content" id="order_review_content">
                    <!-- Will be populated by JavaScript -->
                </div>
			</div>

			<!-- CLIENT DETAILS -->

			<div class="client_details_tab order_food_tab" id="clients_tab">

                <div class="text_header"><span>Данные Клиента</span></div>

				<div>
					<div class="form-group colum-row row">
						<div class="col-sm-12">
							<input type="text" name="client_full_name" id="client_full_name" oninput="document.getElementById('required_fname').style.display = 'none'" onkeyup="this.value=this.value.replace(/[^\sa-zA-Zа-яА-ЯёЁ]/g,'');" class="form-control" placeholder="Полное имя">
							<div class="invalid-feedback" id="required_fname">
								Неверное имя!
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-6">
							<input type="email" name="client_email" id="client_email" oninput="document.getElementById('required_email').style.display = 'none'" class="form-control" placeholder="Email">
							<div class="invalid-feedback" id="required_email">
								Неверный Email!
							</div>
						</div>
						<div class="col-sm-6">
							<input type="text"  name="client_phone_number" id="client_phone_number" oninput="document.getElementById('required_phone').style.display = 'none'" class="form-control" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" placeholder="Номер телефона">
							<div class="invalid-feedback" id="required_phone">
								Неверный номер телефона!
							</div>
						</div>
					</div>
					<div class="form-group colum-row row">
						<div class="col-sm-12">
							<input type="text" name="client_delivery_address" id="client_delivery_address" oninput="document.getElementById('required_delivery_address').style.display = 'none'" class="form-control" placeholder="Адрес доставки">
							<div class="invalid-feedback" id="required_delivery_address">
								Укажите адрес доставки!
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- NEXT AND PREVIOUS BUTTONS -->

			<div style="overflow:auto;padding: 30px;">
				<div style="float:right;">
					<input type="hidden" name="submit_order_food_form">
					<button type="button" class="next_prev_buttons" style="background-color: #bbbbbb;" id="prevBtn" onclick="nextPrev(-1)">Назад</button>
					<button type="button" id="nextBtn" class="next_prev_buttons" onclick="nextPrev(1)">Далее</button>
				</div>
			</div>

            <?php
                $stepCount = 4 + (!$is_logged_in ? 1 : 0);
                echo '<div style="text-align:center;margin-top:40px;">';
                for ($i = 0; $i < $stepCount; $i++) {
                    echo '<span class="step"></span>';
                }
                echo '</div>';
            ?>

		</form>
	</section>

    <!-- FOOTER BOTTOM  -->

    <?php include "Includes/templates/footer.php"; ?>

    <!-- JS SCRIPTS -->

    <script type="text/javascript">

        /* TOGGLE MENU SELECT BUTTON */

        $('.menu_label').click(function() 
        {
            $(this).button('toggle');
            
        });

    </script>

    <!-- JS SCRIPT FOR NEXT AND BACK TABS -->

    <script type="text/javascript">
        
        var currentTab = 0;

        // Remove item from cart
        function removeCartItem(menuId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'action=update&menu_id=' + menuId + '&qty=0'
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    // Remove row from DOM
                    var row = document.getElementById('cart_row_' + menuId);
                    if (row) row.remove();

                    // Update hidden cart_json
                    var cartJson = document.getElementById('cart_json');
                    var cart = {};
                    try { cart = JSON.parse(cartJson.value); } catch(e) {}
                    delete cart[menuId];
                    cartJson.value = JSON.stringify(cart);

                    // Update cart badge
                    var badge = document.getElementById('cart-count');
                    if (badge) {
                        badge.textContent = data.cart_total;
                        badge.style.display = data.cart_total > 0 ? 'inline' : 'none';
                    }

                    // Recalculate total
                    var rows = document.querySelectorAll('.review_item_row');
                    if (rows.length === 0) {
                        // Cart is now empty
                        document.getElementById('cart_items_container').innerHTML =
                            '<div class="cart_empty_state">' +
                            '<span class="cart_empty_icon">🛒</span>' +
                            '<p>Корзина пуста</p>' +
                            '<a href="index.php#menus" class="btn btn-primary">Выбрать блюда</a>' +
                            '</div>';
                        var nextBtn = document.getElementById('nextBtn');
                        if (nextBtn) nextBtn.disabled = true;
                        var alert = document.getElementById('empty_cart_alert');
                        if (alert) alert.style.display = 'block';
                    }
                }
            });
        }

        // Check if cart is empty on page load
        function checkCartStatus() {
            var cartJson = document.getElementById('cart_json').value;
            try {
                var cart = JSON.parse(cartJson);
                if (Object.keys(cart).length === 0) {
                    document.getElementById('empty_cart_alert').style.display = 'block';
                    document.getElementById('nextBtn').disabled = true;
                    return false;
                }
            } catch(e) {
                document.getElementById('empty_cart_alert').style.display = 'block';
                document.getElementById('nextBtn').disabled = true;
                return false;
            }
            return true;
        }
        
        window.addEventListener('load', function() {
            if (checkCartStatus()) {
                showTab(currentTab);
            }
        });

        function showTab(n) 
        {
            var x = document.getElementsByClassName("order_food_tab");
            x[n].style.display = "block";
            
            if (n == 0) 
            {
                document.getElementById("prevBtn").style.display = "none";
            } 
            else 
            {
                document.getElementById("prevBtn").style.display = "inline";
            }
            
            if (n == (x.length - 1)) 
            {
                document.getElementById("nextBtn").innerHTML = "Оформить заказ";
            } 
            else 
            {
                document.getElementById("nextBtn").innerHTML = "Далее";
            }

            fixStepIndicator(n);
            
            // Build review when current tab is the confirmation tab
            if (x[n] && x[n].id === 'confirm_tab') {
                buildReviewTab();
            }
        }

        // Build review tab
        function buildReviewTab() {
            var cartJson = document.getElementById('cart_json').value;
            var extras_napkins = document.getElementById('extras_napkins').value || 0;
            var extras_chopsticks = document.getElementById('extras_chopsticks').value || 0;
            var extras_soy_sauce = document.getElementById('extras_soy_sauce').value || 0;
            var extras_ginger = document.getElementById('extras_ginger').value || 0;
            var extras_wasabi = document.getElementById('extras_wasabi').value || 0;
            
            try {
                var cart = JSON.parse(cartJson);
            } catch(e) {
                return;
            }

            // Fetch menu items from cart
            fetch('get_cart_items.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ cart: cart })
            })
            .then(response => response.json())
            .then(data => {
                var reviewContent = '';
                var cartTotal = 0;
                
                // Group items by category
                var grouped = {};
                data.items.forEach(function(item) {
                    if (!grouped[item.category]) {
                        grouped[item.category] = [];
                    }
                    grouped[item.category].push(item);
                    cartTotal += item.subtotal;
                });
                
                // Build HTML
                for (var category in grouped) {
                    reviewContent += '<div class="review_category_name">' + category + '</div>';
                    grouped[category].forEach(function(item) {
                        reviewContent += '<div class="review_item_row">';
                        reviewContent += '<div class="review_item_name">' + item.name + '</div>';
                        reviewContent += '<div class="review_item_qty">x ' + item.qty + '</div>';
                        reviewContent += '<div class="review_item_price">' + item.subtotal + '₽</div>';
                        reviewContent += '</div>';
                    });
                }
                
                // Add extras section if any selected
                var hasExtras = extras_napkins > 0 || extras_chopsticks > 0 || extras_soy_sauce > 0 || extras_ginger > 0 || extras_wasabi > 0;
                if (hasExtras) {
                    reviewContent += '<div class="review_category_name" style="margin-top: 20px;">Дополнительные услуги</div>';
                    if (extras_napkins > 0) {
                        reviewContent += '<div class="review_item_row"><div class="review_item_name">Салфетки</div><div class="review_item_qty">x ' + extras_napkins + '</div></div>';
                    }
                    if (extras_chopsticks > 0) {
                        reviewContent += '<div class="review_item_row"><div class="review_item_name">Палочки</div><div class="review_item_qty">x ' + extras_chopsticks + '</div></div>';
                    }
                    if (extras_soy_sauce > 0) {
                        reviewContent += '<div class="review_item_row"><div class="review_item_name">Соевый соус</div><div class="review_item_qty">' + extras_soy_sauce + ' гр</div></div>';
                    }
                    if (extras_ginger > 0) {
                        reviewContent += '<div class="review_item_row"><div class="review_item_name">Имбирь</div><div class="review_item_qty">' + extras_ginger + ' гр</div></div>';
                    }
                    if (extras_wasabi > 0) {
                        reviewContent += '<div class="review_item_row"><div class="review_item_name">Васаби</div><div class="review_item_qty">' + extras_wasabi + ' гр</div></div>';
                    }
                }
                
                // Read bonuses input (if present) and calculate final total
                var bonusesInput = document.getElementById('use_bonuses');
                var bonusesToUse = 0;
                if (bonusesInput) {
                    bonusesToUse = parseFloat(bonusesInput.value) || 0;
                    var maxAvail = parseFloat(bonusesInput.max) || 0;
                    if (bonusesToUse > maxAvail) bonusesToUse = maxAvail;
                }

                var finalTotal = Math.max(0, cartTotal - bonusesToUse);

                if (bonusesToUse > 0) {
                    reviewContent += '<div class="review_item_row" style="border:none; padding-top:10px;">'
                                  + '<div><strong>Скидка по бонусам</strong></div>'
                                  + '<div style="width:50px; text-align:right;">- ' + bonusesToUse + '₽</div>'
                                  + '</div>';
                }

                reviewContent += '<div class="review_total"><strong>К оплате:</strong><span>' + finalTotal + '₽</span></div>';

                // Payment method selection
                reviewContent += '<div style="margin-top:18px;">'
                              + '<label style="font-weight:600; display:block; margin-bottom:8px;">Способ оплаты:</label>'
                              + '<label style="display:block; margin-bottom:6px;"><input type="radio" name="payment_method" value="card" checked> Оплата картой (терминал при курьере)</label>'
                              + '<label style="display:block; margin-bottom:6px;"><input type="radio" name="payment_method" value="cash"> Наличными</label>'
                              + '</div>';
                document.getElementById('order_review_content').innerHTML = reviewContent;
            })
            .catch(error => console.error('Error:', error));
        }

        // Next Prev Function

        function nextPrev(n) 
        {
            var x = document.getElementsByClassName("order_food_tab");
            
            if (n == 1 && !validateForm()) return false;
            // Hide the current tab:
            x[currentTab].style.display = "none";
            // Increase or decrease the current tab by 1:
            currentTab = currentTab + n;
            // if you have reached the end of the form...
            if (currentTab >= x.length) 
            {
                // ... the form gets submitted:
                document.getElementById("order_food_form").submit();
                return false;
            }

            // Otherwise, display the correct tab:
            showTab(currentTab);
        }

        // Validate Form Function

        function validateForm()
        {
            var x, id_tab, valid = true;
            x = document.getElementsByClassName("order_food_tab");
            id_tab = x[currentTab].id;

            if(id_tab == "cart_tab")
            {
                // Cart validation - check if cart has items
                var cartJson = document.getElementById('cart_json').value;
                try {
                    var cart = JSON.parse(cartJson);
                    if (Object.keys(cart).length === 0) {
                        alert('Ваша корзина пуста! Пожалуйста, добавьте товары перед оформлением.');
                        valid = false;
                    }
                } catch(e) {
                    valid = false;
                }
            }
            else if(id_tab == "review_tab") 
            {
                // Validate registration fields if checked
                var registerCheckbox = document.getElementById('register_account');
                if (registerCheckbox && registerCheckbox.checked) {
                    var username = document.getElementById('register_username').value.trim();
                    var password = document.getElementById('register_password').value.trim();
                    
                    if (username.length < 3) {
                        alert('Имя пользователя должно быть не менее 3 символов');
                        valid = false;
                    }
                    if (password.length < 6) {
                        alert('Пароль должен быть не менее 6 символов');
                        valid = false;
                    }
                }
            }
            else if(id_tab == "clients_tab")
            {
                y = x[currentTab].getElementsByTagName("input");
                z = x[currentTab].getElementsByClassName("invalid-feedback");

                for (var i = 0; i < y.length; i++) 
                {
                    if(y[i].value == "")
                    {
                        z[i].style.display = "block";
                        valid = false;
                    }
                    if(y[i].type == "email" && !ValidateEmail(y[i].value))
                    {
                        z[i].style.display = "block";
                        valid = false;
                    }
                }
            }

            if (valid) 
            {
                document.getElementsByClassName("step")[currentTab].className += " finish";
            }

            return valid;
        }

        function fixStepIndicator(n) 
        {
            // This function removes the "active" class of all steps...
            var i, x = document.getElementsByClassName("step");
            
            for (i = 0; i < x.length; i++) 
            {
                x[i].className = x[i].className.replace(" active", "");
            }
            
            //... and adds the "active" class on the current step:
            x[n].className += " active";
        }
        
        // Toggle registration fields visibility
        function toggleRegistrationFields() {
            var checkbox = document.getElementById('register_account');
            var fieldsDiv = document.getElementById('registration_fields');
            
            if (checkbox && fieldsDiv) {
                if (checkbox.checked) {
                    fieldsDiv.style.display = 'block';
                } else {
                    fieldsDiv.style.display = 'none';
                    document.getElementById('register_username').value = '';
                    document.getElementById('register_password').value = '';
                }
            }
        }
        function changeExtra(id, delta) {
            var input = document.getElementById(id);
            var val = parseInt(input.value) || 0;
            val = Math.max(0, Math.min(100, val + delta));
            input.value = val;
        }
    </script>

    <style type="text/css">
        .review_tab_content
        {
            padding: 20px 0;
        }
        .review_category_name
        {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin: 24px 0 8px 0;
            color: #9e8a78;
        }
        .review_item_row
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 14px;
            margin-bottom: 6px;
            border-radius: 8px;
            background: #f9f7f5;
            border: 1px solid #ede8e3;
            transition: background 0.15s;
        }
        .review_item_row:hover { background: #f1ece6; }
        .review_item_name
        {
            flex: 1;
            font-size: 15px;
            font-weight: 500;
            color: #222;
        }
        .review_item_qty
        {
            width: 50px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .review_item_price
        {
            width: 90px;
            text-align: right;
            font-weight: 700;
            font-size: 15px;
            color: #333;
        }
        .review_item_remove
        {
            width: 32px;
            text-align: center;
            margin-left: 10px;
        }
        .review_item_remove button
        {
            background: none;
            border: none;
            cursor: pointer;
            color: #ccc;
            font-size: 18px;
            line-height: 1;
            padding: 2px 6px;
            border-radius: 4px;
            transition: color 0.15s, background 0.15s;
        }
        .review_item_remove button:hover
        {
            color: #e74c3c;
            background: #ffeaea;
        }
        .review_total
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 16px 14px;
            border-radius: 8px;
            background: #f0ebe4;
            font-size: 20px;
            font-weight: bold;
            color: #222;
        }
        .cart_empty_state
        {
            text-align: center;
            padding: 50px 20px;
            color: #aaa;
        }
        .cart_empty_state .cart_empty_icon
        {
            font-size: 56px;
            margin-bottom: 16px;
            display: block;
        }
        .cart_empty_state p
        {
            font-size: 18px;
            margin-bottom: 20px;
            color: #888;
        }
        .cart_empty_state .btn-primary
        {
            background: #9e8a78;
            border-color: #9e8a78;
            padding: 10px 28px;
            font-size: 15px;
            border-radius: 6px;
        }
        
        /* EXTRAS SECTION STYLES — redesigned */
        .extras_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 10px;
        }

        .extras_option {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1.5px solid #e8e0d8;
            border-radius: 12px;
            padding: 20px 16px 16px;
            box-shadow: 0 2px 8px rgba(158,138,120,0.08);
            transition: box-shadow 0.2s, border-color 0.2s;
            gap: 14px;
        }

        .extras_option:hover {
            border-color: #9e8a78;
            box-shadow: 0 4px 16px rgba(158,138,120,0.18);
        }

        .extras_icon {
            font-size: 32px;
            line-height: 1;
        }

        .extras_label_text {
            text-align: center;
        }

        .extras_label_text strong {
            display: block;
            font-size: 14px;
            color: #3a3a3a;
            margin-bottom: 2px;
        }

        .extras_label_text span {
            font-size: 12px;
            color: #aaa;
        }

        .extras_counter {
            display: flex;
            align-items: center;
            gap: 0;
            border: 1.5px solid #e0d8d0;
            border-radius: 8px;
            overflow: hidden;
        }

        .extras_counter button {
            width: 32px;
            height: 36px;
            background: #f5f0ec;
            border: none;
            font-size: 18px;
            color: #9e8a78;
            cursor: pointer;
            transition: background 0.15s;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        .extras_counter button:hover {
            background: #9e8a78;
            color: #fff;
        }

        .extras_counter input[type="number"] {
            width: 48px;
            height: 36px;
            border: none;
            border-left: 1.5px solid #e0d8d0;
            border-right: 1.5px solid #e0d8d0;
            text-align: center;
            font-size: 15px;
            font-weight: 600;
            color: #3a3a3a;
            outline: none;
            -moz-appearance: textfield;
        }

        .extras_counter input[type="number"]::-webkit-inner-spin-button,
        .extras_counter input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .form-check-label {
            display: flex;
            align-items: center;
            margin-bottom: 0;
            cursor: pointer;
        }
    </style>