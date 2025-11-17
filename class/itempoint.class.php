<?php
//0 : 50
//1 : 100
//2 : 150
//3 : 300
//4 : 500
//5 : 1000
define( "MAX_CARD_TYPE" , 6 );

class CItemPoint
{
	public $dwVersion			= 0x0100;
	protected $dwItemCount			= array();
	protected $wCardType			   = array();
	protected $wMain					   = array();
	protected $wSub						= array();
	protected $pItemPointInfo		  = NULL;
	protected $nMemNum				= 0;
	
	public function GetItemMain( $dwItemCount , $nIndex ) { return $this->wMain[$dwItemCount][$nIndex]; }
	public function GetItemSub( $dwItemCount , $nIndex ) { return $this->wSub[$dwItemCount][$nIndex]; }
	public function GetItemNum( $dwItemCount ){ return $this->dwItemCount[$dwItemCount]; }
	
	public function SetItemMain( $dwItemCount , $nIndex , $nValue ){ $this->wMain[$dwItemCount][$nIndex] = $nValue; }
	public function SetItemSub( $dwItemCount , $nIndex , $nValue ){ $this->wSub[$dwItemCount][$nIndex] = $nValue; }
	public function SetItemNum( $dwItemCount , $nValue ){ $this->dwItemCount[$dwItemCount] = $nValue; }
	
	public function UpdateToBinary()
	{
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteInt( $this->dwVersion );
		for( $w = 0 ;  $w < MAX_CARD_TYPE ; $w++ )
		{
			$cNeoSerialMemory->WriteInt( $this->dwItemCount[ $w ] );
			for( $i = 0; $i < $this->dwItemCount[$w] ; $i++ )
			{
				$cNeoSerialMemory->WriteWord($this->wMain[$w][$i]);
				$cNeoSerialMemory->WriteWord($this->wSub[$w][$i]);
			}
		}
		$this->pItemPointInfo = $cNeoSerialMemory->GetBuffer();
	}
	
	protected function Load_0100( &$cNeoSerialMemory )
	{
		if ( !$cNeoSerialMemory ) return FALSE;
		for( $w = 0 ; $w < MAX_CARD_TYPE ; $w ++ )
		{
			$this->dwItemCount[$w] = $cNeoSerialMemory->ReadInt();
			for( $i = 0; $i < $this->dwItemCount[$w] ; $i++ )
			{
				$this->wMain[$w][$i] = $cNeoSerialMemory->ReadWord();
				$this->wSub[$w][$i] = $cNeoSerialMemory->ReadWord();
			}
		}
		return TRUE;
	}
	
	public function AssetBegin()
	{
		for( $w = 0 ;  $w < MAX_CARD_TYPE ; $w++ )
		{
			$this->dwItemCount[ $w ] = ITEMPOINT_GET_FREE_BONUS;
			for( $i = 0; $i < ITEMPOINT_GET_FREE_BONUS ; $i++ )
			{
				$this->wMain[$w][$i] = 0xFFFF;
				$this->wSub[$w][$i] = 0xFFFF;
			}
		}
		self::UpdateToBinary();
		self::Save();
		self::Load();
	}
	
	public function Load()
	{
		if ( !$this->pItemPointInfo )
		{
			self::AssetBegin();
			return TRUE;
		}
		
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteBuffer( $this->pItemPointInfo );
		$this->dwVersion = $cNeoSerialMemory->ReadInt();
		
		switch( $this->dwVersion )
		{
			case 0x0100:
			{
				self::Load_0100( $cNeoSerialMemory );
			}break;
		}
		
		return TRUE;
	}
	
	public function Save( )
	{
		if ( !$this->nMemNum ) return FALSE;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "UPDATE MemPoint SET ItemPointInfo = 0x%s WHERE MemNum = %d"
		, $this->pItemPointInfo , $this->nMemNum );
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		$cNeoSQLConnectODBC->CloseRanWeb();
		
		return TRUE;
	}
	
	public function __construct( $memnum )
	{
		if ( !$memnum ) die( "ERROR|FAIL|MEMNUM" );
		
		for( $w = 0 ;  $w < MAX_CARD_TYPE ; $w++ )
		{
			for( $i = 0 ; $i < ITEMPOINT_GET_FREE_BONUS ; ++$i )
			{
				$this->wMain[$w][$i] = 0;
				$this->wSub[$w][$i] = 0;
			}
		}
		
		$this->nMemNum = $memnum;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT ItemPointInfo FROM MemPoint WHERE MemNum = %d" , $memnum );
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->pItemPointInfo = $cNeoSQLConnectODBC->Result( "ItemPointInfo" , ODBC_RETYPE_BINARY );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		self::Load();
	}
}
?>