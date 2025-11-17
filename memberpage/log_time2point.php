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
	<div align="center"><b>ประวัติ เวลาออนไลน์แลกพ้อย แสดงล่าสุด 20 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="62" align="center"><strong>วัน/เวลา</strong></td>
    <td width="73" align="center"><strong>พ้อยก่อนแลก</strong></td>
    <td width="87" align="center"><strong>พ้อยที่เปลี่ยน</strong></td>
    <td width="96" align="center"><strong>เวลาออนไลน์ (นาที)</strong></td>
    <td width="75" align="center"><strong>พ้อยที่ได้</strong></td>
    <td width="73" align="center"><strong>สำเร็จ</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT TOP 20 * FROM Log_Time2Point WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$LogNum = $cNeoSQLConnectODBC->Result("LogNum",ODBC_RETYPE_INT);
	$DateTime = substr( $cNeoSQLConnectODBC->Result("DateTime",ODBC_RETYPE_ENG) , 0 , 16 );
	$Success = $cNeoSQLConnectODBC->Result("Success",ODBC_RETYPE_INT);
	$TimePoint = $cNeoSQLConnectODBC->Result("TimePoint",ODBC_RETYPE_INT);
	$Time = $cNeoSQLConnectODBC->Result("Time",ODBC_RETYPE_INT);
	$NewPoint = $cNeoSQLConnectODBC->Result("NewPoint",ODBC_RETYPE_INT);
	$OldPoint = $cNeoSQLConnectODBC->Result("OldPoint",ODBC_RETYPE_INT);
?>
  <tr>
    <td align="center"><?php echo $DateTime; ?></td>
    <td align="center"><?php echo $OldPoint; ?></td>
    <td align="center"><?php echo $NewPoint; ?></td>
    <td align="center"><?php echo $Time; ?></td>
    <td align="center"><?php echo $TimePoint; ?></td>
    <td align="center"><?php echo $_CONFIG["SUCCESS"][$Success]; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
?>
</table>