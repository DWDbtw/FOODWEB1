<?php
    session_start();
    //Set page title
    $pageTitle = 'Бронирование столика в суши-ресторане';

    include "connect.php";
    include 'Includes/functions/functions.php';
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";


?>
    
    <style>
        :root {
            --rv-accent: #7B5CF0;
            --rv-accent-light: #EDE9FB;
            --rv-border: #E8E8E8;
            --rv-bg: #F7F7F8;
            --rv-text: #1A1A1A;
            --rv-secondary: #6B6B6B;
            --rv-radius: 14px;
        }

        body { background: var(--rv-bg); }

        .rv-hero {
            background: url(Design/images/food_pic.jpg) center/cover no-repeat;
            position: relative;
            min-height: 220px;
            display: flex;
            align-items: flex-end;
        }
        .rv-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(10,8,20,.35), rgba(10,8,20,.72));
        }
        .rv-hero-inner {
            position: relative;
            z-index: 1;
            padding: 40px 40px 36px;
        }
        .rv-hero-inner h1 {
            font-size: 38px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.5px;
            margin: 0 0 6px;
        }
        .rv-hero-inner p {
            color: rgba(255,255,255,.65);
            font-size: 15px;
            margin: 0;
        }

        .rv-wrap {
            max-width: 580px;
            margin: 40px auto 80px;
            padding: 0 20px;
        }

        /* step bar */
        .rv-steps {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
            gap: 0;
        }
        .rv-step {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }
        .rv-step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #E8E8E8;
            color: #999;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: background .2s, color .2s;
        }
        .rv-step.active .rv-step-dot, .rv-step.done .rv-step-dot {
            background: var(--rv-accent);
            color: #fff;
        }
        .rv-step-label {
            font-size: 12px;
            font-weight: 600;
            color: #aaa;
            white-space: nowrap;
        }
        .rv-step.active .rv-step-label, .rv-step.done .rv-step-label {
            color: var(--rv-accent);
        }
        .rv-step-line {
            flex: 1;
            height: 2px;
            background: #E8E8E8;
            margin: 0 6px;
            border-radius: 2px;
        }
        .rv-step.done ~ .rv-step-line, .rv-step.active ~ .rv-step-line { background: var(--rv-accent-light); }

        /* card */
        .rv-card {
            background: #fff;
            border-radius: var(--rv-radius);
            border: 1px solid var(--rv-border);
            padding: 28px;
            margin-bottom: 16px;
        }
        .rv-card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--rv-text);
            margin: 0 0 20px;
        }

        /* inputs */
        .rv-field { margin-bottom: 16px; }
        .rv-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--rv-secondary);
            margin-bottom: 7px;
        }
        .rv-input {
            width: 100%;
            padding: 13px 14px;
            border: 1.5px solid var(--rv-border);
            border-radius: 10px;
            font-size: 15px;
            color: var(--rv-text);
            background: #fff;
            outline: none;
            transition: border-color .15s;
            box-sizing: border-box;
        }
        .rv-input:focus { border-color: var(--rv-accent); }
        .rv-row { display: flex; gap: 14px; }
        .rv-row .rv-field { flex: 1; }

        /* availability result */
        .rv-avail-ok {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px;
            background: #E6F9F0;
            border-radius: 10px;
            color: #1A8A4A;
            font-size: 15px;
            font-weight: 600;
        }
        .rv-avail-ok span { font-size: 22px; }
        .rv-avail-err {
            padding: 18px;
            background: #FFF0F0;
            border-radius: 10px;
            color: #C0392B;
            font-size: 14px;
            text-align: center;
        }

        /* confirm rows */
        .rv-confirm-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 0;
            border-bottom: 1px solid var(--rv-border);
            font-size: 14px;
        }
        .rv-confirm-row:last-child { border-bottom: none; }
        .rv-confirm-label { color: var(--rv-secondary); }
        .rv-confirm-value { font-weight: 600; color: var(--rv-text); }

        /* buttons */
        .rv-btn-row {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 8px;
        }
        .rv-btn {
            padding: 13px 28px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: opacity .15s;
        }
        .rv-btn:hover { opacity: .88; }
        .rv-btn-back { background: #F2F2F2; color: var(--rv-text); }
        .rv-btn-next { background: var(--rv-accent); color: #fff; }

        /* reservation tab hidden by default */
        .reservation_tab { display: none; }

        /* success card */
        .rv-success {
            background: #fff;
            border-radius: var(--rv-radius);
            border: 1px solid var(--rv-border);
            padding: 32px 28px;
        }
        .rv-success-head {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 24px;
        }
        .rv-success-icon {
            width: 48px;
            height: 48px;
            background: var(--rv-accent-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
            color: var(--rv-accent);
        }
        .rv-success-title { font-size: 19px; font-weight: 800; color: var(--rv-text); margin: 0 0 3px; }
        .rv-success-sub { font-size: 13px; color: var(--rv-secondary); margin: 0; }
    </style>

    <div class="rv-hero">
        <div class="rv-hero-inner">
            <h1>Забронировать столик</h1>
            <p>Выберите дату, время и укажите свои данные</p>
        </div>
    </div>

    <section class="rv-wrap">
        <div class="rv-steps" id="rv_steps">
            <div class="rv-step active" id="rvs0"><div class="rv-step-dot">1</div><div class="rv-step-label">Дата</div></div>
            <div class="rv-step-line"></div>
            <div class="rv-step" id="rvs1"><div class="rv-step-dot">2</div><div class="rv-step-label">Доступность</div></div>
            <div class="rv-step-line"></div>
            <div class="rv-step" id="rvs2"><div class="rv-step-dot">3</div><div class="rv-step-label">Данные</div></div>
            <div class="rv-step-line"></div>
            <div class="rv-step" id="rvs3"><div class="rv-step-dot">4</div><div class="rv-step-label">Подтверждение</div></div>
        </div>

        <div>
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

            <!-- TAB 1: DATE & TIME -->
            <div class="reservation_tab" id="tab_datetime">
                <div class="rv-card">
                    <div class="rv-card-title">Выберите дату и время</div>
                    <div class="rv-row">
                        <div class="rv-field">
                            <label class="rv-label">Дата</label>
                            <input type="date" id="reservation_date" name="reservation_date"
                                min="<?php echo date('Y-m-d',strtotime("+1day")); ?>"
                                value="<?php echo date('Y-m-d',strtotime("+1day")); ?>"
                                class="rv-input" required>
                        </div>
                        <div class="rv-field">
                            <label class="rv-label">Время</label>
                            <input type="time" id="reservation_time" name="reservation_time"
                                value="<?php echo date('H:i'); ?>"
                                class="rv-input" required>
                        </div>
                    </div>
                    <div class="rv-field">
                        <label class="rv-label">Количество человек</label>
                        <select class="rv-input" id="number_of_guests" name="number_of_guests" required>
                            <option value="">— Выберите —</option>
                            <?php foreach([1,2,3,4,5,6,7,8] as $n): ?>
                            <option value="<?= $n ?>"><?= $n ?> <?= $n==1?'человек':($n<5?'человека':'человек') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- TAB 2: AVAILABILITY -->
            <div class="reservation_tab" id="tab_availability">
                <div class="rv-card">
                    <div class="rv-card-title">Проверка доступности</div>
                    <div id="availability_content" style="color:#999;text-align:center;padding:10px 0;">Нажмите «Далее» для проверки</div>
                </div>
                <input type="hidden" id="selected_date" name="selected_date" value="">
                <input type="hidden" id="selected_time" name="selected_time" value="">
                <input type="hidden" id="table_id" name="table_id" value="">
            </div>

            <!-- TAB 3: CLIENT DETAILS -->
            <div class="reservation_tab" id="tab_client">
                <div class="rv-card">
                    <div class="rv-card-title">Ваши данные</div>
                    <div class="rv-field">
                        <label class="rv-label">Полное имя</label>
                        <input type="text" name="client_full_name" id="client_full_name"
                            class="rv-input" placeholder="Иван Иванов" required>
                    </div>
                    <div class="rv-row">
                        <div class="rv-field">
                            <label class="rv-label">Email</label>
                            <input type="email" name="client_email" id="client_email"
                                class="rv-input" placeholder="mail@example.com" required>
                        </div>
                        <div class="rv-field">
                            <label class="rv-label">Телефон</label>
                            <input type="text" name="client_phone_number" id="client_phone_number"
                                class="rv-input" placeholder="89001234567"
                                onkeyup="this.value=this.value.replace(/[^0-9]/g,'');" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 4: CONFIRMATION -->
            <div class="reservation_tab" id="tab_confirmation">
                <div class="rv-card">
                    <div class="rv-card-title">Подтверждение бронирования</div>
                    <div class="rv-confirm-row"><span class="rv-confirm-label">Дата и время</span><span class="rv-confirm-value" id="confirm_datetime">—</span></div>
                    <div class="rv-confirm-row"><span class="rv-confirm-label">Гостей</span><span class="rv-confirm-value" id="confirm_guests">—</span></div>
                    <div class="rv-confirm-row"><span class="rv-confirm-label">Имя</span><span class="rv-confirm-value" id="confirm_name">—</span></div>
                    <div class="rv-confirm-row"><span class="rv-confirm-label">Email</span><span class="rv-confirm-value" id="confirm_email">—</span></div>
                    <div class="rv-confirm-row"><span class="rv-confirm-label">Телефон</span><span class="rv-confirm-value" id="confirm_phone">—</span></div>
                </div>
            </div>

            <!-- NAV BUTTONS -->
            <div class="rv-btn-row">
                <input type="hidden" name="submit_table_reservation_form">
                <button type="button" class="rv-btn rv-btn-back" id="prevBtn" onclick="changeTab(-1)" style="display:none;">Назад</button>
                <button type="button" class="rv-btn rv-btn-next" id="nextBtn" onclick="changeTab(1)">Далее</button>
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

