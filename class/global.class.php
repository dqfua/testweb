<?php
define("ID_LENGTH",16);
define("PASSMD5_LENGTH",19);
define("CONEITEMCODE_LENGTH",30);

class IDShopData
{
	public $MemNum = 0;
	public $ServerName = 0;
};

class _tdata
{
    protected $pData = array();
    protected $nData = 0;
    
    private $ppData = array();

    public function __construct() {
        ;
    }
    
    public function GetData( $index ){ return $this->pData[ $index ]; }
    public function GetRollData() { return $this->nData; }

    public function AddData( $arr , $val )
    {
        $this->ppData[ $arr ] = $val;
    }
    
    public function SetData( $i , $arr , $val )
    {
        $arrData = $this->pData[ $i ];
        $arrData[$arr] = $val;
        $this->pData[ $i ] = $arrData;
    }
    
    public function ErasePop()
    {
        array_pop($this->pData);
        $this->nData--;
    }
    
    public function NextData()
    {
        $this->pData[ $this->nData ] = $this->ppData;
        $this->ppData = array();
        $this->nData++;
    }
}

class CGlobal
{
	static public function GetSessionShopIDData( )
	{
		return ( SESSION_MEMBERSHOP );
	}
	
	static public function MemNum2ConeSerial( $MemNum , $MemberID )
	{
		return substr( strtoupper( md5( md5($MemNum) . md5($MemberID) ) ) , 0 , CONEITEMCODE_LENGTH );
	}
	
	// แก้ไข: เพิ่ม static และแก้ signature ให้ return ค่า
	static public function DisCharHtml( $Text )
	{
		if ( strrpos( $Text , "&" ) !== false )
		{
			$last_t = strrpos($Text,'&');
			$str_check = substr( $Text , $last_t );
			$ag = @array_keys( @$sql_inject_2 );
			$bbWORK = false;
			for($i=0;$i<count($ag);$i++)
			{
				if ( strcmp( $ag[$i] , "&" . $str_check ) == 0 )
				{
					$bbWORK = TRUE;
					break;
				}
			}
			if ( $bbWORK == false )
			{
				$Text = substr( $Text , 0 , $last_t );
			}
		}
		// เพิ่ม return เพื่อให้ส่งค่ากลับ
		return $Text;
	}
	
	public function _format_bytes($a_bytes)
	{
		if ($a_bytes < 1024) {
			return $a_bytes .' B';
		} elseif ($a_bytes < 1048576) {
			return round($a_bytes / 1024, 2) .' KiB';
		} elseif ($a_bytes < 1073741824) {
			return round($a_bytes / 1048576, 2) . ' MiB';
		} elseif ($a_bytes < 1099511627776) {
			return round($a_bytes / 1073741824, 2) . ' GiB';
		} elseif ($a_bytes < 1125899906842624) {
			return round($a_bytes / 1099511627776, 2) .' TiB';
		} elseif ($a_bytes < 1152921504606846976) {
			return round($a_bytes / 1125899906842624, 2) .' PiB';
		} elseif ($a_bytes < 1180591620717411303424) {
			return round($a_bytes / 1152921504606846976, 2) .' EiB';
		} elseif ($a_bytes < 1208925819614629174706176) {
			return round($a_bytes / 1180591620717411303424, 2) .' ZiB';
		} else {
			return round($a_bytes / 1208925819614629174706176, 2) .' YiB';
		}
	}
	
	public function bytetomb($a_bytes)
	{
		return round($a_bytes / 1048576, 2);
	}
	
	static public function xmlFolderCheckOut( $dpath /* = "ranmiko" */ )
	{
		$path = "..//" . $dpath . "//" . XML_FOLDER_OUT;
		if ( !is_dir($path) )
			mkdir($path, 0777);
		if ( !file_exists( $path . "//index.html" ) )
		{
			$pFile = fopen( $path . "//index.html" , "w" );
			if ( $pFile )
				fclose( $pFile );
		}
	}
	static public function xmlGetPathOut( $dpath /* = "ranmiko" */ )
	{
		$path = "..//" . $dpath . "//" . XML_FOLDER_OUT;
		return $path . "//" . XML_FOLDER_FILE_OUT;
	}
	static public function xmlGetZipPathOut( $dpath /* = "ranmiko" */ )
	{
		$path = "..//" . $dpath . "//" . XML_FOLDER_OUT;
		return $path . "//" . XML_FOLDER_FILE_ZIP_OUT;
	}
	static public function xmlToZipFile( $inpath , $outpath )
	{
		$zip = new ZipArchive;
		if ($zip->open($outpath, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE ) === TRUE)
		{
			$zip->addFile($inpath, XML_FOLDER_FILE_OUT);
			$zip->close();
		}
	}
        static public function curPageURL()
        {
            $pageURL = 'http';
            if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            } else {
            $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }
            return $pageURL;
        }
	static public function DoEncodeResellApp( $ChaInven , $RandNumTime , $SecCode )
	{
		$varreturn = "";
		$varreturn = md5( $ChaInven . $RandNumTime , $SecCode );
	}
	/*
	// not work
	static public function FileTypeCheck( $string , $array )
	{
		foreach($array as $key=>$val)
		{
			if ( $array[$key] == $szType )
			{
				return true;
			}
		}
		return false;
	}
	*/
	static public function DateDiff($strDate1,$strDate2)
	 {
				return (strtotime($strDate2) - strtotime($strDate1))/  ( 60 * 60 * 24 );  // 1 day = 60*60*24
	 }
	 static public function TimeDiff($strTime1,$strTime2)
	 {
				return (strtotime($strTime2) - strtotime($strTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
	 }
	 static public function DateTimeDiff($strDateTime1,$strDateTime2)
	 {
				return (strtotime($strDateTime2) - strtotime($strDateTime1))/  ( 60 * 60 ); // 1 Hour =  60*60
	 }
	static public function ResellQuery( $ItemPrice , $Item_Resell_Percent )
	{
		$Result = $ItemPrice-($ItemPrice*$Item_Resell_Percent/100);
		if ( $Result > $ItemPrice )
		{
			$Result = $ItemPrice;
		}
		return floor( $Result );
	}
	static public function strToHex($string)
	{
		$hex='';
		for ($i=0; $i < strlen($string); $i++)
		{
			$hex .= dechex(ord($string[$i]));
		}
		return $hex;
	}
	static public function hexToStr($hex)
	{
		$string='';
		for ($i=0; $i < strlen($hex)-1; $i+=2)
		{
			$string .= chr(hexdec($hex[$i].$hex[$i+1]));
		}
		return $string;
	}
	static public function CheckLogOn( /*user.class.php*/ $cUser )
	{
		if ( !$cUser ) return false;
		$cUser = unserialize( self::GetSesUser() );
		$UserID = $cUser->GetUserID();
		$UserNum = $cUser->GetUserNum();
		if ( $UserNum <= 0 || $UserID == "" )
		{
			return false;
		}
		if ( !$cUser->CheckLogOn() )
		{
			return false;
		}
		return true;
	}
	static public function CheckLogOnAndClear( /*user.class.php*/ $cUser )
	{
		if ( !$cUser ) return false;
		$cUser = unserialize( self::GetSesUser() );
		$UserID = $cUser->GetUserID();
		$UserNum = $cUser->GetUserNum();
		if ( $UserNum <= 0 || $UserID == "" )
		{
			$cUser->Clear();
			CGlobal::SetSesUserLogin(OFFLINE);
			return false;
		}
		if ( !$cUser->CheckLogOn() )
		{
			$cUser->Clear();
			CGlobal::SetSesUserLogin(OFFLINE);
			return false;
		}
		return true;
	}
	static public function EncodeName( $txt )
	{
		$txt = base64_encode( $txt );
		return $txt;
	}
	static public function DecodeName( $txt )
	{
		$txt = base64_decode( $txt );
		return $txt;
	}
	static public function CheckNameTxt( $txt )
	{
		$txt  = trim( $txt  );
		$txt = @CNeoInject::sec_Thai( $txt );
		return $txt;
	}
	static public function SetPassMD5( &$pass , $md5 = true )
	{
		if ( $md5 )
		{
			$pass = substr( strtoupper( md5( $pass ) ) , 0 , self::GetPassLen() );
		}
	}
	
	static public function Time2Point( $time , $s_time , $s_point )
	{
		if ( $time <= 0 || $s_point <= 0 || $s_time <= 0 ){ return 0; }
		$rpoint = 0;
		$rpoint = ($time/$s_time)*$s_point;
		if ( $rpoint < 0 ) $rpoint = 0;
		$rpoint = floor( $rpoint );
		return $rpoint;
	}
	
	static function SetSesUser( $CUser ){ $_SESSION[self::GetSesMan()] = $CUser; }
	static function GetSesUser(  ){ return ( isset( $_SESSION[self::GetSesMan()] ) ) ? $_SESSION[self::GetSesMan()] : false; }
	
	static function SetSesUserLogin( $bool ){ $_SESSION[self::GetSesManLogin()] = $bool; }
	static function GetSesUserLogin(  ){ return ( isset( $_SESSION[self::GetSesManLogin()] ) ) ? $_SESSION[self::GetSesManLogin()] : OFFLINE; }
	
	static function CheckStrLen( $str ){ return ( strlen($str) < 2 || strlen($str) > 256 ) ? false : true ; }
	static function CheckStrManLen( $str , $len = ID_LENGTH ){ return ( strlen($str) < 4 || strlen($str) > $len ) ? false : true ; }
	static function CheckNumber( $num ){ return ( $num <=0 || empty($num) ) ? false : true ; }
	static function SetSes( $sesid , $value ){ $_SESSION[$sesid] = $value; }
	static function GetSes( $sesid )
	{
		//if ( isset( $_SESSION[$sesid] ) )
		if ( array_key_exists( $sesid , $_SESSION ) )
		{
			return $_SESSION[$sesid];
		}
		return NULL;
	}
	
	static function GetSesLoginOut() { return CGlobal::GetSes( SESSION_LOGIN_SESSIONOUT );}
	static function SetSesLoginOut( $value ) { $_SESSION[ SESSION_LOGIN_SESSIONOUT ] = $value; }
	
	static function GetSesMan(){ return SESSION_MAN; }
	static function GetSesManLogin(){ return SESSION_MAN_LOGIN; }
	
	static function GetSesChaMan(){ return SESSION_CHAMAN; }
	static function GetSesChaManLogin(){ return SESSION_CHAMAN_LOGIN; }
	
	static function SetSesChaMan( $Neo ){ $_SESSION[self::SESSION_CHAMAN()] = $Neo; }
	static function SetSesChaManLogin( $bool ){ $_SESSION[self::SESSION_CHAMAN_LOGIN()] = $bool; }
	
	static function GetSesAdmin(){ return SESSION_ADMIN; }
	static function GetSesAdminLogin(){ return SESSION_ADMIN_LOGIN; }
	
	static function GetPassLen(){ return PASSWORD_LENG; }
	
	static function getIP(){return $_SERVER['REMOTE_ADDR'];}
	
	static function get_file_extension($file_name) { return substr(strrchr($file_name,'.'),1); }
	static function get_file_name($file_name) { return substr(0,strrchr($file_name,'.')); }
	
	static public function __GET2( $s )
	{
		$result = @CNeoInject::sec_NotWorkCheck( addslashes( $_GET[$s] ) );
		$result = @CNeoInject2::sec_Thai( $result );
		if ( isset( $result ) )
		{
			return $result;
		}
		return "";
	}
	
	static public function __POST( $s )
	{
		$result = @CNeoInject::sec_NotWorkCheck( addslashes( $_POST[$s] ) );
		$result = @CNeoInject2::sec_Thai( $result );
		if ( isset( $result ) )
		{
			return $result;
		}
		return "";
	}
	
	static function gopageQ($page) {
		echo "<meta http-equiv='refresh' content='0.0000000000000000000000000000000000000001; URL={$page}'>";
	}
	static function gopage($page) {
		echo "<meta http-equiv='refresh' content='3; URL={$page}'>";
	}
	static function gopageMan($page,$m) {
		echo "<meta http-equiv='refresh' content='$m; URL={$page}'>";
	}
	
	/**
	* Convert date
	*
	* @author platoosom
	* @email platoosom@hotmail.com
	* @created 2008-06-04 11:15
	* @modified 2008-06-04 11:15
	*
	* input : array( 'begin'=>date/datetime , 'end'=>date/datetime )
	* output : string
	*/
	static public function compare_date( $array )
	{
 
		if( ! is_array( $array ) ){ return ;}
 
		if( ( ! array_key_exists( 'begin' , $array )) || empty( $array['begin'] )){ return ;}
 
		if( ( ! array_key_exists( 'end' , $array )) || empty( $array['end'] )){ return ;}
 
		$begin_time = strtotime( $array['begin'] );
		$end_time = strtotime( $array['end'] );
 
		$amount_time = $end_time - $begin_time ;
 
		$list = array(
								'day'=>array( 'วัน' , '86400' ) ,
								'hour'=>array( 'ชั่วโมง' , '3600' ) ,
								'munite'=>array( 'นาที' , '60' ) ,
								'second'=>array( 'วินาที' , '1' )
		);
 
		foreach( $list as $value ):
 
			$result = floor( $amount_time / $value[1] );
			if( $result > 0 ){ $return[] = $result; $return[] = $value[0]; }
 
			$amount_time = $amount_time % $value[1];
 
		endforeach;
 
		return implode( ' ' , $return ).' วินาที';
 
	}
	
	static function loadpagetime(){
		/**
		* Simple function to replicate PHP 5 behaviour
		*/
		function microtime_float()
		{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
		}
	
		$time_start = microtime_float();
	
		// Sleep for a while
		for($i=0;$i<10000;$i++) 
		{
		}
		// usleep(100);
	
		$time_end = microtime_float();
		$time = $time_end - $time_start;
		return $time;
	}
	
	static function BBCode($Text)
	{
		$Text = nl2br($Text);
		$Text = str_replace("<br>", "\n", $Text);			
		//$Text = str_replace("<", "&lt;", $Text);
		//$Text = str_replace(">", "&gt;", $Text);
		$URLSearchString = " a-zA-Z0-9\:\/\-\?\&\.\=\_\~\#\'";
		$MAILSearchString = $URLSearchString . " a-zA-Z0-9\.@";
		$Text = preg_replace("/\[url\]([$URLSearchString]*)\[\/url\]/", '<a href="$1" target="_blank">$1</a>', $Text);
		$Text = preg_replace("(\[url\=([$URLSearchString]*)\](.+?)\[/url\])", '<a href="$1" target="_blank">$2</a>', $Text);
		$Text = preg_replace("/\[URL\]([$URLSearchString]*)\[\/URL\]/", '<a href="$1" target="_blank">$1</a>', $Text);
		$Text = preg_replace("(\[URL\=([$URLSearchString]*)\](.+?)\[/URL\])", '<a href="$1" target="_blank">$2</a>', $Text);
		
		$Text = preg_replace("(\[mail\]([$MAILSearchString]*)\[/mail\])", '<a href="mailto:$1">$1</a>', $Text);
		$Text = preg_replace("/\[mail\=([$MAILSearchString]*)\](.+?)\[\/mail\]/", '<a href="mailto:$1">$2</a>', $Text);
		$Text = preg_replace("(\[EMAIL\]([$MAILSearchString]*)\[/EMAIL\])", '<a href="mailto:$1">$1</a>', $Text);
		$Text = preg_replace("/\[EMAIL\=([$MAILSearchString]*)\](.+?)\[\/EMAIL\]/", '<a href="mailto:$1">$2</a>', $Text);
		
		$Text = preg_replace("(\[b\](.+?)\[\/b])is",'<b>$1</b>',$Text);
		$Text = preg_replace("(\[B\](.+?)\[\/B])is",'<b>$1</b>',$Text);
		
		$Text = preg_replace("(\[i\](.+?)\[\/i\])is",'<span class="italics">$1</span>',$Text);
		$Text = preg_replace("(\[I\](.+?)\[\/I\])is",'<span class="italics">$1</span>',$Text);
		
		$Text = preg_replace("(\[u\](.+?)\[\/u\])is",'<span class="underline">$1</span>',$Text);
		$Text = preg_replace("(\[U\](.+?)\[\/U\])is",'<span class="underline">$1</span>',$Text);
		
		$Text = preg_replace("(\[s\](.+?)\[\/s\])is",'<span class="strikethrough">$1</span>',$Text);
		$Text = preg_replace("(\[S\](.+?)\[\/S\])is",'<span class="strikethrough">$1</span>',$Text);
		
		$Text = preg_replace("(\[o\](.+?)\[\/o\])is",'<span class="overline">$1</span>',$Text);
		$Text = preg_replace("(\[O\](.+?)\[\/O\])is",'<span class="overline">$1</span>',$Text);
		
		$Text = preg_replace("(\[center\](.+?)\[\/center\])is",'<center>$1</center>',$Text);
		
		$Text = preg_replace("(\[color=(.+?)\](.+?)\[\/color\])is","<span style=\"color: $1\">$2</span>",$Text);
		$Text = preg_replace("(\[size=(.+?)\](.+?)\[\/size\])is","<font size=\"$1\">$2</font>",$Text);
		$Text = preg_replace("/\[list\](.+?)\[\/list\]/is", '<ul class="listbullet">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=1\](.+?)\[\/list\]/is", '<ul class="listdecimal">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=i\](.+?)\[\/list\]/s", '<ul class="listlowerroman">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=I\](.+?)\[\/list\]/s", '<ul class="listupperroman">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=a\](.+?)\[\/list\]/s", '<ul class="listloweralpha">$1</ul>' ,$Text);
		$Text = preg_replace("/\[list=A\](.+?)\[\/list\]/s", '<ul class="listupperalpha">$1</ul>' ,$Text);
		$Text = str_replace("[*]", "<li>", $Text);
		$Text = preg_replace("(\[font=(.+?)\](.+?)\[\/font\])","<span style=\"font-family: $1;\">$2</span>",$Text);
		$Text = preg_replace("/\[img\](.+?)\[\/img\]/", '<img src="$1">', $Text);
		$Text = preg_replace("/\[IMG\](.+?)\[\/IMG\]/", '<img src="$1">', $Text);
		$Text = preg_replace("/\[img\=([0-9]*)x([0-9]*)\](.+?)\[\/img\]/", '<img src="$3" height="$2" width="$1">', $Text);
		$Text = preg_replace("/\[IMG\=([0-9]*)x([0-9]*)\](.+?)\[\/IMG\]/", '<img src="$3" height="$2" width="$1">', $Text);
		
		$CodeLayout = '<div class="codetop">Code</div><div class="codemain"><pre class="brush: php;">$1</pre></div>';
		$Text = preg_replace("/\[code\](.+?)\[\/code\]/is","$CodeLayout", $Text);
		
		$phpLayout = '<div class="codetop">Code</div><div class="codemain"><pre class="brush: php;">$1</pre></div>';
		$Text = preg_replace("/\[php\](.+?)\[\/php\]/is",$phpLayout, $Text);
		
		$csharpLayout = '<div class="codetop">Code</div><div class="codemain"><pre class="brush: c-sharp;">$1</pre></div>';
		$Text = preg_replace("/\[c#\](.+?)\[\/c#\]/is",$csharpLayout, $Text);
		
		$javaLayout = '<div class="codetop">Code</div><div class="codemain"><pre class="brush: java;">$1</pre></div>';
		$Text = preg_replace("/\[java\](.+?)\[\/java\]/is",$javaLayout, $Text);
		
		$vbLayout = '<div class="codetop">Code</div><div class="codemain"><pre class="brush: vb;">$1</pre></div>';
		$Text = preg_replace("/\[vb\](.+?)\[\/vb\]/is",$vbLayout, $Text);
		
		$sqlLayout = '<div class="codetop">Code</div><div class="codemain"><pre class="brush: sql;">$1</pre></div>';
		$Text = preg_replace("/\[sql\](.+?)\[\/sql\]/is",$sqlLayout, $Text);
					
		$FLASHLayout  = '<object width="$1" height="$2"><param name="movie" value="$3"></param><param name="wmode" value="transparent"></param><embed src="$3" type="application/x-shockwave-flash" wmode="transparent" width="$1" height="$2"></embed></object>';
		$Text = preg_replace("#\[FLASH=(.*?),(.*?)\](.*?)\[\/FLASH\]#si",$FLASHLayout, $Text);	
		
		$QuoteLayout = '<div class="quotetop">Quote</div><div class="quotemain">$1</pre>';
		$Text = preg_replace("/\[quote\](.+?)\[\/quote\]/is","$QuoteLayout", $Text);
		$Text = preg_replace("#\[(sub|sup|strike|blockquote|b|i|u)\]#si","<$1>", $Text);
		$Text = preg_replace("#\[\/(sub|sup|strike|blockquote|b|i|u)\]#si","</$1>", $Text);
		$Text = preg_replace("#\[hr\]#si","<hr>", $Text);
		$Text = preg_replace("#\[HR\]#si","<hr>", $Text);
		$Text = preg_replace("#\[highlight=(.*?)\]#si",'<font style="background-color:$1">', $Text);
		$Text = preg_replace("#\[\/highlight\]#si",'</font>', $Text);
		
		$center1 = "#\[center\]#si";
		$center2 = '<div align="center">';
		$Text = preg_replace($center1,$center2, $Text);	
	
		$left1 = "#\[left\]#si";
		$left2 = '<div align="left">';
		$Text = preg_replace($left1,$left2, $Text);	
		
		$right1 = "#\[right\]#si";
		$right2 = '<div align="right">';
		$Text = preg_replace($right1,$right2, $Text);	
		
		$justify1 = "#\[justify\]#si";
		$justify2 = '<div align="justify">';
		$Text = preg_replace($justify1,$justify2, $Text);	
		
		$formatclose1 = "#\[\/(center|left|right|justify)\]#si";
		$formatclose2 = '</div>';
		$Text = preg_replace($formatclose1,$formatclose2, $Text);	
	
		return $Text;
	}
}
?>