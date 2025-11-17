<?php
class CNeoLog
{
	static public function Log_RePointInviteFriends( $MemNum , $UserNum , $FromUserID , $Amount , $BeforePoint , $AfterPoint )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO Log_RePointInviteFriends(MemNum,UserNum,FromUserID,Amount,BeforePoint,AfterPoint) VALUES(%d,%d,'%s',%d,%d,%d)"
																																	 , $MemNum , $UserNum , $FromUserID , $Amount , $BeforePoint , $AfterPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function Log_LoginAdmin( $MemNum , $IP )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO Log_LoginAdmin(MemNum,LogIP) VALUES(%d,'%s')" , $MemNum , $IP );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
	//system check card and add item to itembank
	static public function LogSysItemPointGet( $MemNum , $UserNum , $RefillNum , $ItemMain , $ItemSub )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_SysItemPointGet(MemNum,UserNum,RefillNum,ItemMain,ItemSub)
                                                VALUES(%d,%d,%d,%d,%d)"
                                            , $MemNum , $UserNum , $RefillNum , $ItemMain , $ItemSub );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
	//admin log
	static public function Admin_LogBan( $MemNum , $UserNum , $UserBan )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_AdminBanUser(MemNum,UserNum,UserBan)
                                                VALUES(%d,%d,%d)"
                                            , $MemNum , $UserNum , $UserBan );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            //echo $szTemp;
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
	//user log
    static public function LogChaReborn( $MemNum , $UserNum , $ChaNum , $ChaReborn , $GetPoint )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_ChaReborn(MemNum,UserNum,ChaNum,LogChaReborn,LogChaGetPoint)
                                                VALUES(%d,%d,%d,%d,%d)"
                                            , $MemNum , $UserNum , $ChaNum , $ChaReborn , $GetPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            //echo $szTemp;
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogForgetpassword( $MemNum , $UserNum , $GenNewPass , $LogIP , $Send2Email )
    {
            self::LogUpdateGamePassword( $MemNum , $UserNum , $GenNewPass , $LogIP );
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_Forgetpassword(MemNum,UserNum,GenNewPass,LogIP,LogSend2Email)
                                                VALUES(%d,%d,'%s','%s','%s')"
                                            , $MemNum , $UserNum , $GenNewPass , $LogIP , $Send2Email );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    
    static public function LogUpdateGamePassword( $MemNum , $UserNum , $NewPass , $LogIP )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_DoPass(MemNum,UserNum,UserPass,LogIP)
                                                VALUES(%d,%d,'%s','%s')"
                                            , $MemNum , $UserNum , $NewPass , $LogIP );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    
    static public function LogUpdateCharPassword( $MemNum , $UserNum , $NewPass , $LogIP )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_DoPass(MemNum,UserNum,UserPass2,LogIP)
                                                VALUES(%d,%d,'%s','%s')"
                                            , $MemNum , $UserNum , $NewPass , $LogIP );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    
    static public function LogLogIn( $MemNum , $UserNum , $LogIP )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_Login(MemNum,UserNum,LogIP)
                                                VALUES(%d,%d,'%s')"
                                            , $MemNum , $UserNum , $LogIP );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            //echo $szTemp;
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogBuySkillPoint( $MemNum , $UserNum , $ChaNum , $OldSkillPoint , $NewSkillPoint , $GetSkillPoint , $OldPoint , $NewPoint , $DelPoint )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_BuySkillPoint(MemNum,UserNum,ChaNum,OldSkillPoint,NewSkillPoint,GetSkillPoint,OldPoint,NewPoint,DelPoint)
                                                VALUES(%d,%d,%d,%d,%d,%d,%d,%d,%d)"
                                            , $MemNum , $UserNum , $ChaNum , $OldSkillPoint , $NewSkillPoint , $GetSkillPoint , $OldPoint , $NewPoint , $DelPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            //echo $szTemp;
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogUserBonusPoint( $MemNum , $UserNum , $BeforeBonusPoint , $NewBonusPoint , $BonusPrice , $SerialPassword , $bAdmin = 0 , $ToMerchantID = "", $ToPercentID = 0 )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_UserBonusPoint(MemNum,UserNum,BeforeBonusPoint,NewBonusPoint,BonusPrice,SerialPassword,bAdmin,ToMerchantID,ToPercentID)
                                                VALUES(%d,%d,%d,%d,%d,'%s',%d,'%s',%d)"
                                            ,$MemNum , $UserNum , $BeforeBonusPoint , $NewBonusPoint , $BonusPrice , $SerialPassword , $bAdmin , $ToMerchantID , $ToPercentID );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            //echo $szTemp;
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogUserPoint( $MemNum , $UserNum , $UserPoint , $bAdmin = 0 )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_UserPoint(MemNum,UserNum,UserPoint,bAdmin)
                                                VALUES(%d,%d,%d,%d)"
                                            ,$MemNum , $UserNum , $UserPoint , $bAdmin );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            //echo $szTemp;
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogMapWarp( $MemNum , $UserNum , $ChaNum ,  $GoMap , $MapPoint , $OldPoint , $NewPoint )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_MapWarp(MemNum,UserNum,ChaNum,GoMap,MapPoint,OldPoint,NewPoint)
                                                VALUES(%d,%d,%d,'%s','%s',%d,%d)"
                                            ,$MemNum , $UserNum , $ChaNum ,  $GoMap , $MapPoint , $OldPoint , $NewPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            //echo $szTemp;
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogChangeName( $MemNum , $UserNum , $ChaNum ,  $OldChaName , $NewChaName , $OldPoint , $NewPoint )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_ChangeName(MemNum,UserNum,ChaNum,OldChaName,NewChaName,OldPoint,NewPoint)
                                                VALUES(%d,%d,%d,'%s','%s',%d,%d)"
                                            ,$MemNum , $UserNum , $ChaNum ,  $OldChaName , $NewChaName , $OldPoint , $NewPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogReborn( $MemNum , $UserNum , $ChaNum ,  $OldReborn , $Reborn )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_Reborn(MemNum,UserNum,ChaNum,OldReborn,Reborn)
                                                VALUES(%d,%d,%d,%d,%d)"
                                            ,$MemNum , $UserNum , $ChaNum ,  $OldReborn , $Reborn );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogModifyStat( $MemNum , $UserNum , $ChaNum  , $Pow , $Pow2 , $Dex , $Dex2 , $Spi , $Spi2 , $Str , $Str2 , $Stm , $Stm2 , $StRemain , $StRemain2 , $OldPoint , $NewPoint )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_Stat(MemNum,UserNum,ChaNum,Pow,Pow2,Dex,Dex2,Spi,Spi2,Str1,Str2,Stm,Stm2,StRemain,StRemain2,OldPoint,NewPoint)
                                                VALUES(%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d)"
                                            ,$MemNum , $UserNum , $ChaNum  , $Pow , $Pow2 , $Dex , $Dex2 , $Spi , $Spi2 , $Str , $Str2 , $Stm , $Stm2 , $StRemain , $StRemain2 , $OldPoint , $NewPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogResetSkill( $MemNum , $UserNum , $ChaNum ,  $OldPoint , $NewPoint )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_ResetSkill(MemNum,UserNum,ChaNum,OldPoint,NewPoint)
                                                VALUES(%d,%d,%d,%d,%d)"
                                            ,$MemNum , $UserNum , $ChaNum ,  $OldPoint , $NewPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogTime2Point( $MemNum , $UserNum  , $OldPoint , $NewPoint , $Time , $TimePoint , $bSu  )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_Time2Point(MemNum,UserNum,OldPoint,NewPoint,Time,TimePoint,Success)
                                                VALUES(%d,%d,%d,%d,%d,%d,%d)"
                                            ,$MemNum , $UserNum  , $OldPoint , $NewPoint , $Time , $TimePoint , $bSu );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogResell( $MemNum , $UserNum , $ChaNum , $ItemMain , $ItemSub , $ItemName , $ItemPrice , $OldPoint , $NewPoint , $ChaInvenOld , $ChaInvenNew )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_Resell(MemNum,UserNum,ChaNum,ItemMain,ItemSub,ItemName,ItemPrice,UserPoint_Before,UserPoint_New,ChaInven_Bak,ChaInven_New)
                                                VALUES(%d,%d,%d,%d,%d,'%s',%d,%d,%d,0x%s,0x%s)"
                                            ,$MemNum , $UserNum , $ChaNum , $ItemMain , $ItemSub , $ItemName , $ItemPrice , $OldPoint , $NewPoint , $ChaInvenOld , $ChaInvenNew );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogCharMad( $MemNum , $UserNum , $ChaNum , $OldPoint , $NewPoint  )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_CharMad(MemNum,UserNum,ChaNum,UserPoint_Before,UserPoint_New)
                                                VALUES(%d,%d,%d,%d,%d)"
                                            ,$MemNum,$UserNum,$ChaNum,$OldPoint,$NewPoint );
            //echo $szTemp."<br>";
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogChangeClass( $MemNum , $UserNum , $ChaNum , $Class , $ToClass , $OldPoint , $NewPoint  )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_ChangeClass(MemNum,UserNum,ChaNum,ChaClass,ToClass,UserPoint_Before,UserPoint_New)
                                                VALUES(%d,%d,%d,%d,%d,%d,%d)"
                                            ,$MemNum,$UserNum,$ChaNum,$Class , $ToClass,$OldPoint,$NewPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogChangeSchool( $MemNum , $UserNum , $ChaNum , $School , $ToSchool , $OldPoint , $NewPoint  )
    {
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_ChangeSchool(MemNum,UserNum,ChaNum,School,ToSchool,UserPoint_Before,UserPoint_New)
                                                VALUES(%d,%d,%d,%d,%d,%d,%d)"
                                            ,$MemNum,$UserNum,$ChaNum,$School , $ToSchool,$OldPoint,$NewPoint );
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
    }
    static public function LogBuy( $MemNum , $ItemMain , $ItemSub , $UserNum , $ChaNum , $OldPoint , $NewPoint , $OldGameTime , $NewGameTime , $ItemType , $ItemName , $ItemPrice , $ItemTimePrice , $Query )
    {
            //if ( $MemNum <= 0 || empty($MemNum) || $ItemMain <= 0 || empty($ItemMain) || $ItemSub <= 0 || empty($ItemSub) || $UserNum <= 0 || empty($UserNum) ) return false;
            $cNeoSQLConnnectODBC = new CNeoSQLConnectODBC;
            $cNeoSQLConnnectODBC->ConnectRanWeb();
            $szTemp = sprintf( "INSERT INTO
                                                Log_Buy(MemNum,UserNum,ChaNum,ItemMain,ItemSub,ItemName,ItemPrice,ItemType,UserPoint_Before,UserPoint_New,Query,OldGameTime,NewGameTime,ItemTimePrice)
                                                VALUES(%d,%d,%d,%d,%d,'%s',%d,%d,%d,%d,%d,%d,%d,%d)"
                                            ,$MemNum,$UserNum,$ChaNum,$ItemMain,$ItemSub,$ItemName,$ItemPrice,$ItemType,$OldPoint,$NewPoint,$Query , $OldGameTime , $NewGameTime , $ItemTimePrice );
            //echo $szTemp;
            $cNeoSQLConnnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnnectODBC->CloseRanWeb();
            return true;
    }
}
?>