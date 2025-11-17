<?php
define( "MAX_USER_INVEN" , 5);

class UserInven
{
    public $SlotStore = 0;
    public $Inventory = array();
    
    public function __construct() {
        ;
    }
    
    public function ReadUserInven( $UserInven )
    {
        $cNeoSerialMemory = new CNeoSerialMemory();
        $cNeoSerialMemory->OpenMemory();
        $cNeoSerialMemory->WriteBuffer( $UserInven );
        
        $this->SlotStore = $cNeoSerialMemory->ReadInt();
        if ( $this->SlotStore > MAX_USER_INVEN ) $this->SlotStore = MAX_USER_INVEN;
        $cNeoSerialMemory->SetSeekNow();
        
        for( $i = 0 ; $i < $this->SlotStore ; $i++ )
        {
            $this->Inventory[ $i ] = new CNeoChaInven;
            $this->Inventory[ $i ]->LoadChaInven( $cNeoSerialMemory->GetBuffer() );
            $this->Inventory[ $i ]->UpdateVar2Binary();
            $Binary = $this->Inventory[ $i ]->SaveChaInven();
            
            $cNeoSerialMemory->SetToDefault();
            $cNeoSerialMemory->ReadBuffer( strlen( $Binary ) );
            $cNeoSerialMemory->SetSeekNow();
        }
        
        return true;
    }
};

?>
