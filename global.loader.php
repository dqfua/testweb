<?php
//include functions
include_once("include/functions.inc.php");
//loader debug class
include_once("class/debug.class.php");
//libary loader
include_once("lib.loader.php");
//loader global file
//include file
include_once("include/configure.inc.php");
//class file
//include global function file
include_once("class/global.class.php");
include_once("class/input.class.php");
//include_once("class/odbc.class.php");
include_once("class/sql.class.php");
include_once("class/sec.class.php");
include_once("class/sec2.class.php");
include_once("class/binarycover.class.php");
include_once("class/serialfile.class.php");
include_once("class/serialmemory.class.php");
include_once("class/captcha.class.php");
include_once("class/simplecaptcha.class.php");
include_once("class/app.class.php");
include_once("class/user.class.php");
include_once("class/cha.class.php");
include_once("class/web.class.php");
include_once("class/chaskill.class.php");
include_once("class/ranshop.class.php");
include_once("class/session.class.php");
include_once("class/log.class.php");
include_once("class/tmpay.class.php");
include_once("class/serial.class.php");
include_once("class/online.class.php");
include_once("class/maplist.class.php");
include_once("class/memclass.class.php");
include_once("class/membuyskillpoint.class.php");
include_once("class/skillset.class.php");
include_once("class/itempoint.class.php");
include_once("class/upload.class.php");
include_once("class/sqlite.class.php");
include_once("class/uidb.class.php");
include_once("class/wspro.class.php");
include_once("class/stat.class.php");
include_once("class/chainfo.class.php");
include_once("class/userinfo.class.php");
include_once("class/userinven.class.php");
//include_once("class/chaputonitems.class.php");
include_once("class/itemproject.class.php");

CInput::GetInstance()->BuildFrom( IN_SESSION );
$pIDShopData = unserialize( CInput::GetInstance()->GetValue( CGlobal::GetSessionShopIDData() , IN_SESSION ) );
if ( $pIDShopData )
{
	$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] = $pIDShopData->MemNum;
	$_CONFIG["SERVERMAN"]["SERVER_NAME"] = $pIDShopData->ServerName;
	//printf( "member.set.php %s , %d , %s" , $_SERVER['SCRIPT_FILENAME'] , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] ,  $_CONFIG["SERVERMAN"]["SERVER_NAME"] );
}

{
	$MemNum = 0;
	if ( defined("MODE_ADMIN") )
	{
            //disable for admin on this now,,
            //move to admin folder and process.php file
	}else{
		$MemNum = @$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"];
	}
	if ( $MemNum > 0 )
	{
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		$cWeb->GetSysmFromDB( $MemNum );
		if ( $cWeb->GetServerType() == SERVTYPE_EP3 )
		{
			include_once("class/chainven_ep3.class.php");
		}
		else if ( $cWeb->GetServerType() == SERVTYPE_EP7 )
		{
			include_once("class/chainven_ep7.class.php");
		}
		else if ( $cWeb->GetServerType() == SERVTYPE_PLUSONLINE )
		{
			include_once("class/chainven_plusonline.class.php");
		}
		else if ( $cWeb->GetServerType() == SERVTYPE_EP8 )
		{
			include_once("class/chainven_ep8.class.php");
		}
                include_once("class/chainven.class.php");
	}
}

//session เอาไว้เก็บ userid และ รหัสผ่านของสมาชิค จำเป็นต้องเปลี่ยนแต่ละเซิยเวอร์ห้ามเหมือนกัน
$time = date('Ymd');
if ( !isset( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] ) ) $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] = 0;
define("SESSION_MAN","neomasteI2sessionadministatorman1".$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"].$time);
define("SESSION_MAN_LOGIN","neomasteI2sessionadministatormanlogin1".$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"].$time);
define("SESSION_CHAMAN","neomasteI2sessionadministatorchaman1".$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"].$time);
define("SESSION_CHAMAN_LOGIN","neomasteI2sessionadministatorchamanlogin1".$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"].$time);

//setglobal
include_once("global.set.php");

//session_save_path(PATH_UPLOAD_CATCH_SESSION);
ini_set('session.gc_maxlifetime', 60*60); // 1 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.cookie_secure', FALSE);
ini_set('session.use_only_cookies', TRUE);
?>