<?php
/*
$bLogin = CGlobal::GetSesUserLogin();
if ( $bLogin == ONLINE )
{
	$cUser = unserialize( CGlobal::GetSesUser() );
	header( "Location: member.php" );
	exit;
}
*/
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) == ONLINE )
{
	//header( 'Location:member.php' );
	include_once( "member.php" );
	exit;
}
/*
if ( COnline::OnlineCheck( $cUser ) == ONLINE )
{
	if ( $cUser != NULL )
	{
		//$cUser = unserialize( CGlobal::GetSesUser() );
		header( "Location: member.php" );
		exit;
	}
}
*/
CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$CLIENT_SESSION_LOGIN_CAPTCHA = "CLIENT_SESSION_LOGIN_CAPTCHA";

$nSubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nSubmit == 1 )
{
	/*
	$CAPTCHA = CInput::GetInstance()->GetValueString( "sescode" , IN_POST );
    $szCaptcha = CInput::GetInstance()->GetValueString( $CLIENT_SESSION_LOGIN_CAPTCHA , IN_SESSION );
    if ( strcmp( $szCaptcha , $CAPTCHA ) != 0 )
    {
        CInput::GetInstance()->AddValue( $CLIENT_SESSION_LOGIN_CAPTCHA , "" , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
        //die("ERROR|CLIENT_SESSION_LOGIN_CAPTCHA|" . $szCaptcha . " == " . $CAPTCHA);
		die("รหัสภาพไม่ถูกต้อง");
    }
	*/
	$cWeb = new CNeoWeb;
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp = sprintf( "SELECT MemBan,MemDelete,PassMD5 FROM MemberInfo WHERE MemberNum = %d" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        $bWork = false;
		$PassMD5 = 0;
        while ( $cNeoSQLConnectODBC->FetchRow() )
        {
			$PassMD5 = $cNeoSQLConnectODBC->Result("PassMD5",ODBC_RETYPE_INT);
			if ( $cNeoSQLConnectODBC->Result("MemBan",ODBC_RETYPE_INT) == 0
			&& $cNeoSQLConnectODBC->Result("MemDelete",ODBC_RETYPE_INT) == 0 )
				$bWork = true;
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        if ( $bWork == false ) die("ERROR|MEMBERHASBAN");
	$pConnect = $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB()  );
	if ( !$pConnect )
	{
		echo "<font color=red>ไม่สามารถติดต่อฐานข้อมูล <b>RanUser</b> กรุณาติดต่อแอดมินผู้ดูแล</font><br>";
		exit;
	}else{
		$id = CInput::GetInstance()->GetValueString( "id" , IN_POST );
		$password = CInput::GetInstance()->GetValueString( "password" , IN_POST );
		$bLogin = false;
		CGlobal::SetPassMD5( $password , ( $PassMD5 ) );
		$szTemp = sprintf( "SELECT TOP 1 UserNum FROM UserInfo WHERE UserID = '%s' AND UserPass = '%s' ",$id,$password );
		//echo $szTemp;
		$cNeoSQLConnectODBC->QueryRanUser($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$UserNum = $cNeoSQLConnectODBC->Result("UserNum",ODBC_RETYPE_INT);
			$cUser = new CNeoUser;
			$cUser->Login( $id , $password );
			//CGlobal::SetSesUser( serialize($cUser) );
			//CGlobal::SetSesUserLogin( ONLINE );
			COnline::OnlineSet( $cUser );
			
			$bLogin = CGlobal::GetSesUserLogin();
			
			echo "ยินดีต้อนรับเข้าสู่ระบบ<br>";
			CNeoLog::LogLogIn( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , CGlobal::getIP() );
			$SessionOutCode = CNeoUser::RandCodeLogSessionOut();
			CSessionNeo::LoginID( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , $SessionOutCode , $cUser->LogIP );
			CGlobal::SetSesLoginOut( $SessionOutCode );
			$bLogin = true;
			{
				$cNeoSQLConnectODBC2 = new CNeoSQLConnectODBC;
				$cNeoSQLConnectODBC2->ConnectRanWeb();
				$szTemp = sprintf("SELECT LogNum FROM LoginFist WHERE MemNum = %d AND UserNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
				$cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
				if( !$cNeoSQLConnectODBC2->FetchRow() )
				{
					$szTemp = sprintf("INSERT INTO LoginFist(MemNum,UserNum) VALUES(%d,%d) ",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
					$cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
					//echo $cWeb->GetSys_LoginPoint();
					if ( $cWeb->GetSys_LoginPoint() > 0 )
					{
						$szTemp = sprintf("UPDATE UserInfo SET UserPoint = UserPoint + %d WHERE UserNum = %d",$cWeb->GetSys_LoginPoint(),$UserNum);
						$cNeoSQLConnectODBC->QueryRanUser($szTemp);
						//echo $szTemp;
					}
				}
				$szTemp = sprintf("SELECT UserCount FROM UserInfo WHERE MemNum = %d AND UserID = '%s'",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$id);
				$cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
				if( !$cNeoSQLConnectODBC2->FetchRow() )
				{
					$szTemp = sprintf("INSERT INTO UserInfo( MemNum , UserID , UserEmail , RegisterIP , ParentID ) VALUES( '%s','%s','%s','%s','%s' )"
																								,$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
																								,$id
																								,""
																								,CGlobal::getIP()
																								,"");
					$cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
				}
				$cNeoSQLConnectODBC2->CloseRanWeb();
			}
			break;
		}
		$cNeoSQLConnectODBC->CloseRanUser();
		if ( !$bLogin && $pConnect )
		{
			echo "<font color=red>ไม่พบข้อมูล<b>สมาชิก</b>ของคุณกรุณาล็อกอินใหม่อีกครั้ง<b><!-- <br> <b>PS.</b> . ( ถ้าคุณแน่ใจว่าข้อมูลถูกต้องกรุณาเช็คว่าไอดีของคุณออนไลน์อยู่หรือไม่ ถ้าออนไลน์จะไม่สามารถเข้าสู่ระบบเว็บไซต์ได้ค่ะ ) --> <br><br></font>";
		}
	}
	exit;
}

$simplecapchar = new SimpleCaptcha;
$simplecapchar->nSize = 6;
$simplecapchar->SeassionName = $CLIENT_SESSION_LOGIN_CAPTCHA;
$simplecapchar->Result();
?>
<table width="647" border="0" align="center" cellpadding="0" cellspacing="0" background="../images/item/shop_item_11.jpg">
  <tr>
    <td><img src="../images/item/shop_item_09.jpg" width="647" height="20" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><table width="647" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="32">&nbsp;</td>
        <td width="580" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
          <tr>
            <td colspan="2" align="center"><strong><font color="black" size=+1>สมาชิกล็อกอิน</font></strong></td>
            </tr>
          <tr>
            <td width="41%"><strong><font color="black">ไอดี:</font></strong></td>
            <td width="59%"><input type=text name=id id=id value="" /></td>
            </tr>
          <tr>
            <td><strong><font color="black">รหัสผ่าน:</font></strong></td>
            <td><input type="password" name="password" value="" id="password" /></td>
          </tr>
          <tr style="display:none;">
            <td><strong><font color="black">รหัสภาพ:</font></strong></td>
            <td>
            <?php
			Captcha::ShowQuickCaptcha( $CLIENT_SESSION_LOGIN_CAPTCHA );
			?>
            <br />
            <input type="text" name="sescode" value="" id="sescode" />
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
                <input type="button" name="button" value="ล็อกอิน" onclick="login();" />
                <input type="button" name="button" value="ลืมรหัสผ่าน" onclick="forgetpassword_popup( <?php printf("'%s/popup/forgetpassword.php'",$_CONFIG["HOSTLINK"]); ?> , <?php echo $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]; ?> , 500 , 350 , 1 , 1 , '000' );" />
            </td>
          </tr>
          </table></td>
        <td width="35">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><img src="../images/item/shop_item_47.jpg" width="647" height="9" /></td>
  </tr>
</table>