<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){ printf("ERROR|LOGIN"); exit;}

CInput::GetInstance()->BuildFrom( IN_GET );

$gamenum = CInput::GetInstance()->GetValueInt( "gamenum" , IN_GET );
$itembonus = CInput::GetInstance()->GetValueInt( "itembonus" , IN_GET );
$seclogin = CInput::GetInstance()->GetValueInt( "seclogin" , IN_GET );

if ( $seclogin == 0 || $seclogin != $cUser->SecCodeLogin ) { printf("ERROR|LOGINHACK"); exit; }

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf( "UPDATE GameLogSerial SET SendItemBank = 1 , UserPoint_New = %d , SubmitGood = 1 , UpdateSendItemBank = getdate() , ItemBonus = %d WHERE GameNum = %d"
																																   ,$cUser->GetUserPoint()
																																   ,$itembonus
																																   ,$gamenum
																																   );
$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
$cNeoSQLConnectODBC->CloseRanWeb();
?>