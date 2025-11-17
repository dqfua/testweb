<?php

$CURRENT_SESSION_LISTDATA = "ADM_SUB_ITEM_DATA";

class __SubData
{
    public $SubNum;
    public $SubName;
    public $SubType;
    
    public function __construct( $subnum , $subname , $subtype )
    {
        $this->SubName = $subname;
        $this->SubNum = $subnum;
        $this->SubType = $subtype;
    }
};

class SubMain
{
    private $pData = array();
    private static $Instance;
    
    public static function GetInstance()
    {
        if ( !self::$Instance )
            self::$Instance = new self();
        return self::$Instance;
    }
    
    public function __construct() {
        ;
    }
    
    public function GetData() { return $this->pData; }
    public function SetData( $data ) { $this->pData = $data; } 
    
    public function UpdateFromDB()
    {
        global $cAdmin;
        $MemNum = $cAdmin->GetMemNum();
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp = sprintf( "SELECT SubNum,SubName,SubShow FROM SubProject WHERE MemNum = %d AND SubDel = 0
                            ORDER BY CAST( SubRollRank as MONEY ) ASC
                            " , $MemNum );
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        
        $i = 0;
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
            $SubName = $cNeoSQLConnectODBC->Result("SubName",ODBC_RETYPE_THAI);
            $SubType = $cNeoSQLConnectODBC->Result("SubShow",ODBC_RETYPE_INT);
            
            $SubName = CBinaryCover::tis620_to_utf8( $SubName );
            
            $this->pData[ $i ] = new __SubData( $SubNum , $SubName , $SubType );
            $i++;
        }
        
        $cNeoSQLConnectODBC->CloseRanWeb();
    }
    
};

$pSubMain = unserialize ( CInput::GetInstance()->GetValue( $CURRENT_SESSION_LISTDATA , IN_SESSION ) );
if ( !$pSubMain )
{
    SubMain::GetInstance()->UpdateFromDB();
    $pSubMain = SubMain::GetInstance()->GetData();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION_LISTDATA , serialize( $pSubMain ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
}else{
    SubMain::GetInstance()->SetData( $pSubMain );
}

?>
