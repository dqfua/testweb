<?php

class UserInfo
{
    public $UserNum;
    public $UserID;
    public $UserName;
    public $UserPass;
    public $UserPass2;
    public $UserType;
    public $UserLoginState;
    public $CreateDate;
    public $LastLoginDate;
    public $UserBlock;
    public $UserBlockDate;
    public $ChaRemain;
    public $ChatBlockDate;
    public $UserEmail;
    public $UserPoint;
    public $UserGameOnlineTime;
    public $UserInven;
    public $ParentID;
    public $BonusPoint;
    
    //pointer
    public $NeoUserInven;
    
    public function __construct() {
        ;
    }
	
	static public function GetUserNumFromUserID( $MemNum , $UserID )
    {
		$UserNum = 0;
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserNum FROM UserInfo WHERE UserID = '%s'" , $UserID );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$UserNum = $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		return $UserNum;
    }
	
	static public function GetUserIDFromUserNum( $MemNum , $UserNum )
    {
		$UserID = "";
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$szTemp = sprintf( "SELECT UserID FROM UserInfo WHERE UserNum = %d" , $UserNum );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$UserID = $cNeoSQLConnectODBC->Result( "UserID" , ODBC_RETYPE_ENG );
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		return $UserID;
    }
	
	static public function GetParentIDFromUserID( $MemNum , $UserID )
    {
		$ParentID = "";
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT ParentID FROM UserInfo WHERE UserID = '%s'" , $UserID );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$ParentID = $cNeoSQLConnectODBC->Result( "ParentID" , ODBC_RETYPE_ENG );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $ParentID;
    }
};

?>
