<?php
include( "loader.php" );

//if ( HostDomainChecking( "HOSTLINK_CONTROLPANEL" ) ) die( "ERROR 404" );

if ( CGlobal::GetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] ) != NULL )
{
    $cCpLogin = unserialize( CGlobal::GetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] ) );
    if ( CCPLogin::CheckLogin($cCpLogin) )
        CGlobal::gopageQ("home.php");
    else
        CGlobal::gopageQ("login.php");
}else{
    CGlobal::gopageQ("login.php");
}
?>
