<?php
//session_start();
include("loader.php");
$simplecapchar = new SimpleCaptcha;
$simplecapchar->nSize = $_CONFIG["RESELLITEM"]["SIZE"];
$simplecapchar->SeassionName = $_CONFIG["RESELLITEM"]["SESSION"];
$simplecapchar->Result();
?>