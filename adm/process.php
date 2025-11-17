<?php
session_start();

include_once 'inc\\config.inc.php';

include_once '..\admin.loader.php';
include_once '..\global.loader.php';
include_once '..\lang.loader.php';

//if ( HostDomainChecking( "HOSTLINK_ADMIN" ) ) die( "ERROR 404" );

define("PROCESS_HEADER", "TRUE");

CInput::GetInstance()->BuildFrom( IN_GET );

$cAdmin = unserialize( CInput::GetInstance()->GetValue( $_CONFIG["ADM"]["SESSION"] , IN_SESSION ) );
if ( $cAdmin )
{
	$MemNum = $cAdmin->GetMemNum();
	if ( $MemNum > 0 )
	{
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $MemNum );
		$cWeb->GetSysmFromDB( $MemNum );
		if ( $cWeb->GetServerType() == SERVTYPE_EP3 )
		{
			include_once("../class/chainven_ep3.class.php");
		}
		else if ( $cWeb->GetServerType() == SERVTYPE_EP7 )
		{
			include_once("../class/chainven_ep7.class.php");
		}
		else if ( $cWeb->GetServerType() == SERVTYPE_PLUSONLINE )
		{
			include_once("../class/chainven_plusonline.class.php");
		}
                else if ( $cWeb->GetServerType() == SERVTYPE_EP8 )
		{
			include_once("../class/chainven_ep8.class.php");
		}
	}
        include_once("../class/chainven.class.php");
}

$_CMD = CInput::GetInstance()->GetValueString( "cmd" , IN_GET );

switch( $_CMD )
{
    case "player":
    {
        require_once 'cmd\\player.cmd.php';
    }break;
	case "playeronline":
	{
		require_once 'cmd\\playeronline.cmd.php';
	}break;

    case "set":
    {
        require_once 'cmd\\set.cmd.php';
    }break;

    case "password_security":
    {
        require_once 'cmd\\password_security.cmd.php';
    }break;

    case "itemproject_list":
    {
        require_once 'cmd\\itemproject_list.cmd.php';
    }break;

    case "itemproject_add":
    {
        require_once 'cmd\\itemproject_add.cmd.php';
    }break;

    case "giveitem":
    {
        require_once 'cmd\\giveitem.cmd.php';
    }break;

    case "mapwarp":
    {
        require_once 'cmd\\mapwarp.cmd.php';
    }break;

    case "skilltable":
    {
        require_once 'cmd\\skilltable.cmd.php';
    }break;

    case "report":
    {
        require_once 'cmd\\report.cmd.php';
    }break;

    case "sub_item_add":
    {
        require_once 'cmd\\sub_item_add.cmd.php';
    }break;

    case "sub_item_edit":
    {
        require_once 'cmd\\sub_item_edit.cmd.php';
    }break;

    case "sub_item_list":
    {
            require_once 'cmd\\sub_item_list.cmd.php';
    }break;

    case "truemoney":
    {
        require_once 'cmd\\truemoney.cmd.php';
    }break;

    case "truemoney_feedback":
    {
        require_once 'cmd\\truemoney_feedback.cmd.php';
    }break;

    case "database":
    {
        require_once 'cmd\\database.cmd.php';
    }break;

    case "password":
    {
        require_once 'cmd\\password.cmd.php';
    }break;

    case "password2":
    {
        require_once 'cmd\\password2.cmd.php';
    }break;

    case "setup":
    {
        require_once 'cmd\\setup.cmd.php';
    }break;

    case "main_menu":
    {
        require_once 'cmd\\menu.cmd.php';
    }break;

    case "logout":
    {
        require_once 'cmd\\logout.cmd.php';
    }break;
	
    case "dashboards":
    {
        require_once 'cmd\\dashboards.cmd.php';
    }break;

    case "showLogChangeName":
    {
        require_once 'cmd\\dashboards\\logchangename.cmd.php';
    }break;

    case "export":
    {
            require_once 'cmd\\export.cmd.php';
    }break;

    case "news":
    {
            require_once 'news.php';
    }break;
    
    default:
    {
        if ( $cAdmin )
        {
            require_once 'cmd\\home.cmd.php';
        }else{
            require_once 'cmd\\login.cmd.php';
        }
    }break;
}

?>
