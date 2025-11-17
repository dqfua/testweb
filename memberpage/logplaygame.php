<?php
//include("logon.php");
//$cUser = unserialize( CGlobal::GetSesUser() );
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
?>
<table width="700" border="0" cellpadding="0" cellspacing="3">
  <tr>
  <td>
	<div align="center"><b>ประวัติการเล่นเกมบน ItemShop แสดงล่าสุด 50 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="700" border="0" cellpadding="0" cellspacing="3">
  <tr>
    <td width="118" align="center"><strong>วัน/เวลา</strong></td>
    <td width="215" align="center"><strong>รหัสเกม(พ้อยที่ใช้ในการเล่น)</strong></td>
    <td width="154" align="center"><strong>จำนวนรางวัลที่ได้รับ</strong></td>
    <td width="105" align="center"><strong>พ้อยก่อนเล่น</strong></td>
    <td width="90" align="center"><strong>พ้อยหลังเล่น</strong></td>
  </tr>
<?php
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT TOP 50 * FROM GameLogSerial WHERE MemNum = %d AND UserNum = %d AND SubmitGood = 1 ORDER BY GameNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$DateTime = substr( $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG) , 0 , 16 );
	$GameSerial = $cNeoSQLConnectODBC->Result("GameSerial",ODBC_RETYPE_ENG);
	$PricePlay = $cNeoSQLConnectODBC->Result("PricePlay",ODBC_RETYPE_INT);
	$ItemBonus = $cNeoSQLConnectODBC->Result("ItemBonus",ODBC_RETYPE_INT);
	$ItemBonus_Max = $cNeoSQLConnectODBC->Result("ItemBonus_Max",ODBC_RETYPE_INT);
	$UserPoint_New = $cNeoSQLConnectODBC->Result("UserPoint_New",ODBC_RETYPE_INT);
	$UserPoint_Old = $cNeoSQLConnectODBC->Result("UserPoint_Old",ODBC_RETYPE_INT);
?>
  <tr>
    <td align="center"><?php echo $DateTime; ?></td>
    <td align="center"><?php printf("%s(%d)",$GameSerial,$PricePlay) ?></td>
    <td align="center"><?php printf("%d(%d)",$ItemBonus,$ItemBonus_Max) ?></td>
    <td align="center"><?php echo $UserPoint_Old; ?></td>
    <td align="center"><?php echo $UserPoint_New; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
?>
</table>