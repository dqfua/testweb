<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

CInput::GetInstance()->BuildFrom( IN_POST );

$SESSION_LOGIN_CAPTCHA = "ADMINLOGINCAPTCHA";
$ADM_CURRENT_LOGIN_SESSION_DELAY = "ADM_CURRENT_LOGIN_SESSION_DELAY";

function CMD_PROC( )
{
    global $_CONFIG;
    global $SESSION_LOGIN_CAPTCHA;
    global $ADM_CURRENT_LOGIN_SESSION_DELAY;
    
    $ID = CInput::GetInstance()->GetValueString( "id" , IN_POST );
    $PASSWORD = CInput::GetInstance()->GetValueString( "password" , IN_POST );
    $CAPTCHA = CInput::GetInstance()->GetValueString( "sescode" , IN_POST );
    
    $Delay = CInput::GetInstance()->GetValueInt( $ADM_CURRENT_LOGIN_SESSION_DELAY , IN_SESSION );
    
    if ( time() - $Delay < 5/*seconds*/ )
    {
        die(":0:D:" . (time() - $Delay));
    }
    
    CInput::GetInstance()->AddValue( $ADM_CURRENT_LOGIN_SESSION_DELAY , time() , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    $szCaptcha = CInput::GetInstance()->GetValueString( $SESSION_LOGIN_CAPTCHA , IN_SESSION );
    if ( strcmp( $szCaptcha , $CAPTCHA ) != 0 )
    {
        CInput::GetInstance()->AddValue( $SESSION_LOGIN_CAPTCHA , "" , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
        die(":0:" . $szCaptcha . " == " . $CAPTCHA);
    }
    
    $cAdmin = new CAdmin;
    $cAdmin->Login( $ID , $PASSWORD );
    $bLogin = $cAdmin->GetLogin();
    if ( $bLogin )
    {
        CInput::GetInstance()->AddValue( $_CONFIG["ADM"]["SESSION"] , serialize( $cAdmin ) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
        echo ":1"; // return to ajax
    }else{
        CInput::GetInstance()->AddValue( $SESSION_LOGIN_CAPTCHA , "" , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
        echo ":0"; // return to ajax
    }
}

function CMD_UI()
{
    global $SESSION_LOGIN_CAPTCHA;
    
    /*
    $pCaptcha = new Captcha();
    $szCaptcha = $pCaptcha->RandCaptcha();
    CInput::GetInstance()->AddValue( $SESSION_LOGIN_CAPTCHA , $szCaptcha , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    */
    
    $simplecapchar = new SimpleCaptcha;
    $simplecapchar->nSize = 6;
    $simplecapchar->SeassionName = $SESSION_LOGIN_CAPTCHA;
    $simplecapchar->Result();
?>

<div id="main_login" align="center">

<table>
    <tr><td colspan="2"><div id="title" align="center"><b><u>Control Panel</u></b></div></td></tr>
    <tr>
        <td style="width:99px;"><div align="right">Admin ID :</div></td>
        <td style="width:199px;"><div align="left"><input type="text" id="id" class="edittext"></div></td>
    </tr>
    <tr>
        <td><div align="right">Password :</div></td>
        <td><div align="left"><input type="password" id="password" class="edittext"></div></td>
    </tr>
    <tr>
        <td><div align="right">Captcha :</div></td>
        <td>
            <div align="left">
                <?php
                Captcha::ShowQuickCaptcha( $SESSION_LOGIN_CAPTCHA );
                ?>
                <input type="text" id="sescode" class="edittext" value="">
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <div align="center">
                <?php /*<input type="hidden" id="sescode" value="<?php echo $szCaptcha; ?>">*/ ?>
                <button id="login">LOGIN</button>
            </div>
        </td>
    </tr>
</table>

</div>

<script type="text/javascript" src="js/login.js"></script>

<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $submit == 1 )
    CMD_PROC ();
else
    CMD_UI();

?>
