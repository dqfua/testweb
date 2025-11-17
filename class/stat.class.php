<?php

class CStat
{
	static public function PlayerOnline( $MemNum )
    {
		$PlayerOnline = 0;
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
		$cNeoSQLConnectODBC->QueryRanUser( "SELECT COUNT(UserNum) AS PlayerOnline FROM UserInfo WHERE UserLoginState = 1" );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$PlayerOnline = $cNeoSQLConnectODBC->Result( "PlayerOnline" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$cNeoSQLConnectODBC->QueryRanWeb( sprintf( "INSERT INTO Stat_PlayerOnline( MemNum , PlayerOnline ) VALUES( %d , %d )" , $MemNum , $PlayerOnline ) );
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $PlayerOnline;
    }
};

?>