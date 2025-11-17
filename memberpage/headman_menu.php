<?php
/*
if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) )
{
	die("<div align=center><font color=red><b>?????????????? ??????????????????!!</b></font></div>");
}
$cUser = unserialize( CGlobal::GetSesUser() );
*/
$bLogin = false;
$bExPoint = false;
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) == ONLINE )
{
	/*
	$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
	CGlobal::SetSesUser( serialize($cUser) );
	$cUser = unserialize( CGlobal::GetSesUser() );
	
	$UserID = $cUser->GetUserID();
	$UserNum = $cUser->GetUserNum();
	*/
	$bLogin = true;
	
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
	
	if ( $cWeb->GetSys_BonusPoint() ) $bExPoint = true;
}

?>
<script type="text/javascript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<body onLoad="MM_preloadImages('../images/left/<?php echo ( $bLogin ) ? "shop_left_record_07a.png" : "shop_left_04a.jpg"; ?>','../images/left/refill2.png','../images/shop_left_07a.png','../images/shop_left_record_07a.png')"><table width="217" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td><img src="../images/left/shop_left_02.jpg" width="217" height="13" /></td>
  </tr>
  <tr>
    <td align="left"><div id="ctl00_ShowImgB"> <a onClick="loadpage('login','area',null);" href="#menu" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image86','','../images/left/<?php echo ( $bLogin ) ? "shop_left_record_07a.png" : "shop_left_04a.jpg"; ?>',1)"> <img src="../images/left/<?php echo ( $bLogin ) ? "shop_left_record_07.png" : "shop_left_04.jpg"; ?>" name="Image86" width="217" height="92" border="0" id="Image86" /></a></div>
      <img src="../images/left/shop_left_06.jpg" width="217" height="11" /></td>
  </tr>
<?php
if ( $bLogin )
{
?>
  <tr>
    <td><a onClick="loadpage('refill','area',null);" href="#menu" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image32','','../images/left/refill2.png',1)"><img src="../images/left/refill1.png" name="Image32" border="0" id="Image32" /></a></td>
  </tr>
<?php
	if ( $bExPoint )
	{
?>
	<tr>
    <td><img src="../images/left/shop_left_06.jpg" width="217" height="11" /></td>
    </tr>
  <tr>
    <td><a onClick="loadpage('refill_b','area',null);" href="#menu" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ImageBTExPoint','','../images/shop_left_08a.png',1)"><img src="../images/shop_left_08.png" name="ImageBTExPoint" border="0" id="ImageBTExPoint" /></a></td>
  </tr>
<?php
	}
}
if ( !$bLogin )
{
?>
  <tr>
    <td><img src="../images/left/shop_left_08.jpg" width="217" height="14" /><br />
      <a href="#" onClick="loadpage('register&memnum=<?php echo $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]; ?>&logo=1','area',null);" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image33','','../images/shop_left_07a.png',1)"><img src="../images/shop_left_07.png" name="Image33" width="217" height="79" border="0" id="Image33" /></a><br />
      <img src="../images/left/shop_left_08.jpg" width="217" height="14" /></td>
  </tr>
<?php
}
?>
  </table>
