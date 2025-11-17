<?php
class CSerial
{
	static public function SerialEnc( $serial )
	{
		return serialize( $serial );
	}
	static public function SerialDec( $encode )
	{
		return unserialize( $encode );
	}
}
?>