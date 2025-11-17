<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

define( "DELAY_FOR_VIEWLOG" , 60/*1นาที*/ );

require_once 'itemproject.cmd.php';
require_once "player/user.cmd.php";
require_once "player/user.log.cmd.php";
require_once "player/char.cmd.php";
require_once "player/char.log.cmd.php";

function CMD_UI()
{
?>
<div id="player_main">
    <div id="player_help">
        <p><b><u>ระบบตรวจสอบและแก้ไขข้อมูลของผู้เล่น</u></b><br>
        <b>คำเตือน!!</b> ถ้าแก้ไขไปแล้วจะไม่สามารถกู้กลับคืนมาได้!!
        </p>
    </div>
    <div id="player_menu">
        <p>Menu : <button id="character_mode">ตรวจสอบตัวละคร</button><button id="user_mode">ตรวจสอบไอดี</button></p>
    </div>
    <div id="player_process"></div>
</div>
<script type="text/javascript" src="js/player.js"></script>
<?php
}

$type = CInput::GetInstance()->GetValueInt( "type" , IN_GET );
switch($type)
{
    //char viewer
    case 100:
    {
        CHARACTER_LOGVIEW_MAPMOVE();
    }break;

    case 101:
    {
        CHARACTER_LOGVIEW_CHANGESCHOOL();
    }break;

    case 102:
    {
        CHARACTER_LOGVIEW_CHANGENAME();
    }break;

    case 103:
    {
        CHARACTER_LOGVIEW_CHANGECLASS();
    }break;

    case 104:
    {
        CHARACTER_LOGVIEW_BUYSKILLPOINT();
    }break;

    case 105:
    {
        CHARACTER_LOGVIEW_REBORN();
    }break;

    case 106:
    {
        CHARACTER_LOGVIEW_CHARMAD();
    }break;

    case 107:
    {
        CHARACTER_LOGVIEW_STAT();
    }break;

    case 108:
    {
        CHARACTER_LOGVIEW_RESETSKILL();
    }break;

    //char editor
    case 1001:
    {
        CHARACTER_UI();
    }break;
    
    case 1002:
    {
        CHARACTER_SEARCH();
    }break;

    case 5000:
    {
        CHARACTER_VIEWINFO_HEAD();
        CHARACTER_LOGVIEW_MODE();
        CHARACTER_EDITOR_MODE();
    }break;

    case 7000:
    {
        CHARACTER_SEL();
    }break;

    case 7001:
    {
        CHARACTER_EDITOR_UI();
    }break;

    case 7002:
    {
        CHARACTER_EDITOR_PROC();
    }break;

    case 10001:
    {
        CHARACTER_EDITOR_CHAINVEN_UI();
    }break;

    case 10002:
    {
        CHARACTER_EDITOR_ITEMEDITOR_UI( );
    }break;

    case 10003:
    {
        CHARACTER_EDITOR_ITEMEDITOR_SAVE( );
    }break;

    case 20001:
    {
        CHARACTER_EDITOR_CHAPUTONITEMS_UI();
    }break;

    case 20002:
    {
        CHARACTER_EDITOR_CHAPUTONITEMS_EDITOR_UI();
    }break;

    case 20003:
    {
        CHARACTER_EDITOR_CHAPUTONITEMS_SAVE();
    }break;

    case 20004:
    {
        CHARACTER_EDITOR_CHAPUTONITEMS_SAVE( true );
    }break;

    case 30001:
    {
        CHARACTER_EDITOR_CHASKILL_UI();
    }break;

    case 30002:
    {
        CHARACTER_EDITOR_CHASKILL_DEL();
    }break;

    case 30003:
    {
        CHARACTER_EDITOR_CHASKILL_EDIT();
    }break;

    case 30004:
    {
        CHARACTER_EDITOR_CHASKILL_ADD();
    }break;
	
	case 400001:
    {
        CHARACTER_EDITOR_CHARNAME();
    }break;
	
	case 400002:
    {
        CHARACTER_PROC_CHARNAME();
    }break;
	
	case 500001:
    {
		CHARACTER_EDITOR_CHARGUNAME();
    }break;
	
	case 500002:
    {
		CHARACTER_PROC_CHARGUNAME();
    }break;

    //user editor
    case 2001:
    {
        USER_UI();
    }break;
    
    case 2002:
    {
        USER_SEARCH();
    }break;

    case 2003:
    {
        USER_SEL();
    }break;

    case 2004:
    {
        USER_EDITOR_UI();
    }break;

    //user log viewer
    case 3000:
    {
        USER_LOGVIEW_BANUSER();
    }break;

    case 3001:
    {
        USER_LOGVIEW_REFILL();
    }break;

    case 3002:
    {
        USER_LOGVIEW_BUY();
    }break;

    case 3003:
    {
        USER_LOGVIEW_RESELL();
    }break;

    case 3004:
    {
        USER_LOGVIEW_LOGINITEMSHOP();
    }break;

    case 3005:
    {
        USER_LOGVIEW_REFILLITEMFEEDBACK();
    }break;

    case 3006:
    {
        USER_LOGVIEW_UPDATEPOINT();
    }break;

    case 3007:
    {
        USER_LOGVIEW_TIME2POINT();
    }break;

    case 3008:
    {
        USER_LOGVIEW_LOGINGAME();
    }break;
	
    case 3009:
    {
        USER_LOGVIEW_BONUSPOINTINVITEFRIENDS();
    }break;

    case 3010:
    {
        USER_LOGVIEW_FORGETPASSWORD();
    }break;

    case 3011:
    {
        USER_LOGVIEW_BONUSPOINT();
    }break;

    case 3012:
    {
        USER_LOGVIEW_SHOPBANK_VIEW();
    }break;

    case 3013:
    {
        USER_LOGVIEW_SHOPBANK_VIEW_CMD_DELETE();
    }break;

    case 3100:
    {
        USER_LOGVIEW_CHARALL();
    }break;

    case 40000:
    {
        USER_INFO_EDITOR();
    }break;

    case 40001:
    {
        USER_INFO_SAVE();
    }break;

    case 40002:
    {
        USER_INFO_PASS_EDITOR( 0 );
    }break;

    case 40003:
    {
        USER_INFO_PASS_EDITOR( 1 );
    }break;

    case 40004:
    {
        USER_INFO_PASS_EDITOR_SAVE( 0 );
    }break;

    case 40005:
    {
        USER_INFO_PASS_EDITOR_SAVE( 1 );
    }break;

    case 41000:
    {
        USER_LOCKER_EDITOR();
    }break;

    case 41001:
    {
        USER_LOCKER_EDITOR_UI();
    }break;

    case 41002:
    {
        USER_LOCKER_EDITOR_SAVE();
    }break;

    case 41003:
    {
        USER_LOCKER_EDITOR_SAVE( true );
    }break;

    case 100000:
    {
        USER_BANIPSET_UI();
    }break;

    case 100001:
    {
        USER_BANIPSET_PROCESS();
    }break;

    default:{
        CMD_UI();
    }break;
}
?>
