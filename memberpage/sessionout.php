<?php
//echo CGlobal::GetSesLoginOut();
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
if ( !CSessionNeo::checksessionout( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , CGlobal::GetSesLoginOut() , $cUser->LogIP ) )
{
	$cUser->Clear();
	COnline::OnlineSet( $cUser );
	CGlobal::SetSesUserLogin(OFFLINE);
	CGlobal::gopageQ("?");
	exit;
}
?>