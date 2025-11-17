<?php

class ChaInfo
{
    //struct data
    public $ChaNum;
    public $UserNum;
    public $ChaName;
    public $ChaGuName;
    public $GuNum;
    
    public $ChaClass;
    public $ChaSchool;
    public $ChaHair;
    public $ChaFace;
    public $ChaLevel;
    public $ChaExp;
    public $ChaMoney;
    public $ChaPower;
    public $ChaStrong;
    public $ChaStrength;
    public $ChaSpirit;
    public $ChaDex;
    public $ChaStRemain;
    public $ChaStartMap;
    public $ChaSaveMap;
    public $ChaReturnMap;
    
    public $ChaBright;
    public $ChaPK;
    public $ChaSkillPoint;
    public $ChaInvenLine;
    public $ChaDeleted;
    public $ChaReborn;
    
    public $ChaInven;
    public $ChaPutOnItems;
    public $ChaSkills;
    
    //pointer class
    public $NeoChaInven = NULL;
    public $NeoChaPutOnItems = NULL;
    public $NeoChaSkill = NULL;
    
    public function __construct() {
        ;
    }
    
    public function BuildNeoChaPutOnItems( $MemNum )
    {
        $this->NeoChaPutOnItems = new CNeoChaInven;
        $this->NeoChaPutOnItems->LoadChaPutOnItems( $this->ChaPutOnItems );
        $this->NeoChaPutOnItems->SetMemNum( $MemNum );
        $this->NeoChaPutOnItems->SetChaNum( $this->ChaNum );
    }
    
    public function BuildNeoChaSkill()
    {
        $this->NeoChaSkill = new CNeoChaSkill;
        $this->NeoChaSkill->Binary = $this->ChaSkills;
        $this->NeoChaSkill->LoadData();
    }
    
    public function BuildNeoChaInven( $MemNum )
    {
        //echo $this->ChaInven;
        $this->NeoChaInven = new CNeoChaInven;
        $this->NeoChaInven->LoadChaInven( $this->ChaInven );
        $this->NeoChaInven->SetMemNum( $MemNum );
        $this->NeoChaInven->SetChaNum( $this->ChaNum );
    }
    
    public function Load( &$cNeoSQLConnectODBC , $szTemp , &$pChaList )
    {
        $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $this->ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
            $this->UserNum = $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT );
            $this->ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
            $this->ChaLevel = $cNeoSQLConnectODBC->Result( "ChaLevel" , ODBC_RETYPE_INT );
            $this->ChaClass = $cNeoSQLConnectODBC->Result( "ChaClass" , ODBC_RETYPE_INT );
            $this->ChaSchool = $cNeoSQLConnectODBC->Result( "ChaSchool" , ODBC_RETYPE_INT );
            
            /*
            $this->ChaInven = $cNeoSQLConnectODBC->Result( "ChaInven" , ODBC_RETYPE_BINARY );
            
            $this->NeoChaInven = new CNeoChaInven;
            $this->NeoChaInven->LoadChaInven($this->ChaInven);
            */
            
            //$pChaList->AddData( $this );
        }
    }
};

?>
