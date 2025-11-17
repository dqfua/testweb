<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<TITLE>Administrator Control Panel</title>
</head>

<body>
<?php
include( "loader.php" );
define( "SHOPNEOCP",true );
$cCpLogin = NULL;
if ( CGlobal::GetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] ) != NULL )
{
    $cCpLogin = unserialize( CGlobal::GetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] ) );
    if ( CCPLogin::CheckLogin($cCpLogin) )
        print("");
    else
        die("ERROR|LOGIN");
}else{
    die("ERROR|LOGIN");
}


?>
Administrator Control Panel<br>
ศูนย์กลางระบบควบคุม Shop-Center<br><br>
ล็อกอินโดย : <b><?php echo $cCpLogin->UserName;?></b><br>
<TABLE CELLPADDING="2" CELLSPACEING="2">
    <TR>
        <TD>
            <a href="home.php?pid=">หน้าแรก</a>
        </td>
        <TD>
            <a href="home.php?pid=checkuser">ตรวจสอบความคืบหน้าของ User</a>
        </td>
        <TD>
            <a href="home.php?pid=finduser">ค้นหา User</a>
        </td>
        <TD>
            <a href="home.php?pid=finddeluser">ถังขยะ</a>
        </td>
        <TD>
            <a href="logout.php">ออกจากระบบ</a>
        </td>
    </tr>
</table><br>
<div style="margin-left:19px;">
<?php
CInput::GetInstance()->BuildFrom( IN_GET );
$pid = CInput::GetInstance()->GetValueString( "pid" , IN_GET );
switch( $pid )
{
    case "":
            include("news.php");
    break;
    case "finduser":
            include("finduser.php");
    break;
    case "finddeluser":
            include("finddeluser.php");
    break;
    case "checkuser":
            include("checkuser.php");
    break;
    case "edit":
            include("edituser.php");
    break;
    case "sendmail":
            include("sendmail.php");
    break;
    case "ban":
            include("banuser.php");
    break;
    case "unban":
            include("unbanuser.php");
    break;
    case "del":
            include("deluser.php");
    break;
    case "undel":
            include("undeluser.php");
    break;
	case "deldata":
            include("deldata.php");
    break;
}
?>
</div>

</body>
</html>