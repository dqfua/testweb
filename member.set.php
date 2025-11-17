<?php
header('Content-Type: text/html; charset=windows-874');
$close_string = sprintf(
						"
						<title>Shop-center.</title>
						<div align=center><font color=red><b>
						กำลังปรับปรุงระบบ, ถึงเวลา 03.30น<br>
						</b></font></div></body></html>
						"
						);
if ( !defined("CLOSEWEBSITE") )
define("CLOSEWEBSITE",false);
if ( CLOSEWEBSITE )
{
	if ( !defined("DEBUG") )
		die($close_string);
	else
	{
		if ( DEBUG == false )
		die($close_string);
	}
}

//check logon
//if databasev user is online true
//will clear session for user
//CGlobal::CheckLogOn( CGlobal::GetSesUser() );
?>