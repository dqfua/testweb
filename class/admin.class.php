<?php
/*
Function Administrator control panel editor
Development By NeoMasteI2
FlyFly To the sky!!
*/
if ( !defined("__SERVICENEO_OFF") )
    define("__SERVICENEO_OFF", FALSE);
if ( !defined("__SERVICENEO_ON") )
    define("__SERVICENEO_ON", TRUE);
class CAdmin
{
	protected $bLogin = false;	
	public function GetLogin() { return $this->bLogin; }
	
	protected $nMemberNum = 0;
	protected $szID = "";
	protected $szPassword = "";
        protected $szPasswordCard = "";
        protected $nType = 0;
	public $nServerType = 0;
        public $PassMD5 = 0;
	protected $szEmail = "";
        protected $bLoginPassCard = false;
        public $szFolderShop = "";
        public $MyService = __SERVICENEO_OFF;
	
	public function GetMemNum(){ return $this->nMemberNum; }
	public function GetID(){ return $this->szID; }
	//public function GetPass(){ return $this->szPassword; }
        public function GetPassCard()
		{
			$szReturn = "";
            if ( $this->nMemberNum == 0 ) return $szReturn;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "SELECT MemPass_Card FROM MemberInfo WHERE MemberNum = %d",$this->nMemberNum );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $szReturn = $cNeoSQLConnectODBC->Result("MemPass_Card",ODBC_RETYPE_ENG);
            }
            $cNeoSQLConnectODBC->CloseRanWeb();
            return $szReturn;
		}
        public function GetLoginPassCard(){ return $this->bLoginPassCard; }
        public function SetLoginPassCard( $bLogin ){ $this->bLoginPassCard = $bLogin; }
        public function GetPass()
        {
            $szReturn = "";
            if ( $this->nMemberNum == 0 ) return $szReturn;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "SELECT MemPass FROM MemberInfo WHERE MemberNum = %d",$this->nMemberNum );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $szReturn = $cNeoSQLConnectODBC->Result("MemPass",ODBC_RETYPE_ENG);
            }
            $cNeoSQLConnectODBC->CloseRanWeb();
            return $szReturn;
        }
        public function UpdatePassMD5( )
        {
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "UPDATE MemberInfo SET PassMD5 = %d WHERE MemberNum = %d " , $this->PassMD5 , $this->nMemberNum );
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            $cNeoSQLConnectODBC->CloseRanWeb();
        }
        public function UpdatePass( $password )
        {
            $this->szPassword = $password;
             if ( $this->nMemberNum == 0 ) return false;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "UPDATE MemberInfo SET MemPass = '%s' WHERE MemberNum = %d " , $this->szPassword , $this->nMemberNum );
            $bQuery = $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            $cNeoSQLConnectODBC->CloseRanWeb();
            return $bQuery;
        }
        public function UpdatePassCard( $password )
        {
            $this->szPassword = $password;
             if ( $this->nMemberNum == 0 ) return false;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "UPDATE MemberInfo SET MemPass_Card = '%s' WHERE MemberNum = %d " , $this->szPassword , $this->nMemberNum );
            $bQuery = $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            $cNeoSQLConnectODBC->CloseRanWeb();
            return $bQuery;
        }
	public function GetType(){ return $this->nType; }
	public function GetEmail(){ return $this->szEmail; }
	
	public function SetPassword( &$pass )	{ $pass = substr( strtoupper( md5( $pass ) ) , 0 , CGlobal::GetPassLen() ); }
	
	public function Reset()
	{
		$this->nMemberNum = 0;
		$this->szID = "";
		$this->szPassword = "";
		$this->nType = 0;
		$this->nServerType = 0;
		$this->PassMD5 = 0;
	}
	public function CheckLogin( $id , $pass )
	{
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT MemberNum FROM MemberInfo WHERE MemID = '%s' AND MemPass = '%s' AND MemBan = 0 AND MemDelete = 0",$id,$pass );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$MemberNum = $cNeoSQLConnectODBC->Result( "MemberNum" , ODBC_RETYPE_INT );
		if ( $MemberNum <= 0 || empty( $MemberNum ) ) $bLogin = true; else $bLogin = false;
		$cNeoSQLConnectODBC->CloseRanWeb();
		return ( $bLogin ) ? true : false ;
	}
	public function Login( $id , $pass /* password is md5 24 bit */ )
	{
		if ( $id == "" || $pass == "" ) return ;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT MemberNum,MemType,MemPass_Card,EMail,ServerType,Reg_ShopFolder,MyService,PassMD5 FROM MemberInfo WHERE MemID = '%s' AND MemPass = '%s' AND MemBan = 0 AND MemDelete = 0",$id,$pass );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$MemberNum = $cNeoSQLConnectODBC->Result( "MemberNum" , ODBC_RETYPE_INT );
		if ( $MemberNum <= 0 || empty( $MemberNum ) ) $bLogin = false; else $bLogin = true;
		if ( $bLogin )
		{
			$this->bLogin = true;
			$this->nMemberNum = $MemberNum;
			$this->szID = $id;
			$this->szPassword = $pass;
                        $this->szPasswordCard = $cNeoSQLConnectODBC->Result("MemPass_Card" , ODBC_RETYPE_ENG);
			$this->nType = $cNeoSQLConnectODBC->Result("MemType" , ODBC_RETYPE_INT);
			$this->szEmail = $cNeoSQLConnectODBC->Result("EMail" , ODBC_RETYPE_THAI);
			$this->nServerType = $cNeoSQLConnectODBC->Result("ServerType" , ODBC_RETYPE_INT);
			$this->PassMD5 = $cNeoSQLConnectODBC->Result("PassMD5" , ODBC_RETYPE_INT);
                        $this->szFolderShop = $cNeoSQLConnectODBC->Result("Reg_ShopFolder" , ODBC_RETYPE_THAI);
                        $this->MyService = $cNeoSQLConnectODBC->Result("MyService" , ODBC_RETYPE_INT);
                        
                        CNeoLog::Log_LoginAdmin($MemberNum, CGlobal::getIP() );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
	}
	public function Logout()
	{
		$this->bLogin = false;
		self::Reset();
	}
}
?>