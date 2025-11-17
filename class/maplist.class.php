<?php
define("MAP_ERROR",-1);
class CMapList
{
	protected $pBuffer = NULL;
	public $MemNum = 0;
	public $MapNum = 0;
	public $MapName = array();
	public $MapMain = array();
	public $MapSub = array();
	public $MapPoint = array();
	public function AddMap( $mapname,$main,$sub,$point )
	{
		$this->MapName[ $this->MapNum ] = $mapname;
		$this->MapMain[ $this->MapNum ] = $main;
		$this->MapSub[ $this->MapNum ] = $sub;
		$this->MapPoint[ $this->MapNum ] = $point;
		$this->MapNum ++;
	}
	public function EditMap( $id,$mapname,$main,$sub,$point )
	{
		$this->MapName[ $id ] = $mapname;
		$this->MapMain[ $id ] = $main;
		$this->MapSub[ $id ] = $sub;
		$this->MapPoint[ $id ] = $point;
	}
	public function DelMap( $id )
	{
		if ( $id < 0 || $id >= $this->MapNum ) return false;
		for( $i = $id ; $i < $this->MapNum ; $i++ )
		{
			if ( !array_key_exists( $i+1 , $this->MapName ) ) break;
			$this->MapName[ $i ] = $this->MapName[ $i+1 ];
			$this->MapMain[ $i ] = $this->MapMain[ $i+1 ];
			$this->MapSub[ $i ] = $this->MapSub[ $i+1 ];
			$this->MapPoint[ $i ] = $this->MapPoint[ $i+1 ];
		}
		$this->MapNum--;
		return true;
	}
	public function FindMap( $main,$sub )
	{
		for( $i = 0 ; $i < $this->MapNum ; $i++ )
		{
			if ( $this->MapMain[ $i ] == $main && $this->MapSub[ $i ] == $sub )
			return $i;
		}
		return MAP_ERROR;
	}
	public function Save( )
	{
		if ($this->MemNum == 0) return false;
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteInt( /*VERSION*/ 0x0100 );
		$cNeoSerialMemory->WriteInt( $this->MapNum );
		for( $i = 0 ; $i < $this->MapNum ; $i++ )
		{
			$cNeoSerialMemory->WriteInt( $this->MapMain[ $i ] );
			$cNeoSerialMemory->WriteInt( $this->MapSub[ $i ] );
			$cNeoSerialMemory->WriteString( $this->MapName[ $i ] );
			$cNeoSerialMemory->WriteInt( $this->MapPoint[ $i ] );
		}
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "UPDATE MemMapSet SET MapList = 0x%s WHERE MemNum = %d",$cNeoSerialMemory->GetBuffer(),$this->MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$cNeoSQLConnectODBC->CloseRanWeb();
		return true;
	}
	private function LoadData_0100( $cNeoSerialMemory )
	{
		if ( !$cNeoSerialMemory ) return false;
		
		$this->MapNum = $cNeoSerialMemory->ReadInt();
		for( $i = 0 ; $i < $this->MapNum ; $i++ )
		{
			$this->MapMain[ $i ] = $cNeoSerialMemory->ReadInt();
			$this->MapSub[ $i ] = $cNeoSerialMemory->ReadInt();
			$this->MapName[ $i ] = $cNeoSerialMemory->ReadString();
			$this->MapPoint[ $i ] = $cNeoSerialMemory->ReadInt();
		}
		
		return true;
	}
	private function LoadData()
	{
		if ( !$this->pBuffer ) return false;
		
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteBuffer( $this->pBuffer );
		$VERSION = $cNeoSerialMemory->ReadInt();
		switch( $VERSION )
		{
			case 0x0100:
				self::LoadData_0100( $cNeoSerialMemory );
			break;
			default:
			return false;
		}
		return true;
	}
	public function LoadMapData( $MemNum )
	{
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT MapList FROM MemMapSet WHERE MemNum = %d",$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->pBuffer = $cNeoSQLConnectODBC->Result( "MapList" , ODBC_RETYPE_BINARY );
			self::LoadData();
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		$this->MemNum = $MemNum;
	}
}
?>