<html>
<head>
<title>Project  Move Data</title>
<script language="javascript" src="java.js"></script>
</head>
<body>
<div id="area">Will Begin....</div>
<?php
include_once 'class/odbc.class.php';
include_once 'config.inc.php';

$pInputRanUser = new CNeoSQLConnectODBC();
$pInputRanUser->Connect(
        $_CONFIG["INPUT"]["RANUSER"]["HOST"]
        , $_CONFIG["INPUT"]["RANUSER"]["USER"]
        , $_CONFIG["INPUT"]["RANUSER"]["PASS"]
        , $_CONFIG["INPUT"]["RANUSER"]["DATABASE"]
        );
$pInputRanUser->Query( sprintf( "SELECT TOP 1 UserNum FROM UserInfo ORDER BY UserNum DESC" ) );
while( $pInputRanUser->FetchRow() )
{
	$Input_UserNum = $pInputRanUser->Result( "UserNum" );
}
$pInputRanUser->Close();

printf(
"
<script language='javascript'>
var donow = 0;
var domax = %d;
var delaytime = 5000;

function dothis()
{
	donow++;
	js_popup( 'move.php?usernum=' + donow , donow , 600 , 450 );
	document.getElementById('area').innerHTML='Do in process ' + donow + '/' + domax;
	
	if ( donow < domax )
		setTimeout( dothis , delaytime );
}
setTimeout( dothis , delaytime );
</script>
" , $Input_UserNum
	   );
?>
</body>
</html>