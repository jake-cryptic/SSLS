<?php
require("assets/init.php");

if (CheckLogin()){
	header("Location: " . $config->base . "login");
	die();
} else {
	PermissionLevel(1);
}

?>
You are an admin