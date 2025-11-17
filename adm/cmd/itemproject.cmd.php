<?php
////////////////////////////
// header for itemproject //
////////////////////////////

$arrItemType = array( "Item Bank" , "Item Inventory" );
$arrItemDrop = array( "Drop" , "No Drop" );
/*
$arrItemOption = array( "NULL" , "DAMAGE(%)" , "DEFENSE(%)" , "HITRATE(+%)" , "AVOIDRATE(+%)" , "HP" , "MP" , "SP" , "HP_INC" , "MP_INC" , "SP_INC" , "HMS_INC" , "GRIND_DAMAGE"
                         , "GRIND_DEFENSE" , "ATTACK_RANDOM" , "DIS_SP" , "RESIST" );
 */
$arrItemShowType = array( "แสดง" , "ไม่แสดง" , "เฉพาะ GM" );
$arrItemResell = array( "ไม่รับคืน" , "รับคืน" );
function ItemTypeData(){ global $arrItemType; return $arrItemType; }
function ItemDropData(){ global $arrItemDrop; return $arrItemDrop; }
function ItemOptionData(){ global $arrItemOption; return $arrItemOption; }
function ItemShowTypeData(){ global $arrItemShowType; return $arrItemShowType; }
function ItemResellData(){ global $arrItemResell; return $arrItemResell; }

define("ITEMLIST_NAME_LENGTH", 25);

$CURRENT_SESSION_ITEMLIST = "itemlistdata";
$CURRENT_SESSION_UPLOAD_IMG = sprintf( "nowitemproject_upload_img_edit" );

class ItemProjectTEMP
{
    public $ItemNum;
    public $SubNum;
    public $ItemMain;
    public $ItemSub;
    public $ItemName;
    public $ItemComment;
    public $ItemImage;
    public $Item_Resell;
    public $Item_Resell_Percent;
    public $ItemPrice;
    public $ItemTimePrice;
    public $ItemBonusPointPrice;
    public $ItemSell;
    public $ItemType;
    public $ItemSock;
    public $ItemShow;
    public $ItemDay;
    public $ItemDrop;
    public $ItemDamage;
    public $ItemDefense;
    public $Item_TurnNum;
    public $Item_Res_Ele;
    public $Item_Res_Fire;
    public $Item_Res_Ice;
    public $Item_Res_Poison;
    public $Item_Res_Spirit;
    public $Item_Op1;
    public $Item_Op1_Value;
    public $Item_Op2;
    public $Item_Op2_Value;
    public $Item_Op3;
    public $Item_Op3_Value;
    public $Item_Op4;
    public $Item_Op4_Value;
    public $Item_MaxReborn;
    
    public $Item_InvenX;
    public $Item_InvenY;
    
    public function SetData( $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $Item_Resell
                             , $Item_Resell_Percent , $ItemPrice , $ItemTimePrice , $ItemBonusPointPrice , $ItemSell , $ItemType , $ItemSock , $ItemShow , $ItemDay
                             , $ItemDrop , $ItemDamage , $ItemDefense , $Item_TurnNum , $Item_Res_Ele , $Item_Res_Fire , $Item_Res_Ice
                             , $Item_Res_Poison , $Item_Res_Spirit , $Item_Op1 , $Item_Op1_Value , $Item_Op2 , $Item_Op2_Value , $Item_Op3
                             , $Item_Op3_Value , $Item_Op4 , $Item_Op4_Value , $Item_MaxReborn
                             , $Item_InvenX = 0 , $Item_InvenY = 0
            )
    {
        $this->SubNum = $SubNum;
        $this->ItemMain = $ItemMain;
        $this->ItemSub = $ItemSub;
        $this->ItemName = $ItemName;
        $this->ItemComment = $ItemComment;
        $this->ItemImage = $ItemImage;
        $this->Item_Resell = $Item_Resell;
        $this->Item_Resell_Percent = $Item_Resell_Percent;
        $this->ItemPrice = $ItemPrice;
        $this->ItemTimePrice = $ItemTimePrice;
        $this->ItemBonusPointPrice = $ItemBonusPointPrice;
        $this->ItemSell = $ItemSell;
        $this->ItemType = $ItemType;
        $this->ItemSock = $ItemSock;
        $this->ItemShow = $ItemShow;
        $this->ItemDay = $ItemDay;
        $this->ItemDrop = $ItemDrop;
        $this->ItemDamage = $ItemDamage;
        $this->ItemDefense = $ItemDefense;
        $this->Item_TurnNum = $Item_TurnNum;
        $this->Item_Res_Ele = $Item_Res_Ele;
        $this->Item_Res_Fire = $Item_Res_Fire;
        $this->Item_Res_Ice = $Item_Res_Ice;
        $this->Item_Res_Poison = $Item_Res_Poison;
        $this->Item_Res_Spirit = $Item_Res_Spirit;
        $this->Item_Op1 = $Item_Op1;
        $this->Item_Op1_Value = $Item_Op1_Value;
        $this->Item_Op2 = $Item_Op2;
        $this->Item_Op2_Value = $Item_Op2_Value;
        $this->Item_Op3 = $Item_Op3;
        $this->Item_Op3_Value = $Item_Op3_Value;
        $this->Item_Op4 = $Item_Op4;
        $this->Item_Op4_Value = $Item_Op4_Value;
        $this->Item_MaxReborn = $Item_MaxReborn;
        
        $this->Item_InvenX = $Item_InvenX;
        $this->Item_InvenY = $Item_InvenY;
    }
    
    public function __construct()
    {
        $this->SubNum = 0;
        $this->ItemMain = 0;
        $this->ItemSub = 0;
        $this->ItemName = "";
        $this->ItemComment = "";
        $this->ItemImage = "";
        $this->Item_Resell = 0;
        $this->Item_Resell_Percent = 0;
        $this->ItemPrice = 0;
        $this->ItemTimePrice = 0;
        $this->ItemBonusPointPrice = 0;
        $this->ItemSell = 0;
        $this->ItemType = 0;
        $this->ItemSock = 0;
        $this->ItemShow = 0;
        $this->ItemDay = 0;
        $this->ItemDrop = 0;
        $this->ItemDamage = 0;
        $this->ItemDefense = 0;
        $this->Item_TurnNum = 0;
        $this->Item_Res_Ele = 0;
        $this->Item_Res_Fire = 0;
        $this->Item_Res_Ice = 0;
        $this->Item_Res_Poison = 0;
        $this->Item_Res_Spirit = 0;
        $this->Item_Op1 = 0;
        $Item->Item_Op1_Value = 0;
        $this->Item_Op2 = 0;
        $Item->Item_Op2_Value = 0;
        $this->Item_Op3 = 0;
        $Item->Item_Op3_Value = 0;
        $this->Item_Op4 = 0;
        $Item->Item_Op4_Value = 0;
        $Item->Item_MaxReborn = 0;
        
        $Item->Item_InvenX = 0;
        $Item->Item_InvenY = 0;
    }
};

class ItemList
{
    private $pData = array();
    private $nData = 0;
    public function GetRoll() { return $this->nData; }
    public function GetData( $index ){ return $this->pData[ $index ]; }
    public function DelData( $itemnum )
    {
        /*
        echo $itemnum;
        unset( $this->pData[ $itemnum ] );
        $this->nData--;
         * 
         */
    }
    public function AddItem( $pItemProject )
    {
        $this->pData[ $this->nData ] = $pItemProject;
        $this->nData++;
    }
};

class __SubList
{
    public $SubNum = 0;
    public $SubName = "";
    public function __construct( $SubNum , $SubName )
    {
        $this->SubNum = $SubNum;
        $this->SubName = $SubName;
    }
};

class SubList
{
    private $pData = array();
    private $nData = 0;
    public function Exists( $id )
    {
        for( $i = 0 ; $i < $this->nData ; $i++ )
        {
            $ppData = $this->pData[ $i ];
            if ( $ppData->SubNum == $id ) return true;
        }
        return false;
    }
    public function GetRoll(){ return $this->nData; }
    public function GetData( $index ){ return $this->pData[ $index ]; }
    public function AddData( $SubNum , $SubName )
    {
        $this->pData[ $this->nData ] = new __SubList( $SubNum , $SubName );
        $this->nData++;
    }
    public function __construct(  )
    {
    }
}

function CurrentSession_Sublist( $MemNum ){ return sprintf( "%d_sublistdata",$MemNum ); }
function SubList_GetData( $MemNum )
{
    $CURRENT_SESSION = CurrentSession_Sublist($MemNum);
    $pSubList = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
    if ( !$pSubList )
    {
        $pSubList = new SubList;
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf("SELECT SubNum,SubName FROM SubProject WHERE MemNum = %d AND SubDel = 0" , $MemNum) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
            $SubName = $cNeoSQLConnectODBC->Result("SubName",ODBC_RETYPE_THAI);
            
            //tis620 to utf8
            $SubName = CBinaryCover::tis620_to_utf8( $SubName );
            
            $pSubList->AddData($SubNum, $SubName);
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pSubList) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
    }
    return $pSubList;
}

function CMD_UPLOAD_IMG( $CURRENT_SESSION_UPLOAD_IMG )
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_FILES );
    $pFile = CInput::GetInstance()->GetValue( "Filedata" , IN_FILES );
    if ( !$pFile ) die( "ERROR:FILE" );
    if (!isset($pFile) || !is_uploaded_file($pFile["tmp_name"]) || $pFile["error"] != 0) die( "ERROR:invalid upload" );
    
    $ItemImage = md5( time() . $MemNum );
    $ItemImage .= hash_file('md5', $pFile["tmp_name"]);
    $ItemImage .= OpenSubtitlesHash($pFile["tmp_name"]);
    $ItemImage = $MemNum . "_" . md5( $ItemImage );
    //$ItemImage = $MemNum . "_" . md5( $CURRENT_SESSION_UPLOAD_IMG );
    
    $pUpload = new CUpload( "Filedata" );
    if ( $pUpload->IsOk() )
    {
        $pUpload->SetTypeImage();
        if ( $pUpload->DoImage( $ItemImage ) )
        {
            echo "SUCCESS:" . $ItemImage;
            $ItemImage = CBinaryCover::tis620_to_utf8( $ItemImage );
            CInput::GetInstance()->AddValue( $CURRENT_SESSION_UPLOAD_IMG , serialize( $ItemImage ) , IN_SESSION );
            CInput::GetInstance()->UpdateSession();
            return ;
        }
    }
    CInput::GetInstance()->AddValue( $CURRENT_SESSION_UPLOAD_IMG , serialize( CBinaryCover::tis620_to_utf8( DEFAULT_FILE_ITEMIMAGE ) ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    echo "ERROR:".DEFAULT_FILE_ITEMIMAGE;;
}

?>
