<?php
$arrItemOption = array( "NULL" , "DAMAGE(%)" , "DEFENSE(%)" , "HITRATE(+%)" , "AVOIDRATE(+%)" , "HP" , "MP" , "SP" , "HP_INC" , "MP_INC" , "SP_INC" , "HMS_INC" , "GRIND_DAMAGE"
                         , "GRIND_DEFENSE" , "ATTACK_RANDOM" , "DIS_SP" , "RESIST" );

define("CHAINVEN_HEAD",24);
define("CHAINVEN_ITEM_SLOT",128);
define("CHAINVEN_VERSION",265);
define("INVEN_X",5);
define("INVEN_Y",6);
define("ITEM_ERROR",-1);

$PIECE_POS_TYPE = array( "หัว" , "เสื้อ" , "กางเกง" , "ถุงมือ" , "รองเท้า" , "อาวุธ" , "สร้อยคอ" , "ต่างหู" , "แหวน" );

class _CNeoChaInven
{
        public $ChaPutOnItemsMode = false;
        
	public $MemNum = 0;
	public $ChaNum = 0;
	public $pFile;
	public $Binary;
	public $Binary_Header;
	public $ItemSlotNum = 0;
	public $Binary_ItemSlot = array();
        
        public $Binary_Backup = NULL;
	
	public $Version = 0;
	
	public $Item_InvenX = array();
	public $Item_InvenY = array();
	
	public $Item_DummyW1 = array();
	public $Item_DummyW2 = array();
	public $Item_InvenMain = array();
	public $Item_InvenSub = array();
	public $Item_DummyW3 = array();
	public $Item_DummyW4 = array();
	public $Item_tBORNTIME = array();
	public $Item_tDISGUISE = array();
	public $Item_DummyN1 = array();
	public $Item_DummyN2 = array();
	public $Item_wTrunNum = array();
	public $Item_cGenType = array();
	public $Item_cChnID = array();
	public $Item_cFieldID = array();
	public $Item_cDamage = array();
	public $Item_cDefense = array();
	public $Item_cResist_fire = array();
	public $Item_cResist_ice = array();
	public $Item_cResist_elec = array();
	public $Item_cResist_poison = array();
	public $Item_cResist_spirit = array();
	public $Item_cOptType1 = array();
	public $Item_cOptType2 = array();
	public $Item_cOptType3 = array();
	public $Item_cOptType4 = array();
	public $Item_nOptVALUE1 = array();
	public $Item_nOptVALUE2 = array();
	public $Item_nOptVALUE3 = array();
	public $Item_nOptVALUE4 = array();
	
	public function SetMemNum( $MemNum )
	{
		$this->MemNum = $MemNum;
	}
        
        public function ClearBuffer()
        {
                $this->pFile = NULL;
                $this->Binary = "";
                $this->Binary_Header = "";
                $this->ItemSlotNum = 0;
                $this->ChaNum = 0;
                $this->Binary_Backup = NULL;
        }
        
        public function GetVersion(){ return $this->Version; }
        public function Get_BufferOld() { return $this->Binary_Backup; }
        
        public function SetBinary( $b ) { $this->Binary = $b; }
        public function GetBinary( ){ return $this->Binary; }

        public function SetHeader( $b ){ $this->Binary_Header = $b; }
        public function GetHeader(){ return $this->Binary_Header; }

        public function SetBinaryItemSlot( $a , $s ) { $this->Binary_ItemSlot[$a] = $s; }
        public function GetBinaryItemSlot( $a ) { return $this->Binary_ItemSlot[$a]; }
	
	public function AddItem( &$cNeoSerialMemory , $pos = ITEM_ERROR )
	{
                if ( $this->ChaPutOnItemsMode == false )
                {
                    self::LoadNode_0112( $this->ItemSlotNum , $cNeoSerialMemory );
                    self::SetBinaryItemSlot( $this->ItemSlotNum , $cNeoSerialMemory->GetBuffer() );
                    $this->ItemSlotNum++;
                }else{
                    self::LoadNode_0112( $pos , $cNeoSerialMemory );
                    self::SetBinaryItemSlot( $pos , $cNeoSerialMemory->GetBuffer() );
                }
                return true;
	}
	
	public function DeleteItem( $ItemID )
	{
		if ( $ItemID <= ITEM_ERROR ) return false;
		$this->ItemSlotNum--;
		for( $i = $ItemID ; $i < $this->ItemSlotNum ; $i++  )
		{
			self::UpdateBinaryItemSlot( $i+1 );
			$cNeoSerialMemory = new CNeoSerialMemory;
			$cNeoSerialMemory->OpenMemory(  );
			$cNeoSerialMemory->WriteBuffer( self::GetBinaryItemSlot( $i+1 ) );
			self::SetBinaryItemSlot( $i , $cNeoSerialMemory->GetBuffer() );
			self::LoadNode_0112( $i , $cNeoSerialMemory );
			self::UpdateBinaryItemSlot( $i );
		}
		return true;
	}
        
	public function UpdateBinaryItemSlot( $i )
	{
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		
                if ( $this->ChaPutOnItemsMode == false )
                {
                    //Item X , Y
                    $cNeoSerialMemory->WriteWord( $this->Item_InvenX[$i] );
                    $cNeoSerialMemory->WriteWord( $this->Item_InvenY[$i] );
                    //?
                    $cNeoSerialMemory->WriteWord( $this->Item_DummyW1[$i] );
                    $cNeoSerialMemory->WriteWord( $this->Item_DummyW2[$i] );
                }
                
		//Item Main , Sub
		$cNeoSerialMemory->WriteWord( $this->Item_InvenMain[$i] );
		$cNeoSerialMemory->WriteWord( $this->Item_InvenSub[$i] );
		//?
		$cNeoSerialMemory->WriteWord( $this->Item_DummyW3[$i] );
		$cNeoSerialMemory->WriteWord( $this->Item_DummyW4[$i] );
		//time
		$cNeoSerialMemory->WriteInt( $this->Item_tBORNTIME[$i] );
		$cNeoSerialMemory->WriteInt( $this->Item_tDISGUISE[$i] );
		//?
		$cNeoSerialMemory->WriteInt( $this->Item_DummyN1[$i] );
		$cNeoSerialMemory->WriteInt( $this->Item_DummyN2[$i] );
		//
		$cNeoSerialMemory->WriteByte( 1 );
		$cNeoSerialMemory->WriteByte( 0 );
		$cNeoSerialMemory->WriteByte( 0 );
		$cNeoSerialMemory->WriteByte( 0 );
		$cNeoSerialMemory->WriteByte( 0 );
		$cNeoSerialMemory->WriteByte( 0 );
		$cNeoSerialMemory->WriteByte( 0 );
		$cNeoSerialMemory->WriteByte( 0 );
		//trun num
		$cNeoSerialMemory->WriteWord( $this->Item_wTrunNum[$i] );
		//GenType
		$cNeoSerialMemory->WriteByte( $this->Item_cGenType[$i] );
		//ChnID
		$cNeoSerialMemory->WriteByte( $this->Item_cChnID[$i] );
		//cFieldID
		$cNeoSerialMemory->WriteByte( $this->Item_cFieldID[$i] );
		//Damage
		$cNeoSerialMemory->WriteByte( $this->Item_cDamage[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cDefense[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cResist_fire[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cResist_ice[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cResist_elec[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cResist_poison[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cResist_spirit[$i] );
		//OptType
		$cNeoSerialMemory->WriteByte( $this->Item_cOptType1[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cOptType2[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cOptType3[$i] );
		$cNeoSerialMemory->WriteByte( $this->Item_cOptType4[$i] );
		$cNeoSerialMemory->WriteWord( $this->Item_nOptVALUE1[$i] );
		$cNeoSerialMemory->WriteWord( $this->Item_nOptVALUE2[$i] );
		$cNeoSerialMemory->WriteWord( $this->Item_nOptVALUE3[$i] );
		$cNeoSerialMemory->WriteWord( $this->Item_nOptVALUE4[$i] );
		
		self::SetBinaryItemSlot( $i , $cNeoSerialMemory->GetBuffer() );
	}
	
	public function LoadHead_0112( &$cNeoSerialMemory )
	{
		$this->ItemSlotNum = $cNeoSerialMemory->ReadInt();
	}
	
	public function SaveHead_0112( &$cNeoSerialMemory )
	{
		$cNeoSerialMemory->WriteInt( CHAINVEN_VERSION );
		$cNeoSerialMemory->WriteInt( 64 ); // ???
		$cNeoSerialMemory->WriteInt( $this->ItemSlotNum );
	}
	
	public function LoadNode_0112( $i , &$cNeoSerialMemory )
	{
                        if ( $this->ChaPutOnItemsMode == false )
                        {
                            //Item X , Y
                            $this->Item_InvenX[$i] = $cNeoSerialMemory->ReadWord();
                            $this->Item_InvenY[$i] = $cNeoSerialMemory->ReadWord();
                            //?
                            $this->Item_DummyW1[$i] = $cNeoSerialMemory->ReadWord();
                            $this->Item_DummyW2[$i] = $cNeoSerialMemory->ReadWord();
                        }
			//Item Main , Sub
			$this->Item_InvenMain[$i] = $cNeoSerialMemory->ReadWord();
			$this->Item_InvenSub[$i] = $cNeoSerialMemory->ReadWord();
			//?
			$this->Item_DummyW3[$i] = $cNeoSerialMemory->ReadWord();
			$this->Item_DummyW4[$i] = $cNeoSerialMemory->ReadWord();
			//time
			$this->Item_tBORNTIME[$i] = $cNeoSerialMemory->ReadInt();
			$this->Item_tDISGUISE[$i] = $cNeoSerialMemory->ReadInt();
			//?
			$this->Item_DummyN1[$i] = $cNeoSerialMemory->ReadInt();
			$this->Item_DummyN2[$i] = $cNeoSerialMemory->ReadInt();
			//
			$cNeoSerialMemory->ReadByte(); // ?
			$cNeoSerialMemory->ReadByte(); // ?
			$cNeoSerialMemory->ReadByte(); // ?
			$cNeoSerialMemory->ReadByte(); // ?
			$cNeoSerialMemory->ReadByte(); // ?
			$cNeoSerialMemory->ReadByte(); // ?
			$cNeoSerialMemory->ReadByte(); // ?
			$cNeoSerialMemory->ReadByte(); // ?
			//trun num
			$this->Item_wTrunNum[$i] =  $cNeoSerialMemory->ReadWord();
			//GenType
			$this->Item_cGenType[$i] =  $cNeoSerialMemory->ReadByte();
			//ChnID
			$this->Item_cChnID[$i] =  $cNeoSerialMemory->ReadByte();
			//cFieldID
			$this->Item_cFieldID[$i] =  $cNeoSerialMemory->ReadByte();
			//Damage
			$this->Item_cDamage[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cDefense[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cResist_fire[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cResist_ice[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cResist_elec[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cResist_poison[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cResist_spirit[$i] =  $cNeoSerialMemory->ReadByte();
			//OptType
			$this->Item_cOptType1[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cOptType2[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cOptType3[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_cOptType4[$i] =  $cNeoSerialMemory->ReadByte();
			$this->Item_nOptVALUE1[$i] =  $cNeoSerialMemory->ReadWord();
			$this->Item_nOptVALUE2[$i] =  $cNeoSerialMemory->ReadWord();
			$this->Item_nOptVALUE3[$i] =  $cNeoSerialMemory->ReadWord();
			$this->Item_nOptVALUE4[$i] =  $cNeoSerialMemory->ReadWord();
	}
	
	public function Load_0112( &$cNeoSerialMemory )
	{
		//slot size 128 byte
		$cNeoSerialMemory->SetSeekNow();
		for( $i = 0 ; $i < $this->ItemSlotNum ; $i++ )
		{
			$this->Binary_ItemSlot[$i] = $cNeoSerialMemory->ReadBuffer( CHAINVEN_ITEM_SLOT );
			//echo $this->Binary_ItemSlot[$i]."<br>";
		}
		$cNeoSerialMemory->SetToDefault();
		for( $i = 0 ; $i < $this->ItemSlotNum ; $i++ )
		{
			self::LoadNode_0112( $i , $cNeoSerialMemory );
			//printf( "%s %s <br>",$this->Item_InvenMain[$i],$this->Item_InvenSub[$i] );
		}
	}
	
	public function Save_0112( &$cNeoSerialMemory )
	{
		//24 byte
		self::SaveHead_0112( $cNeoSerialMemory );
		for( $i = 0 ; $i < $this->ItemSlotNum ; $i++ )
		{
			//128 byte item slot
			//self::UpdateBinaryItemSlot( $i );
			$cNeoSerialMemory->WriteBuffer( self::GetBinaryItemSlot($i) );
		}
	}
        
        public function LoadChaPutOnItems( $Binary )
	{
                $this->ChaPutOnItemsMode = true;
                
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteBuffer( $Binary );
		self::SetBinary( $Binary );
		//echo $Binary."<br>\n";
		$Binary_Header = $cNeoSerialMemory->ReadBuffer( CHAINVEN_HEAD );
		self::SetHeader( $Binary_Header );
		
		$cNeoSerialMemory2 = new CNeoSerialMemory;
		$cNeoSerialMemory2->OpenMemory(  );
		$cNeoSerialMemory2->WriteBuffer( self::GetHeader() );
		$this->Version = $cNeoSerialMemory2->ReadInt();
		$cNeoSerialMemory2->ReadInt();
		
		if ( self::GetVersion() == CHAINVEN_VERSION )
		{
			switch( $this->Version )
			{
				case CHAINVEN_VERSION:
				{
					self::LoadHead_0112( $cNeoSerialMemory2 );
					self::Load_0112( $cNeoSerialMemory );
				}
				break;
			}
		return true;
		}
		else
		{
			self::ClearBuffer();
			return false;
		}
	}
	
	public function LoadChaInven( $Binary )
	{
                $this->ChaPutOnItemsMode = false;
                
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		$cNeoSerialMemory->WriteBuffer( $Binary );
		self::SetBinary( $Binary );
		//echo $Binary."<br>\n";
		$Binary_Header = $cNeoSerialMemory->ReadBuffer( CHAINVEN_HEAD );
		self::SetHeader( $Binary_Header );
		
		$cNeoSerialMemory2 = new CNeoSerialMemory;
		$cNeoSerialMemory2->OpenMemory(  );
		$cNeoSerialMemory2->WriteBuffer( self::GetHeader() );
		$this->Version = $cNeoSerialMemory2->ReadInt();
		$cNeoSerialMemory2->ReadInt();
		
		if ( self::GetVersion() == CHAINVEN_VERSION )
		{
			switch( $this->Version )
			{
				case CHAINVEN_VERSION:
				{
					self::LoadHead_0112( $cNeoSerialMemory2 );
					self::Load_0112( $cNeoSerialMemory );
				}
				break;
			}
		return true;
		}
		else
		{
			self::ClearBuffer();
			return false;
		}
	}
        
        public function SaveChaPutOnItems()
	{
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		
		self::Save_0112( $cNeoSerialMemory );
		
		$pBuffer = $cNeoSerialMemory->GetBuffer();
		
		if ( $this->ChaNum <= 0 || $pBuffer == "" ) return false;
                
                //echo "BB".$this->MemNum;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $this->MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf("UPDATE ChaInfo SET ChaPutOnItems = 0x%s WHERE ChaNum = %d",$pBuffer,$this->ChaNum);
		$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
		$cNeoSQLConnectODBC->CloseRanGame();
		return $pBuffer;
	}
	
	public function SaveChaInven()
	{
		$cNeoSerialMemory = new CNeoSerialMemory;
		$cNeoSerialMemory->OpenMemory(  );
		
		self::Save_0112( $cNeoSerialMemory );
		
		return $cNeoSerialMemory->GetBuffer();
	}
}
?>