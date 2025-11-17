<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
$pCha = NULL;
if ( CChaOnline::OnlineGoodCheck( $pCha ) != ONLINE ) { exit; }
if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) ){	die("<div align=center><font color=red><b>กรุณาออกจากเกมส์ก่อนใช้งานระบบนี้!!</b></font></div>");}
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
COnline::OnlineSet( $cUser );
$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

CInput::GetInstance()->BuildFrom( IN_POST );

$mapmoney = CInput::GetInstance()->GetValueInt( "mapmoney" , IN_POST );
if ( $mapmoney == 1 )
{
	$id = CInput::GetInstance()->GetValueInt( "id" , IN_POST );
	$cMapList = new CMapList;
	$cMapList->LoadMapData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $id < 0 || $id >= $cMapList->MapNum ) die("ไม่พบแมพ");
	printf( "พ้อยที่คุณต้องใช้ในการย้ายคือ %d",$cMapList->MapPoint[ $id ] );
	echo "<input type=hidden name=good value=1>";
	exit;
}
$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $nsubmit )
{
	$id = CInput::GetInstance()->GetValueInt( "id" , IN_POST );
	$cMapList = new CMapList;
	$cMapList->LoadMapData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $id < 0 || $id >= $cMapList->MapNum ) die("ไม่พบแมพ");
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
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
	$UserPoint = $cUser->GetUserPoint();
	$NewPoint = $UserPoint-$cMapList->MapPoint[ $id ];
	if ( $NewPoint < 0 ) die("พ้อยของคุณไม่เพียงพอที่จะทำรายการ");
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
	$szTemp = sprintf("UPDATE ChaInfo SET ChaSaveMap = %d WHERE ChaNum = %d",$cMapList->MapMain[ $id ],$ChaNum );
	$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
	$cNeoSQLConnectODBC->CloseRanGame();
	//$cUser->SetUserPoint( $NewPoint );
	//$cUser->UpdateUserPointToDB();
	$cUser->DownPoint( $cMapList->MapPoint[ $id ] );
	CNeoLog::LogMapWarp( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum,$ChaNum,$cMapList->MapMain[ $id ],$cMapList->MapPoint[ $id ],$UserPoint,$cUser->GetUserPoint() );
	die("<font color=green>ย้ายแมพเรียบร้อย</font>");
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
if ( !$cWeb->Sys_MapWarp )
die("ระบบนี้ยังไม่เปิดให้บริการ");
$UserPoint = $cUser->GetUserPoint();
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
        <td>เลือกตัวละคร</td>
        <td><select name="character" id="character" onChange="loadpage( 'mapwarp','mapmoney','mapmoney=1&id='+$('#map').val() );">
          <option value="<?php echo $ChaNum; ?>"><?php echo $ChaName; ?></option>
  </select></td>
      </tr>
      <tr>
        <td align="left" valign="top">เลือกแมพ</td>
        <td align="left" valign="top"><label>
          <select name="map" id="map">
          <option value="-1" selected="selected">เลือกแมพที่ต้องการไป</option>
<?php
$cMapList = new CMapList;
$cMapList->LoadMapData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
for( $i = 0 ; $i < $cMapList->MapNum ; $i++ )
{
	printf( '<option value="%d">%d : %s</option>',$i,$cMapList->MapPoint[ $i ] ,  CBinaryCover::utf8_to_tis620( $cMapList->MapName[ $i ] ) );
}
?>
          </select>
        </label>
        <div id="mapmoney"></div>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="#c" onclick="">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td background="../images/button/free.png" width="64" height="47" align="center" valign="middle" onClick="if ( $('#map').val() != -1 ) loadpage( 'mapwarp','area','submit=1&id='+$('#map').val() ); else alert('กรุณาเลือกแมพก่อน');">ย้าย</td>
              </tr>
            </table>
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>
