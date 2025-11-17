<?php
/*
==========================================
			Class ODBC MS SQL
		Development By NeoMasteI2
		Copyright (c) 2010 NeoMasteI2
		Createdate year 2010
		Modifydate year 2011
==========================================
*/
define( "ODBC_RETYPE_INT" , 0 );
define( "ODBC_RETYPE_ENG" , 1 );
define( "ODBC_RETYPE_THAI" , 2 );
define( "ODBC_RETYPE_BINARY" , 3 );

define( "ODBC_RANGAME_CONNECT" , 0 );
define( "ODBC_RANSHOP_CONNECT" , 1 );
define( "ODBC_RANUSER_CONNECT" , 2 );
define( "ODBC_RANWEB_CONNECT" , 3 );
define( "ODBC_RANLOG_CONNECT" , 4 );

class __CNeoSQLConnectODBC
{
    private static $Instance;
    
    protected $Connection = array();
    
    public function __construct(  ) {
        ;
    }
    
    public function __destruct() {
        //var_dump( $this->Connection );
    }
    
    public static function GetInstance()
    {
        if ( !self::$Instance )
        {
            self::$Instance = new self();
        }
        return self::$Instance;
    }
    
    public function AddConnect( $Connection , $ConnectType )
    {
        $this->Connection[ $ConnectType ] = $Connection;
        //echo 'a|';
        //var_dump( $Connection );
    }
    
    public function GetConnect( $ConnectType , &$pConnection )
    {
        $pConnection = NULL;
        if ( arrkeycheck( $this->Connection , $ConnectType) )
        {
            $pConnection = $this->Connection[ $ConnectType ];
            //echo 'g|';
            //var_dump( $pConnection );
            return true;
        }
        return false;
    }
};

class CNeoSQLConnectODBC
{
	protected $Conn_RanGame = NULL;
	protected $Conn_RanUser = NULL;
	protected $Conn_RanShop = NULL;
	protected $Conn_RanLog = NULL;
	protected $Conn_RanWeb = NULL;
	
	protected $Connect = false;
	protected $Query = false;
        
        public function __construct(  )
        {
        }
        
	function __destruct()
	{		
            self::CloseRanUser(true);
            self::CloseRanGame(true);
            self::CloseRanLog(true);
            self::CloseRanShop(true);
            self::CloseRanWeb(true);
	}
	
	protected function ConnectSQL($dbip,$dbuser,$dbpass,$db){
                $this->Connect = odbc_connect("DRIVER={SQL Server};SERVER=$dbip;DATABASE=$db;UID=$dbuser;PWD=$dbpass;",$dbuser,$dbpass);
		return $this->Connect;
	}
	protected function CloseConnectSQL($connect,$destroy=false)
	{
                if ( !$destroy ) return ;
                
		if ( !$connect ) return ;
		self::QueryClear( $this->Query );
		odbc_close($connect);
	}
	protected function QueryClear( $result )
	{
		if ( $result )
		{
			//odbc_free_result($result);
		}
	}
	protected function SQLQuery($query,$connect)
	{
		self::QueryClear( $this->Query );
		if ( !$connect ) die("ERROR::DATABASE");
		$this->Query = odbc_exec($connect,$query);
		return $this->Query;
	}
	protected function SQL_fetch_row( $id ){return odbc_fetch_row( $id );}
	protected function SQL_result( $id,$result ){return odbc_result($id,$result);	}
	public function GetConnect() { return $this->Connect; }
	public function GetQuery( ){return $this->Query;}
	public function SetQuery( $id ) { $this->Query = $id; }
	public function FetchRow( ){return self::SQL_fetch_row( $this->Query );}
	public function FetchRowMan( $id ){return self::SQL_fetch_row( $id );}
	public function Result( $result , $nType = ODBC_RETYPE_ENG )
	{
		$pReturn = self::SQL_result( $this->Query , $result );
		switch( $nType )
		{
			case ODBC_RETYPE_INT:{
				$bIntDown = FALSE;
                                $dotpos = strpos( $pReturn , ".");
                                if ( $dotpos ) $pReturn = substr ( $pReturn , 0 , $dotpos );
				if ( $pReturn < 0 ) $bIntDown = TRUE;
                                CInput::GetInstance()->BuildVar( $pReturn );
				$pReturn = @CNeoInject::sec_Int( $pReturn );
				if ( $bIntDown ) $pReturn = -$pReturn;
			}break;
			case ODBC_RETYPE_ENG:{
                                CInput::GetInstance()->BuildVar( $pReturn );
				$pReturn = @CNeoInject2::sec_Eng( $pReturn );
			}break;
			case ODBC_RETYPE_THAI:{
                                CInput::GetInstance()->BuildVar( $pReturn );
				$pReturn = @CNeoInject2::sec_Thai( $pReturn );
			}break;
			case ODBC_RETYPE_BINARY:{
                                //CInput::GetInstance()->BuildVar( $pReturn );
				$pReturn = @CNeoInject2::sec_Eng( strtoupper( bin2hex( $pReturn ) ) );
                                //$pReturn = bin2hex($pReturn);
			}break;
			default:{
				$pReturn = NULL;
			}break;
		}
		return $pReturn;
	}
	public function QueryRanGame($query){
		return self::SQLQuery($query,$this->Conn_RanGame);
	}
	public function QueryRanShop($query){
		return self::SQLQuery($query,$this->Conn_RanShop);
	}
	public function QueryRanLog($query){
		return self::SQLQuery($query,$this->Conn_RanLog);
	}
	public function QueryRanUser($query){
		return self::SQLQuery($query,$this->Conn_RanUser);
	}
	public function QueryRanWeb($query){
		return self::SQLQuery($query,$this->Conn_RanWeb);
	}
	public function CloseRanGame($destroy=false){
		return self::CloseConnectSQL($this->Conn_RanGame,$destroy);
	}
	public function CloseRanShop($destroy=false){
		return self::CloseConnectSQL($this->Conn_RanShop,$destroy);
	}
	public function CloseRanLog($destroy=false){
		return self::CloseConnectSQL($this->Conn_RanLog,$destroy);
	}
	public function CloseRanUser($destroy=false){
		return self::CloseConnectSQL($this->Conn_RanUser,$destroy);
	}
	public function CloseRanWeb($destroy=false){
		return self::CloseConnectSQL($this->Conn_RanWeb,$destroy);
	}
	public function ConnectRanUser($host , $user , $pass , $db){
                $connect = null;
                if ( __CNeoSQLConnectODBC::GetInstance()->GetConnect( ODBC_RANUSER_CONNECT , $connect ) )
                {
                    $this->Conn_RanUser = $connect;
                    return true;
                }
                if ( defined("RANUSER_IP") && defined("RANUSER_USER") && defined("RANUSER_PASS") && defined("RANUSER_DATABASE") )
                {
                    $connect = self::ConnectSQL(RANUSER_IP , RANUSER_USER , RANUSER_PASS , RANUSER_DATABASE);
                }else{
                    $connect = self::ConnectSQL($host , $user , $pass , $db);
                }
		$this->Conn_RanUser = $connect;
                __CNeoSQLConnectODBC::GetInstance()->AddConnect( $connect , ODBC_RANUSER_CONNECT );
		if ( !$connect ) return false; else return true;
	}
	public function ConnectRanLog( $host , $user , $pass , $db ){
		$connect = null;
                if ( __CNeoSQLConnectODBC::GetInstance()->GetConnect( ODBC_RANLOG_CONNECT , $connect ) )
                {
                    $this->Conn_RanLog = $connect;
                    return true;
                }
                if ( defined("RANLOG_IP") && defined("RANLOG_USER") && defined("RANLOG_PASS") && defined("RANLOG_DATABASE") )
                {
                    $connect = self::ConnectSQL(RANLOG_IP , RANLOG_USER , RANLOG_PASS , RANLOG_DATABASE);
                }else{
                    $connect = self::ConnectSQL($host , $user , $pass , $db);
                }
		$this->Conn_RanLog = $connect;
                __CNeoSQLConnectODBC::GetInstance()->AddConnect( $connect , ODBC_RANLOG_CONNECT );
		if ( !$connect ) return false; else return true;
	}
	public function ConnectRanShop($host , $user , $pass , $db){
		$connect = null;
                if ( __CNeoSQLConnectODBC::GetInstance()->GetConnect( ODBC_RANSHOP_CONNECT , $connect ) )
                {
                    $this->Conn_RanShop = $connect;
                    return true;
                }
                if ( defined("RANSHOP_IP") && defined("RANSHOP_USER") && defined("RANSHOP_PASS") && defined("RANSHOP_DATABASE") )
                {
                    $connect = self::ConnectSQL(RANSHOP_IP , RANSHOP_USER , RANSHOP_PASS , RANSHOP_DATABASE);
                }else{
                    $connect = self::ConnectSQL($host , $user , $pass , $db);
                }
		$this->Conn_RanShop = $connect;
                __CNeoSQLConnectODBC::GetInstance()->AddConnect( $connect , ODBC_RANSHOP_CONNECT );
		if ( !$connect ) return false; else return true;
	}
	public function ConnectRanGame( $host , $user , $pass , $db ){
		$connect = null;
                if ( __CNeoSQLConnectODBC::GetInstance()->GetConnect( ODBC_RANGAME_CONNECT , $connect ) )
                {
                    $this->Conn_RanGame = $connect;
                    return true;
                }
                if ( defined("RANGAME_IP") && defined("RANGAME_USER") && defined("RANGAME_PASS") && defined("RANGAME_DATABASE") )
                {
                    $connect = self::ConnectSQL(RANGAME_IP , RANGAME_USER , RANGAME_PASS , RANGAME_DATABASE);
                }else{
                    $connect = self::ConnectSQL($host , $user , $pass , $db);
                }
		$this->Conn_RanGame = $connect;
                __CNeoSQLConnectODBC::GetInstance()->AddConnect( $connect , ODBC_RANGAME_CONNECT );
		if ( !$connect ) return false; else return true;
	}
	public function ConnectRanTest( $host , $user , $pass , $db ){
		$connect = self::ConnectSQL($host , $user , $pass , $db);
		if ( $connect ){ self::CloseConnectSQL($connect); }
		if ( !$connect ) return false; else return true;
	}
	public function ConnectRanWeb(){
                $connect = null;
                if ( __CNeoSQLConnectODBC::GetInstance()->GetConnect( ODBC_RANWEB_CONNECT , $connect ) )
                {
                    $this->Conn_RanWeb = $connect;
                    return $connect;
                }
		$connect = self::ConnectSQL(DB_SHOPCENTER_HOST,DB_SHOPCENTER_USER,DB_SHOPCENTER_PASS,DB_SHOPCENTER_DATABASE);
		$this->Conn_RanWeb = $connect;
                __CNeoSQLConnectODBC::GetInstance()->AddConnect( $connect , ODBC_RANWEB_CONNECT );
		//if ( !$connect ) return false; else return true;
                return $connect;
	}
}
?>