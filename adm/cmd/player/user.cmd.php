<?php
$arrUserType = array( "UserNum" , "UserID" );
$SESSION_USERSEL = "USERSEL";

function USER_MENU_UI( $pUserInfo )
{
    printf( "<b>ข้อมูลไอดี UserNum : %d , ID : %s , สมัครไอดีเมื่อ %s , เข้าเกมล่าสุด %s , Email %s</b>" , $pUserInfo->UserNum , $pUserInfo->UserID , substr( $pUserInfo->CreateDate , 0 , -4 ) , substr( $pUserInfo->LastLoginDate , 0 , -4 ) , $pUserInfo->UserEmail );
    printf( "<p><u>เมนูสำหรับตรวจสอบ</u> : <button onclick=\"usermenumode(100);\">ข้อมูลการโดนระงับไอดี</button>
                                         <button onclick=\"usermenumode(101);\">ข้อมูลการเติมบัตร</button>
                                         <button onclick=\"usermenumode(102);\">ข้อมูลการซื้อไอเทม</button>
                                         <button onclick=\"usermenumode(103);\">ข้อมูลการขายไอเทม</button>
                                         <button onclick=\"usermenumode(112);\">แสดงไอเทมที่ค้างอยู่ในช่อง Bank(B)</button>
                                         <button onclick=\"usermenumode(104);\">ประวัติล็อกอินเข้าไอเทมช็อป</button>
                                         <button onclick=\"usermenumode(105);\">ประวัติของรางวัลที่ได้รับเมื่อเติมบัตร</button>
                                         <button onclick=\"usermenumode(106);\">ข้อมูลการอัพเดทพ้อย</button>
                                         <button onclick=\"usermenumode(107);\">ประวัติแลกเวลาออนไลน์เป็นพ้อย</button>
                                         <button onclick=\"usermenumode(108);\">ล็อกอินเกม</button>
                                         <button onclick=\"usermenumode(109);\">Bonus Point Invite Friends</button>
                                         <button onclick=\"usermenumode(111);\">ประวัติแต้มสะสม</button>
                                         <button onclick=\"usermenumode(110);\">ประวัติการกู้รหัสผ่าน</button>
                                         <br>" );
    printf( "<u>เมนูสำหรับแก้ไข</u> : <button onclick=\"usermenumode(0);\">แก้ไขทั่วไป</button>
                                   <button onclick=\"usermenumode(1);\">แก้ไขล็อกเกอร์</button>
                                   <button onclick=\"usermenumode(2);\">ตัวละครทั้งหมดของไอดีนี้</button>
                                   </p>" );
}

function USER_BANIPSET_PROCESS()
{
    global $cAdmin;
    global $SESSION_USERSEL;
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    $MemNum = $cAdmin->GetMemNum();
    
    function editIP2Work( $txt )
    {
        $TXT_WORK = "0123456789.";
        $TXT_WORK_ARR = str_split($TXT_WORK);

        $txt_arr = str_split($txt);
        $txt_new = "";
        foreach( $txt_arr as $key => $value )
        {
            foreach( $TXT_WORK_ARR as $keyw => $valuew )
            {
                if ( $value == $valuew )
                {
                    $txt_new .= $value;
                    break;
                }
            }
        }

        return $txt_new;
    }
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $szIP = CInput::GetInstance()->GetValueString( "ip" , IN_POST );
    $szIP = str_replace( "&#32;" , "" , $szIP );
    $szIP = editIP2Work( $szIP );
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    
    $pData = new _tdata();
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
    $szTemp = sprintf( "SELECT TOP 200 UserID,LogDate,getdate() as NowDate FROM LogLogin WHERE LogIpAddress = '%s' ORDER BY LoginNum DESC" , $szIP );
    //echo $szTemp;
    $pQuery = $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
    //var_dump( $cNeoSQLConnectODBC->FetchRow() );
    while( $cNeoSQLConnectODBC->FetchRowMan( $pQuery ) )
    {
        $UserID = $cNeoSQLConnectODBC->Result( "UserID" , ODBC_RETYPE_ENG );
        $LogDate = $cNeoSQLConnectODBC->Result( "LogDate" , ODBC_RETYPE_ENG );
        $NowDate = $cNeoSQLConnectODBC->Result( "NowDate" , ODBC_RETYPE_ENG );
        
        $Bettween = (int)CGlobal::DateDiff(substr($LogDate,0,10),substr($NowDate,0,10));
        //echo $LogDate . " : " . $NowDate . " : " . $Bettween . "<br>";
        
        if ( $Bettween > 7 ) break; // แบนย้อนหลัง 7 วัน
        
        $bAdd = TRUE;
        for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
        {
            $ppData = $pData->GetData( $i );
            
            if ( $ppData[ "UserID" ] == $UserID )
            {
                $bAdd = FALSE;
                break;
            }
        }
        
        if ( $bAdd == FALSE ) continue;
        $pData->AddData( "UserID" , $UserID );
        $pData->NextData();
    }
    
    $szTemp = "";
    
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        $UserID = $ppData[ "UserID" ];
        $szTemp .= sprintf( "UPDATE UserInfo SET UserBlock = 1 , UserBlockDate = getdate()+99999 WHERE UserID = '%s'" , $UserID );
        
        if ( ( $i % 10 == 9 ) )
        {
            $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
            $szTemp = "";
        }
    }
    
    if ( strlen($szTemp) ) { $cNeoSQLConnectODBC->QueryRanUser( $szTemp ); $szTemp = ""; }
    
    $cNeoSQLConnectODBC->CloseRanUser();
    
    echo "ไอดีที่ถูกแบนทั้งหมด : ";
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
        //$UserNum = $ppData[ "UserNum" ];
        $UserID = $ppData[ "UserID" ];
        if ( $i ) echo ",";
        //printf( "<a href=\"javascript:char2user( %d );\">%s</a>" , $UserNum , $UserID );
		printf( "%s" , $UserID );
        if ( $i % 10 == 9 ) echo "<br>";
    }
}

function USER_BANIPSET_UI()
{
    CInput::GetInstance()->BuildFrom( IN_POST );
    $szIP = CInput::GetInstance()->GetValueString( "ip" , IN_POST );
	//$szIP = ereg_replace('[[:space:]]+', '', trim($szIP));
	$szIP = str_replace( "&#32;" , "" , $szIP );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("แบนยกไอพี",2,"width:400px;");
    function trybuild( $text , $editor )
    {
        table_log_easy_line_begin();
        table_log_easy_add_colume( $text , "" , "");
        table_log_easy_add_colume( $editor , "" , "");
        table_log_easy_line_end();
    }
    
    trybuild( "ไอพีที่ต้องการแบน" , sprintf( '<input type="text" id="banip" class="" style="width:159px;" value="%s">' , $szIP ) );
    trybuild( "" , sprintf( "<button id=\"stbt\" onclick=\"user_setbanip();\">แบน</button>" ) );
    table_log_easy_end();
}

function USER_LOCKER_EDITOR_SAVE( $bDel = false )
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $ItemMain = CInput::GetInstance()->GetValueInt( "ItemMain" , IN_POST );
    $ItemSub = CInput::GetInstance()->GetValueInt( "ItemSub" , IN_POST );
    $ItemDamage = CInput::GetInstance()->GetValueInt( "ItemDamage" , IN_POST );
    $ItemDefense = CInput::GetInstance()->GetValueInt( "ItemDefense" , IN_POST );
    $Item_TurnNum = CInput::GetInstance()->GetValueInt( "Item_TurnNum" , IN_POST );
    $Item_Drop = CInput::GetInstance()->GetValueInt( "Item_Drop" , IN_POST );
    $Item_Res_Ele = CInput::GetInstance()->GetValueInt( "Item_Res_Ele" , IN_POST );
    $Item_Res_Fire = CInput::GetInstance()->GetValueInt( "Item_Res_Fire" , IN_POST );
    $Item_Res_Ice = CInput::GetInstance()->GetValueInt( "Item_Res_Ice" , IN_POST );
    $Item_Res_Poison = CInput::GetInstance()->GetValueInt( "Item_Res_Poison" , IN_POST );
    $Item_Res_Spirit = CInput::GetInstance()->GetValueInt( "Item_Res_Spirit" , IN_POST );
    $Item_Op1 = CInput::GetInstance()->GetValueInt( "Item_Op1" , IN_POST );
    $Item_Op2 = CInput::GetInstance()->GetValueInt( "Item_Op2" , IN_POST );
    $Item_Op3 = CInput::GetInstance()->GetValueInt( "Item_Op3" , IN_POST );
    $Item_Op4 = CInput::GetInstance()->GetValueInt( "Item_Op4" , IN_POST );
    $Item_Op1_Value = CInput::GetInstance()->GetValueInt( "Item_Op1_Value" , IN_POST );
    $Item_Op2_Value = CInput::GetInstance()->GetValueInt( "Item_Op2_Value" , IN_POST );
    $Item_Op3_Value = CInput::GetInstance()->GetValueInt( "Item_Op3_Value" , IN_POST );
    $Item_Op4_Value = CInput::GetInstance()->GetValueInt( "Item_Op4_Value" , IN_POST );
    
    $Item_InvenX = CInput::GetInstance()->GetValueInt( "Item_InvenX" , IN_POST );
    $Item_InvenY = CInput::GetInstance()->GetValueInt( "Item_InvenY" , IN_POST );
    
    $l = CInput::GetInstance()->GetValueInt( "l" , IN_POST );
    CheckNumZero($l);
    if ( $l > MAX_USER_INVEN ) $l = MAX_USER_INVEN;
    
    CheckNumZero($ItemMain);
    CheckNumZero($ItemSub);
    CheckNumZero($ItemDamage);
    CheckNumZero($ItemDefense);
    CheckNumZero($Item_TurnNum);
    CheckNumZero($Item_Res_Ele);
    CheckNumZero($Item_Res_Fire);
    CheckNumZero($Item_Res_Ice);
    CheckNumZero($Item_Res_Poison);
    CheckNumZero($Item_Res_Spirit);
    //CheckNumZero($Item_Op1_Value);
    //CheckNumZero($Item_Op2_Value);
    //CheckNumZero($Item_Op3_Value);
    //CheckNumZero($Item_Op4_Value);
    
    CheckNumZero($Item_InvenX);
    CheckNumZero($Item_InvenY);
    
    if ( !arrkeycheck(ItemDropData(), $Item_Drop ) ) die("ERROR:ITEMDROP");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op1 ) ) die("ERROR:ITEMOP1");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op2 ) ) die("ERROR:ITEMOP2");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op3 ) ) die("ERROR:ITEMOP3");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op4 ) ) die("ERROR:ITEMOP4");
    
    global $SESSION_USERSEL;
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    $Inventory = $pUserInfo->NeoUserInven->Inventory[$l];
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cWeb->GetSysmFromDB( $MemNum );
    $cNeoSerialMemory = CRanShop::BuildItem2ChaInven($Item_InvenX, $Item_InvenY, $ItemMain, $ItemSub, time(), $Item_TurnNum, $Item_Drop, $ItemDamage, $ItemDefense, $Item_Res_Fire, $Item_Res_Ice, $Item_Res_Ele, $Item_Res_Poison, $Item_Res_Spirit, $Item_Op1, $Item_Op2, $Item_Op3, $Item_Op4, $Item_Op1_Value, $Item_Op2_Value, $Item_Op3_Value, $Item_Op4_Value, $cWeb->GetServerType() );
    
    $idm = $Inventory->InvenFind( $Item_InvenX , $Item_InvenY );
    if ( $idm != ITEM_ERROR )
    {
        $Inventory->DeleteItem( $idm );
    }
    
    if ( $bDel == false ) $Inventory->AddItem( $cNeoSerialMemory );
    $Inventory->UpdateVar2Binary();
    $pBuffer = $Inventory->SaveChaInven();
    
    $cNeoSerialMemory->OpenMemory();
    $cNeoSerialMemory->WriteInt( $pUserInfo->NeoUserInven->SlotStore );
    
    
    for( $i = 0 ; $i < $pUserInfo->NeoUserInven->SlotStore ; $i++ )
    {
        if ( $i == $l )
            $cNeoSerialMemory->WriteBuffer( $pBuffer );
        else
        {
            $pppInventory = $pUserInfo->NeoUserInven->Inventory[$i];
            $ppBuff = $pppInventory->SaveChaInven();
            $cNeoSerialMemory->WriteBuffer( $ppBuff );
        }
    }
    
    $szTemp = sprintf("UPDATE UserInven SET UserInven = 0x%s WHERE UserNum = %d",$cNeoSerialMemory->GetBuffer(),$pUserInfo->UserNum);
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
    $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
    $cNeoSQLConnectODBC->CloseRanGame();
    
    CInput::GetInstance()->AddValue( $SESSION_USERSEL , serialize( $pUserInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "<span class=\"info\">บันทึกเรียบร้อยแล้ว!!</span>";
}

function USER_LOCKER_EDITOR_UI()
{
    global $SESSION_USERSEL;
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $l = CInput::GetInstance()->GetValueInt( "l" , IN_POST );
    $x = CInput::GetInstance()->GetValueInt( "x" , IN_POST );
    $y = CInput::GetInstance()->GetValueInt( "y" , IN_POST );
    
    CheckNumZero($l);
    CheckNumZero($x);
    CheckNumZero($y);
    
    if ( $l > MAX_USER_INVEN ) $l = MAX_USER_INVEN;
    
    $Inventory = $pUserInfo->NeoUserInven->Inventory[$l];
    $idm = $Inventory->InvenFind( $x , $y );
    $pItemTemp = new ItemProjectTEMP;
    buildItemCustomEditor($pItemTemp, $Inventory, $idm, sprintf( "<button onclick=\"locker_save( %d,%d,%d );\">Save</button>" , $l , $x , $y ) );
}

function USER_LOCKER_EDITOR()
{
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    if ( !$pUserInfo->NeoUserInven )
    {
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        $cNeoSQLConnectODBC->QueryRanGame( sprintf( "SELECT UserInven FROM UserInven WHERE UserNum = %d" , $pUserInfo->UserNum ) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pUserInfo->UserInven = $cNeoSQLConnectODBC->Result( "UserInven" , ODBC_RETYPE_BINARY );
            
            $pUserInfo->NeoUserInven = new UserInven();
            $pUserInfo->NeoUserInven->ReadUserInven( $pUserInfo->UserInven );
        }
        $cNeoSQLConnectODBC->CloseRanGame();
        
        CInput::GetInstance()->AddValue( $SESSION_USERSEL , serialize( $pUserInfo ) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
    }
    
    function buildlocker( $pUserInfo , $i )
    {
        printf( "<table class=\"gridtable\"><tr><td><u>ล็อกเกอร์ช่องที่ <b>%d</b> จำนวนไอเทมในกระเป๋าทั้งหมดคือ : <b>%d</b> <button onclick=\"locker_show(%d);\">Event</button></u></td></tr><tr><td>" , $i , $pUserInfo->NeoUserInven->Inventory[$i]->GetItemNum() , $i );
        printf( "<table id=\"locker_slot_%d\" border=\"3\" cellspacing=\"3\" cellpadding=\"3\" style=\"display:none;\">" , $i );
        for( $y = 0 ; $y < 4 ; $y ++ )
        {
            echo "<tr>";
            for( $x = 0 ; $x < 6 ; $x ++ )
            {
                $itemname = "";
                $bgcolor = "FF0000";
                $idm = $pUserInfo->NeoUserInven->Inventory[$i]->InvenFind( $x , $y );
                if ( $idm == ITEM_ERROR )
                {
                    $itemname = sprintf( "<button onclick=\"locker_edit( %d , %d , %d );\">ADD</button>" , $i , $x , $y );
                }else{
                    $itemmain = $pUserInfo->NeoUserInven->Inventory[$i]->GetItemMain( $idm );
                    $itemsub = $pUserInfo->NeoUserInven->Inventory[$i]->GetItemSub( $idm );
                    $itemname = sprintf( "<p>%03d:%03d<br><button onclick=\"locker_edit( %d , %d , %d );\">Edit</button><br><button onclick=\"locker_del( %d , %d , %d );\">Del</button></p>" , $itemmain , $itemsub , $i , $x , $y , $i , $x , $y );
                    $bgcolor = "30FF50";
                }
                printf( "<td style=\"width:99px;height:99px;background-color:#%s\"><div align=\"center\">%s</div></td>" , $bgcolor , $itemname );
            }
            echo "</tr>";
        }
        echo "</table></td></tr></table>";
    }
    
    buildlocker( $pUserInfo , 0 );
    buildlocker( $pUserInfo , 1 );
    buildlocker( $pUserInfo , 2 );
    buildlocker( $pUserInfo , 3 );
    buildlocker( $pUserInfo , 4 );
}

function USER_INFO_PASS_EDITOR_SAVE( $type )
{
    global $_CONFIG;
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $Pass = CInput::GetInstance()->GetValueString( "Pass" , IN_POST );
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cWeb->GetSysmFromDB( $MemNum );
    CGlobal::SetPassMD5( $Pass , $_CONFIG["PASSWORD_MD5"][ $cWeb->GetServerType() ] );
    
    $szTemp = "UPDATE UserInfo SET ";
    switch( $type )
    {
        case 0:
        {
            $szTemp .= sprintf( "UserPass = '%s'" , $Pass );
        }break;
        case 1:
        {
            $szTemp .= sprintf( "UserPass2 = '%s'" , $Pass );
        }break;
    }
    $szTemp .= sprintf( " WHERE UserNum = %d" , $pUserInfo->UserNum );
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
    $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
    $cNeoSQLConnectODBC->CloseRanUser();
    
    if ( $type == 0 )
        $pUserInfo->UserPass = $Pass;
    else
        $pUserInfo->UserPass2 = $Pass;
    
    CInput::GetInstance()->AddValue( $SESSION_USERSEL , serialize( $pUserInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo '<span class="info">เปลี่ยนรหัสผ่านเรียบร้อย</span>';
}

function USER_INFO_PASS_EDITOR( $type )
{
    global $cAdmin;
    global $SESSION_USERSEL;
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลทั่วไป",2,"width:400px;");
    function trybuild( $text , $editor )
    {
        table_log_easy_line_begin();
        table_log_easy_add_colume( $text , "" , "");
        table_log_easy_add_colume( $editor , "" , "");
        table_log_easy_line_end();
    }
    
    if ( $cAdmin->nServerType == SERVTYPE_EP3 )
    {
        if ( $type == 0 )
            trybuild( "รหัสผ่านปัจจุบัน" , $pUserInfo->UserPass );
        else
            trybuild( "รหัสผ่านปัจจุบัน" , $pUserInfo->UserPass2 );
    }
    
    trybuild( "ใส่รหัสผ่านใหม่ที่ต้องการ" , '<input type="password" id="Pass" class="" style="width:159px;">' );
    trybuild( "ยืนยันรหัสผ่านอีกครั้ง" , '<input type="password" id="Pass2" class="" style="width:159px;">' );
    trybuild( "" , sprintf( "<button onclick=\"user_passsave(%d);\">บันทึก</button>" , $type ) );
    table_log_easy_end();
}

function USER_INFO_EDITOR()
{
    global $SESSION_USERSEL;
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    $blockarr = array( "No Block" , "Block" );
    
    table_log_easy_begin( "gridtable" );
    table_log_easy_title("ข้อมูลทั่วไป",2,"width:400px;");
    
    function trybuild( $text , $editor )
    {
        table_log_easy_line_begin();
        table_log_easy_add_colume( $text , "" , "");
        table_log_easy_add_colume( $editor , "" , "");
        table_log_easy_line_end();
    }
    
    function buildInputText( $textid , $value , $readonly )
    {
        return sprintf( '<input type="text" id="%s" class="" %s value="%s">' , $textid , $readonly , $value );
    }
    
    trybuild( "UserNum" , buildInputText( "UserNum" , $pUserInfo->UserNum, "readonly" ) );
    trybuild( "UserName" , buildInputText( "UserName" , $pUserInfo->UserName, "" ) );
    trybuild( "UserID" , buildInputText( "UserID" , $pUserInfo->UserID, "" ) );
    trybuild( "UserPass" , sprintf( "<button onclick=\"user_changepass(0);\">เปลี่ยนรหัสผ่าน</button>" ) );
    trybuild( "UserPass2" , sprintf( "<button onclick=\"user_changepass(1);\">เปลี่ยนรหัสผ่าน</button>" ) );
    trybuild( "UserType" , buildInputText( "UserType" , $pUserInfo->UserType, "" ) );
    trybuild( "UserLoginState" , buildInputText( "UserLoginState" , $pUserInfo->UserLoginState, "" ) );
    trybuild( "ChaRemain" , buildInputText( "ChaRemain" , $pUserInfo->ChaRemain, "" ) );
    trybuild( "UserPoint" , buildInputText( "UserPoint" , $pUserInfo->UserPoint, "" ) );
    trybuild( "BonusPoint" , buildInputText( "BonusPoint" , $pUserInfo->BonusPoint, "" ) );
    trybuild( "UserGameOnlineTime" , buildInputText( "UserGameOnlineTime" , $pUserInfo->UserGameOnlineTime, "" ) );
    trybuild( "UserBlock" , buildSelectText("UserBlock", "", $pUserInfo->UserBlock, $blockarr) );
    trybuild( "UserEmail" , buildInputText("UserEmail", $pUserInfo->UserEmail, "") );
    trybuild( "ParentID" , buildInputText("ParentID", $pUserInfo->ParentID, "") );
    
    trybuild( "" , sprintf( "<button onclick=\"user_save(%d);\">บันทึก</button>" , $pUserInfo->UserNum ) );
    
    table_log_easy_end();
}

function USER_INFO_SAVE()
{
    global $SESSION_USERSEL;
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $UserNum = CInput::GetInstance()->GetValueString( "UserNum" , IN_POST );
    $UserName = CInput::GetInstance()->GetValueString( "UserName" , IN_POST );
    $UserID = CInput::GetInstance()->GetValueString( "UserID" , IN_POST );
    $UserType = CInput::GetInstance()->GetValueInt( "UserType" , IN_POST );
    $UserLoginState = CInput::GetInstance()->GetValueInt( "UserLoginState" , IN_POST );
    $ChaRemain = CInput::GetInstance()->GetValueInt( "ChaRemain" , IN_POST );
    $UserPoint = CInput::GetInstance()->GetValueInt( "UserPoint" , IN_POST );
    $BonusPoint = CInput::GetInstance()->GetValueInt( "BonusPoint" , IN_POST );
    $UserGameOnlineTime = CInput::GetInstance()->GetValueInt( "UserGameOnlineTime" , IN_POST );
    $UserBlock = CInput::GetInstance()->GetValueInt( "UserBlock" , IN_POST );
    $UserEmail = CInput::GetInstance()->GetValueString( "UserEmail" , IN_POST );
    $ParentID = CInput::GetInstance()->GetValueString( "ParentID" , IN_POST );
	
    if ( $ParentID == $UserID  ) $ParentID = "";
    
    CheckNumZero($UserNum);
    CheckNumZero($UserType);
    CheckNumZero($UserLoginState);
    CheckNumZero($ChaRemain);
    CheckNumZero($UserPoint);
    CheckNumZero($BonusPoint);
    CheckNumZero($UserGameOnlineTime);
    CheckNumZero($UserBlock);
    
    $szTemp = sprintf( "UPDATE UserInfo SET
                                                    UserName ='%s',
                                                    UserID = '%s',
                                                    UserType = %d,
                                                    UserLoginState = %d,
                                                    ChaRemain = %d,
                                                    UserPoint = %d,
                                                    UserGameOnlineTime = %d,
                                                    UserBlock = %d,
                                                    UserBlockDate = getdate() + 99999
                                                    WHERE UserNum = %d
                                                "
                                                , $UserName
                                                , $UserID
                                                , $UserType
                                                , $UserLoginState
                                                , $ChaRemain
                                                , $UserPoint
                                                , $UserGameOnlineTime
                                                , $UserBlock
                                                , $UserNum
                        );
    //echo $szTemp;
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
    $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
    $cNeoSQLConnectODBC->CloseRanUser();
	
    $szTemp = sprintf( "UPDATE UserInfo SET UserEmail = '%s' , ParentID = '%s' , BonusPoint = %d WHERE MemNum = %d AND UserID = '%s'" , $UserEmail , $ParentID , $BonusPoint , $MemNum , $UserID );
    //echo $szTemp;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    if ( $pUserInfo->UserPoint != $UserPoint ) CNeoLog::LogUserPoint ($MemNum, $UserNum, $UserPoint, TRUE);
    if ( $pUserInfo->BonusPoint != $BonusPoint ) CNeoLog::LogUserBonusPoint ($MemNum, $UserNum, $pUserInfo->BonusPoint, $BonusPoint, 0, '', 1);
    
    if ( $pUserInfo->UserBlock != $UserBlock ) CNeoLog::Admin_LogBan ($MemNum, $UserNum, $UserBlock);
	
    $pUserInfo->UserName = $UserName;
    $pUserInfo->UserID = $UserID;
    $pUserInfo->UserType = $UserType;
    $pUserInfo->UserLoginState = $UserLoginState;
    $pUserInfo->ChaRemain = $ChaRemain;
    $pUserInfo->UserPoint = $UserPoint;
    $pUserInfo->BonusPoint = $BonusPoint;
    $pUserInfo->UserGameOnlineTime = $UserGameOnlineTime;
    $pUserInfo->UserBlock = $UserBlock;
    $pUserInfo->UserEmail = $UserEmail;
    $pUserInfo->ParentID = $ParentID;
    
    CInput::GetInstance()->AddValue( $SESSION_USERSEL , serialize( $pUserInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo '<span class="info">บันทึกข้อมูลเรียบร้อย</span>';
}

function USER_EDITOR_UI()
{
    global $SESSION_USERSEL;
    
    $pUserInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_USERSEL , IN_SESSION ) );
    if ( !$pUserInfo ) die( "ERROR:USERLOGIN:NULL" );
    
    USER_MENU_UI( $pUserInfo );
    printf( "<div id=\"userprocess\"></div>" );
}

function USER_SEL()
{
    global $SESSION_USERSEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    $UserNum = CInput::GetInstance()->GetValueInt( "usernum" , IN_POST );
    CheckNumZero($UserNum);
    
    $pUserInfo = new UserInfo();
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
    $cNeoSQLConnectODBC->QueryRanUser( sprintf( "SELECT UserName,UserID,UserPass,UserPass2,UserType,UserLoginState,CreateDate,LastLoginDate,UserBlock,UserBlockDate,ChatBlockDate,ChaRemain,UserPoint,UserGameOnlineTime FROM UserInfo WHERE UserNum = %d" , $UserNum ) );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $pUserInfo->UserNum = $UserNum;
        $pUserInfo->UserName = $cNeoSQLConnectODBC->Result( "UserName" , ODBC_RETYPE_ENG );
        $pUserInfo->UserID = $cNeoSQLConnectODBC->Result( "UserID" , ODBC_RETYPE_ENG );
        $pUserInfo->UserPass = $cNeoSQLConnectODBC->Result( "UserPass" , ODBC_RETYPE_ENG );
        $pUserInfo->UserPass2 = $cNeoSQLConnectODBC->Result( "UserPass2" , ODBC_RETYPE_ENG );
        $pUserInfo->UserType = $cNeoSQLConnectODBC->Result( "UserType" , ODBC_RETYPE_INT );
        $pUserInfo->UserLoginState = $cNeoSQLConnectODBC->Result( "UserLoginState" , ODBC_RETYPE_INT );
        $pUserInfo->CreateDate = $cNeoSQLConnectODBC->Result( "CreateDate" , ODBC_RETYPE_THAI );
        $pUserInfo->LastLoginDate = $cNeoSQLConnectODBC->Result( "LastLoginDate" , ODBC_RETYPE_THAI );
        $pUserInfo->UserBlock = $cNeoSQLConnectODBC->Result( "UserBlock" , ODBC_RETYPE_INT );
        $pUserInfo->UserBlockDate = $cNeoSQLConnectODBC->Result( "UserBlockDate" , ODBC_RETYPE_THAI );
        $pUserInfo->ChatBlockDate = $cNeoSQLConnectODBC->Result( "ChatBlockDate" , ODBC_RETYPE_THAI );
        $pUserInfo->ChaRemain = $cNeoSQLConnectODBC->Result( "ChaRemain" , ODBC_RETYPE_INT );
        $pUserInfo->UserPoint = $cNeoSQLConnectODBC->Result( "UserPoint" , ODBC_RETYPE_INT );
        $pUserInfo->UserGameOnlineTime = $cNeoSQLConnectODBC->Result( "UserGameOnlineTime" , ODBC_RETYPE_INT );
    }
    $cNeoSQLConnectODBC->CloseRanUser();
    
    if ( empty( $pUserInfo->UserNum ) ) die( "ERROR:USER:NOTFOUND" );
    
    $szTemp = sprintf( "SELECT UserEmail,ParentID,BonusPoint FROM UserInfo WHERE MemNum = %d AND UserID = '%s'" , $MemNum , $pUserInfo->UserID );
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $pUserInfo->UserEmail = $cNeoSQLConnectODBC->Result( "UserEmail" , ODBC_RETYPE_THAI );
        $pUserInfo->ParentID = $cNeoSQLConnectODBC->Result( "ParentID" , ODBC_RETYPE_ENG );
        $pUserInfo->BonusPoint = $cNeoSQLConnectODBC->Result( "BonusPoint" , ODBC_RETYPE_INT );
    }
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    CInput::GetInstance()->AddValue( $SESSION_USERSEL , serialize( $pUserInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function USER_SEARCH()
{
    global $arrUserType;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    $search_type = CInput::GetInstance()->GetValueInt( "search_type" , IN_POST );
    
    $szTemp = "SELECT
                UserNum,UserID
                FROM UserInfo
                WHERE ";
    
    if ( !arrkeycheck($arrUserType, $search_type) ) $search_type = 0;
    switch( $search_type )
    {
        case 0:
        {
            $UserNum = CInput::GetInstance()->GetValueInt( "text" , IN_POST );
            $szTemp .= "UserNum = " . $UserNum;
        }break;
    
        case 1:
        {
            $UserID = CInput::GetInstance()->GetValueString( "text" , IN_POST );
            $UserID = CBinaryCover::utf8_to_tis620( $UserID );
            if ( strlen( $UserID ) < 1 ) die( "ERROR:USERID:LENGTH" );
            $szTemp .= "UserID LIKE '%" . $UserID . "%'";
        }break;
        
        default: die("ERROR:OPTION:SECTOR:OVER:LOAD");
    }
    
    //die( $szTemp );
    
    $pUserSector = array();
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
    $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $UserNum = $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT );
        $UserID = $cNeoSQLConnectODBC->Result( "UserID" , ODBC_RETYPE_ENG );
        
        $pUserSector[ $UserNum ] = $UserID;
    }
    $cNeoSQLConnectODBC->CloseRanUser();
    
    echo "กรุณาเลือกไอดีที่ต้องการ : ";
    echo buildSelectText( "usernum" , "" , 0, $pUserSector );
    echo "<button onclick=\"seluser();\">เลือก</button>";
}

function USER_UI()
{
    global $arrUserType;
    
    echo buildSelectText( "search_type" , "" , 0 , $arrUserType );
    echo "<input type=\"text\" id=\"text\"></input><button onclick=\"user_search( this );\">ค้นหา</button>";
}

?>
