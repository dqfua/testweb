<?php
//include("logon.php");

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
$pCha = NULL;
if ( CChaOnline::OnlineGoodCheck( $pCha ) != ONLINE ) { exit; }

if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) )
{
	die("<div align=center><font color=red><b>กรุณาออกจากเกมส์ก่อนใช้งานระบบนี้!!</b></font></div>");
}
//$cUser = unserialize( CGlobal::GetSesUser() );

//update user from userinfo db
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
//CGlobal::SetSesUser( serialize($cUser) );
//$cUser = unserialize( CGlobal::GetSesUser() );
COnline::OnlineSet( $cUser );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nsubmit )
{
	if ( !CSec::Check() ) exit;
	$password = CInput::GetInstance()->GetValueString( "pass" , IN_POST );
	$school = CInput::GetInstance()->GetValueInt( "school" , IN_POST );
	if ( !CGlobal::CheckStrManLen( $password ) )
	{
		die( "รหัสเข้าเกมส์จะต้องมากกว่า 4 ตัวและน้อยกว่า 16<br>" );
	}
	if ( $school < 0 || $school > 2 )
	{
		die("กรุณาเลือกโรงเรียนให้ถูกต้อง<br>");
	}
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] )
	{
		CGlobal::SetPassMD5( $password , true );
	}
	//printf( "%d : %d : %s : %s<br>" , $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] , $cWeb->GetServerType() , $cUser->GetUserPass() , $password );
	if ( strcmp( $cUser->GetUserPass() , $password ) != 0 )
	{
		die("รหัสผ่านเกาไม่ถูกต้อง<br>");
	}
	if ( !$cWeb->GetSys_School() )
	die("ระบบนี้ยังไม่เปิดให้บริการ");
	$UserPoint = $cUser->GetUserPoint();
	$UsePoint = $cWeb->GetSys_School_P();
	$NewPoint = $UserPoint-$UsePoint;
	if ( $NewPoint >= 0 ) {} else{ die("พ้อยของคุณไม่เพียงพอที่จะทำรายการ<br>"); }
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
	$cNeoSQLConnectODBC->QueryRanGame( sprintf("SELECT ChaSchool FROM ChaInfo WHERE ChaNum = %d",$pCha->GetChaNum()) );
	$oldschool = $cNeoSQLConnectODBC->Result( "ChaSchool",ODBC_RETYPE_INT );
	$szTemp = sprintf("UPDATE ChaInfo SET ChaSchool = %d WHERE ChaNum = %d AND UserNum = %d",$school,$pCha->GetChaNum(),$cUser->GetUserNum() );
	$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
	$cNeoSQLConnectODBC->CloseRanGame();
	$pCha->Login( $pCha->GetChaNum(),$cUser->GetUserNum() );
	
	//CGlobal::SetSes( CGlobal::GetSesChaMan() , serialize( $pCha ) );
	CChaOnline::OnlineSet( $pCha );
	
	$cWeb = new CNeoWeb;
	//$cUser->SetUserPoint( $NewPoint );
	//$cUser->UpdateUserPointToDB();
	$cUser->DownPoint( $UsePoint );
	
	//CGlobal::SetSesUser( serialize( $cUser ) );
	COnline::OnlineSet( $cUser );
	
	CNeoLog::LogChangeSchool( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $pCha->GetChaNum() , $oldschool ,$school  ,$UserPoint , $cUser->GetUserPoint() );
	die("เปลี่ยนโรงเรียนสำเร็จ<br>");
}
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="100%" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" align="center"><strong>เปลี่ยนโรงเรียน<br /></strong>
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
พ้อยที่จำเป็นต้องใช้<br />
<?php
CSec::Begin();
$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
if ( !$cWeb->GetSys_School() )
die("ระบบนี้ยังไม่เปิดให้บริการ");
$UserPoint = $cUser->GetUserPoint();
$UsePoint = $cWeb->GetSys_School_P();
$NewPoint = $UserPoint-$UsePoint;
printf("จำนวนพ้อยที่ใช้ในการเปลี่ยนคือ %d พ้อยที่คุณมีมือ %d พ้อยที่เหลือหลังจากเปลี่ยนแล้วคือ %d",$UsePoint,$UserPoint,$NewPoint);
?>
        </td>
      </tr>
      <tr>
        <td width="34%">ไอดี</td>
        <td width="66%"><label>
          <input name="id" type="text" id="id" readonly value="<?php echo $UserID; ?>" />
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
        <td>เลือกโรงเรียน</td>
        <td><label>
          <select name="school" id="school">
          <option value="-1" selected="selected">เลือกโรงเรียน</option>
          <?php
		  if ( $ChaSchool != 0 )
		  echo '<option value="0">SG</option>';
		  if ( $ChaSchool != 1 )
		  echo '<option value="1">MP</option>';
		  if ( $ChaSchool != 2 )
		  echo '<option value="2">PH</option>';
		  ?>
          </select>
        </label></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="#c" onclick="<?php if ( $NewPoint >= 0 ) echo "if ( confirmText('คุณต้องการทำรายการแน่นอนหรือไม่') ) if ( $('#school').val() < 0 || $('#school').val() > 2 ) alert('กรุณาเลือกโรงเรียนที่คุณต้องการ'); else changeschool();"; else echo "alert('พ้อยของคุณไม่เพียงพอที่จะทำการรายกรุณาเติมพ้อย');"; ?>">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td background="../images/button/<?php if ( $NewPoint >= 0 ) echo "free.png"; else echo"free2.png"; ?>" width="64" height="47" align="center" valign="middle">เปลี่ยน</td>
              </tr>
            </table>
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>
