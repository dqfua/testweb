<?php
//include("logon.php");

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) )
{
	die("<div align=center><font color=red><b>กรุณาออกจากเกมส์ก่อนใช้งานระบบนี้!!</b></font></div>");
}
$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$SkillOn = $cWeb->GetSys_SkillOn();
if ( $SkillOn == 0 ) die("ระบบนี้ยังไม่เปิดให้บริการ<br>");

//$cUser = unserialize( CGlobal::GetSesUser() );
$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
if ( $bChaLogin )
{
	echo"<div align=center><font size=+1><b>ตัวละครที่คุณเลือกอยู่ในปัจจุบัน</b></font></div><br>";
	include("chainfo.php");
}
else
die("กรุณาเลือกตัวละครก่อนใช้งานระบบนี้<br>");

$ChaNum = $pCha->GetChaNum();

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nsubmit )
{
	if ( !CSec::Check() ) exit;
	
	$skillmain = CInput::GetInstance()->GetValueInt( "skillmain" , IN_POST );
	$skillsub = CInput::GetInstance()->GetValueInt( "skillsub" , IN_POST );
	
	if ( $skillmain < 0 || $skillsub < 0 ) die("SkillID ไม่ถูกต้อง");
	$cChaSkill = new CNeoChaSkill;
	$cChaSkill->LoadChaSkill( $ChaNum );
	$skillid = $cChaSkill->FindID( $skillmain , $skillsub );
	if ( $skillid != SKILL_ERROR )
	{
		$cChaSkill->Level[$skillid]--;
		$cChaSkill->UpdateDB( $cChaSkill->GetBuffer() , $ChaNum );
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
		$szTemp = sprintf( "UPDATE ChaInfo SET ChaSkillPoint = ChaSkillPoint+1 WHERE ChaNum = %d",$ChaNum );
		$cNeoSQLConnectODBC->QueryRanGame($szTemp);
		$cNeoSQLConnectODBC->CloseRanGame();
		die("ลดขั้นสกิวของคุณเรียบร้อย");
	}else{
		die("ไม่พบรหัส Skill นี้ในตัวละครของคุณ");
	}
}

$cChaSkill = new CNeoChaSkill;
$cChaSkill->LoadChaSkill( $ChaNum );
?>
<table width="600" border="0" cellpadding="3" cellspacing="3" bgcolor="#000000" align="center">
  <tr>
    <td width="266" align="center"><strong>SkillName</strong></td>
    <td width="110" align="center"><strong>Skill Level</strong></td>
    <td width="102" align="center"><strong>การจัดการ</strong></td>
  </tr>
<?php
CSec::Begin();
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT SkillNum,SkillID,SkillName FROM SkillTable WHERE MemNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
$DB_skillname = array();
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$DB_skillname[ $cNeoSQLConnectODBC->Result("SkillID",ODBC_RETYPE_ENG) ] = $cNeoSQLConnectODBC->Result("SkillName",ODBC_RETYPE_THAI);
}
$cNeoSQLConnectODBC->CloseRanWeb();
for( $i = 0 ; $i < $cChaSkill->SkillNum ; $i++ )
{
	$skillid = sprintf("SN_%03d_%03d",$cChaSkill->Main[$i],$cChaSkill->Sub[$i] );
	if ( !array_key_exists( $skillid , $DB_skillname ) ) 
	$skillname = "ไม่รู้จักชื่อสกิว";
	else
	$skillname = $DB_skillname[ $skillid ];
	if ( $cChaSkill->Level[$i] <= 0 )
	$str = "ไม่มีการจัดการ";
	else
	$str = sprintf( "<a href='#down' onclick=\"loadpage('skilldown&submit=1','area','skillmain=%d&skillsub=%d');\">ลดขั้นสกิว</a>" , $cChaSkill->Main[$i],$cChaSkill->Sub[$i] );
	
?>
  <tr>
    <td align="center"><?php echo $skillname; ?></td>
    <td align="center"><?php echo $cChaSkill->Level[$i]+1; ?></td>
    <td align="center"><?php echo $str; ?></td>
  </tr>
<?php
}
?>
  </table>