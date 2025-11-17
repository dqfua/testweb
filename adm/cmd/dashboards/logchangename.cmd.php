<?php

function LOGUI()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $SESSION_CURRENT = sprintf( "%s%s%d" , session_id() , "LOGCHAGENAME" , $MemNum );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลการเปลี่ยนชื่อตัวละคร",5,"width:800px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "วันเวลา" , "" , "width:199px;");
    table_log_easy_add_head_colume( "ข้อมูลการทำรายการ" , "");
    table_log_easy_line_end();
    
    $pData = phpFastCache::get( $SESSION_CURRENT );
    if ( !$pData )
    {
        $pMapList = new CMapList();
        $pMapList->LoadMapData($MemNum);
        
        $pData = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb( );
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT TOP 100 LogDate,UserNum,ChaNum,OldChaName,NewChaName FROM Log_ChangeName WHERE MemNum = %d ORDER BY LogNum DESC" 
                                                    , $MemNum ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pData->AddData( "UserNum" , $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT ) );
            $pData->AddData( "ChaNum" , $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT ) );
            $pData->AddData( "OldChaName" , CBinaryCover::tis620_to_utf8( $cNeoSQLConnectODBC->Result( "OldChaName" , ODBC_RETYPE_THAI ) ) );
            $pData->AddData( "NewChaName" , CBinaryCover::tis620_to_utf8( $cNeoSQLConnectODBC->Result( "NewChaName" , ODBC_RETYPE_THAI ) ) );
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
        //table_log_easy_add_colume( sprintf( "<a href=\"javascript:char2user_div( %d , '#main_process' );\">%s เปลี่ยนเป็น %s</a>" , $ppData[ "UserNum" ] , $ppData[ "OldChaName" ] , $ppData[ "NewChaName" ] ) , "" , "");
        table_log_easy_add_colume( sprintf( "<a href=\"javascript:char2user( %d );\">%s เปลี่ยนเป็น %s</a>" , $ppData[ "UserNum" ] , $ppData[ "OldChaName" ] , $ppData[ "NewChaName" ] ) , "" , "");
        table_log_easy_line_end();
    }
    
    table_log_easy_end();
}

LOGUI();
?>
<div id="player_main">
    <div id="player_process">
        <div id="userprocess"></div>
    </div>
</div>
<script type="text/javascript" src="js/player.js"></script>