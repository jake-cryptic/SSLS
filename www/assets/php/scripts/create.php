<?php
require("../../init.php");
require("../../../../db.php");

if (!empty($_POST)){
	$errors = array();
	
	if (!isset($_POST["create_email"])) $errors[] = "Missing email";
	if (!isset($_POST["create_username"])) $errors[] = "Missing username";
	if (!isset($_POST["create_pass"])) $errors[] = "Missing password";
	if (!isset($_POST["create_passconf"])) $errors[] = "Missing password confirmation";
	if (!isset($_POST["create"])) $errors[] = "Missing token (reload page)";
	
	if (count($errors) == 0){
		$user_em = $_POST["create_email"];
		$user_un = $_POST["create_username"];
		$user_ps = $_POST["create_pass"];
		$user_pc = $_POST["create_passconf"];
		$user_tk = $_POST["create"];
		
		if ($user_tk != $_SESSION["csrf_token"]){
			// This will detect and stop CSRF attacks
			die("Invalid Session");
		}
		
		if (filter_var($user_em,FILTER_VALIDATE_EMAIL) === false){
			$errors[] = "Not a valid email";
		} else {
			$db = new AbsoluteDouble\SSLS\Database($config);
			$db->Connect();
			$query = $db->Run("SELECT * FROM ssls WHERE email='" . $user_em . "'");
			if ($query->num_rows > 0){
				$errors[] = "Email in use";
			}
			$db->Disconnect();
			unset($db);
		}
		
		if (!preg_match('/^[a-zA-Z0-9_]+$/', $user_un)){
			$errors[] = "Username contains invalid characters";
		} else {
			$db = new AbsoluteDouble\SSLS\Database($config);
			$db->Connect();
			$query = $db->Run("SELECT * FROM ssls WHERE username='" . $user_un . "'");
			if ($query->num_rows > 0){
				$errors[] = "Username in use";
			}
			$db->Disconnect();
			unset($db);
		}
		
		
		if (strlen($user_un) < 4) $errors[] = "Username too short";
		if (strlen($user_un) > 99) $errors[] = "Username too long";
		if (strlen($user_em) > 254) $errors[] = "Email too long";
		
		if ($user_ps !== $user_pc) $errors[] = "Passwords don't match";
		if (!preg_match('@[A-Z]@', $user_ps)) $errors[] = "Password must contain an upper case letter";
		if (!preg_match('@[a-z]@', $user_ps)) $errors[] = "Password must contain a lower case letter";
		if (!preg_match('@[0-9]@', $user_ps)) $errors[] = "Password must contain a number";
		if (strlen($user_ps) <= 8) $errors[] = "Password must be longer than 8 characters";
		
		if (count($errors) == 0){
			// All went okay, let's create the account
			$hashedPassword = password_hash($user_ps,PASSWORD_BCRYPT,array("cost" => 12));
			$user_salt = GenerateSalt();
			
			$db_con = new AbsoluteDouble\SSLS\Database($config);
			$db_obj = $db_con->Connect();
			
			$db_stmt = $db_obj->prepare("INSERT INTO ssls (password, email, salt, username) VALUES (?, ?, ?, ?)");
			$db_stmt->bind_param("ssss",$hashedPassword,$user_em,$user_salt,$user_un);
			
			if (!$db_stmt->execute()){
				die("Failed to create account");
			}
			
			$db_stmt->close();
			$db_con->Disconnect();
			echo "success";
		} else {
			CycleErrors($errors);
		}
	} else {
		CycleErrors($errors);
	}
}