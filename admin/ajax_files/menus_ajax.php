<?php include '../connect.php'; ?>
<?php include '../Includes/functions/functions.php'; ?>
<?php include '../Includes/functions/auth.php'; ?>
<?php admin_require_role($con, array('admin','manager')); ?>

<?php

	if(isset($_POST['do_']) && $_POST['do_'] == "Delete")
	{
		$menu_id = $_POST['menu_id'];

        $stmt = $con->prepare("DELETE from menus where menu_id = ?");
        $stmt->execute(array($menu_id));
	}

?>