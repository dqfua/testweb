<div align="center">
<?php
//$bLogin = CGlobal::GetSesUserLogin();
//$pUser = unserialize( CGlobal::GetSesUser() );

$pUser = NULL;
if ( COnline::OnlineGoodCheck( $pUser ) != ONLINE ){	exit;}

$id = $pUser->GetUserID();
$point = $pUser->GetUserPoint();
$gametime = $pUser->GetGameTime();
$createdate = substr( $pUser->GetCreateDate() , 0 , 16 );
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td colspan="2" align="left"><strong>ข้อมูลของสมาชิก</strong></td>
  </tr>
  <tr>
    <td width="147"><strong>ไอดี</strong></td>
    <td width="418"><?php echo $id; ?></td>
  </tr>
  <tr>
    <td><strong>พ้อยคงเหลือ</strong></td>
    <td><?php echo $point; ?></td>
  </tr>
  <tr>
    <td><strong>เวลาออนไลน์ในปัจจุบัน</strong></td>
    <td><?php echo $gametime; ?></td>
  </tr>
  <tr>
    <td><strong>สมัครเมื่อ</strong></td>
    <td><?php echo $createdate; ?></td>
  </tr>
</table>
</div>