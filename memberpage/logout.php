<?php
CGlobal::gopage("?");
//$bLogin = CGlobal::GetSesUserLogin();
$cUser = NULL;
$bLogin = COnline::OnlineGoodCheck( $cUser );
if ( $bLogin == ONLINE )
{
	$cUser->Clear();
	COnline::OnlineSet( $cUser );
	CGlobal::SetSesUserLogin(OFFLINE);
}
//session_destroy();
echo "ออกจากระบบเรียบร้อย....ขอบคุณที่ใช้บริการ";
?>