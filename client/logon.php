<?php
$bLogin = CGlobal::GetSesUserLogin();
if ( $bLogin == OFFLINE )
{
	exit;
}
?>