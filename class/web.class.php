<?php
define( "__WEBDBDATA" , "SERIALWEBDBDATA");
define( "__WEBSYSTEMINFO" , "SERIALWEBSYSTEMINFO" );

class __CWebSystem_
{
    public $Sys_Class;
    public $Sys_Class_P;
    public $Class_Change_CheckStat;
    public $Class_Change_CheckSkill;
    public $Sys_School;
    public $Sys_School_P;
    public $Sys_CharMad;
    public $Sys_CharMad_P;
    public $Cha_StatsPoint;
    public $Cha_SkillPoint;
    public $Cha_StatsPointBegin;
    public $Cha_SkillPointBegin;
    public $Sys_CharReborn;
    public $Sys_CharReborn_P;
    public $Sys_CharRebornFree;
    public $Sys_CharRebornMax;
    public $Sys_CharRebornFreeOn;
    public $Sys_CharRebornFreeCheck;
    public $Sys_CharRebornLevStart;
    public $Sys_CharRebornLevCheck;
    public $Sys_SkillDown;
    public $Sys_GameTime;
    public $Sys_OnlineTime;
    public $Sys_OnlineGetPoint;
    public $Sys_StatOn;
    public $Sys_StatPoint;
    public $Sys_ResetSkill;
    public $Sys_ResetSkillPoint;
    public $Sys_ChangeName;
    public $Sys_ChangeName_Point;
    public $Login_GetPoint_Fist;
    public $Sys_ChaRebornGetPoint_Lv;
    public $Sys_ChaRebornGetPoint_Value;
    public $ServerType;
    public $Sys_MapWarp;
    public $Sys_ParentID;
    public $Sys_ChaDelete;
    public $Sys_BonusPoint;
    public $Sys_BonusPointEx;
};

class __CWebSystem extends __CWebSystem_
{
    private static $Instance;
    
    public function __construct(  )
    {
    }
    
    public function __destruct()
    {
    }
    
    public static function GetInstance()
    {
        if ( !self::$Instance )
        {
            self::$Instance = new self();
        }
        return self::$Instance;
    }
    
    public function Asset( $pData )
    {
        $this->Sys_Class = $pData->Sys_Class;
        $this->Sys_Class_P = $pData->Sys_Class_P;
        $this->Class_Change_CheckStat = $pData->Class_Change_CheckStat;
        $this->Class_Change_CheckSkill = $pData->Class_Change_CheckSkill;
        $this->Sys_School = $pData->Sys_School;
        $this->Sys_School_P = $pData->Sys_School_P;
        $this->Sys_CharMad = $pData->Sys_CharMad;
        $this->Sys_CharMad_P = $pData->Sys_CharMad_P;
        $this->Cha_StatsPoint = $pData->Cha_StatsPoint;
        $this->Cha_SkillPoint = $pData->Cha_SkillPoint;
        $this->Cha_StatsPointBegin = $pData->Cha_StatsPointBegin;
        $this->Cha_SkillPointBegin = $pData->Cha_SkillPointBegin;
        $this->Sys_CharReborn = $pData->Sys_CharReborn;
        $this->Sys_CharReborn_P = $pData->Sys_CharReborn_P;
        $this->Sys_CharRebornFree = $pData->Sys_CharRebornFree;
        $this->Sys_CharRebornMax = $pData->Sys_CharRebornMax;
        $this->Sys_CharRebornFreeOn = $pData->Sys_CharRebornFreeOn;
        $this->Sys_CharRebornFreeCheck = $pData->Sys_CharRebornFreeCheck;
        $this->Sys_CharRebornLevStart = $pData->Sys_CharRebornLevStart;
        $this->Sys_CharRebornLevCheck = $pData->Sys_CharRebornLevCheck;
        $this->Sys_SkillDown = $pData->Sys_SkillDown;
        $this->Sys_GameTime = $pData->Sys_GameTime;
        $this->Sys_OnlineTime = $pData->Sys_OnlineTime;
        $this->Sys_OnlineGetPoint = $pData->Sys_OnlineGetPoint;
        $this->Sys_StatOn = $pData->Sys_StatOn;
        $this->Sys_StatPoint = $pData->Sys_StatPoint;
        $this->Sys_ResetSkill = $pData->Sys_ResetSkill;
        $this->Sys_ResetSkillPoint = $pData->Sys_ResetSkillPoint;
        $this->Sys_ChangeName = $pData->Sys_ChangeName;
        $this->Sys_ChangeName_Point = $pData->Sys_ChangeName_Point;
        $this->Login_GetPoint_Fist = $pData->Login_GetPoint_Fist;
        $this->Sys_ChaRebornGetPoint_Lv = $pData->Sys_ChaRebornGetPoint_Lv;
        $this->Sys_ChaRebornGetPoint_Value = $pData->Sys_ChaRebornGetPoint_Value;
        $this->ServerType = $pData->ServerType;
        $this->Sys_MapWarp = $pData->Sys_MapWarp;
        $this->Sys_ParentID = $pData->Sys_ParentID;
        $this->Sys_ChaDelete = $pData->Sys_ChaDelete;
        $this->Sys_BonusPoint = $pData->Sys_BonusPoint;
        $this->Sys_BonusPointEx = $pData->Sys_BonusPointEx;
    }
    
    public function GetData()
    {
        $pData = new __CWebSystem_;
        $pData->Sys_Class = $this->Sys_Class;
        $pData->Sys_Class_P = $this->Sys_Class_P;
        $pData->Class_Change_CheckStat = $this->Class_Change_CheckStat;
        $pData->Class_Change_CheckSkill = $this->Class_Change_CheckSkill;
        $pData->Sys_School = $this->Sys_School;
        $pData->Sys_School_P = $this->Sys_School_P;
        $pData->Sys_CharMad = $this->Sys_CharMad;
        $pData->Sys_CharMad_P = $this->Sys_CharMad_P;
        $pData->Cha_StatsPoint = $this->Cha_StatsPoint;
        $pData->Cha_SkillPoint = $this->Cha_SkillPoint;
        $pData->Cha_StatsPointBegin = $this->Cha_StatsPointBegin;
        $pData->Cha_SkillPointBegin = $this->Cha_SkillPointBegin;
        $pData->Sys_CharReborn = $this->Sys_CharReborn;
        $pData->Sys_CharReborn_P = $this->Sys_CharReborn_P;
        $pData->Sys_CharRebornFree = $this->Sys_CharRebornFree;
        $pData->Sys_CharRebornMax = $this->Sys_CharRebornMax;
        $pData->Sys_CharRebornFreeOn = $this->Sys_CharRebornFreeOn;
        $pData->Sys_CharRebornFreeCheck = $this->Sys_CharRebornFreeCheck;
        $pData->Sys_CharRebornLevStart = $this->Sys_CharRebornLevStart;
        $pData->Sys_CharRebornLevCheck = $this->Sys_CharRebornLevCheck;
        $pData->Sys_SkillDown = $this->Sys_SkillDown;
        $pData->Sys_GameTime = $this->Sys_GameTime;
        $pData->Sys_OnlineTime = $this->Sys_OnlineTime;
        $pData->Sys_OnlineGetPoint = $this->Sys_OnlineGetPoint;
        $pData->Sys_StatOn = $this->Sys_StatOn;
        $pData->Sys_StatPoint = $this->Sys_StatPoint;
        $pData->Sys_ResetSkill = $this->Sys_ResetSkill;
        $pData->Sys_ResetSkillPoint = $this->Sys_ResetSkillPoint;
        $pData->Sys_ChangeName = $this->Sys_ChangeName;
        $pData->Sys_ChangeName_Point = $this->Sys_ChangeName_Point;
        $pData->Login_GetPoint_Fist = $this->Login_GetPoint_Fist;
        $pData->Sys_ChaRebornGetPoint_Lv = $this->Sys_ChaRebornGetPoint_Lv;
        $pData->Sys_ChaRebornGetPoint_Value = $this->Sys_ChaRebornGetPoint_Value;
        $pData->ServerType = $this->ServerType;
        $pData->Sys_MapWarp = $this->Sys_MapWarp;
        $pData->Sys_ParentID = $this->Sys_ParentID;
        $pData->Sys_ChaDelete = $this->Sys_ChaDelete;
        $pData->Sys_BonusPoint = $this->Sys_BonusPoint;
        $pData->Sys_BonusPointEx = $this->Sys_BonusPointEx;
        return $pData;
    }
    
    public function Set( $Sys_Class , $Sys_Class_P , $Class_Change_CheckStat , $Class_Change_CheckSkill , $Sys_School , $Sys_School_P , $Sys_CharMad , $Sys_CharMad_P ,
                         $Cha_StatsPoint , $Cha_SkillPoint , $Cha_StatsPointBegin , $Cha_SkillPointBegin , $Sys_CharReborn , $Sys_CharReborn_P , $Sys_CharRebornFree , $Sys_CharRebornMax ,
                         $Sys_CharRebornFreeOn , $Sys_CharRebornFreeCheck , $Sys_CharRebornLevStart , $Sys_CharRebornLevCheck , $Sys_SkillDown , $Sys_GameTime , $Sys_OnlineTime , $Sys_OnlineGetPoint ,
                         $Sys_StatOn , $Sys_StatPoint , $Sys_ResetSkill , $Sys_ResetSkillPoint , $Sys_ChangeName , $Sys_ChangeName_Point , $Login_GetPoint_Fist , $Sys_ChaRebornGetPoint_Lv , $Sys_ChaRebornGetPoint_Value ,
                         $ServerType , $Sys_MapWarp , $Sys_ParentID , $Sys_ChaDelete , $Sys_BonusPoint , $Sys_BonusPointEx
                        )
    {
        $this->Sys_Class = $Sys_Class;
        $this->Sys_Class_P = $Sys_Class_P;
        $this->Class_Change_CheckStat = $Class_Change_CheckStat;
        $this->Class_Change_CheckSkill = $Class_Change_CheckSkill;
        $this->Sys_School = $Sys_School;
        $this->Sys_School_P = $Sys_School_P;
        $this->Sys_CharMad = $Sys_CharMad;
        $this->Sys_CharMad_P = $Sys_CharMad_P;
        $this->Cha_StatsPoint = $Cha_StatsPoint;
        $this->Cha_SkillPoint = $Cha_SkillPoint;
        $this->Cha_StatsPointBegin = $Cha_StatsPointBegin;
        $this->Cha_SkillPointBegin = $Cha_SkillPointBegin;
        $this->Sys_CharReborn = $Sys_CharReborn;
        $this->Sys_CharReborn_P = $Sys_CharReborn_P;
        $this->Sys_CharRebornFree = $Sys_CharRebornFree;
        $this->Sys_CharRebornMax = $Sys_CharRebornMax;
        $this->Sys_CharRebornFreeOn = $Sys_CharRebornFreeOn;
        $this->Sys_CharRebornFreeCheck = $Sys_CharRebornFreeCheck;
        $this->Sys_CharRebornLevStart = $Sys_CharRebornLevStart;
        $this->Sys_CharRebornLevCheck = $Sys_CharRebornLevCheck;
        $this->Sys_SkillDown =  $Sys_SkillDown;
        $this->Sys_GameTime =  $Sys_GameTime;
        $this->Sys_OnlineTime =  $Sys_OnlineTime;
        $this->Sys_OnlineGetPoint =  $Sys_OnlineGetPoint;
        $this->Sys_StatOn =  $Sys_StatOn;
        $this->Sys_StatPoint =  $Sys_StatPoint;
        $this->Sys_ResetSkill =  $Sys_ResetSkill;
        $this->Sys_ResetSkillPoint =  $Sys_ResetSkillPoint;
        $this->Sys_ChangeName =  $Sys_ChangeName;
        $this->Sys_ChangeName_Point =  $Sys_ChangeName_Point;
        $this->Login_GetPoint_Fist = $Login_GetPoint_Fist;
        $this->Sys_ChaRebornGetPoint_Lv = $Sys_ChaRebornGetPoint_Lv;
        $this->Sys_ChaRebornGetPoint_Value = $Sys_ChaRebornGetPoint_Value;
        $this->ServerType = $ServerType;
        $this->Sys_MapWarp = $Sys_MapWarp;
        $this->Sys_ParentID = $Sys_ParentID;
        $this->Sys_ChaDelete = $Sys_ChaDelete;
        $this->Sys_BonusPoint = $Sys_BonusPoint;
        $this->Sys_BonusPointEx = $Sys_BonusPointEx;
    }
    
    public function Get( &$Sys_Class , &$Sys_Class_P , &$Class_Change_CheckStat , &$Class_Change_CheckSkill , &$Sys_School , &$Sys_School_P , &$Sys_CharMad , &$Sys_CharMad_P ,
                         &$Cha_StatsPoint , &$Cha_SkillPoint , &$Cha_StatsPointBegin , &$Cha_SkillPointBegin , &$Sys_CharReborn , &$Sys_CharReborn_P , &$Sys_CharRebornFree , &$Sys_CharRebornMax ,
                         &$Sys_CharRebornFreeOn , &$Sys_CharRebornFreeCheck , &$Sys_CharRebornLevStart , &$Sys_CharRebornLevCheck , &$Sys_SkillDown , &$Sys_GameTime , &$Sys_OnlineTime , &$Sys_OnlineGetPoint ,
                         &$Sys_StatOn , &$Sys_StatPoint , &$Sys_ResetSkill , &$Sys_ResetSkillPoint , &$Sys_ChangeName , &$Sys_ChangeName_Point , &$Login_GetPoint_Fist , &$Sys_ChaRebornGetPoint_Lv , &$Sys_ChaRebornGetPoint_Value ,
                         &$ServerType , &$Sys_MapWarp , &$Sys_ParentID , &$Sys_ChaDelete , &$Sys_BonusPoint , &$Sys_BonusPointEx
                        )
    {
        $Sys_Class = $this->Sys_Class;
        $Sys_Class_P = $this->Sys_Class_P;
        $Class_Change_CheckStat = $this->Class_Change_CheckStat;
        $Class_Change_CheckSkill = $this->Class_Change_CheckSkill;
        $Sys_School = $this->Sys_School;
        $Sys_School_P = $this->Sys_School_P;
        $Sys_CharMad = $this->Sys_CharMad;
        $Sys_CharMad_P = $this->Sys_CharMad_P;
        $Cha_StatsPoint = $this->Cha_StatsPoint;
        $Cha_SkillPoint = $this->Cha_SkillPoint;
        $Cha_StatsPointBegin = $this->Cha_StatsPointBegin;
        $Cha_SkillPointBegin = $this->Cha_SkillPointBegin;
        $Sys_CharReborn = $this->Sys_CharReborn;
        $Sys_CharReborn_P = $this->Sys_CharReborn_P;
        $Sys_CharRebornFree = $this->Sys_CharRebornFree;
        $Sys_CharRebornMax = $this->Sys_CharRebornMax;
        $Sys_CharRebornFreeOn = $this->Sys_CharRebornFreeOn;
        $Sys_CharRebornFreeCheck = $this->Sys_CharRebornFreeCheck;
        $Sys_CharRebornLevStart = $this->Sys_CharRebornLevStart;
        $Sys_CharRebornLevCheck = $this->Sys_CharRebornLevCheck;
        $Sys_SkillDown =  $this->Sys_SkillDown;
        $Sys_GameTime =  $this->Sys_GameTime;
        $Sys_OnlineTime =  $this->Sys_OnlineTime;
        $Sys_OnlineGetPoint =  $this->Sys_OnlineGetPoint;
        $Sys_StatOn =  $this->Sys_StatOn;
        $Sys_StatPoint =  $this->Sys_StatPoint;
        $Sys_ResetSkill =  $this->Sys_ResetSkill;
        $Sys_ResetSkillPoint =  $this->Sys_ResetSkillPoint;
        $Sys_ChangeName =  $this->Sys_ChangeName;
        $Sys_ChangeName_Point =  $this->Sys_ChangeName_Point;
        $Login_GetPoint_Fist = $this->Login_GetPoint_Fist;
        $Sys_ChaRebornGetPoint_Lv = $this->Sys_ChaRebornGetPoint_Lv;
        $Sys_ChaRebornGetPoint_Value = $this->Sys_ChaRebornGetPoint_Value;
        $ServerType = $this->ServerType;
        $Sys_MapWarp = $this->Sys_MapWarp;
        $Sys_ParentID = $this->Sys_ParentID;
        $Sys_ChaDelete = $this->Sys_ChaDelete;
        $Sys_BonusPoint = $this->Sys_BonusPoint;
        $Sys_BonusPointEx = $this->Sys_BonusPointEx;
    }
};

class __CWebDB_
{
    public $RanGame_IP = "";
    public $RanGame_User= "";
    public $RanGame_Pass= "";
    public $RanGame_DB = "";

    public $RanUser_IP = "";
    public $RanUser_User = "";
    public $RanUser_Pass = "";
    public $RanUser_DB = "";

    public $RanShop_IP = "";
    public $RanShop_User = "";
    public $RanShop_Pass = "";
    public $RanShop_DB = "";
};

class __CWebDB extends __CWebDB_
{
    private static $Instance;
    
    public function __construct(  )
    {
    }
    
    public function __destruct()
    {
    }
    
    public static function GetInstance()
    {
        if ( !self::$Instance )
        {
            self::$Instance = new self();
        }
        return self::$Instance;
    }
    
    public function Asset( $pData )
    {
        $this->RanGame_IP = $pData->RanGame_IP;
        $this->RanGame_User = $pData->RanGame_User;
        $this->RanGame_Pass = $pData->RanGame_Pass;
        $this->RanGame_DB = $pData->RanGame_DB;
        
        $this->RanShop_IP = $pData->RanShop_IP;
        $this->RanShop_User = $pData->RanShop_User;
        $this->RanShop_Pass = $pData->RanShop_Pass;
        $this->RanShop_DB = $pData->RanShop_DB;
        
        $this->RanUser_IP = $pData->RanUser_IP;
        $this->RanUser_User = $pData->RanUser_User;
        $this->RanUser_Pass = $pData->RanUser_Pass;
        $this->RanUser_DB = $pData->RanUser_DB;
    }
    
    public function GetData()
    {
        $pData = new __CWebDB_;
        $pData->RanGame_IP = $this->RanGame_IP;
        $pData->RanGame_User = $this->RanGame_User;
        $pData->RanGame_Pass = $this->RanGame_Pass;
        $pData->RanGame_DB = $this->RanGame_DB;
        
        $pData->RanShop_IP = $this->RanShop_IP;
        $pData->RanShop_User = $this->RanShop_User;
        $pData->RanShop_Pass = $this->RanShop_Pass;
        $pData->RanShop_DB = $this->RanShop_DB;
        
        $pData->RanUser_IP = $this->RanUser_IP;
        $pData->RanUser_User = $this->RanUser_User;
        $pData->RanUser_Pass = $this->RanUser_Pass;
        $pData->RanUser_DB = $this->RanUser_DB;
        return $pData;
    }
    
    public function IsRanGame()
    {
        return ( strlen($this->RanGame_IP) && strlen($this->RanGame_User) && strlen($this->RanGame_Pass) && strlen($this->RanGame_DB) );
    }
    
    public function IsRanShop()
    {
        return ( strlen($this->RanShop_IP) && strlen($this->RanShop_User) && strlen($this->RanShop_Pass) && strlen($this->RanShop_DB) );
    }
    
    public function IsRanUser()
    {
        return ( strlen($this->RanUser_IP) && strlen($this->RanUser_User) && strlen($this->RanUser_Pass) && strlen($this->RanUser_DB) );
    }
    
    public function GetRanGame( &$IP , &$User , &$Pass , &$DB )
    {
        $IP = $this->RanGame_IP;
        $User = $this->RanGame_User;
        $Pass = $this->RanGame_Pass;
        $DB = $this->RanGame_DB;
    }
    
    public function GetRanShop( &$IP , &$User , &$Pass , &$DB )
    {
        $IP = $this->RanShop_IP;
        $User = $this->RanShop_User;
        $Pass = $this->RanShop_Pass;
        $DB = $this->RanShop_DB;
    }
    
    public function GetRanUser( &$IP , &$User , &$Pass , &$DB )
    {
        $IP = $this->RanUser_IP;
        $User = $this->RanUser_User;
        $Pass = $this->RanUser_Pass;
        $DB = $this->RanUser_DB;
    }
    
    public function SetRanGame( $IP , $User , $Pass , $DB )
    {
        $this->RanGame_IP = $IP;
        $this->RanGame_User = $User;
        $this->RanGame_Pass = $Pass;
        $this->RanGame_DB = $DB;
    }
    
    public function SetRanShop( $IP , $User , $Pass , $DB )
    {
        $this->RanShop_IP = $IP;
        $this->RanShop_User = $User;
        $this->RanShop_Pass = $Pass;
        $this->RanShop_DB = $DB;
    }
    
    public function SetRanUser( $IP , $User , $Pass , $DB )
    {
        $this->RanUser_IP = $IP;
        $this->RanUser_User = $User;
        $this->RanUser_Pass = $Pass;
        $this->RanUser_DB = $DB;
    }
};

class CNeoWeb
{
	protected $Sys_Class = 0;
	protected $Sys_School = 0;
	protected $Sys_CharMad = 0;
	protected $Sys_CharReborn = 0;
	protected $Sys_Class_P = 0;
        protected $Class_Change_CheckStat = 0;
        protected $Class_Change_CheckSkill = 0;
	protected $Sys_School_P = 0;
	protected $Sys_CharMad_P = 0;
	protected $Sys_CharReborn_P = 0;
	
	protected $Sys_CharRebornFree = 0;
	protected $Sys_CharRebornMax = 0;
	protected $Sys_CharRebornFreeOn = 0;
	protected $Sys_CharRebornFreeCheck = 0;
	protected $Sys_CharRebornLevStart = 0;
	protected $Sys_CharRebornLevCheck = 0;
	
	protected $Sys_SkillDown = 0;
	
	public $Sys_StatOn = 0;
	public $Sys_StatPoint = 0;
	public $Sys_ResetSkill = 0;
	public $Sys_ResetSkillPoint = 0;
	
	public $Sys_MapWarp = 0;
	
	public $Sys_ParentID = 0;
        public $Sys_BonusPoint = 0;
        public $Sys_BonusPointEx = 0;
	
	public $Sys_ChangeName = 0;
	public $Sys_ChangeName_Point = 0;
        
        public $Sys_ChaRebornGetPoint_Lv = 0;
        public $Sys_ChaRebornGetPoint_Value = 0;
	
	protected $Sys_GameTime = 0;
	protected $Sys_OnlineGetPoint = 0;
	protected $Sys_OnlineTime = 0;
	
	protected $Cha_StatsPoint = 0;
	protected $Cha_SkillPoint = 0;
	protected $Cha_StatsPointBegin = 0;
	protected $Cha_SkillPointBegin = 0;
	
	protected $Login_GetPoint_Fist = 0;
        
        protected $Sys_ChaDelete = 0;


        //ServerType
	//0 default Episode 3
	//1 default Episode 6 - 7 (S1)
	protected $ServerType = 0;
	
	public function GetSys_Class(){ return $this->Sys_Class; }
	public function GetSys_Class_P(){ return $this->Sys_Class_P; }
        public function GetSys_Class_Change_CheckStat(){ return $this->Class_Change_CheckStat; }
        public function GetSys_Class_Change_CheckSkill(){ return $this->Class_Change_CheckSkill; }
	public function GetSys_School(){ return $this->Sys_School; }
	public function GetSys_School_P(){ return $this->Sys_School_P; }
	public function GetSys_CharMad(){ return $this->Sys_CharMad; }
	public function GetSys_CharMad_P(){ return $this->Sys_CharMad_P; }
	public function GetSys_CharReborn(){ return $this->Sys_CharReborn; }
	public function GetSys_CharReborn_P(){ return $this->Sys_CharReborn_P; }
	public function GetSys_CharRebornFree(){ return $this->Sys_CharRebornFree; }
	public function GetSys_CharRebornMax(){ return $this->Sys_CharRebornMax; }
	public function GetSys_CharRebornFreeOn(){ return $this->Sys_CharRebornFreeOn; }
	public function GetSys_CharRebornFreeCheck(){ return $this->Sys_CharRebornFreeCheck; }
	public function GetSys_CharRebornLevStart(){ return $this->Sys_CharRebornLevStart; }
	public function GetSys_CharRebornLevCheck(){ return $this->Sys_CharRebornLevCheck; }
	public function GetSys_SkillOn(){ return $this->Sys_SkillDown; }
	public function GetSys_GameTime(){ return $this->Sys_GameTime; }
	public function GetSys_OnlineTime(){ return $this->Sys_OnlineTime; }
	public function GetSys_OnlineGetPoint(){ return $this->Sys_OnlineGetPoint; }
	
	public function GetSys_Cha_StatsPoint(){ return $this->Cha_StatsPoint; }
	public function GetSys_Cha_SkillPoint(){ return $this->Cha_SkillPoint; }
	public function GetSys_Cha_StatsPointBegin(){ return $this->Cha_StatsPointBegin; }
	public function GetSys_Cha_SkillPointBegin(){ return $this->Cha_SkillPointBegin; }
	
	public function GetServerType(){ return $this->ServerType; }
	
	public function GetSys_LoginPoint(){ return $this->Login_GetPoint_Fist; }
	
	public function GetSys_ParentID(){ return $this->Sys_ParentID; }
        public function GetSys_ChaDelete() { return $this->Sys_ChaDelete; }
	public function GetSys_BonusPoint(){ return $this->Sys_BonusPoint; }
        public function GetSys_BonusPointEx(){ return $this->Sys_BonusPointEx; }
        
	public static function GetSessionNameOfSysmDB( $memnum ) { return sprintf( "%d%s" , $memnum , __WEBSYSTEMINFO ); }
	
	public function GetSysmFromDB($memnum)
	{
            $___CURRENTSESSION = self::GetSessionNameOfSysmDB( $memnum ) . "__CWebSystem";
            
            $pData = unserialize( phpFastCache::get($___CURRENTSESSION) );
            if ( $pData )
            {
                __CWebSystem::GetInstance()->Asset( $pData );
                __CWebSystem::GetInstance()->Get( $this->Sys_Class , $this->Sys_Class_P , $this->Class_Change_CheckStat , $this->Class_Change_CheckSkill , $this->Sys_School , $this->Sys_School_P , $this->Sys_CharMad , $this->Sys_CharMad_P ,
                     $this->Cha_StatsPoint , $this->Cha_SkillPoint , $this->Cha_StatsPointBegin , $this->Cha_SkillPointBegin , $this->Sys_CharReborn , $this->Sys_CharReborn_P , $this->Sys_CharRebornFree , $this->Sys_CharRebornMax ,
                     $this->Sys_CharRebornFreeOn , $this->Sys_CharRebornFreeCheck , $this->Sys_CharRebornLevStart , $this->Sys_CharRebornLevCheck , $this->Sys_SkillDown , $this->Sys_GameTime , $this->Sys_OnlineTime , $this->Sys_OnlineGetPoint ,
                     $this->Sys_StatOn , $this->Sys_StatPoint , $this->Sys_ResetSkill , $this->Sys_ResetSkillPoint , $this->Sys_ChangeName , $this->Sys_ChangeName_Point , $this->Login_GetPoint_Fist , $this->Sys_ChaRebornGetPoint_Lv , $this->Sys_ChaRebornGetPoint_Value ,
                        $this->ServerType , $this->Sys_MapWarp , $this->Sys_ParentID , $this->Sys_ChaDelete , $this->Sys_BonusPoint , $this->Sys_BonusPointEx );
                //echo "Get FROM Session<br>";
                return ;
            }

            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf(" SELECT TOP 1
                    School,School_P,Class,Class_P,CharMad,CharMad_P,Cha_SkillPointBegin,Cha_StatsPointBegin,Cha_SkillPoint,Cha_StatsPoint,CharReborn,CharReborn_P,CharReborn_Free
            ,CharRebornLevStart,CharRebornLevCheck,Class_Change_CheckStat,Class_Change_CheckSkill
            ,CharReborn_Max,CharRebornFreeOn,CharRebornFreeCheck,Login_PointFirst,SkillOn,Online2Point,OnlineTime,OnlineGetPoint,StatOn,StatPoint,ResetSkill,ResetSkillPoint,ChangeName,ChangeNamePoint
            ,ChaRebornGetPoint_Lv,ChaRebornGetPoint_Value,ParentID,ChaDelete,BonusPoint,BonusPointEx
            FROM MemSys WHERE MemNum = %d ",$memnum);
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            //echo $szTemp;
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                    $this->Sys_Class = $cNeoSQLConnectODBC->Result( "Class" , ODBC_RETYPE_INT );
                    $this->Sys_Class_P = $cNeoSQLConnectODBC->Result( "Class_P" , ODBC_RETYPE_INT );
                    $this->Class_Change_CheckStat = $cNeoSQLConnectODBC->Result( "Class_Change_CheckStat" , ODBC_RETYPE_INT );
                    $this->Class_Change_CheckSkill = $cNeoSQLConnectODBC->Result( "Class_Change_CheckSkill" , ODBC_RETYPE_INT );
                    $this->Sys_School = $cNeoSQLConnectODBC->Result( "School" , ODBC_RETYPE_INT );
                    $this->Sys_School_P = $cNeoSQLConnectODBC->Result( "School_P" , ODBC_RETYPE_INT );
                    $this->Sys_CharMad = $cNeoSQLConnectODBC->Result( "CharMad" , ODBC_RETYPE_INT );
                    $this->Sys_CharMad_P = $cNeoSQLConnectODBC->Result( "CharMad_P" , ODBC_RETYPE_INT );

                    $this->Cha_StatsPoint = $cNeoSQLConnectODBC->Result( "Cha_StatsPoint" , ODBC_RETYPE_INT );
                    $this->Cha_SkillPoint = $cNeoSQLConnectODBC->Result( "Cha_SkillPoint" , ODBC_RETYPE_INT );
                    $this->Cha_StatsPointBegin = $cNeoSQLConnectODBC->Result( "Cha_StatsPointBegin" , ODBC_RETYPE_INT );
                    $this->Cha_SkillPointBegin = $cNeoSQLConnectODBC->Result( "Cha_SkillPointBegin" , ODBC_RETYPE_INT );
                    $this->Sys_CharReborn = $cNeoSQLConnectODBC->Result( "CharReborn" , ODBC_RETYPE_INT );
                    $this->Sys_CharReborn_P = $cNeoSQLConnectODBC->Result( "CharReborn_P" , ODBC_RETYPE_INT );
                    $this->Sys_CharRebornFree = $cNeoSQLConnectODBC->Result( "CharReborn_Free" , ODBC_RETYPE_INT );
                    $this->Sys_CharRebornMax = $cNeoSQLConnectODBC->Result( "CharReborn_Max" , ODBC_RETYPE_INT );
                    $this->Sys_CharRebornFreeOn = $cNeoSQLConnectODBC->Result( "CharRebornFreeOn" , ODBC_RETYPE_INT );
                    $this->Sys_CharRebornFreeCheck = $cNeoSQLConnectODBC->Result( "CharRebornFreeCheck" , ODBC_RETYPE_INT );
                    $this->Sys_CharRebornLevStart = $cNeoSQLConnectODBC->Result( "CharRebornLevStart" , ODBC_RETYPE_INT );
                    $this->Sys_CharRebornLevCheck = $cNeoSQLConnectODBC->Result( "CharRebornLevCheck" , ODBC_RETYPE_INT );
                    $this->Sys_SkillDown =  $cNeoSQLConnectODBC->Result( "SkillOn" , ODBC_RETYPE_INT );

                    $this->Sys_GameTime =  $cNeoSQLConnectODBC->Result( "Online2Point" , ODBC_RETYPE_INT );
                    $this->Sys_OnlineTime =  $cNeoSQLConnectODBC->Result( "OnlineGetPoint" , ODBC_RETYPE_INT );
                    $this->Sys_OnlineGetPoint =  $cNeoSQLConnectODBC->Result( "OnlineTime" , ODBC_RETYPE_INT );

                    $this->Sys_StatOn =  $cNeoSQLConnectODBC->Result( "StatOn" , ODBC_RETYPE_INT );
                    $this->Sys_StatPoint =  $cNeoSQLConnectODBC->Result( "StatPoint" , ODBC_RETYPE_INT );
                    $this->Sys_ResetSkill =  $cNeoSQLConnectODBC->Result( "ResetSkill" , ODBC_RETYPE_INT );
                    $this->Sys_ResetSkillPoint =  $cNeoSQLConnectODBC->Result( "ResetSkillPoint" , ODBC_RETYPE_INT );

                    $this->Sys_ChangeName =  $cNeoSQLConnectODBC->Result( "ChangeName" , ODBC_RETYPE_INT );
                    $this->Sys_ChangeName_Point =  $cNeoSQLConnectODBC->Result( "ChangeNamePoint" , ODBC_RETYPE_INT );

                    $this->Login_GetPoint_Fist = $cNeoSQLConnectODBC->Result( "Login_PointFirst" , ODBC_RETYPE_INT );

                    $this->Sys_ChaRebornGetPoint_Lv = $cNeoSQLConnectODBC->Result( "ChaRebornGetPoint_Lv" , ODBC_RETYPE_INT );
                    $this->Sys_ChaRebornGetPoint_Value = $cNeoSQLConnectODBC->Result( "ChaRebornGetPoint_Value" , ODBC_RETYPE_INT );
					
                    $this->Sys_ParentID = $cNeoSQLConnectODBC->Result( "ParentID" , ODBC_RETYPE_INT );
                    $this->Sys_ChaDelete = $cNeoSQLConnectODBC->Result( "ChaDelete" , ODBC_RETYPE_INT );
                    $this->Sys_BonusPoint = $cNeoSQLConnectODBC->Result( "BonusPoint" , ODBC_RETYPE_INT );
                    $this->Sys_BonusPointEx = $cNeoSQLConnectODBC->Result( "BonusPointEx" , ODBC_RETYPE_INT );
            }
            $szTemp = sprintf(" SELECT TOP 1
            MapSetNum,MapOpen
            FROM MemMapSet WHERE MemNum = %d ",$memnum);
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                    $this->Sys_MapWarp = $cNeoSQLConnectODBC->Result( "MapOpen" , ODBC_RETYPE_INT );
            }
            $szTemp = sprintf( "SELECT ServerType FROM MemberInfo WHERE MemberNum = %d",$memnum );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                    $this->ServerType = $cNeoSQLConnectODBC->Result( "ServerType" , ODBC_RETYPE_INT );
            }
            $cNeoSQLConnectODBC->CloseRanWeb();
            
            //echo "Get FROM DB<br>";
            __CWebSystem::GetInstance()->Set( $this->Sys_Class , $this->Sys_Class_P , $this->Class_Change_CheckStat , $this->Class_Change_CheckSkill , $this->Sys_School , $this->Sys_School_P , $this->Sys_CharMad , $this->Sys_CharMad_P ,
                                                $this->Cha_StatsPoint , $this->Cha_SkillPoint , $this->Cha_StatsPointBegin , $this->Cha_SkillPointBegin , $this->Sys_CharReborn , $this->Sys_CharReborn_P , $this->Sys_CharRebornFree , $this->Sys_CharRebornMax ,
                                                $this->Sys_CharRebornFreeOn , $this->Sys_CharRebornFreeCheck , $this->Sys_CharRebornLevStart , $this->Sys_CharRebornLevCheck , $this->Sys_SkillDown , $this->Sys_GameTime , $this->Sys_OnlineTime , $this->Sys_OnlineGetPoint ,
                                                $this->Sys_StatOn , $this->Sys_StatPoint , $this->Sys_ResetSkill , $this->Sys_ResetSkillPoint , $this->Sys_ChangeName , $this->Sys_ChangeName_Point , $this->Login_GetPoint_Fist , $this->Sys_ChaRebornGetPoint_Lv , $this->Sys_ChaRebornGetPoint_Value ,
                                                $this->ServerType , $this->Sys_MapWarp , $this->Sys_ParentID , $this->Sys_ChaDelete , $this->Sys_BonusPoint , $this->Sys_BonusPointEx );
            phpFastCache::set($___CURRENTSESSION, serialize( __CWebSystem::GetInstance()->GetData() ), 3600/*1ชั่วโมง*/);
	}
	
	static public function CheckMemNumGood( $MemNum )
	{
		$bGood = false;
		$szTemp = sprintf( "SELECT TOP 1 MemberNum FROM MemberInfo WHERE MemberNum = %d",$MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb(  );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$bGood = true;
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $bGood;
	}
	
	static public function GetServerNameFromMemNum( $MemNum )
	{
		$ServerName = "";
		$szTemp = sprintf( "SELECT TOP 1 ServerName FROM MemberInfo WHERE MemberNum = %d",$MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb(  );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$ServerName = $cNeoSQLConnectODBC->Result( "ServerName",ODBC_RETYPE_THAI );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $ServerName;
	}
	
	static public function GetEncryptPassword2MD5( $MemNum )
	{
		$PassMD5 = 0;
		$szTemp = sprintf( "SELECT TOP 1 PassMD5 FROM MemberInfo WHERE MemberNum = %d",$MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb(  );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$PassMD5 = $cNeoSQLConnectODBC->Result( "PassMD5",ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $PassMD5;
	}
	
	protected $RanGame_IP = "";
	protected $RanGame_User= "";
	protected $RanGame_Pass= "";
	protected $RanGame_DB = "";
	
	public function GetRanGame_IP(){ return $this->RanGame_IP; }
	public function GetRanGame_User(){ return $this->RanGame_User; }
	public function GetRanGame_Pass(){ return $this->RanGame_Pass; }
	public function GetRanGame_DB(){ return $this->RanGame_DB; }
	
	protected $RanUser_IP = "";
	protected $RanUser_User = "";
	protected $RanUser_Pass = "";
	protected $RanUser_DB = "";
	
	public function GetRanUser_IP(){ return $this->RanUser_IP; }
	public function GetRanUser_User(){ return $this->RanUser_User; }
	public function GetRanUser_Pass(){ return $this->RanUser_Pass; }
	public function GetRanUser_DB(){ return $this->RanUser_DB; }
	
	protected $RanShop_IP = "";
	protected $RanShop_User = "";
	protected $RanShop_Pass = "";
	protected $RanShop_DB = "";
	
	public function GetRanShop_IP(){ return $this->RanShop_IP; }
	public function GetRanShop_User(){ return $this->RanShop_User; }
	public function GetRanShop_Pass(){ return $this->RanShop_Pass; }
	public function GetRanShop_DB(){ return $this->RanShop_DB; }
	
	public function Clear()
	{
		$this->RanGame_IP = "";
		$this->RanGame_User = "";
		$this->RanGame_Pass = "";
		$this->RanGame_DB = "";
		
		$this->RanUser_IP = "";
		$this->RanUser_User = "";
		$this->RanUser_Pass = "";
		$this->RanUser_DB = "";
		
		$this->RanShop_IP = "";
		$this->RanShop_User = "";
		$this->RanShop_Pass = "";
		$this->RanShop_DB = "";
	}
	
	public static function GetSessionNameOfWebDB( $MemNum ) { return sprintf("%d%s",$MemNum,__WEBDBDATA); }
	
	public function GetDBInfoFromWebDB( $MemNum , $bWithCache = true )
	{
            if ( $bWithCache )
            {
                if ( __CWebDB::GetInstance()->IsRanGame() && __CWebDB::GetInstance()->IsRanShop() && __CWebDB::GetInstance()->IsRanUser() )
                {
                    __CWebDB::GetInstance()->GetRanGame( $this->RanGame_IP , $this->RanGame_User , $this->RanGame_Pass , $this->RanGame_DB );
                    __CWebDB::GetInstance()->GetRanShop( $this->RanShop_IP , $this->RanShop_User , $this->RanShop_Pass , $this->RanShop_DB );
                    __CWebDB::GetInstance()->GetRanUser( $this->RanUser_IP , $this->RanUser_User , $this->RanUser_Pass , $this->RanUser_DB );
                    //echo "Get FROM Instance<br>";
                    return ;
                }

                $__CURRENTSESSION = self::GetSessionNameOfWebDB( $MemNum ) . "__CWebDB";
                $pData = unserialize( phpFastCache::get($__CURRENTSESSION) );
                if ( $pData )
                {
                    __CWebDB::GetInstance()->Asset( $pData );
                    __CWebDB::GetInstance()->GetRanGame( $this->RanGame_IP , $this->RanGame_User , $this->RanGame_Pass , $this->RanGame_DB );
                    __CWebDB::GetInstance()->GetRanShop( $this->RanShop_IP , $this->RanShop_User , $this->RanShop_Pass , $this->RanShop_DB );
                    __CWebDB::GetInstance()->GetRanUser( $this->RanUser_IP , $this->RanUser_User , $this->RanUser_Pass , $this->RanUser_DB );
                    //echo "Get FROM Session<br>";
                    return ;
                }
            }
            
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( " SELECT 
                                              RanGame_IP,RanGame_User,RanGame_Pass,RanGame_DB
                                              ,RanUser_IP,RanUser_User,RanUser_Pass,RanUser_DB
                                              ,RanShop_IP,RanShop_User,RanShop_Pass,RanShop_DB
                                              FROM MemSQL WHERE MemNum = %d ", $MemNum );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                    self::Clear();
                    $this->RanGame_IP = $cNeoSQLConnectODBC->Result( "RanGame_IP" , ODBC_RETYPE_THAI );
                    $this->RanGame_User = $cNeoSQLConnectODBC->Result( "RanGame_User" , ODBC_RETYPE_THAI );
                    $this->RanGame_Pass = $cNeoSQLConnectODBC->Result( "RanGame_Pass" , ODBC_RETYPE_THAI );
                    $this->RanGame_DB = $cNeoSQLConnectODBC->Result( "RanGame_DB" , ODBC_RETYPE_THAI );

                    $this->RanUser_IP = $cNeoSQLConnectODBC->Result( "RanUser_IP" , ODBC_RETYPE_THAI );
                    $this->RanUser_User = $cNeoSQLConnectODBC->Result( "RanUser_User" , ODBC_RETYPE_THAI );
                    $this->RanUser_Pass = $cNeoSQLConnectODBC->Result( "RanUser_Pass" , ODBC_RETYPE_THAI );
                    $this->RanUser_DB = $cNeoSQLConnectODBC->Result( "RanUser_DB" , ODBC_RETYPE_THAI );

                    $this->RanShop_IP = $cNeoSQLConnectODBC->Result( "RanShop_IP" , ODBC_RETYPE_THAI );
                    $this->RanShop_User = $cNeoSQLConnectODBC->Result( "RanShop_User" , ODBC_RETYPE_THAI );
                    $this->RanShop_Pass = $cNeoSQLConnectODBC->Result( "RanShop_Pass" , ODBC_RETYPE_THAI );
                    $this->RanShop_DB = $cNeoSQLConnectODBC->Result( "RanShop_DB" , ODBC_RETYPE_THAI );
            }
            $cNeoSQLConnectODBC->CloseRanWeb();
            
            //echo "Get FROM DB";
            if ( $bWithCache )
            {
                __CWebDB::GetInstance()->SetRanGame( $this->RanGame_IP , $this->RanGame_User , $this->RanGame_Pass , $this->RanGame_DB );
                __CWebDB::GetInstance()->SetRanShop( $this->RanShop_IP , $this->RanShop_User , $this->RanShop_Pass , $this->RanShop_DB );
                __CWebDB::GetInstance()->SetRanUser( $this->RanUser_IP , $this->RanUser_User , $this->RanUser_Pass , $this->RanUser_DB );

                phpFastCache::set($__CURRENTSESSION, serialize( __CWebDB::GetInstance()->GetData() ), 3600);
            }
	}
}
?>
