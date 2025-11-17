<?php
/*
Class Tmpay
Development By NeoMasteI2
*/
define("TMPAY_RELEASED",false);
define("TMPAY_BLOCKCARD_SQLQUERY_COMMAND","SerialTruemoney != '55555555555551' AND SerialTruemoney != '55555555555552' AND SerialTruemoney != '55555555555553' AND SerialTruemoney != '55555555555554' AND SerialTruemoney != '55555555555555' ");
class TmPay
{
	protected $bVar = false;
	protected $szTemp = "";
	protected $nRand = 0;
	
	public $nCardRank = array();
	public $nRePointCardRank = array();
	public $nBonusPointCardRank = array();
	public $merchant_id = "";
	public $nPercent = 0;
        public $MyService = 0;
	
	public function GetCard_0(){ return $this->nCardRank[0]; }
	public function GetCard_50(){ return $this->nCardRank[1]; }
	public function GetCard_90(){ return $this->nCardRank[2]; }
	public function GetCard_150(){ return $this->nCardRank[3]; }
	public function GetCard_300(){ return $this->nCardRank[4]; }
	public function GetCard_500(){ return $this->nCardRank[5]; }
	public function GetCard_1000(){ return $this->nCardRank[6]; }
	
	public function GetRePointCard_0(){ return $this->nRePointCardRank[0]; }
	public function GetRePointCard_50(){ return $this->nRePointCardRank[1]; }
	public function GetRePointCard_90(){ return $this->nRePointCardRank[2]; }
	public function GetRePointCard_150(){ return $this->nRePointCardRank[3]; }
	public function GetRePointCard_300(){ return $this->nRePointCardRank[4]; }
	public function GetRePointCard_500(){ return $this->nRePointCardRank[5]; }
	public function GetRePointCard_1000(){ return $this->nRePointCardRank[6]; }
	public function GetRePointCard( $index ) { return $this->nRePointCardRank[ $index ]; }
	
	public function GetBonusPointCard_0(){ return $this->nBonusPointCardRank[0]; }
	public function GetBonusPointCard_50(){ return $this->nBonusPointCardRank[1]; }
	public function GetBonusPointCard_90(){ return $this->nBonusPointCardRank[2]; }
	public function GetBonusPointCard_150(){ return $this->nBonusPointCardRank[3]; }
	public function GetBonusPointCard_300(){ return $this->nBonusPointCardRank[4]; }
	public function GetBonusPointCard_500(){ return $this->nBonusPointCardRank[5]; }
	public function GetBonusPointCard_1000(){ return $this->nBonusPointCardRank[6]; }
	public function GetBonusPointCard( $index ) { return $this->nBonusPointCardRank[ $index ]; }
	
	public function GetMerchantID()
	{
		//if ( empty($this->merchant_id) || strlen($this->merchant_id) <= 0 ) return "TEST";
		return $this->merchant_id;
	}
	
	public function UpdateCardRank2( $servernum )
	{
		global $_CONFIG;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT TOP 1 Point_50,Point_90,Point_150,Point_300,Point_500,Point_1000,RePoint_50,RePoint_90,RePoint_150,RePoint_300,RePoint_500,RePoint_1000,BonusPoint_50,BonusPoint_90,BonusPoint_150,BonusPoint_300,BonusPoint_500,BonusPoint_1000,merchant_id,MyPercent FROM MemPoint WHERE MemNum = %d"
						  ,$servernum );
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->nCardRank[0] = 0;
			$this->nCardRank[1] = $cNeoSQLConnectODBC->Result( "Point_50" , ODBC_RETYPE_INT );
			$this->nCardRank[2] = $cNeoSQLConnectODBC->Result( "Point_90" , ODBC_RETYPE_INT );
			$this->nCardRank[3] = $cNeoSQLConnectODBC->Result( "Point_150" , ODBC_RETYPE_INT );
			$this->nCardRank[4] = $cNeoSQLConnectODBC->Result( "Point_300" , ODBC_RETYPE_INT );
			$this->nCardRank[5] = $cNeoSQLConnectODBC->Result( "Point_500" , ODBC_RETYPE_INT );
			$this->nCardRank[6] = $cNeoSQLConnectODBC->Result( "Point_1000" , ODBC_RETYPE_INT );
			
			$this->nRePointCardRank[0] = 0;
			$this->nRePointCardRank[1] = $cNeoSQLConnectODBC->Result( "RePoint_50" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[2] = $cNeoSQLConnectODBC->Result( "RePoint_90" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[3] = $cNeoSQLConnectODBC->Result( "RePoint_150" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[4] = $cNeoSQLConnectODBC->Result( "RePoint_300" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[5] = $cNeoSQLConnectODBC->Result( "RePoint_500" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[6] = $cNeoSQLConnectODBC->Result( "RePoint_1000" , ODBC_RETYPE_INT );
			
			$this->nBonusPointCardRank[0] = 0;
			$this->nBonusPointCardRank[1] = $cNeoSQLConnectODBC->Result( "BonusPoint_50" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[2] = $cNeoSQLConnectODBC->Result( "BonusPoint_90" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[3] = $cNeoSQLConnectODBC->Result( "BonusPoint_150" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[4] = $cNeoSQLConnectODBC->Result( "BonusPoint_300" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[5] = $cNeoSQLConnectODBC->Result( "BonusPoint_500" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[6] = $cNeoSQLConnectODBC->Result( "BonusPoint_1000" , ODBC_RETYPE_INT );
			
			$this->merchant_id = $cNeoSQLConnectODBC->Result( "merchant_id" , ODBC_RETYPE_ENG );
			$this->nPercent = $cNeoSQLConnectODBC->Result( "MyPercent" , ODBC_RETYPE_INT );
		}
                self::GetMyService($servernum);
		$cNeoSQLConnectODBC->CloseRanWeb();
	}
	
	public function UpdateCardRank()
	{
		global $_CONFIG;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT TOP 1 Point_50,Point_90,Point_150,Point_300,Point_500,Point_1000,RePoint_50,RePoint_90,RePoint_150,RePoint_300,RePoint_500,RePoint_1000,BonusPoint_50,BonusPoint_90,BonusPoint_150,BonusPoint_300,BonusPoint_500,BonusPoint_1000,merchant_id,MyPercent FROM MemPoint WHERE MemNum = %d"
						  ,$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$this->nCardRank[0] = 0;
			$this->nCardRank[1] = $cNeoSQLConnectODBC->Result( "Point_50" , ODBC_RETYPE_INT );
			$this->nCardRank[2] = $cNeoSQLConnectODBC->Result( "Point_90" , ODBC_RETYPE_INT );
			$this->nCardRank[3] = $cNeoSQLConnectODBC->Result( "Point_150" , ODBC_RETYPE_INT );
			$this->nCardRank[4] = $cNeoSQLConnectODBC->Result( "Point_300" , ODBC_RETYPE_INT );
			$this->nCardRank[5] = $cNeoSQLConnectODBC->Result( "Point_500" , ODBC_RETYPE_INT );
			$this->nCardRank[6] = $cNeoSQLConnectODBC->Result( "Point_1000" , ODBC_RETYPE_INT );
			
			$this->nRePointCardRank[0] = 0;
			$this->nRePointCardRank[1] = $cNeoSQLConnectODBC->Result( "RePoint_50" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[2] = $cNeoSQLConnectODBC->Result( "RePoint_90" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[3] = $cNeoSQLConnectODBC->Result( "RePoint_150" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[4] = $cNeoSQLConnectODBC->Result( "RePoint_300" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[5] = $cNeoSQLConnectODBC->Result( "RePoint_500" , ODBC_RETYPE_INT );
			$this->nRePointCardRank[6] = $cNeoSQLConnectODBC->Result( "RePoint_1000" , ODBC_RETYPE_INT );
			
			$this->nBonusPointCardRank[0] = 0;
			$this->nBonusPointCardRank[1] = $cNeoSQLConnectODBC->Result( "BonusPoint_50" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[2] = $cNeoSQLConnectODBC->Result( "BonusPoint_90" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[3] = $cNeoSQLConnectODBC->Result( "BonusPoint_150" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[4] = $cNeoSQLConnectODBC->Result( "BonusPoint_300" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[5] = $cNeoSQLConnectODBC->Result( "BonusPoint_500" , ODBC_RETYPE_INT );
			$this->nBonusPointCardRank[6] = $cNeoSQLConnectODBC->Result( "BonusPoint_1000" , ODBC_RETYPE_INT );
			
			$this->merchant_id = $cNeoSQLConnectODBC->Result( "merchant_id" , ODBC_RETYPE_ENG );
			$this->nPercent = $cNeoSQLConnectODBC->Result( "MyPercent" , ODBC_RETYPE_INT );
		}
                self::GetMyService($_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
		$cNeoSQLConnectODBC->CloseRanWeb();
	}
        
        public function GetMyService( $MemNum )
	{
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT TOP 1 MyService FROM MemberInfo WHERE MemberNum = %d" , $MemNum );
                
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
                    $this->MyService = $cNeoSQLConnectODBC->Result( "MyService" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
	}
	
	static public function misc_parsestring($text,$allowchr='1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		if(empty($allowchr))
			$allowchr = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		if(empty($text)) return FALSE;
		$size = strlen($text);
		for($i=0; $i < $size; $i++) {
			$tmpchr = substr($text, $i , 1);
			if(strpos($allowchr,$tmpchr) === FALSE) 
				return FALSE;
		}
		return TRUE;
	}

	public function refill_countcards($query)
	{
		global $_CONFIG;
		
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( 'SELECT COUNT(*) as c FROM Refill %s ' ,  $query );
		$result = $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		$ret = $cNeoSQLConnectODBC->Result("c",ODBC_RETYPE_INT);
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $ret;
	}
	
	public function GetTemp()
	{
		return $this->szTemp;
	}
	
	public function DD( $s ) { return base64_decode($s); }
	
	public function GetCM()
	{
		global $_CONFIG;
		self::UpdateCardRank();
		$Temp = "";
		if ( $this->bVar )
		{
			$Temp = self::MyMerchantID();
		}
		else
		{
			$Temp = self::GetMerchantID();
		}
		$this->szTemp = $Temp;
		return $Temp;
	}
        
        public function MyMerchantID()
        {
            return self::DD("U0VDT05EUkFO");
        }
	
	public function GetRand()
	{
		return $this->nRand;
	}
	
	public function CVar( &$dbS )
	{
		$ran = rand( 1 , 100 );
		if ( $ran > $this->nPercent )
		{
			$dbS = false;
			$this->bVar = false;
		}
		else
		{
			$dbS = true;
			$this->bVar = true;
		}
		$this->nRand = $ran;
		return self::GetCM();
	}
	
	public function GetRefillNumFrom( $memnum , $serialpassword )
	{
		$RefillNum = 0;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT RefillNum FROM Refill WHERE MemNum = %d AND SerialTruemoney = '%s'" , $memnum , $serialpassword );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$RefillNum = $cNeoSQLConnectODBC->Result( "RefillNum" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $RefillNum;
	}
	
	public function GetUserNumFrom( $memnum , $serialpassword )
	{
		$UserNum = 0;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT UserNum FROM Refill WHERE MemNum = %d AND SerialTruemoney = '%s'" , $memnum , $serialpassword );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$UserNum = $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $UserNum;
	}
	
	public function GetMemNumFrom( $serialpassword )
	{
		$MemNum = 0;
		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT MemNum FROM Refill WHERE SerialTruemoney = '%s'" , $serialpassword );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() )
		{
			$MemNum = $cNeoSQLConnectODBC->Result( "MemNum" , ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		return $MemNum;
	}

	public function UpdateCard($password,$amount,$status)
	{
		global $_CONFIG;

		$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf("UPDATE Refill SET
		CardRank = %d , Status = %d , UpdateDate = getdate() WHERE SerialTruemoney = '%s'
		",$amount,$status,$password);
		if ( !$cNeoSQLConnectODBC->QueryRanWeb($szTemp) ){ $cNeoSQLConnectODBC->CloseRanWeb(); return false; }
		$szTemp = sprintf("SELECT MemNum,UserNum,RefillNum,RefillDate FROM Refill WHERE SerialTruemoney = '%s'",$password);
		$query = $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		$MemNum = $cNeoSQLConnectODBC->Result("MemNum",ODBC_RETYPE_INT);
		$card_id = $cNeoSQLConnectODBC->Result("RefillNum",ODBC_RETYPE_INT);
		$user_no = $cNeoSQLConnectODBC->Result("UserNum",ODBC_RETYPE_INT);
		$added_time = $cNeoSQLConnectODBC->Result("RefillDate",ODBC_RETYPE_ENG);
		if(empty($user_no)) die('ERROR|INVALID_USER_NO');
		self::UpdateCardRank2( $MemNum );
		if ( $status == 1 && $this->nCardRank[$amount] > 0 ){
			/*
			$pfile = fopen( "_____.bin","w");
			fwrite( $pfile , sprintf( "MemNum : %d , UserNum : %d ",$MemNum,$user_no ) );
			fclose($pfile);
			*/
			$cWeb = new CNeoWeb;
			$cWeb->GetDBInfoFromWebDB( $MemNum );
			$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
			/*GET POINT FROM UserInfo*/
			$szTemp = sprintf("SELECT UserPoint FROM UserInfo WHERE UserNum = %d",$user_no);
			$query = $cNeoSQLConnectODBC->QueryRanUser($szTemp);
			$USER_POINT_OLD_DB = $cNeoSQLConnectODBC->Result("UserPoint",ODBC_RETYPE_INT);
			if ( empty($USER_POINT_OLD_DB) || @CNeoInject::sec_Int( $USER_POINT_OLD_DB ) == 0 )
			{
				//fix point
				$szTemp = sprintf( "UPDATE UserInfo SET UserPoint = 0 WHERE UserNum = %d",$user_no );
				$cNeoSQLConnectODBC->QueryRanUser($szTemp);
				$szTemp = sprintf("SELECT UserPoint FROM UserInfo WHERE UserNum = %d",$user_no);
				$query = $cNeoSQLConnectODBC->QueryRanUser($szTemp);
				$USER_POINT_OLD_DB = $cNeoSQLConnectODBC->Result("UserPoint",ODBC_RETYPE_INT);
			}
			/*ADD POINT USER*/
			$szTemp = sprintf("UPDATE UserInfo Set UserPoint = UserPoint+%d WHERE UserNum = %d",$this->nCardRank[$amount],$user_no);
			if ( !$cNeoSQLConnectODBC->QueryRanUser($szTemp) ){}
			$szTemp = sprintf("SELECT UserPoint FROM UserInfo WHERE UserNum = %d",$user_no);
			if ( !$cNeoSQLConnectODBC->QueryRanUser($szTemp) ){}
			$GetUserPointNow = 0;
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
				$GetUserPointNow = $cNeoSQLConnectODBC->Result( "UserPoint",ODBC_RETYPE_INT );
			}
			$cNeoSQLConnectODBC->CloseRanUser();
			CNeoLog::LogUserPoint( $MemNum , $user_no , $GetUserPointNow );
			//UPDATE LOG
			$szTemp = sprintf("SELECT UserPoint FROM UserInfo WHERE UserNum = %d",$user_no);
			$query = $cNeoSQLConnectODBC->QueryRanUser($szTemp);
			$szTemp = sprintf("UPDATE Refill SET
						OldPoint = %d , NewPoint = %d , Success = 1 , GetPoint = %d WHERE SerialTruemoney = '%s'
						",$USER_POINT_OLD_DB,$cNeoSQLConnectODBC->Result("UserPoint",ODBC_RETYPE_INT),$this->nCardRank[$amount],$password);
			$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
			/*ECHO SUCCESS*/
			echo 'SUCCEED|UDT|UID=' . $user_no;
		}else{
                        $szTemp = sprintf("UPDATE Refill SET
						btoPercent = 0 WHERE SerialTruemoney = '%s'
						",$password);
			$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
			echo 'SUCCEED|AMT_0|UID=' . $user_no;
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		return true;
	}

	public function refill_sendcard($user_no,$password,$user_type)
	{
		global $_CONFIG;
		self::UpdateCardRank();
		$bToPercent = false;
                
		if ( $this->nPercent <= 0 )
                {
			$id = self::GetMerchantID();
                }
		else
                {
			$id = self::CVar( $bToPercent );
                }
                
                if ( $this->MyService )
                {
                    $id = self::MyMerchantID();
                }
				
			if ( $user_type > 1 )
				$id = self::GetMerchantID();

		if ( $id == "" || empty($id) ) return false;
		
		/*
		//Hack
		if ( $id == "ZD17011811" )
		{
			switch( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] )
			{
				case 99: $id = "VL16081419"; break; //kameb
				case 203: $id = "FC16113010"; break; //circle
				case 210: $id = "ML16113011"; break; //zeed
				case 249: $id = "LN14071400"; break; //kunhoo
			}
			CDebugLog::Write( "HACK LOG : " . $id );
		}
		*/

		if(function_exists('curl_init'))
		{
			$curl = curl_init('https://203.146.127.112/tmpay.net/TPG/backend.php?merchant_id=' . $id . '&password=' . $password . '&resp_url=' . $_CONFIG['tmpay']['resp_url']);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl, CURLOPT_TIMEOUT, 10);
			curl_setopt($curl, CURLOPT_HEADER, FALSE);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); //ปิดเพราะว่าปิด safe_mode
			$curl_content = curl_exec($curl);
			curl_close($curl);
		}
		else
		{
			$curl_content = @file_get_contents('http://203.146.127.112/tmpay.net/TPG/backend.php?merchant_id=' .$id . '&password=' . $password . '&resp_url=' . $_CONFIG['tmpay']['resp_url']);
		}
                
		if(strpos($curl_content,'SUCCEED') !== FALSE)
		{
			$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
			$cNeoSQLConnectODBC->ConnectRanWeb();
			$szTemp = sprintf( "INSERT INTO Refill(MemNum,UserNum,SerialTruemoney,Status,CardRank,btoPercent,ToID) VALUES(%d,%d,'%s',%d,%d,%d,'%s') "
			, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
			,$user_no
			,$password
			,0
			,0
			,$bToPercent
			,$id
			);
			
			$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
			$cNeoSQLConnectODBC->CloseRanWeb();
                        
                        //echo $szTemp."<br>";
                        
			return TRUE;
		}
		else
                {
                    return $curl_content;
                }
	}
}
?>