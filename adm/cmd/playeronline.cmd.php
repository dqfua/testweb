<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

define( "__DELAY_CACHE" , 60/*1นาที*/ );
$CURRENT_SESSION = "PLAYERONLINE_CACHE_SESSION";

class _CPlayerOnline
{
	public $ChaNum;
	public $UserNum;
	public $ChaName;
	public $ChaLevel;
	public $ChaSchool;
	public $ChaClass;
	
	function __construct( $ChaNum , $UserNum , $ChaName , $ChaLevel , $ChaSchool , $ChaClass )
	{
		$this->ChaNum = $ChaNum;
		$this->UserNum = $UserNum;
		$this->ChaName = $ChaName;
		$this->ChaLevel = $ChaLevel;
		$this->ChaSchool = $ChaSchool;
		$this->ChaClass = $ChaClass;
	}
};

function CMD_UI()
{
	global $_CONFIG;
	global $CURRENT_SESSION;
	global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
	echo "<div id=\"player_main\"><div id=\"player_process\">";
	printf( "<u>ข้อมูลผู้เล่นนี้จะอัพเดททุกๆ <b>%d</b> วินาที</u><br>" , __DELAY_CACHE );
	
	$pData = phpFastCache::get( $CURRENT_SESSION );
	if ( !$pData )
	{
		$pData = new _tdata();
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
		$cNeoSQLConnectODBC->QueryRanGame( "SELECT ChaNum,UserNum,ChaName,ChaLevel,ChaSchool,ChaClass FROM ChaInfo WHERE ChaOnline = 1" );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$cPlayerOnline = new _CPlayerOnline(
												$cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT )
												, $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT )
												, CBinaryCover::tis620_to_utf8( $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI ) )
												, $cNeoSQLConnectODBC->Result( "ChaLevel" , ODBC_RETYPE_INT )
												, $cNeoSQLConnectODBC->Result( "ChaSchool" , ODBC_RETYPE_INT )
												, $cNeoSQLConnectODBC->Result( "ChaClass" , ODBC_RETYPE_INT )
												);
			$pData->AddData( "PlayerOnline" , $cPlayerOnline );
			$pData->NextData();
		}
		$cNeoSQLConnectODBC->CloseRanGame();
		phpFastCache::set( $CURRENT_SESSION , $pData , __DELAY_CACHE );
	}
	
	table_log_easy_begin( "gridtable" );
    table_log_easy_title("ผู้เล่นออนไลน์",5,"width:700px;");
    
    table_log_easy_line_begin();
    table_log_easy_add_head_colume( "ChaNum" , "" , "width:99px;");
    table_log_easy_add_head_colume( "ชื่อตัวละคร" , "" , "width:199px;");
    table_log_easy_add_head_colume( "ข้อมูลอื่นๆ" , "" , "width:399px;");
	
	for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
    {
        $ppData = $pData->GetData( $i );
		$cPlayerOnline = $ppData["PlayerOnline"];
		
		table_log_easy_line_begin();
        table_log_easy_add_colume( sprintf( "<a href=\"#\" onclick=\"playeronline2chanum(%d);\">%d(%d)</a>" , $cPlayerOnline->ChaNum , $cPlayerOnline->ChaNum , $cPlayerOnline->UserNum ) , "" , "");
        table_log_easy_add_colume( sprintf( "<a href=\"#\" onclick=\"playeronline2chanum(%d);\">%s</a>" , $cPlayerOnline->ChaNum , $cPlayerOnline->ChaName ) , "" , "");
		table_log_easy_add_colume( sprintf( "<a href=\"#\" onclick=\"playeronline2chanum(%d);\">L:%d, %s, %s</a>" , $cPlayerOnline->ChaNum , $cPlayerOnline->ChaLevel , $_CONFIG["SCHOOL"][$cPlayerOnline->ChaSchool] , $_CONFIG["CHACLASS"][$cPlayerOnline->ChaClass] ) , "" , "");
        table_log_easy_line_end();
	}
	
	table_log_easy_end();
	echo "</div></div>";
	
	echo "<script type=\"text/javascript\" src=\"js/player.js\"></script>";
	echo "<script type=\"text/javascript\" src=\"js/playeronline.js\"></script>";
}

CMD_UI();
?>
