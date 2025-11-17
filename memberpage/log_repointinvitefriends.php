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
	<div align="center"><b>ประวัติโบนัสพ้อยที่ได้รับจาก Invite Friends แสดงล่าสุด 20 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="62" align="center"><strong>วัน/เวลา</strong></td>
    <td width="73" align="center"><strong>จากไอดี</strong></td>
    <td width="87" align="center"><strong>จำนวน</strong></td>
    <td width="96" align="center"><strong>พ้อยก่อนได้รับ</strong></td>
    <td width="75" align="center"><strong>พ้อยหลังจากได้รับ</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT
      [FromUserID]
      ,[Amount]
      ,[BeforePoint]
      ,[AfterPoint]
      ,[LogDate]
  FROM [BBSAsiaGame].[dbo].[Log_RePointInviteFriends]
  WHERE MemNum = %d AND UserNum = %d
  ORDER BY LogNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$LogDate = substr( $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG) , 0 , 16 );
	$FromUserID = $cNeoSQLConnectODBC->Result("FromUserID",ODBC_RETYPE_ENG);
	$Amount = $cNeoSQLConnectODBC->Result("Amount",ODBC_RETYPE_INT);
	$BeforePoint = $cNeoSQLConnectODBC->Result("BeforePoint",ODBC_RETYPE_INT);
	$AfterPoint = $cNeoSQLConnectODBC->Result("AfterPoint",ODBC_RETYPE_INT);
	$FromUserID = UserInfo::GetUserIDFromUserNum( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $FromUserID );
?>
  <tr>
    <td align="center"><?php echo $LogDate; ?></td>
    <td align="center"><?php echo $FromUserID; ?></td>
    <td align="center"><?php echo $Amount; ?></td>
    <td align="center"><?php echo $BeforePoint; ?></td>
    <td align="center"><?php echo $AfterPoint; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
?>
</table>