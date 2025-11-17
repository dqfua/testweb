<?php
session_start();
include("../global.loader.php");
echo CGlobal::GetSes( $_CONFIG["RESELLITEM"]["SESSION"] );
?>