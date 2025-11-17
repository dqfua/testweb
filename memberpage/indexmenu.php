<?php
include("logon.php");
$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$Charmad = $cWeb->GetSys_Charmad();
$Class = $cWeb->GetSys_Class();
$School = $cWeb->GetSys_School();
$Reborn = $cWeb->GetSys_CharReborn();
$SkillOn = $cWeb->GetSys_SkillOn();
$gamepoint = $cWeb->GetSys_GameTime();
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
</script><body onLoad="MM_preloadImages('../images/button/charmad2.png','../images/button/reseller2.png')">

<table width="200" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td><a href="#home" onClick=" loadpage('login','area',null); " onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image14','','../images/button/home2.png',0)"><img src="../images/button/home.png" name="Image14" width="220" height="60" border="0" id="Image14" /></a><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image14','','../images/button/home2.png',0)"></a></td>
  </tr>
  <tr>
    <td><a href="#logout" onClick=" loadpage('chalogin','area',null); " onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image1','','../images/button/character2.png',0)"><img src="../images/button/character.png" name="Image1" width="220" height="60" border="0" id="Image1" /></a><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image2','','../images/button/changepassgame2.png',0)"></a></td>
  </tr>
  <tr>
    <td><a href="#menu" onClick=" loadpage('changepassgame','area',null); " onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image2','','../images/button/changepassgame2.png',0)"><img src="../images/button/changepassgame.png" name="Image2" width="220" height="60" border="0" id="Image2" /></a></td>
  </tr>
  <tr>
    <td><a href="#menu" onClick=" loadpage('checkcharpass','area',null); " onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image3','','../images/button/changepasschar2.png',0)"><img src="../images/button/changepasschar.png" name="Image3" width="220" height="60" border="0" id="Image3" /></a></td>
  </tr>
  <!--
  <tr>
    <td><a href="#" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image4','','../images/button/card2point2.png',0)"><img src="../images/button/card2point.png" name="Image4" width="220" height="60" border="0" id="Image4" /></a></td>
  </tr>
  -->
<?php
if ( $Charmad )
{
?>
  <tr>
    <td><a href="#menu" onClick=" loadpage('charmad','area',null); " onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image5','','../images/button/charmad2.png',1)"><img src="../images/button/charmad.png" name="Image5" width="220" height="60" border="0" id="Image5" /></a></td>
  </tr>
<?php
}
if ( $Class )
{
?>
  <tr>
    <td><a href="#menu" onClick=" loadpage('changeclass','area',null); " onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image6','','../images/button/class2.png',0)"><img src="../images/button/class.png" name="Image6" width="220" height="60" border="0" id="Image6" /></a></td>
  </tr>
<?php
}
?>
  <tr>
    <td><a href="#menu" onClick=" loadpage('disconnect','area',null); " onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image7','','../images/button/disconnect2.png',0)"><img src="../images/button/disconnect.png" name="Image7" width="220" height="60" border="0" id="Image7" /></a></td>
  </tr>
  <tr>
    <td><a href="#menu" onClick=" loadpage('resell','area',null); " onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image8','','../images/button/reseller2.png',1)"><img src="../images/button/reseller.png" name="Image8" width="220" height="60" border="0" id="Image8" /></a></td>
  </tr>
<?php
if ( $School )
{
?>
  <tr>
    <td><a href="#menu" onClick=" loadpage('changeschool','area',null); "  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image9','','../images/button/school2.png',0)"><img src="../images/button/school.png" name="Image9" width="220" height="60" border="0" id="Image9" /></a></td>
  </tr>
<?php
}
if ( $Reborn )
{
?>
  <tr>
    <td><a href="#menu" onClick=" loadpage('reborn','area',null); "  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image11','','../images/button/reborn2.png',0)"><img src="../images/button/reborn.png" name="Image11" width="220" height="60" border="0" id="Image11" /></a></td>
  </tr>
<?php
}
if ( $SkillOn )
{
?>
  <tr>
    <td><a href="#menu" onClick=" loadpage('skilldown','area',null); "  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image12','','../images/button/skilldown2.png',0)"><img src="../images/button/skilldown.png" name="Image12" width="220" height="60" border="0" id="Image12" /></a></td>
  </tr>
<?php
}
if ( $gamepoint )
{
?>
	<tr>
    <td><a href="#menu" onClick=" loadpage('time2point','area',null); "  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image13','','../images/button/time2point2.png',0)"><img src="../images/button/time2point.png" name="Image13" width="220" height="60" border="0" id="Image13" /></a></td>
  </tr>
<?php
}
?>
	<tr>
    <td><a href="#menu" onClick=" loadpage('st_change','area',null); "  onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image15','','../images/button/stat2.png',0)"><img src="../images/button/stat.png" name="Image15" width="220" height="60" border="0" id="Image15" /></a></td>
  </tr>
  <tr>
    <td><a href="#logout" onClick="logout();" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('Image10','','../images/button/logout2.png',0)"><img src="../images/button/logout.png" name="Image10" width="220" height="60" border="0" id="Image10" /></a></td>
  </tr>
</table>
