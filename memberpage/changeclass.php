<?php
//include("logon.php");
function isChangeEnable( $cMemClass , $ChaClass )
{
	return ( $cMemClass->Class1_On == false && $ChaClass == 1 ) ||
			  ( $cMemClass->Class2_On == false && $ChaClass == 2 ) ||
			  ( $cMemClass->Class4_On == false && $ChaClass == 4 ) ||
			  ( $cMemClass->Class8_On == false && $ChaClass == 8 ) ||
			  ( $cMemClass->Class16_On == false && $ChaClass == 16 ) ||
			  ( $cMemClass->Class32_On == false && $ChaClass == 32 ) ||
			  ( $cMemClass->Class64_On == false && $ChaClass == 64 ) ||
			  ( $cMemClass->Class128_On == false && $ChaClass == 128 ) ||
			  ( $cMemClass->Class256_On == false && $ChaClass == 256 ) ||
			  ( $cMemClass->Class512_On == false && $ChaClass == 512 ) ||
			  ( $cMemClass->Class1024_On == false && $ChaClass == 1024 ) ||
			  ( $cMemClass->Class2048_On == false && $ChaClass == 2048 ) ||
			  ( $cMemClass->Class4096_On == false && $ChaClass == 4096 ) ||
			  ( $cMemClass->Class8192_On == false && $ChaClass == 8192 );
}

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
$pCha = NULL;
if ( CChaOnline::OnlineGoodCheck( $pCha ) != ONLINE )
{
    die( sprintf("<font color='red'><b>กรุณาเลือกตัวละครก่อนใช้งานระบบนี้</b></font>") );
}

if ( !CGlobal::CheckLogOn( CGlobal::GetSesUser() ) )
{
	die("<div align=center><font color=red><b>กรุณาออกจากเกมส์ก่อนใช้งานระบบนี้!!</b></font></div>");
}
//$cUser = unserialize( CGlobal::GetSesUser() );

//update user from userinfo db
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
//CGlobal::SetSesUser( serialize($cUser) );
//$cUser = unserialize( CGlobal::GetSesUser() );
COnline::OnlineSet( $cUser );

$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;

$cMemClass = new CMemClass;
$cMemClass->LoadData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nsubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );

if ( $nsubmit )
{
	if ( !CSec::Check() ) exit;
	$password = CInput::GetInstance()->GetValueString( "pass" , IN_POST );
	$class = CInput::GetInstance()->GetValueInt( "class" , IN_POST );
	if ( isChangeEnable( $cMemClass , $ChaClass ) ) die("อาชีพนี้ไม่อนุญาติให้เปลี่ยนอาชีพ");
	if ( !CGlobal::CheckStrManLen( $password ) )
	{
		die( "รหัสเข้าเกมส์จะต้องมากกว่า 4 ตัวและน้อยกว่า 16<br>" );
	}
	$cWeb = new CNeoWeb;
	$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $_CONFIG["PASSWORD_MD5"][$cWeb->GetServerType()] )
	{
		CGlobal::SetPassMD5( $password , true );
	}
	if ( strcmp( $cUser->GetUserPass() , $password ) != 0 )
	{
		die("รหัสผ่านเกาไม่ถูกต้อง<br>");
	}
	 if ( $cWeb->GetServerType() == SERVTYPE_EP3 )
	 {
		if ( $class == 1 || $class == 2 || $class == 4 || $class == 8 ){}else
		{
			die("กรุณาเลือกอาขีพให้ถูกต้อง<br>");
		}
	 }else{
		if ( $class == 1 || $class == 2 || $class == 4 || $class == 8 || $class == 16 || $class == 32 || $class == 64 || $class == 128 || $class == 256 || $class == 512 || $class == 1024 || $class == 2048 || $class == 4096 || $class == 8192 ){}else
		{
			die("กรุณาเลือกอาขีพให้ถูกต้อง<br>");
		}
	 }
	if ( !$cWeb->GetSys_Class() )
	die("ระบบนี้ยังไม่เปิดให้บริการ");
		
	$UserPoint = $cUser->GetUserPoint();
	$UsePoint = $cWeb->GetSys_Class_P();
	$NewPoint = $UserPoint-$UsePoint;
	if ( $NewPoint >= 0 ) {} else{ die("พ้อยของคุณไม่เพียงพอที่จะทำรายการ<br>"); }
	$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
	if ( $bChaLogin )
	{
		echo"<div align=center><font size=+1><b>ตัวละครที่ทำรายการคือ</b></font></div><br>";
		include("chainfo.php");
	}else{
		die("กรุณาเลือกตัวละครก่อนทำรายการ<br>");
	}
	if (  !$pCha->GetNowOnline() )
	{
		die("ไม่สามารถทำรายการได้เนื่องจากตัวละครออนไลน์อยู่<br>");
	}
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
	
	$szTemp = sprintf(" SELECT ChaNum , ChaName , ChaLevel , ChaStRemain , ChaDex  , ChaPower  , ChaSpirit  , ChaStrong  , ChaStrength , ChaClass FROM ChaInfo WHERE UserNum = %d AND ChaNum = %d AND ChaDeleted = 0 AND ChaOnline = 0 ",$cUser->GetUserNum(),$pCha->GetChaNum());
	$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
	
	$GetChaNum = $cNeoSQLConnectODBC->Result( "ChaNum",ODBC_RETYPE_INT );
	$oldclass =  $cNeoSQLConnectODBC->Result( "ChaClass",ODBC_RETYPE_INT );
	
	$CLevel =  $cNeoSQLConnectODBC->Result( "ChaLevel",ODBC_RETYPE_INT );
	$ChaStRemain =  $cNeoSQLConnectODBC->Result( "ChaStRemain",ODBC_RETYPE_INT );
	$ChaDex =  $cNeoSQLConnectODBC->Result( "ChaDex",ODBC_RETYPE_INT );
	$ChaPower =  $cNeoSQLConnectODBC->Result( "ChaPower",ODBC_RETYPE_INT );
	$ChaSpirit =  $cNeoSQLConnectODBC->Result( "ChaSpirit",ODBC_RETYPE_INT );
	$ChaStrong =  $cNeoSQLConnectODBC->Result( "ChaStrong",ODBC_RETYPE_INT );
	$ChaStrength =  $cNeoSQLConnectODBC->Result( "ChaStrength",ODBC_RETYPE_INT );
	
	$error = 0;
	/*
	$pChaInfo = new ChaInfo();
	$pChaInfo->ChaNum = $pCha->GetChaNum();
	$pChaInfo->BuildNeoChaPutOnItems( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
	if ( $pChaInfo->NeoChaPutOnItems->GetItemNum() != 0 )
	{
		printf( "<font color='red'><b>ไม่สามารถทำรายการได้ จำเป็นต้องถอดของทุกชิ้นที่สวมใส่อยู่ออกให้หมดก่อน</b></font><br>" );
		$error++;
	}
	*/
	$cChaSkill = new CNeoChaSkill;
	$cChaSkill->LoadChaSkill( $pCha->GetChaNum() );
	
	if ( $cWeb->GetSys_Class_Change_CheckStat() || $cWeb->GetSys_Class_Change_CheckSkill() ) {
		if ( $cWeb->GetSys_Class_Change_CheckStat() )
			if ( $ChaDex != 0 || $ChaStrength != 0 || $ChaStrong != 0 || $ChaSpirit != 0 || $ChaPower != 0 )
			{
				printf( "<font color='red'><b>ไม่สามารถเปลี่ยนอาชีพได้กรุณารีสเตตัสให้หมดก่อน (ตัวอย่างไอเทมที่สามารถรีได้คือ น้ำยาลบความทรงจำ E (ปล.น้ำยาลบความทรงจำ E สามารถรีได้ทั้งสเตตัสและสกิว) )</b></font><br>" );
				$error++;
			}
		if ( $cWeb->GetSys_Class_Change_CheckSkill() )
			if ( $cChaSkill->SkillNum != 0 )
			{
				printf( "<font color='red'><b>ไม่สามารถเปลี่ยนอาชีพได้กรุณารีสกิวให้หมดก่อน (ตัวอย่างไอเทมที่สามารถรีได้คือ น้ำยาลบความทรงจำ A (ปล.น้ำยาลบความทรงจำ A สามารถรีได้แต่สกิวเท่านั้น) )</b></font><br>" );
				$error++;
			}
	}
	
	if ( $error == 0 )
	{
	
	$CStatus = $ChaStRemain+$ChaDex+$ChaPower+$ChaSpirit+$ChaStrong+$ChaStrength;
	
	$SkillPoint = $cWeb->GetSys_Cha_SkillPointBegin();
	$SkillPoint += ( $CLevel*$cWeb->GetSys_Cha_SkillPoint() ) - $cWeb->GetSys_Cha_SkillPoint();
	
	/*
	$szTemp = sprintf("UPDATE ChaInfo SET
					  ChaClass = %d , ChaSex = %d , ChaSkills = NULL , ChaSkillSlot = NULL
					  , ChaSkillPoint = %d , ChaStRemain = %d
					  , ChaDex = 0 , ChaPower = 0 , ChaSpirit = 0 , ChaStrong = 0 , ChaStrength = 0
					  WHERE ChaNum = %d AND UserNum = %d"
					  ,$class,$SetSex,$SkillPoint,$CStatus,$pCha->GetChaNum(),$cUser->GetUserNum() );
	*/
	/*
	$szTemp = sprintf("UPDATE ChaInfo SET
					  ChaClass = %d , ChaSkills = NULL , ChaSkillSlot = NULL
					  , ChaSkillPoint = %d , ChaStRemain = %d
					  , ChaDex = 0 , ChaPower = 0 , ChaSpirit = 0 , ChaStrong = 0 , ChaStrength = 0
					  WHERE ChaNum = %d AND UserNum = %d"
					  ,$class,$SkillPoint,$CStatus,$pCha->GetChaNum(),$cUser->GetUserNum() );
	*/
	$szTemp = sprintf("UPDATE ChaInfo SET
					  ChaClass = %d
					  ,ChaSkills = NULL
					  ,ChaSkillSlot = NULL
					  ,ChaActionSlot = NULL
					  WHERE ChaNum = %d AND UserNum = %d"
					  ,$class,$pCha->GetChaNum(),$cUser->GetUserNum() );
        //printf("%s<br>",$szTemp);
	$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
	
	$pCha->Login( $pCha->GetChaNum(),$cUser->GetUserNum() );
	
	//CGlobal::SetSes( CGlobal::GetSesChaMan() , serialize( $pCha ) );
	CChaOnline::OnlineSet( $pCha );
	
	$cWeb = new CNeoWeb;
	//$cUser->SetUserPoint( $NewPoint );
	//$cUser->UpdateUserPointToDB();
	$cUser->DownPoint( $UsePoint );
	
	//CGlobal::SetSesUser( serialize( $cUser ) );
	COnline::OnlineSet( $cUser );
	
	CNeoLog::LogChangeClass( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $UserNum , $pCha->GetChaNum() , $oldclass ,$class  ,$UserPoint , $cUser->GetUserPoint() );
        
        printf("<font color='#00FF00'>เปลี่ยนอาชีพสำเร็จ</font>");
	
	}
	
	$cNeoSQLConnectODBC->CloseRanGame();
	
	//die("เปลี่ยนอาชีพสำเร็จ<br>");
        exit;
}
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="100%" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2" align="center"><strong>เปลี่ยนอาชีพ<br /></strong>
<?php
$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
if ( $bChaLogin )
{
	echo"<div align=center><font size=+1><b>ตัวละครที่คุณเลือกอยู่ในปัจจุบัน</b></font></div><br>";
	include("chainfo.php");
}
else
die("กรุณาเลือกตัวละครก่อนใช้งานระบบนี้<br>");
?>
<br />
<?php
CSec::Begin();
$cWeb = new CNeoWeb;
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
if ( !$cWeb->GetSys_Class() )
die("ระบบนี้ยังไม่เปิดให้บริการ");
$UserPoint = $cUser->GetUserPoint();
$UsePoint = $cWeb->GetSys_Class_P();
$NewPoint = $UserPoint-$UsePoint;
$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $MemNum );
$cWeb->GetSysmFromDB( $MemNum );
?>
<font color="#FF0000" size="+1">

<?php
$bNoReset = false;
if ( !$cWeb->GetSys_Class_Change_CheckStat() && !$cWeb->GetSys_Class_Change_CheckSkill() ) {
?>
<b>**หมายเหตุ**</b> ระบบเปลี่ยนอาชีพไม่รีสเตตัสให้และก็ไม่ได้รับแต้มสกิวคืน ก่อนรีกรุณาอ่านและทำความเข้าใจให้ดีก่อน
<?php
}else{
if ( $cWeb->GetSys_Class_Change_CheckStat() && $cWeb->GetSys_Class_Change_CheckSkill() )
{
    $bNoReset = true;
	printf("<b>*หมายเหตุ*</b> คุณจะต้องรีสเตตัสและสกิวให้หมดจึงจะสามารถกดเปลี่ยนอาชีพ<br>");
	printf("<b>*แนะนำ<b> ให้ใช้น้ำยาลบความจำ E เพื่อรีสเตตัสและสกิว <br>");
}else{
if ( $cWeb->GetSys_Class_Change_CheckStat() ){
    $bNoReset = true;
?>
<b>**หมายเหตุ**</b> คุณจะต้องรีสเตตัสให้หมดสักก่อนถึงจะสามารถเปลี่ยนอาชีพได้<br>
<?php
}else if ( $cWeb->GetSys_Class_Change_CheckSkill() ){
    $bNoReset = true;
?>
<b>**หมายเหตุ**</b> คุณจะต้องรีสกิวให้หมดสักก่อนถึงจะสามารถเปลี่ยนอาชีพได้<br>
<?php
}
}
}
if ( $bNoReset )
{
    printf( "PS.การเปลี่ยนอาชีพนี้จะไม่รีสเตตัสหรือสกิวให้เพราะเช่นนั้นคุณต้องการเอง!!<br>" );
}

?>

</font>

<br />

พ้อยที่จำเป็นต้องใช้<br />
<?php
printf("จำนวนพ้อยที่ใช้ในการเปลี่ยนคือ %d พ้อยที่คุณมีมือ %d พ้อยที่เหลือหลังจากเปลี่ยนแล้วคือ %d",$UsePoint,$UserPoint,$NewPoint);
?>
        </td>
      </tr>
      <tr>
        <td width="34%">ไอดี</td>
        <td width="66%"><label>
          <input name="id" type="text" id="id" readonly value="<?php echo $UserID; ?>" />
          </label></td>
      </tr>
      <tr>
        <td>รหัสเข้าเกมส์</td>
        <td><input type="password" name="pass" id="pass" /></td>
      </tr>
      <tr>
        <td>เลือกตัวละคร</td>
        <td><select name="character" id="character">
        <option value="<?php echo $ChaNum; ?>"><?php echo $ChaName; ?></option>
</select></td>
      </tr>
      <tr>
        <td>เลือกโรงเรียน</td>
        <td><label>
          <select name="select" id="select">
          <option value="-1" selected="selected">เลือกอาชีพ</option>
          <?php
		  if ( isChangeEnable( $cMemClass , $ChaClass ) )
		  {}else
		  {
			  if ( $cWeb->GetServerType() == SERVTYPE_EP3 )
			  {
				  if ( $ChaClass != 1 )
				  echo '<option value="1">นักหมัดชาย</option>';
				  if ( $ChaClass != 2 )
				  echo '<option value="2">นักดาบชาย</option>';
				  if ( $ChaClass != 4 )
				  echo '<option value="4">นักธนูหญิง</option>';
				  if ( $ChaClass != 8 )
				  echo '<option value="8">นักเวทย์หญิง</option>';
			  }else{
				if ( $ChaClass != 1 && $cMemClass->Class1_On )
				  printf('<option value="1">%s</option>',$cMemClass->Class1_Name);
					if ( $ChaClass != 64 && $cMemClass->Class64_On )
				  printf('<option value="64">%s</option>',$cMemClass->Class64_Name);
				  if ( $ChaClass != 2 && $cMemClass->Class2_On )
				  printf('<option value="2">%s</option>',$cMemClass->Class2_Name);
				  if ( $ChaClass != 128 && $cMemClass->Class128_On )
				  printf('<option value="128">%s</option>',$cMemClass->Class128_Name);
				  if ( $ChaClass != 256 && $cMemClass->Class256_On )
				  printf('<option value="256">%s</option>',$cMemClass->Class256_Name);
				  if ( $ChaClass != 4 && $cMemClass->Class4_On )
				  printf('<option value="4">%s</option>',$cMemClass->Class4_Name);
				  if ( $ChaClass != 512 && $cMemClass->Class512_On )
				  printf('<option value="512">%s</option>',$cMemClass->Class512_Name);
				  if ( $ChaClass != 8 && $cMemClass->Class8_On )
				  printf('<option value="8">%s</option>',$cMemClass->Class8_Name);
				  if ( $ChaClass != 16 && $cMemClass->Class16_On )
				  printf('<option value="16">%s</option>',$cMemClass->Class16_Name);
				  if ( $ChaClass != 32 && $cMemClass->Class32_On )
				  printf('<option value="32">%s</option>',$cMemClass->Class32_Name);
				  if ( $ChaClass != 1024 && $cMemClass->Class1024_On )
				  printf('<option value="1024">%s</option>',$cMemClass->Class1024_Name);
				  if ( $ChaClass != 2048 && $cMemClass->Class2048_On )
				  printf('<option value="2048">%s</option>',$cMemClass->Class2048_Name);
				  if ( $ChaClass != 4096 && $cMemClass->Class4096_On )
				  printf('<option value="4096">%s</option>',$cMemClass->Class4096_Name);
				  if ( $ChaClass != 8192 && $cMemClass->Class8192_On )
				  printf('<option value="8192">%s</option>',$cMemClass->Class8192_Name);
			}
		  }
		  ?>
          </select>
        </label> <font color="#FF0000">* ไม่แสดงอาชีพตัวละครเป็นอยู่ในปัจจุบัน</font></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><a href="#c" onclick="<?php if ( $NewPoint >= 0 ) echo "if ( confirmText('คุณต้องการทำรายการแน่นอนหรือไม่') )
		if ( $('#select').val() == 1 || $('#select').val() == 2  || $('#select').val() == 4  || $('#select').val() == 8  || $('#select').val() == 16  || $('#select').val() == 32  || $('#select').val() == 64  || $('#select').val() == 128  || $('#select').val() == 256  || $('#select').val() == 512  || $('#select').val() == 1024  || $('#select').val() == 2048  || $('#select').val() == 4096  || $('#select').val() == 8192 ) changeclass(); else alert('กรุณาเลือกอาชีพที่ต้องการทำรายการ');"; else echo "alert('พ้อยของคุณไม่เพียงพอที่จะทำการรายกรุณาเติมพ้อย');"; ?>">
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td background="../images/button/<?php if ( $NewPoint >= 0 ) echo "free.png"; else echo"free2.png"; ?>" width="64" height="47" align="center" valign="middle">เปลี่ยน</td>
              </tr>
            </table>
          </a></td>
      </tr>
    </table></td>
  </tr>
</table>
