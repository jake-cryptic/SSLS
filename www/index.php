<?php
require("./assets/init.php");

if (CheckLogin()){
	header("Location: " . $base . "login");
	die();
} else {
	PermissionLevel(0);
}

?>
Logged in as <?php echo $_SESSION["ua_username"]; ?>, <a href="assets/php/scripts/logout.php">Logout?</a>