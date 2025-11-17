<?php
class CChaOnline
{
	static public function OnlineCheck( &$serial )
	{
		if ( CGlobal::GetSes( CGlobal::GetSesChaManLogin() ) == ONLINE )
		{
			$serial = CSerial::SerialDec( CGlobal::GetSes( CGlobal::GetSesChaMan() ) );
			/*if ( !$dec_serial )
			{
				$serial = NULL;
				session_destroy();
				return OFFLINE;
			}
			*/
			return ONLINE;
		}
		//session_destroy();
		return OFFLINE;
	}
	static public function OnlineGoodCheck( &$pCha )
	{
		if ( self::OnlineCheck( $pCha ) == ONLINE )
		{
			if ( $pCha != NULL )
			{
				return ONLINE;
			}
		}
		return OFFLINE;
	}
	static public function OnlineSet( $serial )
	{
		CGlobal::SetSes( CGlobal::GetSesChaManLogin() , ONLINE );
		CGlobal::SetSes( CGlobal::GetSesChaMan() , CSerial::SerialEnc($serial) );
		//echo $serial->GetChaName();
	}
}

class COnline
{
	static public function OnlineCheck( &$serial )
	{
		if ( CGlobal::GetSesUserLogin() == ONLINE )
		{
			$serial = CSerial::SerialDec( CGlobal::GetSesUser() );
			/*
			if ( !$dec_serial )
			{
				$serial = NULL;
				session_destroy();
				return OFFLINE;
			}
			*/
			return ONLINE;
		}
		//session_destroy();
		return OFFLINE;
	}
	static public function OnlineGoodCheck( &$cUser )
	{
		if ( self::OnlineCheck( $cUser ) == ONLINE )
		{
			if ( $cUser != NULL )
			{
				return ONLINE;
			}
		}
		return OFFLINE;
	}
	static public function OnlineSet( $serial )
	{
		CGlobal::SetSesUserLogin( ONLINE );
		CGlobal::SetSesUser( CSerial::SerialEnc($serial) );
	}
}
?>