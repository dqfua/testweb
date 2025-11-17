<?php
header('Content-Type: text/html; charset=utf-8');
include( "../global.loader.php" );
include( "../lang.loader.php" );
$code_html = "";
$wspro = new WSPro();
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC2 = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$cNeoSQLConnectODBC2->ConnectRanWeb();
$szTemp = sprintf( "SELECT
                    MemberNum
                    ,EMail
                    ,DateDiff(DAY,getdate(),Reg_DateOpenEnd) as DelayTime
                    FROM MemberInfo WHERE MemDelete = 0 AND MemBan = 0 AND Reg_Shop = 1
                    " );
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
while( $cNeoSQLConnectODBC->FetchRow() )
{
    $MemberNum = $cNeoSQLConnectODBC->Result("MemberNum",ODBC_RETYPE_INT);
    $DelayTime = $cNeoSQLConnectODBC->Result("DelayTime",ODBC_RETYPE_INT);
    $EMail = $cNeoSQLConnectODBC->Result("EMail",ODBC_RETYPE_THAI);
    if ( $DelayTime == 15 || $DelayTime == 7 || $DelayTime == 3 || $DelayTime == 1 )
    {
        $MailType = 1;
        $szTemp = sprintf( "SELECT TOP 1 LogNum FROM App_Log_Admin_SendMail
            WHERE MemNum = %d AND
            DAY( getdate() ) = DAY( SendDate ) AND MONTH( getdate() ) = MONTH( SendDate ) AND YEAR( getdate() ) = YEAR( SendDate )
            AND MailType = %d
            " , $MemberNum , $MailType );
        $cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
        while( !$cNeoSQLConnectODBC2->FetchRow() )
        {
            $szTemp = sprintf( "INSERT INTO App_Log_Admin_SendMail( MemNum , MailType ) VALUES( %d,%d )" , $MemberNum , $MailType );
            $cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
            $cLang = new CLang;
            $cLang->SetDataFromDB($MemberNum);
            $mail_subject = $_LANG["MAIL"]["SHOP"]["SUBJECT"][$MailType];
            $mail_message = $_LANG["MAIL"]["SHOP"]["MESSAGE"][$MailType];
			$cLang->TransCode($mail_subject);
            $cLang->TransCode($mail_message);
			$mail_subject_utf8 = CBinaryCover::tis620_to_utf8( $mail_subject );
            $mail_message_utf8 = CBinaryCover::tis620_to_utf8( $mail_message );
			$wspro->AddSendMail( $EMail , $mail_subject_utf8 , $mail_message_utf8 , $EMail , $mail_subject , $mail_message );
            break;
        }
        continue;
    }else if ( $DelayTime == 0 ){
        $MailType = 2;
        $szTemp = sprintf( "SELECT LogNum FROM App_Log_Admin_SendMail
            WHERE MemNum = %d AND
            DAY( getdate() ) = DAY( SendDate ) AND MONTH( getdate() ) = MONTH( SendDate ) AND YEAR( getdate() ) = YEAR( SendDate )
            AND MailType = %d
            " , $MemberNum , $MailType );
        $cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
        while( !$cNeoSQLConnectODBC2->FetchRow() )
        {
            $szTemp = sprintf( "INSERT INTO App_Log_Admin_SendMail( MemNum , MailType ) VALUES( %d,%d )" , $MemberNum , $MailType );
            $cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
            $cLang = new CLang;
            $cLang->SetDataFromDB($MemberNum);
            $mail_subject = $_LANG["MAIL"]["SHOP"]["SUBJECT"][$MailType];
            $mail_message = $_LANG["MAIL"]["SHOP"]["MESSAGE"][$MailType];
			$cLang->TransCode($mail_subject);
            $cLang->TransCode($mail_message);
			$mail_subject_utf8 = CBinaryCover::tis620_to_utf8( $mail_subject );
            $mail_message_utf8 = CBinaryCover::tis620_to_utf8( $mail_message );
			$wspro->AddSendMail( $EMail , $mail_subject_utf8 , $mail_message_utf8 , $EMail , $mail_subject , $mail_message );
            break;
        }
        continue;
    }else if ( $DelayTime < 0 ){
        $MailType = 3;
        $szTemp = sprintf( "SELECT LogNum FROM App_Log_Admin_SendMail
            WHERE MemNum = %d AND
            DAY( getdate() ) = DAY( SendDate ) AND MONTH( getdate() ) = MONTH( SendDate ) AND YEAR( getdate() ) = YEAR( SendDate )
            AND MailType = %d
            " , $MemberNum , $MailType );
        $cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
        while( !$cNeoSQLConnectODBC2->FetchRow() )
        {
            $szTemp = sprintf( "INSERT INTO App_Log_Admin_SendMail( MemNum , MailType ) VALUES( %d,%d )" , $MemberNum , $MailType );
            $cNeoSQLConnectODBC2->QueryRanWeb($szTemp);
            $cLang = new CLang;
            $cLang->SetDataFromDB($MemberNum);
            $mail_subject = $_LANG["MAIL"]["SHOP"]["SUBJECT"][$MailType];
            $mail_message = $_LANG["MAIL"]["SHOP"]["MESSAGE"][$MailType];
			$cLang->TransCode($mail_subject);
            $cLang->TransCode($mail_message);
			$mail_subject_utf8 = CBinaryCover::tis620_to_utf8( $mail_subject );
            $mail_message_utf8 = CBinaryCover::tis620_to_utf8( $mail_message );
			$wspro->AddSendMail( $EMail , $mail_subject_utf8 , $mail_message_utf8 , $EMail , $mail_subject , $mail_message );
			if ( $DelayTime <= -3 ){
				$mail_subject = "พบลูกค้าหมดอายุจำเป็นต้องยกเลิกช็อปทันที!!";
				$mail_message = "ลูกค้าที่หมดอายุคือ <b>{USERNAME}</b><br>
				รหัสสมาชิคคือ <b>{MEMBERNUM}</b><br>
				ระยะเวลาคงเหลือ <b><u>{DAYTOUSE}</u></b><br>
				ลิ้ง Shop คือ : <a href={FOLDERSHOP} target=_blank>{FOLDERSHOP}</a><br>
				ลงทะเบียนเมื่อ : <b>{REGTIME}</b><br>
				ครบกำหนดอายุการใช้งานเมื่อ : <b>{ENDTIME}</b><br><br><br>
				";
				$cLang->TransCode($mail_subject);
				$cLang->TransCode($mail_message);
				$mail_subject_utf8 = CBinaryCover::tis620_to_utf8( $mail_subject );
				$mail_message_utf8 = CBinaryCover::tis620_to_utf8( $mail_message );
				$wspro->AddSendMail( $_CONFIG["MAIL_CONTENT"] , $mail_subject_utf8 , $mail_message_utf8 , $_CONFIG["MAIL_CONTENT"] , $mail_subject , $mail_message );
			}
            break;
        }
		
        continue;
    }
}
$cNeoSQLConnectODBC->CloseRanWeb();
$cNeoSQLConnectODBC2->CloseRanWeb();
if ( $wspro->GetCount() > 0 ) $wspro->DumpToFile();
printf( "SUCCESS" , $mail_subject , $mail_message );
?>
