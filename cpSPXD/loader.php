<?php
session_start();
header('Content-Type: text/html; charset=windows-874');
//global loader
include( "../global.loader.php" );
include( "../lang.loader.php" );
//load configure head file
include( "include/config.inc.php" );
//include ajax function
include("../ajax.loader.php");
include("../admin.ajax.loader.php");
//load class header file
include( "class/login.class.php" );
?>
