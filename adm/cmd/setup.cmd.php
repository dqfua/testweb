<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");
if ( !$cAdmin->GetLoginPassCard() )
{
    require_once 'password_security.cmd.php';
    die("");
}

define( "CONEITEM_ENABLE" , true );
define( "CONESKILL_ENABLE" , false );

function CMD_CONEITEM( $cNeoSQLConnectODBC , $MemNum , $MemberNum )
{
    { // delete subproject
            // use pattern update to recyecle bin
            $szTemp = sprintf( "UPDATE SubProject SET SubDel = 1 WHERE MemNum = %d" , $MemNum );
            if ( CONEITEM_ENABLE )
            {
                    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            }
    }
    { // delete itemproject
            // use pattern update to recyecle bin
            $szTemp = sprintf( "UPDATE ItemProject SET ItemDelete = 1 WHERE MemNum = %d" , $MemNum );
            if ( CONEITEM_ENABLE )
            {
                    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            }
    }
    { // cone subproject
            $szQuerySubProject = "";
            $nQuerySubProject = 0;
            $szTemp = sprintf("SELECT SubNum,SubRollRank,SubName,SubShow FROM SubProject WHERE MemNum = %d AND SubDel = 0",$MemberNum);
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                    $__nSubNum = $cNeoSQLConnectODBC->Result( "SubNum" , ODBC_RETYPE_INT );
                    $nSubNum[ $__nSubNum ] = $__nSubNum;
                    $nSubNumQuery[ $nQuerySubProject ] = $__nSubNum;
                    $szQuerySubProject[$nQuerySubProject] = sprintf( "INSERT INTO SubProject(MemNum,SubName,SubShow) OUTPUT INSERTED.SubNum VALUES( %d,'%s',%d )"
                                                                                                                                                                                              , $MemNum
                                                                                                                                                                                              , $cNeoSQLConnectODBC->Result( "SubName" , ODBC_RETYPE_THAI )
                                                                                                                                                                                              , $cNeoSQLConnectODBC->Result( "SubShow" , ODBC_RETYPE_INT )
                                                                                                                                                                                              );
                    $nQuerySubProject++;
            }
            if ( CONEITEM_ENABLE )
            {
                    for( $i = 0 ; $i < $nQuerySubProject ; $i++ )
                    {
                            $cNeoSQLConnectODBC->QueryRanWeb($szQuerySubProject[ $i ]);
                            while( $cNeoSQLConnectODBC->FetchRow() )
                            {
                                    $__nSubNum = $cNeoSQLConnectODBC->Result( "SubNum" , ODBC_RETYPE_INT );
                                    $nSubNum[ $nSubNumQuery[ $i ] ] = $__nSubNum;
                                    //printf( "%d TO %d\n<br>" , $nSubNumQuery[ $i ] , $__nSubNum );
                            }
                    }
            }
    }
    { // cone itemproject
            $itemcount = 0;
            $querytablenum = 0;
            $szQuery[ $querytablenum ] = "";
            $szTemp = sprintf("SELECT [ItemNum]
                                                              ,[MemNum]
                                                              ,[SubNum]
                                                              ,[ItemMain]
                                                              ,[ItemSub]
                                                              ,[ItemName]
                                                              ,[ItemComment]
                                                              ,[ItemImage]
                                                              ,[Item_MaxReborn]
                                                              ,[Item_Resell]
                                                              ,[Item_Resell_Percent]
                                                              ,[ItemPrice]
                                                              ,[ItemTimePrice]
                                                              ,[ItemBonusPointPrice]
                                                              ,[ItemSell]
                                                              ,[ItemType]
                                                              ,[ItemSock]
                                                              ,[ItemShow]
                                                              ,[ItemDelete]
                                                              ,[ItemDay]
                                                              ,[ItemDrop]
                                                              ,[ItemDamage]
                                                              ,[ItemDefense]
                                                              ,[Item_TurnNum]
                                                              ,[Item_Res_Ele]
                                                              ,[Item_Res_Fire]
                                                              ,[Item_Res_Ice]
                                                              ,[Item_Res_Poison]
                                                              ,[Item_Res_Spirit]
                                                              ,[Item_Op1]
                                                              ,[Item_Op1_Value]
                                                              ,[Item_Op2]
                                                              ,[Item_Op2_Value]
                                                              ,[Item_Op3]
                                                              ,[Item_Op3_Value]
                                                              ,[Item_Op4]
                                                              ,[Item_Op4_Value]
                                                              ,[ItemByPassGM]
                                                      FROM [BBSAsiaGame].[dbo].[ItemProject]
                                                      WHERE MemNum = %d AND ItemDelete = 0
                                                      " , $MemberNum );
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                    $__nSubNum = $cNeoSQLConnectODBC->Result( "SubNum" , ODBC_RETYPE_INT );
                    //printf( "Q : %d TO : %d<br>\n" , $__nSubNum , $nSubNum[ $__nSubNum ] );
                    $szQuery[ $querytablenum ] .= sprintf( "INSERT INTO [BBSAsiaGame].[dbo].[ItemProject]
                                                                                                                                                                                       ([MemNum]
                                                                                                                                                                                       ,[SubNum]
                                                                                                                                                                                       ,[ItemMain]
                                                                                                                                                                                       ,[ItemSub]
                                                                                                                                                                                       ,[ItemName]
                                                                                                                                                                                       ,[ItemComment]
                                                                                                                                                                                       ,[ItemImage]
                                                                                                                                                                                       ,[Item_MaxReborn]
                                                                                                                                                                                       ,[Item_Resell]
                                                                                                                                                                                       ,[Item_Resell_Percent]
                                                                                                                                                                                       ,[ItemPrice]
                                                                                                                                                                                       ,[ItemTimePrice]
                                                                                                                                                                                       ,[ItemBonusPointPrice]
                                                                                                                                                                                       ,[ItemSell]
                                                                                                                                                                                       ,[ItemType]
                                                                                                                                                                                       ,[ItemSock]
                                                                                                                                                                                       ,[ItemShow]
                                                                                                                                                                                       ,[ItemDay]
                                                                                                                                                                                       ,[ItemDrop]
                                                                                                                                                                                       ,[ItemDamage]
                                                                                                                                                                                       ,[ItemDefense]
                                                                                                                                                                                       ,[Item_TurnNum]
                                                                                                                                                                                       ,[Item_Res_Ele]
                                                                                                                                                                                       ,[Item_Res_Fire]
                                                                                                                                                                                       ,[Item_Res_Ice]
                                                                                                                                                                                       ,[Item_Res_Poison]
                                                                                                                                                                                       ,[Item_Res_Spirit]
                                                                                                                                                                                       ,[Item_Op1]
                                                                                                                                                                                       ,[Item_Op1_Value]
                                                                                                                                                                                       ,[Item_Op2]
                                                                                                                                                                                       ,[Item_Op2_Value]
                                                                                                                                                                                       ,[Item_Op3]
                                                                                                                                                                                       ,[Item_Op3_Value]
                                                                                                                                                                                       ,[Item_Op4]
                                                                                                                                                                                       ,[Item_Op4_Value]
                                                                                                                                                                                       ,[ItemByPassGM])
                                                                                                                                                                             VALUES
                                                                                                                                                                                       (%d,%d,%d,%d,'%s','%s','%s',%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)"
                                                                                                                                                                                       , $MemNum
                                                                                                                                                                                       , $nSubNum[ $__nSubNum ]
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemName" , ODBC_RETYPE_THAI )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemComment" , ODBC_RETYPE_THAI )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemImage" , ODBC_RETYPE_THAI )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_MaxReborn" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Resell" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Resell_Percent" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemPrice" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemTimePrice" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemBonusPointPrice" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemSell" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemType" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemSock" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemShow" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemDay" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemDrop" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemDamage" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemDefense" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_TurnNum" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Res_Ele" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Res_Fire" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Res_Ice" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Res_Poison" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Res_Spirit" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Op1" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Op1_Value" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Op2" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Op2_Value" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Op3" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Op3_Value" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Op4" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "Item_Op4_Value" , ODBC_RETYPE_INT )
                                                                                                                                                                                       , $cNeoSQLConnectODBC->Result( "ItemByPassGM" , ODBC_RETYPE_INT )
                                                                                                                                                                                       );
                    if ( ( $itemcount % 10 ) == 9 )
                    {
                            $querytablenum++;
                            $szQuery[ $querytablenum ] = "";
                    }
                    $itemcount++;
            }
            for( $i = 0 ; $i <= $querytablenum ; $i++ )
            {
                    if ( CONEITEM_ENABLE )
                    {
                            $cNeoSQLConnectODBC->QueryRanWeb( $szQuery[$i] );
                    }
            }
            if ( CONEITEM_ENABLE )
            {
                    echo "<font color=\"green\">Cone ไอเทมเรียบร้อยแล้ว!!</font><br>";
            }else{
                    echo "<font color=\"red\">ขณะนี้ระบบ Cone ยังไม่เปิดให้ใช้บริการ</font><br>";
            }
    }
}

function CMD_CONESKILL( $cNeoSQLConnectODBC , $MemNum/*to*/ , $MemberNum/*from*/ )
{
    $cNeoSQLConnectODBC->QueryRanWeb( sprintf("UPDATE SkillTable SET SkillDelete = 1 WHERE MemNum = %d",$MemNum) );
    
    $pData = new _tdata();
    $szTemp = sprintf("SELECT SkillID,SkillName FROM SkillTable WHERE MemNum = %d AND SkillDelete = 0",$MemberNum);
    $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $skillid = $cNeoSQLConnectODBC->Result("SkillID",ODBC_RETYPE_ENG);
        $skillname = $cNeoSQLConnectODBC->Result("SkillName",ODBC_RETYPE_THAI);
        $skillname = CBinaryCover::tis620_to_utf8($skillname);
        
        $pData->AddData( "SkillID" , $skillid );
        $pData->AddData( "SkillName" , $skillname );
        $pData->NextData();
    }
    
    $strQueryInsert = "";
    for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData($i);
        $skillname = $ppData["SkillName"];
        $skillname = CBinaryCover::utf8_to_tis620($skillname);
        $strQueryInsert .= sprintf("INSERT INTO SkillTable( SkillID,SkillName,MemNum ) VALUES( '%s','%s',%d )\n"
                                    ,$ppData["SkillID"]
                                    ,$skillname
                                    ,$MemNum
                                    );
        if ( $i % 30 == 29 )
        {
            $cNeoSQLConnectODBC->QueryRanWeb( $strQueryInsert );
            $strQueryInsert = "";
        }
    }
    
    if ( $strQueryInsert != "" )
    {
        $cNeoSQLConnectODBC->QueryRanWeb( $strQueryInsert );
        $strQueryInsert = "";
    }
    
    $cNeoSQLConnectODBC->QueryRanWeb( sprintf("SELECT SkillData FROM MemSkillSet WHERE MemNum = %d",$MemberNum) );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $strQueryInsert = $cNeoSQLConnectODBC->Result("SkillData",ODBC_RETYPE_BINARY);
    }
    
    if ( $strQueryInsert != "" )
    {
        CSkillSet::FristCheck( $MemNum );
        $strQueryInsert = sprintf( "UPDATE MemSkillSet SET SkillData = 0x%s WHERE MemNum = %d" , $strQueryInsert , $MemNum );
        $cNeoSQLConnectODBC->QueryRanWeb( $strQueryInsert );
        $strQueryInsert = "";
    }
    
    echo "<font color=\"green\">Cone สกิลเรียบร้อยแล้ว!!</font><br>";
}

function CMD_CONESYSTEM()
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    $MemberID = $cAdmin->GetID();

    sleep( 2 );

    CInput::GetInstance()->BuildFrom( IN_POST );
    $coneitemcode = CInput::GetInstance()->GetValueString( "coneitemcode" , IN_POST );
    if ( strlen( $coneitemcode ) != CONEITEMCODE_LENGTH ) die( "<font color=\"red\">รูปแบบ Code ที่กรอกไม่ถูกต้อง</font>" );
    $MyCode = CGlobal::MemNum2ConeSerial( $MemNum , $MemberID );
    if ( strcmp( $MyCode , $coneitemcode ) == 0 ) die( "<font color=\"red\">ไม่สามารถโคลนไอเทมจากช๊อปของตัวเองได้</font>" );

    $MemberNum = 0;
    $MemID = "";
    $bWork = false;
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanWeb();
    //$cNeoSQLConnectODBC->QueryRanWeb( "SELECT MemberNum,MemID FROM MemberInfo WHERE MemBan = 0 AND MemDelete = 0" );
    $cNeoSQLConnectODBC->QueryRanWeb( "SELECT MemberNum,MemID FROM MemberInfo" );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $MemberNum = $cNeoSQLConnectODBC->Result( "MemberNum" , ODBC_RETYPE_INT );
        $MemID = $cNeoSQLConnectODBC->Result( "MemID" , ODBC_RETYPE_ENG );
        $TryCode = CGlobal::MemNum2ConeSerial( $MemberNum , $MemID );
        if ( strcmp( $TryCode , $coneitemcode ) == 0 )
        {
            $bWork = true;
            break;
        }
    }
    
    if ( $bWork )
    {
        CMD_CONEITEM( $cNeoSQLConnectODBC , $MemNum , $MemberNum );
        CMD_CONESKILL( $cNeoSQLConnectODBC , $MemNum , $MemberNum );
    }
    
    $cNeoSQLConnectODBC->CloseRanWeb();
    if ( $bWork == false ) echo "<font color=\"red\">ไม่พบ Code ที่คุณกรอกมาอยู่ในระบบ</font>";
    else echo "<font color=\"green\">การโคลนสำเร็จเสร็จสิ้น.</font>";
}

function CMD_CHECKSTATUS()
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    sleep( 1 );
    
    class __DataType
    {
        public $Name = "";
        public $Type = 0;
        public $AI = 0;
        public $Len = 0;
        public $empty2null = 0;
        
        public function __construct( $column , $type , $ai , $len , $empty2null )
        {
            $this->Name = $column;
            $this->Type = $type;
            $this->AI = $ai;
            $this->Len = $len;
            $this->empty2null = $empty2null;
        } 
   };
    
    class DataType
    {
        private $pData = array();
        
        public function GetData( $tablename , $column )
        {
            return $this->pData[ md5( $tablename . $column ) ];
        }
        
        public function AddData( $column , $type , $ai , $len , $empty2null , $tablename )
        {
            $this->pData[ md5( $tablename . $column ) ] = new __DataType( $column , $type , $ai , $len , $empty2null );
        }
    };
    
    function FixError( $table , $colume , $type , $defaultValue )
    {
        return sprintf( "ALTER TABLE %s ADD %s %s NOT NULL DEFAULT %s" , $table , $colume , $type , $defaultValue );
    }
    
    function checkcolumn( &$cNeoSQLConnectODBC ,&$pData , $tablename )
    {
        while( $cNeoSQLConnectODBC->FetchRow() ) {
            $column = $cNeoSQLConnectODBC->Result( "name" , ODBC_RETYPE_ENG );
            $type = $cNeoSQLConnectODBC->Result( "system_type_id" , ODBC_RETYPE_INT );
            $ai = $cNeoSQLConnectODBC->Result( "is_identity" , ODBC_RETYPE_INT );
            $len = $cNeoSQLConnectODBC->Result( "max_length" , ODBC_RETYPE_INT );
            $empty2null = $cNeoSQLConnectODBC->Result( "is_nullable" , ODBC_RETYPE_INT );
            $pData->AddData($column, $type, $ai, $len, $empty2null, $tablename);
            return true;
        }
        return false;
    }
    
    $pData = new DataType;
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    /*
     * แก้ปัญหาชั่วคราวเรื่องปรับเรทติดลบ
     */
    $ppData = new _tdata();
    //ตรวจสอบและแก้ไข UserInven.UserInven
    //มีปัญหาล็อกเกอร์มาแค่ช่องแรกช่องเดียวเท่านั้น
    if ( true == false )
    {
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , "abcdf" );
        $szTemp = "SELECT UserInvenNum,UserInven FROM UserInven";
        $cNeoSQLConnectODBC->QueryRanGame($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $UserInven = $cNeoSQLConnectODBC->Result( "UserInven" , ODBC_RETYPE_BINARY );
            $ppData->AddData( "UserInvenNum" , $cNeoSQLConnectODBC->Result( "UserInvenNum" , ODBC_RETYPE_INT ) );
            $ppData->AddData( "UserInven" , $UserInven );
            
            $cNeoSerialMemory = new CNeoSerialMemory();
            $cNeoSerialMemory->OpenMemory();
            $cNeoSerialMemory->WriteBuffer( $UserInven );
            
            $cNeoSerialMemory->ReadInt();
            
            $ChaInven = $cNeoSerialMemory->ReadBuffer( strlen( $UserInven ) );
            $pChaInfo = new ChaInfo();
            $pChaInfo->ChaInven = $ChaInven;
            $pChaInfo->BuildNeoChaInven($MemNum);
            $ppData->AddData( "ChaInfo" , $pChaInfo );
            
            $ppData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanGame();
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        for( $i = 0 ; $i < $ppData->GetRollData() ; $i++ )
        {
            $pppData = $ppData->GetData( $i );
            $pChaInfo = $pppData["ChaInfo"];
            for( $n = 0 ; $n < $pChaInfo->NeoChaInven->GetItemNum() ; $n++ )
            {
                if ( $pChaInfo->NeoChaInven->GetItemOptVal1( $n ) >= 20000 && $pChaInfo->NeoChaInven->GetItemOptType1( $n ) >= 18 ) $pChaInfo->NeoChaInven->SetItemOptType1( $n , 0 );
                if ( $pChaInfo->NeoChaInven->GetItemOptVal2( $n ) >= 20000 && $pChaInfo->NeoChaInven->GetItemOptType2( $n ) >= 18 ) $pChaInfo->NeoChaInven->SetItemOptType2( $n , 0 );
                if ( $pChaInfo->NeoChaInven->GetItemOptVal3( $n ) >= 20000 && $pChaInfo->NeoChaInven->GetItemOptType3( $n ) >= 18 ) $pChaInfo->NeoChaInven->SetItemOptType3( $n , 0 );
                if ( $pChaInfo->NeoChaInven->GetItemOptVal4( $n ) >= 20000 && $pChaInfo->NeoChaInven->GetItemOptType4( $n ) >= 18 ) $pChaInfo->NeoChaInven->SetItemOptType4( $n , 0 );
            }
            $pChaInfo->NeoChaInven->UpdateVar2Binary( );
            $Inventory = $pChaInfo->NeoChaInven->SaveChaInven();
            
            $cNeoSerialMemory = new CNeoSerialMemory();
            $cNeoSerialMemory->OpenMemory();
            $cNeoSerialMemory->WriteInt( 0x0105 );
            $cNeoSerialMemory->WriteBuffer( $Inventory );
            
            $szTemp = sprintf( "UPDATE UserInven SET UserInven = 0x%s WHERE UserInvenNum = %d" , $cNeoSerialMemory->GetBuffer() , $pppData["UserInvenNum"] );
            echo $szTemp . "<br>" ;
            $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
        }
        printf( "จำนวนรายการที่จัดทำคือ = %d<br>\n" , $ppData->GetRollData() );
        $cNeoSQLConnectODBC->CloseRanGame();
        die( ":1" );
    }
    //ตรวจสอบและแก้ไข ChaInfo.ChaInven
    if ( true == false )
    {
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        $szTemp = "SELECT ChaNum,ChaInven FROM ChaInfo WHERE ChaNum >= 30000 AND ChaNum <= 40000";
        $cNeoSQLConnectODBC->QueryRanGame($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pChaInfo = new ChaInfo();
            $pChaInfo->ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
            $pChaInfo->ChaInven = $cNeoSQLConnectODBC->Result( "ChaInven" , ODBC_RETYPE_BINARY );
            $pChaInfo->BuildNeoChaInven($MemNum);
            $ppData->AddData( "ChaInfo" , $pChaInfo );
            
            $ppData->NextData();
        }
        for( $i = 0 ; $i < $ppData->GetRollData() ; $i++ )
        {
            $pppData = $ppData->GetData( $i );
            $pChaInfo = $pppData["ChaInfo"];
            for( $n = 0 ; $n < $pChaInfo->NeoChaInven->GetItemNum() ; $n++ )
            {
                if ( $pChaInfo->NeoChaInven->GetItemOptVal1( $n ) >= 20000 && $pChaInfo->NeoChaInven->GetItemOptType1( $n ) >= 18 ) $pChaInfo->NeoChaInven->SetItemOptType1( $n , 0 );
                if ( $pChaInfo->NeoChaInven->GetItemOptVal2( $n ) >= 20000 && $pChaInfo->NeoChaInven->GetItemOptType2( $n ) >= 18 ) $pChaInfo->NeoChaInven->SetItemOptType2( $n , 0 );
                if ( $pChaInfo->NeoChaInven->GetItemOptVal3( $n ) >= 20000 && $pChaInfo->NeoChaInven->GetItemOptType3( $n ) >= 18 ) $pChaInfo->NeoChaInven->SetItemOptType3( $n , 0 );
                if ( $pChaInfo->NeoChaInven->GetItemOptVal4( $n ) >= 20000 && $pChaInfo->NeoChaInven->GetItemOptType4( $n ) >= 18 ) $pChaInfo->NeoChaInven->SetItemOptType4( $n , 0 );
            }
            $pChaInfo->NeoChaInven->UpdateVar2Binary( );
            $Inventory = $pChaInfo->NeoChaInven->SaveChaInven();
        }
        printf( "จำนวนรายการที่จัดทำคือ = %d<br>\n" , $ppData->GetRollData() );
        $cNeoSQLConnectODBC->CloseRanGame();
        die( ":1" );
    }
    //ตรวจสอบและแก้ไข ChaInfo.ChaPutOnItems
    if ( true == false )
    {
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        $szTemp = "SELECT ChaNum,ChaPutOnItems FROM ChaInfo WHERE ChaNum >= 30000 AND ChaNum <= 40000";
        $cNeoSQLConnectODBC->QueryRanGame($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $pChaInfo = new ChaInfo();
            $pChaInfo->ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
            $pChaInfo->ChaPutOnItems = $cNeoSQLConnectODBC->Result( "ChaPutOnItems" , ODBC_RETYPE_BINARY );
            $pChaInfo->BuildNeoChaPutOnItems($MemNum);
            $ppData->AddData( "ChaInfo" , $pChaInfo );
            
            $ppData->NextData();
        }
        for( $i = 0 ; $i < $ppData->GetRollData() ; $i++ )
        {
            $pppData = $ppData->GetData( $i );
            $pChaInfo = $pppData["ChaInfo"];
            for( $n = 0 ; $n < $pChaInfo->NeoChaPutOnItems->GetItemNum() ; $n++ )
            {
                if ( $pChaInfo->NeoChaPutOnItems->GetItemOptVal1( $n ) >= 20000 && $pChaInfo->NeoChaPutOnItems->GetItemOptType1( $n ) >= 18 ) $pChaInfo->NeoChaPutOnItems->SetItemOptType1( $n , 0 );
                if ( $pChaInfo->NeoChaPutOnItems->GetItemOptVal2( $n ) >= 20000 && $pChaInfo->NeoChaPutOnItems->GetItemOptType2( $n ) >= 18 ) $pChaInfo->NeoChaPutOnItems->SetItemOptType2( $n , 0 );
                if ( $pChaInfo->NeoChaPutOnItems->GetItemOptVal3( $n ) >= 20000 && $pChaInfo->NeoChaPutOnItems->GetItemOptType3( $n ) >= 18 ) $pChaInfo->NeoChaPutOnItems->SetItemOptType3( $n , 0 );
                if ( $pChaInfo->NeoChaPutOnItems->GetItemOptVal4( $n ) >= 20000 && $pChaInfo->NeoChaPutOnItems->GetItemOptType4( $n ) >= 18 ) $pChaInfo->NeoChaPutOnItems->SetItemOptType4( $n , 0 );
            }
            $pChaInfo->NeoChaPutOnItems->UpdateVar2Binary( );
            $Inventory = $pChaInfo->NeoChaPutOnItems->SaveChaPutOnItems();
        }
        printf( "จำนวนรายการที่จัดทำคือ = %d<br>\n" , $ppData->GetRollData() );
        $cNeoSQLConnectODBC->CloseRanGame();
        die( ":1" );
    }
    //ย้ายล็อกเกอร์เฉพาะช่อง 2 3 4 ไปรวมกับ ช่อง 1 ปัจจุบัน UserInven.UserInven
    if ( true == false )
    {
        $NowUserInven = array();
        $cNeoSerialMemory = new CNeoSerialMemory();
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , "Op_RanGame1_Backup" );
        $szTemp = "SELECT UserInven,UserNum FROM UserInven WHERE UserInvenNum >= 3500 AND UserInvenNum <= 4000";
        $cNeoSQLConnectODBC->QueryRanGame($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $Inventory = new UserInven();
            $Inventory->ReadUserInven( $cNeoSQLConnectODBC->Result( "UserInven" , ODBC_RETYPE_BINARY ) );
            
            $ppData->AddData( "UserInven" , $Inventory );
            $ppData->AddData( "UserNum" , $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT ) );
            
            $ppData->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanGame();
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        for( $i = 0 ; $i < $ppData->GetRollData() ; $i++ )
        {
            $pppData = $ppData->GetData( $i );
            $UserNum = $pppData["UserNum"];
            
            $szTemp = sprintf( "SELECT UserInven FROM UserInven WHERE UserNum = %d" , $UserNum );
            $cNeoSQLConnectODBC->QueryRanGame($szTemp);
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $Inventory = new UserInven();
                $Inventory->ReadUserInven( $cNeoSQLConnectODBC->Result( "UserInven" , ODBC_RETYPE_BINARY ) );
                $NowUserInven[ $UserNum ] = $Inventory;
            }
        }
        for( $i = 0 ; $i < $ppData->GetRollData() ; $i++ )
        {
            $pppData = $ppData->GetData( $i );
            $UserNum = $pppData["UserNum"];
            $Inventory = $pppData["UserInven"];
            
            $cNeoSerialMemory->OpenMemory();
            $cNeoSerialMemory->WriteInt( MAX_USER_INVEN );
            
            for( $n = 0 ; $n < $Inventory->SlotStore ; $n++ )
            {
                $ppBuff = "";
                if ( $n > 0 )
                {
                    $ppBuff = $Inventory->Inventory[$n]->SaveChaInven();
                    //printf( "%d,%s<br>" , $n , $ppBuff );
                }else{
                    $ppBuff = $NowUserInven[$UserNum]->Inventory[$n]->SaveChaInven();
                }
                $cNeoSerialMemory->WriteBuffer( $ppBuff );
                //printf( "%d,%s<br>" , $n , $ppBuff );
            }
            
            $szTemp = sprintf( "UPDATE UserInven SET UserInven = 0x%s WHERE UserNum = %d" , $cNeoSerialMemory->GetBuffer() , $UserNum );
            $cNeoSQLConnectODBC->QueryRanGame($szTemp);
            //printf( "%s<br>" , $szTemp );
        }
        $cNeoSQLConnectODBC->CloseRanGame();
        printf( "จำนวนรายการที่จัดทำคือ = %d<br>\n" , $ppData->GetRollData() );
        die( ":1" );
    }
    
    //ตรวจสอบที่ RanUser
    {
        $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
        
        $szTemp = "SELECT name,system_type_id,is_nullable,max_length,is_identity FROM sys.columns WHERE OBJECT_ID = OBJECT_ID( N'[dbo].[UserInfo]' ) AND NAME = 'UserPoint'";
        $cNeoSQLConnectODBC->QueryRanUser($szTemp);
        if ( !checkcolumn( $cNeoSQLConnectODBC , $pData , "UserInfo" ) )
        {
            $cNeoSQLConnectODBC->QueryRanUser(FixError("UserInfo", "UserPoint", "int", "0"));
            $cNeoSQLConnectODBC->QueryRanUser($szTemp);
            checkcolumn( $cNeoSQLConnectODBC , $pData , "UserInfo" );
        }

        $szTemp = "SELECT name,system_type_id,is_nullable,max_length,is_identity FROM sys.columns WHERE OBJECT_ID = OBJECT_ID( N'[dbo].[UserInfo]' ) AND NAME = 'UserGameOnlineTime'";
        $cNeoSQLConnectODBC->QueryRanUser($szTemp);
        if ( !checkcolumn( $cNeoSQLConnectODBC , $pData , "UserInfo" ) )
        {
            $cNeoSQLConnectODBC->QueryRanUser(FixError("UserInfo", "UserGameOnlineTime", "int", "0"));
            $cNeoSQLConnectODBC->QueryRanUser($szTemp);
            checkcolumn( $cNeoSQLConnectODBC , $pData , "UserInfo" );
        }
        
        $cNeoSQLConnectODBC->CloseRanUser();
    }
    
    //ตรวจสอบที่ RanGame
    {
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        
        $szTemp = "SELECT name,system_type_id,is_nullable,max_length,is_identity FROM sys.columns WHERE OBJECT_ID = OBJECT_ID( N'[dbo].[ChaInfo]' ) AND NAME = 'ChaReborn'";
        $cNeoSQLConnectODBC->QueryRanGame($szTemp);
        if ( !checkcolumn( $cNeoSQLConnectODBC , $pData , "ChaInfo" ) )
        {
            $cNeoSQLConnectODBC->QueryRanUser(FixError("ChaInfo", "ChaReborn", "int", "0"));
            $cNeoSQLConnectODBC->QueryRanGame($szTemp);
            checkcolumn( $cNeoSQLConnectODBC , $pData , "ChaInfo" );
        }
        
        $cNeoSQLConnectODBC->CloseRanGame();
    }
    
    //ตรวจสอบที่ RanShop
    {
        $cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP() , $cWeb->GetRanShop_User() , $cWeb->GetRanShop_Pass() , $cWeb->GetRanShop_DB() );
        
        $szTemp = "SELECT name,system_type_id,is_nullable,max_length,is_identity FROM sys.columns WHERE OBJECT_ID = OBJECT_ID( N'[dbo].[ShopItemMap]' ) AND NAME = 'ProductNum'";
        $cNeoSQLConnectODBC->QueryRanShop($szTemp);
        checkcolumn( $cNeoSQLConnectODBC , $pData , "ShopItemMap" );
        
        $szTemp = "SELECT name,system_type_id,is_nullable,max_length,is_identity FROM sys.columns WHERE OBJECT_ID = OBJECT_ID( N'[dbo].[ShopItemMap]' ) AND NAME = 'ItemMain'";
        $cNeoSQLConnectODBC->QueryRanShop($szTemp);
        checkcolumn( $cNeoSQLConnectODBC , $pData , "ShopItemMap" );
        
        $szTemp = "SELECT name,system_type_id,is_nullable,max_length,is_identity FROM sys.columns WHERE OBJECT_ID = OBJECT_ID( N'[dbo].[ShopItemMap]' ) AND NAME = 'ItemSub'";
        $cNeoSQLConnectODBC->QueryRanShop($szTemp);
        checkcolumn( $cNeoSQLConnectODBC , $pData , "ShopItemMap" );
        
        $cNeoSQLConnectODBC->CloseRanShop();
    }
    
    function ShowErrorInfo( $ppData , $dbname , $tablename , $column , $type , $datatype , $ai , $len , &$bError )
    {
        if ( !$ppData )
        {
            echo "สถานะไม่ถูกต้อง $dbname -> $tablename -> $column -> $column $type, กรุณาเพิ่ม Column ดังกล่าวไว้ในที่นี้ด้วย<br>\n";
            $bError = true;
        }else{
            if ( $ppData->Type != $datatype )
            {
                echo "สถานะไม่ถูกต้อง $dbname -> $tablename -> $column -> $column $type, กรุณาเปลี่ยน DataType ของ $column เป็น $type ด้วย<br>\n";
                $bError = true;
            }
            if ( $ppData->AI != $ai && $ai != 0 )
            {
                echo "สถานะไม่ถูกต้อง $dbname -> $tablename -> $column -> $column $type, กรุณาตั้ง Identity Specification ที่ $column ด้วย<br>\n";
                $bError = true;
            }
            if ( $ppData->Len != $len && $len != 0 )
            {
                echo "สถานะไม่ถูกต้อง $dbname -> $tablename -> $column -> $column $type, ความยาวของ DataType ให้ใส่เป็น $len<br>\n";
                $bError = true;
            }
        }
    }
    
    $bGlobalError = false;
    
    //รายงานผลที่ RanUser
    {
        $bError = false;
        
        $ppData = $pData->GetData( "UserInfo" ,"UserPoint" );
        ShowErrorInfo( $ppData , "RanUser" , "UserInfo" , "UserPoint" , "int" , 56 , 0 , 0 , $bError );
        
        $ppData = $pData->GetData( "UserInfo" ,"UserGameOnlineTime" );
        ShowErrorInfo( $ppData , "RanUser" , "UserInfo" , "UserGameOnlineTime" , "int" , 56 , 0 , 0 , $bError );
        
        if ( !$bError )
        {
            echo "RanUser พร้อมใช้งาน<br>\n";
        }else{
            echo "RanUser ไม่ผ่านการตรวจสอบ<br>\n";
            $bGlobalError = true;
        }
    }
    
    //รายงานผลที่ RanGame
    {
        $bError = false;
        
        $ppData = $pData->GetData( "ChaInfo" ,"ChaReborn" );
        ShowErrorInfo( $ppData , "RanGame" , "ChaInfo" , "ChaReborn" , "int" , 56 , 0 , 0 , $bError );
        
        if ( !$bError )
        {
            echo "RanGame พร้อมใช้งาน<br>\n";
        }else{
            echo "RanGame ไม่ผ่านการตรวจสอบ<br>\n";
            $bGlobalError = true;
        }
    }
    
    //รายงานผลที่ RanShop
    {
        $bError = false;
        
        $ppData = $pData->GetData( "ShopItemMap" ,"ProductNum" );
        ShowErrorInfo( $ppData , "RanShop" , "ShopItemMap" , "ProductNum" , "int" , 56 , 1 , 0 , $bError );
        
        $ppData = $pData->GetData( "ShopItemMap" ,"ItemMain" );
        ShowErrorInfo( $ppData , "RanShop" , "ShopItemMap" , "ItemMain" , "int" , 56 , 0 , 0 , $bError );
        
        $ppData = $pData->GetData( "ShopItemMap" ,"ItemSub" );
        ShowErrorInfo( $ppData , "RanShop" , "ShopItemMap" , "ItemSub" , "int" , 56 , 0 , 0 , $bError );
        
        if ( !$bError )
        {
            echo "RanShop พร้อมใช้งาน<br>\n";
        }else{
            echo "RanShop ไม่ผ่านการตรวจสอบ<br>\n";
            $bGlobalError = true;
        }
    }
    
    //if ( $bGlobalError )
    //    echo ":0";
    //else
        echo ":1";
}

function CMD_DUMP2RANSHOP()
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    Sleep( 1 );
    
    class __DataItemProjectMini
    {
        public $ItemMain = 0;
        public $ItemSub = 0;
        
        public function __construct( $ItemMain , $ItemSub )
        {
            $this->ItemMain = $ItemMain;
            $this->ItemSub = $ItemSub;
        }
    };
    
    class DataItemProjectMini
    {
        private $pData = array();
        private $nData = 0;
        
        public function GetNumRoll(){ return $this->nData; }
        public function GetData( $index ) { return $this->pData[ $index ]; }
        public function AddData( $ItemMain , $ItemSub )
        {
            $this->pData[$this->nData] = new __DataItemProjectMini( $ItemMain , $ItemSub );
            $this->nData++;
        }
    };
    
    $pData = new DataItemProjectMini;
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = sprintf( "SELECT ItemMain,ItemSub FROM ItemProject WHERE MemNum = %d AND ItemType = 0 AND ItemDelete = 0" , $MemNum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $ItemMain = $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT );
        $ItemSub = $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT );
        $pData->AddData($ItemMain, $ItemSub);
    }
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    $cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP() , $cWeb->GetRanShop_User() , $cWeb->GetRanShop_Pass() , $cWeb->GetRanShop_DB() );
    for( $i = 0 ; $i < $pData->GetNumRoll() ; $i++ )
    {
        $ppData = $pData->GetData($i);
        $szTemp = sprintf( "IF NOT EXISTS(SELECT ProductNum FROM ShopItemMap WHERE ItemMain = %d AND ItemSub = %d)
                            BEGIN
                                INSERT INTO ShopItemMap(ItemMain,ItemSub) VALUES(%d,%d)
                            END
                            " , $ppData->ItemMain , $ppData->ItemSub , $ppData->ItemMain , $ppData->ItemSub );
        //echo $szTemp;
        $cNeoSQLConnectODBC->QueryRanShop($szTemp);
    }
    $cNeoSQLConnectODBC->CloseRanShop();
    
    echo "SUCCESS";
}

function CMD_DUMP2XMLDB()
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    Sleep( 1 );
    
    $pFile = fopen( CGlobal::xmlGetPathOut( $cAdmin->szFolderShop ) , "w" );
    if ( !$pFile ) die ("ERROR:FILE::FIELD");
    
    fprintf( $pFile , "<?xml version=\"1.0\" encoding=\"utf-8\">\n" );
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    
    fprintf( $pFile , "<WORD Ver=\"1\" Id=\"ITEMSUB\">\n" );
    
    $SubCount = 0;
    $szTemp = sprintf("SELECT SubNum,SubName,SubShow,SubCreateDate FROM SubProject WHERE MemNum = %d AND SubDel = 0 AND SubShow = 0",$MemNum);
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
        $SubName = $cNeoSQLConnectODBC->Result("SubName",ODBC_RETYPE_THAI);
        fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"%d\">%s<\/VALUE>\n" , $SubNum , $SubName );
        $SubCount++;
    }
    
    fprintf( $pFile , "<\/WORD>\n" );
    
    $ItemCount = 0;
    $szTemp = sprintf( "SELECT ItemNum,ItemMain,ItemSub,SubNum,ItemPrice,ItemName FROM ItemProject WHERE MemNum = %d AND ItemType = 0 AND ItemDelete = 0" , $MemNum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $ItemMain = $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT );
        $ItemSub = $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT );
        $SubNum = $cNeoSQLConnectODBC->Result( "SubNum" , ODBC_RETYPE_INT );
        $ItemNum = $cNeoSQLConnectODBC->Result( "ItemNum" , ODBC_RETYPE_INT );
        $ItemPrice = $cNeoSQLConnectODBC->Result( "ItemPrice" , ODBC_RETYPE_INT );
        $ItemName = $cNeoSQLConnectODBC->Result( "ItemName" , ODBC_RETYPE_THAI );
        
        fprintf( $pFile , "<WORD Ver=\"1\" Id=\"ItemProject_%04d\">\n" , $ItemCount );
        fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"0\">%d<\/VALUE>" , $ItemNum );
        fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"1\">%d<\/VALUE>" , $ItemMain );
        fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"2\">%d<\/VALUE>" , $ItemSub );
        fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"3\">%d<\/VALUE>" , $ItemPrice );
        fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"4\">%d<\/VALUE>" , $SubNum );
        fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"5\">%s<\/VALUE>" , $ItemName );
        fprintf( $pFile , "<\/WORD>\n");
        
        $ItemCount++;
    }
    
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    fprintf( $pFile, "<WORD Ver=\"1\" Id=\"ITEMINFO\">");
    fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"0\">%d<\/VALUE>",$SubCount);
    fprintf( $pFile , "<VALUE Lang=\"th\" Index=\"1\">%d<\/VALUE>",$ItemCount);
    fprintf( $pFile , "<\/WORD>");
    
    fclose($pFile);
    
    echo "SUCCESS";
}

function CMD_UI()
{
	global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
	$MemberID = $cAdmin->GetID();
?>
<div id="main_setup">
    <p>เมนูนี้ควรจะทำเมื่อตอนคุณเป็นสมาชิคใหม่หรือพึ่งเปิดช็อปใหม่ครั้งแรก<br>
        เมนูข้างในนี้จะเป็นเมนูที่ช่วยเช็คสภาพความพร้อมของดาต้าเบสในเซิร์ฟเวอร์ของคุณ<br>
        เพื่อให้พร้อมที่จะใช้งานทุกระบบภายในช็อปของเรา<br>
        <b><u>สำคัญมาก!!</u> คุณต้องตั้งค่าฐานข้อมูลให้เรียบร้อยเสียก่อน!!<br>
        และดาต้าเบสของคุณที่เชื่อมต่อนั้นจะต้องเป็นตัวเดียวกับเซิร์ฟเวอร์ของคุณเดิมๆอยู่ก่อนแล้วด้วย<br>
        </b>
    </p><br>
    <div style="border: #333 dashed 3px;">
        <div style="margin: 5px;">
            <div id="checktatus" style="float: left;"></div><button id="b_checkstatus">ตรวจสอบความพร้อม</button>
        </div>
        <div style="clear: left;"></div>
    </div>
    <div>
        <p><b><u>เมนูส่วนนี้จะสามารถใช้งานได้เมื่อคุณกดตรวจสอบแล้วและการตรวจสอบผ่านทั้งหมด</u></b></p>
        <div  style="border: #333 groove 3px;">
            <div style="margin: 5px;">
                <p>
                	<b>ระบบโคลนไอเทมจากช๊อปที่คุณเคยใช้บริการมาที่ช๊อปนี้</b><br />
                    การโคลนไอเทมและสกิลจากช๊อปอื่นที่เคยใช้บริการมาจะไม่ต้องรอแอดมินใหญ่ของช๊อปเป็นคนจัดการอีกต่อไป<br />
                    คุณสามารถโคลนได้เองโดยง่ายเพียงแค่นำโค้ดรหัส <b><u>"<?php echo CGlobal::MemNum2ConeSerial( $MemNum , $MemberID ); ?>"</u></b> นี้<br />
                    มาใส่ที่ช่อง <input type="text" id="coneitemcode" style="width:199px;" /><button id="coneitem">โคลนไอเทม</button><br />
                    <b><u>คำเตือน</u></b><br />
                    ในการโคลนไอเทมนั้นเมื่อโคลนไปแล้วไอเทมที่เคยมีอยู่ก่อนหน้านี้จะถูกลบออกทั้งหมดและไม่สามารถกู้คืนได้ก่อนใช้งานระบบนี้กรุณาคิดให้ดีก่อน<br />
                    <div id="coneitemarea"></div>
                </p>
            </div>
        </div>
        <div  style="border: #333 groove 3px;">
            <div style="margin: 5px;">
                <p>
                    เมนูส่วนนี้เหมาะที่จะใช้เมื่อคุณพึ่งเริ่มต้นใช้งานช็อปครั้งแรก<br>
                    เมนูนี้จะทำการนำข้อมูลจากช็อปลงไปบรรทึกไว้ที่ RanShop ของคุณ<br>
                    เพื่อที่จะสามารถซื้อไอเทมจาก Item Bank ได้ <button id="Dump2RanShop">Dump2RanShop</button>
                </p>
            </div>
        </div>
        <br>
        <div  style="border: #333 groove 3px;">
            <div style="margin: 5px;">
                <p>
                    เมนูตรงส่วนนี้จะเป็นการนำข้อมูลจากช็อปลงไปสู่ XML Database<br>
                    เหมาะสำหรับพูดที่พัฒนา API หรือ Game Server ที่จะนำไอเทมลงไปจำหน่ายในเกม<button id="Dump2Xml">Dump2Xml</button>
                </p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="js/setup.js"></script>
<?php
}

$type = CInput::GetInstance()->GetValueInt( "type" , IN_GET );
switch( $type )
{
    case 1101:
    {
        CMD_DUMP2RANSHOP();
    }break;
    
    case 1102:
    {
        CMD_DUMP2XMLDB();
    }break;
	
	case 1103:
    {
        CMD_CONESYSTEM();
    }break;

    case 9979:
    {
        CMD_CHECKSTATUS();
    }break;

    default :
    {
        CMD_UI();
    }break;
}
?>
