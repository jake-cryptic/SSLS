<?php
require("../../init.php");
require("../../../../db.php");

if (!empty($_POST)){
	$errors = array();
	
	if (!isset($_POST["login_email"])) $errors[] = "Missing email";
	if (!isset($_POST["login_pass"])) $errors[] = "Missing password";
	if (!isset($_POST["login"])) $errors[] = "Missing token (reload page)";
	
	if (count($errors) == 0){
		$user_em = $_POST["login_email"];
		$user_ps = $_POST["login_pass"];
		$user_tk = $_POST["login"];
		
		if ($user_tk != $_SESSION["csrf_token"]){
			// This will detect and stop CSRF attacks
			die("Invalid Session");
		}
		
		if (filter_var($user_em,FILTER_VALIDATE_EMAIL) === false){
			$errors[] = "Not a valid email";
		} else {
			$db = new AbsoluteDouble\SSLS\Database($config);
			$db->Connect();
			$query = $db->Run("SELECT password,salt FROM ssls WHERE email='" . $user_em . "'");
			if ($query->num_rows > 0){
				$data = $query->fetch_object();
				$user_db_ps 	= $data->password;
				$user_db_salt 	= $data->salt;
			} else {
				$errors[] = "Invalid email and password combination";
			}
			$db->Disconnect();
			unset($db);
		}
		
		if (count($errors) == 0){
			if (!password_verify($user_ps,$user_db_ps)){
				die("Invalid email and password combination");
			}
			
			$db_con = new AbsoluteDouble\SSLS\Database($config);
			$db_obj = $db_con->Connect();
			
			$r = $db_con->Run("SELECT user_id, active, account_type, username, sign_up, last_login, last_ip FROM ssls WHERE email='" . $user_em . "'");
			
			if ($r->num_rows != 0) {
				$data = $r->fetch_object();
				$_SESSION["ua_email"] 			= $user_em;
				$_SESSION["ua_uid"] 			= $data->user_id;
				$_SESSION["ua_active"] 			= $data->active;
				$_SESSION["ua_account_type"]	= $data->account_type;
				$_SESSION["ua_username"] 		= $data->username;
				$_SESSION["ua_sign_up"] 		= $data->sign_up;
				$_SESSION["ua_last_login"] 		= $data->last_login;
				$_SESSION["ua_last_ip"] 		= $data->last_ip;
			}
			
			$db_con->Disconnect();
			echo "success";
		} else {
			CycleErrors($errors);
		}
	} else {
		CycleErrors($errors);
	}
}