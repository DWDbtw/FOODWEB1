<?php 
	session_start();
	$pageTitle = 'Admin Login';

	if(isset($_SESSION['username_restaurant_qRewacvAqzA']) && isset($_SESSION['password_restaurant_qRewacvAqzA']))
	{
		header('Location: dashboard.php');
	}
?>

<!-- PHP INCLUDES -->

<?php include 'connect.php'; ?>
<?php include 'Includes/functions/functions.php'; ?>
<?php include 'Includes/templates/header.php'; ?>

	<!-- LOGIN FORM -->

	<div class="login">
		<form class="login-container validate-form" name="login-form" action="index.php" method="POST" onsubmit="return validateLoginForm()">
			<span class="login100-form-title p-b-32">
				Admin Login
			</span>
			<?php
				//Check if user click on the submit button
				if(isset($_POST['admin_login']))
				{
					$username = test_input($_POST['username']);
					$password = test_input($_POST['password']);

					// Lookup user by username
					$stmt = $con->prepare("SELECT user_id, username, password, role FROM users WHERE username = ? LIMIT 1");
					$stmt->execute(array($username));
					$userRow = $stmt->fetch(PDO::FETCH_ASSOC);

					$authenticated = false;
					if ($userRow) {
						// Support both password_hash and legacy sha1
						if (password_verify($password, $userRow['password'])) {
							$authenticated = true;
						} elseif (sha1($password) === $userRow['password']) {
							$authenticated = true;
							// Upgrade legacy hash to password_hash
							$upgradeStmt = $con->prepare("UPDATE users SET password = ? WHERE user_id = ?");
							$upgradeStmt->execute(array(password_hash($password, PASSWORD_DEFAULT), $userRow['user_id']));
						}
					}

					if ($authenticated) {
						// Only allow users with admin or manager roles into admin area
						$role = isset($userRow['role']) ? $userRow['role'] : 'client';
						if (!in_array($role, array('admin','manager'))) {
							echo "<div class='alert alert-danger'>Доступ запрещён: недостаточно прав.</div>";
						} else {
							// Set admin session keys
							$_SESSION['admin_user_id'] = $userRow['user_id'];
							$_SESSION['admin_username'] = $userRow['username'];
							$_SESSION['admin_role'] = $role;
							header('Location: dashboard.php');
							die();
						}
					} else {
						?>
							<div class="alert alert-danger">
								<button data-dismiss="alert" class="close close-sm" type="button">
									<span aria-hidden="true">×</span>
								</button>
								<div class="messages">
									<div>Username and/or password are incorrect!</div>
								</div>
							</div>
						<?php 
					}
				}
			?>

			<!-- USERNAME INPUT -->

			<div class="form-input">
				<span class="txt1">Username</span>
				<input type="text" name="username" class = "form-control username" oninput="document.getElementById('username_required').style.display = 'none'" id="user" autocomplete="off">
				<div class="invalid-feedback" id="username_required">Username is required!</div>
			</div>

			<!-- PASSWORD INPUT -->
			
			<div class="form-input">
				<span class="txt1">Password</span>
				<input type="password" name="password" class="form-control" oninput="document.getElementById('password_required').style.display = 'none'" id="password" autocomplete="new-password">
				<div class="invalid-feedback" id="password_required">Password is required!</div>
			</div>

			<!-- SIGNIN BUTTON -->
			
			<p>
				<button type="submit" name="admin_login" >Sign In</button>
			</p>

			<!-- FORGOT PASSWORD PART -->

			<span class="forgotPW">Forgot your password ? <a href="#">Reset it here.</a></span>

		</form>
	</div>

<?php include 'Includes/templates/footer.php'; ?>
