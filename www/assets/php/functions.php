<?php
function CheckLogin() {
	if (isset($_SESSION["ua_uid"]))
		return false;
	else
		return true;
}

function PermissionLevel($required) {
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