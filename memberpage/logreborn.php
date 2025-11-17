<?php
//include("logon.php");
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
?>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
  <td>
	<div align="center"><b>ประวัติ จุติ แสดงล่าสุด 30 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="62" align="center"><strong>วัน/เวลา</strong></td>
    <td width="73" align="center"><strong>ตัวละคร</strong></td>
    <td width="87" align="center"><strong>จุติเก่า</strong></td>
    <td width="96" align="center"><strong>จุติใหม่</strong></td>
    <td width="75" align="center"><strong>เวลาจุติ</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();

$cNeoSQL = new CNeoSQLConnectODBC;
$cNeoSQL->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
$szTemp = sprintf("SELECT ChaName,ChaNum FROM ChaInfo WHERE ChaNum = %d",$ChaNum);
$cNeoSQL->QueryRanGame($szTemp);
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$ChaName[ $cNeoSQL->Result("ChaNum",ODBC_RETYPE_INT) ] = $cNeoSQL->Result("ChaName",ODBC_RETYPE_THAI);
}

$szTemp = sprintf("SELECT TOP 30 * FROM Log_Reborn WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC"
				  ,$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum,$ChaNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$LogNum = $cNeoSQLConnectODBC->Result("LogNum",ODBC_RETYPE_INT);
	$OldReborn = $cNeoSQLConnectODBC->Result("OldReborn",ODBC_RETYPE_INT);
	$Reborn = $cNeoSQLConnectODBC->Result("Reborn",ODBC_RETYPE_INT);
	$LogDate = substr( $cNeoSQLConnectODBC->Result("RebornDate",ODBC_RETYPE_ENG) , 0 , 16 );
?>
  <tr>
    <td align="center"><?php echo $LogDate; ?></td>
    <td align="center"><?php echo $ChaName[$ChaNum]; ?></td>
    <td align="center"><?php echo $OldReborn; ?></td>
    <td align="center"><?php echo $Reborn; ?></td>
    <td align="center"><?php echo $LogDate; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
$cNeoSQL->CloseRanGame();
?>
</table>