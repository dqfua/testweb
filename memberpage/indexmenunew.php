<?php
$pUser = NULL;
if ( COnline::OnlineGoodCheck( $pUser ) != ONLINE ){ exit;}

class TheMenu
{
	public $cWeb;
	public $cMemBuySkillPoint;
};

$CURRENT_SESSION = sprintf( "%d_themenu" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$cWeb = NULL;
$cMemBuySkillPoint = NULL;

$pMenu = unserialize( phpFastCache::get( $CURRENT_SESSION ) );
if ( !$pMenu )
{
	$pMenu = new TheMenu;
	
	$cWeb = new CNeoWeb;
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	
	$cMemBuySkillPoint = new CMemBuySkillPoint;
	$cMemBuySkillPoint->LoadData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	
	$pMenu->cWeb = $cWeb;
	$pMenu->cMemBuySkillPoint = $cMemBuySkillPoint;
	
	phpFastCache::set( $CURRENT_SESSION , serialize( $pMenu ) , 300+floor( rand()%300 )  );
}else{
	$cWeb = $pMenu->cWeb;
	$cMemBuySkillPoint = $pMenu->cMemBuySkillPoint;
}

$Charmad = $cWeb->GetSys_Charmad();
$Class = $cWeb->GetSys_Class();
$School = $cWeb->GetSys_School();
$Reborn = $cWeb->GetSys_CharReborn();
$SkillOn = $cWeb->GetSys_SkillOn();
$gamepoint = $cWeb->GetSys_GameTime();
?>
<table width="100%" border="0" cellpadding="3" cellspacing="5">
<tr>
    <td><strong><a href="#menu" onClick=" loadpage('login','area',null); ">ข้อมูลส่วนตัว</a></strong></td>
    <td><strong><a href="#menu" onClick=" loadpage('chalogin','area',null); ">เลือกตัวละคร</a></strong></td>
    <td><strong><a href="#menu" onClick=" loadpage('changepassgame','area',null); ">เปลี่ยนรหัสเข้าเกมส์</a></strong></td>
    <td><strong><a href="#menu" onClick="logout();">ออกจากระบบ</a></strong></td>
  </tr>
<tr>
  <td><strong><a href="#menu" onClick=" loadpage('checkcharpass','area',null); ">เปลี่ยนรหัสลบตัวละคร</a></strong></td>
  <td><strong><a href="#menu" onClick=" loadpage('disconnect','area',null); ">แก้ไอดีค้าง</a></strong></td>
  <td><?php if ( $gamepoint ){ ?><strong><a href="#menu" onClick=" loadpage('time2point','area',null); ">เวลาออนไลน์แลกพ้อย</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  <td>&nbsp;</td>
  </tr>
<tr>
  <td><?php if ( $Charmad ){ ?><strong><a href="#menu" onClick=" loadpage('charmad','area',null); ">แก้หัวแดง</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  <td><?php if ( $Class ){ ?><strong><a href="#menu" onClick=" loadpage('changeclass','area',null); ">เปลี่ยนอาชีพ</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  <td><?php if ( $School ){ ?><strong><a href="#menu" onClick=" loadpage('changeschool','area',null); ">เปลี่ยนโรงเรียน</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  <td><?php if ($cWeb->Sys_ResetSkill ){ ?><strong><a href="#menu" onclick=" loadpage('resetskill','area',null); ">รีสกิว</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  </tr>
<tr>
  <td><strong><a href="#menu" onClick=" loadpage('resell','area',null);/*setTimeout( function(){ loadpage('work_resell','area_resell_info',''); } , 5000 );*/">ขายไอเทมคืน</a></strong></td>
  <td><?php if ( $Reborn ){ ?><strong><a href="#menu" onClick=" loadpage('reborn','area',null); ">จุติ</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  <td><?php if ( $SkillOn ) { ?><strong><a href="#menu" onClick=" loadpage('skilldown','area',null); ">Skill Down</a></strong><?php }else{ echo "&nbsp"; } ?></td>
  <td><?php if ( $cWeb->Sys_StatOn ){ ?><strong><a href="#menu" onClick=" loadpage('st_change','area',null); ">ปรับแต่งสเตตัส</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  </tr>
<tr>
  <td><?php if ( $cWeb->Sys_MapWarp ){ ?><strong><a href="#menu" onclick=" loadpage('mapwarp','area',null); ">ย้ายแมพ</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  <td><?php if ( $cWeb->Sys_ChangeName ){ ?><strong><a href="#menu" onclick=" loadpage('changename','area',null); ">เปลี่ยนชื่อ</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  <td><?php if ( $cMemBuySkillPoint->ModeOn ){ ?><strong><a href="#menu" onclick=" loadpage('buyskillpoint','area',null); ">ซื้อแต้มสกิว</a></strong><?php }else{ echo "&nbsp;"; } ?></td>
  <td>&nbsp;</td>
</tr>
<tr>
  <td colspan="4">
<?php
if ( $cWeb->GetSys_ParentID() )
{
	printf( "ลิ้งแนะนำไอดีของคุณ : <input type=\"text\" id=\"linkmyparentid\" value=\"%s\" style=\"width:299px\" onmouseover=\"javascript:this.focus();this.select();\"><button onclick=\"javascript:ClipBoard($('#linkmyparentid').val())\">Copy</button>"
		   , sprintf("%s/popup/register.php?memnum=%d&logo=1&bgbody=1&bgcolor=000&ParentID=%s"
					 ,$_CONFIG["HOSTLINK"]
					 , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
					 , $UserID = $pUser->GetUserID()
					 )
		   );
}
?>
  </td>
  </tr>
</table>
