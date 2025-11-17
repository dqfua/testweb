<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Lcoker Move :: NeoMaster</title>
</head>

<body>

<?php
include_once 'class/odbc.class.php';
include_once 'class/debug.log.class.php';
include_once 'config.inc.php';

ignore_user_abort(true);

define( "NEWID","_T9" );
define( "SGNUM",1 );

$pFile = file( "transback_usernum.txt" );
$nLine = 0;
foreach($pFile as $value)
{
	$nUserNum[$nLine] = (int)str_replace( "delete userinfo where usernum = " , "" , $value );
	$nLine++;
}

printf( "UserNum All From Miko Inside Top Server member : %d<br>" , $nLine );

//OUTPUT = MIKO
//INPUT = TOP

$pNeoSQL = new CNeoSQLConnectODBC();
$pNeoSQL->Connect(
        $_CONFIG["OUTPUT"]["RANGAME"]["HOST"]
        , $_CONFIG["OUTPUT"]["RANGAME"]["USER"]
        , $_CONFIG["OUTPUT"]["RANGAME"]["PASS"]
        , $_CONFIG["OUTPUT"]["RANGAME"]["DATABASE"]
        );
for( $i = 0 ; $i < $nLine ; $i++ )
{
	$szTemp = sprintf( "UPDATE ChaInfo SET GuNum = 0 WHERE UserNum = %d" , $nUserNum[$i] );
	$pNeoSQL->Query( $szTemp );
}
$pNeoSQL->Close();
echo "Good!!";
?>

</body>
</html>
