<?php
    /*
		Title Function That Echo The Page Title In Case The Page Has The Variable $pageTitle And Echo Default Title For Other Pages
	*/
	function getTitle()
	{
		global $pageTitle;
		if(isset($pageTitle))
			echo $pageTitle." | Vincent Restaurant - Ваш Ресторан";
		else
			echo "Vincent Restaurant | Ваш Ресторан";
	}

		/*
			Register a new user into `users` table and return result
			Returns array('success' => bool, 'user_id' => int|null, 'message' => string)
		*/
		function register_user($con, $username, $email, $password, $full_name = '', $phone = '', $dob = null)
		{
			// basic validation
			$username = test_input($username);
			$email = test_input($email);

			// Check for existing username or email
			$stmt = $con->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
			$stmt->execute(array($username, $email));
			if ($stmt->rowCount() > 0) {
				return array('success' => false, 'user_id' => null, 'message' => 'Username or email already exists');
			}

			$hashed = sha1($password);

			// Try to split full name into first/last
			$first = null;
			$last = null;
			if (!empty($full_name)) {
				$parts = preg_split('/\s+/', trim($full_name));
				$first = $parts[0];
				$last = isset($parts[1]) ? $parts[count($parts)-1] : null;
			}

			$insert = $con->prepare("INSERT INTO users (username, email, full_name, first_name, last_name, phone, dob, password, bonus_points, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, 'client')");
			$insert->execute(array($username, $email, $full_name, $first, $last, $phone, $dob, $hashed));
			$user_id = $con->lastInsertId();

			if ($user_id) {
				return array('success' => true, 'user_id' => (int)$user_id, 'message' => 'Registered');
			}

			return array('success' => false, 'user_id' => null, 'message' => 'Registration failed');
		}

		/* Calculate bonuses earned for an order. Default: 5% of order total */
		function calculate_order_bonuses($con, $order_total)
		{
			$rate = 0.05;
			return round($order_total * $rate, 2);
		}

		/* Add bonuses to a user's account */
		function add_bonuses($con, $user_id, $order_id, $amount)
		{
			$stmt = $con->prepare("UPDATE users SET bonus_points = bonus_points + ? WHERE user_id = ?");
			return $stmt->execute(array($amount, $user_id));
		}

		/* Spend (deduct) bonuses from a user's account */
		function spend_bonuses($con, $user_id, $order_id, $amount)
		{
			$stmt = $con->prepare("UPDATE users SET bonus_points = GREATEST(bonus_points - ?, 0) WHERE user_id = ?");
			return $stmt->execute(array($amount, $user_id));
		}

		/* Get user's current bonus balance */
		function get_user_bonuses($con, $user_id)
		{
			$stmt = $con->prepare("SELECT bonus_points FROM users WHERE user_id = ?");
			$stmt->execute(array($user_id));
			$row = $stmt->fetch();
			return $row ? (float)$row['bonus_points'] : 0;
		}
	/*
		This function returns the number of items in a given table
	*/

    function countItems($item,$table)
	{
		global $con;
		$stat_ = $con->prepare("SELECT COUNT($item) FROM $table");
		$stat_->execute();
		
		return $stat_->fetchColumn();
	}

    /*
	
	** Check Items Function
	** Function to Check Item In Database [Function with Parameters]
	** $select = the item to select [Example : user, item, category]
	** $from = the table to select from [Example : users, items, categories]
	** $value = The value of select [Example: Ossama, Box, Electronics]

	*/
	function checkItem($select, $from, $value)
	{
		global $con;
		$statment = $con->prepare("SELECT $select FROM $from WHERE $select = ? ");
		$statment->execute(array($value));
		$count = $statment->rowCount();
		
		return $count;
	}


  	/*
    	==============================================
    	TEST INPUT FUNCTION, IS USED FOR SANITIZING USER INPUTS
    	AND REMOVE SUSPICIOUS CHARS and Remove Extra Spaces
    	==============================================
	
	*/

  	function test_input($data) 
  	{
      	$data = trim($data);
      	$data = stripslashes($data);
      	$data = htmlspecialchars($data);
      	return $data;
  	}









?>

