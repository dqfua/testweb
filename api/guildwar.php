<?php
//include global loader
include("../global.loader.php");
CInput::GetInstance()->BuildFrom( IN_GET );

$MemNum = CInput::GetInstance()->GetValueInt( "memnum" , IN_GET );
if ( empty( $MemNum ) || $MemNum <= 0 ) die("ERROR|MEMNUM");

$FontColor = CInput::GetInstance()->GetValueString( "fontcolor" , IN_GET );

$SES_DB = sprintf("guilwar_api_%d" , $MemNum );
define( "MAX_GUILD_WAR" , 4 );

class CData
{
		public $ChaName = array();
		public $ChaLevel = array();
		public $ChaClass = array();
		public $ChaSchool = array();
		public $GuNum = array();
		public $GuName = array();
		public $GuImage = array();
		public $GuTax = array();
		public $GuHTMLImg = array();
		function __construct()
		{
			for( $i = 1 ; $i <= MAX_GUILD_WAR ; $i++ )
			{
				$this->ChaName[$i] = "";
				$this->ChaLevel[$i] = 0;
				$this->ChaClass[$i] = 0;
				$this->ChaSchool[$i] = 0;
				$this->GuImage[$i] = NULL;
				$this->GuNum[$i] = 0;
				$this->GuName[$i] = "";
				$this->GuTax[$i] = 0;
				$this->GuHTMLImg[$i] = "";
			}
		}
};
phpFastCache::$storage = "auto";
//$cache = phpFastCache();
$pSES_DB = phpFastCache::get($SES_DB);
//$pSES_DB = CGlobal::GetSes( $SES_DB );
if ( !$pSES_DB )
{
	$pSES_DB = new CData;
	
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $MemNum );
	
	if( !strlen( $cWeb->GetRanGame_IP() ) || !strlen( $cWeb->GetRanGame_User() ) || !strlen( $cWeb->GetRanGame_Pass() ) || !strlen( $cWeb->GetRanGame_DB() ) ) die("");
	
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP()
																				, $cWeb->GetRanGame_User()
																				, $cWeb->GetRanGame_Pass()
																				, $cWeb->GetRanGame_DB() );
	$szTemp = sprintf( "SELECT GuNum,RegionTax,RegionID FROM GuildRegion" );
	$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
	while( $cNeoSQLConnectODBC->FetchRow() )
	{
		$n = $cNeoSQLConnectODBC->Result( "RegionID" , ODBC_RETYPE_INT );
		$pSES_DB->GuNum[$n] = $cNeoSQLConnectODBC->Result( "GuNum" , ODBC_RETYPE_INT );
		$pSES_DB->GuTax[$n] = $cNeoSQLConnectODBC->Result( "RegionTax" , ODBC_RETYPE_INT );
	}
	for( $i = 1 ; $i <= MAX_GUILD_WAR ; $i++ )
	{
		$szTemp = sprintf( "SELECT GuName,GuMarkImage FROM GuildInfo WHERE GuNum = %d" , $pSES_DB->GuNum[$i] );
		$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$pSES_DB->GuImage[ $i ] = $cNeoSQLConnectODBC->Result( "GuMarkImage" , ODBC_RETYPE_BINARY );
			$pSES_DB->GuName[ $i ] = $cNeoSQLConnectODBC->Result( "GuName" , ODBC_RETYPE_THAI );
		}
	}
	$cNeoSQLConnectODBC->CloseRanGame();
	
	for( $i = 1 ; $i <= MAX_GUILD_WAR ; $i++ )
	{
		$pSES_DB->GuHTMLImg[$i] = "";
		/*
		$pSES_DB->GuHTMLImg[$i] = @file_get_contents( sprintf( "http://gamecentershop.com/app/easyimg_guild.php?img=%s&name=%s"
				, $pSES_DB->GuImage[$i] , $pSES_DB->GuName[$i] )  );
				*/
		$pSES_DB->GuHTMLImg[$i] = CApp::build_guimg($pSES_DB->GuImage[$i],$pSES_DB->GuName[$i]);
	}
	
	phpFastCache::set($SES_DB , $pSES_DB, 600 ); // 10 นาที
	//CGlobal::SetSes( $SES_DB , $pSES_DB );
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: transparent;
}
body,td,th {
	font-family: Tahoma;
	font-size: 12px;
	color: #000000;
	font-weight: bold;
}
.fontColor {
	color:#<?php echo $FontColor; ?>;
}
-->
</style>



</head>

<body>
<table border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top"><div align="center" >
      <table border="0" cellpadding="3" cellspacing="1">
		<?php
		for( $i = 1 ; $i <= MAX_GUILD_WAR ; $i++ )
		{
			$txt = array( 1 => "SG" , 2 => "MP" , 3 => "PH" , 4 => "TD" );
			if ( $pSES_DB->GuImage[$i] )
			{
				printf( "<tr><td align='center'><span class='fontColor'>%s</span></td><td align='right' style='width:99px;'><span class='fontColor'>%s</span></td><td align='center'><span class='fontColor'>%s</span></td></tr>" , $txt[$i] , substr( $pSES_DB->GuName[$i] , 0 , 10 ) ,  $pSES_DB->GuHTMLImg[$i] ); 
			}else{
				printf( "<tr><td align='center'><span class='fontColor'>%s</td><td align='center'>&nbsp;</td></tr>" , $txt[$i] ); 
			}
		}
		  ?>
      </table>
    </div></td>
  </tr>
</table>
</body>
</html>

<script language=JavaScript>
<!--
var message="";
///////////////////////////////////
function clickIE() {if (document.all) {(message);return false;}}
function clickNS(e) {if 
(document.layers||(document.getElementById&&!document.all)) {
if (e.which==2||e.which==3) {(message);return false;}}}
if (document.layers) 
{document.captureEvents(Event.MOUSEDOWN);document.onmousedown=clickNS;}
else{document.onmouseup=clickNS;document.oncontextmenu=clickIE;}
 
document.oncontextmenu=new Function("return false")
// --> 
  </script>
 
<script language="JavaScript1.2" type="text/javascript"> 
function disableselect(e){
return false
}
 
function reEnable(){
return true
}
 
//if IE4+
document.onselectstart=new Function ("return false")
 
//if NS6
if (window.sidebar){
document.onmousedown=disableselect
document.onclick=reEnable
}
</script>
