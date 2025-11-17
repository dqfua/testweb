<?php
header('Content-Type: text/html; charset=windows-874');

if ( !defined("DEBUG") )
define("DEBUG",TRUE);

include("global.loader.php");

set_time_limit(0);
session_start();

CInput::GetInstance()->BuildFrom( IN_GET );

$password = CInput::GetInstance()->GetValueString( "password" , IN_GET );
$hash = CInput::GetInstance()->GetValueString( "hash" , IN_GET );
$amount = CInput::GetInstance()->GetValueInt( "amount" , IN_GET );
$status = CInput::GetInstance()->GetValueInt( "status" , IN_GET );

if(empty($_SERVER['REMOTE_ADDR']) || strcmp($_SERVER['REMOTE_ADDR'],$_CONFIG['tmpay']['access_ip']) != 0) die('ERROR|ACCESS_DENIED');
else if( strlen($password) == 14 && isset($status) && strcmp($hash,md5($password . $password)) == 0 && TmPay::misc_parsestring($password))
{
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    
    $LogNum = 0;
    $MemNum = 0;
    $UserNum = 0;
    
    $szTemp = sprintf("SELECT LogNum,MemNum,UserNum FROM Log_UserBonusPoint WHERE SerialPassword = '%s'" , $password);
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $LogNum = $cNeoSQLConnectODBC->Result("LogNum", ODBC_RETYPE_INT);
        $MemNum = $cNeoSQLConnectODBC->Result("MemNum", ODBC_RETYPE_INT);
        $UserNum = $cNeoSQLConnectODBC->Result("UserNum", ODBC_RETYPE_INT);
    }
    
    if ( $LogNum > 0 && $MemNum > 0 && $UserNum > 0 )
    {
        $UserID = UserInfo::GetUserIDFromUserNum($MemNum, $UserNum);
        
        if ( $UserID != "" )
        {
            $cTmPay = new TmPay;
            $cTmPay->UpdateCardRank2( $MemNum );
            
            $BonusPoint = 0;

            $szTemp = sprintf( "SELECT BonusPoint FROM UserInfo WHERE MemNum = %d AND UserID = '%s'" , $MemNum , $UserID);
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $BonusPoint = $cNeoSQLConnectODBC->Result("BonusPoint", ODBC_RETYPE_INT);
            }
            
            $AddBonusPoint = $cTmPay->GetBonusPointCard($amount);
            
            //update bonuspoint
            $szTemp = sprintf( "UPDATE UserInfo SET BonusPoint = BonusPoint + %d WHERE MemNum = %d AND UserID = '%s'", $AddBonusPoint , $MemNum, $UserID );
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            
            //update log
            $szTemp = sprintf("UPDATE Log_UserBonusPoint SET BeforeBonusPoint = %d, NewBonusPoint = %d, BonusPrice = %d, UpdateDate = getdate(), CardRank = %d, Status = %d WHERE LogNum = %d"
                        , $BonusPoint, $BonusPoint + $AddBonusPoint, $AddBonusPoint, $amount, $status , $LogNum
                        );
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            
            printf( "SUCCEED|LogNum=%d" , $LogNum );
        }else{
            printf( "SUCCEED|ERROR=LogNum=%d=UserIDNotFound" , $LogNum );
        }
    }else{
        printf( "SUCCEED|ERROR=LogNum=%d" , $LogNum );
    }
    
    $cNeoSQLConnectODBC->CloseRanWeb();
}else{
    echo 'ERROR|ACCESS_DENIED';
}

?>
