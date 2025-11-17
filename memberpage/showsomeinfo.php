<?php
$pUser = NULL;
if ( COnline::OnlineGoodCheck( $pUser ) != ONLINE ){ exit;}

phpFastCache::$storage = "auto";

$CURRENT_SESSION_WEB = sprintf( "webdata_%d_%d"
														, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
														, $pUser->GetUserNum() );
$cWeb = unserialize( phpFastCache::get( $CURRENT_SESSION_WEB ) );
if ( !$cWeb )
{
	$cWeb = new CNeoWeb;
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	
	phpFastCache::set( $CURRENT_SESSION_WEB , serialize( $cWeb ) , 300+floor( rand()%300 )  );
}

$UserPoint = 0;
$GameTime = 0;

$CURRENT_SESSION_POINT = sprintf( "pointdata_%d_%d"
														, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
														, $pUser->GetUserNum() );
$CURRENT_SESSION_TIME = sprintf( "timedata_%d_%d"
														, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
														, $pUser->GetUserNum() );
$CURRENT_SESSION_BONUSPOINT = sprintf( "bonuspointdata_%d_%d"
														, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
														, $pUser->GetUserNum() );
$pp = phpFastCache::get($CURRENT_SESSION_POINT);
if ( !$pp ){
	$UserPoint = $pUser->GetUserPoint();
	phpFastCache::set( $CURRENT_SESSION_POINT , $UserPoint , 60 );
}else $UserPoint = $pp;

$pp = phpFastCache::get($CURRENT_SESSION_TIME);
if ( !$pp ){
	$GameTime = $pUser->GetGameTime();
	phpFastCache::set( $CURRENT_SESSION_TIME , $GameTime , 60 );
}else $GameTime = $pp;

$pp = phpFastCache::get($CURRENT_SESSION_BONUSPOINT);
if ( !$pp ){
	$BonusPoint = $pUser->GetBonusPoint();
	phpFastCache::set( $CURRENT_SESSION_BONUSPOINT , $BonusPoint , 60 );
}else $BonusPoint = $pp;

printf( "พ้อยของคุณคือ : <font color='#fffc00'><b>%d</b></font> เวลาออนไลน์ของคุณคือ : <font color='#fffc00'><b>%d</b></font>", $UserPoint, $GameTime );

if ( $cWeb->GetSys_BonusPoint() )
{
	printf( " แต้มสะสมคงเหลือ : <font color='#fffc00'><b>%d</b></font>", $BonusPoint );
}

printf(" <a href='#update' onclick='autoupdatesomeinfo();'><b><font color='#7EFF00'>Update Now</font></b></a>");
?>