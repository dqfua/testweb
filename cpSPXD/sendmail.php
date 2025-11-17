<?php
if ( !defined("SHOPNEOCP") ) die("HACKING....");

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
$membernum = CInput::GetInstance()->GetValueInt( "membernum" , IN_GET );

if ( $membernum == 0 ) die("ERROR|MEMBER|FAIL");
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT
        MemID,MemPass,MemPass_Card,ServerName,EMail,MemType,ServerType,
        Reg_Shop,Reg_ShopFolder,Reg_DateOpen,Reg_DateOpenEnd,MemDelete
        ,MemCreateDate
        ,DAY( Reg_DateOpenEnd ) as End_Day , MONTH( Reg_DateOpenEnd ) as End_Month , YEAR( Reg_DateOpenEnd ) as End_Year
        ,DateDiff(DAY,getdate(),Reg_DateOpenEnd) as DelayTime
        ,DateDiff(DAY,Reg_DateOpen,getdate()) as DelayUse
        FROM MemberInfo WHERE MemberNum = %d"
        ,$membernum);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
$bWork = false;
while( $cNeoSQLConnectODBC->FetchRow() )
{
    $bWork = true;
    
    $MemID = $cNeoSQLConnectODBC->Result("MemID",ODBC_RETYPE_ENG);
    $MemPass = $cNeoSQLConnectODBC->Result("MemPass",ODBC_RETYPE_ENG);
    $MemPass_Card = $cNeoSQLConnectODBC->Result("MemPass_Card",ODBC_RETYPE_ENG);
    $ServerName = $cNeoSQLConnectODBC->Result("ServerName",ODBC_RETYPE_ENG);
    $EMail = $cNeoSQLConnectODBC->Result("EMail",ODBC_RETYPE_THAI);
    $MemType = $cNeoSQLConnectODBC->Result("MemType",ODBC_RETYPE_INT);
    $ServerType = $cNeoSQLConnectODBC->Result("ServerType",ODBC_RETYPE_INT);
    $Reg_Shop = $cNeoSQLConnectODBC->Result("Reg_Shop",ODBC_RETYPE_INT);
    $Reg_ShopFolder = $cNeoSQLConnectODBC->Result("Reg_ShopFolder",ODBC_RETYPE_THAI);
    $Reg_DateOpen = $cNeoSQLConnectODBC->Result("Reg_DateOpen",ODBC_RETYPE_ENG);
    $Reg_DateOpenEnd = $cNeoSQLConnectODBC->Result("Reg_DateOpenEnd",ODBC_RETYPE_ENG);
    $End_Day = $cNeoSQLConnectODBC->Result("End_Day",ODBC_RETYPE_INT);
    $End_Month = $cNeoSQLConnectODBC->Result("End_Month",ODBC_RETYPE_INT);
    $End_Year = $cNeoSQLConnectODBC->Result("End_Year",ODBC_RETYPE_INT);
    $MemDelete = $cNeoSQLConnectODBC->Result("MemDelete",ODBC_RETYPE_INT);
    $MemCreateDate = $cNeoSQLConnectODBC->Result("MemCreateDate",ODBC_RETYPE_ENG);
    $DelayTime = $cNeoSQLConnectODBC->Result("DelayTime",ODBC_RETYPE_INT);
    $DelayUse = $cNeoSQLConnectODBC->Result("DelayUse",ODBC_RETYPE_INT);
}
if ( $bWork == false ) { echo("ERROR|MEMBERERROR"); $submit = 0; }
$subject = $_LANG["MAIL"]["SHOP"]["SUBJECT"];
$message = "";
if ( $submit == 1 )
{
	$subject = CInput::GetInstance()->GetValueString( "subject" , IN_POST );
	$message = CInput::GetInstance()->GetValueString( "message" , IN_POST );
	
    //$subject = CBinaryCover::tis620_to_utf8( $subject );
    //$message = CBinaryCover::tis620_to_utf8( $message );
    //$subject = base64_encode($subject);
    //$message = base64_encode($message);

    $cLang = new CLang;
    $cLang->MemberNum = $membernum;
    $cLang->MemID = $MemID;
    $cLang->Daytouse = $DelayTime;
    $cLang->RegTime = $Reg_DateOpen;
    $cLang->EndTime = $Reg_DateOpenEnd;
    $cLang->FolderShop = $Reg_ShopFolder;
    $cLang->Dayuse = $DelayUse;
    //$cLang->TransCode($subject);
    //$cLang->TransCode($message);

    $send_value = sprintf("http://sendmail.neomasteI2.com/sendmail_get.php?MailTo=%s&MailFrom=%s&MailSubject=%s&MailMessage=%s"
                    ,$EMail,$_CONFIG["MAIL_CONTENT"],$subject,$message
                   );
    $curl_content = file_get_contents( $send_value );
    echo "$send_value<br>";
    echo "$curl_content<br>";
    echo "<font color='#00FF00'><b>ส่งเมล์เรียบร้อยแล้ว</b></font><br>";
    //$subject = base64_decode($subject);
    //$message = base64_decode($message);
    //$subject = CBinaryCover::utf8_to_tis620( $subject );
    //$message = CBinaryCover::utf8_to_tis620( $message );
}
$cNeoSQLConnectODBC->CloseRanWeb();

$send = CInput::GetInstance()->GetValueInt( "send" , IN_GET );

$mail_type = CInput::GetInstance()->GetValueInt( "type" , IN_GET );
$autogo = "";
if ( $mail_type > 0 )
{
    $autogo = true;
}

$mail_subject = $_LANG["MAIL"]["SHOP"]["SUBJECT"][$mail_type];
$mail_message = $_LANG["MAIL"]["SHOP"]["MESSAGE"][$mail_type];

if ( $send == 1 )
{
	$autogo = true;
	$mail_subject = CInput::GetInstance()->GetValueString( "subject" , IN_POST );
	$mail_message = CInput::GetInstance()->GetValueString( "message" , IN_POST );
}

$cLang = new CLang;
$cLang->MemberNum = $membernum;
$cLang->MemID = $MemID;
$cLang->Daytouse = $DelayTime;
$cLang->RegTime = $Reg_DateOpen;
$cLang->EndTime = $Reg_DateOpenEnd;
$cLang->FolderShop = $Reg_ShopFolder;
$cLang->Dayuse = $DelayUse;
$cLang->TransCode($mail_subject);
$cLang->TransCode($mail_message);

function try_utf( &$string )
{
	$string = str_replace( "&#45;" , "-" , $string ) ;
}
try_utf( $EMail );

if ( $autogo )
{
	$mail_subject_utf8 = CBinaryCover::tis620_to_utf8( $mail_subject );
	$mail_message_utf8 = CBinaryCover::tis620_to_utf8( $mail_message );
	/*
	$wspro = new WSPro();
	$wspro->AddSendMail( $EMail , $mail_subject_utf8 , $mail_message_utf8 , $EMail , $mail_subject , $mail_message );
	$wspro->DumpToFile();
	*/
	sendMail( $EMail , $mail_subject_utf8 , $mail_message_utf8 );
	
	printf( "ส่งเมล์ถึง : %s<br>\nหัวข้อ : %s<br>\nเนื้อหา : %s<br>\n" , $EMail , $mail_subject , $mail_message );
}else{
	printf( "<form method='post' action='?pid=sendmail&membernum=%d&send=1'>ส่งถึง : %s<br>\n" , $membernum , $EMail );
	printf( "หัวข้อ : <input type='text' name='subject' id='subject' value='%s' style='199px;' /><br>\n" , $mail_subject );
	printf( "<textarea name='message' cols='100' rows='10' id='message'>%s</textarea><br>\n" , $mail_message );
	printf( "<input type='submit'></form>" );
}
?>
