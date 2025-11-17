<?php
/*
Author : NeoMasteI2
E-Mail : x-cyber@windowslive.com
Programing Language PHP Development And Designer
Product Create Date : 2011 - 01 - 04
© 2011 Copyright By NeoMasteI2 All Right Reserved.
Serial File :
*/
define("BIN_HEADER_DEFAULT","default");
define("BIN_HEADER_LENGTH",128);
define("BIN_VERSION_LENGTH",4);
define("BIN_INT_LENGTH",4);
define("BIN_WORD_LENGTH",2);
define("BIN_BYTE_LENGTH",1);
define("FOR_READ",0);
define("FOR_WRITE",1);
class CNeoSerialFile
{
	private $m_FileStream;

	public function hex2bin($str) {
		$bin = "";
		$i = 0;
		do {
			$bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
			$i += 2;
		} while ($i < strlen($str));
		return $bin;
	}
	public function hexbin($hex) { 
		return decbin(hexdec($hex)); 
	} 
	public function binhex($bin) { 
		return dechex(bindec($bin)); 
	} 
	public function hex2strhex($str){
		return chr(hexdec($str));
	}
	static public function check_DIGI( $str , $digi )
	{
		$r = "";
		$l = strlen($str);
		for( $i = 0 ; $i < $digi-$l ; $i ++ )
		{
			$r .= "0";
		}
		$r .= $str;
		return $r;
	}
	static public function rcvBinary($str)
	{
		$arr = str_split($str, 2);
		$c = count($arr);
		$nstr = "";
		for($i=$c;$i>=0;$i--){
			$nstr .= @$arr{$i};
		}
		return $nstr;
	}
	public function OpenFile( $for_type , $FilePath )
	{
		if ( $for_type == FOR_READ )
		{
			$this->m_FileStream = fopen($FilePath,"rb");
		}elseif ( $for_type == FOR_WRITE ){
			$this->m_FileStream = fopen($FilePath,"wb");
		}
		if ( !$this->m_FileStream ) return false;
		return true;
	}
	public function CloseFile()
	{
		fclose($this->m_FileStream);
	}
	protected function Read( $nSize )
	{
		if ( $nSize == NULL or $nSize == 0 ) return ;
		return fread($this->m_FileStream,$nSize);
	}
	protected function Write( $Buffer )
	{
		return fwrite($this->m_FileStream,$Buffer);
	}
	public function ReadByte( &$n )
	{
		$n = self::Read( BIN_BYTE_LENGTH );
		$n = bin2hex($n);
		$n = self::rcvBinary($n);
		$n = hexdec($n);
	}
	public function WriteByte( $n )
	{
		self::Write( hex2bin(self::rcvBinary(self::check_DIGI(dechex($n),BIN_BYTE_LENGTH*2))) );
	}
	public function ReadWord( &$n )
	{
		$n = self::Read( BIN_WORD_LENGTH );
		$n = bin2hex($n);
		$n = self::rcvBinary($n);
		$n = hexdec($n);
	}
	public function WriteWord( $n )
	{
		self::Write( hex2bin(self::rcvBinary(self::check_DIGI(dechex($n),BIN_WORD_LENGTH*2))) );
	}
	public function ReadInt( &$n )
	{
		$n = self::Read( BIN_INT_LENGTH );
		$n = bin2hex($n);
		$n = self::rcvBinary($n);
		$n = hexdec($n);
	}
	public function WriteInt( $n )
	{
		self::Write( hex2bin(self::rcvBinary(self::check_DIGI(dechex($n),BIN_INT_LENGTH*2))) );
	}
	public function ReadBuffer( &$Buff , $Bufflen )
	{
		$Buff = self::Read($Bufflen);
	}
	public function WriteBuffer( $BufferType )
	{
		self::Write($BufferType);
	}
	public function ReadString( &$buff )
	{
		$len = 0;
		self::ReadInt($len);
		self::ReadBuffer( $buff , $len );
	}
	public function WriteString( $str , $strlen )
	{
		self::WriteInt( $strlen );
		self::WriteBuffer( $str );
	}
	public function ReadFileType( &$BufferType , &$nVersion )
	{
		$BufferType = self::Read( BIN_HEADER_LENGTH );
		$BufferType = str_replace( hex2strhex("00") , "" , $BufferType );
		$nVersion = self::Read( BIN_VERSION_LENGTH );
	}
	public function WriteFileType( $BufferType , $nVersion )
	{
		$aBuffer = str_split($BufferType,1);
		for( $i = 0 ; $i < BIN_HEADER_LENGTH ; $i++ )
		{
			if ( $i < strlen($BufferType) )
				self::Write($aBuffer[$i]);
			else
				self::Write(self::hex2strhex("00"));
		}
		self::WriteInt( $nVersion );
	}
}
?>