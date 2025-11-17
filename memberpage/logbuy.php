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
	<div align="center"><b>ประวัติการซื้อไอเทม แสดงล่าสุด 20 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="99" align="center"><strong>วัน/เวลา</strong></td>
    <td width="80" align="center"><strong>ตัวละคร</strong></td>
    <td width="142" align="center"><strong>ชื่อไอเทม</strong></td>
    <td width="58" align="center"><strong>ราคา</strong></td>
    <td width="49" align="center"><strong>ชนิด</strong></td>
    <td width="75" align="center"><strong>ก่อนซื้อ</strong></td>
    <td width="73" align="center"><strong>หลังซื้อ</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT TOP 20 * FROM Log_Buy WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

$cNeoSQL = new CNeoSQLConnectODBC;
$cNeoSQL->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$LogNum = $cNeoSQLConnectODBC->Result("LogNum",ODBC_RETYPE_INT);
	$ChaNum = $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT);
	$ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
	$ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
	$ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
	$ItemPrice = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
	$ItemType = $cNeoSQLConnectODBC->Result("ItemType",ODBC_RETYPE_INT);
	$UserPoint_Before = $cNeoSQLConnectODBC->Result("UserPoint_Before",ODBC_RETYPE_INT);
	$UserPoint_New = $cNeoSQLConnectODBC->Result("UserPoint_New",ODBC_RETYPE_INT);
	$OldGameTime = $cNeoSQLConnectODBC->Result("OldGameTime",ODBC_RETYPE_INT);
	$NewGameTime = $cNeoSQLConnectODBC->Result("NewGameTime",ODBC_RETYPE_INT);
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
    <td align="center"><?php echo $ItemName; ?></td>
    <td align="center"><?php echo $ItemPrice; ?></td>
    <td align="center"><?php echo $_CONFIG["ITEMTYPE"][$ItemType]; ?></td>
    <td align="center"><?php printf( "%d พ้อย, %d นาที" , $UserPoint_Before , $OldGameTime ); ?></td>
    <td align="center"><?php printf( "%d พ้อย, %d นาที" , $UserPoint_New , $NewGameTime ); ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
$cNeoSQL->CloseRanGame();
?>
</table>