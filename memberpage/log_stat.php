<?php
include("logon.php");
//$cUser = unserialize( CGlobal::GetSesUser() );
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
?>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
  <td>
	<div align="center"><b>ประวัติการปรับแต่งสเตตัส แสดงล่าสุด 20 รายการ</b></div>
    </td>
    </tr>
    </table>
<table width="600" border="0" cellpadding="0" cellspacing="3" bgcolor="#000000">
  <tr>
    <td width="93" align="center"><strong>วัน/เวลา</strong></td>
    <td width="191" align="center"><strong>ตัวละคร</strong></td>
    <td width="191" align="center"><strong>พ้อยก่อนเปลี่ยน</strong></td>
    <td width="191" align="center"><strong>พ้อยหลังเปลี่ยน</strong></td>
    <td width="146" align="center"><strong>ก่อนเปลี่ยน</strong></td>
    <td width="155" align="center"><strong>หลังเปลี่ยน</strong></td>
  </tr>
<?php
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();

$cNeoSQL = new CNeoSQLConnectODBC;
$cNeoSQL->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$szTemp = sprintf("SELECT ChaName FROM ChaInfo WHERE ChaNum = %d",$ChaNum);
	$cNeoSQL->QueryRanGame($szTemp);
	$ChaName = $cNeoSQL->Result("ChaName",ODBC_RETYPE_THAI);
}

$szTemp = sprintf("SELECT TOP 20 * FROM Log_Stat WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ORDER BY LogNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum,$ChaNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);

while( $cNeoSQLConnectODBC->FetchRow() )
{
	$LogNum = $cNeoSQLConnectODBC->Result("LogNum",ODBC_RETYPE_INT);
	$ChaNum = $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT);
	
	$Pow = $cNeoSQLConnectODBC->Result("Pow",ODBC_RETYPE_INT);
	$Pow2 = $cNeoSQLConnectODBC->Result("Pow2",ODBC_RETYPE_INT);
	$Dex = $cNeoSQLConnectODBC->Result("Dex",ODBC_RETYPE_INT);
	$Dex2 = $cNeoSQLConnectODBC->Result("Dex2",ODBC_RETYPE_INT);
	$Spi = $cNeoSQLConnectODBC->Result("Spi",ODBC_RETYPE_INT);
	$Spi2 = $cNeoSQLConnectODBC->Result("Spi2",ODBC_RETYPE_INT);
	$Str1 = $cNeoSQLConnectODBC->Result("Str1",ODBC_RETYPE_INT);
	$Str2 = $cNeoSQLConnectODBC->Result("Str2",ODBC_RETYPE_INT);
	$Stm = $cNeoSQLConnectODBC->Result("Stm",ODBC_RETYPE_INT);
	$Stm2 = $cNeoSQLConnectODBC->Result("Stm2",ODBC_RETYPE_INT);
	$StRemain = $cNeoSQLConnectODBC->Result("StRemain",ODBC_RETYPE_INT);
	$StRemain2 = $cNeoSQLConnectODBC->Result("StRemain2",ODBC_RETYPE_INT);
	$OldPoint = $cNeoSQLConnectODBC->Result("OldPoint",ODBC_RETYPE_INT);
	$NewPoint = $cNeoSQLConnectODBC->Result("NewPoint",ODBC_RETYPE_INT);
	
	$LogDate = substr( $cNeoSQLConnectODBC->Result("ModifyDate",ODBC_RETYPE_ENG) , 0 , 16 );
?>
  <tr>
    <td align="center"><?php echo $LogDate; ?></td>
    <td align="center"><?php echo $ChaName; ?></td>
    <td align="center"><?php echo $OldPoint; ?></td>
    <td align="center"><?php echo $NewPoint; ?></td>
    <td align="center"><table width="130" border="0" cellpadding="0" cellspacing="5">
        <tr>
          <td width="82"><strong>Dex</strong></td>
          <td width="103"><label>
            <input name="textfield" type="text" id="textfield" style="width:40px;" value="<?php echo $Pow; ?>">
          </label></td>
        </tr>
        <tr>
          <td><strong>Pow</strong></td>
          <td><input name="textfield2" type="text" id="textfield2" style="width:40px;" value="<?php echo $Dex; ?>"></td>
        </tr>
        <tr>
          <td><strong>Spirit</strong></td>
          <td><input name="textfield3" type="text" id="textfield3" style="width:40px;" value="<?php echo $Spi; ?>"></td>
        </tr>
        <tr>
          <td><strong>Str</strong></td>
          <td><input name="textfield4" type="text" id="textfield4" style="width:40px;" value="<?php echo $Str1; ?>"></td>
        </tr>
        <tr>
          <td><strong>Stm</strong></td>
          <td><input name="textfield5" type="text" id="textfield5" style="width:40px;" value="<?php echo $Stm; ?>"></td>
        </tr>
        <tr>
          <td><strong>StRemain</strong></td>
          <td><input name="textfield6" type="text" id="textfield6" style="width:40px;" value="<?php echo $StRemain; ?>"></td>
        </tr>
    </table></td>
    <td align="center"><table width="130" border="0" cellpadding="0" cellspacing="5">
        <tr>
          <td width="82"><strong>Dex</strong></td>
          <td width="103"><label>
            <input name="textfield7" type="text" id="textfield7" style="width:40px;" value="<?php echo $Pow2; ?>">
          </label></td>
        </tr>
        <tr>
          <td><strong>Pow</strong></td>
          <td><input name="textfield7" type="text" id="textfield8" style="width:40px;" value="<?php echo $Dex2; ?>"></td>
        </tr>
        <tr>
          <td><strong>Spirit</strong></td>
          <td><input name="textfield7" type="text" id="textfield9" style="width:40px;" value="<?php echo $Spi2; ?>"></td>
        </tr>
        <tr>
          <td><strong>Str</strong></td>
          <td><input name="textfield7" type="text" id="textfield10" style="width:40px;" value="<?php echo $Str2; ?>"></td>
        </tr>
        <tr>
          <td><strong>Stm</strong></td>
          <td><input name="textfield7" type="text" id="textfield11" style="width:40px;" value="<?php echo $Stm2; ?>"></td>
        </tr>
        <tr>
          <td><strong>StRemain</strong></td>
          <td><input name="textfield7" type="text" id="textfield12" style="width:40px;" value="<?php echo $StRemain2; ?>"></td>
        </tr>
      </table></td>
  </tr>
<?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
$cNeoSQL->CloseRanGame();
?>
</table>