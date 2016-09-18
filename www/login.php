<?php
require("assets/init.php");

if (!CheckLogin()){
	header("Location: " . $config->base . "?e=1");
	die();
}
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		
		<title>Login/Sign Up</title>
		<?php require("assets/php/includes/head.php"); ?>
	
	</head>
	<body class="bg">
	
		<div id="form_container">
			<?php
			if (isset($_GET["from"]) && $_GET["from"] == "logout"){
				echo '<div id="form_message" class="msg_waiting">You have been logged out</div>';
			} else {
				echo '<div id="form_message" class="hidden">No message</div>';
			}
			?>
			<div id="login_tab" class="form_container_tab">Login</div>
			<div id="create_tab" class="form_container_tab form_container_tab_a">Create</div>
			
			<div id="login_form">
				<h1>Please Login</h1><br />
				<form id="login_form">
					<input type="text" id="login_email" name="login_email" placeholder="Email" class="textbox" required /><br />
					<input type="password" id="login_pass" name="login_pass" placeholder="Password" class="textbox" required /><br />
					<input type="hidden" id="login" name="login" value="<?php echo $_SESSION["csrf_token"]; ?>" /><br />
					<input type="submit" id="submit_login" class="button" value="Login" />
				</form>
			</div>
			<div id="create_form" class="hidden">
				<h1>Create an account</h1><br />
				<form id="create_form">
					<input type="text" id="create_email" name="create_email" placeholder="Email" class="textbox" required /><br />
					<input type="password" id="create_pass" name="create_pass" placeholder="Password" class="textbox" required /><br />
					<input type="password" id="create_passconf" name="create_passconf" placeholder="Confirm Password" class="textbox" required /><br />
					<input type="hidden" id="create" name="create" value="<?php echo $_SESSION["csrf_token"]; ?>" /><br />
					<input type="submit" id="submit_create" class="button" value="Create" />
				</form>
			</div>
		</div>
		
		<script type="text/javascript" crossorigin="anonymous" integrity="sha256-ihAoc6M/JPfrIiIeayPE9xjin4UWjsx2mjW/rtmxLM4=" src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
		<script src="<?php echo $config->default_jsdir; ?>login.js" type="text/javascript"></script>
	
	</body>
</html>