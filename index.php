<html>
<head>
<title>Gamecentershop.com บริการไอเทมช็อปสำหรับเกมส์ออนไลน์</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" /></head>
<body>
<p><strong><h1>GameCenterShop.com เว็บให้บริการไอเทมช็อปสำหรับเกมส์ออนไลน์</h1></strong></p>
<p><u>เกมส์ที่เรา Support ในขณะนี้ :</u><br />
1. Ran Online , 3,000 บาท / เดือน</p>
<p>&nbsp;</p>
<p>เว็บไซต์ของเราไม่ได้ให้บริการเซิร์ฟเวอร์เกมส์ออนไลน์ เราเป็นเพียงแค่เว็บที่ช่วยจัดการฐานข้อมูลให้กับคุณ<br />
ซึ้งเราจะมีหน้าแอดมินที่ช่วยจัดการส่วนต่างๆของคุณให้ง่ายขึ้นโดยไม่จำเป็นต้องเข้าไปจัดการฐานข้อมูลเองกับมือ<br />
ลดความเสี่ยงต่อการเสียของข้อมูลและสิ่งที่คุณจะได้อีกอย่างหนึ่งคือหน้าเว็บช็อปที่จะเป็นลิ้งโดยตรงจากช็อปของเราเช่น<br />
http://gamecentershop.com/gamexxxx/ เป็นต้น
</p>
<p>&nbsp;</p>
<p>สิ่งที่คุณจำเป็นจะต้องมีมาก่อนจะใช้บริการของเราคือเซิร์ฟเวอร์เกมส์ของคุณที่พร้อมใช้งานพร้อมเปิดบริการออนไลน์<br />
  และมี ดาต้าเบสที่เป็นมาตรสากล พอที่จะนำมาใช่งานร่วมกับเรา สามารถขอคำแนะนำเพิ่มเติมได้หากมีปัญหา 
</p>
<p>&nbsp;</p>
<p>ถ้าสนใจบริการของเราติดต่อ Skype : ts_sgame[แอด]hotmail.com หรือ Email : ts_sgame[แอด]hotmail.com</p>
<p>&nbsp;</p>
<?php
echo "<p><b>ลูกค้าที่กำลังใช้บริการกับเราในขณะนี้!!</b><br />";
include( "global.loader.php" );

$SESSION_CURRENT = "memberinfo_shop";
$pData = phpFastCache::get( $SESSION_CURRENT );
if ( !$pData )
{
	$pData = new _tdata();
	$cSQL = new CNeoSQLConnectODBC();
	$cSQL->ConnectRanWeb();
	$szTemp = sprintf( "SELECT MemberNum,ServerName,ServerType,Reg_ShopFolder FROM MemberInfo WHERE Reg_Shop = 1 AND MemBan = 0 AND MemDelete = 0 ORDER BY MemberNum DESC" );
	$cSQL->QueryRanWeb( $szTemp );
	while( $cSQL->FetchRow() )
	{
		$pData->AddData( "MemberNum" , $cSQL->Result( "MemberNum" , ODBC_RETYPE_INT ) );
		$pData->AddData( "ServerName" , $cSQL->Result( "ServerName" , ODBC_RETYPE_THAI ) );
		$pData->AddData( "ServerType" , $cSQL->Result( "ServerType" , ODBC_RETYPE_INT ) );
		$pData->AddData( "Reg_ShopFolder" , $cSQL->Result( "Reg_ShopFolder" , ODBC_RETYPE_THAI ) );
		$pData->NextData();
	}
	$cSQL->CloseRanWeb();
	phpFastCache::set( $SESSION_CURRENT , $pData , 300 );
}

for( $i = 0 ; $i < $pData->GetRollData() ; $i++ )
{
	$ppData = $pData->GetData( $i );
	printf( "[%d] : [%s][%s] >> <u><a href=\"%s%s\" target=\"_blank\">ไปที่ช็อปนี้</a></u><br>\n"
						 , $ppData["MemberNum"]
						 , $ppData["ServerName"]
						 , $_CONFIG["SERVER_TYPE"][$ppData["ServerType"]]
						 , $_CONFIG["HOSTLINK"]
						 , $ppData["Reg_ShopFolder"] );
}
?>
  
</p>
<p>&nbsp;</p>
<p><b>ปล. เว็บไซต์ของเรามิได้มีส่วนเกี่ยวข้องใดๆในการให้บริการเซิร์ฟเวอร์ของลูกค้าดั่งกล่าวเพราะเราเป็นเพียงแค่ส่วนในการจัดการ<br>
  ฐานข้อมูลเท่านั้น!!
</b></p>
</body>
</html>