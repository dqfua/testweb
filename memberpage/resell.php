<?php
//include("logon.php");
//set_time_limit(120);
$error = 0;
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

if ( !CSessionNeo::checksessionout( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , CGlobal::GetSesLoginOut() , $cUser->LogIP ) )
{
	exit;
}

if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) )
{
	die("<div align=center><font color=red><b>กรุณาออกจากเกมส์ก่อนใช้งานระบบนี้!!</b></font></div>");
}
//$cUser = unserialize( CGlobal::GetSesUser() );

//update user from userinfo db
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
//CGlobal::SetSesUser( serialize($cUser) );
COnline::OnlineSet( $cUser );
//$cUser = unserialize( CGlobal::GetSesUser() );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

if ( !CSessionNeo::checkdopassdelay( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() ) > 1 ) exit;
//$DoPass = md5( rand() % 9999999999999 . session_id() );
$DoPass = md5( session_id() );
CSessionNeo::DoPass( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , $DoPass , $cUser->LogIP );
if ( CSessionNeo::checkdopass( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , $DoPass , $cUser->LogIP ) ) exit;

echo "<div id='area_resell_work'></div>";

//if ( $cUser->GetUserType() == 1 ) die( "ขณะนี้ระบบกำลังปิดปรับปรุง คาดว่าจะใช้งานได้ไม่เกินเวลา 6.00น." );
/*
//$getcode = @CNeoInject::sec_Int( CGlobal::__GET2("getcode") );
//if ( $getcode )
{
	$simplecapchar = new SimpleCaptcha;
	$simplecapchar->nSize = $_CONFIG["RESELLITEM"]["SIZE"];
	$simplecapchar->SeassionName = $_CONFIG["RESELLITEM"]["SESSION"];
	$simplecapchar->Result();
	//$simplecapchar->Display();
	//exit;
}
*/

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nsubmit )
{
    /*
	if ( CGlobal::GetSes( $_CONFIG["RESELLITEM"]["SESSION"] ) == "" ) exit;
	$SecCode = @CNeoInject::sec_Eng( CGlobal::__POST("code") );
	if ( strcmp( CGlobal::GetSes( $_CONFIG["RESELLITEM"]["SESSION"] ) , $SecCode ) != 0 ) die( "<font color=red><b>รหัสภาพไม่ถูกต้อง</b></font>" );
	CGlobal::SetSes( $_CONFIG["RESELLITEM"]["SESSION"] , "" );
	if ( !CSec::Check() ) exit;
	sleep( rand( 3 , 7 ) );
     */
	$Resell_Price = 0;
	$n = CInput::GetInstance()->GetValueInt( "n" , IN_POST );
	$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
	if ( $bChaLogin )
	{
		echo"<div align=center><font size=+1><b>ตัวละครที่ทำรายการคือ</b></font></div><br>";
		include("chainfo.php");
	}else{
		die("กรุณาเลือกตัวละครก่อนทำรายการ<br>");
	}
	$cNeoChaInven = new CNeoChaInven( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cNeoChaInven->ChaInven_DB( $ChaNum );
	if ( $n < 0 || $n >= $cNeoChaInven->GetItemNum() )
	{
		die("ไอเทมที่เลือกไม่ถูกต้อง<br>");
	}
	if (  !$pCha->GetNowOnline() )
	{
		die("ไม่สามารถทำรายการได้เนื่องจากตัวละครออนไลน์อยู่<br>");
	}
	//sleep( rand( 0 , 4 ) );
        if ( $cUser->GetUserBan() )
        {
            die( "ไม่สามารถทำรายการได้เนื่องไอดีคุณถูกระงับ<br>" );
        }
        $cUser->__Ban();
	//sleep( rand( 0 , 4 ) );
        if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) )
        {
            echo "ไม่สามารถทำรายการได้เนื่องจากไอดีคุณออนไลน์อยู่<br>";
            $cUser->__UnBan();
            exit;
        }
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cNeoSQLConnectODBC->ConnectRanUser( $cWeb->GetRanUser_IP(), $cWeb->GetRanUser_User(), $cWeb->GetRanUser_Pass(), $cWeb->GetRanUser_DB() );
	$szTemp = sprintf( "SELECT TOP 1 LoginNum,LogDate
				  FROM LogLogin WHERE UserNum = %d AND LogInOut = 1
				   AND DAY( LogDate ) = DAY( getdate() ) AND MONTH( LogDate ) = MONTH( getdate() ) AND YEAR( LogDate ) = YEAR( getdate() )
				   ORDER BY LoginNum DESC
				  ",$UserNum );
	$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
	$bLoginNumByGame = 0;
	$LoginByGameDate = "";
	while( $cNeoSQLConnectODBC->FetchRow() ){
		$bLoginNumByGame = $cNeoSQLConnectODBC->Result("LoginNum",ODBC_RETYPE_INT);
		$LoginByGameDate = $cNeoSQLConnectODBC->Result("LogDate",ODBC_RETYPE_ENG);
	}
	//echo "Hello world<br>";
	/*
	if ( $bLoginNumByGame == 0 )
	{
		printf("ในวันนี้เรายังไม่พบการล็อกอินของคุณ กรุณาล็อกอินในเกมส์ใหม่อีกครั้ง<br>");
		$error++;
	}
	*/
	if ( $error == 0 )
	{
		$szTemp = sprintf( "SELECT UserLoginState,LastLoginDate,
                                        getdate() as GetSQLDate ,
					DateDiff( minute, LastLoginDate , getdate() ) as DelayLogout
					FROM UserInfo WHERE UserNum = %d AND UserLoginState = 0
					  "
					  ,$UserNum );
                //echo $szTemp;
		$cNeoSQLConnectODBC->QueryRanUser( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() ){
			$UserLoginState = $cNeoSQLConnectODBC->Result( "UserLoginState",ODBC_RETYPE_INT );
			$LastLoginDate = $cNeoSQLConnectODBC->Result( "LastLoginDate",ODBC_RETYPE_ENG );
			$DelayLogout = $cNeoSQLConnectODBC->Result( "DelayLogout",ODBC_RETYPE_INT );
                        $GetSQLDate = $cNeoSQLConnectODBC->Result( "GetSQLDate",ODBC_RETYPE_ENG );
			$DelayLoginandLogout = CGlobal::DateDiff( $LastLoginDate , $GetSQLDate );
			if ( !empty( $DelayLogout ) && $DelayLogout > 1 )
			{
				$bLogoutGood = true;
			}
		}
		/*
		if ( !$bLogoutGood )
		{
			printf("คุณจะต้องออกเกมส์อย่างน้อย 1 นาทีถึงจะสามารถขายไอเทมคืนได้");
			$error++;
		}
		*/
		$cNeoSQLConnectODBC->CloseRanUser();
		
		$cNeoSQLConnectODBC->ConnectRanWeb();
		$szTemp = sprintf( "SELECT COUNT(LogNum) As nCount FROM Log_Resell WHERE MemNum = %d AND UserNum = %d
										 AND DAY( LogDate ) = DAY( getdate() ) AND MONTH( LogDate ) = MONTH( getdate() ) AND YEAR( LogDate ) = YEAR( getdate() )
										 "
										 ,$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
										 ,$UserNum
										 );
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		$nResellCount = 0;
		while( $cNeoSQLConnectODBC->FetchRow() ) {
			$nResellCount = $cNeoSQLConnectODBC->Result( "nCount",ODBC_RETYPE_INT );
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
		/*
		if ( $nResellCount >= $_CONFIG["RESELLITEM"]["CANDO"] ) {
			printf(
				   " คุณไม่สามารถทำรายการขายคืนได้ในวันนี้เนื่องจากรายการที่คุณทำได้ครบ <b>%d</b> ครั้งไปแล้ว กรุณารอทำรายการใหม่พรุ่งนี้จ๊ะ "
				   , $_CONFIG["RESELLITEM"]["CANDO"]
				   );
			$error++;
		}
		*/
	}
	$bSuccess = false;
	if ( $error == 0 )
	{
		$cNeoSQLConnectODBC->ConnectRanWeb();
		/*
		//NEW CODE TEST
		$bWork = false;
		$szTemp = sprintf( "SELECT WorkNum FROM Work_Resell WHERE MemNum = %d AND UserNum = %d AND HowWork = 0" , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		while( $cNeoSQLConnectODBC->FetchRow() ) $bWork = true;
		if ( $bWork == false )
		*/
		{
			$szTemp = sprintf("SELECT TOP 1 ItemNum,ItemName,ItemMain,ItemSub,ItemPrice,ItemImage,Item_Resell_Percent FROM ItemProject WHERE ItemMain = %d AND ItemSub = %d AND ItemDelete = 0 AND MemNum = %d AND Item_Resell = 1 AND ItemType = 1 ORDER BY ItemNum DESC",$cNeoChaInven->GetItemMain($n),$cNeoChaInven->GetItemSub($n),$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
			//echo $szTemp."<br>";
			$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
			$NOW_ItemNum = $cNeoChaInven->FindItem($cNeoChaInven->GetItemMain($n),$cNeoChaInven->GetItemSub($n));
			
			$ChaInvenOld = $cNeoChaInven->GetBinary();
			
			while( $cNeoSQLConnectODBC->FetchRow() )
			{
				/*
				//New Code Test
				$ItemMain = $cNeoSQLConnectODBC->Result("ItemMain");
				$ItemSub = $cNeoSQLConnectODBC->Result("ItemSub");
				$bWork = false;
				$szTemp = sprintf( "SELECT WorkNum FROM Work_Resell WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d ItemPos = %d AND HowWork = 0"
								  , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
									, $UserNum
									, $pCha->GetChaNum()
									, $n
								  );
				$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
				while( $cNeoSQLConnectODBC->FetchRow() ) {
					$bWork = true;
				}
				if ( $bWork ) break;
				$szTemp = sprintf( "INSERT INTO Work_Resell( MemNum , UserNum , ChaNum , ItemPos , ItemMain , ItemSub , ItemInven , ChaInven , SecCode , RandNumTime )
															VALUES( %d , %d , %d , %d , %d , %d , 0x%s , 0x%s , '%s' , %d ) "
															, $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
															, $UserNum
															, $pCha->GetChaNum()
															, $n
															, $ItemMain
															, $ItemSub
															, $cNeoChaInven->GetBinaryItemSlot( $n )
															, $cNeoChaInven->GetBinary(  )
															, $SecCode
															, time()
															);
				//echo $szTemp."<br>";
				$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
				//echo "Work";
				$bSuccess = true;
				*/
				//OLD SYSTEM
				$ItemNum = sprintf( "%06d" , $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT) );
				$ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
				$ItemPrice = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
				$Item_Resell_Percent = $cNeoSQLConnectODBC->Result("Item_Resell_Percent",ODBC_RETYPE_INT);
				$ItemImage = $cNeoSQLConnectODBC->Result("ItemImage",ODBC_RETYPE_THAI);
				$ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
				$ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
				
				$ItemTrunNum = $cNeoChaInven->GetItemTrunNum($NOW_ItemNum);
				$bDel = $cNeoChaInven->DeleteItem( $NOW_ItemNum );
				$NewPoint = 0;
				if ( $bDel )
				{
					$UserPoint = $cUser->GetUserPoint();
					//echo "Hello world<br>";
					//$cNeoChaInven->UpdateVar2Binary(); // this use only of add item only
					$cNeoChaInven->SaveChaInven( );
					if ( $ItemPrice > 0 )
					{
						if ( $ItemTrunNum > 0 )
						{
							//echo "Hello world 1<br>";
							$Resell_Price = $ItemTrunNum*( CGlobal::ResellQuery( $ItemPrice,$Item_Resell_Percent ) );
							//echo "Hello world<br>";
							//$cUser->SetUserPoint( $UserPoint+$Resell_Price );
							$NewPoint = $UserPoint+($ItemPrice*$ItemTrunNum);
							//echo "Hello world<br>";
							//if ( CSessionNeo::checksessionout( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , CGlobal::GetSesLoginOut() , $cUser->LogIP ) )
							$cUser->UpPoint( $Resell_Price );
							//echo "Hello world<br>";
						}
						else
						{
							//echo "Hello world 2<br>";
							$Resell_Price = CGlobal::ResellQuery( $ItemPrice,$Item_Resell_Percent );
							//echo "Hello world<br>";
							//ป้องกันการคำนวนแล้วได้พ้อยเกิดความจริง
							if ( $Resell_Price > $ItemPrice ) $Resell_Price = $ItemPrice;
							//echo "Hello world<br>";
							//$cUser->SetUserPoint( $UserPoint+$Resell_Price );
							$NewPoint = $UserPoint+$ItemPrice;
							//echo "Hello world<br>";
							//if ( CSessionNeo::checksessionout( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , CGlobal::GetSesLoginOut() , $cUser->LogIP ) )
							$cUser->UpPoint( $Resell_Price );
							//echo "Hello world<br>";
						}
						//echo "Hello world3<br>";
						//$cUser->UpdateUserPointToDB();
					}
					$bSuccess = true;
				}
			}
			/*
			// NEW CODE TEST
			else{
				die( "สามารถทำได้ทีละ 1 รายการเท่านั้นกรุณารอรายการเก่าของคุณทำรายการเสียก่อน" );
			}
			*/
		}
		if ( $bSuccess )
		{
			//NEW CODE TEST
			//printf( "ส่งการทำรายการแล้ว กรุณารอระบบตรวจสอบและทำการขายคืนให้คุณอาจจะใช้เวลานานสักหน่อยไม่น่าจะเกิด 10 - 30 วินาที" );
			//OLD SYSTEM
			$cNeoChaInven->ChaInven_DB( $ChaNum );
			$ChaInvenNew = $cNeoChaInven->GetBinary();
			CNeoLog::LogResell( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $pCha->GetChaNum()  , $ItemMain , $ItemSub , $ItemName , /*$ItemPrice*/$Resell_Price  ,$UserPoint , $cUser->GetUserPoint() , $ChaInvenOld , $ChaInvenNew );
			//CGlobal::SetSesUser( serialize($cUser) );
			COnline::OnlineSet( $cUser );
			//CChaOnline::OnlineSet( $pCha );
			printf("การขายคืนสำเร็จ");
		}
		$cNeoSQLConnectODBC->CloseRanWeb();
	}
        $cUser->__UnBan();
	/*
	//NEW CODE TEST
	else{
		printf( "ไอเทมมีอยู่ในรายการแล้วไม่สามารถทำเพิ่มอีกได้" );
	}
	*/
	exit;
}
?>
<table width="600" border="0" cellpadding="0" cellspacing="0" bgcolor="#000000">
  <tr>
    <td align="center"><?php

	$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
	if ( $bChaLogin )
	{
		echo"<div align=center><font size=+1><b>ตัวละครที่คุณเลือกอยู่ในปัจจุบัน</b></font></div><br>";
		include("chainfo.php");
	}
	else
	die("กรุณาเลือกตัวละครก่อนใช้งานระบบนี้<br>");
	
        /*
	printf( "<b>แสดงรายการทั้งหมดของคุณที่นี่ <a href=\"#see\" onclick=\"javascript:loadpage('work_resell','area_resell_info','');\">คลิก!!</a></b>
	<div id='area_resell_info'></div>
	" );
         */
	?><br /></td>
  </tr>
  <tr>
    <td align="center"><p>
      <!-- <a href="#do" onclick="CSS_dis('inventory');" ><b>แสดงรายการที่สามารถขายคืน</b></a><br /> -->
      </p>
      <div id="inventory" style=" display:none; ">
        <table width="90%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="22%" align="center"><b>รหัสไอเทม</b></td>
        <td width="46%" align="center"><b>ชื่อไอเทม</b></td>
        <td width="14%" align="center"><b>ราคา(ชิ้น)</b></td>
        <td width="18%" align="center"><b>ภาษีขายคือ %</b></td>
        </tr>
<?php
CSec::Begin();
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();
$szTemp = sprintf("SELECT ItemNum,ItemName,ItemMain,ItemSub,ItemPrice,ItemImage,Item_Resell_Percent FROM ItemProject WHERE MemNum = %d AND ItemDelete = 0 AND Item_Resell = 1 AND ItemType = 1",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]);
$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
$i = 0;
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$ItemNum_O[$i] = $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT);
	$ItemNum[$i] = sprintf( "%06d" , $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT) );
	$ItemName[$i] = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
	$ItemPrice[$i] = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
	$Item_Resell_Percent[$i] = $cNeoSQLConnectODBC->Result("Item_Resell_Percent",ODBC_RETYPE_INT);
	$ItemImage[$i] = $cNeoSQLConnectODBC->Result("ItemImage",ODBC_RETYPE_THAI);
	$ItemMain[$i] = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
	$ItemSub[$i] = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
?>
          <tr>
            <td align="center"><a href="#see" onclick="load_item(<?php echo $ItemNum_O[$i]; ?>);" title="คลิกเพื่อไปที่ไอเทมชิ้นนี้"><?php echo $ItemNum[$i]; ?></a></td>
            <td align="center"><a href="#see" onclick="load_item(<?php echo $ItemNum_O[$i]; ?>);" title="คลิกเพื่อไปที่ไอเทมชิ้นนี้"><?php echo $ItemName[$i]; ?></a></td>
            <td align="center"><a href="#see" onclick="load_item(<?php echo $ItemNum_O[$i]; ?>);" title="คลิกเพื่อไปที่ไอเทมชิ้นนี้"><?php echo $ItemPrice[$i]; ?></a></td>
            <td align="center"><a href="#see" onclick="load_item(<?php echo $ItemNum_O[$i]; ?>);" title="คลิกเพื่อไปที่ไอเทมชิ้นนี้"><?php echo $Item_Resell_Percent[$i]; ?></a></td>
          </tr>
  <?php
	$i++;
}
$cNeoSQLConnectODBC->CloseRanWeb();
?>
    </table>
    </div>
<?php
$cNeoChaInven = new CNeoChaInven( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$cNeoChaInven->ChaInven_DB( $ChaNum );
?>
    <b><a href="#do" onclick="CSS_dis('myinven');" >Inventory ( คลิกเพื่อแสดงรายการไอเทมที่อยู่ในช่อง I ของคุณ ) ( ไอเทมทั้งหมดในของคุณคือ <?php echo $cNeoChaInven->GetItemNum(); ?> )</a></b>
	<div id="myinven" style="">
    	<table width="90%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="39%" align="center"><b>ชื่อไอเทม</b></td>
            <td width="19%" align="center"><b>ราคาขายคืน</b></td>
            <td width="27%" align="center"><b>ข้อมูลการขายคืน</b></td>
          </tr>
<?php
for( $n = 0 ; $n < $cNeoChaInven->GetItemNum() ; $n ++ )
{
	$strName = "---";
	$strFound = "---";
	$NOW_ItemMain = $cNeoChaInven->GetItemMain($n);
	$NOW_ItemSub = $cNeoChaInven->GetItemSub($n);
	$NOW_ItemNum = -1;
	$ResellPoint = 0;
	for( $ii = 0 ; $ii < $i ; $ii++ )
	{
		//printf( "%d == %d && %d == %d<br>",$ItemMain[$ii],$NOW_ItemMain,$ItemSub[$ii],$NOW_ItemSub );
		if ( $ItemMain[$ii] == $NOW_ItemMain && $ItemSub[$ii] == $NOW_ItemSub )
		{
			//printf("found<br>");
			$strName = $ItemName[$ii];
			$strFound = "<b>คลิก!!เพื่อขายคืน</b>";
			$NOW_ItemNum = $cNeoChaInven->FindItem($NOW_ItemMain,$NOW_ItemSub);
			$ResellPoint = CGlobal::ResellQuery( $ItemPrice[$ii],$Item_Resell_Percent[$ii] );
			break;
		}else{
			//printf("Not FOund<br>");
		}
	}
	/*
	if ( $NOW_ItemNum > 0 )
	{
		$simplecapchar = new SimpleCaptcha;
		$simplecapchar->nSize = $_CONFIG["RESELLITEM"]["SIZE"];
		$simplecapchar->SeassionName = $_CONFIG["RESELLITEM"]["SESSION"];
		$simplecapchar->Result();
		//$simplecapchar->Display();
	}
	*/
?>
			<tr>
            <td width="39%" align="center"><b><a <?php if ( $NOW_ItemNum > 0 ) echo "href='#areagetcode'"; ?> <?php printf( " onclick='/*document.freeform.valuegetfree.value = %d; CSS_dis( \"areagetcode\" );CSS_dis(\"myinven\");*/resell_getcode( %d , \"1\" , \"1\" );' title='%s' ",$NOW_ItemNum,$NOW_ItemNum,$strFound ); ?>><?php echo $strName; ?></a></b></td>
            <td width="19%" align="center"><b><a <?php if ( $NOW_ItemNum > 0 ) echo "href='#areagetcode'"; ?> <?php printf( " onclick='/*document.freeform.valuegetfree.value = %d; CSS_dis( \"areagetcode\" );CSS_dis(\"myinven\");*/resell_getcode( %d , \"1\" , \"1\" );' title='%s' ",$NOW_ItemNum,$NOW_ItemNum,$strFound ); ?>><?php echo $ResellPoint; ?></a></b></td>
            <td width="27%" align="center"><b><a <?php if ( $NOW_ItemNum > 0 ) echo "href='#areagetcode'"; ?> <?php printf( " onclick='/*document.freeform.valuegetfree.value = %d; CSS_dis( \"areagetcode\" );CSS_dis(\"myinven\");*/resell_getcode( %d , \"1\" , \"1\" );' title='%s' ",$NOW_ItemNum,$NOW_ItemNum,$strFound ); ?>><?php echo $strFound; ?></a></b></td>
            </tr>
<?php
}
?>
        </table>
    </div>
    <p>&nbsp;</p></td>
  </tr>
  <tr>
    <td align="center">
<?php
/*
echo '<table width="50%" border="0" cellpadding="0" cellspacing="0">';
for( $y = 0 ; $y <= INVEN_Y ; $y++ )
{
	echo "<TR>";
	for( $x = 0 ; $x <= INVEN_X ; $x++ )
	{
		$ItemNum = -1;
		$nFound = $cNeoChaInven->InvenFind( $x,$y );
		if ( $nFound == ITEM_ERROR )
		{
			$ItemImage = DEFAULT_FILE_ITEMIMAGE;
			$ItemTitle = "ไม่มีไอเทมในช่องนี้";
		}else{
			$nFound2 = ITEM_ERROR;
			$ItemTitle = sprintf( "รหัสไอเทมคือ : IN_%03d_%03d ไม่พบข้อมูลไอเทมชิ้นนี้",$ItemMain[$n],$ItemSub[$n] );
			for( $n = 0 ; $n <= $i ; $n ++ )
			{
				$nFound2 = $cNeoChaInven->FindItem( $ItemMain[$n],$ItemSub[$n] );
				if (  $nFound2 != ITEM_ERROR )
				{
					$ItemNum = $ItemNum[$n];
					$ItemImage = $ItemImage[$n];
					$ItemTitle = sprintf( "ชื่อไอเทม %s ราคา %d ภาษี %d",$ItemName[$n],$ItemPrice[$n],$Item_Resell_Percent[$n] );
					break;
				}
			}
		}
		printf( "<TD><a href='#item' onclick='resell_item(%d);' title='%s'><img src='%s%s' border=0 width=59 height=59></a></TD>"
				,$ItemNum,$ItemTitle,PATH_UPLOAD_ITEMIMAGE,$ItemImage );
	}
	echo "</TR>";
}
echo '</table>';
*/
?>
    
    </td>
  </tr>
  <tr>
    <td align="center">&nbsp;</td>
  </tr>
</table>
<?php

?>
