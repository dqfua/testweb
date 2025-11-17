<?php
if (!defined('SKILL_ERROR'))
{
	define("SKILL_ERROR",-1);
}
define("SKILL_MAX_LEVEL",8);
class CSkillSet
{
	protected $pBuffer = NULL;
	public $MemNum = 0;
	public $SkillNum = 0;
	public $SkillSetOpen = 0;
	public $SkillPoint = 0;
	public $SkillClass = array();
	public $SkillMain = array();
	public $SkillSub = array();
	public $SkillLevel = array();
	public function FristCheck( $MemNum )
	{
		$bWork = false;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT SkillData FROM MemSkillSet WHERE MemNum = %d",$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$bWork = true;
		}
		if ( $bWork == false )
		{
			$szTemp = sprintf( "INSERT INTO MemSkillSet ( MemNum ) VALUES( %d )",$MemNum );
			$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
	}
	public function UpdatePoint( $MemNum , $point )
	{
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "UPDATE MemSkillSet SET UsePoint = %d WHERE MemNum = %d" , $point,$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$cNeoSQLConnectODBC->CloseRanWeb();
		$this->SkillPoint = $point;
	}
	public function UpdateOpen( $MemNum , $nopen )
	{
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "UPDATE MemSkillSet SET nOpen = %d WHERE MemNum = %d" , $nopen,$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$cNeoSQLConnectODBC->CloseRanWeb();
		$this->SkillSetOpen = $nopen;
	}
	public function AddSkill( $class,$main,$sub,$level )
	{
		$this->SkillClass[ $this->SkillNum ] = $class;
		$this->SkillMain[ $this->SkillNum ] = $main;
		$this->SkillSub[ $this->SkillNum ] = $sub;
		$this->SkillLevel[ $this->SkillNum ] = $level;
		$this->SkillNum ++;
	}
	public function EditSkill( $id,$class,$main,$sub,$level )
	{
		$this->SkillClass[ $id ] = $class;
		$this->SkillMain[ $id ] = $main;
		$this->SkillSub[ $id ] = $sub;
		$this->SkillLevel[ $id ] = $level;
	}
	public function DelSkill( $id )
	{
		if ( $id < 0 || $id >= $this->SkillNum ) return false;
		for( $i = $id ; $i < $this->SkillNum ; $i++ )
		{
			$this->SkillClass[ $i ] = $this->SkillClass[ $i+1 ];
			$this->SkillMain[ $i ] = $this->SkillMain[ $i+1 ];
			$this->SkillSub[ $i ] = $this->SkillSub[ $i+1 ];
			$this->SkillLevel[ $i ] = $this->SkillLevel[ $i+1 ];
		}
		$this->SkillNum--;
		return true;
	}
        public function Clear()
        {
            $this->SkillClass = array();
            $this->SkillMain = array();
            $this->SkillSub = array();
            $this->SkillLevel = array();
            $this->SkillNum = 0;
        }
	public function FindSkill( $class,$main,$sub )
	{
		for( $i = 0 ; $i < $this->SkillNum ; $i++ )
		{
			if ( $this->SkillMain[ $i ] == $main && $this->SkillSub[ $i ] == $sub && $this->SkillClass[ $i ] == $class )
			return $i;
		}
		return SKILL_ERROR;
	}
	public function Save( )
	{
		if ($this->MemNum == 0) return false;
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteInt( /*VERSION*/ 0x0100 );
		$cNeoSerialMemory->WriteInt( $this->SkillNum );
		for( $i = 0 ; $i < $this->SkillNum ; $i++ )
		{
			$cNeoSerialMemory->WriteInt( $this->SkillClass[ $i ] );
			$cNeoSerialMemory->WriteInt( $this->SkillMain[ $i ] );
			$cNeoSerialMemory->WriteInt( $this->SkillSub[ $i ] );
			$cNeoSerialMemory->WriteInt( $this->SkillLevel[ $i ] );
		}
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "UPDATE MemSkillSet SET SkillData = 0x%s WHERE MemNum = %d",$cNeoSerialMemory->GetBuffer(),$this->MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$cNeoSQLConnectODBC->CloseRanWeb();
		return true;
	}
	private function LoadData_0100( $cNeoSerialMemory )
	{
		if ( !$cNeoSerialMemory ) return false;
		
		$this->SkillNum = $cNeoSerialMemory->ReadInt();
		for( $i = 0 ; $i < $this->SkillNum ; $i++ )
		{
			$this->SkillClass[ $i ] = $cNeoSerialMemory->ReadInt();
			$this->SkillMain[ $i ] = $cNeoSerialMemory->ReadInt();
			$this->SkillSub[ $i ] = $cNeoSerialMemory->ReadInt();
			$this->SkillLevel[ $i ] = $cNeoSerialMemory->ReadInt();
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
	public function LoadSkillData( $MemNum )
	{
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT SkillData,nOpen,UsePoint FROM MemSkillSet WHERE MemNum = %d",$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->pBuffer = $cNeoSQLConnectODBC->Result( "SkillData",ODBC_RETYPE_BINARY );
			self::LoadData();
			
			$this->SkillSetOpen = $cNeoSQLConnectODBC->Result( "nOpen" , ODBC_RETYPE_INT );
			$this->SkillPoint = $cNeoSQLConnectODBC->Result( "UsePoint" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		$this->MemNum = $MemNum;
	}
}
?>