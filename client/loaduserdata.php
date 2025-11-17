<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){ printf("ERROR|LOGIN"); exit;}

CInput::GetInstance()->BuildFrom( IN_GET );

$gameserial = CInput::GetInstance()->GetValueString( "gameserial" , IN_GET );
$serialnumber = CInput::GetInstance()->GetValueString( "serialnumber" , IN_GET );
$price = CInput::GetInstance()->GetValueInt( "price" , IN_GET );
$itembonus_max = CInput::GetInstance()->GetValueInt( "itembonus_max" , IN_GET );

if ( $itembonus_max <= 0 ) exit;
if ( $price <= 0 ) exit;
if ( empty($serialnumber) ) exit;
if ( empty($gameserial) ) exit;

$szTemp = sprintf( "SELECT TOP 1 MemNum,GameName,GameSerial FROM GameList WHERE GameNameSerial = '%s' " , $gameserial );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );

$gameserial_md5 = "";

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$gameserial_md5 = $cNeoSQLConnectODBC->Result( "GameSerial" , ODBC_RETYPE_ENG );
}

$szTemp = sprintf( "INSERT INTO GameLogSerial( LogNumber , GameSerial , PricePlay , MemNum , UserNum , UserPoint_Old , ItemBonus_Max , SecLogin )
							VALUES( '%09d' , '%s' , %d , %d , %d , %d , %d , %d )
							"
							, $serialnumber
							, $gameserial_md5
							, $price
							, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
							,$cUser->GetUserNum()
							,$cUser->GetUserPoint()
							,$itembonus_max
							,$cUser->SecCodeLogin
						);
$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );

$szTemp = sprintf( "SELECT TOP 1 GameNum FROM GameLogSerial WHERE GameSerial = '%s' ORDER BY GameNum DESC ", $gameserial_md5 );
$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$GameNum = $cNeoSQLConnectODBC->Result( "GameNum" , ODBC_RETYPE_INT );
}

$cNeoSQLConnectODBC->CloseRanWeb();

if ( $gameserial_md5 == "" ) { printf( "ERROR|NOTFOUNDGAME" ); exit; }

printf( "UID|%s|CONE|%d|MEMNUM|%d|USERNUM|%d|GAMESERIAL|%s|GAMENUM|%d|SECCODELOGIN|%d|"
	   ,$cUser->GetUserID()
	   ,$cUser->GetUserPoint()
	   ,$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
	   ,$cUser->GetUserNum()
	   ,$gameserial_md5
	   ,$GameNum
	   ,$cUser->SecCodeLogin
	   );
?>