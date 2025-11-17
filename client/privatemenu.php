<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
$MemNum = $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"];
if ( $MemNum == 15/*RAN EXTEEN*/ || $MemNum == 21 )
{
?>
<p><strong>เมนูส่วนตัวประจำเซิร์ฟเวอร์ |  Private Menu In this server.</strong></p>
<div align="left" style=" margin-left:39px;">
<table width="100%" border="0" cellpadding="3" cellspacing="5">
<tr>
    <td><a href="neobingo/index.php" target="_blank">เกมส์ Bingo</a> | <a href="neobingo/help/help.html" target="_blank">วิธีเล่นเกม</a></td>
    </tr>
</table>
</div>
<p>
  <?php
}
?>
</p>
<p>&nbsp;</p>
