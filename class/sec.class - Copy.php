<?php
/*
=============================================================
				ระบบป้องกัน SQL INJECTION
				Delopment By NeoMasteI2
=============================================================
*/
define("CHAR","ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789_:");
define("BBCHAR","ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789[]=_\/|+-*.<>()%^$#@!: 
	");
define("NUMBER","0123456789");
class CNeoInject
{
	public static function Encode(&$str)
	{
		$str = base64_encode($str);
		return $str;
	}
	public static function Decode(&$str)
	{
		$str = base64_decode($str);
		return $str;
	}
	static public function ReplaceBBCode( $str )
	{
		global $_CONFIG;
		$szReturn = str_replace($_CONFIG["ANTIBB"],$_CONFIG["ANTIBB2"],$str);
		$szReturn = @self::sec_Thai( $szReturn );
		return $szReturn;
	}
	static public function ReplaceBBCode2Def( $str )
	{
		global $_CONFIG;
		$szReturn = str_replace($_CONFIG["ANTIBB2"],$_CONFIG["ANTIBB"],$str);
		return $szReturn;
	}
	public static function sec_Int($str)
	{
		$ret = "";
		$aSTR = str_split($str,1);
		$aCHAR = str_split(NUMBER,1);
		for( $i = 0 ; $i <= strlen($str) ; $i ++ )
		{
			for( $l = 0 ; $l <= strlen(NUMBER) ; $l ++ )
			{
				if ( $aCHAR[$l] == $aSTR[$i] )
				{
					$ret = $ret.$aSTR[$i];
				}
			}
		}
		return (int)$ret;
	}
	public static function sec_Int2($protected)
	{
		if ( $protected == "" ) return ;
		if ( @eregi ( "[0123456789]", $protected ) )
		{
			return $protected;
		} else {
			return ;
		}
	}
	public static function sec_NotWorkCheck($protected)
	{
		if ( $protected == "" ) return ;
		$banlist = array ("'",'"',"--",":"); 
		if ( @eregi ( "[ก-๛a-zA-Z0-9@: ]+", $protected ) )
		{
			$protected = trim(str_replace($banlist, '', $protected));
			return $protected;
		} else {
			return ;
		}
	}
	public static function sec_Eng($protected)
	{
		if ( $protected == "" ) return ;
		$banlist = array ("'", "\"", "<", "\\", "/", "|", "/", "=", "--", "insert", "select", "update", "delete", "distinct", "having", "truncate", "replace", "handler", "like", "procedure", "limit", "order by", "group by", "asc", "desc"); 
		if ( @eregi ( "[a-zA-Z0-9@: ]+", $protected ) )
		{
			$protected = trim(str_replace($banlist, '', $protected));
			return $protected;
		} else {
			return ;
		}
	}
	public static function sec_Thai($protected)
	{
		if ( $protected == "" ) return ;
		$banlist = array (" ", "	" , "'", "/", "\"", "<", "\\", "|", "=", "--", "insert", "select", "update", "delete", "distinct", "having", "truncate", "replace", "handler", "like", "procedure", "limit", "order by", "group by", "asc", "desc"); 
		if ( @eregi ( "[ก-๛a-zA-Z0-9@: ]+", $protected ) )
		{
			$protected = trim(str_replace($banlist, '', $protected));
			return $protected;
		} else {
			return ;
		}
	}
	public static function sec_MailEng($protected)
	{
		if ( $protected == "" ) return ;
		$banlist = array ("'", "\"", "<", "\\", "/", "|", "/", "=", "--", "insert", "select", "update", "delete", "distinct", "having", "truncate", "replace", "handler", "like", "procedure", "limit", "order by", "group by", "asc", "desc"); 
		if ( @eregi ( "[a-zA-Z0-9@: ]+", $protected ) )
		{
			$protected = trim(str_replace($banlist, '', $protected));
			return $protected;
		} else {
			return ;
		}
	}
	public static function sec_Ban($protected)
	{
		if ( $protected == "" ) return ;
		$banlist = array ("GM","จีเอม"); 
		if ( @eregi ( "[ก-๛a-zA-Z0-9@: ]+", $protected ) )
		{
			$protected = trim(str_replace($banlist, '', $protected));
			return $protected;
		} else {
			return ;
		}
	}
}
?>