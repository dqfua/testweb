<?php
class CDebugLog
{
	static public function Write( $stringdata )
	{
		$pFile = fopen( "debug.log" , "at" );
		fprintf( $pFile , $stringdata . "\n" );
		printf( $stringdata . "<br>\n" );
		fclose( $pFile );
	}
        static public function WriteC( $data )
	{
		$pFile = fopen( "transback_chanum.txt" , "at" );
		fprintf( $pFile , sprintf( "delete chainfo where usernum = %d\n" , $data ) );
		fclose( $pFile );
	}
	static public function WriteD( $data )
	{
		$pFile = fopen( "transback_userid.txt" , "at" );
		fprintf( $pFile , sprintf( "delete shoppurchase where useruid = '%s'\n" , $data ) );
		fclose( $pFile );
	}
	static public function WriteN( $data )
	{
		$pFile = fopen( "transback_usernum.txt" , "at" );
		fprintf( $pFile , sprintf( "delete userinfo where usernum = %d\n" , $data ) );
		fclose( $pFile );
	}
}
?>