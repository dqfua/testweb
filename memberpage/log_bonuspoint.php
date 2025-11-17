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
	<div align="center"><b>ประวัติแต้มสะสม แสดงล่าสุด 20 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="62" align="center"><strong>วัน/เวลา</strong></td>
    <td width="73" align="center"><strong>รหัสบัตร</strong></td>
    <td width="87" align="center"><strong>จำนวน</strong></td>
    <td width="96" align="center"><strong>แต้มก่อนได้รับ</strong></td>
    <td width="75" align="center"><strong>แต้มหลังจากได้รับ</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT LogDate,SerialPassword,BonusPrice,BeforeBonusPoint,NewBonusPoint FROM Log_UserBonusPoint WHERE MemNum = %d AND UserNum = %d AND SerialPassword != ''" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$LogDate = $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG);
	$SerialPassword = $cNeoSQLConnectODBC->Result("SerialPassword",ODBC_RETYPE_ENG);
	$BonusPrice = $cNeoSQLConnectODBC->Result("BonusPrice",ODBC_RETYPE_INT);
	$BeforeBonusPoint = $cNeoSQLConnectODBC->Result("BeforeBonusPoint",ODBC_RETYPE_INT);
	$NewBonusPoint = $cNeoSQLConnectODBC->Result("NewBonusPoint",ODBC_RETYPE_INT);
?>
  <tr>
    <td align="center"><?php echo $LogDate; ?></td>
    <td align="center"><?php echo $SerialPassword; ?></td>
    <td align="center"><?php echo $BonusPrice; ?></td>
    <td align="center"><?php echo $BeforeBonusPoint; ?></td>
    <td align="center"><?php echo $NewBonusPoint; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
?>
</table>
