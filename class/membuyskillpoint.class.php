<?php
class CMemBuySkillPoint
{
	public $MemNum = 0;
	public $ModeOn = 0;
	public $SkillPoint = 0;
	public $UsePoint = 0;
	
	public function Reset()
	{
		$this->MemNum = 0;
		$this->ModeOn = 0;
		$this->SkillPoint = 0;
		$this->UsePoint = 0;
	}
	
	public function UpdateDB()
	{
		if ( $this->MemNum <= 0 ) return false;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( " UPDATE MemSkillPoint SET ModeOn = %d , SkillPoint = %d , UsePoint = %d WHERE MemNum = %d "
						  , $this->ModeOn
						  , $this->SkillPoint
						  , $this->UsePoint
						  , $this->MemNum
						  );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$cNeoSQLConnectODBC->CloseRanWeb();
		return true;
	}
	
	public function LoadData( $MemNum )
	{
		global $_CONFIG;
		
		$bWork = false;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT
						  ModeOn,SkillPoint,UsePoint
						  FROM MemSkillPoint
						  WHERE MemNum = %d
						  ",$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->ModeOn = $cNeoSQLConnectODBC->Result( "ModeOn" , ODBC_RETYPE_INT );
			$this->SkillPoint = $cNeoSQLConnectODBC->Result( "SkillPoint" , ODBC_RETYPE_INT );
			$this->UsePoint = $cNeoSQLConnectODBC->Result( "UsePoint" , ODBC_RETYPE_INT );
			$bWork = true;
		}
		if ( !$bWork )
		{
			$szTemp = sprintf( "INSERT INTO
							  MemSkillPoint( MemNum )
							  VALUES( %d )
						  "
						  ,$MemNum
						  );
			$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
			$szTemp = sprintf( "SELECT
						  ModeOn,SkillPoint,UsePoint
						  FROM MemSkillPoint
						  WHERE MemNum = %d
						  ",$MemNum );
			$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
				$this->ModeOn = $cNeoSQLConnectODBC->Result( "ModeOn" , ODBC_RETYPE_INT );
				$this->SkillPoint = $cNeoSQLConnectODBC->Result( "SkillPoint" , ODBC_RETYPE_INT );
				$this->UsePoint = $cNeoSQLConnectODBC->Result( "UsePoint" , ODBC_RETYPE_INT );
			}
		}
		$this->MemNum = $MemNum;
		$cNeoSQLConnectODBC->CloseRanWeb();
	}
}
?>