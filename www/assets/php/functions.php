<?php
function CheckLogin() {
	if (isset($_SESSION["ua_uid"]))
		return false;
	else
		return true;
}

function PermissionLevel($required) {
	if ($required == false) return true;
	
	if (!isset($_SESSION["ua_account_type"]) || $_SESSION["ua_account_type"] < $required){
		die("Access denied");
	}
}

function ScriptBase() {
	$pArr = explode("/",$_SERVER["PHP_SELF"]);
	array_pop($pArr);
	return implode("/",$pArr) . "/";
}

function ShowError($error,$config) {
	switch($error){
		case "Database":
			include("assets/errors/db.php");
			break;
		default:
			break;
	}
}

function GenerateSalt() {
	$num = mt_rand(2,192) * (microtime() + time());
	$salt = hash("sha512",uniqid() . $num);
	return $salt;
}

function GenerateCSRF() {
	if (!isset($_SESSION["csrf_token"]) && empty($_SESSION["csrf_token"])) {
		$_SESSION["csrf_token"] = hash("sha256",uniqid() . time());
	}
}

function CycleErrors($errors){
	foreach ($errors as $e){
		echo $e . "<br />";
	}
	die();
}

// Functions to get user information

function CheckUserExists($username,$config){
	$db_con = new AbsoluteDouble\SSLS\Database($config);
	$db_obj = $db_con->Connect();
	
	$db_stmt = $db_obj->prepare("SELECT active FROM ssls WHERE username = ?");
	$db_stmt->bind_param("s",$username);
	
	if (!$db_stmt->execute()){
		return false;
	}
	if ($db_stmt->get_result()->num_rows == 0){
		return false;
	}
	
	$db_stmt->close();
	$db_con->Disconnect();
	
	return true;
}

function GetUserDetails($username,$config){
	$db_con = new AbsoluteDouble\SSLS\Database($config);
	$db_obj = $db_con->Connect();
	
	$db_stmt = $db_obj->prepare("SELECT active,account_type,sign_up,last_login,username FROM ssls WHERE username = ?");
	$db_stmt->bind_param("s",$username);
	
	$db_stmt->execute();
	$db_stmt->store_result();
	$num_row = $db_stmt->num_rows;

	$db_stmt->bind_result($usr_act, $usr_typ, $usr_sgn, $usr_lgn, $usr_nme);

	while ($db_stmt->fetch()) {
		$_USER = array(
			"ACTIVE"=>$usr_act,
			"ACTYPE"=>$usr_typ,
			"ACSIGN"=>$usr_sgn,
			"ACLLGN"=>$usr_lgn,
			"ACNAME"=>$usr_nme
		);
	}
	
	$db_stmt->free_result();
	$db_stmt->close();
	$db_con->Disconnect();
	
	return $_USER;
}