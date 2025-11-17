<?php
//include("logon.php");
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
{
	$cWeb = new CNeoWeb;
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	//if ( $cWeb->GetServerType() == SERVTYPE_EP3 )
	{
		echo 'ขออภัยในความไม่สะดวก เนื่องจากกำลังปรับปรุงระบบส่วนนี้ ให้ติดต่อผู้ดูแลเพื่อแก้ไขไอดีค้างชั่วคราว!!';
		exit;
	}
}

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nsubmit )
{
	$password = CInput::GetInstance()->GetValueString( "pass" , IN_POST );
	$cWeb = new CNeoWeb;
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] )
	{
		CGlobal::SetPassMD5( $password , true );
	}
	if ( !CGlobal::CheckStrManLen( $password , PASSMD5_LENGTH ) )
	{
		die( "รหัสลบตัวละครจะต้องมากกว่า 4 ตัวและน้อยกว่า 16<br>" );
	}
	if ( strcmp( $cUser->GetUserPass2() , $password ) != 0 )
	{
		die("รหัสลบตัวละครไม่ถูกต้อง<br>");
	}
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
	$szTemp = sprintf("UPDATE UserInfo SET UserLoginState = 0 , LastLoginDate = getdate()+10 WHERE UserNum = %d",$cUser->GetUserNum() );
	if ( !$cNeoSQLConnectODBC->QueryRanUser( $szTemp ) ) { printf("<font color=red><b>ไม่สามารถแก้ไขไอดีค้าง</b></font><br>'n"); }
	$cNeoSQLConnectODBC->CloseRanUser();
	die("แก้ไอดีค้างสำเร็จ<br>");
}
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="100%" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
    <tr>
        <td colspan="2" align="center"><img src="../images/character_img1.gif" width="575" height="500" border="0" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center"><strong>แก้ไขไอดีค้าง</strong></td>
      </tr>
      <tr>
        <td width="34%">ไอดี</td>
        <td width="66%"><label>
          <input name="id" type="text" id="id" readonly="readonly" value="<?php echo $UserID; ?>" />
        </label></td>
      </tr>
      <tr>
        <td>รหัสลบตัวละคร</td>
        <td><input type="password" name="pass" id="pass" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="#c" onclick="disconnect();">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td background="../images/button/free.png" width="64" height="47" align="center" valign="middle"> แก้ไข </td>
              </tr>
            </table>
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>