<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){ printf("ERROR|LOGIN"); exit;}

CInput::GetInstance()->BuildFrom( IN_GET );

$gamenum = CInput::GetInstance()->GetValueInt( "gamenum" , IN_GET );
$itemmain = CInput::GetInstance()->GetValueInt( "itemmain" , IN_GET );
$itemsub = CInput::GetInstance()->GetValueInt( "itemsub" , IN_GET );
$itemsub = CInput::GetInstance()->GetValueInt( "itemsub" , IN_GET );
$usernum = CInput::GetInstance()->GetValueInt( "usernum" , IN_GET );
$gameserial = CInput::GetInstance()->GetValueInt( "gameserial" , IN_GET );
$seclogin = CInput::GetInstance()->GetValueInt( "seclogin" , IN_GET );

if ( $seclogin == 0 || $seclogin != $cUser->SecCodeLogin ) { printf("ERROR|LOGINHACK"); exit; }

if ( $usernum != $cUser->GetUserNum() ) exit;

if ( empty($gameserial) ) exit;

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf( "SELECT SendItemBank FROM GameLogSerial WHERE GameNum = %d",$gamenum );
$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
$bSendItemBank = 1;
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$bSendItemBank = $cNeoSQLConnectODBC->Result( "SendItemBank" , ODBC_RETYPE_INT );
}
if ( $bSendItemBank != 0 ) printf( "ERROR|SENDTOITEMBANKALREADY" ); else
{
	$szTemp = sprintf( "INSERT INTO GameLogItemBank( GameNum , MemNum , UserNum , ItemMain , ItemSub ) VALUES ( %d , %d , %d , %d , %d ) "
																											   , $gamenum
																											   ,$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
																											   ,$cUser->GetUserNum()
																											   ,$itemmain
																											   ,$itemsub
																											   );
	$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
	$cRanShop = new CRanShop;
	$cRanShop->BuyItem( $cUser->GetUserID() , $itemmain , $itemsub );
	printf("SUCCESS|SENDOK");
}
$cNeoSQLConnectODBC->CloseRanWeb();
?>