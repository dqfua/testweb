<?php
class CNeoCha
{
	protected $ChaNum = 0;
	protected $ChaName = "";
	protected $ChaSchool = 0;
	protected $ChaLevel = 0;
	protected $ChaClass = 0;
	protected $ChaSex = 0;
	protected $ChaBright = 0;
	protected $ChaReborn = 0;
	public $ChaSkillPoint = 0;
	
	public function GetChaNum(){ return $this->ChaNum; }
	public function GetChaName(){ return $this->ChaName; }
	public function GetChaSchool(){ return $this->ChaSchool; }
	public function GetChaLevel(){ return $this->ChaLevel;}
	public function GetChaClass(){ return $this->ChaClass; }
	public function GetChaSex(){ return $this->ChaSex; }
	public function GetChaBright(){ return $this->ChaBright; }
	public function GetChaReborn(){ return $this->ChaReborn; }
	
	protected $ChaDex = 0;
	protected $ChaSpirit = 0;
	protected $ChaStrength = 0;
	protected $ChaStrong = 0;
	protected $ChaPower = 0;
	protected $ChaStRemain = 0;
	public function GetChaDex(){ return $this->ChaDex; }
	public function GetChaSpirit(){ return $this->ChaSpirit; }
	public function GetChaStrength(){ return $this->ChaStrength; }
	public function GetChaStrong(){ return $this->ChaStrong; }
	public function GetChaPower(){ return $this->ChaPower; }
	public function GetChaStRemain(){ return $this->ChaStRemain; }
	
	public function Clear()
	{
		$this->ChaNum = 0;
		$this->ChaName = "";
		$this->ChaSchool = 0;
		$this->ChaLevel = 0;
		$this->ChaClass = 0;
		$this->ChaSex = 0;
		$this->ChaBright = 0;
		$this->ChaReborn = 0;
		$this->ChaSkillPoint = 0;
	}
	
	public function GetNowOnline(  )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( " SELECT TOP 1 ChaNum FROM ChaInfo WHERE ChaNum = %d AND ChaOnline = 0 ",$this->ChaNum );
		$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
                $bOK = false;
		while( $cNeoSQLConnectODBC->FetchRow() )
                {
                    $bOK = true;
                }
		$cNeoSQLConnectODBC->CloseRanGame();
		return $bOK;
	}
	
	public function Update_ChaSkillPoint_DB(  )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( "UPDATE ChaInfo SET ChaSkillPoint = %d WHERE ChaNum = %d",$this->ChaSkillPoint,$this->ChaNum );
		$bOK = $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
		$cNeoSQLConnectODBC->CloseRanGame();
		return $bOK;
	}
	
	public function Update2Session_ChaName(  )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( " SELECT TOP 1
						  ChaName
						  FROM ChaInfo WHERE ChaNum = %d",$this->ChaNum );
		$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )	{
			$this->ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
		}
		$cNeoSQLConnectODBC->CloseRanGame();
	}
        
        public function UpdateData( )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( " SELECT TOP 1
						  ChaNum,ChaName,ChaSchool,ChaLevel,ChaClass,ChaBright,ChaReborn,ChaPower,ChaStrong,ChaStrength,ChaSpirit,ChaDex,ChaStRemain,ChaSkillPoint
						  FROM ChaInfo WHERE ChaNum = %d  ",$this->ChaNum );
		$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
			$this->ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
			$this->ChaSchool = $cNeoSQLConnectODBC->Result( "ChaSchool" , ODBC_RETYPE_INT );
			$this->ChaLevel = $cNeoSQLConnectODBC->Result( "ChaLevel" , ODBC_RETYPE_INT );
			$this->ChaClass = $cNeoSQLConnectODBC->Result( "ChaClass" , ODBC_RETYPE_INT );
			$this->ChaBright = $cNeoSQLConnectODBC->Result( "ChaBright" , ODBC_RETYPE_INT );
			$this->ChaReborn = $cNeoSQLConnectODBC->Result( "ChaReborn" , ODBC_RETYPE_INT );
			
			$this->ChaSkillPoint = $cNeoSQLConnectODBC->Result( "ChaSkillPoint" , ODBC_RETYPE_INT );
			
			$this->ChaDex = $cNeoSQLConnectODBC->Result( "ChaDex" , ODBC_RETYPE_INT );
			$this->ChaSpirit = $cNeoSQLConnectODBC->Result( "ChaSpirit" , ODBC_RETYPE_INT );
			$this->ChaStrength = $cNeoSQLConnectODBC->Result( "ChaStrength" , ODBC_RETYPE_INT );
			$this->ChaStrong = $cNeoSQLConnectODBC->Result( "ChaStrong" , ODBC_RETYPE_INT );
			$this->ChaPower = $cNeoSQLConnectODBC->Result( "ChaPower" , ODBC_RETYPE_INT );
			$this->ChaStRemain = $cNeoSQLConnectODBC->Result( "ChaStRemain" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanGame();
	}
	
	public function Login( $ChaNum,$UserNum,$bAdmin = false,$nAdminNum = 0 )
	{
		global $_CONFIG;
		
		$cWeb = new CNeoWeb;
		if ( $bAdmin )
		$cWeb->GetDBInfoFromWebDB( $nAdminNum );
		else
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( " SELECT TOP 1
						  ChaNum,ChaName,ChaSchool,ChaLevel,ChaClass,ChaBright,ChaReborn,ChaPower,ChaStrong,ChaStrength,ChaSpirit,ChaDex,ChaStRemain,ChaSkillPoint
						  FROM ChaInfo WHERE ChaNum = %d AND UserNum = %d ",$ChaNum,$UserNum );
		$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
			$this->ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
			$this->ChaSchool = $cNeoSQLConnectODBC->Result( "ChaSchool" , ODBC_RETYPE_INT );
			$this->ChaLevel = $cNeoSQLConnectODBC->Result( "ChaLevel" , ODBC_RETYPE_INT );
			$this->ChaClass = $cNeoSQLConnectODBC->Result( "ChaClass" , ODBC_RETYPE_INT );
			$this->ChaBright = $cNeoSQLConnectODBC->Result( "ChaBright" , ODBC_RETYPE_INT );
			$this->ChaReborn = $cNeoSQLConnectODBC->Result( "ChaReborn" , ODBC_RETYPE_INT );
			
			$this->ChaSkillPoint = floor( $cNeoSQLConnectODBC->Result( "ChaSkillPoint" , ODBC_RETYPE_INT ) );
			
			$this->ChaDex = floor( $cNeoSQLConnectODBC->Result( "ChaDex" , ODBC_RETYPE_INT ) );
			$this->ChaSpirit = floor( $cNeoSQLConnectODBC->Result( "ChaSpirit" , ODBC_RETYPE_INT ) );
			$this->ChaStrength = floor( $cNeoSQLConnectODBC->Result( "ChaStrength" , ODBC_RETYPE_INT ) );
			$this->ChaStrong = floor( $cNeoSQLConnectODBC->Result( "ChaStrong" , ODBC_RETYPE_INT ) );
			$this->ChaPower = floor( $cNeoSQLConnectODBC->Result( "ChaPower" , ODBC_RETYPE_INT ) );
			$this->ChaStRemain = floor( $cNeoSQLConnectODBC->Result( "ChaStRemain" , ODBC_RETYPE_INT ) );
		}
		$cNeoSQLConnectODBC->CloseRanGame();
	}
}
?>