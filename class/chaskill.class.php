<?php
define("MAX_SKILL_LEVEL",8);
if (!defined('SKILL_ERROR'))
{
	define("SKILL_ERROR",-1);
}
class CNeoChaSkill
{
	protected $VERSION	= 0x0100;
	protected $SIZE			= 8;
	public $Binary;
	public $SkillNum = 0;
	public $Main = array();
	public $Sub = array();
	public $Level = array();
	public $Temp = array();
	public function AddSkill( $main , $sub , $level )
	{
		$this->Main[ $this->SkillNum ] = $main;
		$this->Sub[ $this->SkillNum ] = $sub;
		$this->Level[ $this->SkillNum ] = $level;
		
		$this->SkillNum++;
	}
        
        public function DelSkill( $nid )
        {
            for( $i= $nid ; $i < $this->SkillNum-1 ; $i++ )
            {
                $this->Main[ $i ] = $this->Main[ $i+1 ];
                $this->Sub[ $i ] = $this->Sub[ $i+1 ];
                $this->Level[ $i ] = $this->Level[ $i+1 ];
            }
            
            $this->SkillNum--;
        }
        
	public function FindID( $main , $sub )
	{
		for( $i= 0 ; $i < $this->SkillNum ; $i++ )
		{
			if ( $main == $this->Main[$i] && $sub == $this->Sub[$i] )
				return $i;
		}
		return SKILL_ERROR;
	}
	public function UpdateBuffer()
	{
		$this->Bibary = self::GetBuffer();
	}
	public function GetBuffer()
	{
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteBuffer( "0001000008000000" );
		$cNeoSerialMemory->WriteInt( $this->SkillNum );
		for( $i = 0 ; $i < $this->SkillNum ; $i++ )
		{
			$cNeoSerialMemory->WriteWord( $this->Main[$i] );
			$cNeoSerialMemory->WriteWord( $this->Sub[$i] );
			$cNeoSerialMemory->WriteWord( $this->Level[$i] );
			$cNeoSerialMemory->WriteWord( $this->Temp[$i] );
		}
		return $cNeoSerialMemory;
	}
	public function UpdateDB( $cNeoSerialMemory , $chanum )
	{
		global $_CONFIG;
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( "UPDATE ChaInfo SET ChaSkills = 0x%s WHERE ChaNum = %d",$cNeoSerialMemory->GetBuffer(),$chanum );
		$cNeoSQLConnectODBC->QueryRanGame($szTemp);
		$cNeoSQLConnectODBC->CloseRanGame();
	}
        public function UpdateDBM( $cNeoSerialMemory , $chanum , $MemNum )
	{
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		$cWeb->GetSysmFromDB( $MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( "UPDATE ChaInfo SET ChaSkills = 0x%s WHERE ChaNum = %d",$cNeoSerialMemory->GetBuffer(),$chanum );
		$cNeoSQLConnectODBC->QueryRanGame($szTemp);
		$cNeoSQLConnectODBC->CloseRanGame();
	}
	protected function LoadData_0100( &$cNeoSerialMemory )
	{
		if ( $cNeoSerialMemory == NULL ) return false;
		$SIZE = $cNeoSerialMemory->ReadInt();
		for( $i = 0 ; $i < $SIZE ; $i++ )
		{
			$this->Main[$i] = $cNeoSerialMemory->ReadWord();
			$this->Sub[$i] = $cNeoSerialMemory->ReadWord();
			$this->Level[$i] = $cNeoSerialMemory->ReadWord();
			$this->Temp[$i] = $cNeoSerialMemory->ReadWord();
		}
		$this->SkillNum = $SIZE;
		return true;
	}
	public function LoadData()
	{
		if ( empty($this->Binary) || $this->Binary == NULL ) return false;
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteBuffer( $this->Binary );
		$VERSION = $cNeoSerialMemory->ReadInt();
		$SIZE = $cNeoSerialMemory->ReadInt();
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
	public function LoadChaSkill( $chanum )
	{
		global $_CONFIG;
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( "SELECT ChaSkills FROM ChaInfo WHERE ChaNum = %d",$chanum );
		$cNeoSQLConnectODBC->QueryRanGame($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->Binary = $cNeoSQLConnectODBC->Result( "ChaSkills" , ODBC_RETYPE_BINARY );
			self::LoadData();
		}
		$cNeoSQLConnectODBC->CloseRanGame();
	}
}
?>