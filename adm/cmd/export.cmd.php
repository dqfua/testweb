<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");
if ( !$cAdmin->GetLoginPassCard() )
{
    require_once 'password_security.cmd.php';
    die("");
}

$nServerType = $cAdmin->nServerType;

define( "EXPORT_ENABLE" , true );
define( "EXPORT_IMPORT_SPEED" , 1 );

class CExportWeb extends CNeoWeb
{
    public $ExportNum = 0;
    public $Target_MemNum = 0;
    public $ExtSameID = "";
    public $ExtSameChar = "";
}

//RanUser::UserInfo
//standand colume : UserNum,UserID
//สามารถใช้ได้ทั้ง EP3 - EP7
$USERINFO_STRUCT_COL = "UserPass,UserPass2,ChaRemain,UserPoint,UserEmail,UserGameOnlineTime,UserType,UserBlock,UserBlockDate";
$USERINFO_STRUCT_VAL = "%s,%s,%d,%d,%s,%d,%d,%d,%dt";

//RanGame::ChaInfo
//standand colume : ChaNum,ChaName
//GuNum ไม่จำเป็นต้องเอามาเนื่องจากเป็นการอ้างอิงถึงกิว เราไม่ได้ย้ายระบบกิวมาด้วย
//ChaOnline ไม่จำเป็นต้องย้ายมาเนื่องจากย้ายมาต้องเป็น 0 เสมอ
//คอลั่มที่ไม่จำเป็นต้องเอามาใช้งานในการ Import & Export : UserNum
//ตั้งค่าให้เป็น standand EP3
$CHAINFO_STRUCT_COL = "SGNum,ChaTribe,ChaClass,ChaSchool,ChaHair,ChaFace,ChaLiving,ChaLevel,ChaPower,ChaStrong,ChaStrength,ChaSpirit,ChaDex,ChaIntel,ChaStRemain,ChaExp,ChaViewRange,ChaHP,ChaMP,ChaStartMap,ChaStartGate,ChaPosX,ChaPosY,ChaPosZ,ChaSaveMap,ChaSavePosX,ChaSavePosY,ChaSavePosZ,ChaReturnMap,ChaReturnPosX,ChaReturnPosY,ChaReturnPosZ,ChaBright,ChaAttackP,ChaDefenseP,ChaFightA,ChaShootA,ChaSP,ChaPK,ChaSkillPoint,ChaInvenLine,ChaDeleted,ChaSkills,ChaSkillSlot,ChaActionSlot,ChaPutOnItems,ChaInven,ChaReborn,ChaMoney";
$CHAINFO_STRUCT_VAL = "%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%b,%b,%b,%b,%b,%d,%l";

switch( $nServerType )
{
    case SERVTYPE_PLUSONLINE:
    {
        $CHAINFO_STRUCT_COL = "SGNum,ChaTribe,ChaClass,ChaSchool,ChaSex,ChaHair,ChaHairColor,ChaFace,ChaLiving,ChaLevel,ChaPower,ChaStrong,ChaStrength,ChaSpirit,ChaDex,ChaIntel,ChaStRemain,ChaExp,ChaViewRange,ChaHP,ChaMP,ChaStartMap,ChaStartGate,ChaPosX,ChaPosY,ChaPosZ,ChaSaveMap,ChaSavePosX,ChaSavePosY,ChaSavePosZ,ChaReturnMap,ChaReturnPosX,ChaReturnPosY,ChaReturnPosZ,ChaBright,ChaAttackP,ChaDefenseP,ChaFightA,ChaShootA,ChaSP,ChaPK,ChaSkillPoint,ChaInvenLine,ChaDeleted,ChaSkills,ChaSkillSlot,ChaActionSlot,ChaPutOnItems,ChaInven,ChaReExp,ChaSpMID,ChaSpSID,saveMoney,saveExp,itemCount,ChaCoolTime,VTAddInven,SumMain,SumSub,SumLevel,ChaCP,ChaReborn,ChaSkinSel,ChaMoney";
        $CHAINFO_STRUCT_VAL = "%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%b,%b,%b,%b,%b,%d,%d,%d,%d,%d,%d,%b,%b,%d,%d,%d,%d,%d,%d,%l";
    }break;
}

$MemNum = $cAdmin->GetMemNum();

$CURRENT_SESSION_ROLLBACK = "Export_Rollback";
$CURRENT_SESSION_CWEB = "Export_CWeb";
$CURRENT_SESSION_TARGET_CWEB = "Export_Target_CWeb";

CInput::GetInstance()->BuildFrom( IN_POST );

//ไว้สำหรับตรวจสอบความเข้ากันได้ของ struct col & val
function CHECK_COMPATIBILITY_COL_DB( $COL , $VAL )
{
    $arrCOL = explode(",", $COL);
    $arrVAL = explode(",", $VAL);
    
    return ( sizeof( $arrCOL ) == sizeof( $arrVAL ) );
}

function COLUME_STRUCT_STRING_TO_ODBCRETYPE( $vv )
{
    $nType = ODBC_RETYPE_ENG;
    switch( $vv )
    {
        case "%d":
        {
            $nType = ODBC_RETYPE_INT;
        }break;
    
        case "%dt":
        {
            $nType = ODBC_RETYPE_DATETIME;
        }break;

        case "%t":
        {
            $nType = ODBC_RETYPE_THAI;
        }break;

        case "%s":
        case "%l":
        {
            $nType = ODBC_RETYPE_ENG;
        }break;
    
        case "%b":
        {
            $nType = ODBC_RETYPE_BINARY;
        }break;
    }
    
    return $nType;
}

function COULME_STRUCT_STRINGVAL_TO_SQLSTRING( $vv )
{
    switch( $vv )
    {
        case "%d": return "%d";
            
        case "%dt":
        case "%t":
        case "%s": return "'%s'";
            
        case "%l": return "%s";
            
        case "%b": return "0x%s";
    }
}

function CMD_CREATEKEY()
{
    global $MemNum;
    global $nServerType;
    
    $ExportKey = md5( "EXPORT_" . $MemNum . "_" . $nServerType );

    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = sprintf( "INSERT INTO ExportKey(MemNum,ExportKey,ExportServerType) VALUES( %d , '%s' , %d )" , $MemNum , $ExportKey , $nServerType );
    $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
    $cNeoSQLConnectODBC->CloseRanWeb();

    echo "CREATE KEY SUCCESS";
}

function CMD_DELETEKEY()
{
    global $MemNum;
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    //$szTemp = sprintf( "DELETE ExportKey WHERE MemNum = %d" , $MemNum );
    $szTemp = sprintf( "UPDATE ExportKey SET IsDelete = 1 WHERE MemNum = %d" , $MemNum );
    $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    echo "DELETE KEY SUCCESS";
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function CMD_IMPORT()
{
    global $MemNum;
    global $nServerType;
    
    global $CURRENT_SESSION_CWEB;
    global $CURRENT_SESSION_TARGET_CWEB;
    
    $ImportKey = CInput::GetInstance()->GetValueString( "importKey" , IN_POST );
    
    if ( strlen($ImportKey) != 32 )
    {
        echo "Import Key Error";
        exit;
    }
    
    $Target_MemNum = 0;
    $ExportNum = 0;
    $ExtSameID = "";
    $ExtSameChar = "";
    $ExportServerType = 0;
    
    $LastUserNum = 0;
    $CharacterCount = 0;
    $UserCount = 0;
    
    $time_start = microtime_float();
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();

    $szTemp = sprintf( "SELECT ExportNum,MemNum,LastUserNum,CharacterCount,UserCount,ExtSameID,ExtSameChar,ExportServerType FROM ExportKey WHERE ExportKey = '%s' AND IsDelete = 0" , $ImportKey );

    $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $ExportNum = $cNeoSQLConnectODBC->Result("ExportNum",ODBC_RETYPE_INT);
        
        $Target_MemNum = $cNeoSQLConnectODBC->Result("MemNum",ODBC_RETYPE_INT);
        
        $LastUserNum = $cNeoSQLConnectODBC->Result("LastUserNum",ODBC_RETYPE_INT);
        $CharacterCount = $cNeoSQLConnectODBC->Result("CharacterCount",ODBC_RETYPE_INT);
        $UserCount = $cNeoSQLConnectODBC->Result("UserCount",ODBC_RETYPE_INT);
        
        $ExtSameID = $cNeoSQLConnectODBC->Result("ExtSameID",ODBC_RETYPE_ENG);
        $ExtSameChar = $cNeoSQLConnectODBC->Result("ExtSameChar",ODBC_RETYPE_ENG);
        
        $ExportServerType = $cNeoSQLConnectODBC->Result("ExportServerType",ODBC_RETYPE_INT);
    }

    if( $Target_MemNum > 0 && $Target_MemNum != $MemNum && $nServerType == $ExportServerType )
    {
        $cWeb = new CNeoWeb();
        $cWeb->GetDBInfoFromWebDB($MemNum);
        
        $cTargetWeb = new CExportWeb();
        $cTargetWeb->GetDBInfoFromWebDB($Target_MemNum,false);
        $Target_ServerName = $cTargetWeb->GetServerNameFromMemNum($Target_MemNum);

        $Total_ChaNum = 0;
        $Total_UserNum = 0;
        
        $cNeoSQLConnectODBC->ConnectRanGame( $cTargetWeb->GetRanGame_IP() , $cTargetWeb->GetRanGame_User() , $cTargetWeb->GetRanGame_Pass() , $cTargetWeb->GetRanGame_DB() );

        $szTemp = sprintf( "SELECT COUNT(ChaNum) As TotalChaNum FROM ChaInfo" );
        $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $Total_ChaNum = $cNeoSQLConnectODBC->Result( "TotalChaNum" , ODBC_RETYPE_INT );
        }

        $cNeoSQLConnectODBC->CloseRanGame();
        
        $cNeoSQLConnectODBC->ConnectRanUser($cTargetWeb->GetRanUser_IP(), $cTargetWeb->GetRanUser_User(), $cTargetWeb->GetRanUser_Pass(), $cTargetWeb->GetRanUser_DB());
        
        $szTemp = sprintf("SELECT COUNT(UserNum) As TotalUserNum FROM UserInfo");
        $cNeoSQLConnectODBC->QueryRanUser($szTemp);
        
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $Total_UserNum = $cNeoSQLConnectODBC->Result("TotalUserNum",ODBC_RETYPE_INT);
        }
        
        $cNeoSQLConnectODBC->CloseRanUser();
        
        $cTargetWeb->ExportNum = $ExportNum;
        $cTargetWeb->Target_MemNum = $Target_MemNum;
        $cTargetWeb->ExtSameChar = $ExtSameChar;
        $cTargetWeb->ExtSameID = $ExtSameID;
        
        CInput::GetInstance()->AddValue( $CURRENT_SESSION_CWEB , serialize($cWeb) , IN_SESSION );
        CInput::GetInstance()->AddValue( $CURRENT_SESSION_TARGET_CWEB , serialize($cTargetWeb) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
        
        printf( "<script>Set_CurrentUser(%d);</script>" , $LastUserNum );
        printf( "<script>setMaxUser(%d);</script>" , $Total_UserNum );

        printf( "<div>Import From Server : %s</div>" , $Target_ServerName );
        printf( "<div>Total Character : %d</div>" , $Total_ChaNum );
        printf( "<div>Total User : %d</div>" , $Total_UserNum );
        printf( "<hr><br>" );
        
        //ตั้งค่าทั่วไป
        printf( "<div id=\"exportOption\">" );
        printf( "<b><u>ตั้งค่าไอดีและตัวละครที่ซ้ำ(ไม่ควรเกิน 2 ตัวอักษรและควรเป็นภาษาอังกฤษหรือตัวเลขเท่านั้น)</u></b>" );
        printf( "<hr>" );
        printf( "Ext Same ID : _<input id=\"extSameID\" type=\"text\" value=\"%s\" maxlength=\"2\"><br>Ext Same Char : @<input id=\"extSameChar\" type=\"text\" value=\"%s\" maxlength=\"2\"><br>" , $ExtSameID , $ExtSameChar );
        printf( "<button onclick=\"button_importAllID();\" style=\"width:199px;\">ย้ายแบบทุกไอดี</button>" );
        printf( "<button onclick=\"button_importSingleID();\" style=\"width:199px;\">ย้ายเฉพาะไอดี</button>" );
        printf( "<hr><br></div>" );
        
        //เมนูพิเศษย้ายเฉพาะไอดี
        printf( "<div id=\"areaExtraExport\" style=\"display:none;\">" );
        printf( "<b><u>เมนูพิเศษ(ย้ายเฉพาะไอดี)</u></b>" );
        printf( "<hr>" );
        printf( "ระบุไอดีจากเซิร์ฟเวอร์ต้นทางที่ต้องการย้าย : <input id=\"extraUserID\" type=\"text\" value=\"\" maxlength=\"11\">" );
        printf( "<button id=\"buttonImportSingleID\" onclick=\"importBegin(true);\" style=\"width:199px;\">Import Single ID</button>" );
        printf( "<hr><br></div>" );
        
        //เมนูหลัก
        printf( "<div id=\"areaMenuMain\" style=\"display:none;\">" );
        printf( "<b><u>เมนูหลัก(ย้ายทุกไอดี)</u></b>" );
        printf( "<hr>" );
        printf( "<button id=\"beginImportButton\" onclick=\"importBegin(false);\" style=\"width:199px;\">Begin Import</button>" );
        printf( "<button onclick=\"export_Button_Rollback();\" style=\"width:199px;\">Rollback Import</button>" );
        printf( "<div id=\"importProcessing\"></div>" );
        printf( "<hr><br>" );
        
        printf( "<p><b>Import Process</b></p>" );
        printf( "Status : <span id=\"importStatus\">%s</span><br>" , "Standby" );
        printf( "Character : <span id=\"importCharacter\">%d</span>/%d<br>" , $CharacterCount , $Total_ChaNum );
        printf( "User : <span id=\"importUser\">%d</span>/%d<br>" , $UserCount , $Total_UserNum );
        printf( "Failed : <span id=\"importFailed\">%d</span><br>" , 0 );
        
        printf( "<hr>" );
        
        printf( "</div>" );
        
        printf( "<p><b>Log Import</b></p>" );
        //printf( "<textarea id=\"exportlog\" cols=\"50\" rows=\"10\" readonly>hello world</textarea>" );
        printf( "<select size=\"50\" id=\"exportlog\" style=\"width:600px;\">" );
        printf( "</select>" );
        
        function randStr( $size )
        {
		$chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$string = "";
		for ($i = 0; $i < $size; $i++){
			$pos = rand(0, strlen($chars)-1);
			$string .= $chars{$pos};
		}
		return $string;
	}
        /*
        for( $i = 0 ; $i < 20 ; $i++ )
        {
            echo "<script>exportLog_AddText('$i" . randStr( round( rand() % 100 )  ) . "');</script>";
        }
        */
    }else{
        echo "Import Key Not Found";
    }
    
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    $time_end = microtime_float();
    
    echo "<div>took : " . ($time_end - $time_start) . " ms.</div>";
}

function CMD_IMPORT_BEGIN()
{
    global $CURRENT_SESSION_TARGET_CWEB;
    
    $extSameID = CInput::GetInstance()->GetValueString( "extSameID" , IN_POST );
    $extSameChar = CInput::GetInstance()->GetValueString( "extSameChar" , IN_POST );
    
    if ( strlen( $extSameID ) != 2 ) die("F");
    if ( strlen( $extSameChar ) != 2 ) die("F");
    
    $cTargetWeb = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_TARGET_CWEB , IN_SESSION ) );
    $cTargetWeb->ExtSameChar = $extSameChar;
    $cTargetWeb->ExtSameID = $extSameID;
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION_TARGET_CWEB , serialize($cTargetWeb) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    
    $szTemp = sprintf( "UPDATE ExportKey SET ExtSameID = '%s', ExtSameChar = '%s' WHERE ExportNum = %d" , $extSameID ,$extSameChar , $cTargetWeb->ExportNum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    echo "S";
}

function CMD_IMPORT_PROCESS_SINGLE()
{
    $UserID = CInput::GetInstance()->GetValueString( "extraUserID" , IN_POST );
    $szTemp = "";
    $szTemp .= " WHERE UserID = '" . $UserID . "'";
    CMD_IMPORT_PROCESS_READY($szTemp);
}

function CMD_IMPORT_PROCESS_ALL()
{
    $Current_User = CInput::GetInstance()->GetValueInt( "current_user" , IN_POST );
    
    $szTemp = "";
    $szTemp .= " WHERE UserNum > " . $Current_User;
    $szTemp .= " AND UserNum <= " . ((int)$Current_User + EXPORT_IMPORT_SPEED);
    CMD_IMPORT_PROCESS_READY($szTemp);
}

function CMD_IMPORT_PROCESS_READY( $sszTemp )
{
    global $CURRENT_SESSION_TARGET_CWEB;
    global $USERINFO_STRUCT_COL;
    global $USERINFO_STRUCT_VAL;
    
    $arrUserInfoCol = explode(",", $USERINFO_STRUCT_COL );
    $arrUserInfoVal = explode(",", $USERINFO_STRUCT_VAL );
    
    $cTargetWeb = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_TARGET_CWEB , IN_SESSION ) );
    
    $LastUserNum = 0;
    
    $pUserList = new _tdata();
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    
    //export ranuser
    $cNeoSQLConnectODBC->ConnectRanUser($cTargetWeb->GetRanUser_IP(), $cTargetWeb->GetRanUser_User(), $cTargetWeb->GetRanUser_Pass(), $cTargetWeb->GetRanUser_DB());
    
    //$szTemp = sprintf( "SELECT UserNum,UserID FROM UserInfo WHERE UserNum > %d AND UserNum < %d" , $Current_User , $Current_User + 4 );
    
    $szTemp = "SELECT UserNum,UserID";
    
    //สร้าง Colume ที่กำหนดไว้ด้านบน
    for( $n = 0 ; $n < sizeof($arrUserInfoCol) ; $n++)
    {
        $szTemp .= "," . $arrUserInfoCol[$n];
    }
    
    $szTemp .= " FROM UserInfo";
    $szTemp .= $sszTemp;
    
    //printf( $szTemp );
    
    $cNeoSQLConnectODBC->QueryRanUser($szTemp);
    while($cNeoSQLConnectODBC->FetchRow())
    {
        //standand colume
        $UserID = $cNeoSQLConnectODBC->Result( "UserID" , ODBC_RETYPE_ENG );
        $LastUserNum = $cNeoSQLConnectODBC->Result("UserNum",ODBC_RETYPE_INT);
        
        $pUserList->AddData("UserID", $UserID);
        $pUserList->AddData("UserNum", $LastUserNum);
        
        for( $n = 0 ; $n < sizeof($arrUserInfoCol) ; $n++)
        {
            $cc = $arrUserInfoCol[$n];
            $vv = $arrUserInfoVal[$n];
            $nType = COLUME_STRUCT_STRING_TO_ODBCRETYPE($vv);

            $dbVal = $cNeoSQLConnectODBC->Result($cc,$nType);
            $pUserList->AddData($cc, $dbVal);
        }
        
        $pUserList->NextData();
    }
    
    $cNeoSQLConnectODBC->CloseRanUser(true);
    
    CMD_IMPORT_PROCESS( $pUserList , $LastUserNum );
}

function CMD_IMPORT_PROCESS( $pUserList , $LastUserNum )
{
    global $MemNum;
    
    global $CURRENT_SESSION_CWEB;
    global $CURRENT_SESSION_TARGET_CWEB;
    global $CHAINFO_STRUCT_COL;
    global $CHAINFO_STRUCT_VAL;
    global $USERINFO_STRUCT_COL;
    global $USERINFO_STRUCT_VAL;
    
    $time_start = microtime_float();
    
    $arrChaInfoCol = explode(",", $CHAINFO_STRUCT_COL );
    $arrChaInfoVal = explode(",", $CHAINFO_STRUCT_VAL );
    $arrUserInfoCol = explode(",", $USERINFO_STRUCT_COL );
    $arrUserInfoVal = explode(",", $USERINFO_STRUCT_VAL );
    
    $cWeb = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_CWEB , IN_SESSION ) );
    $cTargetWeb = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_TARGET_CWEB , IN_SESSION ) );
    
    function ErrorFail( $err )
    {
        printf("F:%d",$err);
    }
    
    if ( !$cTargetWeb )
    {
        ErrorFail(99);
        exit;
    }
    
    $CharacterCount = 0;
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    
    if ( $LastUserNum == 0 )
    {
        printf("END");
        exit;
    }
    
    //export rangame
    $cNeoSQLConnectODBC->ConnectRanGame($cTargetWeb->GetRanGame_IP(), $cTargetWeb->GetRanGame_User(), $cTargetWeb->GetRanGame_Pass(), $cTargetWeb->GetRanGame_DB());
    for( $i = 0 ; $i < $pUserList->GetRollData() ; $i++ )
    {
        $ppData = $pUserList->GetData($i);
        
        $pCharacterList = new _tdata();
        
        //$szTemp = sprintf("SELECT ChaNum,ChaName FROM ChaInfo WHERE UserNum = %d",$ppData["UserNum"]);
        
        $szTemp = "SELECT ChaNum,ChaName";
        
        //สร้าง Colume จากตัวแปรที่กำหนดไว้ด้านบน
        for( $n = 0 ; $n < sizeof($arrChaInfoCol) ; $n++)
        {
            $szTemp .= "," . $arrChaInfoCol[$n];
        }
        
        $szTemp .= " FROM ChaInfo";
        $szTemp .= " WHERE UserNum = " . $ppData["UserNum"];
        
        $cNeoSQLConnectODBC->QueryRanGame($szTemp);
        
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            //standand colume
            $ChaNum = $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT);
            $ChaName = $cNeoSQLConnectODBC->Result("ChaName",ODBC_RETYPE_THAI);
            
            $pCharacterList->AddData("ChaNum", $ChaNum);
            $pCharacterList->AddData("ChaName", $ChaName);
            
            for( $n = 0 ; $n < sizeof($arrChaInfoCol) ; $n++)
            {
                $cc = $arrChaInfoCol[$n];
                $vv = $arrChaInfoVal[$n];
                $nType = COLUME_STRUCT_STRING_TO_ODBCRETYPE($vv);
                
                $dbVal = $cNeoSQLConnectODBC->Result($cc,$nType);
                
                $pCharacterList->AddData($cc, $dbVal);
            }
            
            $pCharacterList->NextData();
            
            $CharacterCount++;
        }
        
        $pUserList->SetData($i, "CharacterList", $pCharacterList);
    }
    $cNeoSQLConnectODBC->CloseRanGame(true);

    //import process zone
    $cNeoSQLConnectODBC->ConnectRanWeb();
    
    //import ranuser,rangame
    $cNeoSQLConnectODBC->ConnectRanUser($cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB());
    $cNeoSQLConnectODBC->ConnectRanGame($cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB());
    
    for( $i = 0 ; $i < $pUserList->GetRollData() ; $i++)
    {
        $ppData = $pUserList->GetData($i);
        
        $UserID = $ppData["UserID"];
        
        //ตรวจสอบไอดีซ้ำ
        $szTemp = sprintf( "SELECT TOP 1 UserNum FROM UserInfo WHERE UserID = '%s'" , $UserID );
        
        //echo $szTemp;
        
        $HaveIDSame = false;
        $cNeoSQLConnectODBC->QueryRanUser($szTemp);
        while($cNeoSQLConnectODBC->FetchRow())
        {
            $HaveIDSame = true;
        }
        
        //เมื่อไอดีซ้ำก็ให้เติมต่อท้าย
        if ( $HaveIDSame )
        {
            $UserID .= "_" . $cTargetWeb->ExtSameID;
        }
        
        $szTemp = "INSERT INTO UserInfo(UserName,UserID";
        
        for( $n = 0 ; $n < sizeof($arrUserInfoCol) ; $n++)
        {
            $szTemp .= "," . $arrUserInfoCol[$n];
        }
        
        $szTemp .= ") VALUES('%s','%s'";
        
        for( $n = 0 ; $n < sizeof($arrUserInfoCol) ; $n++)
        {
            $cc = COULME_STRUCT_STRINGVAL_TO_SQLSTRING( $arrUserInfoVal[$n] );
            $szTemp .= "," . $cc;
        }
        
        $szTemp .= ") SELECT SCOPE_IDENTITY() AS UserNum";
        
        $arrVal = array();
        array_push($arrVal, $UserID, $UserID);
        
        for( $n = 0 ; $n < sizeof($arrUserInfoCol) ; $n++)
        {
            array_push($arrVal, $ppData[$arrUserInfoCol[$n]]);
        }
        
        $szTemp = vsprintf( $szTemp , $arrVal );
        
        $NewUserNum = 0;
        
        if ( EXPORT_ENABLE )
        {
            //echo $szTemp . "<br>";
            
            $cNeoSQLConnectODBC->QueryRanUser($szTemp);
            $cNeoSQLConnectODBC->NextResult(); // for GET New ID
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $NewUserNum = $cNeoSQLConnectODBC->Result("UserNum", ODBC_RETYPE_INT);
            }
        }
        
        $szTemp = sprintf( "INSERT INTO ExportLog(ExportNum,MemNum,UserNum,UserID) VALUES( %d , %d , %d , '%s' )" , $cTargetWeb->ExportNum , $MemNum , $NewUserNum , $UserID );
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        
        $WebUserInfo = array();
        
        $szTemp = sprintf( "SELECT TOP 1 UserPass,UserPass2,UserEmail,RegisterIP,ParentID FROM UserInfo WHERE MemNum = %d AND UserID = '%s'" , $cTargetWeb->Target_MemNum , $ppData["UserID"] );
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $WebUserInfo["UserPass"] = $cNeoSQLConnectODBC->Result("UserPass",ODBC_RETYPE_ENG);
            $WebUserInfo["UserPass2"] = $cNeoSQLConnectODBC->Result("UserPass2",ODBC_RETYPE_ENG);
            $WebUserInfo["UserEmail"] = $cNeoSQLConnectODBC->Result("UserEmail",ODBC_RETYPE_THAI);
            $WebUserInfo["RegisterIP"] = $cNeoSQLConnectODBC->Result("RegisterIP",ODBC_RETYPE_ENG);
            $WebUserInfo["ParentID"] = $cNeoSQLConnectODBC->Result("ParentID",ODBC_RETYPE_ENG);
        }
        
        if ( sizeof($WebUserInfo) > 0 )
        {
            $szTemp = sprintf( "INSERT INTO UserInfo( MemNum , UserID , UserPass , UserPass2 , UserEmail , RegisterIP , ParentID ) VALUES( %d , '%s' , '%s' , '%s' , '%s' , '%s' , '%s' )"
                                , $MemNum
                                , $UserID
                                , $WebUserInfo["UserPass"]
                                , $WebUserInfo["UserPass2"]
                                , $WebUserInfo["UserEmail"]
                                , $WebUserInfo["RegisterIP"]
                                , $WebUserInfo["ParentID"]
                             );
            if ( EXPORT_ENABLE )
            {
                $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            }
        }
        
        $pUserList->SetData($i, "NewUserNum", $NewUserNum);
        
        $pCharacterList = $ppData["CharacterList"];
        //print_r($pCharacterList);
        //ส่วนของการย้ายตัวละคร
        for( $l = 0 ; $l < $pCharacterList->GetRollData() ; $l++ )
        {
            $ppCharacterData = $pCharacterList->GetData($l);
            
            $ChaName = $ppCharacterData["ChaName"];
            
            $ChaNameHaveSame = false;
            
            $szTemp = sprintf( "SELECT TOP 1 ChaNum FROM ChaInfo WHERE ChaName = '%s'" , $ChaName );
            $cNeoSQLConnectODBC->QueryRanGame($szTemp);
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $ChaNameHaveSame = true;
            }
            
            if ( $ChaNameHaveSame )
            {
                $ChaName .= "@" . $cTargetWeb->ExtSameChar;
            }
            
            $szTemp = "INSERT INTO ChaInfo(UserNum,ChaName";
            
            for( $n = 0 ; $n < sizeof($arrChaInfoCol) ; $n++)
            {
                $szTemp .= "," . $arrChaInfoCol[$n];
            }
            
            $szTemp .= ") VALUES(%d,'%s'";
            
            for( $n = 0 ; $n < sizeof($arrChaInfoCol) ; $n++)
            {
                $cc = COULME_STRUCT_STRINGVAL_TO_SQLSTRING( $arrChaInfoVal[$n] );
                $szTemp .= "," . $cc;
            }
            
            $szTemp .= ") SELECT SCOPE_IDENTITY() AS ChaNum";
            
            $arrVal = array();
            array_push($arrVal, $NewUserNum, $ChaName);
            
            for( $n = 0 ; $n < sizeof($arrChaInfoCol) ; $n++)
            {
                array_push($arrVal, $ppCharacterData[$arrChaInfoCol[$n]]);
            }
            
            $szTemp = vsprintf( $szTemp , $arrVal );
            
            //echo $szTemp . "<br>";
            
            if ( EXPORT_ENABLE )
            {
                $cNeoSQLConnectODBC->QueryRanGame($szTemp);
                
                /*
                $cNeoSQLConnectODBC->NextResult(); // for GET New ID
                while( $cNeoSQLConnectODBC->FetchRow() )
                {
                    $NewChaNum = $cNeoSQLConnectODBC->Result("ChaNum", ODBC_RETYPE_INT);

                    $ppCharacterData->SetData( $l , "NewChaNum" , $NewChaNum );
                }
                */
            }
        }
    }
    
    //อัพเดทข้อมูลเพื่อใช้ในการสำรองในการรวมเซิบเผื่อมีอะไรผิดพลาดจะได้สามารถรวมเซิบได้อย่างต่อเนื่อง
    $szTemp = sprintf( "UPDATE ExportKey SET LastUserNum = %d, CharacterCount = CharacterCount + %d, UserCount = UserCount + %d WHERE ExportNum = %d" , $LastUserNum , $CharacterCount , $pUserList->GetRollData() , $cTargetWeb->ExportNum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    
    $cNeoSQLConnectODBC->CloseRanUser();
    $cNeoSQLConnectODBC->CloseRanGame();
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    $time_end = microtime_float();
    
    $tookMS = ($time_end - $time_start);
    
    printf("S:%f:%d:%d",$tookMS,$LastUserNum,$pUserList->GetRollData());
    
    for( $i = 0 ; $i < $pUserList->GetRollData() ; $i++)
    {
        $ppData = $pUserList->GetData($i);
        
        $pCharacterList = $ppData["CharacterList"];
        printf(":%s:%d:%d:%d",$ppData["UserID"],$ppData["UserNum"],$ppData["NewUserNum"],$pCharacterList->GetRollData());
        
        for( $n = 0 ; $n < $pCharacterList->GetRollData() ; $n++ )
        {
            $ppCharacterData = $pCharacterList->GetData($n);
            
            $ChaNameUTF8 = CBinaryCover::tis620_to_utf8($ppCharacterData["ChaName"]);
            //printf( ":%s:%d:%d" , $ChaNameUTF8 , $ppCharacterData["ChaNum"] , $ppCharacterData["NewChaNum"] );
            printf( ":%s:%d" , $ChaNameUTF8 , $ppCharacterData["ChaNum"] );
        }
    }
}

function CMD_IMPORT_ROLLBACK_PROCESS()
{
    global $MemNum;
    global $CURRENT_SESSION_ROLLBACK;
    global $CURRENT_SESSION_CWEB;
    
    $start_time = microtime_float();
    
    $cWeb = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_CWEB , IN_SESSION ) );
    
    $rollbackData = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_ROLLBACK , IN_SESSION ) );
    
    if ( $rollbackData->GetRollData() > 0 )
    {
        //for( $i = $rollbackData->GetRollData(), $l = 0 ; $i > 0 && $l < 10 ; $i-- , $l++ )
        {
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $cNeoSQLConnectODBC->ConnectRanUser($cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB());
            $cNeoSQLConnectODBC->ConnectRanGame($cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB());

            $ppData = $rollbackData->GetData($rollbackData->GetRollData()-1);

            //ลบข้อมูลที่ RanGame::ChaInfo
            $szTemp = sprintf( "DELETE ChaInfo WHERE UserNum = %d" , $ppData["UserNum"] );
            $cNeoSQLConnectODBC->QueryRanGame($szTemp);

            //ลบข้อมูลที่ RanUser::UserInfo
            $szTemp = sprintf( "DELETE UserInfo WHERE UserNum = %d" , $ppData["UserNum"] );
            $cNeoSQLConnectODBC->QueryRanUser($szTemp);

            //ลบช้อมูลที่ ShopDB::ExportLog
            $szTemp = sprintf( "DELETE ExportLog WHERE LogNum = %d" , $ppData["LogNum"] );
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);

            //ลบข้อมูลที่ ShopDB::UserInfo
            $szTemp = sprintf( "DELETE UserInfo WHERE UserID = '%s' AND MemNum = %d" , $ppData["UserID"] , $MemNum );
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);

            $cNeoSQLConnectODBC->CloseRanGame();
            $cNeoSQLConnectODBC->CloseRanUser();
            $cNeoSQLConnectODBC->CloseRanWeb();

            $end_time = microtime_float();

            printf( "S:UserID:%s, UserNum:%d, took:%f ms" , $ppData["UserID"] , $ppData["UserNum"] , ( $end_time - $start_time ) );

            $rollbackData->ErasePop();

            CInput::GetInstance()->AddValue( $CURRENT_SESSION_ROLLBACK , serialize( $rollbackData ) , IN_SESSION );
            CInput::GetInstance()->UpdateSession();
        }
    }else{
        echo "F";
    }
}

function CMD_IMPORT_ROLLBACK()
{
    global $CURRENT_SESSION_ROLLBACK;
    global $CURRENT_SESSION_TARGET_CWEB;
    
    $start_time = microtime_float();
    
    $cTargetWeb = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_TARGET_CWEB , IN_SESSION ) );
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    
    $rollbackData = new _tdata();
    
    $szTemp = sprintf( "SELECT LogNum,UserNum,UserID FROM ExportLog WHERE ExportNum = %d" , $cTargetWeb->ExportNum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $rollbackData->AddData("LogNum", $cNeoSQLConnectODBC->Result("LogNum",ODBC_RETYPE_INT));
        $rollbackData->AddData("UserNum", $cNeoSQLConnectODBC->Result("UserNum",ODBC_RETYPE_INT));
        $rollbackData->AddData("UserID", $cNeoSQLConnectODBC->Result("UserID",ODBC_RETYPE_ENG));
        
        $rollbackData->NextData();
    }
    
    printf( "<div><font color=\"red\">Please don't close or change before success.</font></div>" );
    printf( "<select size=\"50\" id=\"exportlog\" style=\"width:600px;\">" );
    printf( "</select>" );
    
    $szTemp = sprintf( "UPDATE ExportKey SET LastUserNum = 0, CharacterCount = 0, UserCount = 0 WHERE ExportNum = %d" , $cTargetWeb->ExportNum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION_ROLLBACK , serialize( $rollbackData ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    $end_time = microtime_float();
    
    echo "<script>export_Rollback_process();</script>";
    
    echo "<div>took " . ( $end_time - $start_time ) . " ms</div>" ;
}

function CMD_UI()
{
    global $MemNum;
    
    global $nServerType;
    
    //เปิดให้ทดสอบแค่เซิบ Neo เท่านั้น
    //if ( $nServerType != SERVTYPE_PLUSONLINE ) die( "<font color=\"red\">your server version unavailable</font>" );
    
    //echo "<div><a href=\"javascript:ChooseMenu( 'export' );\">Export</a></div>";
    //echo "<div><font color=\"red\">In the maintenance</font></div>";
    echo "<div><font color=\"red\">Beta Tester</font></div>";
    
    echo "<div>"
    . "<font color=\"orange\"><b><h2>คำเตือน</h2></b></font>"
    . "<ul>"
    . "<li>ในการรวมเซิบมีความเสี่ยงที่จะผิดพลาด และไม่สามารถกู้คืนได้</li>"
    . "<li>ก่อนรวมเซิบควร Backup Database ของเซิบต้นทางและปลายทางไว้ก่อนเสมอ</li>"
    . "<li>และควรปิดเซิบเวอร์ก่อนทำการรวมเซิบเวอร์เพื่อป้องกันการผิดพลาดอื่นๆที่จะตามมา</li>"
    . "<li>กรุณาอ่านคู่มือการใช้งานและสิ่งที่ต้องทำก่อนรวมเซิบเวอร์ให้เข้าใจก่อนจะทำการรวมเซิบเวอร์</li>"
    . "<li>หากมีอะไรผิดพลาดใดๆทาง Shop ไม่มีการ Backup สำรองไว้ให้และไม่สามารกู้คืนอะไรใดๆให้ได้</li>"
    . "<li>ในการรวมเซิบหรือทำการ Rollback นั้น Internet ของท่านจะต้องเสถียน และไม่มีการหลุดมิเช่นนั้นอาจจะทำให้การรวมผิดพลาดได้</li>"
    . "<li>ในระหว่างที่ระบบกำลังดำเนินการรวมเซิบเวอร์หรือ Rollback ใดๆก็ตามหาก ระบบยังดำเนินการอยู่ห้ามปิด Web Browser หรือเปลี่ยนหน้าโดยเด็ดขาด</li>"
    . "<li>ในระหว่างการรวมเซิร์ฟเวอร์ เซิร์ฟเวอร์ต้นทางหากทำการลบ Key Code เด็ดขาดไม่เช่นนั้นการรวมจะผิดพลาดทันที</li>"
    . "</ul>"
    . "</div>";
    
    echo "<div>"
    . "<font color=\"yellow\"><b><h2>คู่มือการใช้งาน</h2></b></font>"
    . "<ul>"
    . "<li>หากคุณเป็นเซิร์ฟเวอร์ต้นทางให้ทำการกดปุ่ม CREATE EXPORT KEY ท่านจะได้รับ Key Code เพื่อนำไปใช้เป็น Code ในการอ้างอิงการรวมเซิบเวอร์</li>"
    . "<li>เมื่อเลิกใช้งานการรวมเซิบเวอร์ให้ทำการกดปุ่ม DELETE KEY เพื่อทำการยกเลิก Key Code ในการรวมเซิบเวอร์</li>"
    . "<li>หากคุณเป็นเซิร์ฟเวอร์ปลายทาง เมื่อได้รับ Key Code มาแล้วให้นำมาใส่ที่ช่อง Import Key และกดปุ่ม IMPORT</li>"
    . "<li>ระบบจะทำการเปลี่ยนหน้าไปยัง Import ในหน้านี้จะมีข้อมูลตัวละครทั้งหมดและไอดีทั้งหมด</li>"
    . "<li>ก่อนรวมเซิบเวอร์ท่านจะต้องทำตั้งค่า Ext Same ID ในกรณีที่ไอดีซ้ำระบบจะทำการเติมสิ่งที่คุณกรอกต่อท้ายไอดีเช่น gmworld เป็น gmworld_tt</li>"
    . "<li>และ Ext Same Char ไว้สำหรับชื่อตัวละครที่ซ้ำเช่น TEST เป็น TEST@TT</li>"
    . "<li>เมื่อตังค่าต่างๆเสร็จแล้ว เมื่อคุณอ่านคำเตือนต่างๆและสิ่งที่ควรระวังไว้เรียบร้อยแล้วให้ทำการกดปุ่ม BEGIN IMPORT ระบบจะเริ่มทำการย้ายไอดีจากเซิบเวอร์ต้นทางมาปลายทาง</li>"
    . "<li>ในระหว่างการรวมเซิบเวอร์นั้นให้สังเกตุข้อความตรง Status ไว้ ถ้าเสร็จแล้วมันจะแสดงเป็น COMPLETE</li>"
    . "<li>เมื่อท่านพบปัญหาบางอย่างในการรวมทาง Shop ได้ทำระบบ RollBack Import ไว้ให้ แต่ก็มีเงื่อนไขและความเสี่ยงเช่นกัน</li>"
    . "<li>การ Rollback Import นั้น ระบบจะทำการลบ ID และ ตัวละครที่ย้ายมาทั้งหมดออก</li>"
    . "<li>ในระหว่างที่ทำการ Rollback ให้สังเกตุข้อมูล Rollback Success แสดงว่าระบบได้ทำการลบข้อมูลที่ย้ายมาทั้งหมดเสร็จสิ้นแล้ว</li>"
    . "<li>ในการรวมเซิร์ฟเวอร์นั้นจะไม่สามารถรวมจากเซิร์ฟเวอร์ที่ไม่ใช่เซิร์ฟเวอร์ชนิดเดียวกันได้เช่นจาก Ep3 เป็น Ep4 ไม่ได้, ต้องเป็น Ep3 กับ Ep3 เท่านั้น</li>"
    . "<li>และ Database จะต้องตรงกันทุกอย่างมิเช่นนั้นข้อมูลอาจจะส่งไปไม่ถึงและมีการผิดพลาดเกิดขึ้น</li>"
    . "<li>หากพบข้อสงสัยหรือปัญหาใดๆติดต่อทีมงาน</li>"
    . "</ul>"
    . "</div>";
    
    echo "<div>"
    . "<font color=\"red\"><b><h2>สิ่งที่จะไม่ถูกย้ายหรือถูกรวม</h2></b></font>"
    . "<ul>"
    . "<li>กิวจะไม่ถูกย้าย</li>"
    . "<li>ข้อมูลสัตว์เลี้ยงจะไม่ถูกย้าย</li>"
    . "<li>ข้อมูลยานพาหนะจะไม่ถูกย้าย</li>"
    . "<li>ของที่อยู่ในล็อกเกอร์จะไม่ถูกย้าย ทั้งล็อกเกอร์ส่วนตัวและล็อกเกอร์กิว</li>"
    . "<li>Quest ของตัวละครจะไม่ถูกย้าย</li>"
    //. "<li>เงินที่ติดอยู่กับตัวละครจะไม่ถูกย้าย</li>"
    . "<li>ItemShop ทุกอย่างที่อยู่ใน ItemBank หรือไอเทมในช่อง B จะไม่ถูกย้าย</li>"
    . "<li>ประวัติการใช้งานช๊อปทุกอย่างไม่ว่าจะเป็นการซื้อขายแลกเงินเติมเงินจะไม่ถูกย้าย</li>"
    . "</ul>"
    . "</div>";
    
    echo "<div id=\"exportKey\">";
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    
    $ExportKey = "";
    
    $szTemp = sprintf("SELECT ExportNum,ExportKey FROM ExportKey WHERE MemNum = %d" , $MemNum);
    $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
    
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $ExportKey = $cNeoSQLConnectODBC->Result("ExportKey",ODBC_RETYPE_ENG);
    }
    
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    echo "<hr style=\"width:50%;\">";
    echo "<h2><font color=\"\"><b>โซนเซิร์ฟเวอร์ต้นทาง</b></font></h2>";
    
    if( $ExportKey == "" )
    {
        echo "ปุ่มสร้าง Key Code สำหรับเซิร์ฟเวอร์ต้นทาง : <button type=\"button\" onclick=\"createExportKey();\">Create Export Key</button>";
    }else{
        echo "Export Key : <input type=\"text\" value=\"". $ExportKey ."\"><button onclick=\"deleteSubmit();\">DELETE KEY</button>";
    }
    
    echo "<hr style=\"width:50%;\">";
    
    echo "<h2><font color=\"\"><b>โซนเซิร์ฟเวอร์ปลายทาง</b></font></h2>";
    echo "Import Key : <input id=\"importKey\" type=\"text\" value=\"\">";
    echo "<button type=\"button\" onclick=\"importSubmit();\">Import</button>";
    
    echo "</div>";
    echo "<script type=\"text/javascript\" src=\"js/classicloading.js\"></script>";
    echo "<script type=\"text/javascript\" src=\"js/export.js\"></script>";
}

$procType = CInput::GetInstance()->GetValueString( "type" , IN_GET );

switch($procType)
{
    case "key": CMD_CREATEKEY(); break;
    case "delete": CMD_DELETEKEY(); break;
    case "submit": CMD_IMPORT(); break;
    
    case "importprocessbegin": CMD_IMPORT_BEGIN(); break;
    case "importprocess": CMD_IMPORT_PROCESS_ALL(); break;
    case "importprocesssingle": CMD_IMPORT_PROCESS_SINGLE(); break;
    
    case "importrollback": CMD_IMPORT_ROLLBACK(); break;
    case "importrollbackprocess": CMD_IMPORT_ROLLBACK_PROCESS(); break;
    
    default : CMD_UI(); break;
}

?>
