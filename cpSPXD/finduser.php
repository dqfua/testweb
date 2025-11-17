<?php
if ( !defined("SHOPNEOCP") ) die("HACKING....");

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$user = "";
$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $submit == 1 )
{
    $user = CInput::GetInstance()->GetValueString( "user" , IN_POST );
}
?>
<form method="post" action="home.php?pid=finduser&submit=1">ค้นหา User : <input type="text" id="user" name="user" value="<?php echo $user ?>"> <input type="submit" value="ค้นหา"></form><br>
<?php
if ( $submit == 1 )
{
    if ( empty($user) ) die( 'กรุณาใส่อย่างน้อย 1 ตัวอักษรค่ะ<br>' );
    $user = str_replace( " ","%",$user );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = "SELECT
        MemberNum,MemID,ServerName,EMail,MemType,ServerType,Reg_ShopFolder,Reg_DateOpen,Reg_DateOpenEnd,MemBan
        FROM MemberInfo
        WHERE MemID like '%$user%' AND MemDelete = 0
        ";
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    $bFrist = false;
    $ccc = 0;
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        if ( $bFrist == false )
        {
            echo "<TABLE cellpadding='5' cellspaceing='5'>";
            printf("<TR style='background-color:#8b8b8b'>
                <TD style='width:120px;'><b>ไอดี</b></TD>
                <TD style='width:120px;'><b>ชื่อเซิร์ฟเวอร์</b></TD>
                <TD style='width:300px;'><b>อีเมล์</b></TD>
                <TD style='width:150px;'><b>ชนิดของสมาชิค</b></TD>
                <TD style='width:120px;'><b>ชนิดเซิร์ฟเวอร์</b></TD>
                <TD style='width:170px;'><b>ลิ้งที่อยู่</b></TD>
                <TD style='width:180px;'><b>สมัครเมื่อ</b></TD>
                <TD style='width:180px;'><b>หมดเวลาใช้งานเมื่อ</b></TD>
                </TR>");
        }
        
        $MemberNum = $cNeoSQLConnectODBC->Result("MemberNum",ODBC_RETYPE_INT);
        
        if ( $cNeoSQLConnectODBC->Result("MemBan",ODBC_RETYPE_INT) == 0 )
            $MsgBan = sprintf( "<a href='home.php?pid=ban&membernum=%d' onclick='if ( !confirmText(\"คุณต้องการแบน %s ออกจากระบบแน่หรือไม่\") ) return false;'>แบนการใช้งาน</a>" , $MemberNum ,$cNeoSQLConnectODBC->Result("MemID",ODBC_RETYPE_INT) );
        else
            $MsgBan = sprintf( "<a href='home.php?pid=unban&membernum=%d' onclick='if ( !confirmText(\"คุณต้องการปลดแบน %s ออกจากระบบแน่หรือไม่\") ) return false;'>ปลดแบน</a>" , $MemberNum ,$cNeoSQLConnectODBC->Result("MemID",ODBC_RETYPE_INT) );
        
        $color = "d1d1d1";
        if ( $ccc % 2 ) $color = "8b8b8b";
        printf("<TR style='background-color:#%s'>
                <TD>%s</TD>
                <TD>%s</TD>
                <TD>%s</TD>
                <TD>%s</TD>
                <TD>%s</TD>
                <TD><a href='%s%s' target='_blank'>%s</a></TD>
                <TD>%s</TD>
                <TD>%s</TD>
                </TR>
                <TR style='background-color:#%s'>
                <TD colspan='8'>
                <div style='margin-left:29px;'>
                เมนูการจัดการ : 
                <a href='home.php?pid=edit&membernum=%d'>แก้ไขทั่วไป</a>,
                <a href='home.php?pid=sendmail&membernum=%d'>ส่งเมล์</a>,
                <a href='home.php?pid=sendmail&type=1&membernum=%d'>ส่งเมล์แจ้งเวลาหมดอายุ</a>,
                <a href='home.php?pid=sendmail&type=2&membernum=%d'>ส่งเมล์แจ้งเตือนหมดอายุ</a>,
                <a href='home.php?pid=sendmail&type=3&membernum=%d'>ส่งเมล์เลยกำหนดหมดอายุ</a>,
                <a href='home.php?pid=sendmail&type=4&membernum=%d'>แจ้งตัดการใช้งานเพราะครบกำหนด</a>,
                %s,
                <a href='home.php?pid=del&membernum=%d' onclick='if ( !confirmText(\"คุณต้องการลบ %s ออกจากระบบแน่หรือไม่\") ) return false;'>ลบ</a>
                </div>
                </TD>
                </TR>"
                ,$color
                ,$cNeoSQLConnectODBC->Result("MemID",ODBC_RETYPE_INT)
                ,$cNeoSQLConnectODBC->Result("ServerName",ODBC_RETYPE_INT)
                ,$cNeoSQLConnectODBC->Result("EMail",ODBC_RETYPE_THAI)
                ,$_CONFIG["USER"]["TYPE"][$cNeoSQLConnectODBC->Result("MemType",ODBC_RETYPE_INT)]
                ,$_CONFIG["SERVER_TYPE"][$cNeoSQLConnectODBC->Result("ServerType",ODBC_RETYPE_INT)]
                ,$_CONFIG["HOSTLINK"],$cNeoSQLConnectODBC->Result("Reg_ShopFolder",ODBC_RETYPE_INT)
                ,$cNeoSQLConnectODBC->Result("Reg_ShopFolder",ODBC_RETYPE_ENG)
                ,substr( $cNeoSQLConnectODBC->Result("Reg_DateOpen",ODBC_RETYPE_ENG) , 0 , 16 )
                ,substr( $cNeoSQLConnectODBC->Result("Reg_DateOpenEnd",ODBC_RETYPE_ENG) , 0 , 16 )
                ,$color
                ,$MemberNum,$MemberNum,$MemberNum,$MemberNum,$MemberNum,$MemberNum
                ,$MsgBan
                ,$MemberNum
                ,$cNeoSQLConnectODBC->Result("MemID",ODBC_RETYPE_ENG)
                );
        $bFrist = true;
        $ccc++;
    }
    if ( $bFrist == false )
    {
        echo "</TABLE>";
    }
    $cNeoSQLConnectODBC->CloseRanWeb();
}
?>