<div align="center">
<?php
//include("logon.php");
//$bLogin = CGlobal::GetSesUserLogin();
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
$UserNum = $cUser->GetUserNum();

CInput::GetInstance()->BuildFrom( IN_POST );
//$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
//$pCha = unserialize( CGlobal::GetSes( CGlobal::GetSesChaMan() ) );

$chanum = CInput::GetInstance()->GetValueInt( "chanum" , IN_POST );
if ( $chanum <= 0 ) die("ERROR #1");

$pCha = new CNeoCha;
$pCha->Login( $chanum,$UserNum );
$ChaNum = $pCha->GetChaNum();
if ( $ChaNum > 0 )
{
}else{
	die( "ERROR #2" );
}

$ChaName = $pCha->GetChaName();
$ChaClass = $pCha->GetChaClass();
$ChaLevel = $pCha->GetChaLevel();
$ChaSchool = $pCha->GetChaSchool();
$ChaBright = $pCha->GetChaBright();
$ChaReborn = $pCha->GetChaReborn();
$ChaNum = $pCha->GetChaNum();
$ChaSkillPoint = $pCha->ChaSkillPoint;
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $MemNum );
$cWeb->GetSysmFromDB( $MemNum );
$Name_ChaClass = "";
if ( $cWeb->GetServerType() == SERVTYPE_EP7 )
{
    $cMemClass = new CMemClass;
    $cMemClass->LoadData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
    $Name_ChaClass = $cMemClass->ClassName_Arr[ $ChaClass ];
}else{
    $Name_ChaClass = $_CONFIG["CHACLASS"][ $ChaClass ];
}

$bSubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
?>
<table width="600" border="0" align="center" cellpadding="10" cellspacing="10" bgcolor="#000000">
  <tr>
    <td width="200" align="left" valign="top"><img src="<?php echo PATH_UPLOAD_IMAGECLASS . $_CONFIG["IMG_CHACLASS"][$ChaClass];?>" width=200 height=250 border=0 /></td>
    <td width="400" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2"><strong>ข้อมูลตัวละคร</strong></td>
        </tr>
      <tr>
        <td width="34%">ชื่อตัวละคร</td>
        <td width="66%"><?php echo $ChaName; ?></td>
        </tr>
      <tr>
        <td>เลเวล</td>
        <td><?php echo $ChaLevel; ?></td>
        </tr>
      <tr>
        <td><p>โรงเรียน</p></td>
        <td><?php echo $_CONFIG["SCHOOL"][$ChaSchool]; ?></td>
        </tr>
      <tr>
        <td>อาชีพ</td>
        <td><?php echo $Name_ChaClass; ?></td>
        </tr>
<?php
if ( $bSubmit == 0 )
{
?>
        <tr>
        <td>รหัสลบตัวละคร</td>
        <td align="left"><input type="password" id="pass2" name="pass2" style="width:150px;"/></td>
        </tr>
        <tr>
        <td colspan="2" align="right">
        <a href="#" onclick="if (confirm('ต้องการลบตัวละครแน่นอนใช่หรือไม่')) chaDelete_submit(<?php echo $chanum; ?>);">
        <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                <td background="../images/button/free.png" width="64" height="47" align="center" valign="middle">
                ยืนยัน<br />ลบตัวละคร
                </td>
            </tr>
        </table>
        </a>
        </td>
        </tr>
    </table></td>
  </tr>
<?php
}else{
?>
	<tr>
    	<td colspan="2">
<?php
$pass2 = CInput::GetInstance()->GetValueString( "pass2" , IN_POST );
$error = 0;
if ( !CGlobal::CheckStrManLen( $pass2 ) )
{
	echo "รหัสผ่านไม่ถูกต้อง<br>";
	++$error;
}

if ( $error == 0 )
{
	if ( $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] )
	{
		CGlobal::SetPassMD5( $pass2 , true );
	}
	
	if ( strcmp( $cUser->GetUserPass2() , $pass2 ) != 0 )
	{
		echo "รหัสลบตัวละครไม่ถูกต้อง<br>";
		++$error;
	}
	
	if ( $error == 0 )
	{
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( "UPDATE ChaInfo SET ChaDeleted = 1 WHERE ChaNum = %d" , $chanum );
		$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
		$cNeoSQLConnectODBC->CloseRanGame();
		
		$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB()  );
		$szTemp = sprintf( "UPDATE UserInfo SET ChaRemain = ChaRemain + 1 WHERE UserNum = %d" , $UserNum );
		$cNeoSQLConnectODBC->QueryRanUser($szTemp);
		$cNeoSQLConnectODBC->CloseRanUser();
		echo "<font color=\"green\"><b>ลบตัวละครสำเร็จ</b></font>";
		
		CChaOnline::OnlineSet( NULL );
	}
}
?>
        </td>
    </tr>
<?php
}
?>
  </table>
  </div>
  