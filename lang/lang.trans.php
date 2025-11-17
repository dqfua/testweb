<?php
/*
 * {USERNAME} แทนค่าของ ชื่อผู้ใช้
 * {EMAILCONTENT} แทนค่าอีเมล์ที่ให้ติดต่อกลับของ shop
 * {MEMBERNUM} แทนค่าของ MemberNum ของผู้ใช้
 * {DAYTOUSE} แทนค่า อายุการใช้งานที่เหลือ
 * {REGTIME} แทนค่า เวลาที่ผู้ใช้งานลงทะเบียน
 * {ENDTIME} แทนค่า เวลาครบกำหนดการใช้งาน
 * {FOLDERSHOP} แทนค่า ที่อยู่ของลิ้ง Shop ของผู้ใช้
 * {DAYUSE} แทนค่าอายุการใช้งานที่ผ่านมาว่ากี่วันมาแล้ว
*/
class CLang
{
    public $MemberNum = 0;
    public $MemID = "";
    public $Daytouse = 0;
    public $Dayuse = 0;
    public $RegTime = "";
    public $EndTime = "";
    public $FolderShop = "";
    
    public function TransCode( &$text )
    {
        global $_CONFIG;
        
        //แทนผู้ใช้
        $text = str_replace("{MEMBERNUM}", $this->MemberNum, $text);
        $text = str_replace("{USERNAME}", $this->MemID, $text);
        $text = str_replace("{DAYTOUSE}", $this->Daytouse, $text);
        $text = str_replace("{DAYUSE}", $this->Dayuse, $text);
        $text = str_replace("{REGTIME}", substr( $this->RegTime , 0 , 16 ) , $text);
        $text = str_replace("{ENDTIME}", substr( $this->EndTime , 0 , 16 ) , $text);
        $text = str_replace("{FOLDERSHOP}", $_CONFIG["HOSTLINK"] . $this->FolderShop, $text);
        
        //แทนจากระบบ
        $text = str_replace("{EMAILCONTENT}", $_CONFIG["MAIL_CONTENT"], $text);
    }
    
    public function SetDataFromDB( $MemberNum )
    {
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
                ,$MemberNum);
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        $bWork = false;
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $bWork = true;
            $this->MemberNum = $MemberNum;
            
            $MemID = $cNeoSQLConnectODBC->Result("MemID",ODBC_RETYPE_ENG);
            $MemPass = $cNeoSQLConnectODBC->Result("MemPass",ODBC_RETYPE_ENG);
            $MemPass_Card = $cNeoSQLConnectODBC->Result("MemPass_Card",ODBC_RETYPE_ENG);
            $ServerName = $cNeoSQLConnectODBC->Result("ServerName",ODBC_RETYPE_ENG);
            $EMail = $cNeoSQLConnectODBC->Result("EMail",ODBC_RETYPE_THAI);
            $MemType = $cNeoSQLConnectODBC->Result("MemType",ODBC_RETYPE_INT);
            $ServerType = $cNeoSQLConnectODBC->Result("ServerType",ODBC_RETYPE_INT);
            $Reg_Shop = $cNeoSQLConnectODBC->Result("Reg_Shop",ODBC_RETYPE_INT);
            $Reg_ShopFolder = $cNeoSQLConnectODBC->Result("Reg_ShopFolder",ODBC_RETYPE_ENG);
            $Reg_DateOpen = $cNeoSQLConnectODBC->Result("Reg_DateOpen",ODBC_RETYPE_ENG);
            $Reg_DateOpenEnd = $cNeoSQLConnectODBC->Result("Reg_DateOpenEnd",ODBC_RETYPE_ENG);
            $End_Day = $cNeoSQLConnectODBC->Result("End_Day",ODBC_RETYPE_INT);
            $End_Month = $cNeoSQLConnectODBC->Result("End_Month",ODBC_RETYPE_INT);
            $End_Year = $cNeoSQLConnectODBC->Result("End_Year",ODBC_RETYPE_INT);
            $MemDelete = $cNeoSQLConnectODBC->Result("MemDelete",ODBC_RETYPE_INT);
            $MemCreateDate = $cNeoSQLConnectODBC->Result("MemCreateDate",ODBC_RETYPE_ENG);
            $DelayTime = $cNeoSQLConnectODBC->Result("DelayTime",ODBC_RETYPE_INT);
            $DelayUse = $cNeoSQLConnectODBC->Result("DelayUse",ODBC_RETYPE_INT);
            
            $this->MemID = $MemID;
            $this->Daytouse = $DelayTime;
            $this->RegTime = $Reg_DateOpen;
            $this->EndTime = $Reg_DateOpenEnd;
            $this->FolderShop = $Reg_ShopFolder;
            $this->Dayuse = $DelayUse;
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        return $bWork;
    }
}
?>
