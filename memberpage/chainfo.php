<div align="center">
<?php
//include("logon.php");
//$bLogin = CGlobal::GetSesUserLogin();
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserNum = $cUser->GetUserNum();

//$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
//$pCha = unserialize( CGlobal::GetSes( CGlobal::GetSesChaMan() ) );

$pCha = NULL;
if ( CChaOnline::OnlineGoodCheck( $pCha ) != ONLINE )
{
    die( sprintf("<font color='red'><b>กรุณาเลือกตัวละครก่อนใช้งานระบบนี้</b></font>") );
}

$ChaName = $pCha->GetChaName();
$ChaClass = $pCha->GetChaClass();
$ChaLevel = $pCha->GetChaLevel();
$ChaSchool = $pCha->GetChaSchool();
$ChaBright = $pCha->GetChaBright();
$ChaReborn = $pCha->GetChaReborn();
$ChaNum = $pCha->GetChaNum();
$ChaSkillPoint = $pCha->ChaSkillPoint;
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $MemNum );
$cWeb->GetSysmFromDB( $MemNum );
$Name_ChaClass = "";
if ( $cWeb->GetServerType() == SERVTYPE_EP7 )
{
    $cMemClass = new CMemClass;
    $cMemClass->LoadData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
    $Name_ChaClass = $cMemClass->ClassName_Arr[ $ChaClass ];
}else{
    $Name_ChaClass = $_CONFIG["CHACLASS"][ $ChaClass ];
}
?>
<table width="600" border="0" align="center" cellpadding="10" cellspacing="10" bgcolor="#000000">
  <tr>
    <td width="200" align="left" valign="top"><img src="<?php echo PATH_UPLOAD_IMAGECLASS . $_CONFIG["IMG_CHACLASS"][$ChaClass];?>" width=200 height=250 border=0 /></td>
    <td width="400" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2"><strong>ข้อมูลตัวละคร</strong></td>
        </tr>
      <tr>
        <td width="34%">ชื่อตัวละคร</td>
        <td width="66%"><input type=text style="width:150px;" value="<?php echo $ChaName; ?>" readonly="readonly" /></td>
        </tr>
      <tr>
        <td>เลเวล</td>
        <td><input type="text" style="width:150px;" value="<?php echo $ChaLevel; ?>" readonly="readonly" /></td>
        </tr>
      <tr>
        <td><p>โรงเรียน</p></td>
        <td><input type="text" style="width:150px;" value="<?php echo $_CONFIG["SCHOOL"][$ChaSchool]; ?>" readonly="readonly" /></td>
        </tr>
      <tr>
        <td>อาชีพ</td>
        <td><input type="text" style="width:150px;" value="<?php echo $Name_ChaClass; ?>" readonly="readonly" /></td>
        </tr>
        <tr>
          <td>ความดี</td>
          <td><input type="text" style="width:150px;" value="<?php echo $ChaBright; ?>" readonly="readonly" /></td>
        </tr>
        <tr>
          <td>จุติทั้งหมด</td>
          <td><input type="text" style="width:150px;" value="<?php echo $ChaReborn; ?>" readonly="readonly" /></td>
        </tr>
        <tr>
          <td>แต้มสกิว</td>
          <td><input type="text" style="width:150px;" value="<?php echo $ChaSkillPoint; ?>" readonly="readonly" /></td>
        </tr>
    </table></td>
  </tr>
  </table>
  </div>