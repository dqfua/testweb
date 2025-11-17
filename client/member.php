<?php
//session_start();
//header('Content-Type: text/html; charset=windows-874');
include("loader.php");
//include("logon.php");
$pUser = NULL;
if ( COnline::OnlineGoodCheck( $pUser ) != ONLINE ){	exit;}
//$pUser = unserialize( CGlobal::GetSesUser() );
?>
<table width="605" border="0" align="center" cellpadding="2" cellspacing="2" bgcolor="#000000">
  <tr>
    <td width="597" valign="top">
    <img src="../images/pic01.gif" width="600" height="252" border="0" />
<?php
include("../memberpage/memberinfo.php");
include("../memberpage/log_login.php");
echo"<br><hr><br>";
include("../memberpage/logplaygame.php");
echo"<br><hr><br>";
$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
if ( $bChaLogin )
{
	include("../memberpage/chainfo.php");
	echo"<br><hr><br>";
	include("../memberpage/logchangename.php");
	echo"<br><hr><br>";
	include("../memberpage/logmapwarp.php");
	echo"<br><hr><br>";
	include("../memberpage/logchangeschool.php");
	echo"<br><hr><br>";
	include("../memberpage/logchangeclass.php");
	echo"<br><hr><br>";
	include("../memberpage/logreborn.php");
	echo"<br><hr><br>";
	include("../memberpage/log_stat.php");
	echo"<br><hr><br>";
	include("../memberpage/logresetskill.php");
	echo"<br><hr><br>";
}
include("../memberpage/log_buyskillpoint.php");
echo"<br><hr><br>";
include("../memberpage/log_time2point.php");
echo"<br><hr><br>";
include("../memberpage/work_resell.php");
echo"<br><hr><br>";
include("../memberpage/logresell.php");
echo"<br><hr><br>";
include("../memberpage/logbuy.php");
echo"<br><hr><br>";
include("../memberpage/log_itempointget.php");
echo"<br><hr><br>";
include("../memberpage/log_repointinvitefriends.php");
echo"<br><hr><br>";
include("../memberpage/log_bonuspoint.php");
echo"<br><hr><br>";
?>
<div align="center">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="5">
	  <tr>
	    <td height="39" colspan="7" align="center" bgcolor="#000000"><center>
	      <strong>ประวัติการเติมเงิน ( แสดง 20 รายการล่าสุดของคุณ ) </strong>
	      </center></td>
	    </tr>
	  <tr>
	    <td width="14%" height="25" align="center" bgcolor="#333333">วันเวลา</td>
	    <td width="20%" height="25" align="center" bgcolor="#333333">Password</td>
	    <td width="10%" height="25" align="center" bgcolor="#333333">ราคา</td>
        <td width="14%" height="25" align="center" bgcolor="#333333">พ้อยเก่า</td>
    <td width="13%" height="25" align="center" bgcolor="#333333">พ้อยใหม่</td>
    <td width="15%" height="25" align="center" bgcolor="#333333">พ้อยที่ได้</td>
	    <td width="14%" align="center" bgcolor="#333333">สถานะ</td>
	    </tr>
	  <?php
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT TOP 20 SerialTruemoney,Status,CardRank,RefillDate,UpdateDate,OldPoint,NewPoint,GetPoint FROM Refill WHERE MemNum = %d AND UserNum = %d ORDER BY RefillNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$UserNum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$SerialTruemoney = substr( $cNeoSQLConnectODBC->Result("SerialTruemoney",ODBC_RETYPE_ENG) , 0 , 14 );
	$Status = $cNeoSQLConnectODBC->Result("Status",ODBC_RETYPE_INT);
	$CardRank = $cNeoSQLConnectODBC->Result("CardRank",ODBC_RETYPE_INT);
	$OldPoint = $cNeoSQLConnectODBC->Result("OldPoint",ODBC_RETYPE_INT);
	$NewPoint = $cNeoSQLConnectODBC->Result("NewPoint",ODBC_RETYPE_INT);
	$GetPoint = $cNeoSQLConnectODBC->Result("GetPoint",ODBC_RETYPE_INT);
	$RefillDate = substr( $cNeoSQLConnectODBC->Result("RefillDate",ODBC_RETYPE_ENG) , 0 , 16 );
	$UpdateDate = substr( $cNeoSQLConnectODBC->Result("UpdateDate",ODBC_RETYPE_ENG) , 0 , 16 );
?>
	  <tr>
	    <td height="25" align="center" bgcolor="#666666"><?php echo $RefillDate; ?></td>
	    <td height="25" align="center" bgcolor="#666666"><?php echo $SerialTruemoney; ?></td>
	    <td height="25" align="center" bgcolor="#666666"><?php echo $_CONFIG['tmpay']['amount'][$CardRank]; ?></td>
        <td height="25" align="center" bgcolor="#666666"><?php echo $OldPoint; ?></td>
        <td height="25" align="center" bgcolor="#666666"><?php echo $NewPoint; ?></td>
        <td height="25" align="center" bgcolor="#666666"><?php echo $GetPoint; ?></td>
	    <td align="center" bgcolor="#666666"><?php echo CBinaryCover::utf8_to_tis620( $_CONFIG['tmpay']['card_status'][$Status] ); ?></td>
	    </tr>
	  <?php
}
$cNeoSQLConnectODBC->CloseRanWeb();
?>
    </table>
</div>
    </td>
  </tr>
  </table>
<br /><hr /><BR />