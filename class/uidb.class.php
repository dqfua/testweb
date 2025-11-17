<?php
class CUIDB_ItemProject_ItemViewPage
{
	protected $pSqlite = NULL;
	public $pData = array();
	public $nRow = 0;
	public $pDataGM = array();
	public $nRowGM = 0;
	public $nTopPage = array();
	public $nTopPageGM = array();
	
	public function DumpData()
	{
		$szTemp = sprintf( "SELECT PageIndex , SubNum FROM ItemPageNormal" );
		$this->pSqite->Query($szTemp);
		while( $row = $this->pSqite->FetchRow() )
		{
			$this->nTopPage[ $row["SubNum"] ] = $row["PageIndex"];
		}
		
		$szTemp = sprintf( "SELECT PageIndex , SubNum FROM ItemPageGM" );
		$this->pSqite->Query($szTemp);
		while( $row = $this->pSqite->FetchRow() )
		{
			$this->nTopPageGM[ $row["SubNum"] ] = $row["PageIndex"];
		}
		
		$szTemp = sprintf("SELECT ItemNum , SubNum , ItemMain , ItemSub , ItemName , ItemComment , ItemImage , ItemPrice , ItemSell , ItemType , ItemSock , Item_MaxReborn , ItemPage FROM ItemProjectGM");
		$this->pSqite->Query($szTemp);
		for ( $i = 0 ; $row = $this->pSqite->FetchRow() ; $i++ )
		{
			$this->pDataGM[ $i ][ "ItemNum" ] = $row["ItemNum"];
			$this->pDataGM[ $i ][ "SubNum" ] = $row["SubNum"];
			$this->pDataGM[ $i ][ "ItemMain" ] = $row["ItemMain"];
			$this->pDataGM[ $i ][ "ItemSub" ] = $row["ItemSub"];
			$this->pDataGM[ $i ][ "ItemName" ] = $row["ItemName"];
			$this->pDataGM[ $i ][ "ItemComment" ] = $row["ItemComment"];
			$this->pDataGM[ $i ][ "ItemImage" ] = $row["ItemImage"];
			$this->pDataGM[ $i ][ "ItemPrice" ] = $row["ItemPrice"];
			$this->pDataGM[ $i ][ "ItemSell" ] = $row["ItemSell"];
			$this->pDataGM[ $i ][ "ItemType" ] = $row["ItemType"];
		}
		$this->nRowGM = $i;
		
		$szTemp = sprintf("SELECT ItemNum , SubNum , ItemMain , ItemSub , ItemName , ItemComment , ItemImage , ItemPrice , ItemSell , ItemType , ItemSock , Item_MaxReborn , ItemPage FROM ItemProject");
		$this->pSqite->Query($szTemp);
		for ( $i = 0 ; $row = $this->pSqite->FetchRow() ; $i++ )
		{
			$this->pData[ $i ][ "ItemNum" ] = $row["ItemNum"];
			$this->pData[ $i ][ "SubNum" ] = $row["SubNum"];
			$this->pData[ $i ][ "ItemMain" ] = $row["ItemMain"];
			$this->pData[ $i ][ "ItemSub" ] = $row["ItemSub"];
			$this->pData[ $i ][ "ItemName" ] = $row["ItemName"];
			$this->pData[ $i ][ "ItemComment" ] = $row["ItemComment"];
			$this->pData[ $i ][ "ItemImage" ] = $row["ItemImage"];
			$this->pData[ $i ][ "ItemPrice" ] = $row["ItemPrice"];
			$this->pData[ $i ][ "ItemSell" ] = $row["ItemSell"];
			$this->pData[ $i ][ "ItemType" ] = $row["ItemType"];
		}
		$this->nRow = $i;
	}
	
	public function InsertPageNormal( $PageIndex , $SubNum )
	{
		$szTemp = sprintf( "INSERT INTO ItemPageNormal( PageIndex , SubNum ) VALUES( %d , %d )" , $PageIndex , $SubNum );
		$this->pSqite->Query($szTemp);
	}
	
	public function InsertPageGM( $PageIndex , $SubNum )
	{
		$szTemp = sprintf( "INSERT INTO ItemPageGM( PageIndex , SubNum ) VALUES( %d , %d )" , $PageIndex , $SubNum );
		$this->pSqite->Query($szTemp);
	}
	
	public function Insert(
		$ItemNum
		, $SubNum
		, $ItemMain
		, $ItemSub
		, $ItemName
		, $ItemComment
		, $ItemImage
		, $ItemPrice
		, $ItemSell
		, $ItemType
		, $ItemSock
		, $Item_MaxReborn
		, $ItemPage
	)
	{
		$szTemp = sprintf( "
		INSERT INTO ItemProject
		( ItemNum , SubNum , ItemMain , ItemSub , ItemName , ItemComment , ItemImage , ItemPrice , ItemSell , ItemType , ItemSock , Item_MaxReborn , ItemPage )
		VALUES
		( %d , %d , %d , %d , '%s', '%s', '%s' , %d , %d , %d , %d , %d , %d )
		"
		, $ItemNum
		, $SubNum
		, $ItemMain
		, $ItemSub
		, $ItemName
		, $ItemComment
		, $ItemImage
		, $ItemPrice
		, $ItemSell
		, $ItemType
		, $ItemSock
		, $Item_MaxReborn
		, $ItemPage
		);
		$this->pSqite->Query($szTemp);
	}
	
	public function InsertGM(
		$ItemNum
		, $SubNum
		, $ItemMain
		, $ItemSub
		, $ItemName
		, $ItemComment
		, $ItemImage
		, $ItemPrice
		, $ItemSell
		, $ItemType
		, $ItemSock
		, $Item_MaxReborn
		, $ItemPage
	)
	{
		$szTemp = sprintf( "
		INSERT INTO ItemProjectGM
		( ItemNum , SubNum , ItemMain , ItemSub , ItemName , ItemComment , ItemImage , ItemPrice , ItemSell , ItemType , ItemSock , Item_MaxReborn , ItemPage )
		VALUES
		( %d , %d , %d , %d , '%s', '%s', '%s' , %d , %d , %d , %d , %d , %d )
		"
		, $ItemNum
		, $SubNum
		, $ItemMain
		, $ItemSub
		, $ItemName
		, $ItemComment
		, $ItemImage
		, $ItemPrice
		, $ItemSell
		, $ItemType
		, $ItemSock
		, $Item_MaxReborn
		, $ItemPage
		);
		$this->pSqite->Query($szTemp);
	}
	
	public function CreateTable()
	{
		$this->pSqite->Query(
		"
		CREATE TABLE 
		ItemProject
		(
			ItemNum INTEGER PRIMARY KEY DESC
			,SubNum INTEGER
			,ItemMain
			,ItemSub
			,ItemName varchar( 100 )
			,ItemComment varchar(8000)
			,ItemImage varchar(256)
			,ItemPrice INTEGER
			,ItemSell
			,ItemType
			,ItemSock
			,Item_MaxReborn
			,ItemPage
		)
		"
		);
		$this->pSqite->Query(
		"
		CREATE TABLE 
		ItemProjectGM
		(
			ItemNum INTEGER PRIMARY KEY DESC
			,SubNum INTEGER
			,ItemMain
			,ItemSub
			,ItemName varchar( 100 )
			,ItemComment varchar(8000)
			,ItemImage varchar(256)
			,ItemPrice INTEGER
			,ItemSell
			,ItemType
			,ItemSock
			,Item_MaxReborn
			,ItemPage
		)
		"
		);
		$this->pSqite->Query(
		"
		CREATE TABLE 
		ItemPageNormal
		(
			PageIndex INTEGER
			,SubNum
		)
		"
		);
		$this->pSqite->Query(
		"
		CREATE TABLE 
		ItemPageGM
		(
			PageIndex INTEGER
			,SubNum
		)
		"
		);
	}
	
	function __construct( $memnum , $bDel = false )
	{
		$filepathdb = sprintf( "%sitemproject_itemviewpage_%04d.sqlite" , PATH_UPLOAD_CATCH , $memnum );
		
		if ( $bDel )
		{
			unlink( $filepathdb );
		}
		
		$this->pSqite = new CSQLIte( $filepathdb );
	}
}

class CUIDB_ItemProject_HotItem
{
	protected $pSqlite = NULL;
	public $pData = array();
	public $nRow = 0;
	
	public function DumpDataTopSell()
	{
		$this->pSqite->Query("SELECT ItemNum , ItemName , ItemImage ,ItemPrice FROM ItemProject ORDER BY ItemSell DESC");
		for ( $i = 0 ; $row = $this->pSqite->FetchRow() ; $i++ )
		{
			$this->pData[ $i ][ "ItemNum" ] = $row["ItemNum"];
			$this->pData[ $i ][ "ItemName" ] = $row["ItemName"];
			$this->pData[ $i ][ "ItemImage" ] = $row["ItemImage"];
			$this->pData[ $i ][ "ItemPrice" ] = $row["ItemPrice"];
		}
		$this->nRow = $i;
	}
	
	public function DumpData()
	{
		$this->pSqite->Query("SELECT ItemNum , ItemName , ItemImage ,ItemPrice FROM ItemProject");
		for ( $i = 0 ; $row = $this->pSqite->FetchRow() ; $i++ )
		{
			$this->pData[ $i ][ "ItemNum" ] = $row["ItemNum"];
			$this->pData[ $i ][ "ItemName" ] = $row["ItemName"];
			$this->pData[ $i ][ "ItemImage" ] = $row["ItemImage"];
			$this->pData[ $i ][ "ItemPrice" ] = $row["ItemPrice"];
		}
		$this->nRow = $i;
	}
	
	public function Insert( $ItemNum , $ItemName , $ItemImage , $ItemPrice , $ItemSell )
	{
		$szTemp = sprintf( "
		INSERT INTO ItemProject( ItemNum , ItemName , ItemImage ,ItemPrice , ItemSell )
		VALUES( %d , '%s' , '%s' , %d , %d )
		"
		, $ItemNum , $ItemName , $ItemImage , $ItemPrice , $ItemSell );
		return $this->pSqite->Query($szTemp);
	}
	
	public function CreateTable()
	{
		$this->pSqite->Query(
		"
		CREATE TABLE 
		ItemProject
		(
			ItemNum INTEGER PRIMARY KEY DESC
			,ItemName varchar(256)
			,ItemImage varchar(256)
			,ItemPrice INTEGER
			,ItemSell
		)
		"
		);
	}
	
	function __construct( $memnum , $bDel = false )
	{
		$filepathdb = sprintf( "%sitemproject_hotitem_%04d.sqlite" , PATH_UPLOAD_CATCH , $memnum );
		
		if ( $bDel )
		{
			unlink( $filepathdb );
		}
		
		$this->pSqite = new CSQLIte( $filepathdb );
	}
}

class CUIDB_ItemProject_HotProject
{
	protected $pSqlite = NULL;
	protected $pData = array();
	protected $nRow = 0;
	protected $pDataGM = array();
	protected $nRowGM = 0;
	
	public function GetRow(){ return $this->nRow; }
	public function GetRowGM(){ return $this->nRowGM; }
	
	public function GetData( $bGM , $index , $colum )
	{
		$pData = $this->pData;
		if ( $bGM )
		{
			$pData = $this->pDataGM;
		}
		return $pData[ $index ][ $colum ];
	}
	
	public function DumpData()
	{
		$szTemp = sprintf( "
		SELECT ItemNum
		 , SubNum
		 , ItemMain 
		 , ItemSub 
		 , ItemName 
		 , ItemComment 
		 , ItemImage 
		 , ItemPrice 
		 , ItemSell 
		 , ItemType 
		 , ItemSock 
		 , ItemReborn FROM ItemProject ORDER BY ItemNum DESC
		" );
		$this->pSqite->Query( $szTemp );
		for ( $i = 0 ; $row = $this->pSqite->FetchRow() ; $i++ )
		{
			$this->pData[ $i ][ "ItemNum" ] = $row["ItemNum"];
			$this->pData[ $i ][ "SubNum" ] = $row["SubNum"];
			$this->pData[ $i ][ "ItemMain" ] = $row["ItemMain"];
			$this->pData[ $i ][ "ItemSub" ] = $row["ItemSub"];
			$this->pData[ $i ][ "ItemName" ] = $row["ItemName"];
			$this->pData[ $i ][ "ItemComment" ] = $row["ItemComment"];
			$this->pData[ $i ][ "ItemImage" ] = $row["ItemImage"];
			$this->pData[ $i ][ "ItemPrice" ] = $row["ItemPrice"];
			$this->pData[ $i ][ "ItemSell" ] = $row["ItemSell"];
			$this->pData[ $i ][ "ItemType" ] = $row["ItemType"];
			$this->pData[ $i ][ "ItemSock" ] = $row["ItemSock"];
			$this->pData[ $i ][ "ItemReborn" ] = $row["ItemReborn"];
		}
		$this->nRow = $i;
		
		//gm
		$szTemp = sprintf( "
		SELECT ItemNum
		 , SubNum
		 , ItemMain 
		 , ItemSub 
		 , ItemName 
		 , ItemComment 
		 , ItemImage 
		 , ItemPrice 
		 , ItemSell 
		 , ItemType 
		 , ItemSock 
		 , ItemReborn FROM ItemProjectGM ORDER BY ItemNum DESC
		" );
		$this->pSqite->Query( $szTemp );
		for ( $i = 0 ; $row = $this->pSqite->FetchRow() ; $i++ )
		{
			$this->pDataGM[ $i ][ "ItemNum" ] = $row["ItemNum"];
			$this->pDataGM[ $i ][ "SubNum" ] = $row["SubNum"];
			$this->pDataGM[ $i ][ "ItemMain" ] = $row["ItemMain"];
			$this->pDataGM[ $i ][ "ItemSub" ] = $row["ItemSub"];
			$this->pDataGM[ $i ][ "ItemName" ] = $row["ItemName"];
			$this->pDataGM[ $i ][ "ItemComment" ] = $row["ItemComment"];
			$this->pDataGM[ $i ][ "ItemImage" ] = $row["ItemImage"];
			$this->pDataGM[ $i ][ "ItemPrice" ] = $row["ItemPrice"];
			$this->pDataGM[ $i ][ "ItemSell" ] = $row["ItemSell"];
			$this->pDataGM[ $i ][ "ItemType" ] = $row["ItemType"];
			$this->pDataGM[ $i ][ "ItemSock" ] = $row["ItemSock"];
			$this->pDataGM[ $i ][ "ItemReborn" ] = $row["ItemReborn"];
		}
		$this->nRowGM = $i;
	}
	
	public function Insert(
		$ItemNum
		, $SubNum
		, $ItemMain
		, $ItemSub
		, $ItemName
		, $ItemComment
		, $ItemImage
		, $ItemPrice
		, $ItemSell
		, $ItemType
		, $ItemSock
		, $ItemReborn
	)
	{
		$szTemp = sprintf("
		INSERT INTO 
		ItemProject
		( ItemNum
		 , SubNum
		 , ItemMain 
		 , ItemSub 
		 , ItemName 
		 , ItemComment 
		 , ItemImage 
		 , ItemPrice 
		 , ItemSell 
		 , ItemType 
		 , ItemSock 
		 , ItemReborn  )
		VALUES( %d , %d , %d , %d , '%s' , '%s' , '%s' , %d , %d , %d , %d , %d )
		"
		, $ItemNum
		, $SubNum
		, $ItemMain
		, $ItemSub
		, $ItemName
		, $ItemComment
		, $ItemImage
		, $ItemPrice
		, $ItemSell
		, $ItemType
		, $ItemSock
		, $ItemReborn
		);
		return $this->pSqite->Query( $szTemp );
	}
	
	public function InsertGM(
		$ItemNum
		, $SubNum
		, $ItemMain
		, $ItemSub
		, $ItemName
		, $ItemComment
		, $ItemImage
		, $ItemPrice
		, $ItemSell
		, $ItemType
		, $ItemSock
		, $ItemReborn
	)
	{
		$szTemp = sprintf("
		INSERT INTO 
		ItemProjectGM
		( ItemNum
		 , SubNum
		 , ItemMain 
		 , ItemSub 
		 , ItemName 
		 , ItemComment 
		 , ItemImage 
		 , ItemPrice 
		 , ItemSell 
		 , ItemType 
		 , ItemSock 
		 , ItemReborn  )
		VALUES( %d , %d , %d , %d , '%s' , '%s' , '%s' , %d , %d , %d , %d , %d )
		"
		, $ItemNum
		, $SubNum
		, $ItemMain
		, $ItemSub
		, $ItemName
		, $ItemComment
		, $ItemImage
		, $ItemPrice
		, $ItemSell
		, $ItemType
		, $ItemSock
		, $ItemReborn
		);
		return $this->pSqite->Query( $szTemp );
	}
	
	public function CreateTable()
	{
		$this->pSqite->Query(
		"
		CREATE TABLE 
		ItemProject
		(
			ItemNum INTEGER PRIMARY KEY DESC
			,SubNum
			,ItemMain
			,ItemSub
			,ItemName varchar(256)
			,ItemComment varchar(999)
			,ItemImage varchar(256)
			,ItemPrice INTEGER
			,ItemSell
			,ItemType
			,ItemSock
			,ItemReborn
		)
		"
		);
		$this->pSqite->Query(
		"
		CREATE TABLE 
		ItemProjectGM
		(
			ItemNum INTEGER PRIMARY KEY DESC
			,SubNum
			,ItemMain
			,ItemSub
			,ItemName varchar(256)
			,ItemComment varchar(999)
			,ItemImage varchar(256)
			,ItemPrice INTEGER
			,ItemSell
			,ItemType
			,ItemSock
			,ItemReborn
		)
		"
		);
	}
	
	function __construct( $memnum , $bDel = false )
	{
		$filepathdb = sprintf( "%sitemproject_hotproject_%04d.sqlite" , PATH_UPLOAD_CATCH , $memnum );
		
		if ( $bDel )
		{
			unlink( $filepathdb );
		}
		
		$this->pSqite = new CSQLIte( $filepathdb );
	}
}

class CUIDB_SubProject
{
	protected $pSqlite = NULL;
	protected $pData = array();
	protected $pDataGM = array();
	protected $nRow = 0;
	protected $nRowGM = 0;
	
	public function GetRow(){ return $this->nRow; }
	public function GetRowGM(){ return $this->nRowGM; }
	public function GetSubNum( $index ) { return $this->pData[ $index ][ "SubNum" ]; }
	public function GetSubTitle( $index ) { return $this->pData[ $index ][ "SubTitle" ]; }
	public function GetSubNumGM( $index ) { return $this->pDataGM[ $index ][ "SubNum" ]; }
	public function GetSubTitleGM( $index ) { return $this->pDataGM[ $index ][ "SubTitle" ]; }
	
	public function Insert( $SubNum , $SubTitle )
	{
		$szTemp = sprintf( "INSERT INTO SubProject( SubNum , SubTitle ) VALUES( %d , '%s' )"
		, $SubNum
		, $SubTitle
		);
		$nReturn = $this->pSqite->Query( $szTemp );
		return $nReturn;
	}
	
	public function InsertGM( $SubNum , $SubTitle )
	{
		$szTemp = sprintf( "INSERT INTO SubProjectGM( SubNum , SubTitle ) VALUES( %d , '%s' )"
		, $SubNum
		, $SubTitle
		);
		$nReturn = $this->pSqite->Query( $szTemp );
		return $nReturn;
	}
	
	public function Clear()
	{
		return $this->pSqite->Query( "TRUNCATE TABLE SubProject" );
	}
	
	public function DumpData()
	{
		$szTemp = sprintf( "SELECT SubNum , SubTitle FROM SubProject" );
		$this->pSqite->Query( $szTemp );
		for ( $i = 0 ; $row = $this->pSqite->FetchRow() ; $i++ )
		{
			$this->pData[ $i ][ "SubNum" ] = $row["SubNum"];
			$this->pData[ $i ][ "SubTitle" ] = $row["SubTitle"];
		}
		$this->nRow = $i;
		
		//gm data
		$szTemp = sprintf( "SELECT SubNum , SubTitle FROM SubProjectGM" );
		$this->pSqite->Query( $szTemp );
		for ( $i = 0 ; $row = $this->pSqite->FetchRow() ; $i++ )
		{
			$this->pDataGM[ $i ][ "SubNum" ] = $row["SubNum"];
			$this->pDataGM[ $i ][ "SubTitle" ] = $row["SubTitle"];
		}
		$this->nRowGM = $i;
	}
	
	public function CreateTable()
	{
		$this->pSqite->Query("
		DROP TABLE IF EXISTS SubProject;
		DROP TABLE IF EXISTS SubProjectGM;
		");
		
		$this->pSqite->Query(
		"
		CREATE TABLE 
		SubProject
		(
			SubNum INTEGER PRIMARY KEY ASC
			,SubTitle varchar( 256 )
		)
		"
		);
		$this->pSqite->Query(
		"
		CREATE TABLE 
		SubProjectGM
		(
			SubNum INTEGER PRIMARY KEY ASC
			,SubTitle varchar( 256 )
		)
		"
		);
	}
	
	function __construct( $memnum , $bDel = false )
	{
		$filepathdb = sprintf( "%ssubproject_%04d.sqlite" , PATH_UPLOAD_CATCH , $memnum );
		if ( $bDel )
		{
			unlink( $filepathdb );
		}
		$this->pSqite = new CSQLIte( $filepathdb );
	}
}
?>