<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Untitled Document</title>
</head>

<body>

<p>
  <?php
include("logon.php");
include("memberinfo.php");
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
//$cUser = unserialize( CGlobal::GetSesUser() );

//update user from userinfo db
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
//CGlobal::SetSesUser( serialize($cUser) );
COnline::OnlineSet( $cUser );
//$cUser = unserialize( CGlobal::GetSesUser() );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$gamepoint = $cWeb->GetSys_GameTime();
if ( $gamepoint != 1 ) { echo $gamepoint; exit; }

CInput::GetInstance()->BuildFrom( IN_POST );

$point = CGlobal::Time2Point( $cUser->GetGameTime() , $cWeb->GetSys_OnlineGetPoint() , $cWeb->GetSys_OnlineTime() );

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $submit == 1 )
{
	if ( !CSec::Check() ) exit;
	if ( $point <= 0 ) die("เวลาไม่เพียงพอที่จะแลกเปลี่ยน");
	$NewPoint = $cUser->UserPoint+$point;
	if ( $NewPoint <= $cUser->UserPoint )
	{
		CNeoLog::LogTime2Point( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $cUser->UserPoint , $cUser->GetUserPoint() , $cUser->GameTime , $point , false );
		die("พ้อยที่ควรได้ไม่ถูกต้อง");
	}
	CNeoLog::LogTime2Point( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $cUser->UserPoint , $cUser->GetUserPoint() , $cUser->GameTime , $point , true );
	$cUser->GameTime = 0;
	$cUser->UpdateGameTime();
	//$cUser->UserPoint = $NewPoint;
	//$cUser->UpdateUserPointToDB();
	$cUser->UpPoint( $point );
	echo "แลกเปลี่ยนสำเร็จ";
	//CGlobal::SetSesUser( serialize($cUser) );
	COnline::OnlineSet( $cUser );
	exit;
}
CSec::Begin();
?>
  
</p>
<div align="center">
  <p>อัตตราการแลกเปลี่ยนคือ <strong><?php echo $cWeb->GetSys_OnlineGetPoint(); ?></strong> นาที ได้ <strong><?php echo $cWeb->GetSys_OnlineTime(); ?></strong> พ้อย</p>
  <p>ขณะนี้เวลาออนไลน์ของคุณสามารถแลกได้ <strong><?php echo $point; ?></strong> พ้อย</p>
  <p>&nbsp;</p>
  <p>
  <?php
  if ( $point <= 0 ) echo "<a href='#submit' onclick=\"alert('ไม่สามารถแลกเปลี่ยนได้');\">";
  else
  echo "<a href='#submit' onclick=\"if ( confirmText('คุณต้องการแลกเปลี่ยนหรือไม่') ) loadpage('time2point','area','submit=1');\">";
  ?>
  <strong>แลกเปลี่ยน</strong></a></p>
</div>
</body>
</html>
