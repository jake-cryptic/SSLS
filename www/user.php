<?php
require("./assets/init.php");
require("../db.php");

PermissionLevel(false); // Doesn't require any special permissions

if (!isset($_GET["id"])){
	$e = array("MSG"=>"Profile not found");
	require("assets/errors/error.php");
	die();
}

if (CheckUserExists($_GET["id"],$config)){
	$Details = GetUserDetails($_GET["id"],$config);
} else {
	$e = array("MSG"=>"Profile not found");
	require("assets/errors/error.php");
	die();
}
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		
		<title><?php echo $Details["ACNAME"]; ?> | Profile</title>
		<?php require("assets/php/includes/head.php"); ?>
	
	</head>
	<body class="bg">
		Section not ready
	</body>
</html>