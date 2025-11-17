<?php
echo '<TABLE border="0" cellpadding="3" cellspacing="3" align="center">';

CInput::GetInstance()->BuildFrom( IN_GET );

if ( $_CONFIG["SYSTEM"]["SQLITE"] == false )
{
	$l = CInput::GetInstance()->GetValueInt( "l" , IN_GET );
	$szTemp = sprintf("SELECT TOP %d ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemTimePrice,ItemSell,ItemType,ItemSock,Item_MaxReborn FROM ItemProject WHERE MemNum = %d AND ItemShow = 0 AND ItemDelete = 0 ORDER BY ItemNum DESC",$_CONFIG["SERVERMAN"]["TOP_NEWITEM"],$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
	$cUser = NULL;
	$bUserLoginCheck = false;
	$UType = 0;
	if ( COnline::OnlineGoodCheck( $cUser ) == ONLINE )
	{
		$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
		CGlobal::SetSesUser( serialize($cUser) );
		$cUser = unserialize( CGlobal::GetSesUser() );
		$UserID = $cUser->GetUserID();
		$UserNum = $cUser->GetUserNum();
		if ( $UserNum > 0 && $cUser->GetUserType() >= 30 )
		{
			$UType = 1;
			$bUserLoginCheck = true;
			$szTemp = sprintf("SELECT TOP %d ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemTimePrice,ItemSell,ItemType,ItemSock,Item_MaxReborn FROM ItemProject WHERE MemNum = %d AND ( ItemShow = 0 OR ItemShow = 2 ) AND ItemDelete = 0 ORDER BY ItemNum DESC",$_CONFIG["SERVERMAN"]["TOP_NEWITEM"],$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
		}
	}
	
	$CURRENT_SESSION = sprintf( "%d_%d_newitem" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UType );
	
	class __ItemData
	{
		public $ItemNum;
		public $SubNum;
		public $ItemMain;
		public $ItemSub;
		public $ItemName;
		public $ItemComment;
		public $ItemImage;
		public $ItemPrice;
		public $ItemTimePrice;
		public $ItemType;
		public $ItemSock;
		public $itemreborn;
		public function __construct( $ItemNum , $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $ItemPrice , $ItemTimePrice , $ItemType , $ItemSock , $itemreborn )
		{
			$this->ItemNum = $ItemNum;
			$this->SubNum = $SubNum;
			$this->ItemMain = $ItemMain;
			$this->ItemSub = $ItemSub;
			$this->ItemName = $ItemName;
			$this->ItemComment = $ItemComment;
			$this->ItemImage = $ItemImage;
			$this->ItemPrice = $ItemPrice;
			$this->ItemTimePrice = $ItemTimePrice;
			$this->ItemType = $ItemType;
			$this->ItemSock = $ItemSock;
			$this->itemreborn = $itemreborn;
		}
	};
	class ItemData
	{
		private $pData = array();
		private $nData = 0;
		public function GetRoll(){ return $this->nData; }
		public function GetData( $index ) { return $this->pData[ $index ]; }
		public function AddData( $ItemNum , $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $ItemPrice , $ItemTimePrice , $ItemType , $ItemSock , $itemreborn )
		{
			$this->pData[ $this->nData ] = new __ItemData( $ItemNum , $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $ItemPrice , $ItemTimePrice , $ItemType , $ItemSock , $itemreborn );
			$this->nData++;
		}
	};
	
	$pItemData = unserialize( phpFastCache::get( $CURRENT_SESSION ) );
	if ( !$pItemData )
	{
		$pItemData = new ItemData;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		
		if ( $bUserLoginCheck )
			$szTemp2 = sprintf("SELECT COUNT( ItemNum ) As Count FROM ItemProject WHERE MemNum = %d AND ( ItemShow = 0 OR ItemShow = 2 ) AND ItemDelete = 0",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
		else
			$szTemp2 = sprintf("SELECT COUNT( ItemNum ) As Count FROM ItemProject WHERE MemNum = %d AND ItemShow = 0 AND ItemDelete = 0",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
		
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$ItemNum = $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT);
			$SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
			$ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
			$ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
			$ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
			$ItemComment = $cNeoSQLConnectODBC->Result("ItemComment",ODBC_RETYPE_THAI);
			$ItemImage = $cNeoSQLConnectODBC->Result("ItemImage",ODBC_RETYPE_THAI);
			$ItemPrice = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
			$ItemTimePrice = $cNeoSQLConnectODBC->Result("ItemTimePrice",ODBC_RETYPE_INT);
			$ItemSell = $cNeoSQLConnectODBC->Result("ItemSell",ODBC_RETYPE_INT);
			$ItemType = $cNeoSQLConnectODBC->Result("ItemType",ODBC_RETYPE_INT);
			$ItemSock = $cNeoSQLConnectODBC->Result("ItemSock",ODBC_RETYPE_INT);
			$itemreborn = $cNeoSQLConnectODBC->Result("Item_MaxReborn",ODBC_RETYPE_INT);
			
			$strReplace = str_replace( "/" , "" , PATH_UPLOAD_ITEMIMAGE );
			$ItemImage = str_replace( $strReplace , "" , $ItemImage );
			
			$ItemName_Length = strlen($ItemName);
			$ItemName = substr( $ItemName , 0 , $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] );
			// แก้ไขบรรทัด 108 - เพิ่มการรับค่า return
			$ItemName = CGlobal::DisCharHtml( $ItemName );
			if ( $ItemName_Length > $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] )
			$ItemName .= "...";
			
			$ItemComment_Length = strlen( $ItemComment );
			$ItemComment = substr( $ItemComment , 0 , $_CONFIG["SERVERMAN"]["COMMENT_LENGTH"] );
			// แก้ไขบรรทัด 114 - เพิ่มการรับค่า return
			$ItemComment = CGlobal::DisCharHtml( $ItemComment );
			if ( $ItemComment_Length > $_CONFIG["SERVERMAN"]["COMMENT_LENGTH"] )
			$ItemComment .= "...";
			
			$pItemData->AddData( $ItemNum , $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $ItemPrice , $ItemTimePrice , $ItemType , $ItemSock , $itemreborn );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		
		phpFastCache::set( $CURRENT_SESSION , serialize( $pItemData ) , 300+floor( rand()%300 ) );
	}
	
	$nColume = 5;
	$nColume_Max = 3;
	$i = 0;
	for(  ; $i < $pItemData->GetRoll() ;  )
	{
		$i++;
		if ( $i < ( $l*$_CONFIG["SERVERMAN"]["SHOWITEM"] )+1 ) continue;
		if ( $i > (( $l*$_CONFIG["SERVERMAN"]["SHOWITEM"])+$_CONFIG["SERVERMAN"]["SHOWITEM"]) ) break;
		
		$ppData = $pItemData->GetData( $i-1 );
		
		$ItemNum = $ppData->ItemNum;
		$SubNum = $ppData->SubNum;
		$ItemMain = $ppData->ItemMain;
		$ItemSub = $ppData->ItemSub;
		$ItemName = $ppData->ItemName;
		$ItemComment = $ppData->ItemComment;
		$ItemImage = $ppData->ItemImage;
		$ItemPrice = $ppData->ItemPrice;
		$ItemTimePrice = $ppData->ItemTimePrice;
		// แก้ไข @ operator เป็น isset()
		$ItemSell = isset($ppData->ItemSell) ? $ppData->ItemSell : 0;
		$ItemType = $ppData->ItemType;
		$ItemSock = $ppData->ItemSock;
		$itemreborn = $ppData->itemreborn;
		
		$nColume++;
		if ( $nColume > $nColume_Max )
		{
			$nColume = 0;
			echo "<tr>";
			echo "<td valign=top>";
		}else{
			echo "<td valign=top>";
		}
		
		include("noteitem.php");
		
		if ( $nColume > $nColume_Max )
		{
			$nColume = 0;
			echo "</td>";
			echo "</tr>";
		}else{
			echo "</td>";
		}
	}
}else{
	$pUIDB = new CUIDB_ItemProject_HotProject( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , false );
	$pUIDB->DumpData();
	
	$bGM = FALSE;
	$cUser = NULL;
	if ( COnline::OnlineGoodCheck( $cUser ) == ONLINE )
	{
		$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
		CGlobal::SetSesUser( serialize($cUser) );
		$cUser = unserialize( CGlobal::GetSesUser() );
		$UserID = $cUser->GetUserID();
		$UserNum = $cUser->GetUserNum();
		if ( $UserNum > 0 && $cUser->GetUserType() >= 30 )
		{
			$bGM = TRUE;
		}
	}
	
	$nColume = 5;
	$nColume_Max = 3;
	echo '<TABLE border="0" cellpadding="3" cellspacing="3" align="center">';
	for( $i = 0 ; $i < $pUIDB->GetRow() ; $i++ )
	{
		$nColume++;
		if ( $nColume > $nColume_Max )
		{
			$nColume = 0;
			echo "<tr>";
			echo "<td valign=top>";
		}else{
			echo "<td valign=top>";
		}
		$ItemNum = $pUIDB->GetData( $bGM , $i , "ItemNum" );
		$SubNum = $pUIDB->GetData( $bGM , $i , "SubNum" );
		$ItemMain = $pUIDB->GetData( $bGM , $i , "ItemMain" );
		$ItemSub = $pUIDB->GetData( $bGM , $i , "ItemSub" );
		$ItemName = $pUIDB->GetData( $bGM , $i , "ItemName" );
		$ItemComment = $pUIDB->GetData( $bGM , $i , "ItemComment" );
		$ItemImage = $pUIDB->GetData( $bGM , $i , "ItemImage" );
		$ItemPrice = $pUIDB->GetData( $bGM , $i , "ItemPrice" );
		$ItemTimePrice = $pUIDB->GetData( $bGM , $i , "ItemTimePrice" );
		$ItemSell = $pUIDB->GetData( $bGM , $i , "ItemSell" );
		$ItemType = $pUIDB->GetData( $bGM , $i , "ItemType" );
		$ItemSock = $pUIDB->GetData( $bGM , $i , "ItemSock" );
		$itemreborn = $pUIDB->GetData( $bGM , $i , "itemreborn" );
		
		$strReplace = str_replace( "/" , "" , PATH_UPLOAD_ITEMIMAGE );
		$ItemImage = str_replace( $strReplace , "" , $ItemImage );
		
		$ItemName_Length = strlen($ItemName);
		$ItemName = substr( $ItemName , 0 , $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] );
		if ( $ItemName_Length > $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] )
		$ItemName .= "...";
		
		$ItemComment_Length = strlen( $ItemComment );
		$ItemComment = substr( $ItemComment , 0 , $_CONFIG["SERVERMAN"]["COMMENT_LENGTH"] );
		if ( $ItemComment_Length > $_CONFIG["SERVERMAN"]["COMMENT_LENGTH"] )
		$ItemComment .= "...";
		
		include("noteitem.php");
		if ( $nColume > $nColume_Max )
		{
			$nColume = 0;
			echo "</td>";
			echo "</tr>";
		}else{
			echo "</td>";
		}
	}
}
echo '</TABLE>';
?>