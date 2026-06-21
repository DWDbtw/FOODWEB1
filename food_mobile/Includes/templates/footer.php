		<?php
		// Ensure restaurant contact variables exist to avoid notices
		if (!isset($restaurant_address) || !isset($restaurant_email) || !isset($restaurant_phonenumber)) {
		    $restaurant_address = isset($restaurant_address) ? $restaurant_address : '';
		    $restaurant_email = isset($restaurant_email) ? $restaurant_email : '';
		    $restaurant_phonenumber = isset($restaurant_phonenumber) ? $restaurant_phonenumber : '';
		    // Try to load from DB if $con is available
		    if (empty($restaurant_address) || empty($restaurant_email) || empty($restaurant_phonenumber)) {
		        if (isset($con)) {
		            try {
		                $stmt = $con->prepare("SELECT option_name, option_value FROM website_settings WHERE option_name IN ('restaurant_address','restaurant_email','restaurant_phonenumber')");
		                $stmt->execute();
		                $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
		                if (empty($restaurant_address) && isset($rows['restaurant_address'])) $restaurant_address = $rows['restaurant_address'];
		                if (empty($restaurant_email) && isset($rows['restaurant_email'])) $restaurant_email = $rows['restaurant_email'];
		                if (empty($restaurant_phonenumber) && isset($rows['restaurant_phonenumber'])) $restaurant_phonenumber = $rows['restaurant_phonenumber'];
		            } catch (Exception $e) {
		                // ignore DB errors here, keep variables empty
		            }
		        }
		    }
		}
		?>

	</div> <!-- .site-content -->

	<!-- FOOTER BOTTOM -->

	<section class="widget_section" style="background-color: #222227;padding: 100px 0;">
	        <div class="container">
	            <div class="row">
	                <div class="col-lg-3 col-md-6">
	                    <div class="footer_widget">
	                        <img src="Design/images/restaurant-logo.png" alt="Restaurant Logo" style="width: 150px;margin-bottom: 20px;">
	                        <p>
	                            Наш суши-ресторан - один из лучших, предлагаем свежие суши и роллы. Вы можете забронировать столик или заказать еду.
	                        </p>
	                        <ul class="widget_social">
	                            <li><a href="https://t.me/DWDbtw" target="_blank"><i class="fab fa-telegram fa-2x"></i></a></li>
	                            <li><a href="https://vk.com/dwdbtw" target="_blank"><i class="fab fa-vk fa-2x"></i></a></li>
	                            <li><a href="https://www.instagram.com/dwdbtw" target="_blank"><i class="fab fa-instagram fa-2x"></i></a></li>
	                        </ul>
	                    </div>
	                </div>
	                <div class="col-lg-3 col-md-6">
	                     <div class="footer_widget">
	                        <h3>Главный офис</h3>
	                        <p>
	                            <?php echo $restaurant_address; ?>
	                        </p>
	                        <p>
	                            <?php echo $restaurant_email; ?>
	                            <br>
	                            <?php echo $restaurant_phonenumber; ?>   
	                        </p>
	                     </div>
	                </div>
	                <div class="col-lg-3 col-md-6">
	                    <div class="footer_widget">
	                        <h3>
	                            Часы Работы
	                        </h3>
	                        <ul class="opening_time">
	                            <li>Понедельник - Пятница 11:30 - 22:00</li>
	                            <li>Суббота - Воскресенье 12:00 - 23:00</li>
	                        </ul>
	                    </div>
	                </div>
	                <div class="col-lg-3 col-md-6">
	                    <div class="footer_widget">
	                        <h3>Подпишитесь на наши новости</h3>
	                        <div class="subscribe_form">
	                            <form action="#" class="subscribe_form" novalidate="true">
	                                <input type="email" name="EMAIL" id="subs-email" class="form_input" placeholder="Email адрес...">
	                                <button type="submit" class="submit">ПОДПИСАТЬСЯ</button>
	                                <div class="clearfix"></div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
			</div>
		</section>



	<!-- INCLUDE JS SCRIPTS -->

	<script src="Design/js/jquery.min.js"></script>
	<script src="Design/js/bootstrap.min.js"></script>
	<script src="Design/js/bootstrap.bundle.min.js"></script>
	<script src="Design/js/main.js"></script>

    </div> <!-- .site-root -->

	</body>

	<!-- END BODY TAG -->

</html>

<!-- END HTML TAG -->