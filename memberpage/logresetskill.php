<?php
//include("logon.php");
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

//$pCha = unserialize( CGlobal::GetSes( CGlobal::GetSesChaMan() ) );

$pCha = NULL;
if ( CChaOnline::OnlineGoodCheck( $pCha ) != ONLINE ) { exit; }

$ChaNum = $pCha->GetChaNum();
?>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
  <td>
	<div align="center"><b>ประวัติการรีสกิว แสดงล่าสุด 20 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="93" align="center"><strong>วัน/เวลา</strong></td>
    <td width="191" align="center"><strong>ตัวละคร</strong></td>
    <td width="191" align="center"><strong>พ้อยก่อนเปลี่ยน</strong></td>
    <td width="191" align="center"><strong>พ้อยหลังเปลี่ยน</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();

$cNeoSQL = new CNeoSQLConnectODBC;
$cNeoSQL->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );

$szTemp = sprintf("SELECT TOP 20 * FROM Log_ResetSkill WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum,$ChaNum);

$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$LogNum = $cNeoSQLConnectODBC->Result("LogNum",ODBC_RETYPE_INT);
	$ChaNum = $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT);
	
	$OldPoint = $cNeoSQLConnectODBC->Result("OldPoint",ODBC_RETYPE_INT);
	$NewPoint = $cNeoSQLConnectODBC->Result("NewPoint",ODBC_RETYPE_INT);
	
	$LogDate = substr( $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG) , 0 , 16 );
?>
  <tr>
    <td align="center"><?php echo $LogDate; ?></td>
    <td align="center"><?php echo $pCha->GetChaName(); ?></td>
    <td align="center"><?php echo $OldPoint; ?></td>
    <td align="center"><?php echo $NewPoint; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
$cNeoSQL->CloseRanGame();
?>
</table>