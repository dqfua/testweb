<?php
include( "loader.php" );

$CP_CURRENT_LOGIN_SESSION_DELAY = "CP_CURRENT_LOGIN_SESSION_DELAY";
$CP_SESSION_LOGIN_CAPTCHA = "CP_SESSION_LOGIN_CAPTCHA";

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $submit == 1 )
{
    $Delay = CInput::GetInstance()->GetValueInt( $CP_CURRENT_LOGIN_SESSION_DELAY , IN_SESSION );
    
    CInput::GetInstance()->AddValue( $CP_CURRENT_LOGIN_SESSION_DELAY , time() , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    if ( time() - $Delay < 5/*seconds*/ )
    {
        echo("ERROR|CP_CURRENT_LOGIN_SESSION_DELAY:" . (time() - $Delay) . "<br>");
        CGlobal::gopage("index.php");
        exit;
    }
    
    $CAPTCHA = CInput::GetInstance()->GetValueString( "sescode" , IN_POST );
    $szCaptcha = CInput::GetInstance()->GetValueString( $CP_SESSION_LOGIN_CAPTCHA , IN_SESSION );
    if ( strcmp( $szCaptcha , $CAPTCHA ) != 0 )
    {
        CInput::GetInstance()->AddValue( $CP_SESSION_LOGIN_CAPTCHA , "" , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
        //die("ERROR|CP_SESSION_LOGIN_CAPTCHA|" . $szCaptcha . " == " . $CAPTCHA);
        die("ERROR|CP_SESSION_LOGIN_CAPTCHA");
    }
    
    $cCpLogin = new CCPLogin();
    $cCpLogin->UserName = CInput::GetInstance()->GetValueString( "username" , IN_POST );
    $cCpLogin->Password = CInput::GetInstance()->GetValueString( "password" , IN_POST );
    if ( !$cCpLogin->Login() )
    {
        echo("ERROR|LOGIN|FAILING<br>");
    }else{
        echo("SUCCESS|LOGIN<br>");
        CGlobal::gopage("index.php");
        exit;
    }
}

$simplecapchar = new SimpleCaptcha;
$simplecapchar->nSize = 6;
$simplecapchar->SeassionName = $CP_SESSION_LOGIN_CAPTCHA;
$simplecapchar->Result();
?>
<TITLE>ADMINISTRATOR CONTROL PANEL LOGIN</title>
<TABLE>
    <tr>
        <td>
            <b>ADMINISTRATOR CONTROL PANEL LOGIN</b>
        </td>
    </tr>
    <tr>
        <td>
            <FORM action="login.php?submit=1" method="post">
            <TABLE>
                <tr>
                    <td>
                        UserName :
                    </td>
                    <td>
                        <input type="text" name="username">
                    </td>
                </tr>
                <tr>
                    <td>
                        Password :
                    </td>
                    <td>
                        <input type="password" name="password">
                    </td>
                </tr>
                <tr>
                    <td>
                        Password :
                    </td>
                    <td>
                        <?php
                        Captcha::ShowQuickCaptcha( $CP_SESSION_LOGIN_CAPTCHA );
                        ?>
                        <br>
                        <input type="text" name="sescode">
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="LOGIN">
                    </td>
                    <td>&nbsp;
                        
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        COPYRIGHT (C) 2012 BY NEOMASTEI2,NEOMASTER
                    </td>
                </tr>
            </table>
            </form>
        </td>
    </tr>
</table>
