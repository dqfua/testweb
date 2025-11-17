<?php
//include("logon.php");
$error = 0;
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) )
{
	die("<div align=center><font color=red><b>กรุณาออกจากเกมส์ก่อนใช้งานระบบนี้!!</b></font></div>");
}
//$cUser = unserialize( CGlobal::GetSesUser() );

//update user from userinfo db
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
//CGlobal::SetSesUser( serialize($cUser) );
COnline::OnlineSet( $cUser );
//$cUser = unserialize( CGlobal::GetSesUser() );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
?>
<div align="center" style="background-color:#000;">
<table width="669" cellspaceing="3" cellpadding="2" border="0">
    <TR>
        <td width="117" align="center">
            <b>ChaName</b>
        </td>
        <td width="144" align="center">
            <b>ItemName</b>
        </td>
        <td width="57" align="center">
            <b>Price</b>
        </td>
        <td width="100" align="center">
            <b>LogDate</b>
        </td>
        <td width="113" align="center">
            <b>Status</b>
        </td>
        <td width="100" align="center">
            <b>CheckDate</b>
        </td>
    </tr>
<?php
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$cNeoSQLConnectODBC2 = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC2->ConnectRanWeb();
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$cNeoSQLConnectODBC2->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
$szTemp = sprintf("
    
SELECT TOP 20 ChaNum,ItemPos,ItemMain,ItemSub,ItemInven,ChaInven,SecCode,RandNumTime,HowWork,LogDate,CheckDate
FROM Work_Resell
WHERE MemNum = %d AND UserNum = %d
ORDER BY WorkNum DESC
    
"
, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
, $UserNum
);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
while( $cNeoSQLConnectODBC->FetchRow() )
{
    $ItemName = "Unknow";
    $ChaName = "Unknow";
    $ItemPrice = 0;
    $szTemp = sprintf( "SELECT TOP 1 ItemName,ItemPrice FROM ItemProject WHERE MemNum = %d AND ItemMain = %d AND ItemSub = %d ORDER BY ItemNum DESC "
            , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
            , $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT)
            , $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT)
            );
    //echo $szTemp."<br>";
    $cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
    while( $cNeoSQLConnectODBC2->FetchRow() )
    {
        $ItemName = $cNeoSQLConnectODBC2->Result( "ItemName" , ODBC_RETYPE_THAI );
        $ItemPrice = $cNeoSQLConnectODBC2->Result( "ItemPrice",ODBC_RETYPE_INT );
    }
    $szTemp = sprintf("SELECT ChaName FROM ChaInfo WHERE ChaNum = %d" , $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT) );
    $cNeoSQLConnectODBC2->QueryRanGame($szTemp);
    while( $cNeoSQLConnectODBC2->FetchRow() )
    {
        $ChaName = $cNeoSQLConnectODBC2->Result( "ChaName" , ODBC_RETYPE_THAI );
    }
    $CheckDate = substr( $cNeoSQLConnectODBC->Result("CheckDate",ODBC_RETYPE_ENG) , 0 , 16 );
    if ( empty($CheckDate) ) $CheckDate = "Unknow";
?>
    <TR>
        <td align="center">
            <b><?php echo $ChaName; ?></b>
        </td>
        <td align="center">
            <b><?php echo $ItemName; ?></b>
        </td>
        <td align="center">
            <b><?php echo $ItemPrice; ?></b>
        </td>
        <td align="center">
            <b><?php echo substr( $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG) , 0 , 16 ); ?></b>
        </td>
        <td align="center">
            <b><?php echo $_CONFIG["STATUS"]["RESELL"][ $cNeoSQLConnectODBC->Result("HowWork",ODBC_RETYPE_INT) ] ?></b>
        </td>
        <td align="center">
            <b><?php echo $CheckDate; ?></b>
        </td>
    </tr>
<?php
}
$cNeoSQLConnectODBC2->CloseRanWeb();
$cNeoSQLConnectODBC2->CloseRanGame();
$cNeoSQLConnectODBC->CloseRanWeb();
?>
</table>
</div>