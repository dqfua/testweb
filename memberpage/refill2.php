<?php
include("logon.php");
if ( !CSec::Check() ) exit;
//$bLogin = CGlobal::GetSesUserLogin();
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserNum = $cUser->GetUserNum();

CInput::GetInstance()->BuildFrom( IN_POST );

$Serial = @CNeoInject::sec_Int2( CInput::GetInstance()->GetValueString( "serial" , IN_POST ) );
if ( strlen( $Serial ) != 14 ) die("รูปแบบรหัสบัตรไม่ถูกต้อง<br>");
$Tmpay = new TmPay;
if ($Tmpay->misc_parsestring($Serial,'0123456789') == FALSE || strlen($Serial) != 14)
{
	echo 'รหัสบัตรเงินสดที่ระบุมีรูปแบบที่ไม่ถูกต้อง<br />';
}
else if ($Tmpay->refill_countcards('WHERE SerialTruemoney = \'' . $Serial . '\' ') == 1)
{
	echo 'รหัสบัตรเงินสดที่ระบุ ถูกใช้งานไปแล้ว<br />';
}
else if ($Tmpay->refill_countcards('WHERE MemNum = ' . $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] . ' AND  UserNum = '.$UserNum.' AND Status = 0') >= 3)
{
	echo 'ท่านยังมีรหัสบัตรเงินสดที่รอการตรวจสอบอยู่<br />';
}
/*ADD NEW BEGIN*/
else if ($Tmpay->refill_countcards('WHERE MemNum = ' . $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] . ' AND  UserNum = '.$UserNum.' AND SerialTruemoney = \'' . $Serial . '\' AND Status = 0') >= 1)
{
	echo 'รหัสบัตรนี้กำลังรอการตรวจสอบ<br>';
}
else if ($Tmpay->refill_countcards('WHERE MemNum = ' . $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] . ' AND  UserNum = '.$UserNum.' AND SerialTruemoney = \'' . $Serial . '\' ') >= 1)
{
	echo 'รหัสบัตรนี้ถูกใช้งานไปแล้ว<br>';
}
/*ADD NEW END*/
else if ($Tmpay->refill_countcards('WHERE MemNum = ' . $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] . ' AND  UserNum = '.$UserNum.' AND Status > 1 AND RefillDate > (GETDATE()-1)') >= 5)
{
	echo 'ท่านเติมเงินผิดหลายครั้ง ระบบระงับการเติมเงินเป็นเวลา 24 ชั่วโมง<br />';
}
else
{
	if ( $UserNum <= 0 )
	{
		$cUser->Clear();
		COnline::OnlineSet( $cUser );
		CGlobal::SetSesUserLogin(OFFLINE);
		die("กรุณาล็อกอินใหม่!!");
	}
	if(($tmpay_ret = $Tmpay->refill_sendcard( $UserNum , $Serial , $cUser->GetUserType() )) !== TRUE)
	{
		//echo 'ขออภัย ขณะนี้ระบบ TMPAY.NET ขัดข้อง กรุณาติดต่อเจ้าหน้าที่ (Error: ' . $tmpay_ret . ')<br />';
		echo 'ขออภัย ขณะนี้ระบบ ขัดข้อง กรุณาลองเติมบัตรอีกครั้งในภายหลัง<br />';
	}
	else
	{
		echo 'ได้รับข้อมูลบัตรเงินสดเรียบร้อย กรุณารอการตรวจสอบจากระบบ<br />';
	}
}
?>