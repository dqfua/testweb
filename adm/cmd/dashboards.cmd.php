<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

CInput::GetInstance()->BuildFrom( IN_POST );
define( "PLAYERONLINE_STAT_SHOW" , 24 );
$CURRENT_SESSION = "ADM_DASHBOARD_SESSION";

function CMD_PLAYERONLINE_UI(  )
{
	global $cAdmin;
    global $CURRENT_SESSION;
    $MemNum = $cAdmin->GetMemNum();
	
	$stat = array();
	for( $i = 0 ; $i < PLAYERONLINE_STAT_SHOW ; $i++ )
	{
		$stat[ $i ] = 0;
	}
	$i = 0;
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanWeb();
	$cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT TOP %d [PlayerOnline] FROM [BBSAsiaGame].[dbo].[Stat_PlayerOnline] WHERE MemNum = %d ORDER BY StatNum DESC"
																								  , PLAYERONLINE_STAT_SHOW
																								  , $MemNum )
																								  );
	while( $cNeoSQLConnectODBC->FetchRow() )
	{
		$stat[ $i ] = $cNeoSQLConnectODBC->Result( "PlayerOnline" , ODBC_RETYPE_INT );
		$i++;
	}
	$cNeoSQLConnectODBC->CloseRanWeb();
	
	echo "<table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" style=\"width:700px;border: #333 dashed  3px;\">";
	echo "<tr><td colspan=\"2\"><div align=\"center\"><b><u>สถิติผู้เล่นออนไลน์</b></u></div></td></tr>";
	echo "<tr><td style=\"width:20%\"><div align=\"center\">จำนวนผู้เล่น</div></td><td style=\"width:80%\">";
	
	echo "<span class=\"s_playeronline\">";
	foreach($stat as $key=>$val)
	{
		if ( $key > 0 )
		{
			echo ",";
		}
		echo $val;
	}
	echo "</span>";
	
	echo "</td></tr><tr><td colspan=\"2\"><div align=\"center\">ระยะเวลา(ย้อนหลัง 24 ซม)</div></td></tr></table>";
	
	echo "
<script type=\"text/javascript\">
	$(function() { $('.s_playeronline').sparkline( 'html', { height: '159px' , type: 'bar' , barColor: 'green' } );  } );
</script>
	";
}

function CMD_MENU_UI()
{
    echo "<button onclick=\"ChooseMenu( 'showLogChangeName' );\">แสดงข้อมูลการเปลี่ยนชื่อล่าสุดทั้งหมด</button>";
}

CMD_PLAYERONLINE_UI();
CMD_MENU_UI();

if ( $cAdmin->GetLoginPassCard() )
{
    //todo for has admin login show secret menu
}
?>
