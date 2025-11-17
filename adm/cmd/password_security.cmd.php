<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

CInput::GetInstance()->BuildFrom( IN_POST );

function SECUTITY_PROC()
{
    global $_CONFIG;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $Password = CInput::GetInstance()->GetValueString( "password" , IN_POST );
    if ( strcmp( $Password , $cAdmin->GetPassCard() ) != 0 )
    {
        die( "ERROR:PASSWORD" );
    }
    
    $cLang = new CLang;
    $cLang->SetDataFromDB($MemNum);
    $mail_subject = "พบการเข้าระบบด้วยรหัสผ่านระดับ TOP จาก {USERNAME} ส่งจาก GameCenterShop";
    $mail_message = "สวัสคุณ <b>{USERNAME}</b><br>เราพบการล็อกอินเข้าระบบด้วยรหัสผ่านระดับ TOP เมื่อ <b>%s</b> จาก IP <b>%s</b><br>ลิ้งร้านค้าของคุณคือ : <b><a href={FOLDERSHOP}>{FOLDERSHOP}</a></b>";
    $mail_message = sprintf( $mail_message , GetToday() , CGlobal::getIP() );
    $cLang->TransCode($mail_subject);
    $cLang->TransCode($mail_message);
    //$mail_subject_utf8 = CBinaryCover::tis620_to_utf8( $mail_subject );
    //$mail_message_utf8 = CBinaryCover::tis620_to_utf8( $mail_message );
    //sendMail( $cAdmin->GetEmail() , $mail_subject_utf8 , $mail_message_utf8 );
    sendMail( $cAdmin->GetEmail() , $mail_subject , $mail_message ); // ส่งทางนี้เพราะหน้าแอดมินรันบน utf-8 อยู่แล้ว
    
    $cAdmin->SetLoginPassCard( true );
    CInput::GetInstance()->AddValue( $_CONFIG["ADM"]["SESSION"] , serialize( $cAdmin ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS:WORK";
}

function SECURITY_UI()
{
    global $_CMD;
?>
<div id="main_security">
    <table id="table_security" style="width:500px;">
        <tr colspan="2"><b><u>กรุณาใส่รหัสผ่านระดับสูงสุดเพื่อยืนยันว่าคุณเป็นแอดมินระดับท็อป</u></b></tr>
        <tr>
            <td style="width:150px;"><b>Security Password</b></td>
            <td style="width:350px;"><input type="password" id="password" style="width:200px;"></td>
        </tr>
        <tr>
            <td colspan="2"><div align="center"><input type="hidden" id="linkBak" value="<?php echo $_CMD; ?>"><button id="workSubmit" onclick="dosec();">ตกลง</button></div></td>
        </tr>
    </table>
</div>
<script type="text/javascript" src="js/classicloading.js"></script>
<script type="text/javascript" src="js/securitysecond.js"></script>
<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $submit )
    SECUTITY_PROC ();
else
    SECURITY_UI();
?>
