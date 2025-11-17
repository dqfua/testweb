<?php
include("logon.php");
if ( !CSec::Check() ) exit;

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserNum = $cUser->GetUserNum();
$UserType = $cUser->GetUserType();

CInput::GetInstance()->BuildFrom( IN_POST );

$Serial = @CNeoInject::sec_Int2( CInput::GetInstance()->GetValueString( "serial" , IN_POST ) );
if ( strlen( $Serial ) != 14 ) die("Serial Card not work<br>");

$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$RefillNum = 0;
$CardRank = 0;

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
$cNeoSQLConnectODBC->ConnectRanWeb( );

if ( $cWeb->GetSys_BonusPointEx() )
{
    $LogNum = 0;
    $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT LogNum FROM Log_UserBonusPoint WHERE MemNum = %d AND SerialTruemoney = '%s'" 
                                                                                            , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
                                                                                            , $Serial
                    ) );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $LogNum = $cNeoSQLConnectODBC->Result( "LogNum" , ODBC_RETYPE_INT );
    }
    
    if ( $LogNum == 0 )
    {
        $ToPercentID = false;
        $ToMerchantID = "";
        
        $cTmPay = new TmPay;
        $cTmPay->UpdateCardRank();
        if ( $cTmPay->nPercent <= 0 )
        {
            $ToMerchantID = $cTmPay->GetMerchantID();
        }else{
            $ToMerchantID = $cTmPay->CVar($ToPercentID);
        }
        
        if ( $cTmPay->MyService )
        {
            $ToMerchantID = $cTmPay->MyMerchantID();
        }
        
        if ( $UserType > 1 )
        {
            $ToMerchantID = $cTmPay->GetMerchantID();
        }
        
        $curl_content = "";
        
        if(function_exists('curl_init'))
        {
            $curl = curl_init('https://203.146.127.112/tmpay.net/TPG/backend.php?merchant_id=' . $ToMerchantID . '&password=' . $Serial . '&resp_url=' . $_CONFIG['tmpay']['resp_url_bonuspoint']);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, 10);
            curl_setopt($curl, CURLOPT_HEADER, FALSE);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            //curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //ปิดเพราะว่าปิด safe_mode
            $curl_content = curl_exec($curl);
            curl_close($curl);
        }
        else
        {
            $curl_content = @file_get_contents('http://203.146.127.112/tmpay.net/TPG/backend.php?merchant_id=' .$ToMerchantID . '&password=' . $Serial . '&resp_url=' . $_CONFIG['tmpay']['resp_url_bonuspoint']);
        }
        
        if( strpos($curl_content,'SUCCEED') !== FALSE )
        {
            CNeoLog::LogUserBonusPoint ($MemNum, $UserNum, 0, 0, 0, $Serial, 0, $ToMerchantID, $ToPercentID);
        
            echo "<font color=\"green\">thank you just a moment for update status</font>";
        }else{
            if ( strpos($curl_content,'INVALID_MERCHANT_ID') )
            {
                echo "<font color=\"red\">MerchantID wrong.</font>";
            }else if ( strpos($curl_content,'INVALID_PASSWORD') ){
                echo "<font color=\"red\">Serial Truemoney wrong.</font>";
            }else if ( strpos($curl_content,'INVALID_RESP_URL') ){
                echo "<font color=\"red\">Response link wrong.</font>";
            }else{
                echo "<font color=\"red\">Try again later.</font>";
            }
        }
    }else{
        echo "<font color=\"red\">this card already to use</font>";
    }
}else{
    $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT RefillNum, CardRank FROM Refill WHERE MemNum = %d AND UserNum = %d AND SerialTruemoney = '%s' AND Status = 1" 
                                                                                            , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
                                                                                            , $UserNum
                                                                                            , $Serial
                    ) );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $RefillNum = $cNeoSQLConnectODBC->Result( "RefillNum" , ODBC_RETYPE_INT );
        $CardRank = $cNeoSQLConnectODBC->Result( "CardRank" , ODBC_RETYPE_INT );
    }

    if ( $RefillNum > 0 && $CardRank > 0 )
    {
        $LogNum = 0;
        $szTemp = sprintf( "SELECT LogNum,MemNum,UserNum FROM Log_UserBonusPoint WHERE SerialPassword = '%s'" , $Serial );
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
        if ( $LogNum == 0 )
        {
            $szTemp = sprintf("SELECT BonusPoint_50,BonusPoint_90,BonusPoint_150,BonusPoint_300,BonusPoint_500,BonusPoint_1000 FROM MemPoint  WHERE MemNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );

            $BonusPoint_50 = $cNeoSQLConnectODBC->Result( "BonusPoint_50" , ODBC_RETYPE_INT );
            $BonusPoint_90 = $cNeoSQLConnectODBC->Result( "BonusPoint_90" , ODBC_RETYPE_INT );
            $BonusPoint_150 = $cNeoSQLConnectODBC->Result( "BonusPoint_150" , ODBC_RETYPE_INT );
            $BonusPoint_300 = $cNeoSQLConnectODBC->Result( "BonusPoint_300" , ODBC_RETYPE_INT );
            $BonusPoint_500 = $cNeoSQLConnectODBC->Result( "BonusPoint_500" , ODBC_RETYPE_INT );
            $BonusPoint_1000 = $cNeoSQLConnectODBC->Result( "BonusPoint_1000" , ODBC_RETYPE_INT );

            $AddBonusPoint = 0;

            switch($CardRank)
            {
                case 1: $AddBonusPoint = $BonusPoint_50; break;
                case 2: $AddBonusPoint = $BonusPoint_90; break;
                case 3: $AddBonusPoint = $BonusPoint_150; break;
                case 4: $AddBonusPoint = $BonusPoint_300; break;
                case 5: $AddBonusPoint = $BonusPoint_500; break;
                case 6: $AddBonusPoint = $BonusPoint_1000; break;

                default:
                {
                }
            }

            if ( $AddBonusPoint > 0 )
            {
                $BeforeBonusPoint = $cUser->GetBonusPoint();

                $cUser->UpBonusPoint( $AddBonusPoint );

                CNeoLog::LogUserBonusPoint ($MemNum, $UserNum, $BeforeBonusPoint, $cUser->GetBonusPoint(), $AddBonusPoint, $Serial);

                echo "<font color=\"green\">thank you for subscription.</font>";
            }else{
                echo "This No.Card ID dont have promotion at this time.";
            }
        }else{
                echo "This No.Card ID already get promotion.";
        }
    }else{
            echo "Not Found No.Card ID";
    }
}

$cNeoSQLConnectODBC->CloseRanWeb();
?>
