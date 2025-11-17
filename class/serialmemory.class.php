<?php
/*
Author : NeoMasteI2
E-Mail : x-cyber@windowslive.com
Programing Language PHP Development And Designer
Product Create Date : 2011 - 01 - 04
(c) 2011 Copyright By NeoMasteI2 All Right Reserved.
CNeoSerialMemory
*/
class CNeoSerialMemory
{
	private $SESSION_MEMORY;
	private $SESSION_MEMORY_BAK;

	protected function Write( $b )
	{
		$this->SESSION_MEMORY .= $b;
		$this->SESSION_MEMORY_BAK .= $b;
		return true;
	}
	protected function Read( $n )
	{
		$ret = substr( $this->SESSION_MEMORY , 0 , $n );
		$this->SESSION_MEMORY = substr( $this->SESSION_MEMORY , $n , strlen( $this->SESSION_MEMORY ) );;
		//echo "Binary : ".$this->SESSION_MEMORY."<br>";
		return $ret;
	}
        
        public function GetPointerPos()
        {
            return ( strlen( $this->SESSION_MEMORY_BAK ) - strlen( $this->SESSION_MEMORY ) );
        }

	public function OpenMemory(  )
	{
		$this->SESSION_MEMORY = "";
		$this->SESSION_MEMORY_BAK = "";
	}
	
	public function GetBuffer(){ return $this->SESSION_MEMORY; }
	public function GetDefaultBuffer(){ return $this->SESSION_MEMORY_BAK; }
	
	public function SetSeekNow()
	{
		$this->SESSION_MEMORY_BAK = $this->SESSION_MEMORY;
	}

	public function SetToDefault()
	{
		$this->SESSION_MEMORY = $this->SESSION_MEMORY_BAK;
	}

	public function CheckFree( )
	{
		if ( $this->SESSION_MEMORY == "" )
			return true;
		else
			return false;
	}
	public function WriteInt( $num )
	{
		self::Write( CNeoSerialFile::rcvBinary(CNeoSerialFile::check_DIGI(dechex($num),BIN_INT_LENGTH*2)) );
	}
	public function ReadInt( )
	{
		$n = self::Read( BIN_INT_LENGTH*2 );
		$n = CNeoSerialFile::rcvBinary($n);
		$n = hexdec($n);
		return $n;
	}
	public function WriteWord( $num )
	{
		self::Write( CNeoSerialFile::rcvBinary(CNeoSerialFile::check_DIGI(dechex($num),BIN_WORD_LENGTH*2)) );
	}
	public function ReadWord( )
	{
		$n = self::Read( BIN_WORD_LENGTH*2 );
		$n = CNeoSerialFile::rcvBinary($n);
		$n = hexdec($n);
		return $n;
	}
	public function WriteByte( $num )
	{
		self::Write( CNeoSerialFile::rcvBinary(CNeoSerialFile::check_DIGI(dechex($num),BIN_BYTE_LENGTH*2)) );
	}
	public function ReadByte( )
	{
		$n = self::Read( BIN_BYTE_LENGTH*2 );
		$n = CNeoSerialFile::rcvBinary($n);
		$n = hexdec($n);
		return $n;
	}
	public function WriteString( $string )
	{
		self::WriteInt( strlen( CGlobal::strToHex( $string ) ) );
		self::Write( CGlobal::strToHex( $string ) );
	}
	public function ReadString(  )
	{
		$n = self::ReadInt( );
		$ret = CGlobal::hexToStr( self::Read( $n ) );
		return $ret;
	}
	public function WriteBuffer( $b )
	{
		$this->SESSION_MEMORY .= $b;
		$this->SESSION_MEMORY_BAK .= $b;
		return true;
	}
	public function ReadBuffer( $n )
	{
		$ret = substr( $this->SESSION_MEMORY , 0 , $n );
		$this->SESSION_MEMORY = substr( $this->SESSION_MEMORY , $n , strlen( $this->SESSION_MEMORY ) );;
		return $ret;
	}
}
?>