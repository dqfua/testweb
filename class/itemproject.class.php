<?php

class CItemProject
{
    private $__CURRENT_SESSION;
    private $pItemName;
    
    public function __construct($MemNum)
    {
        $this->__CURRENT_SESSION = sprintf("%d__CITEMPROJECT",$MemNum);
        $this->pItemName = unserialize( CInput::GetInstance()->GetValue( $this->__CURRENT_SESSION , IN_SESSION ) );
        if ( !$this->pItemName )
        {
            $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
            $cNeoSQLConnectODBC->ConnectRanWeb( );

            $this->pItemName = new _tdata();
            $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "SELECT ItemName,ItemMain,ItemSub FROM ItemProject WHERE MemNum = %d AND ItemDelete = 0" , $MemNum ) );
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $this->pItemName->AddData( "ItemMain" , $cNeoSQLConnectODBC->Result( "ItemMain" , ODBC_RETYPE_INT ) );
                $this->pItemName->AddData( "ItemSub" , $cNeoSQLConnectODBC->Result( "ItemSub" , ODBC_RETYPE_INT ) );
                $this->pItemName->AddData( "ItemName" , CBinaryCover::tis620_to_utf8( $cNeoSQLConnectODBC->Result( "ItemName" , ODBC_RETYPE_THAI ) ) );
                $this->pItemName->NextData();
            }

            $cNeoSQLConnectODBC->CloseRanWeb();
            
            CInput::GetInstance()->AddValue($this->__CURRENT_SESSION , serialize($this->pItemName) , IN_SESSION );
            CInput::GetInstance()->UpdateSession();
        }
    }
    
    public function GetItemNameL( $m , $s )
    {
        for( $i = 0 ; $i < $this->pItemName->GetRollData() ; $i++ )
        {
            $ppData = $this->pItemName->GetData( $i );
            if ( $m == $ppData["ItemMain"] && $s == $ppData["ItemSub"] ) return $ppData["ItemName"];
        }
        return "Unknow";
    }
}

?>