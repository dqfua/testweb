<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
</head>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/th_TH/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<body>
<?php
include( "../ajax.loader.php" );
include( "../global.loader.php" );

CInput::GetInstance()->BuildFrom( IN_GET );

$MemNum = CInput::GetInstance()->GetValueInt( "memnum" , IN_GET );

if ( $MemNum == 0 || empty( $MemNum ) ) exit;
$bGood = CNeoWeb::CheckMemNumGood( $MemNum );
if ( !$bGood ) exit;

$itemprojectnum = CInput::GetInstance()->GetValueInt( "itemprojectnum" , IN_GET );
if ( $itemprojectnum <= 0 ) exit;
?>
<div class="fb-like" data-href="<?php echo $_CONFIG["HOSTLINK"]; ?>?fb_comment=1&memnum=<?php echo $MemNum; ?>&itemprojectnum=<?php echo $itemprojectnum; ?>" data-send="false" data-width="450" data-show-faces="false" data-font="verdana"></div>
<fb:comments href="<?php echo $_CONFIG["HOSTLINK"]; ?>?fb_comment=1&memnum=<?php echo $MemNum; ?>&itemprojectnum=<?php echo $itemprojectnum; ?>" num_posts="2" width="620"></fb:comments>
</body>
</html>