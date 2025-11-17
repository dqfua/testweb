<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

CInput::GetInstance()->BuildFrom( IN_POST );

function CMD_PROC()
{
    global $_CONFIG;
    global $cAdmin;
    
    $current_password = CInput::GetInstance()->GetValueString( "current_password" , IN_POST );
    $password = CInput::GetInstance()->GetValueString( "password" , IN_POST );
    $password2 = CInput::GetInstance()->GetValueString( "password2" , IN_POST );
    
    function password_check_length( $text ){ return ( strlen($text) < 4 || strlen($text) > 16 ); }
    
    if ( password_check_length( $password ) )
    {
        echo "ERROR:EASY";
        return false;
    }
    if ( strcmp( $password , $password2 ) != 0 )
    {
        echo "ERROR:BANLANSE";
        return false;
    }
    if ( strcmp($current_password,$cAdmin->GetPass()) != 0 )
    {
        echo "ERROR:NOTYES";
        return false;
    }
    $cAdmin->UpdatePass( $password );
    
    CInput::GetInstance()->AddValue( $_CONFIG["ADM"]["SESSION"] , serialize( $cAdmin ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS:" . $password;
    
    return true;
}

function CMD_UI()
{
?>

<div id="main_password">

<table>
    <tr><td colspan="2"><div id="title" align="left"><b><u>เปลี่ยนรหัสผ่าน</u></b></div></td></tr>
    <tr>
        <td><div align="right">รหัสปัจจุบัน :</div></td>
        <td><div align="left"><input type="password" id="current_password" class="edittext"></div></td>
    </tr>
    <tr>
        <td><div align="right">รหัสผ่านใหม่ :</div></td>
        <td><div align="left"><input type="password" id="password" class="edittext"></div></td>
    </tr>
    <tr>
        <td><div align="right">ยืนยันอีกครั้ง :</div></td>
        <td><div align="left"><input type="password" id="password2" class="edittext"></div></td>
    </tr>
    <tr><td colspan="2"><div align="right"><button id="change_password">ยืนยัน</button></div></td></tr>
</table>

</div>

<script type="text/javascript" src="js/password.js"></script>

<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $submit )
    CMD_PROC();
else
    CMD_UI();

?>
