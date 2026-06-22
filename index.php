<?php
    session_start();
    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";


    //Getting website settings

    $stmt_web_settings = $con->prepare("SELECT * FROM website_settings");
    $stmt_web_settings->execute();
    $web_settings = $stmt_web_settings->fetchAll();

    $restaurant_name = "";
    $restaurant_email = "";
    $restaurant_address = "";
    $restaurant_phonenumber = "";

    foreach ($web_settings as $option)
    {
        if($option['option_name'] == 'restaurant_name')
        {
            $restaurant_name = $option['option_value'];
        }

        elseif($option['option_name'] == 'restaurant_email')
        {
            $restaurant_email = $option['option_value'];
        }

        elseif($option['option_name'] == 'restaurant_phonenumber')
        {
            $restaurant_phonenumber = $option['option_value'];
        }
        elseif($option['option_name'] == 'restaurant_address')
        {
            $restaurant_address = $option['option_value'];
        }
    }

?>

<?php
// Determine if current user is manager or admin for showing add-tile
$is_manager_or_admin = false;
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
	try {
		$stmtRole = $con->prepare("SELECT role FROM users WHERE user_id = ? LIMIT 1");
		$stmtRole->execute([ (int)$_SESSION['user_id'] ]);
		$r = $stmtRole->fetch(PDO::FETCH_ASSOC);
		if ($r && in_array($r['role'], ['admin','manager'])) $is_manager_or_admin = true;
	} catch (Exception $e) {
		$is_manager_or_admin = false;
	}
}
?>

	<!-- HOME SECTION -->

	<section class="home-section" id="home">
		<div class="container">
			<div class="row" style="flex-wrap: nowrap;">
				<div class="col-md-6 home-left-section">
					<div style="padding: 100px 0px; color: white;">
<h1>
							VINCENT SUSHI.
						</h1>
						<h2>
							ДЕЛАЕМ ЛЮДЕЙ СЧАСТЛИВЫМИ
						</h2>
						<hr>
						<p>
							Японская кухня с свежай рыбой и овощами
						</p>
						<div style="display: flex;">
							<a href="table-reservation.php" class="bttn_style_1" style="margin-right: 10px; display: flex;justify-content: center;align-items: center;">
								ЗАБРОНИРОВАТЬ СТОЛИК
								<i class="fas fa-angle-right"></i>
							</a>
							<a href="#menus" class="bttn_style_2" style="display: flex;justify-content: center;align-items: center;">
								ПОСМОТРЕТЬ МЕНЮ
								<i class="fas fa-angle-right"></i>
							</a>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</section>

	<!-- OUR QUALITIES SECTION -->

	<section class="our_qualities" style="padding:100px 0px;">
		<div class="container">
			<div class="row">
				<div class="col-md-4">
					<div class="our_qualities_column">
	                    <img src="Design/images/quality_food_img.png" >
	                    <div class="caption">
	                        <h3>
	                            КАЧЕСТВЕННАЯ ЕДА
	                        </h3>
<p>
                    		Свежие ингредиенты и высокое качество
                    	</p>
	                    </div>
	                </div>
				</div>
				<div class="col-md-4">
					<div class="our_qualities_column">
	                    <img src="Design/images/fast_delivery_img.png" >
	                    <div class="caption">
	                        <h3>
	                            БЫСТРАЯ ДОСТАВКА
	                        </h3>
<p>
                    		Свежие ингредиенты и высокое качество
                    	</p>
	                    </div>
	                </div>
				</div>
				<div class="col-md-4">
					<div class="our_qualities_column">
	                    <img src="Design/images/original_taste_img.png" >
	                    <div class="caption">
	                        <h3>
	                            ОРИГИНАЛЬНЫЙ ВКУС
	                        </h3>
<p>
                    		Свежие ингредиенты и высокое качество
                    	</p>
	                    </div>
	                </div>
				</div>

			</div>
		</div>
	</section>

	<!-- OUR MENUS SECTION -->

	<section class="our_menus" id="menus">
		<div class="container">
			<h2 style="text-align: center;margin-bottom: 30px">МЕНЮ СУШИ</h2>
			<div class="menus_tabs">
				<div class="menus_tabs_picker">
					<ul style="text-align: center;margin-bottom: 70px">
						<?php

	                        $stmt = $con->prepare("Select * from menu_categories");
	                        $stmt->execute();
	                        $rows = $stmt->fetchAll();
	                        $count = $stmt->rowCount();

	                        $x = 0;

foreach($rows as $row)
	{
		$catName = strtolower($row['category_name']);
		if(in_array($catName, ['salads', 'салаты', 'burgers', 'бургеры', 'pizzas', 'пиццы', 'pizza', 'пицца', 'traditional food', 'traditional', 'традиционные блюда', 'традиционные'])) continue;
		if($x == 0)
	                        	{
	                        		echo "<li class = 'menu_category_name tab_category_links active_category' onclick=showCategoryMenus(event,'".str_replace(' ', '', $row['category_name'])."')>";
	                        			echo $row['category_name'];
	                        		echo "</li>";

	                        	}
	                        	else
	                        	{
	                        		echo "<li class = 'menu_category_name tab_category_links' onclick=showCategoryMenus(event,'".str_replace(' ', '', $row['category_name'])."')>";
	                        			echo $row['category_name'];
	                        		echo "</li>";
	                        	}

	                        	$x++;
	                     		
	                        }
						?>
					</ul>
				</div>

				<div class="menus_tab">
					<?php
                
                        $stmt = $con->prepare("Select * from menu_categories");
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        $count = $stmt->rowCount();

$i = 0;

                         foreach($rows as $row) 
                         {
$catName = strtolower($row['category_name']);
                         	if(in_array($catName, ['salads', 'салаты', 'burgers', 'бургеры', 'pizzas', 'пиццы', 'pizza', 'пицца', 'traditional food', 'traditional', 'традиционные блюда', 'традиционные'])) continue;
                         	if($i == 0)
                            {

                                echo '<div class="menu_item  tab_category_content" id="'.str_replace(' ', '', $row['category_name']).'" style=display:block>';

                                    $stmt_menus = $con->prepare("Select * from menus where category_id = ?");
                                    $stmt_menus->execute(array($row['category_id']));
                                    $rows_menus = $stmt_menus->fetchAll();

									if($stmt_menus->rowCount() == 0)
									{
										echo "<div style='margin:auto'>В этой категории нет доступных меню!</div>";
									}

									echo "<div class='row'>";
									foreach($rows_menus as $menu)
									{
										?>

<div class="col-md-4 col-lg-3 menu-column">
	<?php $source = "admin/Uploads/images/".$menu['menu_image']; ?>
	<div class="thumbnail menu-item" data-menu-id="<?php echo $menu['menu_id']; ?>" data-menu-name="<?php echo htmlspecialchars($menu['menu_name'], ENT_QUOTES); ?>" data-menu-price="<?php echo $menu['menu_price']; ?>" data-menu-desc="<?php echo htmlspecialchars($menu['menu_description'], ENT_QUOTES); ?>" data-menu-img="<?php echo $source; ?>" data-menu-weight="<?php echo isset($menu['menu_weight']) ? htmlspecialchars($menu['menu_weight'], ENT_QUOTES) : ''; ?>" data-category-id="<?php echo $menu['category_id']; ?>">

		<div class="menu-image product-photo" title="Подробнее о товаре">
			<div class="image-preview">
				<div style="background-image: url('<?php echo $source; ?>');"></div>
			</div>
			<?php if (isset($menu['menu_weight']) && $menu['menu_weight'] !== '') { ?>
				<span class="menu-weight-badge"><?php echo htmlspecialchars($menu['menu_weight'], ENT_QUOTES); ?> г</span>
			<?php } ?>
		</div>

		<div class="caption">
			<h5>
				<span class="menu-name"><?php echo $menu['menu_name'];?></span>
				<?php if ($is_manager_or_admin) { ?>
					<button type="button" class="quick-edit" title="Редактировать" data-menu-id="<?php echo $menu['menu_id']; ?>" data-menu-name="<?php echo htmlspecialchars($menu['menu_name'], ENT_QUOTES); ?>" data-menu-price="<?php echo $menu['menu_price']; ?>" data-menu-desc="<?php echo htmlspecialchars($menu['menu_description'], ENT_QUOTES); ?>" data-menu-img="<?php echo $source; ?>" data-category-id="<?php echo $menu['category_id']; ?>"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></button>
				<?php } ?>
			</h5>
			<p class="menu-desc-text">
				<?php echo $menu['menu_description']; ?>
			</p>
			<div class="card-bottom-row">
				<span class="menu_price"><?php echo $menu['menu_price']."₽"; ?></span>
				<button type="button" class="btn-add-cart">В корзину</button>
			</div>
		</div>
	</div>
</div>

										<?php
									}

									// Add-tile for manager/admin: show a circular plus tile to add a new menu item
									if ($is_manager_or_admin) {
										?>
										<div class="col-md-4 col-lg-3 menu-column">
											<div class="thumbnail add-tile" style="cursor:pointer" data-category-id="<?php echo $row['category_id']; ?>">
												<div class="menu-image">
													<div class="image-preview">
														<div class="add-tile-inner">
															<span class="add-tile-plus">+</span>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php
									}

								echo "</div>";
                                echo '</div>';

                            }

                            else
                            {

                                echo '<div class="menus_categories  tab_category_content" id="'.str_replace(' ', '', $row['category_name']).'">';

                                    $stmt_menus = $con->prepare("Select * from menus where category_id = ?");
                                    $stmt_menus->execute(array($row['category_id']));
                                    $rows_menus = $stmt_menus->fetchAll();

                                    if($stmt_menus->rowCount() == 0)
                                    {
                                        echo "<div class = 'no_menus_div'>В этой категории нет доступных меню!</div>";
                                    }

																	echo "<div class='row'>";
																	foreach($rows_menus as $menu)
																	{
																		?>

<div class="col-md-4 col-lg-3 menu-column">
	<?php $source = "admin/Uploads/images/".$menu['menu_image']; ?>
	<div class="thumbnail menu-item" data-menu-id="<?php echo $menu['menu_id']; ?>" data-menu-name="<?php echo htmlspecialchars($menu['menu_name'], ENT_QUOTES); ?>" data-menu-price="<?php echo $menu['menu_price']; ?>" data-menu-desc="<?php echo htmlspecialchars($menu['menu_description'], ENT_QUOTES); ?>" data-menu-img="<?php echo $source; ?>" data-menu-weight="<?php echo isset($menu['menu_weight']) ? htmlspecialchars($menu['menu_weight'], ENT_QUOTES) : ''; ?>" data-category-id="<?php echo $menu['category_id']; ?>">

		<div class="menu-image product-photo" title="Подробнее о товаре">
			<div class="image-preview">
				<div style="background-image: url('<?php echo $source; ?>');"></div>
			</div>
			<?php if (isset($menu['menu_weight']) && $menu['menu_weight'] !== '') { ?>
				<span class="menu-weight-badge"><?php echo htmlspecialchars($menu['menu_weight'], ENT_QUOTES); ?> г</span>
			<?php } ?>
		</div>

		<div class="caption">
			<h5>
				<span class="menu-name-text"><?php echo $menu['menu_name'];?></span>
				<?php if ($is_manager_or_admin) { ?>
					<span class="edit-pencil" data-menu-id="<?php echo $menu['menu_id']; ?>" data-menu-name="<?php echo htmlspecialchars($menu['menu_name'], ENT_QUOTES); ?>" data-menu-price="<?php echo $menu['menu_price']; ?>" data-menu-desc="<?php echo htmlspecialchars($menu['menu_description'], ENT_QUOTES); ?>" data-menu-img="<?php echo $source; ?>" data-category-id="<?php echo $menu['category_id']; ?>" style="cursor:pointer;opacity:0.85;display:inline-flex;align-items:center;"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></span>
				<?php } ?>
			</h5>
			<p class="menu-desc-text">
				<?php echo $menu['menu_description']; ?>
			</p>
			<div class="card-bottom-row">
				<span class="menu_price"><?php echo $menu['menu_price']."₽"; ?></span>
				<button type="button" class="btn-add-cart">В корзину</button>
			</div>
		</div>
	</div>
</div>

																		<?php
																		}
																		// add-tile for manager/admin in this category
																		if ($is_manager_or_admin) {
																			?>
																			<div class="col-md-4 col-lg-3 menu-column">
																				<div class="thumbnail add-tile" style="cursor:pointer" data-category-id="<?php echo $row['category_id']; ?>">
																					<div class="menu-image">
																						<div class="image-preview">
																							<div class="add-tile-inner">
																								<span class="add-tile-plus">+</span>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																			<?php
																		}
																		echo "</div>";

                                echo '</div>';

                            }

                            $i++;
                            
                        }
                    
                        echo "</div>";
                
                    ?>
				</div>
			</div>
		</div>
	</section>

  	<!-- FOOTER BOTTOM  -->

<style>
/* ====================== PRODUCT CARD REDESIGN ====================== */
.our_menus .menu-column { margin-bottom: 28px; }

.our_menus .thumbnail.menu-item {
	background: #fff;
	border-radius: 18px;
	overflow: hidden;
	box-shadow: 0 8px 24px rgba(0,0,0,0.28);
	display: flex;
	flex-direction: column;
	height: 100%;
	transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.our_menus .thumbnail.menu-item:hover {
	transform: translateY(-3px);
	box-shadow: 0 14px 32px rgba(0,0,0,0.32);
}

.our_menus .menu-image.product-photo {
	position: relative;
	cursor: zoom-in;
	aspect-ratio: 1 / 0.92;
	overflow: hidden;
}
.our_menus .menu-image.product-photo .image-preview,
.our_menus .menu-image.product-photo .image-preview > div {
	width: 100%;
	height: 100%;
}
.our_menus .menu-image.product-photo .image-preview > div {
	background-size: cover;
	background-position: center;
	transition: transform 0.35s ease;
}
.our_menus .menu-image.product-photo:hover .image-preview > div {
	transform: scale(1.04);
}
.our_menus .menu-weight-badge {
	position: absolute;
	top: 10px;
	right: 10px;
	background: rgba(255,255,255,0.92);
	color: #16161a;
	font-size: 11px;
	font-weight: 600;
	padding: 4px 9px;
	border-radius: 20px;
	letter-spacing: 0.02em;
	pointer-events: none;
}

.our_menus .thumbnail.menu-item .caption {
	background: #fff;
	padding: 14px 16px 16px;
	display: flex;
	flex-direction: column;
	flex: 1;
}
.our_menus .thumbnail.menu-item .caption h5 {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	gap: 8px;
	margin: 0 0 6px;
}
.our_menus .thumbnail.menu-item .menu-name,
.our_menus .thumbnail.menu-item .menu-name-text {
	font-size: 15px;
	font-weight: 700;
	color: #16161a;
	line-height: 1.3;
}
.our_menus .thumbnail.menu-item .menu-desc-text {
	font-size: 12.5px;
	line-height: 1.45;
	color: #8e8e93;
	margin: 0 0 12px;
	display: -webkit-box;
	-webkit-line-clamp: 2;
	-webkit-box-orient: vertical;
	overflow: hidden;
}
.our_menus .card-bottom-row {
	margin-top: auto;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px;
}
.our_menus .card-bottom-row .menu_price {
	font-size: 16px;
	font-weight: 700;
	color: #16161a;
}
.our_menus .btn-add-cart {
	background: #f0f0f1;
	color: #16161a;
	border: none;
	border-radius: 30px;
	padding: 9px 18px;
	font-size: 13px;
	font-weight: 600;
	cursor: pointer;
	transition: background 0.15s ease, transform 0.1s ease;
	white-space: nowrap;
}
.our_menus .btn-add-cart:hover { background: #e2e2e4; }
.our_menus .btn-add-cart:active { transform: scale(0.96); }
.our_menus .btn-add-cart.added { background: #c9f2d6; color: #146c2e; }

/* ====================== PRODUCT DETAILS MODAL ====================== */
.product-modal-overlay {
	position: fixed;
	inset: 0;
	background: rgba(0,0,0,0.65);
	z-index: 10050;
	display: none;
	align-items: center;
	justify-content: center;
	padding: 24px;
}
.product-modal-overlay.open { display: flex; }
.product-modal {
	background: #fff;
	border-radius: 20px;
	overflow: hidden;
	max-width: 880px;
	width: 100%;
	max-height: 90vh;
	display: flex;
	box-shadow: 0 30px 70px rgba(0,0,0,0.5);
	position: relative;
}
.product-modal-image {
	flex: 1 1 48%;
	min-height: 320px;
	background-size: cover;
	background-position: center;
	background-color: #222;
}
.product-modal-info {
	flex: 1 1 52%;
	padding: 32px 34px;
	display: flex;
	flex-direction: column;
	overflow-y: auto;
}
.product-modal-close {
	position: absolute;
	top: 14px;
	right: 14px;
	width: 34px;
	height: 34px;
	border-radius: 50%;
	background: rgba(255,255,255,0.85);
	border: none;
	font-size: 20px;
	line-height: 1;
	color: #333;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 2;
}
.product-modal-close:hover { background: #fff; }
.product-modal-title {
	font-size: 23px;
	font-weight: 700;
	color: #16161a;
	margin: 0 0 4px;
}
.product-modal-weight {
	font-size: 13px;
	color: #9a9a9e;
	margin-bottom: 16px;
}
.product-modal-desc {
	font-size: 14px;
	line-height: 1.65;
	color: #56565b;
	margin-bottom: 22px;
	white-space: pre-line;
}
.product-modal-spacer { flex: 1; }
.product-modal-add-cart {
	background: #6f5cf0;
	color: #fff;
	border: none;
	border-radius: 14px;
	padding: 16px;
	font-size: 14px;
	font-weight: 700;
	letter-spacing: 0.02em;
	text-transform: uppercase;
	cursor: pointer;
	transition: opacity 0.15s ease;
}
.product-modal-add-cart:hover { opacity: 0.9; }
.product-modal-add-cart.added { background: #2fae5c; }

@media (max-width: 720px) {
	.product-modal { flex-direction: column; max-height: 92vh; }
	.product-modal-image { min-height: 220px; }
	.product-modal-info { padding: 24px 20px; }
}
</style>

<!-- CART TOAST NOTIFICATION -->
<div id="cart-toast" style="display:none;position:fixed;bottom:20px;right:20px;background:#4CAF50;color:white;padding:15px 25px;border-radius:5px;z-index:9999;font-size:16px;box-shadow:0 4px 12px rgba(0,0,0,0.15);"></div>

<!-- PRODUCT DETAILS MODAL (opens when a product photo is clicked) -->
<div class="product-modal-overlay" id="productDetailsModal">
	<div class="product-modal">
		<button type="button" class="product-modal-close" id="productModalClose">&times;</button>
		<div class="product-modal-image" id="pmImage"></div>
		<div class="product-modal-info">
			<h3 class="product-modal-title" id="pmName"></h3>
			<div class="product-modal-weight" id="pmWeight" style="display:none;"></div>
			<p class="product-modal-desc" id="pmDesc"></p>
			<div class="product-modal-spacer"></div>
			<button type="button" class="product-modal-add-cart" id="pmAddCart">В корзину</button>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {

	function showToast(text) {
		var toast = $('#cart-toast');
		toast.text(text);
		toast.fadeIn(200);
		setTimeout(function() { toast.fadeOut(500); }, 2000);
	}

	function updateCartBadge(count) {
		var badge = $('#cart-count');
		if (count > 0) { badge.text(count).show(); }
		else { badge.hide(); }
	}

	function addToCart(menuId, menuName, $btn, defaultLabel) {
		$.ajax({
			url: 'add_to_cart.php',
			type: 'POST',
			data: { action: 'add', menu_id: menuId },
			success: function(response) {
				if (response.success) {
					showToast(menuName + ' добавлено в корзину');
					updateCartBadge(response.cart_total);
					if ($btn && $btn.length) {
						$btn.addClass('added').text('Добавлено ✓');
						setTimeout(function() {
							$btn.removeClass('added').text(defaultLabel);
						}, 1200);
					}
				}
			}
		});
	}

	// "В корзину" button on each product card
	$(document).on('click', '.btn-add-cart', function(e) {
		e.stopPropagation();
		var $btn = $(this);
		var $item = $btn.closest('.menu-item');
		addToCart($item.data('menu-id'), $item.data('menu-name'), $btn, 'В корзину');
	});

	// Clicking the product photo opens the details modal
	$(document).on('click', '.product-photo', function(e) {
		var $item = $(this).closest('.menu-item');
		var name = $item.data('menu-name');
		var price = $item.data('menu-price');
		var desc = $item.data('menu-desc');
		var img = $item.data('menu-img');
		var weight = $item.data('menu-weight');
		var menuId = $item.data('menu-id');

		$('#pmImage').css('background-image', "url('" + img + "')");
		$('#pmName').text(name);
		$('#pmDesc').text(desc);
		if (weight) {
			$('#pmWeight').text(weight + ' г').show();
		} else {
			$('#pmWeight').hide();
		}
		$('#pmAddCart').removeClass('added').text('В КОРЗИНУ ЗА ' + price + ' ₽')
			.data('menu-id', menuId).data('menu-name', name).data('price', price);

		$('#productDetailsModal').addClass('open');
		$('body').css('overflow', 'hidden');
	});

	function closeProductModal() {
		$('#productDetailsModal').removeClass('open');
		$('body').css('overflow', '');
	}

	$('#productModalClose').on('click', closeProductModal);
	$('#productDetailsModal').on('click', function(e) {
		if (e.target === this) closeProductModal();
	});
	$(document).on('keydown', function(e) {
		if (e.key === 'Escape') closeProductModal();
	});

	$('#pmAddCart').on('click', function() {
		var $btn = $(this);
		var price = $btn.data('price');
		addToCart($btn.data('menu-id'), $btn.data('menu-name'), $btn, 'В КОРЗИНУ ЗА ' + price + ' ₽');
	});

	// Cart count on page load
	$.ajax({
		url: 'add_to_cart.php',
		type: 'POST',
		data: { action: 'count' },
		success: function(response) {
			if (response.success && response.cart_total > 0) {
				updateCartBadge(response.cart_total);
			}
		}
	});
});
</script>
		<!-- Quick Add Modal (manager/admin) -->
		<?php if ($is_manager_or_admin): ?>
		<style>
		/* add-tile: круг совпадает по размерам с .image-preview обычных карточек */
		.add-tile .image-preview > div.add-tile-inner {
			width: 100%;
			height: 100%;
			border-radius: 50%;
			background: rgba(255,255,255,0.07);
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.add-tile-plus {
			font-size: 90px;
			line-height: 1;
			color: rgba(255,255,255,0.35);
			font-weight: 200;
			user-select: none;
			margin-top: -6px;
		}
		.add-tile:hover .add-tile-inner {
			background: rgba(255,255,255,0.13);
		}
		.add-tile:hover .add-tile-plus {
			color: rgba(255,255,255,0.6);
		}
		/* Minimalist pencil edit button */
		.quick-edit, .edit-pencil {
			background: none !important;
			border: none !important;
			box-shadow: none !important;
			padding: 2px !important;
			margin: 0 !important;
			color: rgba(255,255,255,0.45) !important;
			cursor: pointer;
			display: inline-flex;
			align-items: center;
			transition: color 0.2s;
			flex-shrink: 0;
		}
		.quick-edit:hover, .edit-pencil:hover {
			color: rgba(255,255,255,0.9) !important;
		}
		.quick-edit svg, .edit-pencil svg {
			display: block;
		}
		</style>

		<style>
		#addMenuModal .modal-dialog { max-width: 420px; }
		#addMenuModal .modal-content {
			background: #1a1a1a;
			border: 0.5px solid rgba(255,255,255,0.12);
			border-radius: 12px;
			overflow: hidden;
			box-shadow: 0 24px 64px rgba(0,0,0,0.6);
		}
		#addMenuModal .modal-header {
			background: transparent;
			border-bottom: 0.5px solid rgba(255,255,255,0.08);
			padding: 1.1rem 1.4rem 1rem;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}
		#addMenuModal .modal-title {
			font-size: 13px;
			font-weight: 500;
			color: #e8e2d4;
			letter-spacing: 0.1em;
			text-transform: uppercase;
			margin: 0;
		}
		#addMenuModal .close {
			color: rgba(255,255,255,0.35);
			opacity: 1;
			text-shadow: none;
			font-size: 20px;
			transition: color 0.15s;
		}
		#addMenuModal .close:hover { color: rgba(255,255,255,0.75); }
		#addMenuModal .modal-body {
			padding: 1.25rem 1.4rem;
			background: transparent;
		}
		#addMenuModal .form-group { margin-bottom: 1.1rem; }
		#addMenuModal .form-group label {
			display: block;
			font-size: 10px;
			font-weight: 500;
			letter-spacing: 0.12em;
			text-transform: uppercase;
			color: rgba(255,255,255,0.35);
			margin-bottom: 7px;
		}
		#addMenuModal .form-control {
			background: rgba(255,255,255,0.05) !important;
			border: 0.5px solid rgba(255,255,255,0.12) !important;
			border-radius: 8px !important;
			color: #e8e2d4 !important;
			font-size: 14px;
			padding: 9px 12px;
			transition: border-color 0.15s;
			box-shadow: none !important;
		}
		#addMenuModal .form-control:focus {
			border-color: rgba(185,155,90,0.55) !important;
			background: rgba(255,255,255,0.07) !important;
		}
		#addMenuModal .form-control::placeholder { color: rgba(255,255,255,0.2); }
		#addMenuModal textarea.form-control {
			height: 80px;
			resize: none;
			line-height: 1.55;
		}
		#addMenuModal .price-wrap {
			display: flex;
			align-items: center;
			background: rgba(255,255,255,0.05);
			border: 0.5px solid rgba(255,255,255,0.12);
			border-radius: 8px;
			overflow: hidden;
			transition: border-color 0.15s;
		}
		#addMenuModal .price-wrap:focus-within { border-color: rgba(185,155,90,0.55); }
		#addMenuModal .price-wrap input {
			background: none !important;
			border: none !important;
			border-radius: 0 !important;
			flex: 1;
			color: #e8e2d4 !important;
			font-size: 14px;
			padding: 9px 12px;
			outline: none;
			box-shadow: none !important;
		}
		#addMenuModal .price-symbol {
			padding: 0 14px 0 0;
			font-size: 14px;
			color: rgba(185,155,90,0.7);
			font-weight: 500;
		}
		#addMenuModal .file-row {
			display: flex;
			align-items: center;
			gap: 10px;
		}
		#addMenuModal .file-btn-label {
			background: rgba(255,255,255,0.06);
			border: 0.5px solid rgba(255,255,255,0.15);
			border-radius: 8px;
			color: rgba(255,255,255,0.6);
			font-size: 13px;
			padding: 8px 14px;
			cursor: pointer;
			white-space: nowrap;
			transition: background 0.15s;
			margin: 0;
		}
		#addMenuModal .file-btn-label:hover { background: rgba(255,255,255,0.1); }
		#addMenuModal input[type="file"] { display: none; }
		#addMenuModal .file-status {
			font-size: 13px;
			color: rgba(255,255,255,0.28);
		}
		#addMenuModal .modal-footer {
			background: transparent;
			border-top: 0.5px solid rgba(255,255,255,0.08);
			padding: 0.9rem 1.4rem 1.2rem;
			display: flex;
			justify-content: flex-end;
			gap: 10px;
		}
		#addMenuModal .btn-cancel-custom {
			background: rgba(255,255,255,0.06);
			border: 0.5px solid rgba(255,255,255,0.12);
			border-radius: 8px;
			color: rgba(255,255,255,0.5);
			font-size: 13px;
			padding: 9px 20px;
			cursor: pointer;
			transition: background 0.15s;
		}
		#addMenuModal .btn-cancel-custom:hover { background: rgba(255,255,255,0.1); }
		#addMenuModal .btn-add-custom {
			background: linear-gradient(135deg, #b99b5a 0%, #8a7040 100%);
			border: none;
			border-radius: 8px;
			color: #1a1510;
			font-size: 13px;
			font-weight: 600;
			padding: 9px 24px;
			cursor: pointer;
			letter-spacing: 0.04em;
			transition: opacity 0.15s;
		}
		#addMenuModal .btn-add-custom:hover { opacity: 0.88; }
		#addMenuModal .image-preview-thumb {
			margin-top: 10px;
			display: flex;
			justify-content: center;
		}
		#addMenuModal #add_menu_imagePreview {
			width: 80px;
			height: 80px;
			border-radius: 8px;
			border: 0.5px dashed rgba(255,255,255,0.2);
			display: flex;
			align-items: center;
			justify-content: center;
			overflow: hidden;
			background: rgba(255,255,255,0.03);
		}
		#addMenuModal #add_menu_imagePreview img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }
		#addMenuModal #add_menu_imagePreview .plus-icon { font-size: 28px; color: rgba(255,255,255,0.2); font-weight: 200; }
		</style>

		<div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Добавить позицию</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form id="addMenuForm" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="form-group">
							<label>Название</label>
							<input type="text" name="menu_name" id="menu_name" class="form-control" placeholder="Напр. Ролл Филадельфия" required />
						</div>
						<div class="form-group">
					
						<div class="form-group">
							<label>Описание</label>
							<textarea name="menu_description" id="menu_description" class="form-control" placeholder="Кратко опишите состав и вкус блюда..."></textarea>
						</div>
						<div class="form-group">
							<label>Цена</label>
							<div class="price-wrap">
								<input type="text" name="menu_price" id="menu_price" placeholder="0" />
								<span class="price-symbol">₽</span>
							</div>
						</div>
						<div class="form-group">
							<label>Фото</label>
							<div class="file-row">
								<label class="file-btn-label" for="menu_image">&#8593; Выбрать файл</label>
								<input type="file" name="menu_image" id="menu_image" accept="image/*" />
								<span class="file-status" id="file_status_text">Файл не выбран</span>
							</div>
							<div class="image-preview-thumb">
								<div id="add_menu_imagePreview"><div class="plus-icon">+</div></div>
							</div>
						</div>
						<input type="hidden" name="category_id" id="add_category_id" />
						<input type="hidden" name="menu_id" id="menu_id" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn-cancel-custom" data-dismiss="modal">Отмена</button>
						<button type="submit" class="btn-add-custom">Добавить</button>
					</div>
					</form>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php include "Includes/templates/footer.php"; ?>

    <script type="text/javascript">

	    $(document).ready(function()
	    {
	        $('#contact_send').click(function()
	        {
	            var contact_name = $('#contact_name').val();
	            var contact_email = $('#contact_email').val();
	            var contact_subject = $('#contact_subject').val();
	            var contact_message = $('#contact_message').val();

	            var flag = 0;

if($.trim(contact_name) == "")
    	            {
	            		$('#invalid-name').text('Это обязательное поле!');
	            		flag = 1;
	            	}
	            else
	            {
	            	if(contact_name.length < 5)
	            	{
	            		$('#invalid-name').text('Длина меньше 5 символов!');
	            		flag = 1;
	                	}
	            }

	            if(!ValidateEmail(contact_email))
	            {
	            		$('#invalid-email').text('Неверный email!');
	            		flag = 1;
	            }

	            if($.trim(contact_subject) == "")
	            {
	            		$('#invalid-subject').text('Это обязательное поле!');
	            		flag = 1;
	        }

	            if($.trim(contact_message) == "")
	            {
	            		$('#invalid-message').text('Это обязательное поле!');
	            		flag = 1;
	        }

	            if(flag == 0)
	            {
	            	$('#sending_load').show();

		            $.ajax({
		                url: "Includes/php-files-ajax/contact.php",
		                type: "POST",
		                data:{contact_name:contact_name, contact_email:contact_email, contact_subject:contact_subject, contact_message:contact_message},
		                success: function (data) 
		                {
		                	$('#contact_status_message').html(data);
		                },
		                beforeSend: function()
		                {
					        $('#sending_load').show();
					    },
					    complete: function()
					    {
					        $('#sending_load').hide();
					    },
		                error: function(xhr, status, error) 
		                {
		                    alert("Internal ERROR has occured, please, try later!");
		                }
		            });
	            }
	            
	        });
	    }); 
	    
	</script>

	<?php if ($is_manager_or_admin): ?>
	<script type="text/javascript">
		$(document).on('click', '.add-tile', function(e) {
			var cat = $(this).data('category-id');
			$('#add_category_id').val(cat);
			$('#menu_id').val('');
			$('#menu_name').val('');
			$('#menu_description').val('');
			$('#menu_price').val('');
			$('#file_status_text').text('Файл не выбран');
			$('#add_menu_imagePreview').html('<div class="plus-icon">+</div>');
			$('#addMenuModal .modal-title').text('Добавить позицию');
			$('.btn-add-custom').text('Добавить');
			$('#addMenuModal').modal('show');
		});

		// Quick edit handler: open modal with item data for editing
		$(document).on('click', '.quick-edit, .edit-pencil', function(e) {
			e.stopPropagation();
			var $t = $(this);
			var menu_id = $t.data('menu-id');
			var menu_name = $t.data('menu-name') || $t.attr('data-menu-name');
			var menu_price = $t.data('menu-price') || $t.attr('data-menu-price');
			var menu_desc = $t.data('menu-desc') || $t.attr('data-menu-desc');
			var menu_img = $t.data('menu-img') || $t.attr('data-menu-img');
			var cat = $t.data('category-id') || $t.attr('data-category-id');

			$('#menu_id').val(menu_id);
			$('#add_category_id').val(cat);
			$('#menu_name').val(menu_name);
			$('#menu_price').val(menu_price);
			$('#menu_description').val(menu_desc);
			if (menu_img) {
				$('#add_menu_imagePreview').html('<img src="'+menu_img+'" />');
				$('#file_status_text').text(menu_img.split('/').pop());
			} else {
				$('#add_menu_imagePreview').html('<div class="plus-icon">+</div>');
				$('#file_status_text').text('Файл не выбран');
			}

			$('#addMenuModal .modal-title').text('Редактировать позицию');
			$('.btn-add-custom').text('Сохранить');
			$('#addMenuModal').modal('show');
		});

		function readURL_Add(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#add_menu_imagePreview').html('<img src="'+e.target.result+'" />');
				}
				reader.readAsDataURL(input.files[0]);
			}
		}

		$('#menu_image').change(function(){
			readURL_Add(this);
			var name = this.files && this.files[0] ? this.files[0].name : 'Файл не выбран';
			$('#file_status_text').text(name);
		});

		$('#addMenuForm').submit(function(e){
			e.preventDefault();
			var fd = new FormData(this);
			$.ajax({
				url: 'add_menu_front.php',
				type: 'POST',
				data: fd,
				processData: false,
				contentType: false,
				dataType: 'json',
				success: function(resp) {
					if (resp && resp.success) {
						window.location.reload();
					} else {
						alert(resp.message || 'Ошибка при добавлении меню');
					}
				},
				error: function() { alert('Ошибка сети при добавлении меню'); }
			});
		});
	</script>
	<?php endif; ?>


	<script>
	// Fix anchor scroll offset for fixed navbar
	(function() {
		var navbarHeight = document.querySelector('nav, .navbar, header') 
			? document.querySelector('nav, .navbar, header').offsetHeight 
			: 90;

		// Apply scroll-padding-top to html so CSS anchors also work
		document.documentElement.style.scrollPaddingTop = navbarHeight + 'px';

		// Override all anchor clicks with smooth scroll + offset
		document.addEventListener('click', function(e) {
			var link = e.target.closest('a[href]');
			if (!link) return;
			var href = link.getAttribute('href');
			if (!href) return;

			// Handle same-page hash links: #home, #menus, index.php#home etc.
			var hashMatch = href.match(/(^#|[^#]*#)([^?]+)$/);
			if (!hashMatch) return;
			var id = hashMatch[2];
			var target = document.getElementById(id);
			if (!target) return;

			e.preventDefault();
			var top = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
			window.scrollTo({ top: top, behavior: 'smooth' });
			history.pushState(null, '', '#' + id);
		});

		// Fix initial load if URL has hash
		if (window.location.hash) {
			setTimeout(function() {
				var id = window.location.hash.slice(1);
				var target = document.getElementById(id);
				if (target) {
					var top = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
					window.scrollTo({ top: top, behavior: 'smooth' });
				}
			}, 100);
		}
	})();
	</script>