<?php
function CHARACTER_LOGVIEW_MODE()
{
    printf( "<u>เมนูตรวจสอบ</u> : 
        <button onclick=\"charlogviewmode(0);\">ข้อมูลการย้ายแมพ</button> |
        <button onclick=\"charlogviewmode(1);\">ข้อมูลการย้ายโรงเรียน</button> |
        <button onclick=\"charlogviewmode(2);\">ข้อมูลการเปลี่ยนชื่อ</button> |
        <button onclick=\"charlogviewmode(3);\">ข้อมูลการเปลี่ยนอาชีพ</button>
        <button onclick=\"charlogviewmode(4);\">ข้อมูลการซื้อแต้มสกิล</button>
        <button onclick=\"charlogviewmode(5);\">ข้อมูลการจุติ</button>
        <button onclick=\"charlogviewmode(6);\">ข้อมูลการล้างความผิด</button>
        <button onclick=\"charlogviewmode(7);\">ข้อมูลการปรับแต่งสเตตัส</button>
        <button onclick=\"charlogviewmode(8);\">ข้อมูลการรีเซ็ตสกิล</button>
        <br>" );
}

function CHARACTER_LOGVIEW_MAPMOVE()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_MAPMOVE" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการย้ายแมพ",5,"width:800px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "ย้ายไปแมพ" , "" , "width:199px;");
    table_log_easy_add_head_colume( "ราคาวาร์ป" , "");
    table_log_easy_add_head_colume( "พ้อยที่มีก่อนวาร์ป" , "");
    table_log_easy_add_head_colume( "พ้อยที่เหลือจากการวาร์ป" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pMapList = new CMapList();
        $pMapList->LoadMapData($MemNum);
        
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT OldPoint,NewPoint,GoMap,MapPoint,CreateDate FROM Log_MapWarp WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "OldPoint" , $cNeoSQLConnectODBC->Result( "OldPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewPoint" , $cNeoSQLConnectODBC->Result( "NewPoint" , ODBC_RETYPE_INT ) );
            $GoMap = $cNeoSQLConnectODBC->Result( "GoMap" , ODBC_RETYPE_INT );
            $pData->AddData( "GoMap" , $GoMap );
            $pData->AddData( "MapName" , $pMapList->MapName[ $GoMap ] );
            $pData->AddData( "MapPoint" , $cNeoSQLConnectODBC->Result( "MapPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "CreateDate" , $cNeoSQLConnectODBC->Result( "CreateDate" , ODBC_RETYPE_THAI ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "CreateDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "MapName" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "MapPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "OldPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewPoint" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function CHARACTER_LOGVIEW_CHANGESCHOOL()
{
    global $_CONFIG;
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_CHANGESCHOOL" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการย้ายโรงเรียน",5,"width:800px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "โรงเรียนก่อนย้าย" , "");
    table_log_easy_add_head_colume( "ย้ายไปโรงเรียน" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนทำรายการ" , "");
    table_log_easy_add_head_colume( "พ้อยหลังจากทำรายการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT School,ToSchool,UserPoint_Before,UserPoint_New,LogDate FROM Log_ChangeSchool WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "School" , $cNeoSQLConnectODBC->Result( "School" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ToSchool" , $cNeoSQLConnectODBC->Result( "ToSchool" , ODBC_RETYPE_INT ) );
            $pData->AddData( "UserPoint_Before" , $cNeoSQLConnectODBC->Result( "UserPoint_Before" , ODBC_RETYPE_INT ) );
            $pData->AddData( "UserPoint_New" , $cNeoSQLConnectODBC->Result( "UserPoint_New" , ODBC_RETYPE_INT ) );
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
        table_log_easy_add_colume( $_CONFIG["SCHOOL"][$ppData[ "School" ]] , "" , "");
        table_log_easy_add_colume( $_CONFIG["SCHOOL"][$ppData[ "ToSchool" ]] , "" , "");
        table_log_easy_add_colume( $ppData[ "UserPoint_Before" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "UserPoint_New" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function CHARACTER_LOGVIEW_CHANGENAME()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_CHANGENAME" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการเปลี่ยนแปลงชื่อของตัวละคร",5,"width:1000px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "ชื่อก่อนเปลี่ยน" , "");
    table_log_easy_add_head_colume( "ชื่อที่เปลี่ยน" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนทำรายการ" , "");
    table_log_easy_add_head_colume( "พ้อยหลังจากทำรายการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT OldChaName,NewChaName,OldPoint,NewPoint,LogDate FROM Log_ChangeName WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "OldChaName" , CBinaryCover::tis620_to_utf8( $cNeoSQLConnectODBC->Result( "OldChaName" , ODBC_RETYPE_THAI ) ) );
            $pData->AddData( "NewChaName" , CBinaryCover::tis620_to_utf8( $cNeoSQLConnectODBC->Result( "NewChaName" , ODBC_RETYPE_THAI ) ) );
            $pData->AddData( "OldPoint" , $cNeoSQLConnectODBC->Result( "OldPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewPoint" , $cNeoSQLConnectODBC->Result( "NewPoint" , ODBC_RETYPE_INT ) );
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
        table_log_easy_add_colume( $ppData[ "OldChaName" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewChaName" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "OldPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewPoint" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function CHARACTER_LOGVIEW_CHANGECLASS()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_CHANGECLASS" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการเปลี่ยนอาชีพของตัวละคร",5,"width:800px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "อาชีพก่อนเปลี่ยน" , "");
    table_log_easy_add_head_colume( "อาชีพหลังเปลี่ยน" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนทำรายการ" , "");
    table_log_easy_add_head_colume( "พ้อยหลังจากทำรายการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $cMemClass = new CMemClass;
        $cMemClass->LoadData( $MemNum );
        
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT ChaClass,ToClass,UserPoint_Before,UserPoint_New,LogDate FROM Log_ChangeClass WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "ChaClass" , $cMemClass->ClassName_Arr[ $cNeoSQLConnectODBC->Result( "ChaClass" , ODBC_RETYPE_INT ) ] );
            $pData->AddData( "ToClass" , $cMemClass->ClassName_Arr[ $cNeoSQLConnectODBC->Result( "ToClass" , ODBC_RETYPE_INT ) ] );
            $pData->AddData( "UserPoint_Before" , $cNeoSQLConnectODBC->Result( "UserPoint_Before" , ODBC_RETYPE_INT ) );
            $pData->AddData( "UserPoint_New" , $cNeoSQLConnectODBC->Result( "UserPoint_New" , ODBC_RETYPE_INT ) );
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
        table_log_easy_add_colume( $ppData[ "ChaClass" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "ToClass" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "UserPoint_Before" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "UserPoint_New" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function CHARACTER_LOGVIEW_BUYSKILLPOINT()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_BUYSKILLPOINT" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการซื้อแต้มสกิล",7,"width:1000px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "แต้มสกิลที่จะได้รับ" , "");
    table_log_easy_add_head_colume( "แต้มสกิลก่อนซื้อ" , "");
    table_log_easy_add_head_colume( "แต้มสกิลหลังซื้อ" , "");
    table_log_easy_add_head_colume( "พ้อยในการทำรายการ" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนทำรายการ" , "");
    table_log_easy_add_head_colume( "พ้อยหลังจากทำรายการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $cMemClass = new CMemClass;
        $cMemClass->LoadData( $MemNum );
        
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT OldSkillPoint,NewSkillPoint,GetSkillPoint,OldPoint,NewPoint,DelPoint,LogDate FROM Log_BuySkillPoint WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "OldSkillPoint" , $cNeoSQLConnectODBC->Result( "OldSkillPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewSkillPoint" , $cNeoSQLConnectODBC->Result( "NewSkillPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "GetSkillPoint" , $cNeoSQLConnectODBC->Result( "GetSkillPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "OldPoint" , $cNeoSQLConnectODBC->Result( "OldPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewPoint" , $cNeoSQLConnectODBC->Result( "NewPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "DelPoint" , $cNeoSQLConnectODBC->Result( "DelPoint" , ODBC_RETYPE_INT ) );
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
        table_log_easy_add_colume( $ppData[ "GetSkillPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "OldSkillPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewSkillPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "DelPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "OldPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewPoint" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function CHARACTER_LOGVIEW_REBORN()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_REBORN" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการจุติ",2,"width:300px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "จุติครั้งที่" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $cMemClass = new CMemClass;
        $cMemClass->LoadData( $MemNum );
        
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT Reborn,RebornDate FROM Log_Reborn WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "Reborn" , $cNeoSQLConnectODBC->Result( "Reborn" , ODBC_RETYPE_INT ) );
            $pData->AddData( "RebornDate" , $cNeoSQLConnectODBC->Result( "RebornDate" , ODBC_RETYPE_THAI ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "RebornDate" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "Reborn" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function CHARACTER_LOGVIEW_CHARMAD()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_CHARMAD" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการล้างความผิด",3,"width:300px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนทำรายการ" , "");
    table_log_easy_add_head_colume( "พ้อยหลังทำรายการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $cMemClass = new CMemClass;
        $cMemClass->LoadData( $MemNum );
        
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT UserPoint_Before,UserPoint_New,LogDate FROM Log_CharMad WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "UserPoint_Before" , $cNeoSQLConnectODBC->Result( "UserPoint_Before" , ODBC_RETYPE_INT ) );
            $pData->AddData( "UserPoint_New" , $cNeoSQLConnectODBC->Result( "UserPoint_New" , ODBC_RETYPE_INT ) );
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
        table_log_easy_add_colume( $ppData[ "UserPoint_Before" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "UserPoint_New" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function CHARACTER_LOGVIEW_STAT()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_STAT" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการปรับแต่งสเตตัส",4,"width:300px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "ข้อมูล" , "");
    table_log_easy_add_head_colume( "พ้อยก่อนทำรายการ" , "");
    table_log_easy_add_head_colume( "พ้อยหลังทำรายการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $cMemClass = new CMemClass;
        $cMemClass->LoadData( $MemNum );
        
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT Pow,Pow2,Dex,Dex2,Spi,Spi2,Str1,Str2,Stm,Stm2,StRemain,StRemain2,OldPoint,NewPoint,ModifyDate FROM Log_Stat WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "Pow" , $cNeoSQLConnectODBC->Result( "Pow" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Pow2" , $cNeoSQLConnectODBC->Result( "Pow2" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Dex" , $cNeoSQLConnectODBC->Result( "Dex" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Dex2" , $cNeoSQLConnectODBC->Result( "Dex2" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Spi" , $cNeoSQLConnectODBC->Result( "Spi" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Spi2" , $cNeoSQLConnectODBC->Result( "Spi2" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Str1" , $cNeoSQLConnectODBC->Result( "Str1" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Str2" , $cNeoSQLConnectODBC->Result( "Str2" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Stm" , $cNeoSQLConnectODBC->Result( "Stm" , ODBC_RETYPE_INT ) );
            $pData->AddData( "Stm2" , $cNeoSQLConnectODBC->Result( "Stm2" , ODBC_RETYPE_INT ) );
            $pData->AddData( "StRemain" , $cNeoSQLConnectODBC->Result( "StRemain" , ODBC_RETYPE_INT ) );
            $pData->AddData( "StRemain2" , $cNeoSQLConnectODBC->Result( "StRemain2" , ODBC_RETYPE_INT ) );
            $pData->AddData( "OldPoint" , $cNeoSQLConnectODBC->Result( "OldPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewPoint" , $cNeoSQLConnectODBC->Result( "NewPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ModifyDate" , $cNeoSQLConnectODBC->Result( "ModifyDate" , ODBC_RETYPE_THAI ) );
            $pData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        phpFastCache::set( $SESSION_CURRENT , $pData , DELAY_FOR_VIEWLOG );
    }
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        
        table_log_easy_line_begin();
        table_log_easy_add_colume( $ppData[ "ModifyDate" ] , "" , "");
        
        table_log_easy_add_colume( sprintf( "ก่อนทำรายการ : Pow => %d,Dex => %d,Spi => %d,Str => %d,Stm => %d,StRemain => %d<br>
หลังทำรายการ : Pow => %d,Dex => %d,Spi => %d,Str => %d,Stm => %d,StRemain => %d"
                                            , $ppData[ "Pow" ]
                                            , $ppData[ "Dex" ]
                                            , $ppData[ "Spi" ]
                                            , $ppData[ "Str1" ]
                                            , $ppData[ "Stm" ]
                                            , $ppData[ "StRemain" ]
                                            , $ppData[ "Pow2" ]
                                            , $ppData[ "Dex2" ]
                                            , $ppData[ "Spi2" ]
                                            , $ppData[ "Str2" ]
                                            , $ppData[ "Stm2" ]
                                            , $ppData[ "StRemain2" ]
                                ) , "" , "");
        
        table_log_easy_add_colume( $ppData[ "OldPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewPoint" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

function CHARACTER_LOGVIEW_RESETSKILL()
{
    global $_CONFIG;
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $SESSION_CURRENT = sprintf( "%s%s%d%d%d" , session_id() , "CHARACTER_LOGVIEW_RESETSKILL" , $MemNum , $pChaInfo->UserNum , $pChaInfo->ChaNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการรีเซ็ตสกิล",3,"width:400px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "");
    table_log_easy_add_head_colume( "พ้อยที่ใช้ในการทำรายการ" , "");
    table_log_easy_add_head_colume( "พ้อยหลังจากทำรายการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT OldPoint,NewPoint,LogDate FROM Log_ResetSkill WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum
                                                    , $pChaInfo->UserNum
                                                    , $pChaInfo->ChaNum
                ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "OldPoint" , $cNeoSQLConnectODBC->Result( "OldPoint" , ODBC_RETYPE_INT ) );
            $pData->AddData( "NewPoint" , $cNeoSQLConnectODBC->Result( "NewPoint" , ODBC_RETYPE_INT ) );
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
        table_log_easy_add_colume( $ppData[ "OldPoint" ] , "" , "");
        table_log_easy_add_colume( $ppData[ "NewPoint" ] , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

?>
