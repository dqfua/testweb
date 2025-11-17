<?php
//include("logon.php");
//$bLogin = CGlobal::GetSesUserLogin();
//$cUser = unserialize( CGlobal::GetSesUser() );

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}

$UserNum = $cUser->GetUserNum();

CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nSubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nSubmit == 1 )
{
	$chanum = CInput::GetInstance()->GetValueInt( "chanum" , IN_POST );
	$cChar = new CNeoCha;
	$cChar->Login( $chanum,$UserNum );
	$ChaNum = $cChar->GetChaNum();
	if ( $ChaNum > 0 )
	{
		echo "ยินดีต้อนรับเข้าสู่ระบบ";
		//CGlobal::SetSes( CGlobal::GetSesChaManLogin() , ONLINE );
		//CGlobal::SetSes( CGlobal::GetSesChaMan() , serialize( $cChar ) );
		CChaOnline::OnlineSet( $cChar );
	}else{
		echo "ไม่พบข้อมูลตัวละคร";
	}
	exit;
}

$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );
$cWeb->GetSysmFromDB( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cSkillSet = new CSkillSet;
$cSkillSet->LoadSkillData( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] );

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
$szTemp = sprintf( " SELECT ChaNum,ChaName,ChaSchool,ChaLevel,ChaClass,ChaReborn FROM ChaInfo WHERE UserNum = %d AND ChaDeleted = 0 ",$UserNum );
$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$ChaNum = $cNeoSQLConnectODBC->Result("ChaNum",ODBC_RETYPE_INT);
	$ChaName = $cNeoSQLConnectODBC->Result("ChaName",ODBC_RETYPE_THAI);
	$ChaSchool = $cNeoSQLConnectODBC->Result("ChaSchool",ODBC_RETYPE_INT);
	$ChaLevel = $cNeoSQLConnectODBC->Result("ChaLevel",ODBC_RETYPE_INT);
	$ChaClass = $cNeoSQLConnectODBC->Result("ChaClass",ODBC_RETYPE_INT);
        $ChaReborn = $cNeoSQLConnectODBC->Result("ChaReborn",ODBC_RETYPE_INT);
?>
<table width="600" border="0" align="center" cellpadding="5" cellspacing="5" bgcolor="#000000">
  <tr>
    <td width="200" align="left" valign="top"><img src="<?php echo PATH_UPLOAD_IMAGECLASS . $_CONFIG["IMG_CHACLASS"][$ChaClass];?>" width=200 height=250 border=0 /></td>
    <td width="400" align="left" valign="top"><table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td colspan="2"><strong>ข้อมูลตัวละคร</strong></td>
        </tr>
      <tr>
        <td width="34%">ชื่อตัวละคร</td>
        <td width="66%"><input type=text style="width:150px;" value="<?php echo $ChaName; ?>" readonly="readonly" /></td>
        </tr>
      <tr>
        <td>เลเวล</td>
        <td><input type="text" style="width:150px;" value=<?php echo $ChaLevel; ?> readonly="readonly" /></td>
        </tr>
<?php
if ( $cWeb->GetSys_CharReborn() )
{
?>
      <tr>
        <td>จุติ</td>
        <td><input type="text" style="width:150px;" value="<?php echo $ChaReborn; ?>" readonly="readonly" /></td>
        </tr>
<?php
}
?>
      <tr>
        <td><p>โรงเรียน</p></td>
        <td><input type="text" style="width:150px;" value="<?php echo $_CONFIG["SCHOOL"][$ChaSchool]; ?>" readonly="readonly" /></td>
        </tr>
      <tr>
        <td>อาชีพ</td>
        <td><input type="text" style="width:150px;" value=<?php echo $_CONFIG["CHACLASS"][$ChaClass]; ?> readonly="readonly" /></td>
        </tr>
      <tr>
        <td colspan="2" align="right">

        <TABLE BORDER="0" align="right" style="margin-right:39px;">
            <TR><TD>
                <a href="#" onclick="chalogin(<?php echo $ChaNum; ?>);">
                <table border="0" cellpadding="0" cellspacing="0">
                        <tr>
                        <td background="../images/button/free.png" width="64" height="47" align="center" valign="middle">
                        เลือกตัวนี้
                        </td>
                    </tr>
                </table>
                </a>
                </TD>
<?php
$BB_Images = "free2.png";
$BB_Script = "alert('ระบบนี้ไม่เปิดใช้งาน');";

if ( $cWeb->Sys_ChaRebornGetPoint_Lv > 0 )
{
    if ( $cWeb->Sys_ChaRebornGetPoint_Lv == $ChaReborn )
    {
        if ( $ChaLevel == $cWeb->GetSys_CharRebornLevCheck() )
        {
            //$BB_Images = "free.png";
            $bCan = false;
            $cNeoSQLConnectODBC->ConnectRanWeb();
            $szTemp = sprintf("SELECT LogNum FROM Log_ChaReborn WHERE MemNum = %d AND UserNum = %d AND ChaNum = %d"
                    , $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]
                    , $UserNum
                    , $ChaNum
                    );
            $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
            while( $cNeoSQLConnectODBC->FetchRow() )
            {
                $bCan = true;
            }
            $cNeoSQLConnectODBC->CloseRanGame();
            if ( $bCan )
            {
                $BB_Script = "alert('คุณได้รับรางวัลไปแล้ว');";
            }else{
                $BB_Script = sprintf("loadpage('reborngetfreepoint','area_getrebornfreepoint','chanum=%d');",$ChaNum);
                $BB_Images = "free.png";
            }
        }else{
            $BB_Script = "alert('จุติของคุณยังไม่สามารถใช้งานได้');";
        }
    }else{
        $BB_Script = "alert('จุติของคุณยังไม่สามารถใช้งานได้');";
    }
}
?>
            
            <TD>
            <div id="area_getrebornfreepoint">
            <a href="#" onclick="<?php echo $BB_Script; ?>">
            <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td background="../images/button/<?php echo $BB_Images; ?>" width="64" height="47" align="center" valign="middle">
                    รับพ้อย
                    </td>
                </tr>
            </table>
            
            </a>
            </div>
            </TD>
            <TD><div id="area_skillset_<?php echo $ChaNum; ?>">
            <?php
			if ( $cSkillSet->SkillPoint == 0 )
	            $BB_Script = sprintf( "if ( confirmText( 'คุณต้องการรับสกิวหรือไม่' ) ) loadpage('setskillset','area_skillset_%d','chanum=%d');",$ChaNum,$ChaNum );
             else
				 $BB_Script = sprintf( "if ( confirmText( 'รับสกิวจำเป็นต้องใช้พ้อยจำนวน %d คุณต้องการรับสกิวหรือไม่' ) ) loadpage('setskillset','area_skillset_%d','chanum=%d');" , $cSkillSet->SkillPoint,$ChaNum,$ChaNum );
            $BB_Images = "free.png";
            if ( $cSkillSet->SkillSetOpen == 1 ){
			?>
            <a href="#" onclick="<?php echo $BB_Script; ?>">
            <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td background="../images/button/<?php echo $BB_Images; ?>" width="64" height="47" align="center" valign="middle">
                    รับสกิว
                    </td>
                </tr>
            </table>
            </a>
            <?php } ?>
            </div></TD>
<?php
if ( $cWeb->GetSys_ChaDelete() > 0 )
{
?>
            <TD>
            <div id="">
            <a href="#" onclick="chaDelete(<?php echo $ChaNum; ?>);">
            <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <td background="../images/button/<?php echo $BB_Images; ?>" width="64" height="47" align="center" valign="middle">
                    ลบตัวละคร
                    </td>
                </tr>
            </table>
            </a>
            </div>
            </TD>
<?php
}
?>
            </TR>
        </TABLE>
            
        </td>
        </tr>
    </table></td>
  </tr>
  </table>
<?php
}
$cNeoSQLConnectODBC->CloseRanGame();
?>