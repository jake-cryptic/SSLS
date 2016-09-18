<?php
require("php/config.php");

//error_reporting($config->debug_level);

$base = ScriptBase();

// Array ( [lifetime] => 0 [path] => / [domain] => [secure] => [httponly] => 1 )
session_set_cookie_params(0,"/","",false,true);
session_name("SSLS_ID");
session_start();

GenerateCSRF();