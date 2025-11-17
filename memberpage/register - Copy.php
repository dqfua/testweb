<?php
CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$MemNum = CInput::GetInstance()->GetValueInt( "memnum" , IN_GET );
if ( $MemNum == 0 || empty( $MemNum ) ) exit;
$bGood = CNeoWeb::CheckMemNumGood( $MemNum );
if ( !$bGood ) exit;

$bLogo = CInput::GetInstance()->GetValueInt( "logo" , IN_GET );
if ( $bLogo != 1 ) $bLogo = 0;

$nSubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nSubmit == 1 )
{
	if ( CGlobal::GetSes( $_CONFIG["REGISTER"]["SESSION"] ) == "" ) exit;
	if ( strcmp( CGlobal::GetSes( $_CONFIG["REGISTER"]["SESSION"] ) , CInput::GetInstance()->GetValueString( "code" , IN_POST ) ) != 0 ) die( "<font color=red><b>รหัสความปลอดภัยไม่ถูกต้อง</b></font>" );
	CGlobal::SetSes( $_CONFIG["REGISTER"]["SESSION"] , "" );
	$g_id = CInput::GetInstance()->GetValueString( "id" , IN_POST );
	$g_pass = CInput::GetInstance()->GetValueString( "pass" , IN_POST );
	$g_pass2 = CInput::GetInstance()->GetValueString( "pass2" , IN_POST );
	$g_passdel = CInput::GetInstance()->GetValueString( "passdel" , IN_POST );
	$g_email = CInput::GetInstance()->GetValueString( "email" , IN_POST );
	if ( !CGlobal::CheckStrManLen( $g_id ) ) die( "Oh!! เสียใจด้วยไอดีของคุณไม่ถูกต้อง" );
	if ( !CGlobal::CheckStrManLen( $g_pass ) ) die( "Oh!! เสียใจด้วยรหัสเข้าเกมส์ของคุณไม่ถูกต้อง" );
	if ( !CGlobal::CheckStrManLen( $g_passdel ) ) die( "Oh!! เสียใจด้วยรหัสลบตัวละครของคุณไม่ถูกต้อง" );
	if ( strcmp( $g_pass , $g_pass2 ) != 0  ) die( "Oh!! เสียใจด้วยรหัสเข้าเกมส์ทั้ง 2 ช่องของคุณไม่ตรงกัน!!" );
	if ( CNeoUser::CheckIDAlready( $g_id , $MemNum ) ) die( "Oh!! เสียใจด้วยไอดีของคุณถูกใช้งานไปแล้ว!!" );
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $MemNum );
	$cWeb->GetSysmFromDB( $MemNum );
	
	if ( $cWeb->GetSys_ParentID() )
	{
		$ParentID = CInput::GetInstance()->GetValueString( "ParentID" , IN_POST );
		if ( strcmp( $ParentID , $g_id ) == 0 ) die( "Oh!! ไม่สามารถใส่ไอดีตัวเองเป็นไอดีผู้แนะนำได้" );
		//die( CInput::GetInstance()->GetValueString( "ParentID" , IN_POST ) );
	}
	
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanWeb();
	$szTemp = sprintf( "SELECT MemBan,MemDelete,PassMD5 FROM MemberInfo WHERE MemberNum = %d" , $MemNum );
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
$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
	$g_passnormal = $g_pass;
	$g_pass2normal = $g_passdel;
	CGlobal::SetPassMD5( $g_pass , ( $PassMD5 ) );
	CGlobal::SetPassMD5( $g_passdel , ( $PassMD5 ) );
	if ( $cWeb->GetSys_ParentID() )
	{
		$nParentUserNum = 0;
		$szTemp = sprintf( "SELECT UserNum FROM UserInfo WHERE UserID = '%s'" , $ParentID );
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$nParentUserNum = $cNeoSQLConnectODBC->Result("UserNum",ODBC_RETYPE_INT);
		}
		if ( !$nParentUserNum )
		{
			$ParentID = "";
		}
	}
	$szTemp = sprintf( "INSERT INTO UserInfo( UserName , UserID , UserPass , UserPass2 ) VALUES( '%s','%s','%s','%s' )" , $g_id , $g_id , $g_pass , $g_passdel );
	$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
	$cNeoSQLConnectODBC->CloseRanUser();
	$szTemp = sprintf( "INSERT INTO UserInfo( MemNum , UserID , UserEmail , RegisterIP , ParentID ) VALUES( '%s','%s','%s','%s','%s' )"
																								,$MemNum
																								,$g_id
																								,$g_email
																								,CGlobal::getIP()
																								,$ParentID
																								);
	$cNeoSQLConnectODBC->ConnectRanWeb();
	$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
	$cNeoSQLConnectODBC->CloseRanWeb();
        printf(
                "<font color='#00F000'><b>กรุณาจำข้อมูลของคุณให้ดี</b></font><br>
                ไอดี : <b>%s</b><br>
                รหัสเข้าเกม : <b>%s</b><br>
                รหัสลบตัวละคร : <b>%s</b><br>
                อีเมล์ : <b>%s</b><br>
                "
                ,$g_id
                ,$g_passnormal
                ,$g_pass2normal
                ,$g_email
                );
	if ( $cWeb->GetSys_ParentID() && $ParentID != "" )
	{
		printf( "ไอดีผู้แนะนำของคุณคือ : <b>%s</b><br>" , $ParentID );
	}
	echo "การสมัครไอดีสำเร็จกรุณาจำข้อมูลของคุณให้ดี!!";
	exit;
}
$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $MemNum );
{
	$simplecapchar = new SimpleCaptcha;
	$simplecapchar->nSize = $_CONFIG["REGISTER"]["SIZE"];
	$simplecapchar->SeassionName = $_CONFIG["REGISTER"]["SESSION"];
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
        <td><strong>REGISTER : สมัครไอดี</strong><font size="-1">&nbsp;</font></td>
      </tr>
      </table></td>
  </tr>
  <tr>
    <td>
    <div id="area_register">
      <form id="form_register" name="form_register" method="post" action="">
        <table width="550" border="0" cellpadding="5" cellspacing="5">
          <tr>
            <td width="148" align="right"><strong>ไอดี :</strong></td>
            <td width="322" align="left"><input name="id" type="text" id="id" size="16" style="width:169px;" />
              <font color="#FF0000" size="-1"><b>* ภาษาอังกฤษ 4 - 16 ตัว</b></font></td>
          </tr>
          <tr>
            <td align="right"><strong>รหัสเข้าเกมส์ :</strong></td>
            <td align="left"><input name="pass" type="password" id="pass" size="16" style="width:169px;" />
              <font color="#FF0000" size="-1"><b>* ภาษาอังกฤษ 4 - 16 ตัว</b></font></td>
          </tr>
          <tr>
            <td align="right"><strong>ยืนยันรหัสเข้าเกมส์ :</strong></td>
            <td align="left"><input name="pass2" type="password" id="pass2" size="16" style="width:169px;" />
              <font color="#FF0000" size="-1"><b>* ภาษาอังกฤษ 4 - 16 ตัว</b></font></td>
          </tr>
          <tr>
            <td align="right" valign="top"><strong>รหัสลบตัวละคร</strong></td>
            <td align="left"><input name="passdel" type="password" id="passdel" size="16" style="width:169px;"/><font color="red"><b> **สำคัญมาก ห้ามใส่ 1234</b></font>                
            </td>
          </tr>
          <tr>
            <td align="right"><strong>อีเมล์ :</strong></td>
            <td align="left"><input name="email" type="text" id="email" size="16" style="width:169px;" /><br />
            <font color="#00FF00">จำเป็นต้องใช้ในการกู้รหัสผ่าน หรือไม่ใส่ก็ได้</font></td>
          </tr>
<?php
//echo "\$cWeb->GetSys_ParentID() : " . $cWeb->GetSys_ParentID(); 
if ( $cWeb->GetSys_ParentID() )
{
	$ParentID = CInput::GetInstance()->GetValueString( "parentid" , IN_GET );
?>
          <tr>
            <td align="right"><strong>ไอดีผู้แนะนำ :</strong></td>
            <td align="left"><input name="id" type="text" id="ParentID" size="16" style="width:169px;" value="<?php echo $ParentID; ?>" /> 
            <font color="#00FF00">ถ้าไม่มีก็ไม่ต้องกรอก</font></td>
          </tr>
<?php
}
?>
          <tr>
            <td align="right"><strong>ความปลอดภัย :</strong></td>
            <td align="left" valign="middle">
<?php
echo "<img src=\"../displaycaptcha.php\" border=\"0\"></img><br>";
?>
<input name="seccode" type="text" id="seccode" style="width:104px;text-align:center;" size="16" maxlength="<?php echo $_CONFIG["REGISTER"]["SIZE"]; ?>" />
<?php
//echo " : <b>" . CGlobal::GetSes( $_CONFIG["REGISTER"]["SESSION"] ) . "</b>";
?>
</td>
          </tr>
          <tr>
            <td align="right"><label>
              <input type="button" name="button" id="button" value="สมัครไอดี" onclick="regcccckk( '<?php echo CGlobal::GetSes( $_CONFIG["REGISTER"]["SESSION"] ); ?>',<?php echo $MemNum; ?> );" />
            </label></td>
            <td align="left">
                <input type="reset" name="button2" id="button2" value="กรอกใหม่"  />
                <!--<input type="button" name="button" value="ลืมรหัสผ่าน" onclick="forgetpassword_popup( <?php printf("'%s/popup/forgetpassword.php'",$_CONFIG["HOSTLINK"]); ?> , <?php echo $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]; ?> , 500 , 350 , 1 , 1 , '000' );" />-->
            </td>
          </tr>
        </table>
      </form>
    </div>
    </td>
  </tr>
  <tr>
    <td>Copyright (c) 2011 - 2012 <a href="<?php echo $_CONFIG["HOSTLINK"]; ?>" target="_blank"><?php echo $_CONFIG["HOSTLINK"]; ?></a> , All Rights reserved.</td>
  </tr>
</table>
