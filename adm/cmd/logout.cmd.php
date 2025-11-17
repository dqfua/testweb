<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

CInput::GetInstance()->DelValue( $_CONFIG["ADM"]["SESSION"] , IN_SESSION );
CInput::GetInstance()->UpdateSession();

session_destroy();
?>