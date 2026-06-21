<?php
    //Set page title
    $pageTitle = 'Бронирование столика в суши-ресторане';

    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";


?>
    
    <style type="text/css">
        .table_reservation_section
        {
            max-width: 850px;
            margin: 50px auto;
            min-height: 500px;
        }

        .check_availability_submit
        {
            background: #AFC4D5;
            color: white;
            border-color: #AFC4D5;
            font-family: work sans,sans-serif;
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
        .text_header
        {
            margin-bottom: 5px;
            font-size: 18px;
            font-weight: bold;
            line-height: 1.5;
            margin-top: 22px;
            text-transform: capitalize;
        }
        .layer
        {
            height: 100%;
            background: -moz-linear-gradient(top, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
            background: -webkit-linear-gradient(top, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
            background: linear-gradient(to bottom, rgba(45,45,45,0.4) 0%, rgba(45,45,45,0.9) 100%);
        }

        /* RESERVATION TABS STYLES */
        .reservation_tab
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

        .step.finish 
        {
            background-color: #4CAF50;
        }

        .error_div
        {
            padding: 15px;
            margin-top: 20px;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            text-align: center;
        }

        .success_div
        {
            padding: 15px;
            margin-top: 20px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
        }

        .confirmation_info
        {
            background: white;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
            box-shadow: 0 0 5px 0 rgba(60, 66, 87, 0.04);
        }

        .confirmation_row
        {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
        }

        .confirmation_row:last-child
        {
            border-bottom: none;
        }

        .confirmation_label
        {
            font-weight: bold;
            color: #666;
        }

        .confirmation_value
        {
            color: #333;
        }
    </style>

    <!-- START ORDER FOOD SECTION -->

    <section style="
    background: url(Design/images/food_pic.jpg);
    background-position: center bottom;
    background-repeat: no-repeat;
    background-size: cover;">
        <div class="layer">
            <div style="text-align: center;padding: 15px;">
                <h1 style="font-size: 120px; color: white;font-family: 'Roboto'; font-weight: 100;
">ЗАБРОНИРОВАТЬ СТОЛИК</h1>
            </div>
        </div>
        
    </section>

	<section class="table_reservation_section">

        <div class="container">
            <?php

            if(isset($_POST['submit_table_reservation_form']) && $_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $selected_date = !empty($_POST['selected_date']) ? $_POST['selected_date'] : $_POST['reservation_date'];
                $selected_time = !empty($_POST['selected_time']) ? $_POST['selected_time'] : $_POST['reservation_time'];
                $desired_date = trim($selected_date . " " . $selected_time);
                $number_of_guests = intval($_POST['number_of_guests']);
                $table_id = intval($_POST['table_id']);
                $client_full_name = test_input($_POST['client_full_name']);
                $client_phone_number = test_input($_POST['client_phone_number']);
                $client_email = test_input($_POST['client_email']);

                if (empty($client_full_name) || empty($client_phone_number) || empty($client_email) || empty($desired_date) || $table_id <= 0) {
                    echo "<div class='alert alert-danger' style='margin-top: 20px;'>";
                        echo "Ошибка: требуется корректная информация для бронирования. Попробуйте ещё раз.";
                    echo "</div>";
                } else {
                    $con->beginTransaction();
                    try
                    {
                        $stmtClient = $con->prepare("INSERT INTO clients(client_name,client_phone,client_email) VALUES(?,?,?)");
                        $stmtClient->execute(array($client_full_name,$client_phone_number,$client_email));
                        $client_id = $con->lastInsertId();

                        $stmt_reservation = $con->prepare("INSERT INTO reservations(date_created, client_id, selected_time, nbr_guests, table_id) VALUES(?, ?, ?, ?, ?)");
                        $stmt_reservation->execute(array(date("Y-m-d H:i:s"), $client_id, $desired_date, $number_of_guests, $table_id));

                        echo "<div class='alert alert-success' style='margin-top: 20px;'>";
                            echo "<h4>Отлично! Ваше бронирование успешно создано.</h4>";
                            echo "<p><strong>Дата и время:</strong> " . htmlspecialchars($desired_date) . "</p>";
                            echo "<p><strong>Количество человек:</strong> " . htmlspecialchars($number_of_guests) . "</p>";
                            echo "<p><strong>Контактный телефон:</strong> " . htmlspecialchars($client_phone_number) . "</p>";
                            echo "<p style='margin-top: 15px;'><a href='index.php' class='btn btn-success'>Вернуться на главную</a></p>";
                        echo "</div>";

                        $con->commit();
                    }
                    catch(Exception $e)
                    {
                        $con->rollBack();
                        echo "<div class = 'alert alert-danger'>";
                            echo htmlspecialchars($e->getMessage());
                        echo "</div>";
                    }
                }
            }
            ?>

            <form method="POST" id="reservation_form" action="table-reservation.php">

                <!-- TAB 1: SELECT DATE AND TIME -->

                <div class="reservation_tab" id="tab_datetime">
                    <div class="text_header">
                        <span>1. Выберите дату и время</span>
                    </div>
                    <div style="background: white; padding: 20px; border-radius: 4px;">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="reservation_date">Дата</label>
                                    <input type="date" id="reservation_date" name="reservation_date" 
                                    min="<?php echo date('Y-m-d',strtotime("+1day")); ?>"
                                    value="<?php echo date('Y-m-d',strtotime("+1day")); ?>"
                                    class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="reservation_time">Время</label>
                                    <input type="time" id="reservation_time" name="reservation_time" 
                                    value="<?php echo date('H:i'); ?>" 
                                    class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="number_of_guests">Сколько человек?</label>
                                    <select class="form-control" id="number_of_guests" name="number_of_guests" required>
                                        <option value="">-- Выберите количество --</option>
                                        <option value="1">1 человек</option>
                                        <option value="2">2 человека</option>
                                        <option value="3">3 человека</option>
                                        <option value="4">4 человека</option>
                                        <option value="5">5 человек</option>
                                        <option value="6">6 человек</option>
                                        <option value="7">7 человек</option>
                                        <option value="8">8 человек</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: CHECK AVAILABILITY AND SELECT TABLE -->

                <div class="reservation_tab" id="tab_availability">
                    <div class="text_header">
                        <span>2. Проверка доступности</span>
                    </div>
                    <div id="availability_content" style="background: white; padding: 20px; border-radius: 4px;">
                        <p style="text-align: center; color: #999;">Нажмите "Далее" для проверки доступности</p>
                    </div>
                    <input type="hidden" id="selected_date" name="selected_date" value="">
                    <input type="hidden" id="selected_time" name="selected_time" value="">
                    <input type="hidden" id="table_id" name="table_id" value="">
                </div>

                <!-- TAB 3: CLIENT DETAILS -->

                <div class="reservation_tab" id="tab_client">
                    <div class="text_header">
                        <span>3. Ваши данные</span>
                    </div>
                    <div class="client_details_tab" style="background: white; padding: 20px; border-radius: 4px;">
                        <div class="form-group colum-row row">
                            <div class="col-sm-12">
                                <input type="text" name="client_full_name" id="client_full_name" 
                                oninput="document.getElementById('required_fname').style.display = 'none'" 
                                onkeyup="this.value=this.value.replace(/[^\sa-zA-Zа-яА-ЯёЁ]/g,'');" 
                                class="form-control" placeholder="Полное имя" required>
                                <div class="invalid-feedback" id="required_fname" style="display:none;">
                                    Неверное имя!
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <input type="email" name="client_email" id="client_email" 
                                oninput="document.getElementById('required_email').style.display = 'none'" 
                                class="form-control" placeholder="Email" required>
                                <div class="invalid-feedback" id="required_email" style="display:none;">
                                    Неверный Email!
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <input type="text" name="client_phone_number" id="client_phone_number" 
                                oninput="document.getElementById('required_phone').style.display = 'none'" 
                                class="form-control" onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" 
                                placeholder="Номер телефона" required>
                                <div class="invalid-feedback" id="required_phone" style="display:none;">
                                    Неверный номер телефона!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 4: CONFIRMATION -->

                <div class="reservation_tab" id="tab_confirmation">
                    <div class="text_header">
                        <span>4. Подтверждение</span>
                    </div>
                    <div class="confirmation_info">
                        <div class="confirmation_row">
                            <span class="confirmation_label">Дата и время:</span>
                            <span class="confirmation_value" id="confirm_datetime">--</span>
                        </div>
                        <div class="confirmation_row">
                            <span class="confirmation_label">Количество человек:</span>
                            <span class="confirmation_value" id="confirm_guests">--</span>
                        </div>
                        <div class="confirmation_row">
                            <span class="confirmation_label">Имя:</span>
                            <span class="confirmation_value" id="confirm_name">--</span>
                        </div>
                        <div class="confirmation_row">
                            <span class="confirmation_label">Email:</span>
                            <span class="confirmation_value" id="confirm_email">--</span>
                        </div>
                        <div class="confirmation_row">
                            <span class="confirmation_label">Телефон:</span>
                            <span class="confirmation_value" id="confirm_phone">--</span>
                        </div>
                    </div>
                </div>

                <!-- NAVIGATION BUTTONS -->

                <div style="overflow:auto;padding: 30px;">
                    <div style="float:right;">
                        <input type="hidden" name="submit_table_reservation_form">
                        <button type="button" class="next_prev_buttons" style="background-color: #bbbbbb;" id="prevBtn" onclick="changeTab(-1)">Назад</button>
                        <button type="button" id="nextBtn" class="next_prev_buttons" onclick="changeTab(1)">Далее</button>
                    </div>
                </div>

                <div style="text-align:center;margin-top:40px;">
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                    <span class="step"></span>
                </div>

            </form>

        </div>
    </section>

    <!-- FOOTER BOTTOM  -->

    <?php include "Includes/templates/footer.php"; ?>

    <!-- JAVASCRIPT FOR RESERVATION TABS -->

    <script type="text/javascript">
        var currentTab = 0;

        window.addEventListener('load', function() {
            showTab(currentTab);
        });

        function showTab(n) 
        {
            var x = document.getElementsByClassName("reservation_tab");
            if (n >= x.length) return false;
            if (n < 0) return false;
            
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
                document.getElementById("nextBtn").innerHTML = "Забронировать";
            } 
            else 
            {
                document.getElementById("nextBtn").innerHTML = "Далее";
            }

            fixStepIndicator(n);
        }

        function ValidateEmail(mail)
        {
            var regex = /^[\w.-]+@[\w.-]+\.[A-Za-z]{2,}$/;
            return regex.test(mail);
        }

        function changeTab(n)
        {
            var x = document.getElementsByClassName("reservation_tab");
            
            // Hide current tab
            x[currentTab].style.display = "none";
            
            // Moving forward
            if (n == 1) {
                if (!validateTab(currentTab)) return false;
                
                // If on tab 0 (date/time), check availability before moving
                if (currentTab == 0) {
                    checkAvailability();
                    return false;
                }
                
                // If on tab 2 (client), update confirmation
                if (currentTab == 2) {
                    updateConfirmation();
                }
            }
            
            currentTab = currentTab + n;
            
            // If reached the end, submit form
            if (currentTab >= x.length) 
            {
                document.getElementById("reservation_form").submit();
                return false;
            }

            showTab(currentTab);
        }

        function validateTab(tabIndex)
        {
            var x = document.getElementsByClassName("reservation_tab");
            var tab = x[tabIndex];
            var valid = true;

            if (tabIndex == 0) {
                // Validate date/time fields
                var date = document.getElementById('reservation_date').value;
                var time = document.getElementById('reservation_time').value;
                var guests = document.getElementById('number_of_guests').value;

                if (!date || !time || !guests) {
                    alert('Пожалуйста, заполните все поля: дата, время и количество человек');
                    valid = false;
                }
            }

            if (tabIndex == 2) {
                // Validate client details
                var name = document.getElementById('client_full_name').value;
                var email = document.getElementById('client_email').value;
                var phone = document.getElementById('client_phone_number').value;

                if (!name) {
                    alert('Пожалуйста, введите ваше имя');
                    valid = false;
                }
                if (!email) {
                    alert('Пожалуйста, введите ваш email');
                    valid = false;
                } else if (!ValidateEmail(email)) {
                    alert('Пожалуйста, введите корректный email');
                    valid = false;
                }
                if (!phone) {
                    alert('Пожалуйста, введите ваш номер телефона');
                    valid = false;
                }
            }

            return valid;
        }

        function checkAvailability()
        {
            var date = document.getElementById('reservation_date').value;
            var time = document.getElementById('reservation_time').value;
            var guests = document.getElementById('number_of_guests').value;

            // Store values in hidden fields for form submission
            document.getElementById('selected_date').value = date;
            document.getElementById('selected_time').value = time;

            // Show loading state
            document.getElementById('availability_content').innerHTML = '<div style="padding: 20px; text-align: center;">Проверка доступности...</div>';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'check_table_availability.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onload = function() {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success && response.table_id) {
                        document.getElementById('table_id').value = response.table_id;
                        var content = '<div style="padding: 20px; text-align: center;">';
                        content += '<p style="color: #4CAF50; font-size: 18px; font-weight: bold;">✓ Столик доступен!</p>';
                        content += '<p><strong>Дата:</strong> ' + date + '</p>';
                        content += '<p><strong>Время:</strong> ' + time + '</p>';
                        content += '<p><strong>Количество человек:</strong> ' + guests + '</p>';
                        content += '</div>';
                        document.getElementById('availability_content').innerHTML = content;

                        currentTab++;
                        showTab(currentTab);
                    } else {
                        var content = '<div class="error_div" style="padding: 20px; text-align: center; font-weight: bold;">';
                        content += '✗ Извините, нет доступных столиков на эту дату и время.';
                        content += '<br>Пожалуйста, вернитесь и выберите другую дату или время.';
                        content += '</div>';
                        content += '<div style="text-align: center; margin-top: 15px;">';
                        content += '<button type="button" class="next_prev_buttons" style="background-color: #bbbbbb;" onclick="goBackToDate()">Вернуться и выбрать другую дату</button>';
                        content += '</div>';

                        document.getElementById('availability_content').innerHTML = content;
                        showTab(currentTab);
                    }
                } catch(e) {
                    console.error('Error:', e);
                    document.getElementById('availability_content').innerHTML = '<div class="error_div" style="padding: 20px; text-align: center; font-weight: bold;">✗ Ошибка при проверке доступности. Попробуйте еще раз.</div>';
                    showTab(currentTab);
                }
            };

            xhr.send('date=' + encodeURIComponent(date) + '&time=' + encodeURIComponent(time) + '&guests=' + encodeURIComponent(guests));
        }

        function updateConfirmation()
        {
            var date = document.getElementById('selected_date').value;
            var time = document.getElementById('selected_time').value;
            var guests = document.getElementById('number_of_guests').value;
            var name = document.getElementById('client_full_name').value;
            var email = document.getElementById('client_email').value;
            var phone = document.getElementById('client_phone_number').value;

            document.getElementById('confirm_datetime').textContent = date + ' ' + time;
            document.getElementById('confirm_guests').textContent = guests + ' человек';
            document.getElementById('confirm_name').textContent = name;
            document.getElementById('confirm_email').textContent = email;
            document.getElementById('confirm_phone').textContent = phone;
        }

        function goBackToDate() {
            document.getElementById('table_id').value = '';
            document.getElementById('availability_content').innerHTML = '';
            currentTab = 0;
            showTab(currentTab);
        }

        function fixStepIndicator(n) 
        {
            var i, x = document.getElementsByClassName("step");
            
            for (i = 0; i < x.length; i++) 
            {
                x[i].className = x[i].className.replace(" active", "");
            }
            
            x[n].className += " active";
        }
    </script>

