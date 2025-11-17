<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

class SetData
{
    public $Cha_StatsPoint = 0;
    public $Cha_SkillPoint = 0;
    public $Cha_StatsPointBegin = 0;
    public $Cha_SkillPointBegin = 0;
    public $School = 0;
    public $School_P = 0;
    public $Class = 0;
    public $Class_P = 0;
    public $CharMad = 0;
    public $CharMad_P = 0;
    public $CharReborn = 0;
    public $CharReborn_P = 0;
    public $CharReborn_Free = 0;
    public $CharReborn_Max = 0;
    public $CharRebornFreeOn = 0;
    public $CharRebornFreeCheck = 0;
    public $Login_PointFirst = 0;
    public $SkillOn = 0;
    public $Online2Point = 0;
    public $OnlineGetPoint = 0;
    public $OnlineTime = 0;
    public $StatOn = 0;
    public $StatPoint = 0;
    public $ResetSkill = 0;
    public $ResetSkillPoint = 0;
    public $ChangeName = 0;
    public $ChangeNamePoint = 0;
    public $CharRebornLevStart = 0;
    public $CharRebornLevCheck = 0;
    public $Class_Change_CheckStat = 0;
    public $Class_Change_CheckSkill = 0;
    public $ChaRebornGetPoint_Lv = 0;
    public $ChaRebornGetPoint_Value = 0;
    public $ParentID = 0;
    public $ChaDelete = 0;
    public $BonusPoint = 0;
    public $BonusPointEx = 0;
    
    public $cMemBuySkillPoint = NULL;
    public $cMemClass = NULL;
    public $cSkillSet = NULL;
    
    public function __construct( $MemNum )
    {
        $this->cMemBuySkillPoint = new CMemBuySkillPoint;
        $this->cMemBuySkillPoint->LoadData( $MemNum );
        
        $this->cMemClass = new CMemClass;
        $this->cMemClass->LoadData( $MemNum );
        
        CSkillSet::FristCheck( $MemNum );
        $this->cSkillSet = new CSkillSet;
        $this->cSkillSet->LoadSkillData( $MemNum );
    }
    
    public function Update2DB( $MemNum )
    {
        $szTemp = sprintf("UPDATE MemSys SET
                            Cha_StatsPoint = %d,
                            Cha_SkillPoint = %d,
                            Cha_StatsPointBegin = %d,
                            Cha_SkillPointBegin = %d,
                            School = %d,
                            School_P = %d,
                            Class = %d,
                            Class_P = %d,
                            CharMad = %d,
                            CharMad_P = %d,
                            CharReborn = %d,
                            CharReborn_P = %d,
                            CharReborn_Free = %d,
                            CharReborn_Max = %d,
                            CharRebornFreeOn = %d,
                            CharRebornFreeCheck = %d,
                            Login_PointFirst = %d,
                            SkillOn = %d,
                            Online2Point = %d,
                            OnlineGetPoint = %d,
                            OnlineTime = %d,
                            StatOn = %d,
                            StatPoint = %d,
                            ResetSkill = %d,
                            ResetSkillPoint = %d,
                            ChangeName = %d,
                            ChangeNamePoint = %d,
                            CharRebornLevStart = %d,
                            CharRebornLevCheck = %d,
                            Class_Change_CheckStat = %d,
                            Class_Change_CheckSkill = %d,

                            ChaRebornGetPoint_Lv = %d,
                            ChaRebornGetPoint_Value = %d,
                            ParentID = %d,
                            ChaDelete = %d,
                            BonusPoint = %d,
                            BonusPointEx = %d

                            WHERE MemNum = %d
                    "
                , $this->Cha_StatsPoint
                , $this->Cha_SkillPoint
                , $this->Cha_StatsPointBegin
                , $this->Cha_SkillPointBegin
                , $this->School
                , $this->School_P
                , $this->Class
                , $this->Class_P
                , $this->CharMad
                , $this->CharMad_P
                , $this->CharReborn
                , $this->CharReborn_P
                , $this->CharReborn_Free
                , $this->CharReborn_Max
                , $this->CharRebornFreeOn
                , $this->CharRebornFreeCheck
                , $this->Login_PointFirst
                , $this->SkillOn
                , $this->Online2Point
                , $this->OnlineGetPoint
                , $this->OnlineTime
                , $this->StatOn
                , $this->StatPoint
                , $this->ResetSkill
                , $this->ResetSkillPoint
                , $this->ChangeName
                , $this->ChangeNamePoint
                , $this->CharRebornLevStart
                , $this->CharRebornLevCheck
                , $this->Class_Change_CheckStat
                , $this->Class_Change_CheckSkill
                , $this->ChaRebornGetPoint_Lv
                , $this->ChaRebornGetPoint_Value
                , $this->ParentID
                , $this->ChaDelete
                , $this->BonusPoint
                , $this->BonusPointEx
                
                , $MemNum
                );
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        $cNeoSQLConnectODBC->CloseRanWeb();
    }
};

$CURRENT_SESSION = "UserSetData";

$pSetData = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
if ( !$pSetData )
{
    $MemNum = $cAdmin->GetMemNum();
    $pSetData = new SetData($MemNum);
    
    $szTemp = sprintf("
        IF EXISTS( SELECT MemNum FROM MemSys WHERE MemNum = %d )
        BEGIN
            SELECT Cha_StatsPoint,Cha_SkillPoint,Cha_StatsPointBegin,Cha_SkillPointBegin,School,School_P,Class,Class_P,CharMad,CharMad_P
                    ,CharReborn,CharReborn_P,CharReborn_Free,CharReborn_Max,CharRebornFreeOn,CharRebornFreeCheck
                    ,CharRebornLevStart,CharRebornLevCheck,Class_Change_CheckStat,Class_Change_CheckSkill
                    ,Login_PointFirst,SkillOn,Online2Point,OnlineGetPoint,OnlineTime,StatOn,StatPoint,ResetSkill,ResetSkillPoint
                    ,ChangeNamePoint,ChangeName,ChaRebornGetPoint_Lv,ChaRebornGetPoint_Value,ParentID,ChaDelete,BonusPoint,BonusPointEx
                    FROM MemSys WHERE MemNum = %d
        END
        ELSE
        BEGIN
            INSERT INTO MemSys(MemNum) VALUES( %d )
        END
        " , $MemNum , $MemNum , $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $pSetData->Cha_StatsPoint = $cNeoSQLConnectODBC->Result( "Cha_StatsPoint" , ODBC_RETYPE_INT );
        $pSetData->Cha_SkillPoint = $cNeoSQLConnectODBC->Result( "Cha_SkillPoint" , ODBC_RETYPE_INT );
        $pSetData->Cha_StatsPointBegin = $cNeoSQLConnectODBC->Result( "Cha_StatsPointBegin" , ODBC_RETYPE_INT );
        $pSetData->Cha_SkillPointBegin = $cNeoSQLConnectODBC->Result( "Cha_SkillPointBegin" , ODBC_RETYPE_INT );
        $pSetData->School = $cNeoSQLConnectODBC->Result( "School" , ODBC_RETYPE_INT );
        $pSetData->School_P = $cNeoSQLConnectODBC->Result( "School_P" , ODBC_RETYPE_INT );
        $pSetData->Class = $cNeoSQLConnectODBC->Result( "Class" , ODBC_RETYPE_INT );
        $pSetData->Class_P = $cNeoSQLConnectODBC->Result( "Class_P" , ODBC_RETYPE_INT );
        $pSetData->CharMad = $cNeoSQLConnectODBC->Result( "CharMad" , ODBC_RETYPE_INT );
        $pSetData->CharMad_P = $cNeoSQLConnectODBC->Result( "CharMad_P" , ODBC_RETYPE_INT );
        $pSetData->CharReborn = $cNeoSQLConnectODBC->Result( "CharReborn" , ODBC_RETYPE_INT );
        $pSetData->CharReborn_P = $cNeoSQLConnectODBC->Result( "CharReborn_P" , ODBC_RETYPE_INT );
        $pSetData->CharReborn_Free = $cNeoSQLConnectODBC->Result( "CharReborn_Free" , ODBC_RETYPE_INT );
        $pSetData->CharReborn_Max = $cNeoSQLConnectODBC->Result( "CharReborn_Max" , ODBC_RETYPE_INT );
        $pSetData->CharRebornFreeOn = $cNeoSQLConnectODBC->Result( "CharRebornFreeOn" , ODBC_RETYPE_INT );
        $pSetData->CharRebornFreeCheck = $cNeoSQLConnectODBC->Result( "CharRebornFreeCheck" , ODBC_RETYPE_INT );
        $pSetData->CharRebornLevStart = $cNeoSQLConnectODBC->Result( "CharRebornLevStart" , ODBC_RETYPE_INT );
        $pSetData->CharRebornLevCheck = $cNeoSQLConnectODBC->Result( "CharRebornLevCheck" , ODBC_RETYPE_INT );
        $pSetData->Class_Change_CheckStat = $cNeoSQLConnectODBC->Result( "Class_Change_CheckStat" , ODBC_RETYPE_INT );
        $pSetData->Class_Change_CheckSkill = $cNeoSQLConnectODBC->Result( "Class_Change_CheckSkill" , ODBC_RETYPE_INT );
        $pSetData->Login_PointFirst = $cNeoSQLConnectODBC->Result( "Login_PointFirst" , ODBC_RETYPE_INT );
        $pSetData->SkillOn = $cNeoSQLConnectODBC->Result( "SkillOn" , ODBC_RETYPE_INT );
        $pSetData->Online2Point = $cNeoSQLConnectODBC->Result( "Online2Point" , ODBC_RETYPE_INT );
        $pSetData->OnlineGetPoint = $cNeoSQLConnectODBC->Result( "OnlineGetPoint" , ODBC_RETYPE_INT );
        $pSetData->OnlineTime = $cNeoSQLConnectODBC->Result( "OnlineTime" , ODBC_RETYPE_INT );
        $pSetData->StatOn = $cNeoSQLConnectODBC->Result( "StatOn" , ODBC_RETYPE_INT );
        $pSetData->StatPoint = $cNeoSQLConnectODBC->Result( "StatPoint" , ODBC_RETYPE_INT );
        $pSetData->ResetSkill = $cNeoSQLConnectODBC->Result( "ResetSkill" , ODBC_RETYPE_INT );
        $pSetData->ResetSkillPoint = $cNeoSQLConnectODBC->Result( "ResetSkillPoint" , ODBC_RETYPE_INT );
        $pSetData->ChangeNamePoint = $cNeoSQLConnectODBC->Result( "ChangeNamePoint" , ODBC_RETYPE_INT );
        $pSetData->ChangeName = $cNeoSQLConnectODBC->Result( "ChangeName" , ODBC_RETYPE_INT );
        $pSetData->ChaRebornGetPoint_Lv = $cNeoSQLConnectODBC->Result( "ChaRebornGetPoint_Lv" , ODBC_RETYPE_INT );
        $pSetData->ChaRebornGetPoint_Value = $cNeoSQLConnectODBC->Result( "ChaRebornGetPoint_Value" , ODBC_RETYPE_INT );
        $pSetData->ParentID = $cNeoSQLConnectODBC->Result( "ParentID" , ODBC_RETYPE_INT );
        $pSetData->ChaDelete = $cNeoSQLConnectODBC->Result( "ChaDelete" , ODBC_RETYPE_INT );
        $pSetData->BonusPoint = $cNeoSQLConnectODBC->Result( "BonusPoint" , ODBC_RETYPE_INT );
        $pSetData->BonusPointEx = $cNeoSQLConnectODBC->Result( "BonusPointEx" , ODBC_RETYPE_INT );
    }
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSetData ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
}

require_once 'set_project.cmd.php';

$page = CInput::GetInstance()->GetValueString( "page" , IN_GET );
CInput::GetInstance()->BuildFrom(IN_POST);

function CMD_PROCESS()
{
    global $page;
    switch( $page )
    {
        case "p0":{
            CMD_PROCESS_P0();
        }break;
        case "p1":{
            CMD_PROCESS_P1();
        }break;
        case "p2":{
            CMD_PROCESS_P2();
        }break;
        case "p3":{
            CMD_PROCESS_P3();
        }break;
        case "p4":{
            CMD_PROCESS_P4();
        }break;
    }
}

function CMD_UI()
{
    global $page;
    switch( $page )
    {
        case "p0":{
            CMD_UI_P0();
            exit;
        }break;
        case "p1":{
            CMD_UI_P1();
            exit;
        }break;
        case "p2":{
            CMD_UI_P2();
            exit;
        }break;
        case "p3":{
            CMD_UI_P3();
            exit;
        }break;
        case "p4":{
            CMD_UI_P4();
            exit;
        }break;
    }
?>
<div id="main_set">
    <span id="workSET" class="info" style="display: none;">บันทึกเรียบร้อย</span>
    <span id="workFAIL" class="info_err" style="display: none;">ไม่สามารถบันทึกได้เนื่องจากมีปัญหาบางประการ</span>
    <div align="left"><b><u>เมนู</u> : </b><button onclick="switchMenu( 'p0' );">ทั่วไป</button> | <button onclick="switchMenu( 'p1' );">เมนูอื่นๆ</button> | <button onclick="switchMenu( 'p2' );">ระบบเปลี่ยนอาชีพ</button> | <button onclick="switchMenu( 'p3' );">ระบบจุติ</button> | <button onclick="switchMenu( 'p4' );">รับสกิล</button></div>
    <div id="set_process"></div>
</div>
<script type="text/javascript" src="js/set.js"></script>
<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( !$submit )
    CMD_UI();
else
    CMD_PROCESS();

?>
