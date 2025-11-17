<?php
if ( !defined("SHOPNEOCP") ) die("HACKING....");

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
$membernum = CInput::GetInstance()->GetValueInt( "membernum" , IN_GET );

if ( $membernum == 0 ) die("ERROR|MEMBER|FAIL");
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
if ( $submit == 1 )
{
    $MemID = CInput::GetInstance()->GetValueString( "MemID" , IN_POST );
    $MemPass = CInput::GetInstance()->GetValueString( "MemPass" , IN_POST );
    $MemPass_Card = CInput::GetInstance()->GetValueString( "MemPass_Card" , IN_POST );
    $ServerName = CInput::GetInstance()->GetValueString( "ServerName" , IN_POST );
    $EMail = CInput::GetInstance()->GetValueString( "EMail" , IN_POST );
    $MemType = CInput::GetInstance()->GetValueInt( "MemType" , IN_POST );
    $ServerType = CInput::GetInstance()->GetValueInt( "ServerType" , IN_POST );
    $Reg_Shop = CInput::GetInstance()->GetValueInt( "Reg_Shop" , IN_POST );
    $Reg_ShopFolder = CInput::GetInstance()->GetValueString( "Reg_ShopFolder" , IN_POST );
    $MyService = CInput::GetInstance()->GetValueString( "MyService" , IN_POST );
    
    $Reg_DateOpenEnd_Day = CInput::GetInstance()->GetValueInt( "Reg_DateOpenEnd_Day" , IN_POST );
    $Reg_DateOpenEnd_Month = CInput::GetInstance()->GetValueInt( "Reg_DateOpenEnd_Month" , IN_POST );
    $Reg_DateOpenEnd_Year = $_CONFIG["MAX"]["YEARBEGIN"]+CInput::GetInstance()->GetValueInt( "Reg_DateOpenEnd_Year" , IN_POST );
    $szTemp = sprintf( "
        UPDATE MemberInfo SET
        MemID = '%s',
        MemPass = '%s',
        MemPass_Card = '%s',
        ServerName = '%s',
        EMail = '%s',
        MemType = '%d',
        ServerType = '%d',
        Reg_Shop = '%d',
        Reg_ShopFolder = '%s',
        MyService = %d
        WHERE MemberNum = %d
        "
        ,$MemID
        ,$MemPass
        ,$MemPass_Card
        ,$ServerName
        ,$EMail
        ,$MemType
        ,$ServerType
        ,$Reg_Shop
        ,$Reg_ShopFolder
        ,$MyService
        ,$membernum
        );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    $szTemp = sprintf("
        update MemberInfo
        set
        Reg_DateOpenEnd = DATEADD(Day, %d-DATEPART(Day, Reg_DateOpenEnd), Reg_DateOpenEnd)
        where MemberNum = %d
    "
            ,$Reg_DateOpenEnd_Day
            ,$membernum
            );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    $szTemp = sprintf("
        update MemberInfo
        set
        Reg_DateOpenEnd = DATEADD(Month, %d-DATEPART(Month, Reg_DateOpenEnd), Reg_DateOpenEnd)
        where MemberNum = %d
    "
            ,$Reg_DateOpenEnd_Month
            ,$membernum
            );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    $szTemp = sprintf("
        update MemberInfo
        set
        Reg_DateOpenEnd = DATEADD(Year, %d-DATEPART(Year, Reg_DateOpenEnd), Reg_DateOpenEnd)
        where MemberNum = %d
    "
            ,$Reg_DateOpenEnd_Year
            ,$membernum
            );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    echo "<font color='#00FF00'>อัพเดทข้อมูลแล้ว</font><br>\n";
}
$szTemp = sprintf("SELECT
        MemID,MemPass,MemPass_Card,ServerName,EMail,MemType,ServerType,
        Reg_Shop,Reg_ShopFolder,Reg_DateOpen,Reg_DateOpenEnd,MemDelete
        ,MemCreateDate,MyService
        ,DAY( Reg_DateOpenEnd ) as End_Day , MONTH( Reg_DateOpenEnd ) as End_Month , YEAR( Reg_DateOpenEnd ) as End_Year
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
    $MyService = $cNeoSQLConnectODBC->Result("MyService",ODBC_RETYPE_INT);
}
$cNeoSQLConnectODBC->CloseRanWeb();
if ( $bWork == false ) die("ERROR|MEMBERERROR");
?>
<b>แก้ไขสมาชิคทั่วไป</b><br><hr>
<form method="post" action="home.php?pid=edit&membernum=<?php echo $membernum; ?>&submit=1">
<TABLE cellpadding="5" cellspaceing="5">
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           หมายเลขสมาชิค
        </td>
        <TD style="width:300px;">
            <?php
            echo $membernum;
            ?>
        </td>
    </TR>
    <TR style="background-color:#d1d1d1">
        <TD style="width:200px;" valign="top">
           ไอดี
        </td>
        <TD style="width:500px;">
            <input type="text" name="MemID" id="MemID" value="<?php echo $MemID; ?>"> <a href="#" onclick="document.getElementById('MemID').value = '<?php echo $MemID; ?>';return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           รหัสผ่าน
        </td>
        <TD style="width:300px;">
            <input type="password" name="MemPass" id="MemPass" value="<?php echo $MemPass; ?>"><br>
            <a href="#" onclick="CSS_dis('showpass'); return false;">ซ่อน/แสดง</a><div id="showpass" style="display: none;">รหัสผ่านปัจจุบันคือ : <b><?php echo $MemPass; ?></b></div>
        </td>
    </TR>
    <TR style="background-color:#d1d1d1">
        <TD style="width:200px;" valign="top">
           รหัสผ่าน ขั้นที่ 2
        </td>
        <TD style="width:300px;">
            <input type="password" name="MemPass_Card" id="$MemPass_Card" value="<?php echo $MemPass_Card; ?>"><br>
            <a href="#" onclick="CSS_dis('showpass2'); return false;">ซ่อน/แสดง</a><div id="showpass2" style="display: none;">รหัสผ่านปัจจุบันคือ : <b><?php echo $MemPass_Card; ?></b></div>
        </td>
    </TR>
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           ชื่อเซิร์ฟเวอร์
        </td>
        <TD style="width:500px;">
            <input type="text" name="ServerName" id="ServerName" value="<?php echo $ServerName; ?>"> <a href="#" onclick="document.getElementById('ServerName').value = '<?php echo $ServerName; ?>';return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#d1d1d1">
        <TD style="width:200px;" valign="top">
           อีเมล์
        </td>
        <TD style="width:500px;">
            <input type="text" name="EMail" id="EMail" value="<?php echo $EMail; ?>"> <a href="#" onclick="document.getElementById('EMail').value = '<?php echo $EMail; ?>';return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           ชนิดของสมาชิค
        </td>
        <TD style="width:500px;">
            <select name="MemType" id="MemType">
            <?php
            foreach($_CONFIG["USER"]["TYPE"] as $key=>$val)
            {
                $aaa = "";
                if ( $key == $MemType ) $aaa = "selected";
                printf( "<option value='%d' %s >%s</option>" , $key , $aaa , $_CONFIG["USER"]["TYPE"][$key] );
            }
            ?>
            </select>
            <a href="#" onclick="document.getElementById('MemType').options[<?php echo $MemType; ?>].selected=true;return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#d1d1d1">
        <TD style="width:200px;" valign="top">
           ชนิดของเซิร์ฟเวอร์
        </td>
        <TD style="width:500px;">
            <select name="ServerType" id="ServerType">
            <?php
            foreach($_CONFIG["SERVER_TYPE"] as $key=>$val)
            {
                $aaa = "";
                if ( $key == $ServerType ) $aaa = "selected";
                printf( "<option value='%d' %s >%s</option>" , $key , $aaa , $_CONFIG["SERVER_TYPE"][$key] );
            }
            ?>
            </select>
            <a href="#" onclick="document.getElementById('ServerType').options[<?php echo $ServerType; ?>].selected=true;return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#d1d1d1">
        <TD style="width:200px;" valign="top">
           ลงทะเบียนช็อป
        </td>
        <TD style="width:500px;">
            <select name="Reg_Shop" id="Reg_Shop">
            <?php
            foreach($_CONFIG["SHOP_REG"] as $key=>$val)
            {
                $aaa = "";
                if ( $key == $Reg_Shop ) $aaa = "selected";
                printf( "<option value='%d' %s >%s</option>" , $key , $aaa , $_CONFIG["SHOP_REG"][$key] );
            }
            ?>
            </select>
            <a href="#" onclick="document.getElementById('ServerType').options[<?php echo $ServerType; ?>].selected=true;return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           บริการจากนีโอ
        </td>
        <TD style="width:500px;">
            <select name="MyService" id="MyService">
            <?php
            foreach($_CONFIG["MYSERVICE"] as $key=>$val)
            {
                $aaa = "";
                if ( $key == $MyService ) $aaa = "selected";
                printf( "<option value='%d' %s >%s</option>" , $key , $aaa , $_CONFIG["MYSERVICE"][$key] );
            }
            ?>
            </select>
            <a href="#" onclick="document.getElementById('Reg_Shop').options[<?php echo $Reg_Shop; ?>].selected=true;return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#d1d1d1">
        <TD style="width:200px;" valign="top">
           ลิ้งร้านค้า
        </td>
        <TD style="width:500px;">
            <input type="text" name="Reg_ShopFolder" id="Reg_ShopFolder" value="<?php echo $Reg_ShopFolder; ?>"> <a href="#" onclick="document.getElementById('Reg_ShopFolder').value = '<?php echo $Reg_ShopFolder; ?>';return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           ลงทะเบียนเมื่อ
        </td>
        <TD style="width:300px;">
            <?php
            echo substr($Reg_DateOpen,0,16);
            ?>
        </td>
    </TR>
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           รหัสโคลน
        </td>
        <TD style="width:300px;">
            <?php echo CGlobal::MemNum2ConeSerial( $membernum , $MemID ); ?>
        </td>
    </TR>
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           สิ้นสุดอายุการใช้งานเมื่อ
        </td>
        <TD style="width:300px;">
            วัน : 
            <select name="Reg_DateOpenEnd_Day" id="Reg_DateOpenEnd_Day">
                <?php
                for( $i = 1 ; $i <= $_CONFIG["MAX"]["DAY"] ; $i++ )
                {
                    $aaa = "";
                    if ( $i == $End_Day ) $aaa = "selected";
                    printf( "<option value='%d' %s >%d</option>" , $i , $aaa , $i );
                }
                ?>
            </select> |
            เดือน : 
            <select name="Reg_DateOpenEnd_Month" id="Reg_DateOpenEnd_Month">
                <?php
                for( $i = 1 ; $i <= $_CONFIG["MAX"]["MONTH"] ; $i++ )
                {
                    $aaa = "";
                    if ( $i == $End_Month ) $aaa = "selected";
                    printf( "<option value='%d' %s >%d</option>" , $i , $aaa , $i );
                }
                ?>
            </select> |
            ปี : 
            <select name="Reg_DateOpenEnd_Year" id="Reg_DateOpenEnd_Year">
                <?php
                for( $i = $_CONFIG["MAX"]["YEARBEGIN"] ; $i <= $_CONFIG["MAX"]["YEAR"] ; $i++ )
                {
                    $aaa = "";
                    if ( $i == $End_Year ) $aaa = "selected";
                    printf( "<option value='%d' %s >%d</option>" , $i-$_CONFIG["MAX"]["YEARBEGIN"] , $aaa , $i );
                }
                ?>
            </select>
             เวลา <?php echo substr( $Reg_DateOpenEnd , 11 , 8 ); ?>
             <a href="#" onclick="
                 document.getElementById('Reg_DateOpenEnd_Day').options[<?php echo $End_Day-1; ?>].selected=true;
                 document.getElementById('Reg_DateOpenEnd_Month').options[<?php echo $End_Month-1; ?>].selected=true;
                 document.getElementById('Reg_DateOpenEnd_Year').options[<?php echo $End_Year-$_CONFIG["MAX"]["YEARBEGIN"]; ?>].selected=true;
                 return false;">คืนค่า</a>
        </td>
    </TR>
    <TR style="background-color:#8b8b8b">
        <TD style="width:200px;" valign="top">
           
        </td>
        <TD style="width:300px;">
            <input type='submit' value='บันทึก'>
        </td>
    </TR>
</table>
</form>