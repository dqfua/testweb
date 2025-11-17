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

$CURRENT_SESSION_BONUSPOINT = sprintf( "userbonuspointdata_%d_%d"
														, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
														, $cUser->GetUserNum() );

$UserNum = $cUser->GetUserNum();
$UserID = $cUser->GetUserID();

$cTmPay = new TmPay;
$cTmPay->UpdateCardRank();
$Card50 = $cTmPay->GetBonusPointCard_50();
$Card90 = $cTmPay->GetBonusPointCard_90();
$Card150 = $cTmPay->GetBonusPointCard_150();
$Card300 = $cTmPay->GetBonusPointCard_300();
$Card500 = $cTmPay->GetBonusPointCard_500();
$Card1000 = $cTmPay->GetBonusPointCard_1000();

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
        <td height="29" align="center" bgcolor="#666666"><strong>แต้มที่จะได้รับ</strong></td>
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
<?php
if ( $cWeb->GetSys_BonusPointEx() )
{
?>
          <input type="button" name="button" id="button" value="เติมแต้ม" onclick="refill_bonus();" />
<?php
}else{
?>
          <input type="button" name="button" id="button" value="รับแต้มสะสม" onclick="refill_bonus();" />
<?php
}
?>
          </td>
          </tr>
      </table>
    </div></td>
  </tr>
  <tr>
  <td><table width="90%" align="center" cellpadding="5" cellspacing="5">
    <tr>
      <td height="25">
<?php
	$pData = phpFastCache::get( $CURRENT_SESSION_BONUSPOINT );
    if ( !$pData )
	{
		$pData = new _tdata();
		$szTemp = sprintf( "SELECT BeforeBonusPoint,NewBonusPoint,BonusPrice,SerialPassword,LogDate FROM Log_UserBonusPoint WHERE MemNum = %d AND UserNum = %d AND BeforeBonusPoint < NewBonusPoint" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum );
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$BeforeBonusPoint = $cNeoSQLConnectODBC->Result("BeforeBonusPoint",ODBC_RETYPE_INT);
			$NewBonusPoint = $cNeoSQLConnectODBC->Result("NewBonusPoint",ODBC_RETYPE_INT);
			$BonusPrice = $cNeoSQLConnectODBC->Result("BonusPrice",ODBC_RETYPE_INT);
			$SerialPassword = "XXXXXXX" . substr( $cNeoSQLConnectODBC->Result("SerialPassword",ODBC_RETYPE_ENG) , 6 , 14 );
			$LogDate = substr( $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG) , 0 , 16 );
			
			$pData->AddData( "BeforeBonusPoint" , $BeforeBonusPoint );
			$pData->AddData( "NewBonusPoint" , $NewBonusPoint );
			$pData->AddData( "BonusPrice" , $BonusPrice );
			$pData->AddData( "SerialPassword" , $SerialPassword );
			$pData->AddData( "LogDate" , $LogDate );
			$pData->NextData();
		}
		
		phpFastCache::set( $CURRENT_SESSION_BONUSPOINT , $pData , DELAY_FOR_VIEWLOG );
	}
?>
        <table width="100%" border="0" cellpadding="0" cellspacing="5">
        <tr>
          <td height="39" colspan="7" align="center" bgcolor="#000000"><center>
            <strong>ประวัติการรับแต้มสะสม ( แสดง 20 รายการล่าสุด ) </strong>
            </center></td>
        </tr>
        <tr>
          <td width="10%" height="25" align="center" bgcolor="#333333">วันเวลา</td>
          <td width="30%" height="25" align="center" bgcolor="#333333">Password</td>
          <td width="20%" height="25" align="center" bgcolor="#333333">แต้มก่อนรับ</td>
          <td width="20%" height="25" align="center" bgcolor="#333333">แต้มหลังรับ</td>
          <td width="20%" height="25" align="center" bgcolor="#333333">แต้มที่ได้รับ</td>
        </tr>
        
<?php
for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
{
	$ppData = $pData->GetData( $i );
?>
		<tr>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData[ "LogDate" ]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData[ "SerialPassword" ]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData[ "BeforeBonusPoint" ]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData[ "NewBonusPoint" ]; ?></td>
          <td height="25" align="center" bgcolor="#666666"><?php echo $ppData[ "BonusPrice" ]; ?></td>
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