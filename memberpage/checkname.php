<?php
//header('Content-Type: text/html; charset=tis-620');

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
$chaname = @CGlobal::CheckNameTxt( CInput::GetInstance()->GetValueString( "chaname" , IN_GET ) );
if ( $submit == 1 )
{
	$chaname_en = CGlobal::EncodeName( $chaname . PASSWORD_EN );
	$chaname_md5 = substr( md5( $chaname_en.PASSWORD_EN ) , MD5_BEGIN , MD5_END );
	/*
	die( "ชื่อที่คุณต้องการเปลี่ยนคือ : <font color=green><b><u>$chaname</u></b></font>
		<input type='hidden' id='goname' name='goname' value='$chaname_en'>
		<input type='hidden' id='goname_en' name='goname_en' value='$chaname_md5'>
		" );
	*/
	die("<font color=green>Ready can change you can now.</font>
		<input type='hidden' id='goname' name='goname' value='$chaname_en'>
		<input type='hidden' id='goname_en' name='goname_en' value='$chaname_md5'>
		");
}
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
$szTemp = sprintf("SELECT ChaNum,ChaName,UserNum FROM ChaInfo WHERE ChaName = '%s' ",$chaname );
$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
$bOk = true;
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$bOk = false;
	break;
}
$cNeoSQLConnectODBC->CloseRanGame();
//echo "asdsab ".strlen( $chaname );
if ( empty($chaname) || strlen($chaname) < 4 || strlen($chaname) > 32 || !$bOk )
{
?>
<font color="#FF0000">This can can't use <b><?php echo $chaname; ?></b></font>
<?php
}else{
?>
<br><font color="#00FF00">This name available <b><a href="#change" onclick="setchaname('<?php echo $chaname; ?>');">"<?php echo $chaname; ?>"</a></b><br></font><br>
<?php
}
?>