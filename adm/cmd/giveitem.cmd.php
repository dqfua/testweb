<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

CInput::GetInstance()->BuildFrom( IN_POST );

define("WSPRO_MODE", false);

class ItemData
{
    public $ItemNum = 0;
    public $ItemMain = 0;
    public $ItemSub = 0;
    public $ItemName = 0;
    public function __construct( $ItemNum , $ItemMain , $ItemSub , $ItemName )
    {
        $this->ItemNum = $ItemNum;
        $this->ItemMain = $ItemMain;
        $this->ItemSub = $ItemSub;
        $this->ItemName = $ItemName;
    }
};

class ItemList
{
    private $pData = array();
    private $nData = 0;
    public function IDExisits( $id )
    {
        for( $i = 0 ; $i < $this->nData ; $i++ )
        {
            $ppData = $this->pData[ $i ];
            if ( $ppData->ItemNum == $id ) return $i;
        }
        return -1;
    }
    public function GetData( $index ){ return $this->pData[ $index ]; }
    public function GetRoll(){ return $this->nData; }
    public function AddItem( $ItemNum , $ItemMain , $ItemSub , $ItemName )
    {
        $this->pData[ $this->nData ] = new ItemData( $ItemNum , $ItemMain , $ItemSub , $ItemName );
        $this->nData++;
    }
};

$CURRENT_SESSION = sprintf( "%d_itemlistdata" , $MemNum );

function CMD_PROCESS()
{
    global $cAdmin;
    global $CURRENT_SESSION;
    $MemNum = $cAdmin->GetMemNum();
    
    $pagetype = CInput::GetInstance()->GetValueInt( "pagetype" , IN_POST );
    
    $itemlist = CInput::GetInstance()->GetValueInt( "itemlist" , IN_POST );
    $userid = CInput::GetInstance()->GetValueString( "userid" , IN_POST );
    $ddd = CInput::GetInstance()->GetValueInt( "ddd" , IN_POST );
    $mmm = CInput::GetInstance()->GetValueInt( "mmm" , IN_POST );
    $yyy = CInput::GetInstance()->GetValueInt( "yyy" , IN_POST );
    
    if ( $itemlist <= 0 ) die( "ERROR:ITEMLIST:EMPTY" );
    
    $pItemList = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
    if ( !$pItemList ) die( "ERROR:ITEMLIST:POINTER:EMPTY" );
    
	$nid = $pItemList->IDExisits( $itemlist );
    if ( $nid == -1 ) die( "ERROR:ITEMLIST:NONE" );
    
    $ppData = $pItemList->GetData( $nid );
    $itemmain = $ppData->ItemMain;
    $itemsub = $ppData->ItemSub;
    
    $nProductNum = CRanShop::InsertItemID($MemNum, $itemmain, $itemsub);
    
    switch( $pagetype )
    {
        case 0:
        {
            //all
            $wspro = new WSPro();
            
            $cWeb = new CNeoWeb;
            $cWeb->GetDBInfoFromWebDB( $MemNum );
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
            $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP()
                                                    , $cWeb->GetRanUser_User()
                                                    , $cWeb->GetRanUser_Pass()
                                                    , $cWeb->GetRanUser_DB() );
            $cNeoSQLConnectODBC->QueryRanUser( sprintf( "SELECT UserID FROM UserInfo" ) );
            $idlist = array();
            $querylist = array();
            $i = 0;
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $idlist[ $i ] = $cNeoSQLConnectODBC->Result( "UserID" , ODBC_RETYPE_ENG );
                $querylist[ $i ] = sprintf( "INSERT INTO ShopPurchase( UserUID , ProductNum ) VALUES( '%s' , %d )" , $idlist[ $i ] , $nProductNum );
                $wspro->AddSQLQuery($MemNum, SQL_TYPE_RANSHOP, $querylist[ $i ]);
                $idlist[ $i ] = CBinaryCover::tis620_to_utf8( $idlist[ $i ] );
                $i++;
            }
            $cNeoSQLConnectODBC->CloseRanUser();
            
            if ( WSPRO_MODE == false )
            {
                $cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP()
                                                        , $cWeb->GetRanShop_User()
                                                        , $cWeb->GetRanShop_Pass()
                                                        , $cWeb->GetRanShop_DB() );
                $szQueryShopTemp = "";
                for( $n = 0 ; $n < $i ; $n++ )
                {
                    $szQueryShopTemp .= $querylist[ $n ];
                    if ( $n % 100 == 99 )
                    {
                        $cNeoSQLConnectODBC->QueryRanShop ($szQueryShopTemp);
						//CDebugLog::Write( $szQueryShopTemp );
                        $szQueryShopTemp = "";
                    }
                }
                if ( $szQueryShopTemp != "" )
                {
                    $cNeoSQLConnectODBC->QueryRanShop ($szQueryShopTemp);
					//CDebugLog::Write( $szQueryShopTemp );
                    $szQueryShopTemp = "";
                }
                $cNeoSQLConnectODBC->CloseRanShop();
            }
            
            printf( "SUCCESS:%d:%d:" , $pagetype , $i );
            for( $n = 0 ; $n < $i ; $n++ )
            {
                printf( "%s," , $idlist[ $n ] );
                if ( $n % 5 == 4 ) printf( "<br>" );
            }
            
            $wspro->DumpToFile();
        }break;
        case 1:
        {
            //id only
            if ( empty( $userid ) || $userid == "" ) die( "ERROR:USERID:EMPTY" );
            $cWeb = new CNeoWeb;
            $cWeb->GetDBInfoFromWebDB( $MemNum );
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
            $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP()
                                                    , $cWeb->GetRanUser_User()
                                                    , $cWeb->GetRanUser_Pass()
                                                    , $cWeb->GetRanUser_DB() );
            $cNeoSQLConnectODBC->QueryRanUser( sprintf( "SELECT UserNum FROM UserInfo WHERE UserID = '%s'" , $userid ) );
            $do = $cNeoSQLConnectODBC->FetchRow();
            $cNeoSQLConnectODBC->CloseRanUser();
            if ( $do )
            {
                CRanShop::AddItemToItemBank( $MemNum , $userid , $itemmain , $itemsub );
                printf( "SUCCESS:%d:%s:%d:%d:%d" , $pagetype , $userid , $itemmain , $itemsub , $itemlist );
            }else{
                printf( "ERROR:USERID:NOTFOUND:%s" , $userid );
            }
        }break;;
        case 2:
        {
            //date only
            /*
            $today = GetToday();
            $today_day;
            $today_month;
            $today_year;
            $today_hour;
            $today_minut;
            DateFromSQL2Data($today, $today_year, $today_month, $today_day , $today_hour, $today_minut);
            */
            $today_day = $ddd;
            $today_month = $mmm;
            $today_year = $yyy;
            
            $wspro = new WSPro();
            
            $cWeb = new CNeoWeb;
            $cWeb->GetDBInfoFromWebDB( $MemNum );
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
            $cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP()
                                                    , $cWeb->GetRanUser_User()
                                                    , $cWeb->GetRanUser_Pass()
                                                    , $cWeb->GetRanUser_DB() );
            //$cNeoSQLConnectODBC->QueryRanUser( sprintf( "SELECT UserID FROM UserInfo WHERE DAY( LastLoginDate ) = %d AND MONTH( LastLoginDate ) = %d AND YEAR( LastLoginDate ) = %d" , $today_day , $today_month , $today_year ) );
            $szTemp = sprintf( "SELECT UserID FROM LogLogin WHERE DAY( LogDate ) = %d AND MONTH( LogDate ) = %d AND YEAR( LogDate ) = %d" , $today_day , $today_month , $today_year );
            $cNeoSQLConnectODBC->QueryRanUser( $szTemp );
            //CDebugLog::Write( $szTemp );
            $idlist = array();
            $querylist = array();
            $giveuserid = array();
            $i = 0;
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $idlist[ $i ] = $cNeoSQLConnectODBC->Result( "UserID" , ODBC_RETYPE_ENG );
                $idlist[ $i ] = str_replace("&#32;", "", $idlist[ $i ]);
                $querylist[ $i ] = sprintf( "INSERT INTO ShopPurchase( UserUID , ProductNum ) VALUES( '%s' , %d )" , $idlist[ $i ] , $nProductNum );
                $wspro->AddSQLQuery($MemNum, SQL_TYPE_RANSHOP, $querylist[ $i ]);
                $giveuserid[ $idlist[ $i ] ] = false;
                //$idlist[ $i ] = CBinaryCover::tis620_to_utf8( $idlist[ $i ] );
                $i++;
            }
            $cNeoSQLConnectODBC->CloseRanUser();
            
            if ( WSPRO_MODE == false )
            {
                $cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP()
                                                        , $cWeb->GetRanShop_User()
                                                        , $cWeb->GetRanShop_Pass()
                                                        , $cWeb->GetRanShop_DB() );
                $szQueryShopTemp = "";
                $count = 0;
                for( $n = 0 ; $n < $i ; $n++ )
                {
                    if ( $giveuserid[ $idlist[ $n ] ] != false ) continue;
                    $giveuserid[ $idlist[ $n ] ] = true;
                    
                    $count++;
                    
                    $szQueryShopTemp .= $querylist[ $n ];
                    //CDebugLog::Write( $querylist[ $n ] );
                    if ( $count % 100 == 99 )
                    {
                        $cNeoSQLConnectODBC->QueryRanShop ($szQueryShopTemp);
						//CDebugLog::Write( $szQueryShopTemp );
                        $szQueryShopTemp = "";
                    }
                }
                if ( $szQueryShopTemp != "" )
                {
                    $cNeoSQLConnectODBC->QueryRanShop ($szQueryShopTemp);
					//CDebugLog::Write( $szQueryShopTemp );
                    $szQueryShopTemp = "";
                }
                $cNeoSQLConnectODBC->CloseRanShop();
            }
            
            /*
            printf( "SUCCESS:%d:%d:" , $pagetype , $i );
            for( $n = 0 ; $n < $i ; $n++ )
            {
                printf( "%s," , $idlist[ $n ] );
                if ( $n % 5 == 4 ) printf( "<br>" );
            }
            */
            printf( "SUCCESS:%d:%d:" , $pagetype , sizeof( $giveuserid ) );
            foreach( $giveuserid as $key => $value )
            {
                printf( "%s," , CBinaryCover::tis620_to_utf8( $key ) );
                if ( $n % 5 == 4 ) printf( "<br>" );
            }
            
            $wspro->DumpToFile();
        }break;
        default:
        {
            die( "ERROR:PAGETYPE:OVER" );
        }break;
    }
}

function CMD_UI()
{
    global $cAdmin;
    global $CURRENT_SESSION;
    $MemNum = $cAdmin->GetMemNum();
    
    $today = GetToday();
    $today_day;
    $today_month;
    $today_year;
    $today_hour;
    $today_minut;
    DateFromSQL2Data($today, $today_year, $today_month, $today_day , $today_hour, $today_minut);
    
    $pItemList = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
    if ( !$pItemList )
    {
        $pItemList = new ItemList;
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb(sprintf("SELECT ItemNum,ItemMain,ItemSub,ItemName FROM ItemProject WHERE MemNum = %d AND ItemType = 0 AND ItemDelete = 0 ORDER BY ItemNum DESC",$MemNum));
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ItemNum = $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT);
            $ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
            $ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
            $ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
            
            //tis620 to utf8
            $ItemName = CBinaryCover::tis620_to_utf8( $ItemName );
            
            $pItemList->AddItem($ItemNum, $ItemMain, $ItemSub, $ItemName);
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pItemList) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
    }
    $arrlist = array();
    for( $i = 0 ; $i < $pItemList->GetRoll() ; $i++ )
    {
        $ppData = $pItemList->GetData($i);
        //$arrlist[ $ppData->ItemNum ] = $ppData->ItemName;
		$arrlist[ $ppData->ItemNum ] = $ppData->ItemName;
    }
?>

<div id="main_giveitem">
    <p><b><u>ระบบแจกไอเทม</u></b>
    ระบบนี้จะเป็นการส่งไอเทมเข้าไปยัง Item Bank ของไอดีต่างๆ<br>
    ปล.ไอเทมที่ต้องการแจกจะต้องแอดไอเทมประเภท Item Bank ในช็อปเสียก่อน</p>
    <b>เมนู : </b><button onclick="pageType(this,0);">แจกทุกไอดี</button> | <button onclick="pageType(this,1);">แจกเฉพาะไอดี</button> | <button onclick="pageType(this,2);">แจกเฉพาะผู้เล่นที่เล่นวันนี้</button>
    <div id="giveitem_proc" style="display:none;">
        ประเภทรายการที่จะทำคือ : <b><span id="modetxtShow"></div></b>
        <div id="giveitem_proc_report"></div>
        <table id="giveitem_table_proc" style="display:none;">
            <tr>
                <td style="width: 159px;">ไอเทมที่ต้องการแจก :</td>
                <td style="width: 299px;"><?php echo buildSelectText("itemlist","main_giveitem_itemlist",0,$arrlist); ?></td>
            </tr>
            <tr id="tableid">
                <td>ไอดี :</td>
                <td><input type="text" id="userid"></td>
            </tr>
            <tr id="tabledate">
                <td>เฉพาะไอดีที่เข้าเล่นใน :</td>
                <td>
                    วัน : <?php echo buildSelectDay("ddd","",$today_day); ?>
                    เดือน : <?php echo buildSelectMonth("mmm","",$today_month); ?>
                    ปี : <?php echo buildSelectYear("yyy","",$today_year); ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><div align="center"><input type="hidden" id="pageType" value="0"><button id="doProc">ทำรายการ</button></div></td>
            </tr>
        </table>
    </div>
</div>
<script type="text/javascript" src="js/classicloading.js"></script>
<script type="text/javascript" src="js/giveitem.js"></script>
<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $submit )
    CMD_PROCESS();
else
    CMD_UI();

?>
