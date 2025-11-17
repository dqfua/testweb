<?php
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
if ( !CSessionNeo::checksessionout( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , CGlobal::GetSesLoginOut() , $cUser->LogIP ) )
{
	exit;
}
//update user from userinfo db
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
CGlobal::SetSesUser( serialize($cUser) );
$cUser = unserialize( CGlobal::GetSesUser() );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

if ( $cUser->GetUserType() >= 30 )
	$bUserLoginCheck = true;

if ( !CSec::Check() ) exit;

if ( !CSessionNeo::checkdopassdelay( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() ) > 1 ) exit;
$DoPass = md5( session_id() );
CSessionNeo::DoPass( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , $DoPass , $cUser->LogIP );
if ( CSessionNeo::checkdopass( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , $DoPass , $cUser->LogIP ) ) exit;

CInput::GetInstance()->BuildFrom( IN_POST );

$n = CInput::GetInstance()->GetValueInt( "itemnum" , IN_POST );
$usetimepoint = CInput::GetInstance()->GetValueInt( "usetimepoint" , IN_POST );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
if ( $bUserLoginCheck )
	$szTemp = sprintf("SELECT TOP 1 ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemTimePrice,ItemBonusPointPrice,ItemSell,ItemType,ItemSock,Item_MaxReborn FROM ItemProject WHERE MemNum = %d AND ( ItemShow = 0 OR ItemShow = 2 ) AND ItemDelete = 0 AND ItemNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$n);
else
	$szTemp = sprintf("SELECT TOP 1 ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemTimePrice,ItemBonusPointPrice,ItemSell,ItemType,ItemSock,Item_MaxReborn FROM ItemProject WHERE MemNum = %d AND ItemShow = 0 AND ItemDelete = 0 AND ItemNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$n);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$MemNum = $cNeoSQLConnectODBC->Result("MemNum",ODBC_RETYPE_INT);
	$SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
	$ItemNum = $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT);
	$ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
	$ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
	$ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
	$ItemComment = $cNeoSQLConnectODBC->Result("ItemComment",ODBC_RETYPE_THAI);
	$ItemImage = $cNeoSQLConnectODBC->Result("ItemImage",ODBC_RETYPE_THAI);
	$ItemPrice = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
	$ItemTimePrice = $cNeoSQLConnectODBC->Result("ItemTimePrice",ODBC_RETYPE_INT);
	$ItemBonusPointPrice = $cNeoSQLConnectODBC->Result("ItemBonusPointPrice",ODBC_RETYPE_INT);
	$ItemSell = $cNeoSQLConnectODBC->Result("ItemSell",ODBC_RETYPE_INT);
	$ItemType = $cNeoSQLConnectODBC->Result("ItemType",ODBC_RETYPE_INT);
	$ItemSock = $cNeoSQLConnectODBC->Result("ItemSock",ODBC_RETYPE_INT);
	$Item_MaxReborn = $cNeoSQLConnectODBC->Result("Item_MaxReborn",ODBC_RETYPE_INT);
	
	$strReplace = str_replace( "/" , "" , PATH_UPLOAD_ITEMIMAGE );
	$ItemImage = str_replace( $strReplace , "" , $ItemImage );
}
if ( $ItemSock <= 0 )
{
	die("สินค้าชิ้นนี้หมดแล้ว เสียใจด้วยย!! Y Y <br>");
}

if ( $cUser->GetUserBan() )
{
	die( "ไม่สามารถทำรายการได้เนื่องไอดีคุณถูกระงับ<br>" );
}
$cUser->__Ban();
ignore_user_abort(true);

$UserPoint = $cUser->GetUserPoint();
$GameTime = $cUser->GetGameTime();
$BonusPoint = $cUser->GetBonusPoint();
if ( $usetimepoint == 1 )
{
	if ( $GameTime < $ItemTimePrice && $ItemTimePrice > 0 )
	{
		echo "<font colo=red><b>ไม่สามารถทำรายการได้เนื่องจากเวลาออนไลน์ของคุณไม่เพียงพอ</b></font><br>";
		$cUser->__UnBan();
		exit;
	}
	if ( $BonusPoint < $ItemBonusPointPrice && $ItemBonusPointPrice > 0 )
	{
		echo "<font colo=red><b>ไม่สามารถทำรายการได้เนื่องจากแต้มสะสมไม่เพียงพอ</b></font><br>";
		$cUser->__UnBan();
		exit;
	}
	if (  $ItemTimePrice <= 0 )
	{
		echo "<font colo=red><b>ไม่สามารถซื้อไอเทมนี้ด้วยเวลาออนไลน์ได้</b></font><br>";
		$cUser->__UnBan();
		exit;
	}
}else{
	if ( $UserPoint < $ItemPrice && $ItemPrice > 0 )
	{
		echo "<font colo=red><b>ไม่สามารถทำรายการได้เนื่องจากพ้อยของคุณไม่เพียงพอ</b></font><br>";
		$cUser->__UnBan();
		exit;
	}
	if ( $BonusPoint < $ItemBonusPointPrice && $ItemBonusPointPrice > 0 )
	{
		echo "<font colo=red><b>ไม่สามารถทำรายการได้เนื่องจากแต้มสะสมไม่เพียงพอ</b></font><br>";
		$cUser->__UnBan();
		exit;
	}
	if ( $ItemTimePrice > 0 && $ItemPrice == 0  )
	{
		echo "<font colo=red><b>ไม่สามารถทำรายการได้เนื่องจากการซื้อที่คุณเลือกไม่ถูกต้อง</b></font><br>";
		$cUser->__UnBan();
		exit;
	}
}

if( $ItemPrice >= 0 && $ItemTimePrice >= 0 ){
	$bSuccess = false;
	if ( $ItemType == 0 )
	{
		//add to item bank
		$cRanShop = new CRanShop;
		if ( CSessionNeo::checksessionout( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , CGlobal::GetSesLoginOut() , $cUser->LogIP ) )
			$bSuccess = $cRanShop->BuyItem( $UserID , $ItemMain , $ItemSub );
	}elseif ( $ItemType == 1 )
	{
		$error = 0;
		if ( $error == 0 )
		{
			//add to inventory item
			$cNeoSQLConnectODBC->CloseRanUser();
			$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
			if ( $bChaLogin )
			{
				$pCha = unserialize( CGlobal::GetSes( CGlobal::GetSesChaMan() ) );
				$ChaNum = $pCha->GetChaNum();
				if ( $ChaNum > 0 && $pCha->GetNowOnline() )
				{
					if ( $Item_MaxReborn == 0 || $pCha->GetChaReborn() >= $Item_MaxReborn )
					{
						$cRanShop = new CRanShop;
						$bCom = false;
						if ( CSessionNeo::checksessionout( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , CGlobal::GetSesLoginOut() , $cUser->LogIP ) )
							$bCom = $cRanShop->BuyItem_F( $ChaNum , $ItemMain , $ItemSub );
						if ( $bCom )
						{
							$bSuccess = true;
						}
						else
						{
							echo "<font color=red><b>ซื้อไอเทมไม่สำเร็จเนื่องจากตัวละครที่คุณเลือกไม่ถูกต้อง หรือตัวละครอาจจะออนไลน์อยู่</b></font>";
							$cUser->__UnBan();
							exit;
						}
					}else{
						echo "ตัวละครยังจุติไม่เพียงพอที่จะซื้อไอเทมชิ้นนี้";
						$cUser->__UnBan();
						exit;
					}
				}else{
					echo "<font color=red><b>ไม่พบตัวละคร!!</b></font><br>";
					$cUser->__UnBan();
					exit;
				}
			}else{
				echo "<font color=red><b>รหัสภาพไม่ถูกต้อง</b></font>";
				$cUser->__UnBan();
				exit;
			}
		}else{
			echo "<font color=red><b>กรุณาเลือกตัวละครที่คุณต้องการซื้อไอเทมชิ้นนี้!!</b></font><br>";
			$cUser->__UnBan();
			exit;
		}
	}
	if ( $bSuccess )
	{
		$cNeoSQLS = new CNeoSQLConnectODBC;
		$cNeoSQLS->ConnectRanWeb();
		$szTemp = sprintf("UPDATE ItemProject SET ItemSock = ItemSock - 1 WHERE ItemNum = %d",$ItemNum);
		$Query = $cNeoSQLS->QueryRanWeb($szTemp);
		$cNeoSQLS->CloseRanWeb();
		if ( $usetimepoint == 1 )
		{
			$cUser->DownGameTime( $ItemTimePrice );
		}else{
			//$cUser->SetUserPoint( $UserPoint-$ItemPrice );
			//$Query = $cUser->UpdateUserPointToDB();
			$cUser->DownPoint( $ItemPrice );
		}
		
		if ( $ItemBonusPointPrice > 0 )
		{
			$cUser->DownBonusPoint( $ItemBonusPointPrice );
		}
		
		CGlobal::SetSesUser( serialize( $cUser ) );
		CNeoLog::LogBuy( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $ItemMain , $ItemSub , $UserNum , $ChaNum , $UserPoint , $cUser->GetUserPoint() , $GameTime , $cUser->GetGameTime() , $ItemType , $ItemName , $ItemPrice , $ItemTimePrice , $Query );
			$cUser->__UnBan();
	}else{
			$cUser->__UnBan();
            die("<font color=red><b>ไม่สามารถทำรายการได้เนื่องจากไม่พบไอเทมในระบบ</b></font>");
        }
}else{
	echo "<font color=red><b>ราคาไอเทมชิ้นนี้ไม่ถูกต้อง!!</b></font><br>";
	$cUser->__UnBan();
	exit;
}
?>
<table width="500" border="0" align="center" cellpadding="3" cellspacing="3" bgcolor="#000000">
  <tr>
    <td colspan="2" align="center" bgcolor="#0099FF"><strong><font color=black>ทำรายการสำเร็จ</font></strong></td>
  </tr>
  <tr>
    <td width="150" align="left" valign="top"><img src="<?php echo PATH_UPLOAD_ITEMIMAGE . $ItemImage; ?>" width=155 height=155 border=0 /></td>
    <td width="329" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" bgcolor="#00CCFF"><strong><font color="#000000">สินค้าที่คุณทำรายการ</font></strong></td>
        </tr>
      <tr>
        <td width="38%" bgcolor="#00CCFF"><strong><font color="#000000">ชื่อ</font></strong></td>
        <td width="62%" bgcolor="#00CCFF"><font color="#000000"><?php echo $ItemName; ?></font></td>
        </tr>
      <tr>
        <td bgcolor="#00CCFF"><strong><font color="#000000">ชนิด</font></strong></td>
        <td bgcolor="#00CCFF"><font color="#000000"><?php echo $_CONFIG["ITEMTYPE"][$ItemType]; ?></font></td>
      </tr>
      <tr>
        <td bgcolor="#00CCFF"><strong><font color="#000000">ราคา<?php if ( $usetimepoint == 1 ) echo "(เวลาออนไลน์)"; ?></font></strong></td>
        <td bgcolor="#00CCFF"><font color="#000000"><?php if ( $usetimepoint == 1 ) echo $ItemTimePrice ."นาที"; else echo $ItemPrice . "พ้อย"; ?></font></td>
      </tr>
<?php
if ( $ItemBonusPointPrice > 0 )
{
?>
      <tr>
        <td bgcolor="#00CCFF"><strong><font color="#000000">แต้มสะสม</font></strong></td>
        <td bgcolor="#00CCFF"><font color="#000000"><?php echo $ItemBonusPointPrice; ?> แต้ม</font></td>
      </tr>
<?php
}
?>
    </table></td>
  </tr>
  </table>