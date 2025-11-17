<?php
//include global loader
include("../global.loader.php");

CInput::GetInstance()->BuildFrom( IN_GET );

$MemNum = CInput::GetInstance()->GetValueInt( "memnum" , IN_GET );
$show = CInput::GetInstance()->GetValueInt( "show" , IN_GET );
$reborn = CInput::GetInstance()->GetValueInt( "reborn" , IN_GET );
//$guild = CInput::GetInstance()->GetValueInt( "guild" , IN_GET );

if ( $reborn == 1 ) $reborn = true; else $reborn = false;
if ( $guild == 1 ) $guild = true; else $guild = false;

if ( empty( $MemNum ) || $MemNum <= 0 ) die("ERROR|MEMNUM");
if ( empty( $show ) || $show <= 0 ) die("ERROR|SHOW");
if ( $show > 200 ) $show = 200;

$FontColor = CInput::GetInstance()->GetValueString( "fontcolor" , IN_GET );

class CData
{
		public $NumRow = 0;
		public $ChaName = array();
		public $ChaLevel = array();
		public $ChaClass = array();
		public $ChaSchool = array();
		public $GuNum = array();
		public $GuName = array();
		public $GuImage = array();
		public $GuHTMLImg = array();
		public $ChaReborn = array();
};

$SES_DB = sprintf("ranking_api_%d" , $MemNum );

phpFastCache::$storage = "auto";
//$cache = phpFastCache();
$pSES_DB = phpFastCache::get($SES_DB);
//$pSES_DB = CGlobal::GetSes( $SES_DB );
//if ( !$pSES_DB )
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
	$szTemp = sprintf( "SELECT TOP %d ChaName,ChaLevel,ChaClass,ChaSchool,GuNum,ChaReborn
										FROM ChaInfo WHERE ChaDeleted = 0
										ORDER BY ChaReborn DESC , ChaLevel DESC , ChaExp DESC" , $show );
	$cNeoSQLConnectODBC->QueryRanGame($szTemp);
	while( $cNeoSQLConnectODBC->FetchRow() )
	{
		$pSES_DB->ChaName[ $pSES_DB->NumRow ] = $cNeoSQLConnectODBC->Result( "ChaName",ODBC_RETYPE_THAI );
		$pSES_DB->ChaLevel[ $pSES_DB->NumRow ] = $cNeoSQLConnectODBC->Result( "ChaLevel",ODBC_RETYPE_INT );
		$pSES_DB->ChaClass[ $pSES_DB->NumRow ] = $cNeoSQLConnectODBC->Result( "ChaClass",ODBC_RETYPE_INT );
		$pSES_DB->ChaSchool[ $pSES_DB->NumRow ] = $cNeoSQLConnectODBC->Result( "ChaSchool",ODBC_RETYPE_INT );
		$pSES_DB->GuNum[ $pSES_DB->NumRow ] = $cNeoSQLConnectODBC->Result( "GuNum",ODBC_RETYPE_INT );
		$pSES_DB->ChaReborn[ $pSES_DB->NumRow ] = $cNeoSQLConnectODBC->Result( "ChaReborn",ODBC_RETYPE_INT );
		$pSES_DB->NumRow ++;
	}
	for( $i = 0 ; $i < $pSES_DB->NumRow ; $i++ )
	{
		$szTempGuild = sprintf( "SELECT GuName,GuMarkImage FROM GuildInfo WHERE GuNum = %d" , $pSES_DB->GuNum[ $i ] );
		$cNeoSQLConnectODBC->QueryRanGame($szTempGuild);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$pSES_DB->GuName[ $i ] = $cNeoSQLConnectODBC->Result( "GuName",ODBC_RETYPE_THAI );
			$pSES_DB->GuImage[ $i ] = $cNeoSQLConnectODBC->Result( "GuMarkImage",ODBC_RETYPE_BINARY );
		}
	}
	$cNeoSQLConnectODBC->CloseRanGame();
	
	for( $i = 0 ; $i < $pSES_DB->NumRow ; $i++ )
	{
		$pSES_DB->GuHTMLImg[$i] = "";
		$pSES_DB->GuHTMLImg[$i] = CApp::build_guimg($pSES_DB->GuImage[$i],$pSES_DB->GuName[$i]);
		/*
		$pSES_DB->GuHTMLImg[$i] = @file_get_contents( sprintf( "http://gamecentershop.com/app/easyimg_guild.php?img=%s&name=%s"
				, $pSES_DB->GuImage[$i] , $pSES_DB->GuName[$i] )  );
				*/
	}
	
	phpFastCache::set($SES_DB , $pSES_DB, 3600 ); // 1 ซม
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
<table width="100%" height="355" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td valign="top"><div align="center" >
    <TABLE border="0" cellpadding="1" cellspacing="1">
    <TR><TD align="center"><span class='fontColor'><strong>ลำดับ</strong></span></TD><TD style="width:89px;" align="right" colspan="2"><span class='fontColor'><strong>ชื่อตัวละคร</strong></span></TD><TD align="center" style="<?php if ( $reborn ) echo "width:59px;"; ?>"><span class='fontColor'><strong>เลเวล<?php if ( $reborn ) echo "(จุติ)"; ?></strong></span></TD></TR>
<?php
for( $i = 0 ; $i < $pSES_DB->NumRow ; $i++ )
{
	$bgcolor = "";
	if ( $i % 2 )
		$bgcolor = "#CCCCCC";
	$reborn_txt = "";
	if ( $reborn )
	{
		$reborn_txt = sprintf( "(%d)" , $pSES_DB->ChaReborn[ $i ] );
	}
	printf( "<TR><TD align='center' bgcolor='$bgcolor'><span class='fontColor'>%d</span></TD><TD align='right' bgcolor='$bgcolor'><span class='fontColor'>%s</span></TD><TD align='left' bgcolor='$bgcolor'><span class='fontColor'>%s</span></TD><TD align='center' bgcolor='$bgcolor'><span class='fontColor'>%d%s</span></TD></TR>"
																		, $i+1
																		, substr($pSES_DB->ChaName[$i],0,10)
																		, $pSES_DB->GuHTMLImg[$i]
																		, $pSES_DB->ChaLevel[$i]
																		, $reborn_txt
																		 );
}
?>

</TABLE>
    </div></td>
  </tr>
</table>
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

</body>
</html>