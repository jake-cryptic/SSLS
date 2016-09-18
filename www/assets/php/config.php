<?php
require("functions.php");

$config = (object)array(
	"debug" => true,
	"debug_level" => 5,
	
	"base" => "/",
	"default_css" => "assets/css/default.css",
	"default_jsdir" => "assets/js/",
	
	"meta" => array(
		"charset" => "UTF-8",
		"author" => "SSLS 2016"
	)
);

global $config;