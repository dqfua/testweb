<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");
if ( !$cAdmin->GetLoginPassCard() )
{
    require_once 'password_security.cmd.php';
    die("");
}

CInput::GetInstance()->BuildFrom( IN_POST );

$type = CInput::GetInstance()->GetValueInt( "type" , IN_GET );

class __UserInfo
{
    public $UserNum = 0;
    public $UserID = "";
    public $RegisterDate = "";
    public function __construct( $UserNum , $UserID , $RegisterDate )
    {
        $this->UserID = $UserID;
        $this->UserNum = $UserNum;
        $this->RegisterDate = $RegisterDate;
    }
};

class CSmartUserInfo
{
    public $pData = array();
    
    public function AddData( $UserNum , $UserID , $RegisterData )
    {
        $this->pData[ $UserNum ] = new __UserInfo($UserNum, $UserID, $RegisterDate);
    }
    
    public function GetData( $index ){ return $this->pData[ $index ]; }
    public function __construct()
    {
    }
};

function CMD_TYPE_REPORT_TOPCHARACTERMONEY()
{
    global $cAdmin;
    $timedata = 60;
    $MemNum = $cAdmin->GetMemNum();
    $CURRENT_SESSION = "CMD_TYPE_REPORT_TOPCHARACTERMONEY" . $MemNum;
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    
    $_data = unserialize( phpFastCache::get($CURRENT_SESSION) );
    printf("<h2><b><u>แสดงที่มีเงินติดตัวมากที่สุด (ข้อมูลส่วนนี้จะอัพเดททุกๆ %d นาที)</b></u></h2>" , ($timedata/60) );
    
    if ( $_data == NULL )
    {
        //echo "NEW";
        //new
        $_data = array();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
        $szTemp = sprintf("SELECT TOP %d ChaNum,UserNum,ChaName,ChaMoney FROM ChaInfo ORDER BY ChaMoney DESC" , ADM_REPORT_TOPUSERPOINT);
        $cNeoSQLConnectODBC->QueryRanGame($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ChaNum = $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT);
            $UserNum = $cNeoSQLConnectODBC->Result("UserNum",ODBC_RETYPE_INT);
            $ChaName = $cNeoSQLConnectODBC->Result("ChaName",ODBC_RETYPE_ENG);
            $ChaMoney = $cNeoSQLConnectODBC->Result("ChaMoney",ODBC_RETYPE_ENG);
            
            //cover tis to utf
            $ChaName = CBinaryCover::tis620_to_utf8( $ChaName );

            $_data[ $ChaNum ] = array( $UserNum , $ChaName , $ChaMoney );
            
            //echo $ChaName . "<br>";
        }
        $cNeoSQLConnectODBC->CloseRanGame();
        phpFastCache::set($CURRENT_SESSION, serialize( $_data ), $timedata /*3600*/ ); // ทุกๆ 1 ซม.
    }//else echo "MEMORY";
    
    //print_r($_data);
    
    echo "<div id=\"player_main\"><div id=\"player_process\"></div></div>";
    
    echo "<table>";
    echo "<tr><td style=\"width:59px;\"><div align=\"center\">Rank</div></td>"
            . "<td style=\"width:99px;\"><div align=\"center\">ChaNum</div></td>"
            . "<td style=\"width:159px;\"><div align=\"center\">ChaName</div></td>"
            . "<td style=\"width:59px;\"><div align=\"center\">ChaMoney</div></td></tr>";
    $rank = 1;
    $__bbgc = "#303030";
    $__bbgc2 = "#000000";
    foreach( $_data as $key => $value )
    {
        $__data = $value;
        //printf( "%d : %s(%d)<br>\n" , $key , $__data[0] , $__data[1] );
        printf( "<tr><td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:user2char(%d,'#main_show');\">%d</a></div></td>"
                . "<td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:user2char(%d,'#main_show');\">%d</a></div></td>"
                . "<td style=\"background-color:%s\"><a href=\"javascript:user2char(%d,'#main_show');\">%s</a></td>"
                . "<td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:user2char(%d,'#main_show');\">%d</a></div></td></tr>"
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $rank
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $key
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $__data[1]
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $__data[2]
                );
        $rank++;
    }
    echo "</table>";
}

function CMD_TYPE_REPORT_ALLGMID()
{
    global $cAdmin;
    $timedata = 60;
    $MemNum = $cAdmin->GetMemNum();
    $CURRENT_SESSION = "CMD_TYPE_REPORT_ALLGMID_" . $MemNum;
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    
    $_data = unserialize( phpFastCache::get($CURRENT_SESSION) );
    printf("<h2><b><u>แสดง ID GM ทั้งหมด (ข้อมูลส่วนนี้จะอัพเดททุกๆ %d นาที)</b></u></h2>" , ($timedata/60) );
    
    if ( $_data == NULL )
    {
        //echo "NEW";
        //new
        $_data = array();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
        $szTemp = sprintf("SELECT UserNum,UserID,UserPoint,UserType FROM UserInfo WHERE UserType > 1");
        $cNeoSQLConnectODBC->QueryRanUser($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $UserNum = $cNeoSQLConnectODBC->Result("UserNum",ODBC_RETYPE_INT);
            $UserID = $cNeoSQLConnectODBC->Result("UserID",ODBC_RETYPE_ENG);
            $UserPoint = $cNeoSQLConnectODBC->Result("UserPoint",ODBC_RETYPE_INT);
            $UserType = $cNeoSQLConnectODBC->Result("UserType",ODBC_RETYPE_INT);

            $_data[ $UserNum ] = array( $UserID , $UserPoint , $UserType );
        }
        $cNeoSQLConnectODBC->CloseRanUser();
        phpFastCache::set($CURRENT_SESSION, serialize( $_data ), $timedata /*3600*/ ); // ทุกๆ 1 ซม.
    }//else echo "MEMORY";
    
    echo "<table>";
    echo "<tr><td style=\"width:59px;\"><div align=\"center\">Rank</div></td>"
            . "<td style=\"width:99px;\"><div align=\"center\">UserNum</div></td>"
            . "<td style=\"width:159px;\"><div align=\"center\">UserID</div></td>"
            . "<td style=\"width:59px;\"><div align=\"center\">UserPoint</div></td></tr>";
    $rank = 1;
    $__bbgc = "#303030";
    $__bbgc2 = "#000000";
    foreach( $_data as $key => $value )
    {
        $__data = $value;
        //printf( "%d : %s(%d)<br>\n" , $key , $__data[0] , $__data[1] );
        printf( "<tr><td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%d</a></div></td>"
                . "<td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%d</a></div></td>"
                . "<td style=\"background-color:%s\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%s%s</a></td>"
                . "<td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%d</a></div></td></tr>"
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $rank
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $key
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $__data[0] , ( $__data[2] > 1 ) ? "<b><u>[GM]</u></b>" : ""
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $__data[1]
                );
        $rank++;
    }
    echo "</table>";
}

function CMD_TYPE_REPORT_TOPUSERPOINT()
{
    global $cAdmin;
    $timedata = 60;
    $MemNum = $cAdmin->GetMemNum();
    $CURRENT_SESSION = "CMD_TYPE_REPORT_TOPUSERPOINT_" . $MemNum;
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    
    $_data = unserialize( phpFastCache::get($CURRENT_SESSION) );
    printf("<h2><b><u>แสดงจำนวนยอดพ้อยสูงสุดภายในเซิร์ฟเวอร์ (ข้อมูลส่วนนี้จะอัพเดททุกๆ %d นาที)</b></u></h2>" , ($timedata/60) );
    
    if ( $_data == NULL )
    {
        //echo "NEW";
        //new
        $_data = array();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
        $szTemp = sprintf("SELECT TOP %d UserNum,UserID,UserPoint,UserType FROM UserInfo WHERE UserPoint > 0 ORDER BY UserPoint DESC",ADM_REPORT_TOPUSERPOINT);
        $cNeoSQLConnectODBC->QueryRanUser($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $UserNum = $cNeoSQLConnectODBC->Result("UserNum",ODBC_RETYPE_INT);
            $UserID = $cNeoSQLConnectODBC->Result("UserID",ODBC_RETYPE_ENG);
            $UserPoint = $cNeoSQLConnectODBC->Result("UserPoint",ODBC_RETYPE_INT);
            $UserType = $cNeoSQLConnectODBC->Result("UserType",ODBC_RETYPE_INT);

            $_data[ $UserNum ] = array( $UserID , $UserPoint , $UserType );
        }
        $cNeoSQLConnectODBC->CloseRanUser();
        phpFastCache::set($CURRENT_SESSION, serialize( $_data ), $timedata /*3600*/ ); // ทุกๆ 1 ซม.
    }//else echo "MEMORY";
    
    echo "<table>";
    echo "<tr><td style=\"width:59px;\"><div align=\"center\">Rank</div></td>"
            . "<td style=\"width:99px;\"><div align=\"center\">UserNum</div></td>"
            . "<td style=\"width:159px;\"><div align=\"center\">UserID</div></td>"
            . "<td style=\"width:59px;\"><div align=\"center\">UserPoint</div></td></tr>";
    $rank = 1;
    $__bbgc = "#303030";
    $__bbgc2 = "#000000";
    foreach( $_data as $key => $value )
    {
        $__data = $value;
        //printf( "%d : %s(%d)<br>\n" , $key , $__data[0] , $__data[1] );
        printf( "<tr><td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%d</a></div></td>"
                . "<td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%d</a></div></td>"
                . "<td style=\"background-color:%s\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%s%s</a></td>"
                . "<td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%d</a></div></td></tr>"
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $rank
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $key
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $__data[0] , ( $__data[2] > 1 ) ? "<b><u>[GM]</u></b>" : ""
                , ( $rank % 2 == 1 ) ? $__bbgc : $__bbgc2 , $key , $__data[1]
                );
        $rank++;
    }
    echo "</table>";
}

function CMD_TYPE_REPORT_TOPBONUSPOINT()
{
    global $cAdmin;
    $timedata = 60;
    $MemNum = $cAdmin->GetMemNum();
    $CURRENT_SESSION = "CMD_TYPE_REPORT_TOPBONUSPOINT_" . $MemNum;
    
    $_data = unserialize( phpFastCache::get($CURRENT_SESSION) );
    printf("<h2><b><u>แสดงจำนวนยอดแต้มสะสมสูงสุดภายในเซิร์ฟเวอร์ (ข้อมูลส่วนนี้จะอัพเดททุกๆ %d นาที)</b></u></h2>" , ($timedata/60) );
    
    if ( $_data == NULL )
    {
        //echo "NEW";
        //new
        $_data = new _tdata();
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp = sprintf("SELECT TOP %d UserID,BonusPoint FROM UserInfo WHERE MemNum = %d AND BonusPoint > 0 ORDER BY BonusPoint DESC",ADM_REPORT_TOPUSERPOINT, $MemNum);
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $UserID = $cNeoSQLConnectODBC->Result("UserID",ODBC_RETYPE_ENG);
            $BonusPoint = $cNeoSQLConnectODBC->Result("BonusPoint",ODBC_RETYPE_INT);

            $_data->AddData("UserID", $UserID);
            $_data->AddData("BonusPoint", $BonusPoint);
            $_data->NextData();
        }
        $cNeoSQLConnectODBC->CloseRanUser();
        phpFastCache::set($CURRENT_SESSION, serialize( $_data ), $timedata /*3600*/ ); // ทุกๆ 1 ซม.
    }//else echo "MEMORY";
    
    echo "<table>";
    echo "<tr><td style=\"width:59px;\"><div align=\"center\">Rank</div></td>"
            . "<td style=\"width:159px;\"><div align=\"center\">UserID</div></td>"
            . "<td style=\"width:59px;\"><div align=\"center\">BonusPoint</div></td></tr>";
    
    $__bbgc = "#303030";
    $__bbgc2 = "#000000";
    for( $i = 0 ; $i < $_data->GetRollData() ; $i++ )
    {
        $ppData = $_data->GetData($i);
        
        $UserNum = UserInfo::GetUserNumFromUserID($MemNum, $ppData["UserID"]);
        
        printf( "<tr><td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%d</a></div></td>"
                . "<td style=\"background-color:%s\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%s</a></td>"
                . "<td style=\"background-color:%s\"><div align=\"center\"><a href=\"javascript:char2user_div(%d,'#main_show');\">%d</a></div></td></tr>"
                , ( $i % 2 == 1 ) ? $__bbgc : $__bbgc2 , $UserNum , $i+1
                , ( $i % 2 == 1 ) ? $__bbgc : $__bbgc2 , $UserNum , $ppData["UserID"]
                , ( $i % 2 == 1 ) ? $__bbgc : $__bbgc2 , $UserNum , $ppData["BonusPoint"]
                );
    }
    echo "</table>";
}

function CMD_TYPE_GET_USERID()
{
    global $CURRENT_SESSION_USER;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    $CURRENT_SESSION_USER = sprintf( "%d_shopadmusermember" , $MemNum );
    
    $UserNum = CInput::GetInstance()->GetValueInt( "usernum" , IN_POST );
    if ( $UserNum == 0 || empty($UserNum) ) return ;
    
    Sleep( 1 );
    
    $smartuserinfo = unserialize( phpFastCache::get($CURRENT_SESSION_USER) );
    if ( !$smartuserinfo )
    {
        $smartuserinfo = new CSmartUserInfo();
    }
    
    $pData = $smartuserinfo->GetData($UserNum);
    $UserID = $pData->UserID;
    
    if ( $UserID == "" )
    {
        $cWeb = new CNeoWeb;
        $cWeb->GetDBInfoFromWebDB( $MemNum );
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
        $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP() , $cWeb->GetRanUser_User() , $cWeb->GetRanUser_Pass() , $cWeb->GetRanUser_DB() );
        $szTemp = sprintf("SELECT UserID,CreateDate FROM UserInfo WHERE UserNum = %d",$UserNum);
        $cNeoSQLConnectODBC->QueryRanUser($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $UserID = $cNeoSQLConnectODBC->Result("UserID",ODBC_RETYPE_ENG);
            $RegisterData = $cNeoSQLConnectODBC->Result("CreateDate",ODBC_RETYPE_ENG);
            $smartuserinfo->AddData($UserNum, $UserID, $RegisterData);
        }
        $cNeoSQLConnectODBC->CloseRanUser();
    }else{
        $pData = $smartuserinfo->GetData($UserNum);
        $UserID = $pData->UserID;
    }
    phpFastCache::set($CURRENT_SESSION_USER, serialize($smartuserinfo) , 3600*24); // 1 วัน
    
    echo $UserID;
}

class __SmartFristD
{
    public $RefillNumEncode = "";
    public $RefillNum = 0;
    public $UserNum = 0;
    public $SerialTruemoney = "";
    public $Status = 0;
    public $CardRank = 0;
    public $RefillDate = "";
    public $UpdateDate = "";

    public function __construct( $RefillNum , $UserNum , $SerialTruemoney , $Status , $CardRank , $RefillDate , $UpdateDate , $RefillNumEn )
    {
        $this->RefillDate = $RefillNum;
        $this->UserNum = $UserNum;
        $this->SerialTruemoney = $SerialTruemoney;
        $this->Status = $Status;
        $this->CardRank = $CardRank;
        $this->RefillDate = $RefillDate;
        $this->UpdateDate = $UpdateDate;
        $this->RefillNumEncode = $RefillNumEn;
    }
};

class __SmartCard
{
    public $pData = array();
    private $nData = 0;
    public function GetMoney()
    {
        global $_CONFIG;
        $nMoney = 0;
        foreach( $this->pData as $key => $value )
        {
            $nMoney += $_CONFIG['tmpay']['amount'][ $value->CardRank ];
        }
        return $nMoney;
    }
    public function GetRollData(){ return $this->nData; }
    public function GetData( $index ){ return $this->pData[$index]; }
    public function AddData( $RefillNum , $UserNum , $SerialTruemoney , $Status , $CardRank , $RefillDate , $UpdateDate , $RefillNumEn )
    {
        $this->pData[ $this->nData ] = new __SmartFristD( $RefillNum , $UserNum , $SerialTruemoney , $Status , $CardRank , $RefillDate , $UpdateDate , $RefillNumEn );
        $this->nData++;
    }
}

function CMD_PROC( &$smallsmartd , $CURRENT_SESSION , $MemNum , $ddd , $mmm , $yyy , $timedata )
{
    //printf( "\$ddd = $ddd , \$mmm = $mmm , \$yyy = $yyy<br>\n" );
    
    //$smallsmartd = unserialize( phpFastCache::get($CURRENT_SESSION) );
    if ( !$smallsmartd )
    {
        $smallsmartd = new __SmartCard;
        
        if ( $ddd != 0 )
            $ddd = sprintf( "AND DAY( RefillDate ) = %d" , $ddd );
        else
            $ddd = "";
        
        if ( $mmm != 0 )
            $mmm = sprintf( "AND MONTH( RefillDate ) = %d" , $mmm );
        else
            $mmm = "";
        
        $yyy = sprintf( "AND YEAR( RefillDate ) = %d" , $yyy );
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp = sprintf("SELECT RefillNum,UserNum,SerialTruemoney,Status,CardRank,RefillDate,UpdateDate
                            FROM Refill WHERE MemNum = %d AND bToPercent = 0
                            %s %s %s
                            ORDER BY RefillNum DESC
                            " , $MemNum , $ddd , $mmm , $yyy );
        //echo $szTemp . "<br>\n";
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $RefillNum = $cNeoSQLConnectODBC->Result( "RefillNum" , ODBC_RETYPE_INT );
            $UserNum = $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT );
            $SerialTruemoney = $cNeoSQLConnectODBC->Result( "SerialTruemoney" , ODBC_RETYPE_ENG );
            $Status = $cNeoSQLConnectODBC->Result( "Status" , ODBC_RETYPE_INT );
            $CardRank = $cNeoSQLConnectODBC->Result( "CardRank" , ODBC_RETYPE_INT );
            $RefillDate = substr( $cNeoSQLConnectODBC->Result( "RefillDate" , ODBC_RETYPE_ENG ) , 0 , 21 );
            $UpdateDate = substr( $cNeoSQLConnectODBC->Result( "UpdateDate" , ODBC_RETYPE_ENG ) , 0 , 21 );
            
            $SerialTruemoney = "XXXXXXX" . substr( $SerialTruemoney ,  7 );
            
            $smallsmartd->AddData($RefillNum, $UserNum, $SerialTruemoney, $Status, $CardRank, $RefillDate, $UpdateDate, NumToEncode( $RefillNum ) );
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        phpFastCache::set($CURRENT_SESSION, serialize( $smallsmartd ), $timedata /*3600*/ ); // ทุกๆ 1 ซม.
    }
}

function CMD_TYPE_HTML( $smallsmartd )
{
    global $_CONFIG;
    printf( "<script type=\"text/javascript\">" );
    printf( "var cardRoll = 0 , cardDataInfo = [" );
    for( $i = 0 ; $i < $smallsmartd->GetRollData() ; $i++ )
    {
        $pData = $smallsmartd->GetData( $i );
        //|    RefillNum(INT)        |   RefillNumEncode(STRING) |   SerialTruemoney(STRING) |   UserNum(INT)    |
        //|    RefillDate(STRING)    |   UpdateDate(STRING)      |   Status(STRING)          |   CardRank(INT)   |
        printf( "[ %d , \"%s\" , \"%s\" , %d , \"%s\" , \"%s\" , \"%s\" , %d ],"
                , $pData->RefillNum
                , $pData->RefillNumEncode
                , $pData->SerialTruemoney
                , $pData->UserNum
                , $pData->RefillDate
                , $pData->UpdateDate
                , $_CONFIG['tmpay']['card_status'][ $pData->Status ]
                , $_CONFIG['tmpay']['amount'][ $pData->CardRank ]
                );
    }
    printf( "]" );
    printf( "</script>" );
?>
<table>
    <tr>
        <td>รายการทั้งหมด <b><u><?php echo $smallsmartd->GetRollData(); ?></u></b> รายการ</td>
    </tr>
    <tr>
        <td>รายได้ทั้งหมดคือ <b><u><?php echo $smallsmartd->GetMoney(); ?></u></b> รายการ</td>
    </tr>
    <tr>
        <td>
            <table id="cardInfoTable" cellspacing="3" cellpadding="3" style="border: #333 dashed  3px;">
                <tbody>
                    <tr>
                        <td style="width:119px;">รหัสรายการ</td>
                        <td style="width:259px;">ทำรายการเมื่อ</td>
                    </tr>
                </tbody>
                <?php
                /*
                for( $i = 0 ; $i < $smallsmartd->GetRollData() ; $i++ )
                {
                    $pData = $smallsmartd->GetData( $i );
                    printf( "<tr>
                                <td>%s</td>
                                <td>
                                %s <button onclick=\"showcardInfo(%d,%d);\">รายละเอียด</button>
                                <div id=\"cardinfo_%d\" style=\"display:none;\">
                                ทำรายการเมื่อ : %s<br>\n
                                ถูกตรวจสอบเมื่อ : %s<br>\n
                                สถานะ : %s<br>\n
                                บัตรมูลค่า : %d<br>\n
                                เติมเข้าไอดี : <span id=\"userinfo_id_%d\"></span>(%d)
                                </div>
                                </td>
                            </tr>" , $pData->RefillNumEncode
                                   , $pData->SerialTruemoney
                                   , $i
                                   , $pData->UserNum
                                   , $i
                                   , $pData->RefillDate
                                   , $pData->UpdateDate
                                   , $_CONFIG['tmpay']['card_status'][ $pData->Status ]
                                   , $_CONFIG['tmpay']['amount'][ $pData->CardRank ]
                                   , $i
                                   , $pData->UserNum
                            );
                }
                 * 
                 */
                ?>
                <tfoot>
                    <tr>
                        <td colspan="2"><div align="center"><button onclick="needinfoAdd( this );">โหลดข้อมูลเพิ่มเติม</button></div></td>
                    </tr>
                </tfoot>
            </table>
        </td>
    </tr>
</table>
<?php
}

function CMD_TYPE_SHOW_DAY()
{
    global $_CONFIG;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $ddd = CInput::GetInstance()->GetValueInt( "ddd" , IN_POST );
    $mmm = CInput::GetInstance()->GetValueInt( "mmm" , IN_POST );
    $yyy = CInput::GetInstance()->GetValueInt( "yyy" , IN_POST );
    
    $CURRENT_SESSION = sprintf( "%d_smartshow_%d_%d_%d" , $MemNum , $yyy , $mmm , $ddd );
    
    echo "<span><u><b>ข้อมูลที่แสดงนี้จะอัพเดททุกๆ 1 ซม.</b></u></span>";
    
    $smallsmartd = NULL;
    CMD_PROC($smallsmartd, $CURRENT_SESSION , $MemNum , $ddd, $mmm, $yyy , 3600/*1ซม*/);
    CMD_TYPE_HTML($smallsmartd);
}

function CMD_TYPE_SHOW_MONTH()
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $mmm = CInput::GetInstance()->GetValueInt( "mmm" , IN_POST );
    $yyy = CInput::GetInstance()->GetValueInt( "yyy" , IN_POST );
    
    $CURRENT_SESSION = sprintf( "%d_smartshow_%d_%d_%d" , $MemNum , $yyy , $mmm , 0 );
    
    echo "<span><u><b>ข้อมูลที่แสดงนี้จะอัพเดททุกๆ 1 วัน.</b></u></span>";
    
    $smallsmartd = NULL;
    CMD_PROC($smallsmartd, $CURRENT_SESSION , $MemNum , 0, $mmm, $yyy , 3600*24/*1วัน*/);
    CMD_TYPE_HTML($smallsmartd);
}
function CMD_TYPE_SHOW_YEAR()
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $yyy = CInput::GetInstance()->GetValueInt( "yyy" , IN_POST );
    
    $CURRENT_SESSION = sprintf( "%d_smartshow_%d_%d_%d" , $MemNum , $yyy , 0 , 0 );
    
    echo "<span><u><b>ข้อมูลที่แสดงนี้จะอัพเดททุกๆ 1 สัปดาห์.</b></u></span>";
    
    $smallsmartd = NULL;
    CMD_PROC($smallsmartd, $CURRENT_SESSION , $MemNum , 0, 0, $yyy , 3600*24*7/*7วัน*/);
    CMD_TYPE_HTML($smallsmartd);
}

function CMD_TYPE_NOWMAL()
{
    global $_CONFIG;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $CURRENT_SESSION = sprintf("%d_smallfristc",$MemNum);
    
    class __SmallFristC
    {
        public $CardOK = 0;
        public $CardFail = 0;
        public $CardALL = 0;
        public $MoneyIn = 0;
        public $ComeInDay = 0;
        public $ComeOutDay = 0;
        public $RegShopDate = "";
        public $RegShopEndDate = "";
    };
    
    $today = GetToday();
    $today_day;
    $today_month;
    $today_year;
    $today_hour;
    $today_minut;
    DateFromSQL2Data($today, $today_year, $today_month, $today_day , $today_hour, $today_minut);
    //$smallfristc = CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION );
    $smallfristc = unserialize( phpFastCache::get($CURRENT_SESSION) );
    if ( !$smallfristc )
    {
        $smallfristc = new __SmallFristC;

        //$cWeb = new CNeoWeb();
        //$cWeb->GetDBInfoFromWebDB($MemNum);
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp = sprintf( "SELECT Status,CardRank FROM Refill WHERE MemNum = %d AND bToPercent = 0" , $MemNum );
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $status = $cNeoSQLConnectODBC->Result( "Status" , ODBC_RETYPE_INT );
            $cardmoney = $cNeoSQLConnectODBC->Result( "CardRank" , ODBC_RETYPE_INT );
            switch( $status )
            {
                case 1:
                {
                    $smallfristc->CardOK++;
                    $smallfristc->MoneyIn += $_CONFIG["tmpay"]["amount"][$cardmoney];
                }break;
                default:
                    $smallfristc->CardFail++;
            }
            $smallfristc->CardALL++;
        }
        $szTemp = sprintf("SELECT Reg_DateOpen,Reg_DateOpenEnd
                                  FROM MemberInfo WHERE MemberNum = %d" , $MemNum );
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $smallfristc->RegShopDate = substr( $cNeoSQLConnectODBC->Result( "Reg_DateOpen" , ODBC_RETYPE_THAI) , 0 , 10 );
            $smallfristc->RegShopEndDate = substr( $cNeoSQLConnectODBC->Result( "Reg_DateOpenEnd" , ODBC_RETYPE_THAI) , 0 , 10 );
            $smallfristc->ComeInDay = CGlobal::DateDiff( $smallfristc->RegShopDate , substr( $today , 0 , 10 ) );
            $smallfristc->ComeOutDay = CGlobal::DateDiff( substr( $today , 0 , 10 ) , $smallfristc->RegShopEndDate );
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        phpFastCache::set($CURRENT_SESSION, serialize($smallfristc) , 3600*24); // 1 วัน
        //CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($smallfristc) , IN_SESSION );
        //CInput::GetInstance()->UpdateSession();
        //echo "DB::";
    }
?>

<div id="main_show" class="main_report_show">
    <div id="show_small" class="show_small">
        <table cellspancing="1" cellpadding="1" class="show_small_table">
            <tr>
                <td colspan="2"><b><u>ข้อมูลส่วนนี้จะอัพเดททุกๆ 1 วัน</u></b></td>
            </tr>
            <tr>
                <td style="width:199px;" align="left">ลิ้งหน้าบ้าน</td>
                <td style="width:99px;" align="left"><?php printf( "<a href=\"%s%s\" target=\"_blank\">%s</a>" , $_CONFIG["HOSTLINK"] , $cAdmin->szFolderShop , $cAdmin->szFolderShop ); ?></td>
            </tr>
            <tr>
                <td>เลขที่สมาชิค</td>
                <td><?php echo $cAdmin->GetMemNum(); ?></td>
            </tr>
            <tr>
                <td>ลงทะเบียนช็อปเมื่อ</td>
                <td><?php echo $smallfristc->RegShopDate; ?></td>
            </tr>
            <tr>
                <td>สิ้นสุดเมื่อ</td>
                <td><?php echo $smallfristc->RegShopEndDate; ?></td>
            </tr>
            <tr>
                <td>อายุการใช้งานที่ผ่านมา</td>
                <td><?php echo $smallfristc->ComeInDay; ?> วัน</td>
            </tr>
            <tr>
                <td>ระยะเวลาที่เหลือ</td>
                <td><?php echo $smallfristc->ComeOutDay; ?> วัน</td>
            </tr>
            <tr>
                <td>จำนวนบัตรทั้งหมด</td>
                <td><?php echo $smallfristc->CardALL; ?></td>
            </tr>
            <tr>
                <td>บัตรผ่าน</td>
                <td><?php echo $smallfristc->CardOK; ?></td>
            </tr>
            <tr>
                <td>บัตรเสีย</td>
                <td><?php echo $smallfristc->CardFail; ?></td>
            </tr>
            <tr>
                <td colspan="2"><div align="center"><a href="javascript:onClick_ReportTopUserPoint();">แสดงลำดับพ้อยมากที่สุด TOP <?php echo ADM_REPORT_TOPUSERPOINT; ?></a></div></td>
            </tr>
            <tr>
                <td colspan="2"><div align="center"><a href="javascript:onClick_ReportTopChaMoney();">แสดงลำดับเงินในตัวละครมากที่สุด TOP <?php echo ADM_REPORT_TOPUSERPOINT; ?></a></div></td>
            </tr>
            <tr>
                <td colspan="2"><div align="center"><a href="javascript:onClick_ReportTopBonusPoint();">แสดงลำดับแต้มสะสมมากที่สุด TOP <?php echo ADM_REPORT_TOPUSERPOINT; ?></a></div></td>
            </tr>
            <tr>
                <td colspan="2"><div align="center"><a href="javascript:onClick_ReportGMAll();">แสดง ID GM ทั้งหมด</a></div></td>
            </tr>
            <tr>
                <td colspan="2"><div align="center"><a href="javascript:ChooseMenu( 'playeronline' );">แสดงผู้เล่นออนไลน์</a></div></td>
            </tr>
        </table>
    </div>
    <div id="menu_small" class="menu_small">
        <span align="left"><b><u>เลือกแบบรายงาน</u></b></span>
        <div style="display:block;">
            <div id="main_sel" style="display:none;float:left;margin-right: 9px;">กรุณาเลือก</div>
            <div id="sel_y" style="display:none;float:left;margin-right: 9px;">ปี <?php echo buildSelectYear("yyy","",$today_year); ?></div>
            <div id="sel_m" style="display:none;float:left;margin-right: 9px;">เดือน <?php echo buildSelectMonth("mmm","",$today_month); ?></div>
            <div id="sel_d" style="display:none;float:left;margin-right: 9px;">วัน <?php echo buildSelectDay("ddd","",$today_day); ?></div>
            <div id="main_sel_smart" style="display:none;float:left;"><button id="menu_bshow" style="display:block;float:left;">แสดง</button>
            <button id="menu_back" style="display:block;float:left;">ย้อนกลับ</button></div>
        </div>
        <div id="mini_menu" style="display:block;">
            <table>
                <tr>
                    <td><button id="bday">รายวัน</button></td>
                    <td><button id="bmonth">รายเดือน</button></td>
                    <td><button id="byear">รายปี</button></td>
                </tr>
            </table>
        </div>
        <div id="small_smart" style="clear: left;display:none;"></div>
    </div>
</div>

<script type="text/javascript" src="js/classicloading.js"></script>
<script type="text/javascript" src="js/player.js"></script>
<script type="text/javascript" src="js/report.js"></script>

<?php
}

switch( $type )
{
    case 1:
    {
            CMD_TYPE_SHOW_DAY();
    }break;
    case 2:
    {
            CMD_TYPE_SHOW_MONTH();
    }break;
    case 3:
    {
            CMD_TYPE_SHOW_YEAR();
    }break;

    case 1000:
    {
        CMD_TYPE_REPORT_TOPUSERPOINT(  );
    }break;
    case 1001:
    {
        CMD_TYPE_REPORT_ALLGMID();
    }break;
    case 1002:
    {
        CMD_TYPE_REPORT_TOPBONUSPOINT(  );
    }break;
    case 1003:
    {
            CMD_TYPE_REPORT_TOPCHARACTERMONEY(  );
    }break;

    case 1101:
    {
        CMD_TYPE_GET_USERID(  );
    }break;

    default: CMD_TYPE_NOWMAL(); break;
}
?>
