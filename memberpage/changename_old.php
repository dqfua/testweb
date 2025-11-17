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
	if ( !CSec::Check() ) exit;
	
	$goname = CGlobal::DecodeName( CInput::GetInstance()->GetValueString( "chaname" , IN_POST ) );
	
	echo $goname;
	//echo @CNeoInject::sec_Thai( CGlobal::__POST("chaname") );
	
	if ( !strpos($goname,PASSWORD_EN) ) die("รูปแบบชื่อตัวละครไม่ถูกต้อง");
	$goname = str_replace( PASSWORD_EN , "" , $goname );
	
	$goname = base64_decode( $goname );
	
	$name_md5 = CInput::GetInstance()->GetValueString( "name_md5" , IN_POST );
	$program_md5 = substr( md5( CGlobal::EncodeName($goname.PASSWORD_EN).PASSWORD_EN ) , MD5_BEGIN , MD5_END );
	if ( strcmp($name_md5, $program_md5 ) != 0 ) die("ERROR NAME CODE FAIL");
	
	$password = CInput::GetInstance()->GetValueString( "password" , IN_POST );
	
	if ( !CGlobal::CheckStrManLen( $password ) )
	{
		die( "รหัสเข้าเกมส์จะต้องมากกว่า 4 ตัวและน้อยกว่า 16<br>" );
	}
	if ( strcmp( $cUser->GetUserPass() , $password ) != 0 )
	{
		die("รหัสผ่านเกาไม่ถูกต้อง<br>");
	}
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( !$cWeb->Sys_ChangeName )
	die("ระบบนี้ยังไม่เปิดให้บริการ");
	$UserPoint = $cUser->GetUserPoint();
	$UsePoint = $cWeb->Sys_ChangeName_Point;
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
	$OLD_NAME = $pCha->GetChaName();
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
	$szTemp = sprintf("UPDATE ChaInfo SET ChaName = '%s' WHERE ChaNum = %d AND UserNum = %d",$goname,$pCha->GetChaNum(),$cUser->GetUserNum() );
	$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
	$cNeoSQLConnectODBC->CloseRanGame();
	$pCha->Login( $pCha->GetChaNum(),$cUser->GetUserNum() );
	
	//CGlobal::SetSes( CGlobal::GetSesChaMan() , serialize( $pCha ) );
	CChaOnline::OnlineSet( $pCha );
	
	if ( $UsePoint > 0 )
	{
		//$cUser->SetUserPoint( $NewPoint );
		//$cUser->UpdateUserPointToDB();
		$cUser->DownPoint( $UsePoint );
	}
	
	CNeoLog::LogChangeName( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $pCha->GetChaNum()  , $OLD_NAME , $goname , $UserPoint , $cUser->GetUserPoint() );
	
	//CGlobal::SetSesUser( serialize( $cUser ) );
	COnline::OnlineSet( $cUser );
	
	die("เปลี่ยนชื่อตัวละครสำเร็จ<br>");
}
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="100%" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" align="center"><strong>แก้ไขชื่อตัวละคร<br /></strong>
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
if ( !$cWeb->Sys_ChangeName )
die("ระบบนี้ยังไม่เปิดให้บริการ");
$UserPoint = $cUser->GetUserPoint();
$UsePoint = $cWeb->Sys_ChangeName_Point;
$NewPoint = $UserPoint-$UsePoint;
printf("จำนวนพ้อยที่ใช้ในการเปลี่ยนคือ %d พ้อยที่คุณมีมือ %d พ้อยที่เหลือหลังจากเปลี่ยนแล้วคือ %d",$UsePoint,$UserPoint,$NewPoint);
?><br>
<br>
<hr><br>
<div id="chaname">
<input type='hidden' id='goname' name='goname' value=''>
<div id="area_changename"></div>
เปลี่ยนชื่อตัวละครเป็น : <input name="changename" id="changename" type="text" value="<?php echo $pCha->GetChaName(); ?>"  <?php if ( $NewPoint < 0 ) echo "disabled"; ?> > 
 <a href="#checkname" onClick="checkchaname( 'changename' , 'area_changename' );"><b> เช็คชื่อ </b></a></div><br>
<hr><br>
<br>
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
        <td>&nbsp;</td>
        <td><a href="#c" onclick="<?php if ( $NewPoint >= 0 ) echo "if ( confirmText('คุณต้องการทำรายการแน่นอนหรือไม่') ) if ( $('#goname').val() == '' ) alert('กรุณากดเช็คชื่อให้ถูกต้อง'); else loadpage('changename&submit=1','area','password='+$('#pass').val() + '&chaname='+$('#goname').val()+ '&name_md5='+$('#goname_en').val() );"; else echo "alert('พ้อยของคุณไม่เพียงพอที่จะทำการรายกรุณาเติมพ้อย');"; ?>">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td background="../images/button/<?php if ( $NewPoint >= 0 ) echo "free.png"; else echo"free2.png"; ?>" width="64" height="47" align="center" valign="middle">เปลี่ยนชื่อ</td>
              </tr>
            </table>
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>
