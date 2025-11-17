<?php
//include("logon.php");
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nsubmit )
{
	$oldpass = CInput::GetInstance()->GetValueString( "oldpass" , IN_POST );
	$pass = CInput::GetInstance()->GetValueString( "pass" , IN_POST );
	$pass2 = CInput::GetInstance()->GetValueString( "pass2" , IN_POST );
	$cWeb = new CNeoWeb;
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] )
	{
		CGlobal::SetPassMD5( $oldpass , true );
	}
	if ( !CGlobal::CheckStrManLen( $oldpass , PASSMD5_LENGTH ) )
	{
		die("รหัสผ่านเก่าจะต้องมากกว่า 4 ตัว แล้วน้อยกว่า 16 ตัวค่ะ<br>");
	}
	if ( !CGlobal::CheckStrManLen( $pass ) )
	{
		die("ความยาวของรหัสผ่านใหม่ไม่ถูกต้อง จะต้องมากกว่า4 ตัวน้อยกว่า 16 ตัว<br>");
	}
	if ( strcmp( $pass , $pass2 ) != 0 )
	{
		die("รหัสผ่านใหม่ทั้งสองช่องไม่ตรงกัน<br>");
	}
	$backup_pass = $pass;
	if ( strcmp( $cUser->GetUserPass2() , $oldpass ) != 0 )
	{
		die("รหัสผ่านเกาไม่ถูกต้อง<br>");
	}
	if ( $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] )
	{
		CGlobal::SetPassMD5( $pass , true );
	}
	$cUser->UpdatePassChar( $pass );
	
	CNeoLog::LogUpdateCharPassword( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $backup_pass , CGlobal::getIP() );
	
	echo "เปลี่ยนรหัสลบตัวละครสำเร็จ<br>";
	printf( "รหัสผ่านใหม่ของคุณคือ : <b><u>%s</u></b><br>" , $backup_pass );
	exit;
}
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="100%" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" align="center"><strong>เปลี่ยนรหัสลบตัวละคร</strong></td>
        </tr>
      <tr>
        <td width="34%">ไอดี</td>
        <td width="66%"><label>
          <input name="id" type="text" id="id" readonly="readonly" value="<?php echo $UserID; ?>" />
        </label></td>
        </tr>
      <tr>
        <td>รหัสผ่านเก่า</td>
        <td><input type="password" name="oldpass" id="oldpass" /></td>
        </tr>
      <tr>
        <td>รหัสใหม่</td>
        <td><input type="password" name="pass" id="pass" /></td>
      </tr>
      <tr>
        <td>ยืนยัน-รหัสผ่านใหม่</td>
        <td>
          <input type="password" name="pass2" id="pass2" /></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>
        <a href="#c" onclick="checkcharpass();">
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td background="../images/button/free.png" width="64" height="47" align="center" valign="middle">
                    เปลี่ยน
                    </td>
                </tr>
            </table>
        </a>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
