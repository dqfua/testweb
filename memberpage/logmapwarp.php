<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
?>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
  <td>
	<div align="center"><b>ประวัติการย้ายแมพ แสดงล่าสุด 20 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="62" align="center"><strong>วัน/เวลา</strong></td>
    <td width="73" align="center"><strong>ตัวละคร</strong></td>
    <td width="87" align="center"><strong>ย้ายไปแมพ</strong></td>
    <td width="96" align="center"><strong>ราคาย้าย</strong></td>
    <td width="75" align="center"><strong>พ้อยก่อนย้าย</strong></td>
    <td width="73" align="center"><strong>พ้อยหลังย้าย</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT TOP 20 * FROM Log_MapWarp WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

$cNeoSQL = new CNeoSQLConnectODBC;
$cNeoSQL->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$LogNum = $cNeoSQLConnectODBC->Result("LogNum",ODBC_RETYPE_INT);
	$ChaNum = $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT);
	$GoMap = $cNeoSQLConnectODBC->Result("GoMap",ODBC_RETYPE_INT);
	$MapPoint = $cNeoSQLConnectODBC->Result("MapPoint",ODBC_RETYPE_INT);
	$OldPoint = $cNeoSQLConnectODBC->Result("OldPoint",ODBC_RETYPE_INT);
	$NewPoint = $cNeoSQLConnectODBC->Result("NewPoint",ODBC_RETYPE_INT);
	$LogDate = substr( $cNeoSQLConnectODBC->Result("CreateDate",ODBC_RETYPE_ENG) , 0 , 16 );
	$ChaName = "";
	if ( $ChaNum > 0 )
	{
		$szTemp = sprintf("SELECT ChaName FROM ChaInfo WHERE ChaNum = %d",$ChaNum);
		$cNeoSQL->QueryRanGame($szTemp);
		$ChaName = $cNeoSQL->Result("ChaName",ODBC_RETYPE_THAI);
	}
?>
  <tr>
    <td align="center"><?php echo $LogDate; ?></td>
    <td align="center"><?php echo $ChaName; ?></td>
    <td align="center"><?php echo $GoMap; ?></td>
    <td align="center"><?php echo $MapPoint; ?></td>
    <td align="center"><?php echo $OldPoint; ?></td>
    <td align="center"><?php echo $NewPoint; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
$cNeoSQL->CloseRanGame();
?>
</table>