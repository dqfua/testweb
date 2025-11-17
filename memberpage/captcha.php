<?php
include_once( "../class/captcha.class.php" );
include_once( "../class/simplecaptcha.class.php" );
include_once( "../class/sec.class.php" );
include_once( "../class/global.class.php" );

CInput::GetInstance()->BuildFrom( IN_GET );

$nsize = CInput::GetInstance()->GetValueInt( "size" , IN_GET );
$sessionname = CInput::GetInstance()->GetValueString( "sessionname" , IN_GET );
$simplecapchar = new SimpleCaptcha;
$simplecapchar->nSize = $nsize;
$simplecapchar->SeassionName = $sessionname;
$simplecapchar->Result();
$simplecapchar->Display();
/*
$capchar = new Captcha();
$capchar->size = nsize;
$capchar->session=sessionname;
$capchar->display();
*/
?>