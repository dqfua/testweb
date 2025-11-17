<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
$pCha = NULL;
if ( CChaOnline::OnlineGoodCheck( $pCha ) != ONLINE )
{
    die( sprintf("<font color='red'><b>กรุณาเลือกตัวละครก่อนใช้งานระบบนี้</b></font>") );
}

if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) ){
	die("<div align=center><font color=red><b>กรุณาออกจากเกมส์ก่อนใช้งานระบบนี้!!</b></font></div>");
}
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
COnline::OnlineSet( $cUser );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

$cMemBuySkillPoint = new CMemBuySkillPoint;
$cMemBuySkillPoint->LoadData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
if ( !$cMemBuySkillPoint->ModeOn ) exit;

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

CInput::GetInstance()->BuildFrom( IN_GET );
$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );

if ( $nsubmit )
{
	$UserPoint = $cUser->GetUserPoint();
	$UsePoint = $cMemBuySkillPoint->UsePoint;
	$NewPoint = $UserPoint-$UsePoint;
	
	if ( $NewPoint < 0 ) die( "<font color='red'>ไม่สามารถทำรายการได้เนื่องจากพ้อยของคุณไม่เพียงพอ</font>" );
        $pCha->UpdateData();
	$OldSkillPoint = $pCha->ChaSkillPoint;
	$pCha->ChaSkillPoint = $pCha->ChaSkillPoint+$cMemBuySkillPoint->SkillPoint;
	$pCha->Update_ChaSkillPoint_DB();
	CChaOnline::OnlineSet( $pCha );
	
	//$cUser->SetUserPoint( $NewPoint );
	//$cUser->UpdateUserPointToDB();
	$cUser->DownPoint( $UsePoint );
	COnline::OnlineSet( $cUser );
	CNeoLog::LogBuySkillPoint( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $ChaNum , $OldSkillPoint , $pCha->ChaSkillPoint , $cMemBuySkillPoint->SkillPoint
											, $UserPoint , $cUser->GetUserPoint() , $UsePoint );
	printf(
		   "<font color=green><b></b>การทำรายการสำเร็จ<br>
		   พ้อยในปัจจุบันของคุณคือ : %d พ้อย แต้มสกิวของตัวละครของคุณคือ : %d
		   ขอบคุณที่ใช้บริการค่ะ<br></font>"
		   ,$cUser->GetUserPoint()
		   ,$pCha->ChaSkillPoint
		   );
	exit;
}
?>
<table width="600" border="0" cellpadding="0" cellspacing="0" align="center">
  <tr>
    <td bgcolor="#000000">
<div id="area_info"></div>
	<div align="center">
      <strong>กรุณาอ่าน</strong><br>
      <font color="#FF0000" size="+1"><b><u>แต้มสกิวนี้จะหายไปเมื่อใช้น้ำยาลบความจำ</u></b></font><br />
      คุณต้องการซื้อแต้มสกิวจำนวน <b><?php echo $cMemBuySkillPoint->SkillPoint; ?></b> แต้ม ในราคา <b><?php echo $cMemBuySkillPoint->UsePoint; ?></b> พ้อย ถ้าต้องการกรุณาคลิกปุ่มซื้อแต้มสกิว<br>
      พ้อยในปัจจุบันของคุณคือ <b><?php echo  $cUser->GetUserPoint(); ?></b> พ้อย ถ้าหากทำรายการไปพ้อยของคุณจะเปลี่ยนเป็น <b><?php echo  ($cUser->GetUserPoint()-$cMemBuySkillPoint->UsePoint); ?></b> พ้อย<br><br>
<?php
$java_script = "";
if ( $cUser->GetUserPoint()-$cMemBuySkillPoint->UsePoint < 0 ) $java_script = "alert('พ้อยของคุณไม่เพียงพอที่จะทำรายการ');";
else $java_script = "if ( confirmText('คุณต้องการทำรายการแน่นอนหรือไม่ (กรุณาอ่านรายละเอียดให้เข้าใจก่อนทำรายการทุกครั้งค่ะ)') ) loadpage('buyskillpoint&submit=1','area_info',null); else return false;";
?>
      <input type="submit" name="button" id="button" value="ซื้อแต้มสกิว" onClick="<?php echo $java_script; ?>">
      </div><br>
    </td>
  </tr>
</table>
