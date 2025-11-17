<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){ printf("ERROR|LOGIN"); exit;}

CInput::GetInstance()->BuildFrom( IN_GET );

$gamenum = CInput::GetInstance()->GetValueInt( "gamenum" , IN_GET );
$seclogin = CInput::GetInstance()->GetValueInt( "seclogin" , IN_GET );

if ( $seclogin == 0 || $seclogin != $cUser->SecCodeLogin ) { printf("ERROR|LOGINHACK"); exit; }

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf( "SELECT TOP 1 PricePlay FROM GameLogSerial WHERE GameNum = %d " , $gamenum );
$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );

$PricePlay = -1;

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$PricePlay = $cNeoSQLConnectODBC->Result("PricePlay",ODBC_RETYPE_INT);
}

$NewPoint = $cUser->GetUserPoint()-$PricePlay;

$szTemp = sprintf( " UPDATE GameLogSerial SET UserPoint_New = %d , UpdateCone = getdate() , SubmitCone = 1 WHERE GameNum = %d " , $NewPoint , $gamenum );
$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );

$cUser->SetUserPoint( $NewPoint );
$cUser->UpdateUserPointToDB();
CGlobal::SetSesUser( serialize( $cUser ) );

$cNeoSQLConnectODBC->CloseRanWeb();
?>