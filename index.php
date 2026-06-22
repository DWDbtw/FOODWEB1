<?php
session_start();
include "connect.php";
include 'Includes/functions/functions.php';
include "Includes/templates/header.php";
include "Includes/templates/navbar.php";

// Getting website settings
$stmt_web_settings = $con->prepare("SELECT * FROM website_settings");
$stmt_web_settings->execute();
$web_settings = $stmt_web_settings->fetchAll();

$restaurant_name = "";
$restaurant_email = "";
$restaurant_address = "";
$restaurant_phonenumber = "";

foreach ($web_settings as $option) {
    if ($option['option_name'] == 'restaurant_name') {
        $restaurant_name = $option['option_value'];
    } elseif ($option['option_name'] == 'restaurant_email') {
        $restaurant_email = $option['option_value'];
    } elseif ($option['option_name'] == 'restaurant_phonenumber') {
        $restaurant_phonenumber = $option['option_value'];
    } elseif ($option['option_name'] == 'restaurant_address') {
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
        $stmtRole->execute([(int)$_SESSION['user_id']]);
        $r = $stmtRole->fetch(PDO::FETCH_ASSOC);
        if ($r && in_array($r['role'], ['admin', 'manager'])) $is_manager_or_admin = true;
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
                    <h1>VINCENT SUSHI.</h1>
                    <h2>ДЕЛАЕМ ЛЮДЕЙ СЧАСТЛИВЫМИ</h2>
                    <hr>
                    <p>Японская кухня с свежай рыбой и овощами</p>
                    <div style="display: flex;">
                        <a href="table-reservation.php" class="bttn_style_1" style="margin-right: 10px; display: flex;justify-content: center;align-items: center;">
                            ЗАБРОНИРОВАТЬ СТОЛИК <i class="fas fa-angle-right"></i>
                        </a>
                        <a href="#menus" class="bttn_style_2" style="display: flex;justify-content: center;align-items: center;">
                            ПОСМОТРЕТЬ МЕНЮ <i class="fas fa-angle-right"></i>
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
                    <img src="Design/images/quality_food_img.png">
                    <div class="caption">
                        <h3>КАЧЕСТВЕННАЯ ЕДА</h3>
                        <p>Свежие ингредиенты и высокое качество</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="our_qualities_column">
                    <img src="Design/images/fast_delivery_img.png">
                    <div class="caption">
                        <h3>БЫСТРАЯ ДОСТАВКА</h3>
                        <p>Свежие ингредиенты и высокое качество</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="our_qualities_column">
                    <img src="Design/images/original_taste_img.png">
                    <div class="caption">
                        <h3>ОРИГИНАЛЬНЫЙ ВКУС</h3>
                        <p>Свежие ингредиенты и высокое качество</p>
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
                    $x = 0;
                    foreach ($rows as $row) {
                        $catName = strtolower($row['category_name']);
                        if (in_array($catName, ['salads','салаты','burgers','бургеры','pizzas','пиццы','pizza','пицца','traditional food','traditional','традиционные блюда','традиционные'])) continue;
                        if ($x == 0) {
                            echo "<li class='menu_category_name tab_category_links active_category' onclick=showCategoryMenus(event,'".str_replace(' ', '', $row['category_name'])."')>";
                        } else {
                            echo "<li class='menu_category_name tab_category_links' onclick=showCategoryMenus(event,'".str_replace(' ', '', $row['category_name'])."')>";
                        }
                        echo $row['category_name'];
                        echo "</li>";
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
                $i = 0;
                foreach ($rows as $row) {
                    $catName = strtolower($row['category_name']);
                    if (in_array($catName, ['salads','салаты','burgers','бургеры','pizzas','пиццы','pizza','пицца','traditional food','traditional','традиционные блюда','традиционные'])) continue;

                    $wrapClass = ($i == 0) ? 'menu_item tab_category_content' : 'menus_categories tab_category_content';
                    $wrapStyle = ($i == 0) ? 'style=display:block' : '';
                    echo '<div class="'.$wrapClass.'" id="'.str_replace(' ', '', $row['category_name']).'" '.$wrapStyle.'>';

                    $stmt_menus = $con->prepare("Select * from menus where category_id = ?");
                    $stmt_menus->execute([$row['category_id']]);
                    $rows_menus = $stmt_menus->fetchAll();

                    if ($stmt_menus->rowCount() == 0) {
                        echo "<div style='margin:auto'>В этой категории нет доступных меню!</div>";
                    }

                    echo "<div class='row'>";
                    foreach ($rows_menus as $menu) {
                        $source = "admin/Uploads/images/" . $menu['menu_image'];
                        $cal = htmlspecialchars($menu['calories'] ?? '', ENT_QUOTES);
                        $pro = htmlspecialchars($menu['proteins'] ?? '', ENT_QUOTES);
                        $fat = htmlspecialchars($menu['fats']     ?? '', ENT_QUOTES);
                        $carb= htmlspecialchars($menu['carbs']    ?? '', ENT_QUOTES);
                        ?>
                        <div class="col-md-4 col-lg-3 menu-column">
                            <div class="thumbnail menu-item" style="cursor:pointer"
                                 data-menu-id="<?php echo $menu['menu_id']; ?>"
                                 data-menu-name="<?php echo htmlspecialchars($menu['menu_name'], ENT_QUOTES); ?>"
                                 data-menu-price="<?php echo $menu['menu_price']; ?>"
                                 data-menu-desc="<?php echo htmlspecialchars($menu['menu_description'], ENT_QUOTES); ?>"
                                 data-menu-img="<?php echo $source; ?>"
                                 data-category-id="<?php echo $menu['category_id']; ?>"
                                 data-menu-calories="<?php echo $cal; ?>"
                                 data-menu-proteins="<?php echo $pro; ?>"
                                 data-menu-fats="<?php echo $fat; ?>"
                                 data-menu-carbs="<?php echo $carb; ?>">
                                <div class="menu-image">
                                    <div class="image-preview">
                                        <div style="background-image: url('<?php echo $source; ?>');"></div>
                                    </div>
                                </div>
                                <div class="caption">
                                    <h5 style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                                        <span class="menu-name"><?php echo $menu['menu_name']; ?></span>
                                        <?php if ($is_manager_or_admin) { ?>
                                            <button type="button" class="quick-edit" title="Редактировать"
                                                    data-menu-id="<?php echo $menu['menu_id']; ?>"
                                                    data-menu-name="<?php echo htmlspecialchars($menu['menu_name'], ENT_QUOTES); ?>"
                                                    data-menu-price="<?php echo $menu['menu_price']; ?>"
                                                    data-menu-desc="<?php echo htmlspecialchars($menu['menu_description'], ENT_QUOTES); ?>"
                                                    data-menu-img="<?php echo $source; ?>"
                                                    data-category-id="<?php echo $menu['category_id']; ?>"
                                                    data-menu-calories="<?php echo $cal; ?>"
                                                    data-menu-proteins="<?php echo $pro; ?>"
                                                    data-menu-fats="<?php echo $fat; ?>"
                                                    data-menu-carbs="<?php echo $carb; ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                            </button>
                                        <?php } ?>
                                    </h5>
                                    <p><?php echo $menu['menu_description']; ?></p>
                                    <span class="menu_price"><?php echo $menu['menu_price'] . "₽"; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                    }

                    if ($is_manager_or_admin) { ?>
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
                    <?php }

                    echo "</div>";
                    echo '</div>';
                    $i++;
                }
                echo "</div>";
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Quick Add/Edit Modal (manager/admin) -->
<?php if ($is_manager_or_admin): ?>
<style>
.add-tile .image-preview > div.add-tile-inner {
    width: 100%; height: 100%; border-radius: 50%;
    background: rgba(255,255,255,0.07);
    display: flex; align-items: center; justify-content: center;
}
.add-tile-plus {
    font-size: 90px; line-height: 1;
    color: rgba(255,255,255,0.35); font-weight: 200;
    user-select: none; margin-top: -6px;
}
.add-tile:hover .add-tile-inner { background: rgba(255,255,255,0.13); }
.add-tile:hover .add-tile-plus  { color: rgba(255,255,255,0.6); }
.quick-edit, .edit-pencil {
    background: none !important; border: none !important; box-shadow: none !important;
    padding: 2px !important; margin: 0 !important;
    color: rgba(255,255,255,0.45) !important; cursor: pointer;
    display: inline-flex; align-items: center; transition: color 0.2s; flex-shrink: 0;
}
.quick-edit:hover, .edit-pencil:hover { color: rgba(255,255,255,0.9) !important; }
.quick-edit svg, .edit-pencil svg { display: block; }
</style>
<style>
#addMenuModal .modal-dialog { max-width: 420px; }
#addMenuModal .modal-content {
    background: #1a1a1a; border: 0.5px solid rgba(255,255,255,0.12);
    border-radius: 12px; overflow: hidden; box-shadow: 0 24px 64px rgba(0,0,0,0.6);
}
#addMenuModal .modal-header {
    background: transparent; border-bottom: 0.5px solid rgba(255,255,255,0.08);
    padding: 1.1rem 1.4rem 1rem; display: flex; align-items: center; justify-content: space-between;
}
#addMenuModal .modal-title {
    font-size: 13px; font-weight: 500; color: #e8e2d4;
    letter-spacing: 0.1em; text-transform: uppercase; margin: 0;
}
#addMenuModal .close { color: rgba(255,255,255,0.35); opacity: 1; text-shadow: none; font-size: 20px; transition: color 0.15s; }
#addMenuModal .close:hover { color: rgba(255,255,255,0.75); }
#addMenuModal .modal-body { padding: 1.25rem 1.4rem; background: transparent; }
#addMenuModal .form-group { margin-bottom: 1.1rem; }
#addMenuModal .form-group label {
    display: block; font-size: 10px; font-weight: 500;
    letter-spacing: 0.12em; text-transform: uppercase;
    color: rgba(255,255,255,0.35); margin-bottom: 7px;
}
#addMenuModal .form-control {
    background: rgba(255,255,255,0.05) !important;
    border: 0.5px solid rgba(255,255,255,0.12) !important;
    border-radius: 8px !important; color: #e8e2d4 !important;
    font-size: 14px; padding: 9px 12px; transition: border-color 0.15s; box-shadow: none !important;
}
#addMenuModal .form-control:focus {
    border-color: rgba(185,155,90,0.55) !important;
    background: rgba(255,255,255,0.07) !important;
}
#addMenuModal .form-control::placeholder { color: rgba(255,255,255,0.2); }
#addMenuModal textarea.form-control { height: 80px; resize: none; line-height: 1.55; }
#addMenuModal .price-wrap {
    display: flex; align-items: center;
    background: rgba(255,255,255,0.05); border: 0.5px solid rgba(255,255,255,0.12);
    border-radius: 8px; overflow: hidden; transition: border-color 0.15s;
}
#addMenuModal .price-wrap:focus-within { border-color: rgba(185,155,90,0.55); }
#addMenuModal .price-wrap input {
    background: none !important; border: none !important; border-radius: 0 !important;
    flex: 1; color: #e8e2d4 !important; font-size: 14px; padding: 9px 12px;
    outline: none; box-shadow: none !important;
}
#addMenuModal .price-symbol { padding: 0 14px 0 0; font-size: 14px; color: rgba(185,155,90,0.7); font-weight: 500; }
#addMenuModal .nutrition-row { display: flex; gap: 6px; }
#addMenuModal .nutrition-row .form-control { padding: 9px 8px; text-align: center; }
#addMenuModal .file-row { display: flex; align-items: center; gap: 10px; }
#addMenuModal .file-btn-label {
    background: rgba(255,255,255,0.06); border: 0.5px solid rgba(255,255,255,0.15);
    border-radius: 8px; color: rgba(255,255,255,0.6); font-size: 13px;
    padding: 8px 14px; cursor: pointer; white-space: nowrap; transition: background 0.15s; margin: 0;
}
#addMenuModal .file-btn-label:hover { background: rgba(255,255,255,0.1); }
#addMenuModal input[type="file"] { display: none; }
#addMenuModal .file-status { font-size: 13px; color: rgba(255,255,255,0.28); }
#addMenuModal .modal-footer {
    background: transparent; border-top: 0.5px solid rgba(255,255,255,0.08);
    padding: 0.9rem 1.4rem 1.2rem; display: flex; justify-content: flex-end; gap: 10px;
}
#addMenuModal .btn-cancel-custom {
    background: rgba(255,255,255,0.06); border: 0.5px solid rgba(255,255,255,0.12);
    border-radius: 8px; color: rgba(255,255,255,0.5); font-size: 13px;
    padding: 9px 20px; cursor: pointer; transition: background 0.15s;
}
#addMenuModal .btn-cancel-custom:hover { background: rgba(255,255,255,0.1); }
#addMenuModal .btn-add-custom {
    background: linear-gradient(135deg, #b99b5a 0%, #8a7040 100%);
    border: none; border-radius: 8px; color: #1a1510; font-size: 13px;
    font-weight: 600; padding: 9px 24px; cursor: pointer; letter-spacing: 0.04em; transition: opacity 0.15s;
}
#addMenuModal .btn-add-custom:hover { opacity: 0.88; }
#addMenuModal .image-preview-thumb { margin-top: 10px; display: flex; justify-content: center; }
#addMenuModal #add_menu_imagePreview {
    width: 80px; height: 80px; border-radius: 8px;
    border: 0.5px dashed rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; background: rgba(255,255,255,0.03);
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
                        <label>КБЖУ на порцию (необязательно)</label>
                        <div class="nutrition-row">
                            <input type="number" name="calories" id="calories" class="form-control" placeholder="ккал" min="0" step="0.1">
                            <input type="number" name="proteins" id="proteins" class="form-control" placeholder="Белки г" min="0" step="0.1">
                            <input type="number" name="fats"     id="fats"     class="form-control" placeholder="Жиры г"  min="0" step="0.1">
                            <input type="number" name="carbs"    id="carbs"    class="form-control" placeholder="Углев. г" min="0" step="0.1">
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

<!-- МОДАЛЬНОЕ ОКНО КАРТОЧКИ ТОВАРА -->
<style>
#productModal .modal-dialog { max-width: 680px; }
#productModal .modal-content {
    background: #18181b; border: 0.5px solid rgba(255,255,255,0.10);
    border-radius: 16px; overflow: hidden; box-shadow: 0 32px 80px rgba(0,0,0,0.7); position: relative;
}
#productModal .pm-layout { display: flex; }
#productModal .pm-img {
    width: 240px; min-height: 260px; flex-shrink: 0;
    background-size: cover; background-position: center; background-color: #111;
}
#productModal .pm-body {
    flex: 1; padding: 28px 28px 24px; display: flex; flex-direction: column;
}
#productModal .pm-close {
    position: absolute; top: 14px; right: 16px;
    background: rgba(255,255,255,0.08); border: none; border-radius: 50%;
    width: 30px; height: 30px; color: rgba(255,255,255,0.5); font-size: 18px;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: background 0.15s; z-index: 10;
}
#productModal .pm-close:hover { background: rgba(255,255,255,0.15); color: #fff; }
#productModal .pm-name { font-size: 22px; font-weight: 700; color: #f0ebe0; margin: 0 0 6px; line-height: 1.25; }
#productModal .pm-desc { font-size: 13px; color: rgba(255,255,255,0.45); margin: 0 0 18px; line-height: 1.55; }
#productModal .pm-nutrition { display: flex; gap: 8px; margin-bottom: 22px; }
#productModal .pm-nutrition-item {
    flex: 1; background: rgba(255,255,255,0.05);
    border: 0.5px solid rgba(255,255,255,0.09);
    border-radius: 10px; padding: 10px 8px 8px; text-align: center;
}
#productModal .pm-nutrition-item .val {
    display: block; font-size: 17px; font-weight: 700;
    color: #e8dfc8; line-height: 1; margin-bottom: 4px;
}
#productModal .pm-nutrition-item .lbl {
    display: block; font-size: 10px; color: rgba(255,255,255,0.35);
    letter-spacing: 0.08em; text-transform: uppercase;
}
#productModal .pm-nutrition-item.cal .val { color: #b99b5a; }
#productModal .pm-footer { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
#productModal .pm-price { font-size: 22px; font-weight: 800; color: #f0ebe0; }
#productModal .pm-add-btn {
    background: linear-gradient(135deg, #9b7fe8 0%, #7c5cc7 100%);
    border: none; border-radius: 10px; color: #fff;
    font-size: 14px; font-weight: 600; padding: 11px 22px;
    cursor: pointer; transition: opacity 0.15s; white-space: nowrap;
}
#productModal .pm-add-btn:hover { opacity: 0.85; }
@media (max-width: 576px) {
    #productModal .pm-layout { flex-direction: column; }
    #productModal .pm-img { width: 100%; min-height: 200px; }
    #productModal .pm-nutrition { flex-wrap: wrap; }
    #productModal .pm-nutrition-item { min-width: calc(50% - 4px); }
}
</style>

<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button class="pm-close" data-dismiss="modal">&times;</button>
            <div class="pm-layout">
                <div class="pm-img" id="pm-img"></div>
                <div class="pm-body">
                    <h3 class="pm-name" id="pm-name"></h3>
                    <p class="pm-desc" id="pm-desc"></p>
                    <div id="pm-nutrition-wrap"></div>
                    <div class="pm-footer">
                        <span class="pm-price" id="pm-price"></span>
                        <button class="pm-add-btn" id="pm-add-btn">В корзину</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "Includes/templates/footer.php"; ?>

<!-- CART TOAST NOTIFICATION -->
<div id="cart-toast" style="display:none;position:fixed;bottom:20px;right:20px;background:#4CAF50;color:white;padding:15px 25px;border-radius:5px;z-index:9999;font-size:16px;box-shadow:0 4px 12px rgba(0,0,0,0.15);"></div>

<script type="text/javascript">
$(document).ready(function() {
    // Контактная форма
    $('#contact_send').click(function() {
        var contact_name    = $('#contact_name').val();
        var contact_email   = $('#contact_email').val();
        var contact_subject = $('#contact_subject').val();
        var contact_message = $('#contact_message').val();
        var flag = 0;
        if ($.trim(contact_name) == "") { $('#invalid-name').text('Это обязательное поле!'); flag = 1; }
        else if (contact_name.length < 5) { $('#invalid-name').text('Длина меньше 5 символов!'); flag = 1; }
        if (!ValidateEmail(contact_email)) { $('#invalid-email').text('Неверный email!'); flag = 1; }
        if ($.trim(contact_subject) == "") { $('#invalid-subject').text('Это обязательное поле!'); flag = 1; }
        if ($.trim(contact_message) == "") { $('#invalid-message').text('Это обязательное поле!'); flag = 1; }
        if (flag == 0) {
            $.ajax({
                url: "Includes/php-files-ajax/contact.php",
                type: "POST",
                data: {contact_name: contact_name, contact_email: contact_email, contact_subject: contact_subject, contact_message: contact_message},
                success: function(data) { $('#contact_status_message').html(data); },
                beforeSend: function() { $('#sending_load').show(); },
                complete: function() { $('#sending_load').hide(); },
                error: function() { alert("Internal ERROR has occured, please, try later!"); }
            });
        }
    });

    // Счётчик корзины при загрузке
    $.ajax({
        url: 'add_to_cart.php', type: 'POST', data: {action: 'count'},
        success: function(response) {
            if (response.success && response.cart_total > 0) {
                $('#cart-count').text(response.cart_total).show();
            }
        }
    });
});
</script>

<?php if ($is_manager_or_admin): ?>
<script type="text/javascript">
// Открытие модала добавления
$(document).on('click', '.add-tile', function(e) {
    var cat = $(this).data('category-id');
    $('#add_category_id').val(cat);
    $('#menu_id').val('');
    $('#menu_name').val('');
    $('#menu_description').val('');
    $('#menu_price').val('');
    $('#calories').val('');
    $('#proteins').val('');
    $('#fats').val('');
    $('#carbs').val('');
    $('#file_status_text').text('Файл не выбран');
    $('#add_menu_imagePreview').html('<div class="plus-icon">+</div>');
    $('#addMenuModal .modal-title').text('Добавить позицию');
    $('.btn-add-custom').text('Добавить');
    $('#addMenuModal').modal('show');
});

// Открытие модала редактирования
$(document).on('click', '.quick-edit, .edit-pencil', function(e) {
    e.stopPropagation();
    var $t = $(this);
    $('#menu_id').val($t.data('menu-id'));
    $('#add_category_id').val($t.data('category-id'));
    $('#menu_name').val($t.data('menu-name'));
    $('#menu_price').val($t.data('menu-price'));
    $('#menu_description').val($t.data('menu-desc'));
    $('#calories').val($t.data('menu-calories') || '');
    $('#proteins').val($t.data('menu-proteins') || '');
    $('#fats').val($t.data('menu-fats') || '');
    $('#carbs').val($t.data('menu-carbs') || '');
    var menu_img = $t.data('menu-img');
    if (menu_img) {
        $('#add_menu_imagePreview').html('<img src="' + menu_img + '" />');
        $('#file_status_text').text(menu_img.split('/').pop());
    } else {
        $('#add_menu_imagePreview').html('<div class="plus-icon">+</div>');
        $('#file_status_text').text('Файл не выбран');
    }
    $('#addMenuModal .modal-title').text('Редактировать позицию');
    $('.btn-add-custom').text('Сохранить');
    $('#addMenuModal').modal('show');
});

// Превью изображения
function readURL_Add(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { $('#add_menu_imagePreview').html('<img src="' + e.target.result + '" />'); };
        reader.readAsDataURL(input.files[0]);
    }
}
$('#menu_image').change(function() {
    readURL_Add(this);
    $('#file_status_text').text(this.files && this.files[0] ? this.files[0].name : 'Файл не выбран');
});

// Отправка формы добавления/редактирования
$('#addMenuForm').submit(function(e) {
    e.preventDefault();
    $.ajax({
        url: 'add_menu_front.php', type: 'POST',
        data: new FormData(this), processData: false, contentType: false, dataType: 'json',
        success: function(resp) {
            if (resp && resp.success) { window.location.reload(); }
            else { alert(resp.message || 'Ошибка при сохранении'); }
        },
        error: function() { alert('Ошибка сети'); }
    });
});
</script>
<?php endif; ?>

<!-- Открытие карточки товара -->
<script type="text/javascript">
$(document).on('click', '.menu-item', function(e) {
    if ($(e.target).closest('.quick-edit, .edit-pencil').length) return;

    var $el = $(this);
    $('#pm-img').css('background-image', 'url("' + $el.data('menu-img') + '")');
    $('#pm-name').text($el.data('menu-name'));
    $('#pm-desc').text($el.data('menu-desc') || '');
    $('#pm-price').text(parseFloat($el.data('menu-price')).toFixed(2) + ' ₽');
    $('#pm-add-btn').data('menu-id', $el.data('menu-id')).data('menu-name', $el.data('menu-name'));

    var cal  = $el.data('menu-calories');
    var pro  = $el.data('menu-proteins');
    var fat  = $el.data('menu-fats');
    var carb = $el.data('menu-carbs');

    var wrap = $('#pm-nutrition-wrap');
    wrap.empty();
    if (cal || pro || fat || carb) {
        var html = '<div class="pm-nutrition">';
        if (cal)  html += '<div class="pm-nutrition-item cal"><span class="val">' + cal  + '</span><span class="lbl">ккал</span></div>';
        if (pro)  html += '<div class="pm-nutrition-item"><span class="val">' + pro  + '</span><span class="lbl">Белки г</span></div>';
        if (fat)  html += '<div class="pm-nutrition-item"><span class="val">' + fat  + '</span><span class="lbl">Жиры г</span></div>';
        if (carb) html += '<div class="pm-nutrition-item"><span class="val">' + carb + '</span><span class="lbl">Углев. г</span></div>';
        html += '</div>';
        wrap.html(html);
    }

    $('#productModal').modal('show');
});

// Кнопка "В корзину" в карточке
$(document).on('click', '#pm-add-btn', function() {
    var menuId   = $(this).data('menu-id');
    var menuName = $(this).data('menu-name');
    $.ajax({
        url: 'add_to_cart.php', type: 'POST',
        data: {action: 'add', menu_id: menuId},
        success: function(response) {
            if (response.success) {
                $('#productModal').modal('hide');
                var toast = $('#cart-toast');
                toast.text(menuName + ' добавлено в корзину');
                toast.fadeIn(200);
                setTimeout(function() { toast.fadeOut(500); }, 2000);
                var count = response.cart_total;
                if (count > 0) { $('#cart-count').text(count).show(); } else { $('#cart-count').hide(); }
            }
        }
    });
});
</script>

<script>
// Fix anchor scroll offset for fixed navbar
(function() {
    var navbarHeight = document.querySelector('nav, .navbar, header')
        ? document.querySelector('nav, .navbar, header').offsetHeight : 90;
    document.documentElement.style.scrollPaddingTop = navbarHeight + 'px';
    document.addEventListener('click', function(e) {
        var link = e.target.closest('a[href]');
        if (!link) return;
        var href = link.getAttribute('href');
        if (!href) return;
        var hashMatch = href.match(/(^#|[^#]*#)([^?]+)$/);
        if (!hashMatch) return;
        var id = hashMatch[2];
        var target = document.getElementById(id);
        if (!target) return;
        e.preventDefault();
        var top = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
        window.scrollTo({top: top, behavior: 'smooth'});
        history.pushState(null, '', '#' + id);
    });
    if (window.location.hash) {
        setTimeout(function() {
            var id = window.location.hash.slice(1);
            var target = document.getElementById(id);
            if (target) {
                var top = target.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                window.scrollTo({top: top, behavior: 'smooth'});
            }
        }, 100);
    }
})();
</script>
