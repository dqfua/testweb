<?php
if ( !defined("SHOPNEOCP") ) die("HACKING....");

CInput::GetInstance()->BuildFrom( IN_GET );
$membernum = CInput::GetInstance()->GetValueInt( "membernum" , IN_GET );

if ( $membernum == 0 ) die("ERROR|MEMBER|FAIL");
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf( "SELECT
    MemberNum,MemID,ServerName,EMail,MemType,ServerType,Reg_ShopFolder,Reg_DateOpen,Reg_DateOpenEnd,MemBan
    FROM MemberInfo
    WHERE MemberNum = %d AND MemDelete = 0" , $membernum );
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
$bWork = false;
while( $cNeoSQLConnectODBC->FetchRow() )
{
    $MemID = $cNeoSQLConnectODBC->Result("MemID",ODBC_RETYPE_ENG);
    $bWork = true;
}
if ( $bWork )
{
	$szTemp = sprintf("UPDATE MemSQL SET RanGame_IP = '',RanGame_User = '',RanGame_Pass = '',RanGame_DB = '',RanUser_IP = '',RanUser_User = '',RanUser_Pass = '',RanUser_DB = '',RanShop_IP = '',RanShop_User = '',RanShop_Pass = '',RanShop_DB = '' WHERE MemNum = %d" , $membernum);
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	
    $szTemp = sprintf( "UPDATE MemberInfo SET MemDelete = 1 WHERE MemberNum = %d" , $membernum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    printf( "ลบไอดี : <font color='#FF0000'><b>%s</b></font> เรียบร้อยแล้ว" , $MemID );
}
$cNeoSQLConnectODBC->CloseRanWeb();
CGlobal::gopage( "home.php?pid=finduser" );
?>
