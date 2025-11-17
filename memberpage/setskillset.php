<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){ exit; }

$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
COnline::OnlineSet( $cUser );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

CInput::GetInstance()->BuildFrom( IN_POST );

$ChaNum = CInput::GetInstance()->GetValueInt( "chanum" , IN_POST );
if ( $ChaNum == 0 )
    exit;

$cSkillSet = new CSkillSet;
$cSkillSet->LoadSkillData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
if ( $cSkillSet->SkillPoint > 0 )
{
	if ( $cSkillSet->SkillPoint > $cUser->GetUserPoint() )
		die( "พ้อยของคุณไม่เพียงพอที่จะทำรายการ" );
		
	$cUser->DownPoint( $cSkillSet->SkillPoint );
	CGlobal::SetSesUser( serialize( $cUser ) );
}

$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );

$ChaClass =  0;
$szTemp = sprintf("SELECT ChaClass FROM ChaInfo WHERE ChaNum = %d" , $ChaNum );
$cNeoSQLConnectODBC->QueryRanGame($szTemp);
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$ChaClass =  $cNeoSQLConnectODBC->Result( "ChaClass" , ODBC_RETYPE_INT );
}

if ( $ChaClass != 0 )
{
	$DataSkill = NULL;
	$cChaSkill = new CNeoChaSkill;
	
	for( $i = 0 ; $i < $cSkillSet->SkillNum ; $i ++ )
	{
		if ( $cSkillSet->SkillClass[ $i ] != $ChaClass ) continue;
		
		$cChaSkill->AddSkill( $cSkillSet->SkillMain[ $i ] , $cSkillSet->SkillSub[ $i ] , $cSkillSet->SkillLevel[ $i ] );
		
		//printf( "%d//%d//%d\n" , $cSkillSet->SkillMain[ $i ] , $cSkillSet->SkillSub[ $i ] , $cSkillSet->SkillLevel[ $i ] );
	}
	
	//$szTemp = sprintf("UPDATE ChaInfo SET ChaSkill = 0x%s WHERE ChaNum = %d" , $DataSkill , $ChaNum );
	//$cNeoSQLConnectODBC->QueryRanGame($szTemp);
	$cChaSkill->UpdateDB( $cChaSkill->GetBuffer(), $ChaNum );
	echo "เพิ่มสกิวสำเร็จ";
}else{
	echo "ไม่พบตัวละคร";
}

$cNeoSQLConnectODBC->CloseRanGame();
?>
