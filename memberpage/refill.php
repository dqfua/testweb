<?php
//die("ขออภัยในความไม่สะดวกขณะนี้ระบบเติมบัตรกำลังปรับปรุง คาดว่าน่าจะใช้ได้ภายในวันนี้เวลา 22.00น");
//include("logon.php");
//$bLogin = CGlobal::GetSesUserLogin();
//if ( !isset( CGlobal::GetSesUser() ) ) exit;
//if ( !CGlobal::GetSesUser() ) exit;
//$cUser = unserialize( CGlobal::GetSesUser() );
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

define( "DELAY_FOR_VIEWLOG" , 60/*1นาที*/ );

phpFastCache::$storage = "auto";

$CURRENT_SESSION_WEB = sprintf( "webdata_%d_%d"
														, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
														, $cUser->GetUserNum() );
$cWeb = unserialize( phpFastCache::get( $CURRENT_SESSION_WEB ) );
if ( !$cWeb )
{
	$cWeb = new CNeoWeb;
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	
	phpFastCache::set( $CURRENT_SESSION_WEB , serialize( $cWeb ) , 300+floor( rand()%300 )  );
}

$CURRENT_SESSION_REFILL = sprintf( "userrefilldata_%d_%d"
														, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
														, $cUser->GetUserNum() );

$UserNum = $cUser->GetUserNum();
$UserID = $cUser->GetUserID();

$cTmPay = new TmPay;
$cTmPay->UpdateCardRank();
$Card50 = $cTmPay->GetCard_50();
$Card90 = $cTmPay->GetCard_90();
$Card150 = $cTmPay->GetCard_150();
$Card300 = $cTmPay->GetCard_300();
$Card500 = $cTmPay->GetCard_500();
$Card1000 = $cTmPay->GetCard_1000();

CSec::Begin();

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
?>
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td><table width="90%" border="0" align="center" cellpadding="0" cellspacing="3">
      <tr>
        <td height="39" colspan="7" align="center" bgcolor="#000000"><strong>โปรโมชั่นทรูมันนี่</strong></td>
      </tr>
      <tr>
        <td height="29" align="center" bgcolor="#333333"><strong>ราคาบัตร</strong></td>
        <td height="29" align="center" bgcolor="#333333"><strong>50</strong></td>
        <td height="29" align="center" bgcolor="#333333"><strong>90</strong></td>
        <td height="29" align="center" bgcolor="#333333"><strong>150</strong></td>
        <td height="29" align="center" bgcolor="#333333"><strong>300</strong></td>
        <td height="29" align="center" bgcolor="#333333"><strong>500</strong></td>
        <td height="29" align="center" bgcolor="#333333"><strong>1000</strong></td>
      </tr>
      <tr>
        <td height="29" align="center" bgcolor="#666666"><strong>พ้อยที่เติมได้</strong></td>
        <td height="29" align="center" bgcolor="#666666"><strong><?php echo $Card50; ?></strong></td>
        <td height="29" align="center" bgcolor="#666666"><strong><?php echo $Card90; ?></strong></td>
        <td height="29" align="center" bgcolor="#666666"><strong><?php echo $Card150; ?></strong></td>
        <td height="29" align="center" bgcolor="#666666"><strong><?php echo $Card300; ?></strong></td>
        <td height="29" align="center" bgcolor="#666666"><strong><?php echo $Card500; ?></strong></td>
        <td height="29" align="center" bgcolor="#666666"><strong><?php echo $Card1000; ?></strong></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><div id="refill">
      <table width="90%" align="center" cellpadding="5" cellspacing="5">
        <tr>
          <td height="25" width="30%"><strong>ID:</strong></td>
          <td height="25">:&nbsp;<?php echo $UserID; ?></td>
          </tr>
        <tr>
          <td height="25"><strong>รหัสบัตรเงินสด:</strong></td>
          <td height="25">:&nbsp;
            <input  maxlength="14" size="14" type="text" name="serial" id="serial" /></td>
          </tr>
        <tr>
          <td height="25">&nbsp;</td>
          <td height="25">
          <input type="button" name="button" id="button" value="เติมเงิน" onclick="refill();" />
          </td>
          </tr>
      </table>
    </div></td>
  </tr>
  <tr>
  <td><table width="90%" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td width="30%" height="25" align="center">รับเฉพาะบัตร <strong><font color="red">True Money เท่านั้น</font></strong> ไม่รับบัตร True   Move ค่ะ<br />
        ราคาบัตร   จะปรากฏในประวัติการเติมเงินหลังจากทีมงานตรวจสอบเรียบร้อยแล้ว </td>
      </tr>
    <tr>
      <td height="25"><table width="100%" border="0" cellpadding="0" cellspacing="5">
        <tr>
          <td height="39" colspan="7" align="center" bgcolor="#000000"><center>
            <strong>ประวัติการเติมเงิน ( แสดง 20 รายการล่าสุด ) </strong>
            </center></td>
        </tr>
        <tr>
          <td width="11%" height="25" align="center" bgcolor="#333333">วันเวลา</td>
          <td width="16%" height="25" align="center" bgcolor="#333333">Password</td>
          <td width="10%" height="25" align="center" bgcolor="#333333">ราคา</td>
          <td width="17%" height="25" align="center" bgcolor="#333333">พ้อยก่อนเติม</td>
          <td width="17%" height="25" align="center" bgcolor="#333333">พ้อยหลังเติม</td>
          <td width="20%" height="25" align="center" bgcolor="#333333">พ้อยที่ได้รับคือ</td>
          <td width="9%" align="center" bgcolor="#333333">สถานะ</td>
        </tr>
<?php
	$pData = phpFastCache::get( $CURRENT_SESSION_REFILL );
    if ( !$pData )
	{
		$pData = new _tdata();
		$szTemp = sprintf("SELECT TOP 20 SerialTruemoney,Status,CardRank,RefillDate,UpdateDate,OldPoint,NewPoint,GetPoint FROM Refill WHERE MemNum = %d AND UserNum = %d ORDER BY RefillNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$SerialTruemoney = "XXXXXXX" . substr( $cNeoSQLConnectODBC->Result("SerialTruemoney",ODBC_RETYPE_ENG) , 6 , 14 );
			$Status = $cNeoSQLConnectODBC->Result("Status",ODBC_RETYPE_INT);
			$CardRank = $cNeoSQLConnectODBC->Result("CardRank",ODBC_RETYPE_INT);
			$OldPoint = $cNeoSQLConnectODBC->Result("OldPoint",ODBC_RETYPE_INT);
			$NewPoint = $cNeoSQLConnectODBC->Result("NewPoint",ODBC_RETYPE_INT);
			$GetPoint = $cNeoSQLConnectODBC->Result("GetPoint",ODBC_RETYPE_INT);
			$RefillDate = substr( $cNeoSQLConnectODBC->Result("RefillDate",ODBC_RETYPE_ENG) , 0 , 16 );
			$UpdateDate = substr( $cNeoSQLConnectODBC->Result("UpdateDate",ODBC_RETYPE_ENG) , 0 , 16 );
			
			$pData->AddData( "SerialTruemoney" , $SerialTruemoney );
			$pData->AddData( "Status" , $Status );
			$pData->AddData( "CardRank" , $CardRank );
			$pData->AddData( "OldPoint" , $OldPoint );
			$pData->AddData( "NewPoint" , $NewPoint );
			$pData->AddData( "GetPoint" , $GetPoint );
			$pData->AddData( "RefillDate" , $RefillDate );
			$pData->AddData( "UpdateDate" , $UpdateDate );
			$pData->NextData();
		}
		
		phpFastCache::set( $CURRENT_SESSION_REFILL , $pData , DELAY_FOR_VIEWLOG );
	}
	
	for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
	{
		$ppData = $pData->GetData( $i );
?>
        <tr>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData["RefillDate"]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData["SerialTruemoney"]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $_CONFIG['tmpay']['amount'][$ppData["CardRank"]]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData["OldPoint"]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData["NewPoint"]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData["GetPoint"]; ?></td>
          <td align="center" bgcolor="#666666"><?php echo CBinaryCover::utf8_to_tis620( $_CONFIG['tmpay']['card_status'][$ppData["Status"]] ); ?></td>
        </tr>
<?php
}
?>
        </table>
        </td>
    </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center"><img src="../images/truemoney.gif" width="557" height="220" /></td>
  </tr>
</table>
<?php
$cNeoSQLConnectODBC->CloseRanWeb();
?>