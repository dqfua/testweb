<?php
$szTemp = sprintf("SELECT SubNum,SubName,SubShow FROM SubProject WHERE MemNum = %d AND ( SubShow = 0 OR SubShow = 2 ) And SubDel = 0 ORDER BY SubName ASC , SubNum DESC",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
$bGM = FALSE;
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) == ONLINE )
{
	if ( $cUser->GetUserType() >= 30 )
	{
		$bGM = TRUE;
	}
}

phpFastCache::$storage = "auto";

$CURRENT_SESSION = sprintf( "%d_themenudata" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

class __Data
{
	public $SubNum;
	public $SubName;
	public $SubShow;

	public function __construct( $SubNum , $SubName , $SubShow )
	{
		$this->SubNum = $SubNum;
		$this->SubName = $SubName;
		$this->SubShow = $SubShow;
	}
};

class MenuData
{
	private $pData = array();
	private $nData = 0;
	public function GetRoll(){return $this->nData; }
	public function GetData( $index ) { return $this->pData[ $index ]; }
	public function AddData( $SubNum , $SubName , $SubShow )
	{
		$this->pData[ $this->nData ] = new __Data( $SubNum , $SubName , $SubShow );
		$this->nData++;
	}
};

?>
<table width="180" border="0" cellpadding="0" cellspacing="0">
<TR>
<TD><a href="#see" onclick="load_newitem();"><font size='2'><b>หน้าหลัก</b></font></a></TD>
</TR>
<?php
if ( $_CONFIG["SYSTEM"]["SQLITE"] == false )
{
	$pMenuData = unserialize( phpFastCache::get( $CURRENT_SESSION ) );
	if ( !$pMenuData )
	{
		$pMenuData = new MenuData;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
			$SubName = $cNeoSQLConnectODBC->Result("SubName",ODBC_RETYPE_THAI);
			$SubShow = $cNeoSQLConnectODBC->Result("SubShow",ODBC_RETYPE_INT);
			$pMenuData->AddData( $SubNum , $SubName , $SubShow );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		
		phpFastCache::set( $CURRENT_SESSION , serialize( $pMenuData ) , 300+floor( rand()%300 ) );
	}
	
	for( $i = 0 ; $i < $pMenuData->GetRoll() ; $i++  )
	{
		$ppData = $pMenuData->GetData( $i );
		
		$SubNum = $ppData->SubNum;
		$SubName = $ppData->SubName;
		$SubShow = $ppData->SubShow;
		
		if ( $SubShow == 2 && !$bGM ) continue;
		
		printf( "<TR height='24'><TD><a href='#see' onclick=\"loadpage('seeitem','area','n=%d');\"><font size='2'><b>%s</b></font></a></TR></TD>" , $SubNum , $SubName );
	}
}else{
	$pUIDB = new CUIDB_SubProject( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$pUIDB->DumpData();
	
	if ( !$bGM )
	{
		for( $i = 0 ; $i < $pUIDB->GetRow() ; $i++ )
		{
			printf( "<TR height='24'><TD><a href='#see' onclick=\"loadpage('seeitem','area','n=%d');\"><font size='2'><b>%s</b></font></a></TR></TD>" , $pUIDB->GetSubNum( $i ) , $pUIDB->GetSubTitle( $i ) );
		}
	}else{
		for( $i = 0 ; $i < $pUIDB->GetRowGM() ; $i++ )
		{
			printf( "<TR height='24'><TD><a href='#see' onclick=\"loadpage('seeitem','area','n=%d');\"><font size='2'><b>%s</b></font></a></TR></TD>" , $pUIDB->GetSubNumGM( $i ) , $pUIDB->GetSubTitleGM( $i ) );
		}
	}
}
?>
</table>