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
    WHERE MemberNum = %d" , $membernum );
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
$bWork = false;
while( $cNeoSQLConnectODBC->FetchRow() )
{
    $MemID = $cNeoSQLConnectODBC->Result("MemID",ODBC_RETYPE_ENG);
    $bWork = true;
}
if ( $bWork )
{
    $szTemp = sprintf( "UPDATE MemberInfo SET MemDelete = 0 WHERE MemberNum = %d" , $membernum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    printf( "เรียกไอดี : <font color='#00FF00'><b>%s</b></font> เข้าสู่ระบบเรียบร้อยแล้ว" , $MemID );
}
$cNeoSQLConnectODBC->CloseRanWeb();
CGlobal::gopage( "home.php?pid=finduser" );
?>
