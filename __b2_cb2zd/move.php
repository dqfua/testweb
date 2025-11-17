<html>
<head>
<title>Move System...</title>
</head>
<body>

<?php
include_once 'class/odbc.class.php';
include_once 'class/debug.log.class.php';
include_once 'config.inc.php';

ignore_user_abort(true);

$GET_UserNum = $_GET["usernum"];

if ( empty( $GET_UserNum ) ) die( "ERROR::UserNum Is empty...<br>" );

//die("Program run success already!!");

$error = 0;
$Query = NULL;

$pNeoSQL = new CNeoSQLConnectODBC();
$pNeoSQL->Connect(
        $_CONFIG["INPUT"]["RANUSER"]["HOST"]
        , $_CONFIG["INPUT"]["RANUSER"]["USER"]
        , $_CONFIG["INPUT"]["RANUSER"]["PASS"]
        , $_CONFIG["INPUT"]["RANUSER"]["DATABASE"]
        );
$pNeoSQL->Query( sprintf( "SELECT UserNum,UserID,UserPass,UserPass2,ChaRemain,UserPoint,UserEmail,UserGameOnlineTime FROM UserInfo WHERE UserNum = %d AND UserBlock = 0" , $GET_UserNum ) );
$bBegin = FALSE;
while( $pNeoSQL->FetchRow() )
{
	$Input_UserNum = $pNeoSQL->Result( "UserNum" );
	$Input_UserNum_OLD = $Input_UserNum;
	$Input_UserID = $pNeoSQL->Result( "UserID" );
	$Input_UserPass = $pNeoSQL->Result( "UserPass" );
	$Input_UserPass2 = $pNeoSQL->Result( "UserPass2" );
	$Input_ChaRemain = $pNeoSQL->Result( "ChaRemain" );
	$Input_UserPoint = $pNeoSQL->Result( "UserPoint" );
	$Input_UserEmail = $pNeoSQL->Result( "UserEmail" );
	$Input_UserGameOnlineTime = $pNeoSQL->Result( "UserGameOnlineTime" );
	CDebugLog::Write( "===========================================" );
	CDebugLog::Write( sprintf( "UserNum : %d \t UserID : %s \t UserPass : %s\t UserPass2 : %s\t ChaRemain : %d\t UserPoint : %d\t UserEmail : %s\t UserGameOnlineTime : %d" 
		   , $Input_UserNum
		   , $Input_UserID
		   , $Input_UserPass
		   , $Input_UserPass2
		   , $Input_ChaRemain
		   , $Input_UserPoint
		   , $Input_UserEmail
		   , $Input_UserGameOnlineTime
		   ) );
	$bBegin = TRUE;
}
if ( !$bBegin )
{
	$pNeoSQL->Close();
	die( "Not Found Member in data<br>" );
}
CDebugLog::Write( sprintf( "Get Data From UserInfo Done...." ) );
$pNeoSQL->Close();

$pNeoSQL->Connect(
        $_CONFIG["OUTPUT"]["RANUSER"]["HOST"]
        , $_CONFIG["OUTPUT"]["RANUSER"]["USER"]
        , $_CONFIG["OUTPUT"]["RANUSER"]["PASS"]
        , $_CONFIG["OUTPUT"]["RANUSER"]["DATABASE"]
        );
$pNeoSQL->Query( sprintf( "SELECT TOP 1 UserNum FROM UserInfo WHERE UserID = '%s'", $Input_UserID ) );
while( $pNeoSQL->FetchRow() )
	$bBegin = FALSE;
if ( !$bBegin )
{
	$bBegin = TRUE;
	$Input_UserID = $Input_UserID . NEWID;
	$pNeoSQL->Query( sprintf( "SELECT TOP 1 UserNum FROM UserInfo WHERE UserID = '%s'", $Input_UserID ) );
	while( $pNeoSQL->FetchRow() )
		$bBegin = FALSE;
	if ( !$bBegin )
	{
		$pNeoSQL->Close();
		CDebugLog::Write( sprintf( "Can not move because UserID have already in database out.." ) );
		exit;
	}
	if ( strlen( $Input_UserID ) > 19 )
	{
		$pNeoSQL->Close();
		CDebugLog::Write( sprintf( "Can not move because UserID have over length.." ) );
		exit;
	}
	CDebugLog::Write( sprintf( "Change UserID to : %s" , $Input_UserID ) );
}

$Query = $pNeoSQL->Query( sprintf( "INSERT INTO UserInfo( UserID,UserPass,UserPass2,ChaRemain,UserPoint,UserEmail,UserGameOnlineTime ) VALUES( '%s','%s','%s',%d,%d,'%s',%d )" 
																														   , $Input_UserID
																														   , $Input_UserPass
																														   , $Input_UserPass2
																														   , $Input_ChaRemain
																														   , $Input_UserPoint
																														   , $Input_UserEmail
																														   , $Input_UserGameOnlineTime
																														   ) );
if ( !$Query )
{
	$pNeoSQL->Close();
	CDebugLog::Write( sprintf( "Can not move because UserID have over length.." ) );
	exit;
}

$pNeoSQL->Query( sprintf( "SELECT UserNum FROM UserInfo WHERE UserID = '%s'" , $Input_UserID ) );
$bBegin = FALSE;
while( $pNeoSQL->FetchRow() )
{
	$Input_UserNum = $pNeoSQL->Result( "UserNum" );
	$bBegin = TRUE;
}
if ( !$bBegin )
{
	$pNeoSQL->Close();
	CDebugLog::Write( sprintf( "Can not do next process because can not get UserNum..." ) );
	exit;
}
$pNeoSQL->Close();

CDebugLog::WriteD( $Input_UserID );
CDebugLog::WriteN( $Input_UserNum );

$nCharMove = 0;
$pNeoSQL->Connect(
			$_CONFIG["INPUT"]["RANGAME"]["HOST"]
			, $_CONFIG["INPUT"]["RANGAME"]["USER"]
			, $_CONFIG["INPUT"]["RANGAME"]["PASS"]
			, $_CONFIG["INPUT"]["RANGAME"]["DATABASE"]
			);
$pNeoSQL->Query( sprintf( "SELECT
						 [SGNum]
						,[UserNum]
						,[GuNum]
						,[GuPosition]
						,[ChaName]
						,[ChaGuName]
						,[ChaTribe]
						,[ChaClass]
						,[ChaSchool]
						,[ChaSex]
						,[ChaHair]
						,[ChaHairColor]
						,[ChaFace]
						,[ChaLiving]
						,[ChaLevel]
						,[ChaMoney]
						,[ChaPower]
						,[ChaStrong]
						,[ChaStrength]
						,[ChaSpirit]
						,[ChaDex]
						,[ChaIntel]
						,[ChaStRemain]
						,[ChaExp]
						,[ChaViewRange]
						,[ChaHP]
						,[ChaMP]
						,[ChaStartMap]
						,[ChaStartGate]
						,[ChaPosX]
						,[ChaPosY]
						,[ChaPosZ]
						,[ChaSaveMap]
						,[ChaSavePosX]
						,[ChaSavePosY]
						,[ChaSavePosZ]
						,[ChaReturnMap]
						,[ChaReturnPosX]
						,[ChaReturnPosY]
						,[ChaReturnPosZ]
						,[ChaBright]
						,[ChaAttackP]
						,[ChaDefenseP]
						,[ChaFightA]
						,[ChaShootA]
						,[ChaSP]
						,[ChaPK]
						,[ChaSkillPoint]
						,[ChaInvenLine]
						,[ChaDeleted]
						,[ChaOnline]
						,[ChaQuest]
						,[ChaSkills]
						,[ChaSkillSlot]
						,[ChaActionSlot]
						,[ChaPutOnItems]
						,[ChaInven]
						,[ChaReExp]
						,[ChaSpMID]
						,[ChaSpSID]
						,[saveMoney]
						,[saveExp]
						,[itemCount]
						,[ChaCoolTime]
						,[VTAddInven]
						,[SumMain]
						,[SumSub]
						,[SumLevel]
						,[ChaCP]
						,[ChaReborn]
						 FROM ChaInfo WHERE UserNum = %d"
						 , $Input_UserNum_OLD ) );
while( $pNeoSQL->FetchRow() )
{
	$nCharMove++;
	$Input_SGNum[ $nCharMove ] = $pNeoSQL->Result( "SGNum" );
	$Input_GuNum[ $nCharMove ] = $pNeoSQL->Result( "GuNum" );
	$Input_GuPosition[ $nCharMove ] = $pNeoSQL->Result( "GuPosition" );
	$Input_ChaName[ $nCharMove ] = $pNeoSQL->Result( "ChaName" );
	$Input_ChaGuName[ $nCharMove ] = $pNeoSQL->Result( "ChaGuName" );
	$Input_ChaTribe[ $nCharMove ] = $pNeoSQL->Result( "ChaTribe" );
	$Input_ChaClass[ $nCharMove ] = $pNeoSQL->Result( "ChaClass" );
	$Input_ChaSchool[ $nCharMove ] = $pNeoSQL->Result( "ChaSchool" );
	$Input_ChaSex[ $nCharMove ] = $pNeoSQL->Result( "ChaSchool" );
	$Input_ChaHair[ $nCharMove ] = $pNeoSQL->Result( "ChaHair" );
	$Input_ChaHairColor[ $nCharMove ] = $pNeoSQL->Result( "ChaHairColor" );
	$Input_ChaFace[ $nCharMove ] = $pNeoSQL->Result( "ChaFace" );
	$Input_ChaLiving[ $nCharMove ] = $pNeoSQL->Result( "ChaLiving" );
	$Input_ChaLevel[ $nCharMove ] = $pNeoSQL->Result( "ChaLevel" );
	$Input_ChaMoney[ $nCharMove ] = $pNeoSQL->Result( "ChaMoney" );
	$Input_ChaPower[ $nCharMove ] = $pNeoSQL->Result( "ChaPower" );
	$Input_ChaStrong[ $nCharMove ] = $pNeoSQL->Result( "ChaStrong" );
	$Input_ChaStrength[ $nCharMove ] = $pNeoSQL->Result( "ChaStrength" );
	$Input_ChaSpirit[ $nCharMove ] = $pNeoSQL->Result( "ChaSpirit" );
	$Input_ChaDex[ $nCharMove ] = $pNeoSQL->Result( "ChaDex" );
	$Input_ChaIntel[ $nCharMove ] = $pNeoSQL->Result( "ChaIntel" );
	$Input_ChaStRemain[ $nCharMove ] = $pNeoSQL->Result( "ChaStRemain" );
	$Input_ChaExp[ $nCharMove ] = $pNeoSQL->Result( "ChaExp" );
	$Input_ChaViewRange[ $nCharMove ] = $pNeoSQL->Result( "ChaViewRange" );
	$Input_ChaHP[ $nCharMove ] = $pNeoSQL->Result( "ChaHP" );
	$Input_ChaMP[ $nCharMove ] = $pNeoSQL->Result( "ChaMP" );
	$Input_ChaStartMap[ $nCharMove ] = $pNeoSQL->Result( "ChaStartMap" );
	$Input_ChaStartGate[ $nCharMove ] = $pNeoSQL->Result( "ChaStartGate" );
	$Input_ChaPosX[ $nCharMove ] = $pNeoSQL->Result( "ChaPosX" );
	$Input_ChaPosY[ $nCharMove ] = $pNeoSQL->Result( "ChaPosY" );
	$Input_ChaPosZ[ $nCharMove ] = $pNeoSQL->Result( "ChaPosZ" );
	$Input_ChaSaveMap[ $nCharMove ] = $pNeoSQL->Result( "ChaSaveMap" );
	$Input_ChaSavePosX[ $nCharMove ] = $pNeoSQL->Result( "ChaSavePosX" );
	$Input_ChaSavePosY[ $nCharMove ] = $pNeoSQL->Result( "ChaSavePosY" );
	$Input_ChaSavePosZ[ $nCharMove ] = $pNeoSQL->Result( "ChaSavePosZ" );
	$Input_ChaReturnMap[ $nCharMove ] = $pNeoSQL->Result( "ChaReturnMap" );
	$Input_ChaReturnPosX[ $nCharMove ] = $pNeoSQL->Result( "ChaReturnPosX" );
	$Input_ChaReturnPosY[ $nCharMove ] = $pNeoSQL->Result( "ChaReturnPosY" );
	$Input_ChaReturnPosZ[ $nCharMove ] = $pNeoSQL->Result( "ChaReturnPosZ" );
	$Input_ChaBright[ $nCharMove ] = $pNeoSQL->Result( "ChaBright" );
	$Input_ChaAttackP[ $nCharMove ] = $pNeoSQL->Result( "ChaAttackP" );
	$Input_ChaDefenseP[ $nCharMove ] = $pNeoSQL->Result( "ChaDefenseP" );
	$Input_ChaFightA[ $nCharMove ] = $pNeoSQL->Result( "ChaFightA" );
	$Input_ChaShootA[ $nCharMove ] = $pNeoSQL->Result( "ChaShootA" );
	$Input_ChaSP[ $nCharMove ] = $pNeoSQL->Result( "ChaShootA" );
	$Input_ChaPK[ $nCharMove ] = $pNeoSQL->Result( "ChaPK" );
	$Input_ChaSkillPoint[ $nCharMove ] = $pNeoSQL->Result( "ChaSkillPoint" );
	$Input_ChaInvenLine[ $nCharMove ] = $pNeoSQL->Result( "ChaInvenLine" );
	$Input_ChaDeleted[ $nCharMove ] = $pNeoSQL->Result( "ChaDeleted" );
	$Input_ChaOnline[ $nCharMove ] = $pNeoSQL->Result( "ChaOnline" );
	$Input_ChaQuest[ $nCharMove ] = bin2hex( $pNeoSQL->Result( "ChaQuest" ) );
	$Input_ChaSkills[ $nCharMove ] = bin2hex( $pNeoSQL->Result( "ChaSkills" ) );
	$Input_ChaSkillSlot[ $nCharMove ] = bin2hex( $pNeoSQL->Result( "ChaSkillSlot" ) );
	$Input_ChaActionSlot[ $nCharMove ] = bin2hex( $pNeoSQL->Result( "ChaActionSlot" ) );
	$Input_ChaPutOnItems[ $nCharMove ] = bin2hex( $pNeoSQL->Result( "ChaPutOnItems" ) );
	$Input_ChaInven[ $nCharMove ] = bin2hex( $pNeoSQL->Result( "ChaInven" ) );
	$Input_ChaReExp[ $nCharMove ] = $pNeoSQL->Result( "ChaReExp" );
	$Input_ChaSpMID[ $nCharMove ] = $pNeoSQL->Result( "ChaSpMID" );
	$Input_ChaSpSID[ $nCharMove ] = $pNeoSQL->Result( "ChaSpSID" );
	$Input_saveMoney[ $nCharMove ] = $pNeoSQL->Result( "saveMoney" );
	$Input_saveExp[ $nCharMove ] = $pNeoSQL->Result( "saveExp" );
	$Input_itemCount[ $nCharMove ] = $pNeoSQL->Result( "itemCount" );
	$Input_ChaCoolTime[ $nCharMove ] = bin2hex( $pNeoSQL->Result( "ChaCoolTime" ) );
	$Input_VTAddInven[ $nCharMove ] = bin2hex( $pNeoSQL->Result( "VTAddInven" ) );
	$Input_SumMain[ $nCharMove ] = $pNeoSQL->Result( "SumMain" );
	$Input_SumSub[ $nCharMove ] = $pNeoSQL->Result( "SumSub" );
	$Input_SumLevel[ $nCharMove ] = $pNeoSQL->Result( "SumLevel" );
	$Input_ChaCP[ $nCharMove ] = $pNeoSQL->Result( "ChaCP" );
	$Input_ChaReborn[ $nCharMove ] = $pNeoSQL->Result( "ChaReborn" );
}
$pNeoSQL->Close();

/*
for( $i = 1 ; $i <= $nCharMove ; $i++ )
{
    echo $Input_ChaName[ $i ] . " : " . strtoupper( $Input_ChaInven[ $i ] );
}
*/

if ( $nCharMove > 0 )
{
	$pNeoSQL->Connect(
				$_CONFIG["OUTPUT"]["RANGAME"]["HOST"]
				, $_CONFIG["OUTPUT"]["RANGAME"]["USER"]
				, $_CONFIG["OUTPUT"]["RANGAME"]["PASS"]
				, $_CONFIG["OUTPUT"]["RANGAME"]["DATABASE"]
				);
	for( $i = 1 ; $i <= $nCharMove ; $i++ )
	{
		$bWork = TRUE;
		$szTemp = sprintf( "SELECT ChaNum FROM ChaInfo WHERE ChaName = '%s'" , $Input_ChaName[ $i ] );
		print( $szTemp . "<br>" );
		$pNeoSQL->Query( $szTemp );
		while( $pNeoSQL->FetchRow() )
		{
			$bWork = FALSE;
		}
		if ( $bWork == FALSE )
		{
			$Input_ChaName[ $i ] = $Input_ChaName[ $i ] . NEWIDNAME;
			if ( strlen( $Input_ChaName[ $i ] > 33 ) )
			{
				CDebugLog::Write( "Can not move Character name : %s because new character name so long.." , $Input_ChaName[ $i ] );
				continue;
			}
			$bWork = TRUE;
			$pNeoSQL->Query( sprintf( "SELECT ChaNum FROM ChaInfo WHERE ChaName = '%s'" , $Input_ChaName[ $i ] ) );
			while( $pNeoSQL->FetchRow() )
			{
				$bWork = FALSE;
			}
			if ( $bWork == FALSE )
			{
				CDebugLog::Write( "Can not move character name %s because this name have already.." , $Input_ChaName[ $i ] );
				continue;
			}
			CDebugLog::Write( sprintf( "Change Character name to : %s" , $Input_ChaName[ $i ] ) );
		}
		
		$szTemp = sprintf(
							  "
							  INSERT INTO [ChaInfo]
							   ([SGNum]
							   ,[UserNum]
							   ,[GuNum]
							   ,[GuPosition]
							   ,[ChaName]
							   ,[ChaGuName]
							   ,[ChaTribe]
							   ,[ChaClass]
							   ,[ChaSchool]
							   ,[ChaSex]
							   ,[ChaHair]
							   ,[ChaHairColor]
							   ,[ChaFace]
							   ,[ChaLiving]
							   ,[ChaLevel]
							   ,[ChaMoney]
							   ,[ChaPower]
							   ,[ChaStrong]
							   ,[ChaStrength]
							   ,[ChaSpirit]
							   ,[ChaDex]
							   ,[ChaIntel]
							   ,[ChaStRemain]
							   ,[ChaExp]
							   ,[ChaViewRange]
							   ,[ChaHP]
							   ,[ChaMP]
							   ,[ChaStartMap]
							   ,[ChaStartGate]
							   ,[ChaPosX]
							   ,[ChaPosY]
							   ,[ChaPosZ]
							   ,[ChaSaveMap]
							   ,[ChaSavePosX]
							   ,[ChaSavePosY]
							   ,[ChaSavePosZ]
							   ,[ChaReturnMap]
							   ,[ChaReturnPosX]
							   ,[ChaReturnPosY]
							   ,[ChaReturnPosZ]
							   ,[ChaBright]
							   ,[ChaAttackP]
							   ,[ChaDefenseP]
							   ,[ChaFightA]
							   ,[ChaShootA]
							   ,[ChaSP]
							   ,[ChaPK]
							   ,[ChaSkillPoint]
							   ,[ChaInvenLine]
							   ,[ChaDeleted]
							   ,[ChaOnline]
							   ,[ChaQuest]
							   ,[ChaSkills]
							   ,[ChaSkillSlot]
							   ,[ChaActionSlot]
							   ,[ChaPutOnItems]
							   ,[ChaInven]
							   ,[ChaReExp]
							   ,[ChaSpMID]
							   ,[ChaSpSID]
							   ,[saveMoney]
							   ,[saveExp]
							   ,[itemCount]
							   ,[ChaCoolTime]
							   ,[VTAddInven]
							   ,[SumMain]
							   ,[SumSub]
							   ,[SumLevel]
							   ,[ChaCP]
							   ,[ChaReborn])
						 VALUES
							   (%d
							   ,%d
							   ,%d
							   ,%d
							   ,'%s'
							   ,'%s'
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%s
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,0x%s
							   ,0x%s
							   ,0x%s
							   ,0x%s
							   ,0x%s
							   ,0x%s
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,0x%s
							   ,0x%s
							   ,%d
							   ,%d
							   ,%d
							   ,%d
							   ,%d)
							  "
							  ,$Input_SGNum[ $i ]
							  ,$Input_UserNum
							   ,0//$Input_GuNum[ $i ]
							   ,$Input_GuPosition[ $i ]
							   ,$Input_ChaName[ $i ]
							   ,$Input_ChaGuName[ $i ]
							   ,$Input_ChaTribe[ $i ]
							   ,$Input_ChaClass[ $i ]
							   ,$Input_ChaSchool[ $i ]
							   ,$Input_ChaSex[ $i ]
							   ,$Input_ChaHair[ $i ]
							   ,$Input_ChaHairColor[ $i ]
							   ,$Input_ChaFace[ $i ]
							   ,$Input_ChaLiving[ $i ]
							   ,$Input_ChaLevel[ $i ]
							   ,$Input_ChaMoney[ $i ]
							   ,$Input_ChaPower[ $i ]
							   ,$Input_ChaStrong[ $i ]
							   ,$Input_ChaStrength[ $i ]
							   ,$Input_ChaSpirit[ $i ]
							   ,$Input_ChaDex[ $i ]
							   ,$Input_ChaIntel[ $i ]
							   ,$Input_ChaStRemain[ $i ]
							   ,$Input_ChaExp[ $i ]
							   ,$Input_ChaViewRange[ $i ]
							   ,$Input_ChaHP[ $i ]
							   ,$Input_ChaMP[ $i ]
							   ,$Input_ChaStartMap[ $i ]
							   ,$Input_ChaStartGate[ $i ]
							   ,$Input_ChaPosX[ $i ]
							   ,$Input_ChaPosY[ $i ]
							   ,$Input_ChaPosZ[ $i ]
							   ,$Input_ChaSaveMap[ $i ]
							   ,$Input_ChaSavePosX[ $i ]
							   ,$Input_ChaSavePosY[ $i ]
							   ,$Input_ChaSavePosZ[ $i ]
							   ,$Input_ChaReturnMap[ $i ]
							   ,$Input_ChaReturnPosX[ $i ]
							   ,$Input_ChaReturnPosY[ $i ]
							   ,$Input_ChaReturnPosZ[ $i ]
							   ,$Input_ChaBright[ $i ]
							   ,$Input_ChaAttackP[ $i ]
							   ,$Input_ChaDefenseP[ $i ]
							   ,$Input_ChaFightA[ $i ]
							   ,$Input_ChaShootA[ $i ]
							   ,$Input_ChaSP[ $i ]
							   ,$Input_ChaPK[ $i ]
							   ,$Input_ChaSkillPoint[ $i ]
							   ,$Input_ChaInvenLine[ $i ]
							   ,$Input_ChaDeleted[ $i ]
							   ,$Input_ChaOnline[ $i ]
							   , ''//$Input_ChaQuest[ $i ]//ปิดไว้เนื่องจากการย้าย Quest จำเป็นต้องใช้การอนุญาติตัวอักษรจำนวนมาก ตามจำนวน Quest ที่มีถ้าไม่เปิดไว้มากๆก็ไม่ควรย้ายเพราะอาจจะทำให้เซิร์ฟเวอร์พังได้
							   ,$Input_ChaSkills[ $i ]
							   ,$Input_ChaSkillSlot[ $i ]
							   ,$Input_ChaActionSlot[ $i ]
							   ,$Input_ChaPutOnItems[ $i ]
                        
							   //, ''//$Input_ChaInven[ $i ] // ปิดไว้เนื่องจากเซิร์ฟเวอร์ที่ต้องการย้ายไม่ต้องการย้ายของในเป้มา
                                                           ,$Input_ChaInven[ $i ]
                        
							   ,$Input_ChaReExp[ $i ]
							   ,$Input_ChaSpMID[ $i ]
							   ,$Input_ChaSpSID[ $i ]
							   ,$Input_saveMoney[ $i ]
							   ,$Input_saveExp[ $i ]
							   ,$Input_itemCount[ $i ]
							   ,$Input_ChaCoolTime[ $i ]
							   ,$Input_VTAddInven[ $i ]
							   ,$Input_SumMain[ $i ]
							   ,$Input_SumSub[ $i ]
							   ,$Input_SumLevel[ $i ]
							   ,$Input_ChaCP[ $i ]
							   ,$Input_ChaReborn[ $i ]
							  );
		$Query = $pNeoSQL->Query( $szTemp );
		if ( $Query )
                {
			CDebugLog::Write( sprintf( "Move Character name : %s complete.." , $Input_ChaName[ $i ] ) );
                        CDebugLog::WriteC( $Input_UserNum );
                }
		else
			CDebugLog::Write( sprintf( "Can not move character name : %s because can not query..." , $Input_ChaName[ $i ] ) );
	}
	$pNeoSQL->Close();
	CDebugLog::Write( sprintf( "Found Character : %d" , $nCharMove ) );
}else{
	CDebugLog::Write( "Not found data character.." );
}

CDebugLog::Write( "Process success..." );
CDebugLog::Write( "===========================================" );
?>
<script language="javascript"> 
setTimeout("self.close();",5000) 
</script> 
</body>
</html>