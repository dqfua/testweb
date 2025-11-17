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
        $_CONFIG["OUTPUT"]["RANUSER"]["HOST"]
        , $_CONFIG["OUTPUT"]["RANUSER"]["USER"]
        , $_CONFIG["OUTPUT"]["RANUSER"]["PASS"]
        , $_CONFIG["OUTPUT"]["RANUSER"]["DATABASE"]
        );
for( $i = 0 ; $i < $nLine ; $i++ )
{
	$szTemp = sprintf( "SELECT UserID FROM UserInfo WHERE UserNum = %d" , $nUserNum[$i] );
	$pNeoSQL->Query( $szTemp );
	while( $pNeoSQL->FetchRow() )
	{
		$szUserID[$i] = $pNeoSQL->Result( "UserID" );
		
		if ( strcmp( substr($szUserID[$i] , strlen( $szUserID[$i] ) - strlen( NEWID ) , strlen( NEWID ) ) , NEWID ) == 0 )
			$szUserID[$i] = substr( $szUserID[$i] , 0 , strlen( $szUserID[$i] ) - strlen( NEWID ) );
	}
}
$pNeoSQL->Close();
$pNeoSQL->Connect(
        $_CONFIG["INPUT"]["RANUSER"]["HOST"]
        , $_CONFIG["INPUT"]["RANUSER"]["USER"]
        , $_CONFIG["INPUT"]["RANUSER"]["PASS"]
        , $_CONFIG["INPUT"]["RANUSER"]["DATABASE"]
        );
for( $i = 0 ; $i < $nLine ; $i++ )
{
	$szTemp = sprintf( "SELECT UserNum FROM UserInfo WHERE UserID = '%s'" , $szUserID[$i] );
	$pNeoSQL->Query( $szTemp );
	while( $pNeoSQL->FetchRow() )
	{
		$nUserNumFromOLD[$i] = $pNeoSQL->Result( "UserNum" );
	}
}
$pNeoSQL->Close();
$pNeoSQL->Connect(
        $_CONFIG["INPUT"]["RANGAME"]["HOST"]
        , $_CONFIG["INPUT"]["RANGAME"]["USER"]
        , $_CONFIG["INPUT"]["RANGAME"]["PASS"]
        , $_CONFIG["INPUT"]["RANGAME"]["DATABASE"]
        );
for( $i = 0 ; $i < $nLine ; $i++ )
{
	$szTemp = sprintf( "SELECT UserMoney,UserInven FROM UserInven WHERE UserNum = %d" , $nUserNumFromOLD[$i] );
	$pNeoSQL->Query( $szTemp );
	$bMove[$i] = FALSE;
	while( $pNeoSQL->FetchRow() )
	{
		$nUserMoney[$i] = $pNeoSQL->Result( "UserMoney" );
		$pUserInven[$i] = bin2hex( $pNeoSQL->Result( "UserInven" ) );
		$bMove[$i] = TRUE;
	}
}
$pNeoSQL->Close();
$pNeoSQL->Connect(
        $_CONFIG["OUTPUT"]["RANGAME"]["HOST"]
        , $_CONFIG["OUTPUT"]["RANGAME"]["USER"]
        , $_CONFIG["OUTPUT"]["RANGAME"]["PASS"]
        , $_CONFIG["OUTPUT"]["RANGAME"]["DATABASE"]
        );
for( $i = 0 ; $i < $nLine ; $i++ )
{
	if ( $bMove[$i] == FALSE ) continue;
	$szTemp = sprintf( "SELECT UserInvenNum FROM UserInven WHERE UserNum = %d" , $nUserNum[$i] );
	$pNeoSQL->Query( $szTemp );
	$bMove[$i] = FALSE;
	while( $pNeoSQL->FetchRow() )
	{
		$bMove[$i] = TRUE;
	}
	if ( $bMove[$i] == FALSE )
	{
		$szTemp = sprintf( "INSERT INTO UserInven( UserNum , SGNum , UserMoney , UserInven ) VALUES( %d , %d , " . $nUserMoney[$i] . " , 0x%s )"
																									, $nUserNum[$i]
																									, SGNUM
																									, $pUserInven[$i]
																									);
		$pNeoSQL->Query( $szTemp );
		continue;
	}
	$szTemp = sprintf( "UPDATE UserInven SET UserMoney = " . $nUserMoney[$i] . " , UserInven = 0x%s WHERE UserNum = %d"
					  , $pUserInven[$i]
					  , $nUserNum[$i]
					  );
	$pNeoSQL->Query( $szTemp );
}
$pNeoSQL->Close();
echo "Good!!";
?>

</body>
</html>
