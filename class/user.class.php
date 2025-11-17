<?php
define("ONLINE",1);
define("OFFLINE",0);
class CNeoUser
{
	static public function GetUserIDFromUserNum( $MemNum , $UserNum )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserID FROM UserInfo WHERE UserNum = %d" , $UserNum );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$UserID = "";
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$UserID = $cNeoSQLConnectODBC->Result( "UserID",ODBC_RETYPE_ENG );
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		return $UserID;
	}
	
	protected $UserNum = 0;
	protected $UserID = "";
	protected $Password = "";
	protected $UserType = 0;
	protected $CreateDate = "";
	public $UserPoint = 0;
	public $GameTime = 0;
        public $BonusPoint = 0;
	public $SecCodeLogin = 0;
	public $SessionNameID = "";
	public $LogIP = "";
	
	public function SetUserNum( $UserNum ) { $this->UserNum = $UserNum; }
	public function GetUserNum() { return $this->UserNum; }
	public function GetUserID(){ return $this->UserID; }
	public function UpPoint( $ItemPoint )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserPoint = UserPoint + %d WHERE UserNum = %d " , $ItemPoint ,$this->UserNum );
		$pReturn = $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$cNeoSQLConnectODBC->CloseRanUser();
		CNeoLog::LogUserPoint( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $this->UserNum , $ItemPoint , 0 );
		return $pReturn;
	}
	public function DownPoint( $ItemPoint )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserPoint = UserPoint - %d WHERE UserNum = %d " , $ItemPoint ,$this->UserNum );
		$pReturn = $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$cNeoSQLConnectODBC->CloseRanUser();
		CNeoLog::LogUserPoint( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $this->UserNum , $ItemPoint , 0 );
		return $pReturn;
	}
        public function UpBonusPoint( $BonusPoint )
	{
		global $_CONFIG;
                
                $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;

                $BeforeBonusPoint = self::GetBonusPoint();
		
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "UPDATE UserInfo SET BonusPoint = BonusPoint + %d WHERE MemNum = %d AND UserID = '%s' " , $BonusPoint , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] ,$this->UserID );
		$pReturn = $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		
		CNeoLog::LogUserBonusPoint( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $this->UserNum , $BeforeBonusPoint , self::GetBonusPoint() , $BonusPoint , '' );
                
                $cNeoSQLConnectODBC->CloseRanWeb();
		return $pReturn;
	}
        public function DownBonusPoint( $BonusPoint )
	{
		global $_CONFIG;
                
                $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;

                $BeforeBonusPoint = self::GetBonusPoint();
		
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "UPDATE UserInfo SET BonusPoint = BonusPoint - %d WHERE MemNum = %d AND UserID = '%s' " , $BonusPoint , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] ,$this->UserID );
		$pReturn = $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		
		CNeoLog::LogUserBonusPoint( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $this->UserNum , $BeforeBonusPoint , self::GetBonusPoint() , $BonusPoint . '' );
                
                $cNeoSQLConnectODBC->CloseRanWeb();
		return $pReturn;
	}
	public function DownGameTime( $GameTime )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserGameOnlineTime = UserGameOnlineTime - %d WHERE UserNum = %d " , $GameTime ,$this->UserNum );
		$pReturn = $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$cNeoSQLConnectODBC->CloseRanUser();
		CNeoLog::LogUserPoint( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $this->UserNum , $GameTime , 0 );
		return $pReturn;
	}
	public function GetUserPass( $cAdmin = NULL )
	{
		global $_CONFIG;
                $MemNum = $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"];
                if ( $cAdmin ) $MemNum = $cAdmin->GetMemNum();
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserPass FROM UserInfo WHERE UserNum = %d",$this->UserNum );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$UserPass = $cNeoSQLConnectODBC->Result( "UserPass",ODBC_RETYPE_ENG );
		$cNeoSQLConnectODBC->CloseRanUser();
		return $UserPass;
	}
	public function GetUserPass2( $cAdmin = NULL )
	{
		global $_CONFIG;
                $MemNum = $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"];
                if ( $cAdmin ) $MemNum = $cAdmin->GetMemNum();
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserPass2 FROM UserInfo WHERE UserNum = %d",$this->UserNum );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$UserPass = $cNeoSQLConnectODBC->Result( "UserPass2",ODBC_RETYPE_ENG );
		$cNeoSQLConnectODBC->CloseRanUser();
		return $UserPass;
	}
	public function GetUserType(){ return $this->UserType; }
	//public function GetUserPoint(){ self::UpdateLastUserPoint(); return $this->UserPoint; }
	public function GetUserPoint(){ return self::UpdateLastUserPoint(); }
	public function GetCreateDate(){ return $this->CreateDate; }
	public function GetGameTime()
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserGameOnlineTime FROM UserInfo WHERE UserNum = %d",$this->UserNum );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$UserGameOnlineTime = $cNeoSQLConnectODBC->Result( "UserGameOnlineTime",ODBC_RETYPE_INT );
		$this->GameTime = $UserGameOnlineTime;
		$cNeoSQLConnectODBC->CloseRanUser();
		return $UserGameOnlineTime;
	}
        
        public function GetBonusPoint()
	{
		global $_CONFIG;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT BonusPoint FROM UserInfo WHERE MemNum = %d AND UserID = '%s'", $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"], $this->UserID );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$UserBonusPoint = $cNeoSQLConnectODBC->Result( "BonusPoint",ODBC_RETYPE_INT );
		$this->BonusPoint = $UserBonusPoint;
		$cNeoSQLConnectODBC->CloseRanWeb();
                
		return $UserBonusPoint;
	}
	
	public function SetUserPoint($s){ $this->UserPoint = $s; }
	
	public function UpdateGameTime()
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserGameOnlineTime = %d WHERE UserNum = %d",$this->GameTime,$this->UserNum );
		//echo $szTemp;
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$cNeoSQLConnectODBC->CloseRanUser();
	}
	
	public function UpdateLastUserPoint()
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserPoint FROM UserInfo WHERE UserNum = %d",$this->UserNum );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$UserPoint = $cNeoSQLConnectODBC->Result( "UserPoint",ODBC_RETYPE_INT );
		$this->UserPoint = $UserPoint;
		$cNeoSQLConnectODBC->CloseRanUser();
		return $UserPoint;
	}
	
	public function UpdateUserPointToDB()
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserPoint = %d WHERE UserNum = %d",$this->UserPoint,$this->UserNum );
		//echo $szTemp;
		$Query = $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		if ( $Query )
			$Query = true;
		else
			$Query = false;
		$cNeoSQLConnectODBC->CloseRanUser();
		
		CNeoLog::LogUserPoint( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $this->UserNum , $this->UserPoint );
		
		return $Query;
	}
	
	public function UpdatePassGame( $pass )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserPass = '%s' WHERE UserNum = %d AND UserID = '%s' " , $pass , $this->UserNum , $this->UserID );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$cNeoSQLConnectODBC->CloseRanUser();
	}
	
	public function UpdatePassChar( $pass )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserPass2 = '%s' WHERE UserNum = %d AND UserID = '%s' " , $pass , $this->UserNum , $this->UserID );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$cNeoSQLConnectODBC->CloseRanUser();
	}
	
	public function Clear()
	{
		$this->UserNum = 0;
		$this->UserID = "";
		$this->Password = "";
		$this->UserType = 0;
		$this->CreateDate = "";
		$this->UserPoint = 0;
		$this->SessionNameID = "";
		$this->LogIP = "";
		CGlobal::SetSesLoginOut( "" );
		$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
		if ( $bChaLogin )
		{
			$pChar = unserialize( CGlobal::GetSes( CGlobal::GetSesChaMan() ) );
			$pChar->Clear();
			CGlobal::SetSes( CGlobal::GetSesChaManLogin() , OFFLINE );
		}
	}
	
	public function LogOn(){ return ( self::GetUserNum() <= 0 ) ? false : true ; }
	
	public function CheckLogOn()
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserNum,UserLoginState FROM UserInfo WHERE UserNum = %d  AND UserLoginState = 0 ",$this->UserNum );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$bLogin = false;
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			//$this->UserNum = $cNeoSQLConnectODBC->Result( "UserNum" );
			//$this->UserLoginState = $cNeoSQLConnectODBC->Result( "UserLoginState" );
			$bLogin = true;
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		return ( $bLogin ) ? true : false ;
	}
        
        public function GetUserBan()
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserNum FROM UserInfo WHERE UserID = '%s' AND UserPass = '%s'  AND UserBlock = 1 ",$this->UserID , $this->Password );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$bBan = false;
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$bBan = true;
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		return $bBan;
	}
        
     public function __Ban()
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserBlock = 1 , UserBlockDate = getdate()+999999 WHERE UserNum = %d" , $this->UserNum );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$cNeoSQLConnectODBC->CloseRanUser();
	}
        
    public function __UnBan()
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "UPDATE UserInfo SET UserBlock = 0 , UserBlockDate = getdate()-1 WHERE UserNum = %d" , $this->UserNum);
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$cNeoSQLConnectODBC->CloseRanUser();
	}
	
	public function Login( $id , $password )
	{
		//none protect sql injection
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		//$szTemp = sprintf( "SELECT UserNum,UserID,UserPass,UserType,UserPoint,CreateDate,UserGameOnlineTime FROM UserInfo WHERE UserID = '%s' AND UserPass = '%s'--  AND UserLoginState = 0 ",$id , $password );
                $szTemp = sprintf( "SELECT UserNum,UserID,UserPass,UserType,UserPoint,CreateDate,UserGameOnlineTime FROM UserInfo WHERE UserID = '%s' AND UserPass = '%s'",$id , $password );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$bLogin = false;
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->UserNum = $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT );
			$this->UserID = $cNeoSQLConnectODBC->Result( "UserID" , ODBC_RETYPE_ENG );
			$this->Password = $cNeoSQLConnectODBC->Result( "UserPass" , ODBC_RETYPE_ENG );
			$this->UserType = $cNeoSQLConnectODBC->Result( "UserType" , ODBC_RETYPE_INT );
			$this->CreateDate = $cNeoSQLConnectODBC->Result( "CreateDate" , ODBC_RETYPE_ENG );
			$this->UserPoint = $cNeoSQLConnectODBC->Result( "UserPoint" , ODBC_RETYPE_INT );
			$this->GameTime = $cNeoSQLConnectODBC->Result( "UserGameOnlineTime" , ODBC_RETYPE_INT );
			$bLogin = true;
			$this->SecCodeLogin = round(rand( 100,100000 ));
			
			$this->SessionNameID = session_id();
			$this->LogIP = CGlobal::getIP();
			//CSessionNeo::LoginID( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $this->UserNum , $this->SessionNameID , $this->LogIP );
			//CNeoLog::LogLogIn( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $this->UserNum , CGlobal::getIP() );
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		return ( $bLogin ) ? true : false ;
	}
	
	static public function CheckIDAlready( $id , $MemNum )
	{
		//none protect sql injection
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( " SELECT UserNum FROM UserInfo WHERE UserID = '%s' ",$id );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		$bLogin = false;
		while( $cNeoSQLConnectODBC->FetchRow() )	{
			$bLogin = true;
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		return $bLogin;
	}
	
	static function RandCodeLogSessionOut(){ return md5( session_id() . round(rand( 100,100000 )) . CGlobal::getIP() ); }
}
?>