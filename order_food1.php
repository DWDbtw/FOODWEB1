<?php
    //Set page title
    $pageTitle = 'Заказ Еды';
    
    session_start();

    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
    // order page styles applied inline below; no page-level class injected
    
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
            margin: 28px auto;
            padding: 28px;
            /* make the order area a card with internal scroll */
            max-height: calc(100vh - 240px);
            box-sizing: border-box;
            overflow: auto; /* internal scroll if needed */
            background: #ffffff;
            border-radius: 8px;
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

        /* Keep the navigation controls visible at bottom of the card */
        .order_controls_wrap {
            position: sticky;
            bottom: 0;
            background: linear-gradient(to top, rgba(255,255,255,0.95), rgba(255,255,255,0));
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

        /* ensure order card has space below for footer */
        .order_food_section { margin-bottom: 40px; }

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
                        
                        // Calculate bonuses earned for this order
                        $bonuses_earned = calculate_order_bonuses($con, $cart_total);
                        
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
                            }
                            
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
                    <span>
                        1. Ваша Корзина
                    </span>
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
                                    echo '<div class="review_item_row">';
                                        echo '<div class="review_item_name">' . htmlspecialchars($item['name']) . '</div>';
                                        echo '<div class="review_item_qty">x ' . $item['qty'] . '</div>';
                                        echo '<div class="review_item_price">' . $item['subtotal'] . '₽</div>';
                                    echo '</div>';
                                }
                            }
                            
                            echo '<div class="review_total">';
                                echo '<strong>Итого:</strong>';
                                echo '<span>' . $cart_total . '₽</span>';
                            echo '</div>';
                        } else {
                            echo '<div style="text-align: center; padding: 30px; color: #999;">';
                                echo '<p>Ваша корзина пуста</p>';
                                echo '<p><a href="index.php#menus" class="btn btn-primary">Выбрать блюда</a></p>';
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
					<span>
						2. Дополнительные услуги
					</span>
				</div>

				<div style="background: white; padding: 20px; border-radius: 4px;">
					<p style="margin-bottom: 20px; color: #666;">Выберите желаемые дополнительные услуги:</p>
					
					<div class="extras_option">
						<div class="form-check">
							<input class="form-check-input" type="number" name="extras_napkins" id="extras_napkins" value="0" min="0" max="100">
							<label class="form-check-label" for="extras_napkins">
								<strong>Салфетки</strong> <span style="color: #999;">(шт)</span>
							</label>
						</div>
					</div>

					<div class="extras_option">
						<div class="form-check">
							<input class="form-check-input" type="number" name="extras_chopsticks" id="extras_chopsticks" value="0" min="0" max="100">
							<label class="form-check-label" for="extras_chopsticks">
								<strong>Палочки</strong> <span style="color: #999;">(пара)</span>
							</label>
						</div>
					</div>

					<div class="extras_option">
						<div class="form-check">
							<input class="form-check-input" type="number" name="extras_soy_sauce" id="extras_soy_sauce" value="0" min="0" max="100">
							<label class="form-check-label" for="extras_soy_sauce">
								<strong>Соевый соус</strong> <span style="color: #999;">(гр)</span>
							</label>
						</div>
					</div>

					<div class="extras_option">
						<div class="form-check">
							<input class="form-check-input" type="number" name="extras_ginger" id="extras_ginger" value="0" min="0" max="100">
							<label class="form-check-label" for="extras_ginger">
								<strong>Имбирь</strong> <span style="color: #999;">(гр)</span>
							</label>
						</div>
					</div>

					<div class="extras_option">
						<div class="form-check">
							<input class="form-check-input" type="number" name="extras_wasabi" id="extras_wasabi" value="0" min="0" max="100">
							<label class="form-check-label" for="extras_wasabi">
								<strong>Васаби</strong> <span style="color: #999;">(гр)</span>
							</label>
						</div>
					</div>
				</div>
			</div>

			<!-- ORDER REVIEW TAB -->

			<div class="order_food_tab" id="review_tab">

				<div class="text_header">
					<span>
						3. Бонусы и Регистрация
					</span>
				</div>

				<div style="background: white; padding: 20px; border-radius: 4px;">
					
					<!-- BONUSES SECTION -->
					<?php if ($is_logged_in): ?>
						<div style="background: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
							<h4 style="color: #17a2b8; margin-top: 0;">💰 Ваши бонусы</h4>
							<p style="margin: 5px 0;"><strong>Доступно бонусов:</strong> <span id="available_bonus_display"><?php echo number_format($user_bonuses['available_bonuses'], 2, ',', ' '); ?></span></p>
							<p style="margin: 5px 0; color: #666;">Вы можете использовать бонусы для скидки на этот заказ (1 бонус = 1 рубль скидки)</p>
							
							<div style="margin-top: 15px;">
								<label for="use_bonuses" style="font-weight: 600;">Использовать бонусов:</label>
								<input type="number" id="use_bonuses" name="use_bonuses" min="0" max="<?php echo $user_bonuses['available_bonuses']; ?>" step="0.01" value="0" style="width: 100%; padding: 10px; border: 2px solid #eee; border-radius: 4px;">
								<small style="color: #666;">Максимум: <?php echo number_format($user_bonuses['available_bonuses'], 2, ',', ' '); ?> бонусов</small>
							</div>
						</div>
					<?php else: ?>
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
					<?php endif; ?>
				</div>
			</div>

			<!-- CONFIRMATION TAB -->

			<div class="order_food_tab" id="confirm_tab">

				<div class="text_header">
					<span>
						4. Подтверждение заказа
					</span>
				</div>

				<div class="review_tab_content" id="order_review_content">
					<!-- Will be populated by JavaScript -->
				</div>
			</div>

			<!-- CLIENT DETAILS -->

			<div class="client_details_tab order_food_tab" id="clients_tab">

				<div class="text_header">
					<span>
						5. Данные Клиента
					</span>
				</div>

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

			<div style="text-align:center;margin-top:40px;">
				<span class="step"></span>
				<span class="step"></span>
				<span class="step"></span>
				<span class="step"></span>
			</div>

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
            
            if (n == 2) {
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
                
                reviewContent += '<div class="review_total"><strong>Итого:</strong><span>' + cartTotal + '₽</span></div>';
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
    </script>

    <!-- STYLES FOR REVIEW TAB -->

    <style type="text/css">
        .review_tab_content
        {
            padding: 20px 0;
        }
        .review_category_name
        {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            color: #000000;
        }
        .review_item_row
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .review_item_name
        {
            flex: 1;
        }
        .review_item_qty
        {
            width: 50px;
            text-align: center;
        }
        .review_item_price
        {
            width: 80px;
            text-align: right;
            font-weight: bold;
        }
        .review_total
        {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #AFC4D5;
            font-size: 20px;
            font-weight: bold;
        }
        
        /* EXTRAS SECTION STYLES */
        .extras_option
        {
            margin-bottom: 20px;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
            border-left: 3px solid #9e8a78;
        }
        
        .form-check-input
        {
            width: 70px !important;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 4px;
            margin-right: 15px;
            font-size: 16px;
        }
        
        .form-check-label
        {
            display: flex;
            align-items: center;
            margin-bottom: 0;
            cursor: pointer;
        }
    </style>