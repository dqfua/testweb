<?php
if ( !defined("ALL_BROWSER") )
define("ALL_BROWSER",true);

include( "../global.loader.php" );

CInput::GetInstance()->BuildFrom( IN_GET );

$img_code = CInput::GetInstance()->GetValueString( "img" , IN_GET );
$name_code = CInput::GetInstance()->GetValueString( "name" , IN_GET );

if ( empty( $img_code ) ) die("");
//die("ERROR|LOAD|IMG|FAIL");

phpFastCache::$storage = "auto";
$SES_C = "img_guild_".md5( $img_code );
$htmlImg = phpFastCache::get($SES_C);
if ( !$htmlImg )
{
	$htmlImg = "
	<style>
	body
	{
	margin:0px;
	};
	</style>
	";
	$htmlImg .= CApp::build_guimg($img_code,$name_code);
	
	phpFastCache::set($SES_C , $htmlImg, 3600*24 ); // 1 วัน
}

echo $htmlImg;

?>
