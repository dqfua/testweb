<?php
include("logon.php");
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
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
	$szTemp = sprintf("UPDATE ChaInfo SET
					  ChaSkills = NULL
					  ,ChaSkillSlot = NULL
					  ,ChaActionSlot = NULL
					  WHERE
					  ChaNum = %d AND UserNum = %d"
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
	
	if( $cWeb->Sys_ResetSkillPoint > 0 )
	{
		$UserPoint = $cUser->GetUserPoint();
		$UsePoint = $cWeb->Sys_ResetSkillPoint;
		$NewPoint = $UserPoint-$UsePoint;
		
		//$cUser->SetUserPoint( $NewPoint );
		//$cUser->UpdateUserPointToDB();
		$cUser->DownPoint( $UsePoint );
	}
	
	CNeoLog::LogResetSkill( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $pCha->GetChaNum(),$UsePoint,$cUser->GetUserPoint() );
	
	$pCha->Login( $pCha->GetChaNum(),$cUser->GetUserNum() );
	//CGlobal::SetSes( CGlobal::GetSesChaMan() , serialize( $pCha ) );
	//CGlobal::SetSesUser( serialize( $cUser ) );
	COnline::OnlineSet( $cUser );
	
	die("ปรับแต่ สกิว สำเร็จ<br>");
}
CSec::Begin();
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="100%" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" align="center"><p><strong>รีสกิว<br />
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
?>
        </p>
<div align='center'><b>สิ่งที่จำเป็นต้องใช้!!</b><br>พ้อยที่ใช้ในการปรับแต่งคือ <b><font color=red><?php echo $cWeb->Sys_ResetSkillPoint; ?></font></b> พ้อยปัจจุบันของคุณ : <?php echo $UserPoint; ?> </div><br />
<br />
<div align='center'><b>คำเตือน!!</b><br><b><font color=red>รีสกิวจะทำให้ผู้เล่นสกิวหายหมด(แต้มสกิวจะไม่ได้รับคืน ให้ติดต่อเจ้าของเซิร์ฟเวอร์เพื่อชี้แจงต่อไป)</font></b></div></td>
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
        <td><a href="#c" onclick="<?php if ( $UserPoint < $cWeb->Sys_ResetSkillPoint ) echo "alert('ไม่สามารถปรับแต่งได้เนื่องจากพ้อยของคุณไม่เพียงพอ');"; else echo "if ( confirmText('คุณต้องการทำรายการแน่นอนหรือไม่') ) loadpage('resetskill&submit=1','area','password='+$('#pass').val() ); "; ?>" >
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td background="../images/button/<?php if ( $UserPoint < $cWeb->Sys_ResetSkillPoint ) echo "free2.png"; else echo "free.png"; ?>" width="64" height="47" align="center" valign="middle">รีสกิว</td>
              </tr>
            </table>
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>
