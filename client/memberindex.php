<?php
// =================================================================
// ==  MEMBER INDEX - FIXED VERSION                               ==
// == แก้ไขปัญหา "Headers Already Sent" โดยการย้ายส่วนประมวลผลไว้ข้างบน
// =================================================================

// 1. เริ่มต้นด้วย Session ก่อนเสมอ
session_start();

// 2. ตรวจสอบและ Redirect ถ้า URL ไม่ลงท้ายด้วย /
// แก้ไขโค้ดให้กระชับและแก้ไข syntax ของ header()
if (substr($_SERVER['REQUEST_URI'], -1) !== '/') {
    // แก้ไข: "Location : " ต้องเป็น "Location:" (ไม่มีช่องว่าง)
    header("Location: " . $_SERVER['REQUEST_URI'] . "/");
    exit;
}

// 3. ทำการประมวลผลทั้งหมดก่อน แล้วเก็บผลลัพธ์ไว้ในตัวแปร
include("loader.php");

CInput::GetInstance()->BuildFrom(IN_GET);
 $shopid = CInput::GetInstance()->GetValueString("id", IN_GET);

 $pIDShopData = new IDShopData;
 $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
 $cNeoSQLConnectODBC->ConnectRanWeb();
 $szTemp = sprintf("SELECT MemberNum,ServerName FROM MemberInfo WHERE Reg_ShopFolder = '%s' AND MemDelete = 0 ORDER BY MemberNum DESC", $shopid);
 $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
while ($cNeoSQLConnectODBC->FetchRow()) {
    $pIDShopData->MemNum = $cNeoSQLConnectODBC->Result("MemberNum", ODBC_RETYPE_INT);
    $pIDShopData->ServerName = $cNeoSQLConnectODBC->Result("ServerName", ODBC_RETYPE_ENG);
}
 $cNeoSQLConnectODBC->CloseRanWeb();

CInput::GetInstance()->AddValue(CGlobal::GetSessionShopIDData(), serialize($pIDShopData), IN_SESSION);
CInput::GetInstance()->UpdateSession();

 $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] = $pIDShopData->MemNum;
 $_CONFIG["SERVERMAN"]["SERVER_NAME"] = $pIDShopData->ServerName;

// 4. ตรวจสอบว่าพบร้านค้าหรือไม่ ถ้าไม่พบให้หยุดทำงานทันที
if (!$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]) {
    die("ไม่พบร้านค้า");
}

// 5. สุ่มค่าสำหรับแสดงสินค้าใหม่
 $nRand = round(rand() % 100);
 $bShowNewItem = ($nRand >= 50);

// --- สิ้นสุดส่วนของการประมวลผล (Logic) ---
// --- เริ่มต้นส่วนของการแสดงผล (Presentation) ---
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
<meta http-equiv="pragma" content="no-cache" />
<title><?php echo htmlspecialchars($_CONFIG["SERVERMAN"]["SERVER_NAME"]); ?> - Power By GameCenterShop.com</title>
<link href="../css/default.css" rel="stylesheet" type="text/css" />
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
<style type="text/css">
<!--
body {
    background-image: url(../images/bg/groundzero_03.jpg);
}
-->
</style></head>
<body onload="loaddefault(<?php echo ($bShowNewItem) ? '1' : '0'; ?>);MM_preloadImages('/images/left/refill2.png','../images/a01/shop_a01_06ccc.jpg')">
<div align="center">
  <table width="971" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td><table width="971" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td bgcolor="#1c1c1c"><table width="971" border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="217" valign="top"><table width="217" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><div id="area_man_menu">&nbsp;</div></td>
                </tr>
                
                <tr>
                  <td><img src="../images/left/shop_left_08.jpg" width="217" height="14" /><br /></td>
                </tr>
                <tr>
                  <td><img src="../images/left/shop_left_09.jpg" width="217" height="46" /></td>
                </tr>
                <tr>
                  <td><img src="../images/left/shop_left_10.jpg" width="217" height="24" /></td>
                </tr>
                <tr>
                  <td align="left" valign="top" style="background:url(../images/left/shop_left_12.jpg); background-repeat:repeat-y;"><table border="0" cellpadding="0" cellspacing="0" style=" margin-left:39px;width:180px;;">
                    <tr>
                      <td><div id="menu"></div></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../images/left/shop_left_32.jpg" width="217" height="29" /></td>
                </tr>
                <tr>
                  <td><img src="../images/left/shop_left_08.jpg" width="217" height="14" /></td>
                </tr>
                <tr>
                  <td><img src="../images/left/shop_left_08.jpg" width="217" height="14" /></td>
                </tr>
                <tr>
                  <td align="right">
                  <iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F%23%21%2Fpages%2Fgamecentershopcom%2F165413903600880&amp;width=200&amp;height=590&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:200px; height:590px;" allowTransparency="true"></iframe>
                  </td>
                </tr>
              </table></td>
              <td width="754" valign="top"><table width="754" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><img src="../images/right/shop_right_04.jpg"  width="754" height="5" /></td>
                </tr>
                <tr>
                  <td valign="top" background="../images/right/shop_right_05.jpg"><table width="754" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="19">&nbsp;</td>
                      <td width="697" valign="top"><table width="697" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td valign="top"><table width="697" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="492" valign="top">
                              <div id="indexmenunew" align="left"></div>
                              <div id="indexsomeinfo" align="left" style="margin-left:39px;"></div>
                              </td>
                            </tr>
                          </table></td>
                        </tr>
                        <tr>
                          <td valign="top"><table width="697" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><table width="697" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="165"><img src="../images/a01/shop_a01_03a.jpg"  width="165" height="58" /></td>
                                  <td width="171"><img src="../images/a01/shop_a01_04.jpg" width="171" height="58" /></td>
                                  <td width="10"><img src="../images/a01/shop_a01_05.jpg" width="205" height="58" /></td>
                                  <td width="351"><a onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image172','','../images/a01/shop_a01_06ccc.jpg',1)"><img src="../images/a01/shop_a01_06aaa.gif" name="Image172" width="156" height="58" border="0" id="Image172" /></a></td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td><table border="0" cellpadding="0" cellspacing="0" width="705">
                                <tr>
                                  <td width="6">&nbsp;</td>
                                  <td><table id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1" cellspacing="0" cellpadding="0" border="0" style="width:697px;border-collapse:collapse;">
                                    <tr>
                                      <td><table>
                                        <tr>
                                          <td width='100%'><div id="area"></div></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                  </table>
                                    <div align="center" class="style3"></div></td>
                                </tr>
                              </table></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                          </table></td>
                        </tr>
                      </table></td>
                      <td width="38">&nbsp;</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><img src="../images/right/shop_right_59.jpg" width="754" height="15" /></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td bgcolor="#1c1c1c"><div id="copyright_foot"></div></td>
        </tr>
        <tr>
          <td><img src="../images/right/shop_right_64.jpg" width="971" height="14" /></td>
        </tr>
      </table></td>
    </tr>
  </table>
</div>
</body>
</html>