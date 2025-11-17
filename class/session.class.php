<?php
class CSessionNeo
{
	static public function DoPass( $MemNum , $UserNum , $DoPass , $LogIP )
    {
            global $_CONFIG;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_DoPass(MemNum,UserNum,DoPass,LogIP)
                                                VALUES(%d,%d,'%s','%s')"
                                            , $MemNum , $UserNum , $DoPass , $LogIP );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnectODBC->CloseRanWeb();
    }
	static public function checkdopassdelay( $MemNum , $UserNum )
    {
            global $_CONFIG;
			$bReturn = 0;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "SELECT TOP 1 DATEDIFF(SECOND,LogDate,GETDATE()) as Delay
												FROM Log_DoPass
                                                WHERE MemNum = %d AND UserNum = %d
												ORDER BY LogNum DESC
												"
                                            , $MemNum , $UserNum );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
					$bReturn = $cNeoSQLConnectODBC->Result("Delay" , ODBC_RETYPE_INT);
			}
            $cNeoSQLConnectODBC->CloseRanWeb();
			return $bReturn;
    }
	static public function checkdopass( $MemNum , $UserNum , $DoPass , $LogIP )
    {
            global $_CONFIG;
			$SessionNameID = "";
			$bReturn = false;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "SELECT TOP 1 MemNum,UserNum,DoPass,LogIP FROM Log_DoPass
                                                WHERE MemNum = %d AND UserNum = %d
												ORDER BY LogNum DESC
												"
                                            , $MemNum , $UserNum );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
				//printf( "%s == %s <br>" , $SessionNameID , $cNeoSQLConnectODBC->Result("SessionNameID") );
				//printf( "%s == %s <br>" , $LogIP , $cNeoSQLConnectODBC->Result("LogIP") );
				if ( strcmp( $SessionNameID , $cNeoSQLConnectODBC->Result("DoPass" , ODBC_RETYPE_ENG) ) == 0 &&
					strcmp( $LogIP , $cNeoSQLConnectODBC->Result("LogIP" , ODBC_RETYPE_ENG) ) == 0
					)
					$bReturn = true;
					break;
			}
            $cNeoSQLConnectODBC->CloseRanWeb();
			return $bReturn;
    }
    static public function LoginID( $MemNum , $UserNum , $SessionNameID , $LogIP )
    {
            global $_CONFIG;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_SessionLogin(MemNum,UserNum,SessionNameID,LogIP)
                                                VALUES(%d,%d,'%s','%s')"
                                            , $MemNum , $UserNum , $SessionNameID , $LogIP );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnectODBC->CloseRanWeb();
    }
	static public function checksessionout( $MemNum , $UserNum , $SessionNameID , $LogIP )
    {
            global $_CONFIG;
			$bReturn = false;
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "SELECT TOP 1 MemNum,UserNum,SessionNameID,LogIP FROM Log_SessionLogin
                                                WHERE MemNum = %d AND UserNum = %d
												ORDER BY LogNum DESC
												"
                                            , $MemNum , $UserNum );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
				//printf( "%s == %s <br>" , $SessionNameID , $cNeoSQLConnectODBC->Result("SessionNameID") );
				//printf( "%s == %s <br>" , $LogIP , $cNeoSQLConnectODBC->Result("LogIP") );
				if ( strcmp( $SessionNameID , $cNeoSQLConnectODBC->Result("SessionNameID",ODBC_RETYPE_ENG) ) == 0 &&
					strcmp( $LogIP , $cNeoSQLConnectODBC->Result("LogIP",ODBC_RETYPE_ENG) ) == 0
					)
					$bReturn = true;
					break;
			}
            $cNeoSQLConnectODBC->CloseRanWeb();
			return $bReturn;
    }
}
?>