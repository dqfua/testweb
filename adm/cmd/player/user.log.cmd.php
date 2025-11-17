<?php
function USER_LOGVIEW_BONUSPOINT()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_BONUSPOINT" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" , "gridtable" );
    table_log_easy_title("ประวัติแต้มสะสม",6,"width:600px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "" , "width:159px;");
    table_log_easy_add_head_colume( "SerialPassword" , "" , "width:259px;");
    table_log_easy_add_head_colume( "ก่อน" , "" , "width:99px;");
    table_log_easy_add_head_colume( "หลัง" , "" , "width:99px;");
    table_log_easy_add_head_colume( "จำนวน" , "" , "width:99px;");
    table_log_easy_add_head_colume( "Admin" , "" , "width:99px;");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        
        $szTemp = sprintf( "SELECT TOP 30 BeforeBonusPoint,NewBonusPoint,BonusPrice,bAdmin,SerialPassword,LogDate FROM Log_UserBonusPoint WHERE UserNum = %d AND MemNum = %d ORDER BY LogNum DESC"
                            , $pUserInfo->UserNum
                            , $MemNum
                        );
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
        //echo $szTemp;
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "BeforeBonusPoint" , $cNeoSQLConnectODBC->Result( "BeforeBonusPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewBonusPoint" , $cNeoSQLConnectODBC->Result( "NewBonusPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "BonusPrice" , $cNeoSQLConnectODBC->Result( "BonusPrice" , ODBC_RETYPE_INT ) );
            $pData->AddData( "bAdmin" , $cNeoSQLConnectODBC->Result( "bAdmin" , ODBC_RETYPE_INT ) );
            $pData->AddData( "SerialPassword" , $cNeoSQLConnectODBC->Result( "SerialPassword" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_ENG ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    $TTTT = array( "No" , "Yes" );
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "LogDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "SerialPassword" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "BeforeBonusPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewBonusPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "BonusPrice" ] , "" , "");
        table_log_easy_add_colume( $TTTT[$ppData[ "bAdmin" ]] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_createtbody();
    table_log_closetbody();
    
    table_log_easy_end();
}

function USER_LOGVIEW_FORGETPASSWORD()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_FORGETPASSWORD" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" , "gridtable" );
    table_log_easy_title("ประวัติการกู้รหัสผ่าน",3,"width:600px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "" , "width:159px;");
    table_log_easy_add_head_colume( "ไอพีที่ใช้กู้รหัสผ่าน" , "" , "width:299px;");
    table_log_easy_add_head_colume( "รหัสผ่านใหม่ที่ได้รับ" , "" , "width:159px;");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        
        $szTemp = sprintf( "SELECT TOP 30 LogDate,LogIP,GenNewPass FROM Log_Forgetpassword WHERE UserNum = %d AND MemNum = %d ORDER BY LogNum DESC"
                            , $pUserInfo->UserNum
                            , $MemNum
                        );
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
        //echo $szTemp;
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "LogIP" , $cNeoSQLConnectODBC->Result( "LogIP" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "GenNewPass" , $cNeoSQLConnectODBC->Result( "GenNewPass" , ODBC_RETYPE_ENG ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( substr( $ppData[ "LogDate" ] , 0 , 16 ) , "" , "");
        table_log_easy_add_colume( $ppData[ "LogIP" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "GenNewPass" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_createtbody();
    table_log_closetbody();
    
    table_log_easy_end();
}

function USER_LOGVIEW_LOGINGAME()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_LOGINGAME" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" , "gridtable" );
    table_log_easy_title("ล็อกอินเกม",3,"width:700px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "ไอพีเข้าเกม" , "");
    table_log_easy_add_head_colume( "สถานะ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        
        $szTemp = sprintf( "SELECT LogDate,LogIpAddress,LogInOut FROM LogLogin WHERE UserNum = %d ORDER BY LoginNum DESC" 
                                                    , $pUserInfo->UserNum
                );
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
        $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
        //echo $szTemp;
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "LogIpAddress" , $cNeoSQLConnectODBC->Result( "LogIpAddress" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "LogInOut" , $cNeoSQLConnectODBC->Result( "LogInOut" , ODBC_RETYPE_INT ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanUser();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    echo '<script type="text/javascript">var logviewroll = 0;var logview_logingame = [';
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        printf( "[ '%s' , '%s' , '%s' ]," , $ppData[ "LogDate" ] , $ppData[ "LogIpAddress" ] , $_CONFIG["USER"]["LOGIN"][ $ppData[ "LogInOut" ] ] );
        
        /*
        table_log_easy_line_begin();
        table_log_easy_add_colume( substr( $ppData[ "LogDate" ] , 0 , -4 ) , "" , "");
        table_log_easy_add_colume( $ppData[ "LogIpAddress" ] , "" , "");
        table_log_easy_add_colume( $_CONFIG["USER"]["LOGIN"][ $ppData[ "LogInOut" ] ] , "" , "");
        table_log_easy_line_end();
        */
    }
    
    echo '];</script>';
    
    table_log_createtbody();
    table_log_closetbody();
    
    table_log_createtfoot();
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "<button id=\"updatedata\" onclick='userlog_logingame(this);'>แสดงข้อมูลเพิ่ม</button>" , "3");
    table_log_easy_line_end();
    table_log_closetfoot();
    
    table_log_easy_end();
    
    echo '<script type="text/javascript">userlog_logingame( $("#updatedata") );</script>';
}

function USER_LOGVIEW_BONUSPOINTINVITEFRIENDS()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_BONUSPOINTINVITEFRIENDS" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" , "gridtable" );
    table_log_easy_title("Bonus RePoint Invite Friends",5,"width:700px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "จากไอดี" , "");
    table_log_easy_add_head_colume( "จำนวน" , "");
	table_log_easy_add_head_colume( "ก่อน" , "");
	table_log_easy_add_head_colume( "หลัง" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        
        $szTemp = sprintf( "SELECT LogDate,FromUserID,Amount,BeforePoint,AfterPoint FROM Log_RePointInviteFriends WHERE MemNum = %d AND UserNum = %d ORDER BY LoginNum DESC" 
                                                    , $pUserInfo->UserNum
                );
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
        //echo $szTemp;
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "FromUserID" , $cNeoSQLConnectODBC->Result( "FromUserID" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "Amount" , $cNeoSQLConnectODBC->Result( "Amount" , ODBC_RETYPE_INT ) );
			$pData->AddData( "BeforePoint" , $cNeoSQLConnectODBC->Result( "BeforePoint" , ODBC_RETYPE_INT ) );
			$pData->AddData( "AfterPoint" , $cNeoSQLConnectODBC->Result( "AfterPoint" , ODBC_RETYPE_INT ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "LogDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "FromUserID" ] , "" , "");
		table_log_easy_add_colume( $ppData[ "Amount" ] , "" , "");
		table_log_easy_add_colume( $ppData[ "BeforePoint" ] , "" , "");
		table_log_easy_add_colume( $ppData[ "AfterPoint" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_createtbody();
    table_log_closetbody();
    
    table_log_easy_end();
}

function USER_LOGVIEW_BANUSER()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_BANUSER" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการโดนแบน",2,"width:300px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "การสั่งการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT UserBan,BanDate FROM Log_AdminBanUser WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pUserInfo->UserNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "UserBan" , $cNeoSQLConnectODBC->Result( "UserBan" , ODBC_RETYPE_INT ) );
            $pData->AddData( "BanDate" , $cNeoSQLConnectODBC->Result( "BanDate" , ODBC_RETYPE_THAI ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "BanDate" ] , "" , "");
        table_log_easy_add_colume( $_CONFIG["USER"]["USERBAN"][ $ppData[ "UserBan" ] ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_REFILL()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_REFILL" , $MemNum , $pUserInfo->UserNum );
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT SerialTrueMoney,Status,CardRank,OldPoint,NewPoint,GetPoint,RefillDate,UpdateDate FROM Refill WHERE MemNum = %d AND UserNum = %d ORDER BY RefillNum DESC" 
                                                    , $MemNum
                                                    , $pUserInfo->UserNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "SerialTrueMoney" , $cNeoSQLConnectODBC->Result( "SerialTrueMoney" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "Status" , $cNeoSQLConnectODBC->Result( "Status" , ODBC_RETYPE_INT ) );
            $pData->AddData( "CardRank" , $cNeoSQLConnectODBC->Result( "CardRank" , ODBC_RETYPE_INT ) );
            $pData->AddData( "OldPoint" , $cNeoSQLConnectODBC->Result( "OldPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewPoint" , $cNeoSQLConnectODBC->Result( "NewPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "GetPoint" , $cNeoSQLConnectODBC->Result( "BanDate" , ODBC_RETYPE_INT ) );
            $pData->AddData( "RefillDate" , $cNeoSQLConnectODBC->Result( "RefillDate" , ODBC_RETYPE_THAI ) );
            $pData->AddData( "UpdateDate" , $cNeoSQLConnectODBC->Result( "UpdateDate" , ODBC_RETYPE_THAI ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
	
	table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการเติมบัตร",8,"width:1500px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลาที่เติมบัตร" , "");
    table_log_easy_add_head_colume( "วันเวลาที่ตรวจบัตร" , "");
    table_log_easy_add_head_colume( "รหัสบัตร" , "" , "width:259px;");
    table_log_easy_add_head_colume( "สถานะ" , "" , "width:199px;");
    table_log_easy_add_head_colume( "มูลค่าบัตร" , "");
    table_log_easy_add_head_colume( "พ้อยของบัตร" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนเติม" , "");
    table_log_easy_add_head_colume( "พ้อยหลังเติม" , "");
    table_log_easy_line_end();
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "RefillDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "UpdateDate" ] , "" , "");
        
        if( $cAdmin->nServerType == SERVTYPE_EP3 )
        {
            table_log_easy_add_colume( $ppData[ "SerialTrueMoney" ] , "" , "");
        }else{
            table_log_easy_add_colume( "XXXXXXX".substr( $ppData[ "SerialTrueMoney" ] , 7 ) , "" , "");
        }
        
        table_log_easy_add_colume( $_CONFIG['tmpay']['card_status'][ $ppData[ "Status" ] ] , "" , "");
        table_log_easy_add_colume( $_CONFIG['tmpay']['amount'][ $ppData[ "CardRank" ] ] , "" , "");
        table_log_easy_add_colume( $ppData[ "GetPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "OldPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewPoint" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_BUY()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_BUY" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการซื้อไอเทม",6,"width:1500px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "" , "width:139px;");
    table_log_easy_add_head_colume( "ตัวละครที่ทำรายการ" , "" , "width:199px;");
    table_log_easy_add_head_colume( "ไอเทมที่ทำรายการ" , "" , "width:399px;");
    table_log_easy_add_head_colume( "ราคา" , "" , "width:49px;");
    table_log_easy_add_head_colume( "ก้อนซื้อ" , "" , "width:99px;");
    table_log_easy_add_head_colume( "หลังซื้อ" , "" , "width:99px;");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $ChaData = array();
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        $cNeoSQLConnectODBC->QueryRanGame( sprintf( "SELECT ChaNum,ChaName FROM ChaInfo WHERE UserNum = %d" , $pUserInfo->UserNum ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
            $ChaName = CBinaryCover::tis620_to_utf8( $ChaName );
            $ChaData[ $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT ) ] = $ChaName;
        }
        $cNeoSQLConnectODBC->CloseRanGame();
        
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT TOP 200 ChaNum,ItemMain,ItemSub,ItemName,ItemPrice,ItemType,UserPoint_Before,UserPoint_New,OldGameTime,NewGameTime,LogDate FROM Log_Buy WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pUserInfo->UserNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
            $pData->AddData( "ChaNum" , $ChaNum );
            $pData->AddData( "ChaName" , $ChaData[ $ChaNum ] );
            $pData->AddData( "ItemMain" , $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ItemSub" , $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ItemPrice" , $cNeoSQLConnectODBC->Result( "ItemPrice" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ItemType" , $cNeoSQLConnectODBC->Result( "ItemType" , ODBC_RETYPE_INT ) );
            $pData->AddData( "UserPoint_Before" , $cNeoSQLConnectODBC->Result( "UserPoint_Before" , ODBC_RETYPE_INT ) );
            $pData->AddData( "UserPoint_New" , $cNeoSQLConnectODBC->Result( "UserPoint_New" , ODBC_RETYPE_INT ) );
            $pData->AddData( "OldGameTime" , $cNeoSQLConnectODBC->Result( "OldGameTime" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewGameTime" , $cNeoSQLConnectODBC->Result( "NewGameTime" , ODBC_RETYPE_INT ) );
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_THAI ) );
            
            $ItemName = $cNeoSQLConnectODBC->Result( "ItemName" , ODBC_RETYPE_THAI );
            $ItemName = CBinaryCover::tis620_to_utf8( $ItemName );
            $pData->AddData( "ItemName" , $ItemName );
            
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "LogDate" ] , "" , "");
        if ( $ppData[ "ItemType" ] == 1 )
            table_log_easy_add_colume( $ppData[ "ChaName" ] , "" , "");
        else
            table_log_easy_add_colume( "To ItemBank" , "" , "");
        table_log_easy_add_colume( sprintf( "%03d:%03d:%s" , $ppData[ "ItemMain" ] , $ppData[ "ItemSub" ] , $ppData[ "ItemName" ] ) , "" , "");
        table_log_easy_add_colume( $ppData[ "ItemPrice" ] , "" , "");
        table_log_easy_add_colume( sprintf( "%d พ้อย, %d นาที" , $ppData[ "UserPoint_Before" ] , $ppData[ "OldGameTime" ] ) , "" , "");
        table_log_easy_add_colume( sprintf( "%d พ้อย, %d นาที" , $ppData[ "UserPoint_New" ] , $ppData[ "NewGameTime" ] ) , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_RESELL()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_RESELL" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการขายไอเทมคืน",6,"width:1500px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "" , "width:139px;");
    table_log_easy_add_head_colume( "ตัวละครที่ทำรายการ" , "" , "width:199px;");
    table_log_easy_add_head_colume( "ไอเทมที่ทำรายการ" , "" , "width:399px;");
    table_log_easy_add_head_colume( "ราคา" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนซื้อ" , "");
    table_log_easy_add_head_colume( "พ้อยหลังซื้อ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $ChaData = array();
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        $cNeoSQLConnectODBC->QueryRanGame( sprintf( "SELECT ChaNum,ChaName FROM ChaInfo WHERE UserNum = %d" , $pUserInfo->UserNum ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
            $ChaName = CBinaryCover::tis620_to_utf8( $ChaName );
            $ChaData[ $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT ) ] = $ChaName;
        }
        $cNeoSQLConnectODBC->CloseRanGame();
        
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT TOP 200 ChaNum,ItemMain,ItemSub,ItemName,ItemPrice,UserPoint_Before,UserPoint_New,LogDate,ChaInven_Bak,ChaInven_New FROM Log_Resell WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pUserInfo->UserNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
            $pData->AddData( "ChaNum" , $ChaNum );
            $pData->AddData( "ChaName" , $ChaData[ $ChaNum ] );
            $pData->AddData( "ItemMain" , $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ItemSub" , $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ItemPrice" , $cNeoSQLConnectODBC->Result( "ItemPrice" , ODBC_RETYPE_INT ) );
            $pData->AddData( "UserPoint_Before" , $cNeoSQLConnectODBC->Result( "UserPoint_Before" , ODBC_RETYPE_INT ) );
            $pData->AddData( "UserPoint_New" , $cNeoSQLConnectODBC->Result( "UserPoint_New" , ODBC_RETYPE_INT ) );
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_THAI ) );
            
            $ItemName = $cNeoSQLConnectODBC->Result( "ItemName" , ODBC_RETYPE_THAI );
            $ItemName = CBinaryCover::tis620_to_utf8( $ItemName );
            $pData->AddData( "ItemName" , $ItemName );
            
            $pData->AddData( "ChaInven_Bak" , $cNeoSQLConnectODBC->Result( "ChaInven_Bak" , ODBC_RETYPE_BINARY ) );
            $pData->AddData( "ChaInven_New" , $cNeoSQLConnectODBC->Result( "ChaInven_New" , ODBC_RETYPE_BINARY ) );
            
            $pData->NextData();
        }
        
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    $pItemProject = new CItemProject($MemNum);
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        $pChaInvenBak = new CNeoChaInven();
        $pChaInvenBak->LoadChaInven($ppData[ "ChaInven_Bak" ]);
        $pChaInvenNew = new CNeoChaInven();
        $pChaInvenNew->LoadChaInven($ppData[ "ChaInven_New" ]);
        
        $itemview_list = "<div id=\"itemview_".$i."\" style=\"display:none;\">";
        
        //view old item
        $itemview_list .= "<table class=\"gridtable\"><tr><td colspan=\"4\">" . sprintf("ข้อมูลช่อง Inventory ก่อนจะทำรายการ, จำนวนไอเทมทั้งหมด : %d" , $pChaInvenBak->GetItemNum()) . "</td></tr>";
        $itemview_list .= "<tr><td>M</td><td>S</td><td>N</td><td>Name</td></tr>";
        for( $n = 0 ; $n < $pChaInvenBak->GetItemNum() ; $n++ )
        {
            $itemview_list .= sprintf("<tr><td>%d</td><td>%d</td><td>%d</td><td>%s</td></tr>"
                                      , $pChaInvenBak->GetItemMain($n)
                                      , $pChaInvenBak->GetItemSub($n)
                                      , $pChaInvenBak->GetItemTrunNum($n)
                                      , $pItemProject->GetItemNameL($pChaInvenBak->GetItemMain($n), $pChaInvenBak->GetItemSub($n))
                                      );
        }
        $itemview_list .= "</table><hr>";
        
        //view new item
        $itemview_list .= "<table class=\"gridtable\"><tr><td colspan=\"4\">" . sprintf("ข้อมูลช่อง Inventory หลังจะทำรายการ, จำนวนไอเทมทั้งหมด : %d" , $pChaInvenNew->GetItemNum()) . "</td></tr>";
        $itemview_list .= "<tr><td>M</td><td>S</td><td>N</td><td>Name</td></tr>";
        for( $n = 0 ; $n < $pChaInvenNew->GetItemNum() ; $n++ )
        {
            $itemview_list .= sprintf("<tr><td>%d</td><td>%d</td><td>%d</td><td>%s</td></tr>"
                                      , $pChaInvenNew->GetItemMain($n)
                                      , $pChaInvenNew->GetItemSub($n)
                                      , $pChaInvenNew->GetItemTrunNum($n)
                                      , $pItemProject->GetItemNameL($pChaInvenNew->GetItemMain($n), $pChaInvenNew->GetItemSub($n))
                                      );
        }
        $itemview_list .= "</table>";
        
        $itemview_list .= "</div>";
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "LogDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "ChaName" ] , "" , "");
        table_log_easy_add_colume( sprintf( "%03d:%03d:%s" , $ppData[ "ItemMain" ] , $ppData[ "ItemSub" ] , $ppData[ "ItemName" ] ) . "<button onclick=\"resell_itemview(".$i.");\">ตรวจสอบข้อมูลเชิงลึก</button>" . $itemview_list , "" , "");
        table_log_easy_add_colume( $ppData[ "ItemPrice" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "UserPoint_Before" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "UserPoint_New" ] , "" , "");
        table_log_easy_line_end();
    }
}

function USER_LOGVIEW_LOGINITEMSHOP()
{
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_LOGINITEMSHOP" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการล็อกอินไอเทมช็อป",2,"width:300px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "ไอดีที่เข้าสู่ระบบ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT LogIP,LogDate FROM Log_Login WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pUserInfo->UserNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "LogIP" , $cNeoSQLConnectODBC->Result( "LogIP" , ODBC_RETYPE_ENG ) );
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_THAI ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "LogDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "LogIP" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_REFILLITEMFEEDBACK()
{
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_REFILLITEMFEEDBACK" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการได้รับของรางวัลเมื่อเติมบัตร",3,"width:600px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "รหัสบัตร" , "" , "width:109px;");
    table_log_easy_add_head_colume( "ไอเทมที่ได้รับ" , "" , "width:399px;");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $RefillInfo = new _tdata();
        $ItemNameInfo = new _tdata();
        
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT ItemName,ItemMain,ItemSub FROM ItemProject WHERE MemNum = %d" , $MemNum ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ItemMain = $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT );
            $ItemSub = $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT );
            $ItemName = $cNeoSQLConnectODBC->Result( "ItemName" , ODBC_RETYPE_THAI );
            $ItemName = CBinaryCover::tis620_to_utf8( $ItemName );
            $ItemNameInfo->AddData( "ItemMain" , $ItemMain );
            $ItemNameInfo->AddData( "ItemSub" , $ItemSub );
            $ItemNameInfo->AddData( "ItemName" , $ItemName );
            $ItemNameInfo->NextData();
        }
        
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT RefillNum,SerialTruemoney FROM Refill WHERE MemNum = %d" , $MemNum ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $RefillNum = $cNeoSQLConnectODBC->Result( "RefillNum" , ODBC_RETYPE_INT );
            $SerialTruemoney = $cNeoSQLConnectODBC->Result( "SerialTruemoney" , ODBC_RETYPE_ENG );
            $RefillInfo->AddData( "RefillNum" , $RefillNum );
            $RefillInfo->AddData( "SerialTruemoney" , $SerialTruemoney );
            $RefillInfo->NextData();
        }
        
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT RefillNum,ItemMain,ItemSub,LogDate FROM Log_SysItemPointGet WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pUserInfo->UserNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $RefillNum = $cNeoSQLConnectODBC->Result( "RefillNum" , ODBC_RETYPE_INT );
            $ItemMain = $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT );
            $ItemSub = $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT );
            $pData->AddData( "ItemMain" , $ItemMain );
            $pData->AddData( "ItemSub" , $ItemSub );
            $pData->AddData( "RefillNum" , $RefillNum );
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_THAI ) );
            
            $ItemName = "Not Found";
            for( $i = 0 ; $i < $ItemNameInfo->GetRollData() ; $i++ )
            {
                $ppData = $ItemNameInfo->GetData( $i );
                if ( $ppData["ItemMain"] == $ItemMain && $ppData["ItemSub"] == $ItemSub )
                {
                    $ItemName = $ppData["ItemName"];
                    break;
                }
            }
            $pData->AddData( "ItemName" , $ItemName );
            
            $SerialTruemoney = "Not Found";
            for( $i = 0 ; $i < $RefillInfo->GetRollData() ; $i++ )
            {
                $ppData = $RefillInfo->GetData( $i );
                if ( $ppData["RefillNum"] == $RefillNum )
                {
                    $SerialTruemoney = $ppData["SerialTrueMoney"];
                    break;
                }
            }
            $pData->AddData( "SerialTruemoney" , $SerialTruemoney );
            
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "LogDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "SerialTruemoney" ] , "" , "");
        table_log_easy_add_colume( sprintf( "%03d:%03d:%s" , $ppData[ "ItemMain" ] , $ppData[ "ItemSub" ] , $ppData[ "ItemName" ] ) , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_UPDATEPOINT()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_UPDATEPOINT" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ความเคลื่อนไหวพ้อย",3,"width:400px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "การเปลี่ยนแปลง" , "");
    table_log_easy_add_head_colume( "แก้ไขโดย Admin" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT UserPoint,bAdmin,LogDate FROM Log_UserPoint WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pUserInfo->UserNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "UserPoint" , $cNeoSQLConnectODBC->Result( "UserPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "bAdmin" , $cNeoSQLConnectODBC->Result( "bAdmin" , ODBC_RETYPE_INT ) );
            $pData->AddData( "LogDate" , $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_THAI ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    $TTTT = array( "No" , "Yes" );
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "LogDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "UserPoint" ] , "" , "");
        table_log_easy_add_colume( $TTTT[ $ppData[ "bAdmin" ] ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_TIME2POINT()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_TIME2POINT" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("แลกเปลี่ยนเวลาเป็นพ้อย",5,"width:400px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "เวลาก่อนแลก" , "");
    table_log_easy_add_head_colume( "พ้อยที่จะได้รับ" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนแลก" , "");
    table_log_easy_add_head_colume( "พ้อยหลังแลก" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT OldPoint,NewPoint,Time,TimePoint,DateTime FROM Log_Time2Point WHERE MemNum = %d AND UserNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pUserInfo->UserNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "OldPoint" , $cNeoSQLConnectODBC->Result( "OldPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewPoint" , $cNeoSQLConnectODBC->Result( "NewPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Time" , $cNeoSQLConnectODBC->Result( "Time" , ODBC_RETYPE_INT ) );
            $pData->AddData( "TimePoint" , $cNeoSQLConnectODBC->Result( "TimePoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "DateTime" , $cNeoSQLConnectODBC->Result( "DateTime" , ODBC_RETYPE_THAI ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "DateTime" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "Time" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "TimePoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "OldPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewPoint" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_CHARALL()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_CHARALL" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ตัวละครทั้งหมดของไอดีนี้",2,"width:400px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "ChaNum" , "");
    table_log_easy_add_head_colume( "ชื่อตัวละคร" , "" , "width:309px;");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        $cNeoSQLConnectODBC->QueryRanGame( sprintf( "SELECT ChaNum,ChaName FROM ChaInfo WHERE UserNum = %d ORDER BY ChaNum DESC" , $pUserInfo->UserNum ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
            $ChaName = CBinaryCover::tis620_to_utf8( $ChaName );
            $pData->AddData( "ChaNum" , $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ChaName" , $ChaName );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanGame();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "ChaNum" ] , "" , "");
        table_log_easy_add_colume( sprintf( "%s <button onclick=\"user2char(%d);\">แก้ไข</button>" , $ppData[ "ChaName" ] , $ppData[ "ChaNum" ] ) , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_SHOPBANK_VIEW()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_SHOPBANK_VIEW" , $MemNum , $pUserInfo->UserNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ไอเทมที่ยังค้างอยู่ในช่อง Bank(B)",5,"width:400px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "ชื่อไอเทม" , "");
    table_log_easy_add_head_colume( "วันเวลาที่ซื้อ" , "");
    table_log_easy_add_head_colume( "คำสั่ง" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP() , $cWeb->GetRanShop_User() , $cWeb->GetRanShop_Pass() , $cWeb->GetRanShop_DB() );
        $cNeoSQLConnectODBC->QueryRanShop( sprintf( "SELECT "
                . "PurKey,PurDate"
                . ",ItemMain = (SELECT ItemMain FROM ShopItemMap WHERE ProductNum = ShopPurchase.ProductNum)"
                . ",ItemSub = (SELECT ItemSub FROM ShopItemMap WHERE ProductNum = ShopPurchase.ProductNum)"
                . " FROM ShopPurchase"
                . " WHERE UserUID = '%s' AND PurFlag = 0"
                . " ORDER BY PurDate DESC"
                , $pUserInfo->UserID ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "PurKey" , $cNeoSQLConnectODBC->Result( "PurKey" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ItemMain" , $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ItemSub" , $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT ) );
            $pData->AddData( "PurDate" , $cNeoSQLConnectODBC->Result( "PurDate" , ODBC_RETYPE_ENG ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanShop();
        
        $cNeoSQLConnectODBC->ConnectRanWeb();
        for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
        {
            $ppData = $pData->GetData( $i );

            $szTemp = sprintf("SELECT ItemName FROM ItemProject WHERE MemNum = %d AND ItemMain = %d AND ItemSub = %d AND ItemDelete = 0" , $MemNum , $ppData[ "ItemMain" ] , $ppData[ "ItemSub" ]);
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $pData->SetData($i, "ItemName", CBinaryCover::tis620_to_utf8( $cNeoSQLConnectODBC->Result( "ItemName" , ODBC_RETYPE_THAI ) ));
            }
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( sprintf( "%03d:%03d %s" , $ppData[ "ItemMain" ] , $ppData[ "ItemSub" ] , $ppData[ "ItemName" ] ) , "" , "");
        table_log_easy_add_colume( $ppData[ "PurDate" ] , "" , "");
        table_log_easy_add_colume( sprintf( "<input type=\"button\" value=\"ลบไอเทม\" onclick=\"shoppurchase_delete(%d);\" />" , $ppData["PurKey"] ) , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function USER_LOGVIEW_SHOPBANK_VIEW_CMD_DELETE()
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $PurKey = CInput::GetInstance()->GetValueInt( "purkey" , IN_POST );
    
    if ( $PurKey > 0 )
    {
        $SESSION_CURRENT = sprintf( "%s%s%d%d" , session_id() , "USER_LOGVIEW_SHOPBANK_VIEW" , $MemNum , $pUserInfo->UserNum );
        phpFastCache::set( $SESSION_CURRENT , NULL , 0 );
        
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP() , $cWeb->GetRanShop_User() , $cWeb->GetRanShop_Pass() , $cWeb->GetRanShop_DB() );
        $szTemp = sprintf( "DELETE ShopPurchase WHERE UserUID = '%s' AND PurKey = %d" , $pUserInfo->UserID , $PurKey );
        $cNeoSQLConnectODBC->QueryRanShop( $szTemp );
        $cNeoSQLConnectODBC->CloseRanShop();
        
        echo "<font color=\"green\">ลบไอเทมสำเร็จ</font>";
    }else{
        echo "<font color=\"red\">ลบไอเทมไม่สำเร็จ</font>";
    }
}

?>
