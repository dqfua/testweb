<?php
class CSQLIte
{
	protected $DB;
	protected $Query;
	
	public function Query( $querystring )
	{
		if ( !$this->DB ) return NULL;
		$this->Query = $this->DB->query( $querystring );
		return $this->Query;
	}
	
	public function Clear()
	{
		if ( !$this->DB ) return NULL;
		return $this->DB->clear();
	}
	
	public function FetchRow( )
	{
		if ( !$this->DB ) return NULL;
		if ( !$this->Query ) return NULL;
		return $this->Query->fetchArray( );
	}
	
	function __construct( $fulldbpath )
	{
		$this->DB = new SQLite3( $fulldbpath );
	}
	
	function __destruct(  )
	{
		if ( $this->DB )
		{
			$this->DB->close();
		}
	}
}

?>