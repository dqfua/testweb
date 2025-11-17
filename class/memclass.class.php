<?php
class CMemClass
{
	public $MemNum = 0;
	public $Class1_On = false;
	public $Class1_Name = "";
	public $Class2_On = false;
	public $Class2_Name = "";
	public $Class4_On = false;
	public $Class4_Name = "";
	public $Class8_On = false;
	public $Class8_Name = "";
	public $Class16_On = false;
	public $Class16_Name = "";
	public $Class32_On = false;
	public $Class32_Name = "";
	public $Class64_On = false;
	public $Class64_Name = "";
	public $Class128_On = false;
	public $Class128_Name = "";
	public $Class256_On = false;
	public $Class256_Name = "";
	public $Class512_On = false;
	public $Class512_Name = "";
	public $Class1024_On = false;
	public $Class1024_Name = "";
	public $Class2048_On = false;
	public $Class2048_Name = "";
        public $Class4096_On = false;
	public $Class4096_Name = "";
        public $Class8192_On = false;
	public $Class8192_Name = "";
	
        public $ClassOn_Arr = array(  );
        public $ClassName_Arr = array(  );
		
        protected function DataToArray()
        {
            $this->ClassOn_Arr[ 1 ] = $this->Class1_On;
            $this->ClassName_Arr[ 1 ] = $this->Class1_Name;
            
            $this->ClassOn_Arr[ 2 ] = $this->Class2_On;
            $this->ClassName_Arr[ 2 ] = $this->Class2_Name;
            
            $this->ClassOn_Arr[ 1 ] = $this->Class1_On;
            $this->ClassName_Arr[ 1 ] = $this->Class1_Name;
            
            $this->ClassOn_Arr[ 4 ] = $this->Class4_On;
            $this->ClassName_Arr[ 4 ] = $this->Class4_Name;
            
            $this->ClassOn_Arr[ 8 ] = $this->Class8_On;
            $this->ClassName_Arr[ 8 ] = $this->Class8_Name;
            
            $this->ClassOn_Arr[ 16 ] = $this->Class16_On;
            $this->ClassName_Arr[ 16 ] = $this->Class16_Name;
            
            $this->ClassOn_Arr[ 32 ] = $this->Class32_On;
            $this->ClassName_Arr[ 32 ] = $this->Class32_Name;
            
            $this->ClassOn_Arr[ 64 ] = $this->Class64_On;
            $this->ClassName_Arr[ 64 ] = $this->Class64_Name;
            
            $this->ClassOn_Arr[ 128 ] = $this->Class128_On;
            $this->ClassName_Arr[ 128 ] = $this->Class128_Name;
            
            $this->ClassOn_Arr[ 256 ] = $this->Class256_On;
            $this->ClassName_Arr[ 256 ] = $this->Class256_Name;
            
            $this->ClassOn_Arr[ 512 ] = $this->Class512_On;
            $this->ClassName_Arr[ 512 ] = $this->Class512_Name;
			
            $this->ClassOn_Arr[ 1024 ] = $this->Class1024_On;
            $this->ClassName_Arr[ 1024 ] = $this->Class1024_Name;
			
            $this->ClassOn_Arr[ 2048 ] = $this->Class2048_On;
            $this->ClassName_Arr[ 2048 ] = $this->Class2048_Name;
            
            $this->ClassOn_Arr[ 4096 ] = $this->Class4096_On;
            $this->ClassName_Arr[ 4096 ] = $this->Class4096_Name;
            
            $this->ClassOn_Arr[ 8192 ] = $this->Class8192_On;
            $this->ClassName_Arr[ 8192 ] = $this->Class8192_Name;
        }
	public function Reset()
	{
		$this->Class1_On = false;
		$this->Class1_Name = "";
		$this->Class2_On = false;
		$this->Class2_Name = "";
		$this->Class4_On = false;
		$this->Class4_Name = "";
		$this->Class8_On = false;
		$this->Class8_Name = "";
		$this->Class16_On = false;
		$this->Class16_Name = "";
		$this->Class32_On = false;
		$this->Class32_Name = "";
		$this->Class64_On = false;
		$this->Class64_Name = "";
		$this->Class128_On = false;
		$this->Class128_Name = "";
		$this->Class256_On = false;
		$this->Class256_Name = "";
		$this->Class512_On = false;
		$this->Class512_Name = "";
		$this->Class1024_On = false;
		$this->Class1024_Name = "";
		$this->Class2048_On = false;
		$this->Class2048_Name = "";
                $this->Class4096_On = false;
		$this->Class4096_Name = "";
                $this->Class8192_On = false;
		$this->Class8192_Name = "";
                self::DataToArray();
	}
	public function UpdateDB( )
	{
		global $_CONFIG;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "
						  UPDATE MemChangeClass SET 
						  Class1_On = %d
						  ,Class1_Name = '%s'
						  ,Class2_On = %d
						  ,Class2_Name = '%s'
						  ,Class4_On = %d
						  ,Class4_Name = '%s'
						  ,Class8_On = %d
						  ,Class8_Name = '%s'
						  ,Class16_On = %d
						  ,Class16_Name = '%s'
						  ,Class32_On = %d
						  ,Class32_Name = '%s'
						  ,Class64_On = %d
						  ,Class64_Name = '%s'
						  ,Class128_On = %d
						  ,Class128_Name = '%s'
						  ,Class256_On = %d
						  ,Class256_Name = '%s'
						  ,Class512_On = %d
						  ,Class512_Name = '%s'
						  ,Class1024_On = %d
						  ,Class1024_Name = '%s'
						  ,Class2048_On = %d
						  ,Class2048_Name = '%s'
                                                  ,Class4096_On = %d
						  ,Class4096_Name = '%s'
                                                  ,Class8192_On = %d
						  ,Class8192_Name = '%s'
						  WHERE MemNum = %d
						  "
						  
						  ,$this->Class1_On
						  ,$this->Class1_Name
						  ,$this->Class2_On
						  ,$this->Class2_Name
						  ,$this->Class4_On
						  ,$this->Class4_Name
						  ,$this->Class8_On
						  ,$this->Class8_Name
						  ,$this->Class16_On
						  ,$this->Class16_Name
						  ,$this->Class32_On
						  ,$this->Class32_Name
						  ,$this->Class64_On
						  ,$this->Class64_Name
						  ,$this->Class128_On
						  ,$this->Class128_Name
						  ,$this->Class256_On
						  ,$this->Class256_Name
						  ,$this->Class512_On
						  ,$this->Class512_Name
						  ,$this->Class1024_On
						  ,$this->Class1024_Name
						  ,$this->Class2048_On
						  ,$this->Class2048_Name
                                                  ,$this->Class4096_On
						  ,$this->Class4096_Name
                                                  ,$this->Class8192_On
						  ,$this->Class8192_Name
						  
						  ,$this->MemNum
						  );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		//echo $szTemp."<br>";
		$cNeoSQLConnectODBC->CloseRanWeb();
                self::DataToArray();
	}
	public function LoadData( $MemNum )
	{
		global $_CONFIG;
                
                //echo $MemNum."<br>";
		
		$bWork = false;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT
						  Class1_On,Class1_Name
						  ,Class2_On,Class2_Name
						  ,Class4_On,Class4_Name
						  ,Class8_On,Class8_Name
						  ,Class16_On,Class16_Name
						  ,Class32_On,Class32_Name
						  ,Class64_On,Class64_Name
						  ,Class128_On,Class128_Name
						  ,Class256_On,Class256_Name
						  ,Class512_On,Class512_Name
						  ,Class1024_On,Class1024_Name
						  ,Class2048_On,Class2048_Name
                                                  ,Class4096_On,Class4096_Name
                                                  ,Class8192_On,Class8192_Name
						  FROM MemChangeClass WHERE MemNum = %d
						  ",$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->Class1_On = $cNeoSQLConnectODBC->Result( "Class1_On" , ODBC_RETYPE_INT );
			$this->Class1_Name = $cNeoSQLConnectODBC->Result( "Class1_Name" , ODBC_RETYPE_THAI );
			$this->Class2_On = $cNeoSQLConnectODBC->Result( "Class2_On" , ODBC_RETYPE_INT );
			$this->Class2_Name = $cNeoSQLConnectODBC->Result( "Class2_Name" , ODBC_RETYPE_THAI );
			$this->Class4_On = $cNeoSQLConnectODBC->Result( "Class4_On" , ODBC_RETYPE_INT );
			$this->Class4_Name = $cNeoSQLConnectODBC->Result( "Class4_Name" , ODBC_RETYPE_THAI );
			$this->Class8_On = $cNeoSQLConnectODBC->Result( "Class8_On" , ODBC_RETYPE_INT );
			$this->Class8_Name = $cNeoSQLConnectODBC->Result( "Class8_Name" , ODBC_RETYPE_THAI );
			$this->Class16_On = $cNeoSQLConnectODBC->Result( "Class16_On" , ODBC_RETYPE_INT );
			$this->Class16_Name = $cNeoSQLConnectODBC->Result( "Class16_Name" , ODBC_RETYPE_THAI );
			$this->Class32_On = $cNeoSQLConnectODBC->Result( "Class32_On" , ODBC_RETYPE_INT );
			$this->Class32_Name = $cNeoSQLConnectODBC->Result( "Class32_Name" , ODBC_RETYPE_THAI );
			$this->Class64_On = $cNeoSQLConnectODBC->Result( "Class64_On" , ODBC_RETYPE_INT );
			$this->Class64_Name = $cNeoSQLConnectODBC->Result( "Class64_Name" , ODBC_RETYPE_THAI );
			$this->Class128_On = $cNeoSQLConnectODBC->Result( "Class128_On" , ODBC_RETYPE_INT );
			$this->Class128_Name = $cNeoSQLConnectODBC->Result( "Class128_Name" , ODBC_RETYPE_THAI );
			$this->Class256_On = $cNeoSQLConnectODBC->Result( "Class256_On" , ODBC_RETYPE_INT );
			$this->Class256_Name = $cNeoSQLConnectODBC->Result( "Class256_Name" , ODBC_RETYPE_THAI );
			$this->Class512_On = $cNeoSQLConnectODBC->Result( "Class512_On" , ODBC_RETYPE_INT );
			$this->Class512_Name = $cNeoSQLConnectODBC->Result( "Class512_Name" , ODBC_RETYPE_THAI );
			$this->Class1024_On = $cNeoSQLConnectODBC->Result( "Class1024_On" , ODBC_RETYPE_INT );
			$this->Class1024_Name = $cNeoSQLConnectODBC->Result( "Class1024_Name" , ODBC_RETYPE_THAI );
			$this->Class2048_On = $cNeoSQLConnectODBC->Result( "Class2048_On" , ODBC_RETYPE_INT );
			$this->Class2048_Name = $cNeoSQLConnectODBC->Result( "Class2048_Name" , ODBC_RETYPE_THAI );
                        $this->Class4096_On = $cNeoSQLConnectODBC->Result( "Class4096_On" , ODBC_RETYPE_INT );
			$this->Class4096_Name = $cNeoSQLConnectODBC->Result( "Class4096_Name" , ODBC_RETYPE_THAI );
                        $this->Class8192_On = $cNeoSQLConnectODBC->Result( "Class8192_On" , ODBC_RETYPE_INT );
			$this->Class8192_Name = $cNeoSQLConnectODBC->Result( "Class8192_Name" , ODBC_RETYPE_THAI );
                        
                        $this->Class1_Name = CBinaryCover::tis620_to_utf8( $this->Class1_Name );
                        $this->Class2_Name = CBinaryCover::tis620_to_utf8( $this->Class2_Name );
                        $this->Class4_Name = CBinaryCover::tis620_to_utf8( $this->Class4_Name );
                        $this->Class8_Name = CBinaryCover::tis620_to_utf8( $this->Class8_Name );
                        $this->Class16_Name = CBinaryCover::tis620_to_utf8( $this->Class16_Name );
                        $this->Class32_Name = CBinaryCover::tis620_to_utf8( $this->Class32_Name );
                        $this->Class64_Name = CBinaryCover::tis620_to_utf8( $this->Class64_Name );
                        $this->Class128_Name = CBinaryCover::tis620_to_utf8( $this->Class128_Name );
                        $this->Class256_Name = CBinaryCover::tis620_to_utf8( $this->Class256_Name );
                        $this->Class512_Name = CBinaryCover::tis620_to_utf8( $this->Class512_Name );
                        $this->Class1024_Name = CBinaryCover::tis620_to_utf8( $this->Class1024_Name );
                        $this->Class2048_Name = CBinaryCover::tis620_to_utf8( $this->Class2048_Name );
                        $this->Class4096_Name = CBinaryCover::tis620_to_utf8( $this->Class4096_Name );
                        $this->Class8192_Name = CBinaryCover::tis620_to_utf8( $this->Class8192_Name );
                        
			$bWork = true;
		}
		if ( !$bWork )
		{
			$szTemp = sprintf( "INSERT INTO
							  MemChangeClass( MemNum
											 , Class1_On , Class1_Name
											 , Class2_On , Class2_Name
											 , Class4_On , Class4_Name
											 , Class8_On , Class8_Name
											 , Class16_On , Class16_Name
											 , Class32_On , Class32_Name
											 , Class64_On , Class64_Name
											 , Class128_On , Class128_Name
											 , Class256_On , Class256_Name
											 , Class512_On , Class512_Name
											 , Class1024_On , Class1024_Name
											 , Class2048_On , Class2048_Name
                                                                                         , Class4096_On , Class4096_Name
                                                                                         , Class8192_On , Class8192_Name
											 )
							  VALUES( %d
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
									 , %d , '%s'
                                                                         , %d , '%s'
                                                                         , %d , '%s'
									 )
						  "
						  ,$MemNum
						  , 1 , $_CONFIG["CHACLASS"][1]
						  , 1 , $_CONFIG["CHACLASS"][2]
						  , 1 , $_CONFIG["CHACLASS"][4]
						  , 1 , $_CONFIG["CHACLASS"][8]
						  , 1 , $_CONFIG["CHACLASS"][16]
						  , 1 , $_CONFIG["CHACLASS"][32]
						  , 1 , $_CONFIG["CHACLASS"][64]
						  , 1 , $_CONFIG["CHACLASS"][128]
						  , 1 , $_CONFIG["CHACLASS"][256]
						  , 1 , $_CONFIG["CHACLASS"][512]
						  , 1 , $_CONFIG["CHACLASS"][1024]
						  , 1 , $_CONFIG["CHACLASS"][2048]
                                                  , 1 , $_CONFIG["CHACLASS"][4096]
                                                  , 1 , $_CONFIG["CHACLASS"][8192]
						  );
                        //echo $szTemp."<br>";
			$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
			$szTemp = sprintf( "SELECT
							  Class1_On,Class1_Name
							  ,Class2_On,Class2_Name
							  ,Class4_On,Class4_Name
							  ,Class8_On,Class8_Name
							  ,Class16_On,Class16_Name
							  ,Class32_On,Class32_Name
							  ,Class64_On,Class64_Name
							  ,Class128_On,Class128_Name
							  ,Class256_On,Class256_Name
							  ,Class512_On,Class512_Name
							  ,Class1024_On,Class1024_Name
							  ,Class2048_On,Class2048_Name
                                                          ,Class4096_On,Class4096_Name
                                                          ,Class8192_On,Class8192_Name
							  FROM MemChangeClass WHERE MemNum = %d
							  ",$MemNum );
			$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
				$this->Class1_On = $cNeoSQLConnectODBC->Result( "Class1_On" );
				$this->Class1_Name = $cNeoSQLConnectODBC->Result( "Class1_Name" );
				$this->Class2_On = $cNeoSQLConnectODBC->Result( "Class2_On" );
				$this->Class2_Name = $cNeoSQLConnectODBC->Result( "Class2_Name" );
				$this->Class4_On = $cNeoSQLConnectODBC->Result( "Class4_On" );
				$this->Class4_Name = $cNeoSQLConnectODBC->Result( "Class4_Name" );
				$this->Class8_On = $cNeoSQLConnectODBC->Result( "Class8_On" );
				$this->Class8_Name = $cNeoSQLConnectODBC->Result( "Class8_Name" );
				$this->Class16_On = $cNeoSQLConnectODBC->Result( "Class16_On" );
				$this->Class16_Name = $cNeoSQLConnectODBC->Result( "Class16_Name" );
				$this->Class32_On = $cNeoSQLConnectODBC->Result( "Class32_On" );
				$this->Class32_Name = $cNeoSQLConnectODBC->Result( "Class32_Name" );
				$this->Class64_On = $cNeoSQLConnectODBC->Result( "Class64_On" );
				$this->Class64_Name = $cNeoSQLConnectODBC->Result( "Class64_Name" );
				$this->Class128_On = $cNeoSQLConnectODBC->Result( "Class128_On" );
				$this->Class128_Name = $cNeoSQLConnectODBC->Result( "Class128_Name" );
				$this->Class256_On = $cNeoSQLConnectODBC->Result( "Class256_On" );
				$this->Class256_Name = $cNeoSQLConnectODBC->Result( "Class256_Name" );
				$this->Class512_On = $cNeoSQLConnectODBC->Result( "Class512_On" );
				$this->Class512_Name = $cNeoSQLConnectODBC->Result( "Class512_Name" );
				$this->Class1024_On = $cNeoSQLConnectODBC->Result( "Class1024_On" );
				$this->Class1024_Name = $cNeoSQLConnectODBC->Result( "Class1024_Name" );
				$this->Class2048_On = $cNeoSQLConnectODBC->Result( "Class2048_On" );
				$this->Class2048_Name = $cNeoSQLConnectODBC->Result( "Class2048_Name" );
                                $this->Class4096_On = $cNeoSQLConnectODBC->Result( "Class4096_On" );
				$this->Class4096_Name = $cNeoSQLConnectODBC->Result( "Class4096_Name" );
                                $this->Class8192_On = $cNeoSQLConnectODBC->Result( "Class8192_On" );
				$this->Class8192_Name = $cNeoSQLConnectODBC->Result( "Class8192_Name" );
                                
				$bWork = true;
			}
		}
		$this->MemNum = $MemNum;
                self::DataToArray();
		$cNeoSQLConnectODBC->CloseRanWeb();
	}
}
?>