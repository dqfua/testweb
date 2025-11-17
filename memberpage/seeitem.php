<?php
$cUser = NULL;
$bUserLoginCheck = false;
$UType = 0;

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

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
	}
}

if ( $_CONFIG["SYSTEM"]["SQLITE"] == false )
{
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
		public $ItemBonusPointPrice;
		public $ItemType;
		public $ItemSock;
		public $itemreborn;
		public function __construct( $ItemNum , $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $ItemPrice , $ItemTimePrice , $ItemBonusPointPrice , $ItemType , $ItemSock , $itemreborn )
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
			$this->ItemBonusPointPrice = $ItemBonusPointPrice;
			$this->ItemType = $ItemType;
			$this->ItemSock = $ItemSock;
			$this->itemreborn = $itemreborn;
		}
	};
	class ItemData
	{
		private $pData = array();
		private $nData = 0;
		public $Count = 0;
		public function GetRoll(){ return $this->nData; }
		public function GetData( $index ) { return $this->pData[ $index ]; }
		public function AddData( $ItemNum , $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $ItemPrice , $ItemTimePrice , $ItemBonusPointPrice , $ItemType , $ItemSock , $itemreborn )
		{
			$this->pData[ $this->nData ] = new __ItemData( $ItemNum , $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $ItemPrice , $ItemTimePrice , $ItemBonusPointPrice , $ItemType , $ItemSock , $itemreborn );
			$this->nData++;
		}
	};
	
	$n = CInput::GetInstance()->GetValueInt( "n" , IN_POST );
	$l = CInput::GetInstance()->GetValueInt( "l" , IN_GET );
	
	$CURRENT_SESSION = sprintf( "%d_%d_%d_newitem" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UType , $n );
	$pItemData = unserialize( phpFastCache::get( $CURRENT_SESSION ) );
	if ( !$pItemData )
	{
		$pItemData = new ItemData;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		if ( $bUserLoginCheck )
			$szTemp = sprintf("SELECT COUNT( ItemNum ) As Count FROM ItemProject WHERE MemNum = %d AND ( ItemShow = 0 OR ItemShow = 2 ) AND ItemDelete = 0 AND SubNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$n);
		else
			$szTemp = sprintf("SELECT COUNT( ItemNum ) As Count FROM ItemProject WHERE MemNum = %d AND ItemShow = 0 AND ItemDelete = 0 AND SubNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$n);
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		$pItemData->Count = $cNeoSQLConnectODBC->Result("Count",ODBC_RETYPE_INT);
		
		if ( $bUserLoginCheck )
			$szTemp = sprintf("SELECT ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemTimePrice,ItemBonusPointPrice,ItemSell,ItemType,ItemSock,Item_MaxReborn FROM ItemProject WHERE MemNum = %d AND ( ItemShow = 0 OR ItemShow = 2 ) AND ItemDelete = 0 AND SubNum = %d ORDER BY ItemNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$n);
		else
			$szTemp = sprintf("SELECT ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemTimePrice,ItemBonusPointPrice,ItemSell,ItemType,ItemSock,Item_MaxReborn FROM ItemProject WHERE MemNum = %d AND ItemShow = 0 AND ItemDelete = 0 AND SubNum = %d ORDER BY ItemNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$n);
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
			$ItemBonusPointPrice = $cNeoSQLConnectODBC->Result("ItemBonusPointPrice",ODBC_RETYPE_INT);
			$ItemSell = $cNeoSQLConnectODBC->Result("ItemSell",ODBC_RETYPE_INT);
			$ItemType = $cNeoSQLConnectODBC->Result("ItemType",ODBC_RETYPE_INT);
			$ItemSock = $cNeoSQLConnectODBC->Result("ItemSock",ODBC_RETYPE_INT);
			$itemreborn = $cNeoSQLConnectODBC->Result("Item_MaxReborn",ODBC_RETYPE_INT);
			
			$strReplace = str_replace( "/" , "" , PATH_UPLOAD_ITEMIMAGE );
			$ItemImage = str_replace( $strReplace , "" , $ItemImage );
			
			$ItemName_Length = strlen($ItemName);
			$ItemName = substr( $ItemName , 0 , $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] );
			// แก้ไขบรรทัด 116 - เพิ่มการรับค่า return
			$ItemName = CGlobal::DisCharHtml( $ItemName );
			if ( $ItemName_Length > $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] )
			$ItemName .= "...";
			
			$ItemComment_Length = strlen( $ItemComment );
			$ItemComment = substr( $ItemComment , 0 , $_CONFIG["SERVERMAN"]["COMMENT_LENGTH"] );
			// แก้ไขบรรทัด 122 - เพิ่มการรับค่า return
			$ItemComment = CGlobal::DisCharHtml( $ItemComment );
			if ( $ItemComment_Length > $_CONFIG["SERVERMAN"]["COMMENT_LENGTH"] )
			$ItemComment .= "...";
			
			$pItemData->AddData( $ItemNum , $SubNum , $ItemMain , $ItemSub , $ItemName , $ItemComment , $ItemImage , $ItemPrice , $ItemTimePrice , $ItemBonusPointPrice , $ItemType , $ItemSock , $itemreborn );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		
		phpFastCache::set( $CURRENT_SESSION , serialize( $pItemData ) , 300+floor( rand()%300 ) );
	}
	
	$Count = $pItemData->Count;
	$i_max = floor($Count/$_CONFIG["SERVERMAN"]["SHOWITEM"]);
	
	echo "<div align='center'><TABLE BORDER=0 CELLSPACEING=5 CELLPADDING=5><TR>";
	for( $i = 0 ; $i <= $i_max ; $i++ )
	{
		$NextLine = false;
		if ( ( ( $i-1 ) % 20 ) == 19 ) $NextLine = true;
		
		if ( $NextLine ) echo "</tr><tr>";
		
		if ( $l == $i )
		echo "<TD><font size=+2 color=red><b>".($i+1)."</b></font></TD>";
		else
		echo "<TD><a href='#see' onclick=\"loadpage('seeitem&l=$i','area','n=$n');\"><font size=+1>".($i+1)."</font></a></TD>";
		
		//if ( $NextLine ) echo "</tr>";
	}
	echo "</TR></TABLE></div>";
	?>
	<TABLE border="0" cellpadding="3" cellspacing="3" align="left">
	<tr>
	<td>
	
	</td>
	</tr>
	<?php
	$nColume = 5;
	$nColume_Max = 3;
	$i = 0;
	while( $i < $pItemData->GetRoll() )
	{
		$i++;
		if ( $i < ( $l*$_CONFIG["SERVERMAN"]["SHOWITEM"] )+1 ) continue;
		if ( $i > (( $l*$_CONFIG["SERVERMAN"]["SHOWITEM"])+$_CONFIG["SERVERMAN"]["SHOWITEM"]) ) break;
		$nColume++;
		if ( $nColume > $nColume_Max )
		{
			$nColume = 0;
			echo "<tr>";
			echo "<td valign=top>";
		}else{
			echo "<td valign=top>";
		}
		
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
		$ItemBonusPointPrice = $ppData->ItemBonusPointPrice;
		$ItemSell = isset($ppData->ItemSell) ? $ppData->ItemSell : 0;
		$ItemType = $ppData->ItemType;
		$ItemSock = $ppData->ItemSock;
		$itemreborn = $ppData->itemreborn;
		
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
	echo '</TABLE>';
}else{
	$pUIDB = new CUIDB_ItemProject_ItemViewPage( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , FALSE );
	$pUIDB->DumpData();
	
	$l = CInput::GetInstance()->GetValueInt( "l" , IN_GET ); // page number
	$s = CInput::GetInstance()->GetValueInt( "n" , IN_POST ); // sub project number
	
	$nTopPage = $pUIDB->nTopPage[ $s ];
	$nRow = $pUIDB->nRow;
	$pData = $pUIDB->pData;
	if ( $bUserLoginCheck )
	{
		$nTopPage = $pUIDB->nTopPageGM[ $s ];
		$nRow = $pUIDB->nRowGM;
		$pData = $pUIDB->pDataGM;
	}
	
	echo "<div align='center'><TABLE BORDER=0 CELLSPACEING=5 CELLPADDING=5><TR>";
	for( $i = 0 ; $i <= $nTopPage ; $i++ )
	{
		if ( $l == $i )
		echo "<TD><font size=+2 color=red><b>".($i+1)."</b></font></TD>";
		else
		echo "<TD><a href='#see' onclick=\"loadpage('seeitem&l=$i','area','n=$s');\"><font size=+1>".($i+1)."</font></a></TD>";
	}
	echo "</TR></TABLE></div>";
	
	echo '
	<TABLE border="0" cellpadding="3" cellspacing="3" align="left">
	<tr>
	<td>
	
	</td>
	</tr>
	';
	$nColume = 5;
	$nColume_Max = 3;
	$ccl = 0;
	for( $i = 0 ; $i < $nRow ; $i++ )
	{
		if ( $pData[ $i ][ "SubNum" ] != $s ) continue;
		$ccl++;
		if ( $ccl < ( $l*$_CONFIG["SERVERMAN"]["SHOWITEM"] )+1 ) continue;
		if ( $ccl > (( $l*$_CONFIG["SERVERMAN"]["SHOWITEM"])+$_CONFIG["SERVERMAN"]["SHOWITEM"]) ) break;
		$nColume++;
		if ( $nColume > $nColume_Max )
		{
			$nColume = 0;
			echo "<tr>";
			echo "<td valign=top>";
		}else{
			echo "<td valign=top>";
		}
		$ItemNum = $pData[ $i ][ "ItemNum" ];
		$SubNum = $pData[ $i ][ "SubNum" ];
		$ItemMain = $pData[ $i ][ "ItemMain" ];
		$ItemSub = $pData[ $i ][ "ItemSub" ];
		$ItemName = $pData[ $i ][ "ItemName" ];
		$ItemComment = $pData[ $i ][ "ItemComment" ];
		$ItemImage = $pData[ $i ][ "ItemImage" ];
		$ItemPrice = $pData[ $i ][ "ItemPrice" ];
		$ItemTimePrice = $pData[ $i ][ "ItemTimePrice" ];
		$ItemBonusPointPrice = $pData[ $i ][ "ItemBonusPointPrice" ];
		$ItemSell = isset($pData[ $i ][ "ItemSell" ]) ? $pData[ $i ][ "ItemSell" ] : 0;
		$ItemType = $pData[ $i ][ "ItemType" ];
		$ItemSock = $pData[ $i ][ "ItemSock" ];
		$itemreborn = $pData[ $i ][ "Item_MaxReborn" ];
		
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
	
	echo "</table>";
	
	echo "<div align='center'><TABLE BORDER=0 CELLSPACEING=5 CELLPADDING=5><TR>";
	for( $i = 0 ; $i <= $nTopPage ; $i++ )
	{
		if ( $l == $i )
		echo "<TD><font size=+2 color=red><b>".($i+1)."</b></font></TD>";
		else
		echo "<TD><a href='#see' onclick=\"loadpage('seeitem&l=$i','area','n=$s');\"><font size=+1>".($i+1)."</font></a></TD>";
	}
	echo "</TR></TABLE></div>";
}
?>