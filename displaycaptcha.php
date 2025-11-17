<?php
include("global.loader.php");

session_start();

$strCaptcha = CGlobal::GetSes( $_CONFIG["CAPTCHASINGLEDISPLAY"] );
if ( !strlen($strCaptcha) ) die("");

header('Content-Type: image/png');
$pCaptcha = new Captcha;
$pCaptcha->display( $strCaptcha );
?>