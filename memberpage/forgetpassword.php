<?php
CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$MemNum = CInput::GetInstance()->GetValueInt( "memnum" , IN_GET );
if ( $MemNum == 0 || empty( $MemNum ) ) exit;
$bGood = CNeoWeb::CheckMemNumGood( $MemNum );
if ( !$bGood ) exit;

$bLogo = CInput::GetInstance()->GetValueInt( "logo" , IN_GET );
if ( $bLogo != 1 ) $bLogo = 0;

$nSubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $nSubmit == 1 )
{
        //die("ยังไม่เปิดให้บริการ!!");
	if ( CGlobal::GetSes( $_CONFIG["FORGETPASSWORD"]["SESSION"] ) == "" ) exit;
	if ( strcmp( CGlobal::GetSes( $_CONFIG["FORGETPASSWORD"]["SESSION"] ) , CInput::GetInstance()->GetValueString( "seccode" , IN_POST ) ) != 0 ) die( "<font color=red><b>รหัสลับไม่ถูกต้อง</b></font>" );
	CGlobal::SetSes( $_CONFIG["FORGETPASSWORD"]["SESSION"] , "" );
	$g_id = CInput::GetInstance()->GetValueString( "id" , IN_POST );
	$g_password = CInput::GetInstance()->GetValueString( "password" , IN_POST );
	$g_email = CInput::GetInstance()->GetValueString( "email" , IN_POST );
	if ( !CGlobal::CheckStrManLen( $g_id ) ) die( "ไอดีของคุณไม่ถูกต้อง" );
	if ( !CGlobal::CheckStrManLen( $g_password ) ) die( "กรุณากรอกรหัสลบตัวละคร" );
	if (!filter_var($g_email, FILTER_VALIDATE_EMAIL) || !strlen( $g_email ))
	{
		die( "อีเมล์ที่คุณกรอกไม่ถูกต้อง" );
	}
        
        $simplecapchar = new SimpleCaptcha;
	$simplecapchar->nSize = $_CONFIG["FORGETPASSWORD"]["SIZE_WORK"];
	$simplecapchar->SeassionName = $_CONFIG["FORGETPASSWORD"]["SESSION_WORK"];
	$simplecapchar->Result();
        $newpassword_gen = CGlobal::GetSes( $_CONFIG["FORGETPASSWORD"]["SESSION_WORK"] );
        $newpassword_gen_md5 = $newpassword_gen;
        
        $UserNum = 0;
        $UserPass2 = "";
        
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $MemNum );
	$cWeb->GetSysmFromDB( $MemNum );
	$EncrpyMD5 = CNeoWeb::GetEncryptPassword2MD5( $MemNum );
    if ( $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] )
	{
		CGlobal::SetPassMD5( $newpassword_gen_md5 , $EncrpyMD5 );
		CGlobal::SetPassMD5( $g_password , $EncrpyMD5 );
	}
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp = sprintf("SELECT UserCount FROM UserInfo WHERE MemNum = %d AND UserID = '%s' AND UserEmail = '%s'" , $MemNum , $g_id , $g_email );
		//echo $szTemp;
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        //echo $szTemp;
		$UserCount = 0;
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $UserCount = $cNeoSQLConnectODBC->Result( "UserCount",ODBC_RETYPE_INT );
        }
        
        if ( $UserCount > 0 )
        {
			$UserNum = 0;
			$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
			$cNeoSQLConnectODBC->QueryRanUser( sprintf( "SELECT TOP 1 UserNum FROM UserInfo WHERE UserID = '%s' AND UserPass2 = '%s'" , $g_id , $g_password ) );
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
				$UserNum = $cNeoSQLConnectODBC->Result( "UserNum",ODBC_RETYPE_INT );
			}
			if ( $UserNum )
			{
				$szTemp = sprintf( "SELECT COUNT(*) as nCount FROM Log_Forgetpassword WHERE MemNum = %d AND UserNum = %d AND DAY( RefillDate ) = DAY( getdate() ) AND MONTH( RefillDate ) = MONTH( getdate() ) AND YEAR( RefillDate ) = YEAR( getdate() )"
						, $MemNum , $UserNum );
				$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
				$nCount = 0;
				while( $cNeoSQLConnectODBC->FetchRow() )
					$nCount = $cNeoSQLConnectODBC->Result ("nCount",ODBC_RETYPE_INT);
				$cNeoSQLConnectODBC->CloseRanWeb();
				if ( $nCount < $_CONFIG["FORGETPASSWORD"]["DO"] )
				{
					$szTemp = sprintf( "UPDATE UserInfo SET UserPass = '%s' WHERE UserNum = %d " , $newpassword_gen_md5 , $UserNum );
					$cNeoSQLConnectODBC->QueryRanUser($szTemp);
					CNeoLog::LogForgetpassword( $MemNum , $UserNum , $newpassword_gen , CGlobal::getIP() , $g_email );
					printf("เราได้ทำการส่งรหัสผ่านใหม่ของคุณไปยังอีเมล์ที่อยู่ของคุณแล้ว กรุณาตรวจสอบที่ กล่องเมล์เข้า หรือ เมล์ขยะ");
					
					$subject = sprintf( "ลืมรหัสผ่าน %s (RanOnline)" , CNeoWeb::GetServerNameFromMemNum( $MemNum ) );
					$message = sprintf( "ยินดีต้อนรับคุณ %s<br>
													รหัสเข้าเกมใหม่ของคุณคือ %s<br>
														   "
														   , $g_id
														   , $newpassword_gen
														   );
					$subject = CBinaryCover::tis620_to_utf8( $subject );
					$message = CBinaryCover::tis620_to_utf8( $message );
					sendMail( $g_email , $subject , $message );
				}else{
					printf( "ไม่สามารถทำรายการได้เนื่องจากวันนี้คุณได้ทำการกู้รหัสผ่านมากกว่า %d ครั้งแล้ว สามารถทำรายการได้ใหม่ในวันพรุ่งนี้" , $_CONFIG["FORGETPASSWORD"]["DO"] );
				}
				$cNeoSQLConnectODBC->CloseRanUser();
				$cNeoSQLConnectODBC->CloseRanWeb();
			}else{
				printf( "<font color='red'><b>รหัสลบตัวละครไม่ถูกต้อง</b></font>" );
			}
        }else{
            if ( $UserCount == 0 ) {
                printf( "<font color='red'><b>ไม่พบข้อมูลไอดี</b></font>" );
            }
        }
	exit;
}

{
	$simplecapchar = new SimpleCaptcha;
	$simplecapchar->nSize = $_CONFIG["FORGETPASSWORD"]["SIZE"];
	$simplecapchar->SeassionName = $_CONFIG["FORGETPASSWORD"]["SESSION"];
	$simplecapchar->Result();
}
?>
<table width="550" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="100%" border="0" cellpadding="0" cellspacing="0">
<?php
if ( $bLogo == 1 )
{
?>
      <tr>
        <td><img src="../logo.png" border="0" /></td>
        </tr>
<?php
}
?>
      <tr>
        <td><strong>FORGETPASSWORD : ลืมรหัสผ่าน</strong><font size="-1">&nbsp;</font></td>
      </tr>
      </table></td>
  </tr>
  <tr>
    <td>
    <div id="area_forgetpassword">
      <form id="form_forgetpassword" name="form_register" method="post" action="">
        <table width="550" border="0" cellpadding="5" cellspacing="5">
          <tr>
            <td width="148" align="right"><strong>ไอดี :</strong></td>
            <td width="322" align="left"><input name="id" type="text" id="id" size="16" style="width:169px;" /></td>
          </tr>
          <tr>
            <td width="148" align="right"><strong>อีเมล์ :</strong></td>
            <td width="322" align="left"><input name="email" type="text" id="email" size="99" style="width:169px;" /></td>
          </tr>
          <tr>
            <td width="148" align="right"><strong>รหัสลบตัวละคร :</strong></td>
            <td width="322" align="left"><input name="password" type="password" id="password" size="19" style="width:169px;" /></td>
          </tr>
          <tr>
            <td align="right"><strong>รหัสลับ :</strong></td>
            <td align="left"><input name="seccode" type="text" id="seccode" style="width:69px;text-align:right;" size="16" maxlength="<?php echo $_CONFIG["FORGETPASSWORD"]["SIZE"]; ?>" />
              : <b><?php echo CGlobal::GetSes( $_CONFIG["FORGETPASSWORD"]["SESSION"] ); ?></b></td>
          </tr>
        </table>
          <div align="center"><input type="button" value="ลืมรหัสผ่าน" onclick="loadpage('forgetpassword&memnum=<?php echo $MemNum; ?>','area_forgetpassword','submit=1&id='+$('#id').val()+'&email='+$('#email').val()+'&password='+$('#password').val()+'&seccode='+$('#seccode').val() );"></div>
      </form>
    </div>
    </td>
  </tr>
  <tr>
    <td>Copyright (c) 2011 - 2012 <a href="<?php echo $_CONFIG["HOSTLINK"]; ?>" target="_blank"><?php echo $_CONFIG["HOSTLINK"]; ?></a> , All Rights reserved.</td>
  </tr>
</table>
