<?php
//get from global supersafe
//$fbd_chr
//$sql_inject_1
class CNeoInject2
{
	public static function sec_Eng( $text )
	{
		global $fbd_chr;
		global $sql_inject_1;
		
		$text_return = str_replace( $fbd_chr , "" , $text );
		$text_return = str_replace( $sql_inject_1 , "" , $text_return );
		return addslashes(trim($text_return));
	}
	public static function sec_Thai( $text )
	{
		global $fbd_chr;
		global $sql_inject_1;
		
		$text_return = str_replace( $fbd_chr , "" , $text );
		$text_return = str_replace( $sql_inject_1 , "" , $text_return );
		return addslashes(trim($text_return));
	}
}
?>