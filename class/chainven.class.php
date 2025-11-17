<?php
$TRADE_SALE = 0x01;
$TRADE_EXCHANGE = 0x02;
$TRADE_THROW = 0x04;
$TRADE_EVENT_SGL = 0x08;
$TRADE_DISGUISE = 0x10;
$TRADE_TIMELMT = 0x20;
$TRADE_CHANNEL_ALL = 0x40;
$TRADE_ALL = $TRADE_SALE|$TRADE_EXCHANGE|$TRADE_THROW;

$ITEMGEN_DEFAULT = 0;
$ITEMGEN_INIT = 1;
$ITEMGEN_SHOP = 2;
$ITEMGEN_NPC = 3;
$ITEMGEN_QUEST = 4;
$ITEMGEN_MOB = 5;
$ITEMGEN_GMEDIT = 6;
$ITEMGEN_OLD = 7;
$ITEMGEN_BILLING = 8;
$ITEMGEN_GMEDIT2 = 9;
$ITEMGEN_ITEMMIX = 10;
$ITEMGEN_GATHERING = 11;
$ITEMGEN_SYSTEM = 12;
$ITEMGEN_ALL = -1;

class CNeoChaInven extends _CNeoChaInven
{
    public function __construct( $MemNum )
    {
        parent::SetMemNum($MemNum);
    }

    public function SetMemNum( $MemNum ) { return $this->MemNum = $MemNum; }
    public function GetItemNum(){ return $this->ItemSlotNum; }
    public function SetChaNum( $ChaNum ) { return $this->ChaNum = $ChaNum; }

    public function GetItemMain( $ItemID ) { return $this->Item_InvenMain[$ItemID]; }
    public function GetItemSub( $ItemID ){ return $this->Item_InvenSub[$ItemID]; }
    public function GetItemDrop( $ItemID ){ return $this->Item_cGenType[$ItemID]; }
    public function GetItemTrunNum( $ItemID ){ return $this->Item_wTrunNum[$ItemID]; }
    public function GetItemDamage( $ItemID ){ return $this->Item_cDamage[$ItemID]; }
    public function GetItemDefense( $ItemID ){ return $this->Item_cDefense[$ItemID]; }
    public function GetItemResistEle( $ItemID ){ return $this->Item_cResist_elec[$ItemID]; }
    public function GetItemResistFire( $ItemID ){ return $this->Item_cResist_fire[$ItemID]; }
    public function GetItemResistIce( $ItemID ){ return $this->Item_cResist_ice[$ItemID]; }
    public function GetItemResistPoison( $ItemID ){ return $this->Item_cResist_poison[$ItemID]; }
    public function GetItemResistSpirit( $ItemID ){ return $this->Item_cResist_spirit[$ItemID]; }
    public function GetItemOptType1( $ItemID ){ return $this->Item_cOptType1[$ItemID]; }
    public function GetItemOptType2( $ItemID ){ return $this->Item_cOptType2[$ItemID]; }
    public function GetItemOptType3( $ItemID ){ return $this->Item_cOptType3[$ItemID]; }
    public function GetItemOptType4( $ItemID ){ return $this->Item_cOptType4[$ItemID]; }
    public function GetItemOptVal1( $ItemID ){ return $this->Item_nOptVALUE1[$ItemID]; }
    public function GetItemOptVal2( $ItemID ){ return $this->Item_nOptVALUE2[$ItemID]; }
    public function GetItemOptVal3( $ItemID ){ return $this->Item_nOptVALUE3[$ItemID]; }
    public function GetItemOptVal4( $ItemID ){ return $this->Item_nOptVALUE4[$ItemID]; }
    
    public function SetItemOptType1( $ItemID , $val ){ $this->Item_cOptType1[$ItemID] = $val; }
    public function SetItemOptType2( $ItemID , $val ){ $this->Item_cOptType2[$ItemID] = $val; }
    public function SetItemOptType3( $ItemID , $val ){ $this->Item_cOptType3[$ItemID] = $val; }
    public function SetItemOptType4( $ItemID , $val ){ $this->Item_cOptType4[$ItemID] = $val; }

    public function  InvenFind( $x , $y )
    {
            for( $i = 0 ; $i < $this->ItemSlotNum; $i++ )
            {
                    if ( $x == $this->Item_InvenX[$i] && $y == $this->Item_InvenY[$i] )
                    return $i;
            }
            return ITEM_ERROR;
    }

    public function FindItem( $ItemMain , $ItemSub )
    {
            for( $i = 0 ; $i < $this->ItemSlotNum; $i++ )
            {
                    if ( $ItemMain == $this->Item_InvenMain[$i] && $ItemSub == $this->Item_InvenSub[$i] )
                            return $i;
            }
            return ITEM_ERROR;
    }

    public function UpdateItem( &$cNeoSerialMemory , $i )
    {
            if ( $i <= ITEM_ERROR || $i >= $this->ItemSlotNum ) return false;
            parent::SetBinaryItemSlot( $i , $cNeoSerialMemory->GetBuffer() );
            return true;
    }
    
    public function UpdateVar2Binary()
    {
        for( $i = 0 ; $i < $this->ItemSlotNum ; $i++ )
        {
            self::UpdateBinaryItemSlot( $i );
        }
    }

    public function SaveChaPutOnItems()
    {
            $pBuffer = parent::SaveChaPutOnItems();
            
            if ( $this->ChaNum <= 0 || $pBuffer == "" ) return false;

            $cWeb = new CNeoWeb;
            $cWeb->GetDBInfoFromWebDB( $this->MemNum );
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
            $cNeoSQLConnectODBC->QueryRanGame( sprintf("UPDATE ChaInfo SET ChaPutOnItems = 0x%s WHERE ChaNum = %d",$pBuffer,$this->ChaNum) );
            $cNeoSQLConnectODBC->CloseRanGame();
            return $pBuffer;
    }

    public function SaveChaInven()
    {
            $pBuffer = parent::SaveChaInven();

            if ( $this->ChaNum <= 0 || $pBuffer == "" || empty( $this->ChaNum ) ) return $pBuffer;

            $cWeb = new CNeoWeb;
            $cWeb->GetDBInfoFromWebDB( $this->MemNum );
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
            $cNeoSQLConnectODBC->QueryRanGame( sprintf("UPDATE ChaInfo SET ChaInven = 0x%s WHERE ChaNum = %d",$pBuffer,$this->ChaNum) );
            $cNeoSQLConnectODBC->CloseRanGame();
            return $pBuffer;
    }

    public function ChaInven_DB( $ChaNum )
    {
            $cWeb = new CNeoWeb;
            $cWeb->GetDBInfoFromWebDB( $this->MemNum );
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
            $cNeoSQLConnectODBC->QueryRanGame( sprintf("SELECT ChaNum,ChaInven FROM ChaInfo WHERE ChaNum = %d",$ChaNum) );
            $Binary = $cNeoSQLConnectODBC->Result( "ChaInven" , ODBC_RETYPE_BINARY );
            $cNeoSQLConnectODBC->CloseRanGame();
            if ( empty($Binary) || $Binary == NULL )
            return false;
            $this->ChaNum = $ChaNum;
            $this->Binary_Backup = $Binary;
            parent::LoadChaInven( $Binary );
            return true;
    }
}

?>
