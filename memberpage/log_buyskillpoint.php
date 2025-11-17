<?php
//include("logon.php");
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
?>
<table width="700" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
  <td>
	<div align="center"><b>ประวัติการซื้อแต้มสกิว แสดงล่าสุด 20 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="700" border="0" cellpadding="0" cellspacing="3">
  <tr>
    <td width="62" align="center"><strong>วัน/เวลา</strong></td>
    <td width="73" align="center"><strong>ตัวละคร</strong></td>
    <td width="87" align="center"><strong>แต้มสกิวก่อนซื้อ</strong></td>
    <td width="96" align="center"><strong>แต้มสกิวหลังซื้อ</strong></td>
    <td width="75" align="center"><strong>แต้มสกิวที่ได้รับ</strong></td>
    <td width="73" align="center"><strong>พ้อยก่อนซื้อ</strong></td>
    <td width="73" align="center"><strong>พ้อยหลังซื้อ</strong></td>
    <td width="73" align="center"><strong>ราคาที่ซื้อ</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT TOP 20 * FROM Log_BuySkillPoint WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

$cNeoSQL = new CNeoSQLConnectODBC;
$cNeoSQL->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$ChaNum = $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT);
	$OldSkillPoint = $cNeoSQLConnectODBC->Result("OldSkillPoint",ODBC_RETYPE_INT);
	$NewSkillPoint = $cNeoSQLConnectODBC->Result("NewSkillPoint",ODBC_RETYPE_INT);
	$GetSkillPoint = $cNeoSQLConnectODBC->Result("GetSkillPoint,ODBC_RETYPE_INT");
	$OldPoint = $cNeoSQLConnectODBC->Result("OldPoint",ODBC_RETYPE_INT);
	$NewPoint = $cNeoSQLConnectODBC->Result("NewPoint",ODBC_RETYPE_INT);
	$DelPoint = $cNeoSQLConnectODBC->Result("DelPoint",ODBC_RETYPE_INT);
	$LogDate = substr( $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG) , 0 , 16 );
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
    <td align="center"><?php echo $OldSkillPoint; ?></td>
    <td align="center"><?php echo $NewSkillPoint; ?></td>
    <td align="center"><?php echo $GetSkillPoint; ?></td>
    <td align="center"><?php echo $OldPoint; ?></td>
    <td align="center"><?php echo $NewPoint; ?></td>
    <td align="center"><?php echo $DelPoint; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
$cNeoSQL->CloseRanGame();
?>
</table>