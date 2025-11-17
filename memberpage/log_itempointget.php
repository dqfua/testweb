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
	<div align="center"><b>ประวัติ ของรางวัลเมื่อเติมพ้อย แสดงล่าสุด 50 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="62" align="center"><strong>วัน/เวลา</strong></td>
    <td width="73" align="center"><strong>ชื่อไอเทม</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$cNeoSQLConnectODBC2 = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC2->ConnectRanWeb();
$szTemp = sprintf("SELECT TOP 50 * FROM Log_SysItemPointGet WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
	$ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
	$LogDate = substr( $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG) , 0 , 16 );
	
	$szTemp = sprintf( "SELECT ItemName FROM ItemProject WHERE ItemMain = %d AND ItemSub = %d AND MemNum = %d ORDER BY ItemNum DESC" , $ItemMain , $ItemSub , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
	$ItemName = "";
	while( $cNeoSQLConnectODBC2->FetchRow() )
	{
		$ItemName = $cNeoSQLConnectODBC2->Result("ItemName",ODBC_RETYPE_THAI);
	}
?>
  <tr>
    <td align="center"><?php echo $LogDate; ?></td>
    <td align="center"><?php echo $ItemName; ?></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
$cNeoSQLConnectODBC2->CloseRanWeb();
?>
</table>