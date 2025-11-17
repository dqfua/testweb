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
		die("รหัสผ่านเกาไม่ถูกต้อง<br>");
	}
	if ( !$cWeb->GetSys_CharReborn() )
	die("ระบบนี้ยังไม่เปิดให้บริการ");
	$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
	if ( $bChaLogin )
	{
		echo"<div align=center><font size=+1><b>ตัวละครที่ทำรายการคือ</b></font></div><br>";
		include("chainfo.php");
	}else{
		die("กรุณาเลือกตัวละครก่อนทำรายการ<br>");
	}
	$UserPoint = $cUser->GetUserPoint();
	$UsePoint = $cWeb->GetSys_CharReborn_P();
	$NewPoint = $UserPoint-$UsePoint;
	$Reborn_Free = $cWeb->GetSys_CharRebornFree();
	$Reborn_Max = $cWeb->GetSys_CharRebornMax();
	$ChaReborn = $pCha->GetChaReborn();
	$ChaLevel = $pCha->GetChaLevel();
	
	if ( $ChaLevel != $cWeb->GetSys_CharRebornLevCheck() )
	{
		die("<font color=red><b>เลเวลตัวละครของคุณเพียงพอ!!</font></b>");
	}
	
	if ( $cWeb->GetSys_CharRebornFreeOn() == 1 )
	{
		if ( $ChaReborn < $cWeb->GetSys_CharRebornFreeCheck() )
		{
			die("<font color=red><b>ตัวละครของคุณยังไม่สามารถจุติโดยใช้พ้อยได้เนื่องจากตัวละครของคุณยังจุติในเกมส์ไม่ครบตามที่กำหนด!!</font></b>");
		}
	}
	
	if ( $ChaReborn >= $Reborn_Max  )
	die( "ไม่สามารถจุติได้เนื่องจากคุณจุติครบแล้ว" );
	$bRebornFree = ( $ChaReborn >= $Reborn_Free ) ? false : true;
	if ( $NewPoint >= 0 || $bRebornFree ) {} else{ die("พ้อยของคุณไม่เพียงพอที่จะทำรายการ<br>"); }
	
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
	$szTemp = sprintf("UPDATE ChaInfo SET ChaLevel = %d , ChaReborn = ChaReborn + 1 WHERE ChaNum = %d AND UserNum = %d"
					  , $cWeb->GetSys_CharRebornLevStart() ,$pCha->GetChaNum(),$cUser->GetUserNum() );
	$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
	$cNeoSQLConnectODBC->CloseRanGame();
	$pCha->Login( $pCha->GetChaNum(),$cUser->GetUserNum() );
	//CGlobal::SetSes( CGlobal::GetSesChaMan() , serialize( $pCha ) );
	CChaOnline::OnlineSet( $pCha );
	if ( !$bRebornFree )
	{
		//$cUser->SetUserPoint( $NewPoint );
		//$cUser->UpdateUserPointToDB();
		$cUser->DownPoint( $UsePoint );
	}
	//CGlobal::SetSesUser( serialize( $cUser ) );
	COnline::OnlineSet( $cUser );
	CNeoLog::LogReborn( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , $pCha->GetChaNum() , $ChaReborn , $ChaReborn+1  );
	die("จุติเรียบร้อยแล้ว<br>");
}
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="100%" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" align="center"><strong>จุติ<br /></strong>
<?php
$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
if ( $bChaLogin )
{
	echo"<div align=center><font size=+1><b>ตัวละครที่คุณเลือกอยู่ในปัจจุบัน</b></font></div><br>";
	include("chainfo.php");
}
else
die("กรุณาเลือกตัวละครก่อนใช้งานระบบนี้<br>");
?><br />
<?php
CSec::Begin();
$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
if ( !$cWeb->GetSys_CharReborn() )
die("ระบบนี้ยังไม่เปิดให้บริการ");
$UserPoint = $cUser->GetUserPoint();
$UsePoint = $cWeb->GetSys_CharReborn_P();
$NewPoint = $UserPoint-$UsePoint;
$Reborn_Free = $cWeb->GetSys_CharRebornFree();
$Reborn_Max = $cWeb->GetSys_CharRebornMax();
$ChaReborn = $pCha->GetChaReborn();
$ChaLevel = $pCha->GetChaLevel();

if ( $ChaLevel != $cWeb->GetSys_CharRebornLevCheck() )
{
	die("<font color=red><b>เลเวลตัวละครของคุณไม่เพียงพอ!!</font></b>");
}

if ( $cWeb->GetSys_CharRebornFreeOn() == 1 )
{
	if ( $ChaReborn < $cWeb->GetSys_CharRebornFreeCheck() )
	{
		die("<font color=red><b>ตัวละครของคุณยังไม่สามารถจุติโดยใช้พ้อยได้เนื่องจากตัวละครของคุณยังจุติในเกมส์ไม่ครบตามที่กำหนด!!</font></b>");
	}
}

printf("จุติฟรี %d จุติ เสียพ้อย %d รวมทั้งหมด %d<br>",$Reborn_Free,$Reborn_Max-$Reborn_Free,$Reborn_Max);
if ( $ChaReborn >= $Reborn_Max  )
die( "ไม่สามารถจุติได้เนื่องจากคุณจุติครบแล้ว<br>" );
$bRebornFree = ( $ChaReborn >= $Reborn_Free ) ? false : true;
if ( !$bRebornFree )
printf("จำนวนพ้อยที่ใช้ในการเปลี่ยนคือ %d พ้อยที่คุณมีมือ %d พ้อยที่เหลือหลังจากเปลี่ยนแล้วคือ %d",$UsePoint,$UserPoint,$NewPoint);
?>
        </td>
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
        <td>รายละเอียดการจุติ</td>
        <td><?php printf( "จุติฟรีไปแล้ว %d จุติเสียพ้อย %d จุติรวมทั้งหมด %d",( $ChaReborn > $Reborn_Max ) ? $Reborn_Max : $ChaReborn , ( $ChaReborn > $Reborn_Max ) ? $ChaReborn : 0 , $ChaReborn ); ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="#c" onclick="<?php if ( $bRebornFree ) echo "if ( confirmText('จุติฟรี คุณต้องการจะจุติหรือไม่') ) loadpage('reborn&submit=1','area','password='+$('#pass').val()); "; else if ( $NewPoint >= 0 ) echo "if ( confirmText('จุติพ้อย คุณต้องการจะจุติหรือไม่') ) loadpage('reborn&submit=1','area','password='+$('#pass').val()); "; else echo "จุติฟรีของคุณหมดแล้ว คุณจำเป็นต้องใช้พ้อยในการจุติ แต่พ้อยของคุณไม่เพียงพอ"; ?>">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td background="../images/button/<?php if ( ( $bRebornFree ) || ( !$bRebornFree && $NewPoint >= 0 ) ) echo "free.png"; else echo"free2.png"; ?>" width="64" height="47" align="center" valign="middle">
              <?php
              if ( $bRebornFree )
			  echo "จุติฟรี";
			  else
			  echo "จุติเสียพ้อย";
			  ?></td>
              </tr>
            </table>
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>
