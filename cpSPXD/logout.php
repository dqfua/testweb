<?php
include("loader.php");
if ( CGlobal::GetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] ) != NULL )
{
    $cCpLogin = unserialize( CGlobal::GetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] ) );
    if ( CCPLogin::CheckLogin($cCpLogin) )
        $cCpLogin->Logout();
}
CGlobal::gopage("index.php");
echo "ขอบคุณที่ใช้บริการค่ะ<br>";
?>
