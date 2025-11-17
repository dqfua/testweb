<?php
//include("logon.php");
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) )
{
	die("<div align=center><font color=red><b>กรุณาออกจากเกมส์ก่อนใช้งานระบบนี้!!</b></font></div>");
}
//$cUser = unserialize( CGlobal::GetSesUser() );

//update user from userinfo db
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
//CGlobal::SetSesUser( serialize($cUser) );
COnline::OnlineSet( $cUser );
//$cUser = unserialize( CGlobal::GetSesUser() );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nsubmit )
{
	if ( !CSec::Check() ) exit;
	
	$password = CInput::GetInstance()->GetValueString( "password" , IN_POST );
	if ( !CGlobal::CheckStrManLen( $password ) )
	{
		die( "รหัสเข้าเกมส์จะต้องมากกว่า 4 ตัวและน้อยกว่า 16<br>" );
	}
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] )
	{
		CGlobal::SetPassMD5( $password , true );
	}
	if ( strcmp( $cUser->GetUserPass() , $password ) != 0 )
	{
		die("รหัสผ่านเก่าไม่ถูกต้อง<br>");
	}
	if( !$cWeb->Sys_StatOn ) die("ระบบนี้ยังไม่เปิดให้บริการ<br>");
	$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
	if ( $bChaLogin )
	{
		echo"<div align=center><font size=+1><b>ตัวละครที่ทำรายการคือ</b></font></div><br>";
		include("chainfo.php");
	}else{
		die("กรุณาเลือกตัวละครก่อนทำรายการ<br>");
	}
	if (  !$pCha->GetNowOnline() )
	{
		die("ไม่สามารถทำรายการได้เนื่องจากตัวละครออนไลน์อยู่<br>");
	}
	
	$mainpass = CInput::GetInstance()->GetValueString( "mainpass" , IN_POST );
	
	$stm = CInput::GetInstance()->GetValueInt( "stm" , IN_POST );
	$str = CInput::GetInstance()->GetValueInt( "str" , IN_POST );
	$dex = CInput::GetInstance()->GetValueInt( "dex" , IN_POST );
	$pow = CInput::GetInstance()->GetValueInt( "pow" , IN_POST );
	$spirit = CInput::GetInstance()->GetValueInt( "spirit" , IN_POST );
	$stremain = CInput::GetInstance()->GetValueInt( "strremain" , IN_POST );
	if ( $stm <0 || $stm > 65535 ) die("Stm Error");
	if ( $str < 0 || $str > 65535 ) die ("Str Error");
	if ( $dex < 0 || $dex > 65535 ) die("Dex Error");
	if ( $pow < 0 || $pow > 65535 ) die("Pow Error");
	if ( $spirit < 0 || $spirit > 65535 ) die("Spi Error");
	if ( $stremain < 0 || $stremain > 65535 ) die("StRemain Error");
	
	if ( strcmp($mainpass,md5( $str+$stm+$dex+$pow+$spirit+$stremain )) != 0 ) { echo "สเตตัสไม่ถูกต้อง"; exit;}
	
	$st_all = 0;
	if ( $pCha->GetChaDex()  > 0 )
	$st_all += $pCha->GetChaDex();
	if ( $pCha->GetChaSpirit()  > 0 )
	$st_all += $pCha->GetChaSpirit();
	if ( $pCha->GetChaStrength()  > 0 )
	$st_all += $pCha->GetChaStrength();
	if ( $pCha->GetChaStrong()  > 0 )
	$st_all += $pCha->GetChaStrong();
	if ( $pCha->GetChaPower()  > 0 )
	$st_all += $pCha->GetChaPower();
	if ( $pCha->GetChaStRemain()  > 0 )
	$st_all += $pCha->GetChaStRemain();
	if ( strcmp($mainpass,md5( $st_all )) != 0 ) { echo "สเตตัสไม่ถูกต้อง #2"; exit;}
	
	$stm2 = $pCha->GetChaStrength();
	$str2 = $pCha->GetChaStrong();
	$dex2 = $pCha->GetChaDex();
	$pow2 = $pCha->GetChaPower();
	$spirit2 = $pCha->GetChaSpirit();
	$stremain2 = $pCha->GetChaStRemain();
	
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
	$szTemp = sprintf("UPDATE ChaInfo SET
					  ChaPower = %d
					  ,ChaStrong = %d
					  ,ChaStrength = %d
					  ,ChaSpirit = %d
					  ,ChaDex = %d
					  ,ChaStRemain = %d
					  WHERE
					  ChaNum = %d AND UserNum = %d"
					  ,$pow,$str,$stm,$spirit,$dex,$stremain
					  ,$pCha->GetChaNum(),$cUser->GetUserNum() );
	//echo $szTemp."<br>";
	$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
	$cNeoSQLConnectODBC->CloseRanGame();
	
	$pCha->Login( $pCha->GetChaNum(),$cUser->GetUserNum() );
	//CGlobal::SetSes( CGlobal::GetSesChaMan() , serialize( $pCha ) );
	CChaOnline::OnlineSet( $pCha );
	//CGlobal::SetSesUser( serialize( $cUser ) );
	COnline::OnlineSet( $cUser );
	
	$UsePoint = 0;
	$NewPoint = 0;
	
	if( $cWeb->Sys_StatPoint > 0 )
	{
		$UserPoint = $cUser->GetUserPoint();
		$UsePoint = $cWeb->Sys_StatPoint;
		$NewPoint = $UserPoint-$UsePoint;
		
		//$cUser->SetUserPoint( $NewPoint );
		//$cUser->UpdateUserPointToDB();
		$cUser->DownPoint( $UsePoint );
	}
	
	CNeoLog::LogModifyStat( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $pCha->GetChaNum()  ,$pow , $pow2 , $dex , $dex2 , $spirit,$spirit2 , $str , $str2 , $stm , $stm2 , $stremain,$stremain2,$UsePoint,$cUser->GetUserPoint() );
	
	$pCha->Login( $pCha->GetChaNum(),$cUser->GetUserNum() );
	//CGlobal::SetSes( CGlobal::GetSesChaMan() , serialize( $pCha ) );
	//CGlobal::SetSesUser( serialize($cUser) );
	COnline::OnlineSet( $cUser );
	
	die("ปรับแต่ สเตตัส สำเร็จ<br>");
}
CSec::Begin();
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="100%" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" align="center"><p><strong>แก้ไข สเตตัส<br />
          </strong>
  <?php
$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$UserPoint = $cUser->GetUserPoint();
$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
if ( $bChaLogin )
{
	echo"<div align=center><font size=+1><b>ตัวละครที่คุณเลือกอยู่ในปัจจุบัน</b></font></div><br>";
	include("chainfo.php");
}
else
die("กรุณาเลือกตัวละครก่อนใช้งานระบบนี้<br>");
$st_all = 0;
if ( $pCha->GetChaDex()  > 0 )
$st_all += $pCha->GetChaDex();
if ( $pCha->GetChaSpirit()  > 0 )
$st_all += $pCha->GetChaSpirit();
if ( $pCha->GetChaStrength()  > 0 )
$st_all += $pCha->GetChaStrength();
if ( $pCha->GetChaStrong()  > 0 )
$st_all += $pCha->GetChaStrong();
if ( $pCha->GetChaPower()  > 0 )
$st_all += $pCha->GetChaPower();
if ( $pCha->GetChaStRemain()  > 0 )
$st_all += $pCha->GetChaStRemain();

$mainpass = md5( $st_all );
?>
        </p>
<div align='center'><b>สิ่งที่จำเป็นต้องใช้!!</b><br>พ้อยที่ใช้ในการปรับแต่งคือ <b><font color=red><?php echo $cWeb->Sys_StatPoint; ?></font></b> พ้อยปัจจุบันของคุณ : <?php echo $UserPoint; ?> </div><br />
<br />
<div align='center'><b>คำเตือน!!</b><br>ค่าของแต่ละช่องจะต้องไม่เกิน <b><font color=red>65535</font></b></div>
          <form id="form1" name="form1" method="post" action="">
          <input type="hidden" name="allstcheck" id="allstcheck" value="<?php echo $st_all; ?>" />
          <input type="hidden" name="mainpass" id="mainpass" value="<?php echo $mainpass; ?>" />
            <table width="300" border="0" cellpadding="0" cellspacing="5">
              <tr>
                <td width="140" align="left">Dex</td>
                <td width="145" align="left"><input type="text" name="dex" id="dex" style="width:39px;" onchange="check_over( <?php echo $st_all; ?> );" value="<?php echo $pCha->GetChaDex(); ?>"  onblur="updatest();" />
                  <input type="button" name="button" id="button" value="&lt;" style="width:29px;" onclick=" str_set( 'dex',true ); "  />
                  <input type="button" name="button2" id="button2" value="&gt;" style="width:29px;" onclick=" str_set( 'dex',false ); " /></td>
                </tr>
              <tr>
                <td align="left">Power</td>
                <td align="left"><input type="text" name="power" id="power" style="width:39px;" onchange="check_over( <?php echo $st_all; ?> );" value="<?php echo $pCha->GetChaPower(); ?>"  onblur="updatest();" />
                  <input type="button" name="button3" id="button3" value="&lt;" style="width:29px;" onclick=" str_set( 'power',true ); "  />
                  <input type="button" name="button3" id="button4" value="&gt;" style="width:29px;" onclick=" str_set( 'power',false ); " /></td>
                </tr>
              <tr>
                <td align="left">Spirit</td>
                <td align="left"><input type="text" name="spirit" id="spirit" style="width:39px;" onchange="check_over( <?php echo $st_all; ?> );" value="<?php echo $pCha->GetChaSpirit(); ?>"  onblur="updatest();" />
                  <input type="button" name="button4" id="button5" value="&lt;" style="width:29px;" onclick=" str_set( 'spirit',true ); " />
                  <input type="button" name="button4" id="button6" value="&gt;" style="width:29px;" onclick=" str_set( 'spirit',false ); " /></td>
                </tr>
              <tr>
                <td align="left">Str</td>
                <td align="left"><input type="text" name="str" id="str" style="width:39px;" onchange="check_over( <?php echo $st_all; ?> );" value="<?php echo $pCha->GetChaStrong(); ?>"  onblur="updatest();" />
                  <input type="button" name="button5" id="button7" value="&lt;" style="width:29px;" onclick=" str_set( 'str',true ); "  />
                  <input type="button" name="button5" id="button8" value="&gt;" style="width:29px;" onclick=" str_set( 'str',false ); " /></td>
                </tr>
              <tr>
                <td align="left">Stm</td>
                <td align="left"><input type="text" name="stm" id="stm" style="width:39px;" onchange="check_over( <?php echo $st_all; ?> );" value="<?php echo $pCha->GetChaStrength(); ?>" onblur="updatest();" />
                  <input type="button" name="button6" id="button9" value="&lt;" style="width:29px;" onclick=" str_set( 'stm',true ); "  />
                  <input type="button" name="button6" id="button10" value="&gt;" style="width:29px;" onclick=" str_set( 'stm',false ); " /></td>
                </tr>
              <tr>
                <td align="left">สเตตัสที่เหลือ</td>
                <td align="left"><input type="text" name="strremain" id="strremain" style="width:39px;" onchange="check_over( <?php echo $st_all; ?> );" value="<?php echo $pCha->GetChaStRemain(); ?>" disabled="disabled" onblur="updatest();"  /> <a href="#do" onclick="stat_reset();"> Reset  </a> </td>
                </tr>
              </table>
          </form></td>
      </tr>
      <tr>
        <td width="34%">ไอดี</td>
        <td width="66%"><label>
          <input name="id" type="text" id="id" readonly="readonly" value="<?php echo $UserID; ?>" />
          </label></td>
      </tr>
      <tr>
        <td>รหัสเข้าเกมส์</td>
        <td><input type="password" name="pass" id="pass" /></td>
      </tr>
      <tr>
        <td>เลือกตัวละคร</td>
        <td><select name="character" id="character">
        <option value="<?php echo $ChaNum; ?>"><?php echo $ChaName; ?></option>
</select></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="#c" onclick="<?php if ( $UserPoint < $cWeb->Sys_StatPoint ) echo "alert('ไม่สามารถปรับแต่งได้เนื่องจากพ้อยของคุณไม่เพียงพอ');"; else echo "if ( confirmText('คุณต้องการทำรายการแน่นอนหรือไม่') ) changest(); "; ?>" >
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td background="../images/button/<?php if ( $UserPoint < $cWeb->Sys_StatPoint ) echo "free2.png"; else echo "free.png"; ?>" width="64" height="47" align="center" valign="middle">ปรับแต่ง</td>
              </tr>
            </table>
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>
