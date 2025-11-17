<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>Forget Password : ลืมรหัสผ่าน</title>

<?php
include( "../ajax.loader.php" );
include( "../global.loader.php" );

CInput::GetInstance()->BuildFrom( IN_GET );

$MemNum = CInput::GetInstance()->GetValueInt( "memnum" , IN_GET );
if ( $MemNum == 0 || empty( $MemNum ) ) exit;
$bGood = CNeoWeb::CheckMemNumGood( $MemNum );
if ( !$bGood ) exit;

$bLogo = CInput::GetInstance()->GetValueInt( "logo" , IN_GET );
if ( $bLogo != 1 ) $bLogo = 0;

$bBgBody = CInput::GetInstance()->GetValueInt( "bgbody" , IN_GET );
$DxBgBody_Color = "#000";
if ( $bBgBody != 1 ) $bBgBody = 0;
else
{
	$DxBgBody_Color = "#".CInput::GetInstance()->GetValueString( "bgcolor" , IN_GET );
}
?>

<style type="text/css">
<!--
body,td,th {
	font-size: 14px;
	color: #FFF;
}
body {
<?php
if ( $bBgBody == 1 )
{
?>
	background-color: <?php echo $DxBgBody_Color; ?>;
<?php
}
?>
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
a:link {
	color: #FFF;
}
a:visited {
	color: #FFF;
}
a:hover {
	color: #F00;
}
a:active {
	color: #FFF;
}
-->
</style></head>

<body onload="loadpage('forgetpassword&memnum=<?php echo $MemNum; ?>&logo=<?php echo $bLogo; ?>','area',null);">

<div id="area"></div>

</body>
</html>
