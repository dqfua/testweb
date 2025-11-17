<?php
include( "../global.loader.php" );

$i = 0;
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$cNeoSQLConnectODBC->QueryRanWeb( "SELECT MemberNum FROM MemberInfo WHERE MemBan = 0 AND MemDelete = 0" );
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$Member[ $i ] = $cNeoSQLConnectODBC->Result( "MemberNum" , ODBC_RETYPE_INT );
	$i++;
}
$cNeoSQLConnectODBC->CloseRanWeb();

for( $n = 0 ; $n < $i ; $n++ )
{
	//Clear Session Data
	CInput::GetInstance()->DelValue( CNeoWeb::GetSessionNameOfWebDB( $Member[ $n ] ) , IN_SESSION );
	
	//Clear Instance Object
	__CWebDB::GetInstance()->SetRanGame( NULL , NULL , NULL , NULL );
	__CWebDB::GetInstance()->SetRanShop( NULL , NULL , NULL , NULL );
	__CWebDB::GetInstance()->SetRanUser( NULL , NULL , NULL , NULL );
	__CNeoSQLConnectODBC::GetInstance()->Clear();
	
	$PlayerOnline = CStat::PlayerOnline( $Member[ $n ] );
}

echo "SUCCESS";
?>