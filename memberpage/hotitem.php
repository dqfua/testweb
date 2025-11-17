<?php
CInput::GetInstance()->BuildFrom( IN_POST );

$bNewItemornot = CInput::GetInstance()->GetValueInt( "bNewItemornot" , IN_GET );

$CURRENT_SESSION = sprintf("%d_hotitem" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
phpFastCache::$storage = "auto";

class __TheData
{
	public $ItemNum = 0;
	public $ItemName = "";
	public $ItemImage = "";
	public $ItemPrice = 0;
	public function __construct( $ItemNum , $ItemName , $ItemImage , $ItemPrice )
	{
		$this->ItemNum = $ItemNum;
		$this->ItemName = $ItemName;
		$this->ItemImage = $ItemImage;
		$this->ItemPrice = $ItemPrice;
	}
};

class HotItem
{
	private $pData = array();
	private $nData = 0;
	public function RandGet()
	{
		if ( $this->nData <= 0 ) return NULL;
		$nRand = floor( rand() % $this->nData );
		return $this->pData[ $nRand ];
	}
	public function AddData( $ItemNum , $ItemName , $ItemImage , $ItemPrice )
	{
		$this->pData[ $this->nData ] = new __TheData( $ItemNum , $ItemName , $ItemImage , $ItemPrice );
		$this->nData++;
	}
};

if ( $_CONFIG["SYSTEM"]["SQLITE"] == false )
{
	$ItemNum = 0;
	$ItemName = "";
	$ItemImage = "";
	$ItemPrice = 0;
	
	$pHotItem = unserialize( phpFastCache::get( $CURRENT_SESSION ) );
	if ( !$pHotItem )
	{
		$pHotItem = new HotItem;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = "";
		if ( $bNewItemornot )
			$szTemp = sprintf("SELECT TOP 10 ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemSell,ItemType,ItemSock FROM ItemProject WHERE MemNum = %d AND ItemShow = 0 AND ItemDelete = 0 ORDER BY NEWID()",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
		else
			$szTemp = sprintf("SELECT TOP 10 ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemSell,ItemType,ItemSock FROM ItemProject WHERE MemNum = %d AND ItemShow = 0 AND ItemDelete = 0 ORDER BY ItemSell DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		$ItemNum = 0;
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$ItemNum = $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT);
			$ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
			$ItemImage = $cNeoSQLConnectODBC->Result("ItemImage",ODBC_RETYPE_THAI);
			$ItemPrice = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
			$pHotItem->AddData( $ItemNum , $ItemName , $ItemImage , $ItemPrice );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		if ( $ItemNum == 0 ) exit;
		
		$strReplace = str_replace( "/" , "" , PATH_UPLOAD_ITEMIMAGE );
		$ItemImage = str_replace( $strReplace , "" , $ItemImage );
		
		$ItemName_Length = strlen($ItemName);
		$ItemName = substr( $ItemName , 0 , $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] );
		CGlobal::DisCharHtml( $ItemName );
		if ( $ItemName_Length > $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] )
		$ItemName .= "...";
		phpFastCache::set( $CURRENT_SESSION , serialize( $pHotItem ) , 3600*24 /*1´¡*/ );
	}else{
		$ppData = $pHotItem->RandGet();
		if ( $ppData )
		{
			$ItemNum = $ppData->ItemNum;
			$ItemName = $ppData->ItemName;
			$ItemImage = $ppData->ItemImage;
			$ItemPrice = $ppData->ItemPrice;
		}
	}
?>
<table width="205" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="205" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="27"><img src="../images/right/shop_right_16.jpg" width="27" height="150" /></td>
        <td width="150"><a href="#item" onclick="load_item(<?php echo $ItemNum; ?>);"><img src="<?php echo PATH_UPLOAD_ITEMIMAGE . $ItemImage; ?>" width="150" height="150" border="0" /></a></td>
        <td width="28"><img src="../images/right/shop_right_18.jpg" width="28" height="150" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><img src="../images/right/shop_right_22.jpg" width="205" height="17" /></td>
  </tr>
  <tr>
    <td height="56" valign="bottom" background="../images/right/shop_right_34.jpg"><table width="195" border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td><div align="center" class="style3">
          <p><a href="#item" onclick="load_item(<?php echo $ItemNum; ?>);" target="_self" class="links_link2"><?php echo $ItemName; ?></a></p>
        </div></td>
      </tr>
      <tr>
        <td><div align="center" class="style3">√“§“ : <span class="style4"><?php echo $ItemPrice; ?></span></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
}else{
	$ItemNum = 0;
	$pUIDB = new CUIDB_ItemProject_HotItem( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $bNewItemornot )
	{
		$pUIDB->DumpData();
		$nItem = floor( rand() % $pUIDB->nRow );
		$ItemNum = $pUIDB->pData[ $nItem ][ "ItemNum" ];
		$ItemName = $pUIDB->pData[ $nItem ][ "ItemName" ];
		$ItemImage = $pUIDB->pData[ $nItem ][ "ItemImage" ];
		$ItemPrice = $pUIDB->pData[ $nItem ][ "ItemPrice" ];
	}else{
		$pUIDB->DumpDataTopSell();
		$ItemNum = $pUIDB->pData[ 0 ][ "ItemNum" ];
		$ItemName = $pUIDB->pData[ 0 ][ "ItemName" ];
		$ItemImage = $pUIDB->pData[ 0 ][ "ItemImage" ];
		$ItemPrice = $pUIDB->pData[ 0 ][ "ItemPrice" ];
	}
	if ( $ItemNum == 0 ) exit;
	
	$strReplace = str_replace( "/" , "" , PATH_UPLOAD_ITEMIMAGE );
	$ItemImage = str_replace( $strReplace , "" , $ItemImage );
	
	$ItemName_Length = strlen($ItemName);
	$ItemName = substr( $ItemName , 0 , $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] );
	CGlobal::DisCharHtml( $ItemName );
	if ( $ItemName_Length > $_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] )
	$ItemName .= "...";
?>
<table width="205" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top"><table width="205" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="27"><img src="../images/right/shop_right_16.jpg" width="27" height="150" /></td>
        <td width="150"><a href="#item" onclick="load_item(<?php echo $ItemNum; ?>);"><img src="<?php echo PATH_UPLOAD_ITEMIMAGE . $ItemImage; ?>" width="150" height="150" border="0" /></a></td>
        <td width="28"><img src="../images/right/shop_right_18.jpg" width="28" height="150" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><img src="../images/right/shop_right_22.jpg" width="205" height="17" /></td>
  </tr>
  <tr>
    <td height="56" valign="bottom" background="../images/right/shop_right_34.jpg"><table width="195" border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td><div align="center" class="style3">
          <p><a href="#item" onclick="load_item(<?php echo $ItemNum; ?>);" target="_self" class="links_link2"><?php echo $ItemName; ?></a></p>
        </div></td>
      </tr>
      <tr>
        <td><div align="center" class="style3">√“§“ : <span class="style4"><?php echo $ItemPrice; ?></span></div></td>
      </tr>
    </table></td>
  </tr>
</table>
<?php
}
?>