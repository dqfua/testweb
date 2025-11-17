<?php
class CRanShop
{
        static public function BuildItem2PutOnItem( $ItemMain , $ItemSub , $TheTime , $Item_TurnNum
        , $ItemDrop , $ItemDamage , $ItemDefense , $Item_Res_Fire , $Item_Res_Ice , $Item_Res_Ele , $Item_Res_Poison , $Item_Res_Spirit
        , $Item_Op1 , $Item_Op2 , $Item_Op3 , $Item_Op4 , $Item_Op1_Value , $Item_Op2_Value , $Item_Op3_Value , $Item_Op4_Value
        , $ServerType )
        {
            global $ITEMGEN_SHOP,$ITEMGEN_GMEDIT;
            
            $cNeoSerialMemory = new CNeoSerialMemory;
            $cNeoSerialMemory->OpenMemory(  );
            
            //Item Main , Sub
            $cNeoSerialMemory->WriteWord( $ItemMain );
            $cNeoSerialMemory->WriteWord( $ItemSub );
            //? ชนิด LONGLONG ใช้ในการอ้างอิงถึง UserNum ที่ดรอปได้มาของไอเทมชิ้นนี้(เจ้าของไอเทมที่ดรอปได้มานั้นเอง)
            $cNeoSerialMemory->WriteWord( 65535 ); //
            $cNeoSerialMemory->WriteWord( 65535 ); //
            //time
            $cNeoSerialMemory->WriteInt( $TheTime );
            $cNeoSerialMemory->WriteInt( 0 ); // what this??
            //?
            $cNeoSerialMemory->WriteInt( 0 );
            $cNeoSerialMemory->WriteInt( 0 );
            //
            $cNeoSerialMemory->WriteByte( 1 );
            $cNeoSerialMemory->WriteByte( 0 );
            $cNeoSerialMemory->WriteByte( 0 );
            $cNeoSerialMemory->WriteByte( 0 );
            $cNeoSerialMemory->WriteByte( 0 );
            $cNeoSerialMemory->WriteByte( 0 );
            $cNeoSerialMemory->WriteByte( 0 );
            $cNeoSerialMemory->WriteByte( 0 );

            //trun num
            $cNeoSerialMemory->WriteWord( $Item_TurnNum );
            
            //GenType
            $GetType = $ITEMGEN_SHOP;
            if ( $ItemDrop ) $GetType = $ITEMGEN_GMEDIT;
            
            $cNeoSerialMemory->WriteByte( $GetType );
            //ChnID | เซิร์ฟเวอร์ Channel ที่ดรอปได้มา
            $cNeoSerialMemory->WriteByte( 0 );
            //cFieldID | เซิร์ฟเวอร์ Field ที่ดรอปได้มา
            $cNeoSerialMemory->WriteByte( 0xFF );
            //Damage
            $cNeoSerialMemory->WriteByte( $ItemDamage );
            $cNeoSerialMemory->WriteByte( $ItemDefense );
            $cNeoSerialMemory->WriteByte( $Item_Res_Fire );
            $cNeoSerialMemory->WriteByte( $Item_Res_Ice );
            $cNeoSerialMemory->WriteByte( $Item_Res_Ele );
            $cNeoSerialMemory->WriteByte( $Item_Res_Poison );
            $cNeoSerialMemory->WriteByte( $Item_Res_Spirit );
            //OptType
            $cNeoSerialMemory->WriteByte( $Item_Op1 );
            $cNeoSerialMemory->WriteByte( $Item_Op2 );
            $cNeoSerialMemory->WriteByte( $Item_Op3 );
            $cNeoSerialMemory->WriteByte( $Item_Op4 );
            $cNeoSerialMemory->WriteWord( $Item_Op1_Value );
            $cNeoSerialMemory->WriteWord( $Item_Op2_Value );
            $cNeoSerialMemory->WriteWord( $Item_Op3_Value );
            $cNeoSerialMemory->WriteWord( $Item_Op4_Value );

            if ( $ServerType == SERVTYPE_EP7 || $ServerType == SERVTYPE_PLUSONLINE )
            {
                    //PetID
                    $cNeoSerialMemory->WriteInt( 0 );
                    //VehicleID
                    $cNeoSerialMemory->WriteInt( 0 );
                    //VietnamGainItem
                    $cNeoSerialMemory->WriteByte( 0 );
                    //?
                    $cNeoSerialMemory->WriteByte( 0 );
                    $cNeoSerialMemory->WriteByte( 0 );
                    $cNeoSerialMemory->WriteByte( 0 );
                    $cNeoSerialMemory->WriteByte( 0 );
                    $cNeoSerialMemory->WriteByte( 0 );
                    $cNeoSerialMemory->WriteByte( 0 );
                    $cNeoSerialMemory->WriteByte( 0 );

                    if ( $ServerType == SERVTYPE_PLUSONLINE )
                    {
                        //suit change color system
                        //$cNeoSerialMemory->WriteByte( $this->Item_bSubmitColor[$i] );
                        $cNeoSerialMemory->WriteInt( 0 );
                        $cNeoSerialMemory->WriteInt( 0 );
                    }
            }else if ( $ServerType == SERVERTYPE_EP8 )
            {
                //PetID
                $cNeoSerialMemory->WriteInt( 0 );
                //VehicleID
                $cNeoSerialMemory->WriteInt( 0 );
                //VietnamGainItem
                $cNeoSerialMemory->WriteByte( 0 );
                //?
                $cNeoSerialMemory->WriteByte( 0 );
                $cNeoSerialMemory->WriteByte( 0 );
                $cNeoSerialMemory->WriteByte( 0 );
                //ownerid
                $cNeoSerialMemory->WriteInt( 0 );
            }
            return $cNeoSerialMemory;
        }
        
        static public function BuildItem2ChaInven( $InvenX , $InvenY , $ItemMain , $ItemSub , $TheTime , $Item_TurnNum
        , $ItemDrop , $ItemDamage , $ItemDefense , $Item_Res_Fire , $Item_Res_Ice , $Item_Res_Ele , $Item_Res_Poison , $Item_Res_Spirit
        , $Item_Op1 , $Item_Op2 , $Item_Op3 , $Item_Op4 , $Item_Op1_Value , $Item_Op2_Value , $Item_Op3_Value , $Item_Op4_Value
        , $ServerType )
        {
            $cNeoSerialMemory = new CNeoSerialMemory;
            $cNeoSerialMemory->OpenMemory(  );

            //Item X , Y
            $cNeoSerialMemory->WriteWord( $InvenX  );
            $cNeoSerialMemory->WriteWord( $InvenY );
            //?
            $cNeoSerialMemory->WriteWord( 0 );
            $cNeoSerialMemory->WriteWord( 0 );
            
            $cNeoSerialMemory2 = self::BuildItem2PutOnItem($ItemMain, $ItemSub, $TheTime, $Item_TurnNum, $ItemDrop, $ItemDamage, $ItemDefense, $Item_Res_Fire, $Item_Res_Ice, $Item_Res_Ele, $Item_Res_Poison, $Item_Res_Spirit, $Item_Op1, $Item_Op2, $Item_Op3, $Item_Op4, $Item_Op1_Value, $Item_Op2_Value, $Item_Op3_Value, $Item_Op4_Value, $ServerType);
            $cNeoSerialMemory->WriteBuffer( $cNeoSerialMemory2->GetBuffer() );
            
            return $cNeoSerialMemory;
        }
        
	static public function GiveItemToItemBankFromCardSys( $MemNum , $UserNum , $RefillNum , $password , $ntype )
	{
		if ( $ntype >= MAX_CARD_TYPE || $ntype < 0 ) return FALSE;
		$cItemPoint = new CItemPoint( $MemNum );
		for( $w = 0 ;  $w < MAX_CARD_TYPE ; $w++ )
		{
			if ( $w != $ntype ) continue;
			for( $i = 0; $i < $cItemPoint->GetItemNum($w) ; $i++ )
			{
				$ItemMain = $cItemPoint->GetItemMain($w,$i);
				$ItemSub = $cItemPoint->GetItemSub($w,$i);
				if ( $ItemMain != 0xFFFF && $ItemSub != 0xFFFF )
				{
					$UserID = CNeoUser::GetUserIDFromUserNum( $MemNum , $UserNum );
					if ( strlen( $UserID ) > 0 )
					{
						CRanShop::InsertItemID( $MemNum , $ItemMain , $ItemSub );
						CRanShop::AddItemToItemBank( $MemNum , $UserID , $ItemMain , $ItemSub );
						CNeoLog::LogSysItemPointGet( $MemNum , $UserNum , $RefillNum , $ItemMain , $ItemSub );
					}
				}
			}
		}
		return TRUE;
	}
	
	static public function SetProductNum( $id )
	{
		$id = substr( $id , 0 , ID_LENGTH );
		$year = date( "y" );
		$month = date( "m" );
		$day = date( "d" );
		$hour = date( "h" );
		$minut = date( "i" );
		$second = date( "s" );
		$szReturn = sprintf( "%s%02d%02d%02d%02d%02d%02d%02d",$id,$year,$month,$day,$hour,$minut,$second,floor(rand(0,99)) );
		return  $szReturn;
	}
        
	static public function InsertItemID( $memnum , $main , $sub )
	{
		$nProductNum = 0;
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $memnum );
		//$cWeb->GetSysmFromDB( $memnum );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$pp = $cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP(), $cWeb->GetRanShop_User(), $cWeb->GetRanShop_Pass(), $cWeb->GetRanShop_DB() );
		$szTemp = sprintf( "SELECT ProductNum FROM ShopItemMap WHERE ItemMain = %d AND ItemSub = %d" , $main , $sub );
                //if ( !$pp ) printf( "%s,%s,%s,%s" , $cWeb->GetRanShop_IP(), $cWeb->GetRanShop_User(), $cWeb->GetRanShop_Pass(), $cWeb->GetRanShop_DB() );
		$cNeoSQLConnectODBC->QueryRanShop($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$nProductNum = $cNeoSQLConnectODBC->Result( "ProductNum" , ODBC_RETYPE_INT );
		}
		if ( $nProductNum == 0 )
		{
			$szTemp = sprintf( "INSERT INTO ShopItemMap( ItemMain , ItemSub ) VALUES( %d , %d )" , $main , $sub );
			$cNeoSQLConnectODBC->QueryRanShop($szTemp);
			$szTemp = sprintf( "SELECT ProductNum FROM ShopItemMap WHERE ItemMain = %d AND ItemSub = %d" , $main , $sub );
			$cNeoSQLConnectODBC->QueryRanShop($szTemp);
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
				$nProductNum = $cNeoSQLConnectODBC->Result( "ProductNum" , ODBC_RETYPE_INT  );
			}
		}
		$cNeoSQLConnectODBC->CloseRanShop();
		return $nProductNum;
	}
        
	public function AddItemToItemBank( $memnum , $UserID , $ItemMain , $ItemSub )
	{
		global $_CONFIG;
		$Purkey = self::SetProductNum( $UserID );
                
                $bFound = false;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $memnum );

		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP(), $cWeb->GetRanShop_User(), $cWeb->GetRanShop_Pass(), $cWeb->GetRanShop_DB() );
		$szTemp = sprintf( " SELECT ProductNum FROM ShopItemMap WHERE ItemMain = %d AND ItemSub = %d ",$ItemMain,$ItemSub );
		//CDebugLog::Write( $szTemp );
		$cNeoSQLConnectODBC->QueryRanShop( $szTemp );
                while( $cNeoSQLConnectODBC->FetchRow() )
                {
					$ProductNum = $cNeoSQLConnectODBC->Result( "ProductNum" , ODBC_RETYPE_INT  );
                    $bFound = true;
                }
                if ( $bFound )
                {
                    $szTemp = sprintf( "INSERT INTO ShopPurchase(UserUID,ProductNum) VALUES('%s',%d)",$UserID,$ProductNum );
                    $cNeoSQLConnectODBC->QueryRanShop( $szTemp );
					//CDebugLog::Write( $szTemp );
                }
		$cNeoSQLConnectODBC->CloseRanShop();
                return $bFound;
	}
        
	public function BuyItem_F( $ChaNum , $ItemMain , $ItemSub )
	{
		global $_CONFIG;
		if ( $ChaNum <= 0 || empty($ChaNum) ) return false;
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "UPDATE ItemProject SET ItemSell = ItemSell+1 WHERE MemNum = %d AND ItemMain = %d AND ItemSub = %d", $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$ItemMain,$ItemSub);
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		$szTemp = sprintf( "SELECT TOP 1
						   ItemNum,ItemName,ItemImage,ItemPrice
						  ,ItemDay,ItemDrop,ItemDamage,ItemDefense,Item_TurnNum
						  ,Item_Res_Ele,Item_Res_Fire,Item_Res_Ice,Item_Res_Poison,Item_Res_Spirit
						  ,Item_Op1,Item_Op1_Value,Item_Op2,Item_Op2_Value,Item_Op3,Item_Op3_Value,Item_Op4,Item_Op4_Value
						  FROM ItemProject WHERE MemNum = %d AND ItemMain = %d AND ItemSub = %d AND ItemType = 1 AND ( ItemShow = 0 OR ItemShow = 2 ) AND ItemDelete = 0"
						  ,$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$ItemMain,$ItemSub );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$bDo = false;
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$bDo = true;
			$ItemNum = $cNeoSQLConnectODBC->Result( "ItemNum" , ODBC_RETYPE_INT  );
			$ItemName = $cNeoSQLConnectODBC->Result( "ItemName" , ODBC_RETYPE_THAI  );
			$ItemImage = $cNeoSQLConnectODBC->Result( "ItemImage" , ODBC_RETYPE_THAI  );
			$ItemPrice = $cNeoSQLConnectODBC->Result( "ItemPrice" , ODBC_RETYPE_INT  );
			$ItemDay = $cNeoSQLConnectODBC->Result( "ItemDay" , ODBC_RETYPE_INT );
			$ItemDrop = $cNeoSQLConnectODBC->Result( "ItemDrop" , ODBC_RETYPE_INT );
			$ItemDamage = $cNeoSQLConnectODBC->Result( "ItemDamage" , ODBC_RETYPE_INT );
			$ItemDefense = $cNeoSQLConnectODBC->Result( "ItemDefense" , ODBC_RETYPE_INT );
			$Item_TurnNum = $cNeoSQLConnectODBC->Result( "Item_TurnNum" , ODBC_RETYPE_INT );
			$Item_Res_Ele = $cNeoSQLConnectODBC->Result( "Item_Res_Ele" , ODBC_RETYPE_INT );
			$Item_Res_Fire = $cNeoSQLConnectODBC->Result( "Item_Res_Fire" , ODBC_RETYPE_INT );
			$Item_Res_Ice = $cNeoSQLConnectODBC->Result( "Item_Res_Ice" , ODBC_RETYPE_INT );
			$Item_Res_Poison = $cNeoSQLConnectODBC->Result( "Item_Res_Poison" , ODBC_RETYPE_INT );
			$Item_Res_Spirit = $cNeoSQLConnectODBC->Result( "Item_Res_Spirit" , ODBC_RETYPE_INT );
			$Item_Op1 = $cNeoSQLConnectODBC->Result( "Item_Op1" , ODBC_RETYPE_INT );
			$Item_Op1_Value = $cNeoSQLConnectODBC->Result( "Item_Op1_Value" , ODBC_RETYPE_INT );
			$Item_Op2 = $cNeoSQLConnectODBC->Result( "Item_Op2" , ODBC_RETYPE_INT );
			$Item_Op2_Value = $cNeoSQLConnectODBC->Result( "Item_Op2_Value" , ODBC_RETYPE_INT );
			$Item_Op3 = $cNeoSQLConnectODBC->Result( "Item_Op3" , ODBC_RETYPE_INT );
			$Item_Op3_Value = $cNeoSQLConnectODBC->Result( "Item_Op3_Value" , ODBC_RETYPE_INT );
			$Item_Op4 = $cNeoSQLConnectODBC->Result( "Item_Op4" , ODBC_RETYPE_INT );
			$Item_Op4_Value = $cNeoSQLConnectODBC->Result( "Item_Op4_Value" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		$cNeoChaInven = new CNeoChaInven( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		if ( $bDo )
		{
			$cNeoChaInven->ChaInven_DB( $ChaNum );
			
			$InvenX = -1;
			$InvenY = -1;
			
			for( $ix = 0 ; $ix <= INVEN_X; $ix ++ )
			{
				for( $iy = 0 ; $iy <= INVEN_Y; $iy ++ )
				{
					if ( $cNeoChaInven->InvenFind( $ix , $iy ) == ITEM_ERROR )
					{
						$InvenX = $ix;
						$InvenY = $iy;
						break;
					}
				}
			}
			
			if ( $InvenY == -1 || $InvenX == -1 ) return false;
                        
                        $cNeoSerialMemory = self::BuildItem2ChaInven($InvenX, $InvenY, $ItemMain, $ItemSub, time(), $Item_TurnNum, $ItemDrop, $ItemDamage, $ItemDefense, $Item_Res_Fire, $Item_Res_Ice, $Item_Res_Ele, $Item_Res_Poison, $Item_Res_Spirit, $Item_Op1, $Item_Op2, $Item_Op3, $Item_Op4, $Item_Op1_Value, $Item_Op2_Value, $Item_Op3_Value, $Item_Op4_Value, $cWeb->GetServerType() );
			
			//?
			//echo strtoupper( $cNeoSerialMemory->GetBuffer() );
			$cNeoChaInven->AddItem( $cNeoSerialMemory );
                        $cNeoChaInven->UpdateVar2Binary();
			return $cNeoChaInven->SaveChaInven();
		}
		
		return false;
	}
	public function BuyItem( $UserID , $ItemMain , $ItemSub )
	{
		global $_CONFIG;
		$Purkey = self::SetProductNum( $UserID );
                
                $bFound = false;
		
		$cWeb = new CNeoWeb;
		$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanShop( $cWeb->GetRanShop_IP(), $cWeb->GetRanShop_User(), $cWeb->GetRanShop_Pass(), $cWeb->GetRanShop_DB() );
		$szTemp = sprintf( " SELECT ProductNum FROM ShopItemMap WHERE ItemMain = %d AND ItemSub = %d ",$ItemMain,$ItemSub );
		$cNeoSQLConnectODBC->QueryRanShop( $szTemp );
                while( $cNeoSQLConnectODBC->FetchRow() )
                {
					$ProductNum = $cNeoSQLConnectODBC->Result( "ProductNum" , ODBC_RETYPE_INT );
                    //$szTemp = sprintf( "INSERT INTO ShopPurchase(PurKey,UserUID,ProductNum) VALUES('%s','%s',%d)",$Purkey,$UserID,$ProductNum );
                    $szTemp = sprintf( "INSERT INTO ShopPurchase(UserUID,ProductNum) VALUES('%s',%d)",$UserID,$ProductNum );
                    $cNeoSQLConnectODBC->QueryRanShop( $szTemp );
                    $cNeoSQLConnectODBC->ConnectRanWeb();
                    $szTemp = sprintf( "UPDATE ItemProject SET ItemSell = ItemSell+1 WHERE MemNum = %d AND ItemMain = %d AND ItemSub = %d", $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$ItemMain,$ItemSub);
                    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
                    $cNeoSQLConnectODBC->CloseRanWeb();
					//printf( "\$ProductNum = $ProductNum<br>" );
                    $bFound = true;
                }
		$cNeoSQLConnectODBC->CloseRanShop();
                return $bFound;
	}
}
?>