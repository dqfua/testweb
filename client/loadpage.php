<?php
session_start();
header('Content-Type: text/html; charset=windows-874');
include_once("loader.php");

CInput::GetInstance()->BuildFrom( IN_GET );
$page = CInput::GetInstance()->GetValueString( "page" , IN_GET );
if ( $page == "" )
include("../memberpage/newitem.php");
elseif ( $page == "menu" )
include("../memberpage/menu.php");
elseif ( $page == "headman_menu" )
include("../memberpage/headman_menu.php");
elseif ( $page == "seeitem" )
include("../memberpage/seeitem.php");
elseif ( $page == "hotitem" )
include("../memberpage/hotitem.php");
elseif ( $page == "item" )
include("../memberpage/item.php");
elseif ( $page == "login" )
include("../memberpage/login.php");
elseif ( $page == "logout" )
include("../memberpage/logout.php");
elseif ( $page == "register" )
include("../memberpage/register.php");
elseif ( $page == "member" )
include("../memberpage/member.php");
elseif ( $page == "chalogin" )
include("../memberpage/chalogin.php");
elseif ( $page == "reborngetfreepoint" )
include("../memberpage/reborngetfreepoint.php");
elseif ( $page == "forgetpassword" )
include("../memberpage/forgetpassword.php");
elseif ( $page == "buy" )
include("../memberpage/buy.php");
elseif ( $page == "refill" )
include("../memberpage/refill.php");
elseif ( $page == "refill2" )
include("../memberpage/refill2.php");
elseif ( $page == "refill_b" )
include("../memberpage/refill_b.php");
elseif ( $page == "refill_bonus" )
include("../memberpage/refill_bonus.php");
elseif ( $page == "indexmenu" )
include("../memberpage/indexmenu.php");
elseif ( $page == "indexmenunew" )
include("../memberpage/indexmenunew.php");
elseif ( $page == "chalogin" )
include("../memberpage/chalogin.php");
elseif ( $page == "changepassgame" )
include("../memberpage/changepassgame.php");
elseif ( $page == "checkcharpass" )
include("../memberpage/checkcharpass.php");
elseif ( $page == "disconnect" )
include("../memberpage/disconnect.php");
elseif ( $page == "changeschool" )
include("../memberpage/changeschool.php");
elseif ( $page == "changeclass" )
include("../memberpage/changeclass.php");
elseif ( $page == "charmad" )
include("../memberpage/charmad.php");
elseif ( $page == "reborn" )
include("../memberpage/reborn.php");
elseif ( $page == "resell" )
include("../memberpage/resell.php");
elseif ( $page == "work_resell" )
include("../memberpage/work_resell.php");
elseif ( $page == "skilldown" )
include("../memberpage/skilldown.php");
elseif ( $page == "time2point" )
include("../memberpage/time2point.php");
elseif ( $page == "st_change" )
include("../memberpage/st_change.php");
elseif ( $page == "resetskill" )
include("../memberpage/resetskill.php");
elseif ( $page == "changename" )
include("../memberpage/changename.php");
elseif ( $page == "checkname" )
include("../memberpage/checkname.php");
elseif ( $page == "mapwarp" )
include("../memberpage/mapwarp.php");
elseif ( $page == "showsomeinfo" )
include("../memberpage/showsomeinfo.php");
elseif ( $page == "buyskillpoint" )
include("../memberpage/buyskillpoint.php");
elseif ( $page == "setskillset" )
include("../memberpage/setskillset.php");
elseif ( $page == "privatemenu" )
include("../client/privatemenu.php");
elseif ( $page == "loaduserdata" )
include("../client/loaduserdata.php");
elseif ( $page == "loadgame" )
include("../client/loadgame.php");
elseif ( $page == "updatecone" )
include("../client/updatecone.php");
elseif ( $page == "sendtoitembank" )
include("../client/sendtoitembank.php");
elseif ( $page == "sendend" )
include("../client/sendend.php");
elseif ( $page == "captcha" )
include("../memberpage/captcha.php");
elseif ( $page == "helppage" )
include("../memberpage/helppage.php");
elseif ( $page == "sessionout" )
include("../memberpage/sessionout.php");
elseif ( $page == "copyright_foot" )
include("../memberpage/copyright_foot.php");
elseif ( $page == "log_itempointget" )
include("../memberpage/log_itempointget.php");
elseif ( $page == "chadelete" )
include("../memberpage/chadelete.php");

elseif ( $page == "sqlite_test" )
include("../memberpage/sqlite_test.php");
?>