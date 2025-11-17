<?php
/*
==========================================
			Class ODBC MS SQL
		Development By NeoMasteI2
		Copyright (c) 2010 NeoMasteI2
		Createdate year 2010
		Modifydate year 2011
==========================================
*/
class CNeoSQLConnectODBC
{
	protected $Conn_RanGame = NULL;
	
	protected $Connect = false;
	protected $QuerySQL = false;
	
	protected function ConnectSQL($dbip,$dbuser,$dbpass,$db){
		@$this->Connect = odbc_connect("DRIVER={SQL Server};SERVER=$dbip;DATABASE=$db;UID=$dbuser;PWD=$dbpass;",$dbuser,$dbpass);
		return $this->Connect;
	}
	protected function CloseConnectSQL($connect){
		return odbc_close($connect);
	}
	protected function SQLQuery($query,$connect){
		if ( !$this->Connect ) die("<font color=red><b>มีปัญหาไม่สามารถเชื่อมต่อกับฐานข้อมูล!!</b></font><br>");
		$this->QuerySQL = odbc_exec($connect,$query);
		return $this->QuerySQL;
	}
        protected function Prepare($connect , $query_string){
            return odbc_prepare($connect, $query_string);
        }
	protected function SQL_fetch_row( $id ){return @odbc_fetch_row( $id );}
	protected function SQL_result( $id,$result ){return @odbc_result($id,$result);	}
	public function GetConnect() { return $this->Connect; }
	public function GetQuery( ){return $this->QuerySQL;}
	public function SetQuery( $id ) { $this->QuerySQL = $id; }
        public function NumRows( ){return odbc_num_rows($this->QuerySQL);}
	public function FetchRow( ){return self::SQL_fetch_row( $this->QuerySQL );}
        public function PrepareRanGame($query_string){ return self::Prepare( $this->Conn_RanGame , $query_string ); }
	public function Result( $result ){
		return self::SQL_result( $this->QuerySQL , $result );
	}
	public function Query($query){
		return self::SQLQuery($query,$this->Conn_RanGame);
	}
	public function Close(){
		return self::CloseConnectSQL($this->Conn_RanGame);
	}
	public function Connect( $host , $user , $pass , $db ){
		$connect = self::ConnectSQL($host , $user , $pass , $db);
		$this->Conn_RanGame = $connect;
		if ( !$connect ) return false; else return true;
	}
}
?>