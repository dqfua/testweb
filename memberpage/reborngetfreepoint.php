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

$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$ChaLevel = 0;
$ChaReborn = 0;

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
$szTemp = sprintf("SELECT ChaLevel,ChaReborn FROM ChaInfo WHERE UserNum = %d AND ChaNum = %d" , $UserNum , $ChaNum );
$cNeoSQLConnectODBC->QueryRanGame($szTemp);
while( $cNeoSQLConnectODBC->FetchRow() )
{
    $ChaLevel = $cNeoSQLConnectODBC->Result("ChaLevel",ODBC_RETYPE_INT);
    $ChaReborn = $cNeoSQLConnectODBC->Result("ChaReborn",ODBC_RETYPE_INT);
}
$cNeoSQLConnectODBC->CloseRanGame();

if ( $ChaLevel == 0 || $ChaReborn == 0 ) die("ไม่พบตัวละคร");

if ( $cWeb->Sys_ChaRebornGetPoint_Lv > 0 )
{
    if ( $cWeb->Sys_ChaRebornGetPoint_Lv == $ChaReborn )
    {
        if ( $ChaLevel == $cWeb->GetSys_CharRebornLevCheck() )
        {
            $bCan = false;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf("SELECT LogNum FROM Log_ChaReborn WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d"
                    , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
                    , $UserNum
                    , $ChaNum
                    );
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $bCan = true;
            }
            $cNeoSQLConnectODBC->CloseRanGame();
            if ( $bCan )
            {
                die("คุณได้รับรางวัลไปแล้ว");
            }else{
                CNeoLog::LogChaReborn($_CONFIG["SERVERMAN"]["SERVER_MEMNUM"], $UserNum, $ChaNum, $ChaReborn, $cWeb->Sys_ChaRebornGetPoint_Value );
                $cUser->UpPoint( $cWeb->Sys_ChaRebornGetPoint_Value );
                COnline::OnlineSet( $cUser );
                die("ขอบคุณที่ร่วมกิจกรรม");
            }
        }else{
            die("จุติของคุณยังไม่สามารถใช้งานได้");
        }
    }else{
        die("จุติของคุณยังไม่สามารถใช้งานได้");
    }
}
?>
