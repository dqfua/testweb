<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

$arrON = array( "OFF" , "ON" );
$arrONP = array( "YES" , "NO" );

function CMD_PROCESS_P0()
{
    global $CURRENT_SESSION;
    global $pSetData;
    global $_CONFIG;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $clear = CInput::GetInstance()->GetValueInt( "clear" , IN_POST );
    if ( $clear )
    {
        $szTemp = sprintf( "DELETE LoginFist WHERE MemNum = %d" , $MemNum );
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        die("SUCCESS:WORK");
    }
    
    $Cha_StatsPointBegin = CInput::GetInstance()->GetValueInt( "Cha_StatsPointBegin" , IN_POST );
    $Cha_StatsPoint = CInput::GetInstance()->GetValueInt( "Cha_StatsPoint" , IN_POST );
    $Cha_SkillPointBegin = CInput::GetInstance()->GetValueInt( "Cha_SkillPointBegin" , IN_POST );
    $Cha_SkillPoint = CInput::GetInstance()->GetValueInt( "Cha_SkillPoint" , IN_POST );
    $Login_PointFirst = CInput::GetInstance()->GetValueInt( "Login_PointFirst" , IN_POST );
    $PassMD5 = CInput::GetInstance()->GetValueInt( "PassMD5" , IN_POST );
    $ParentID = CInput::GetInstance()->GetValueInt( "ParentID" , IN_POST );
    $BonusPoint = CInput::GetInstance()->GetValueInt( "BonusPoint" , IN_POST );
    $BonusPointEx = CInput::GetInstance()->GetValueInt( "BonusPointEx" , IN_POST );
	
    CheckNumZero( $Cha_StatsPointBegin );
    CheckNumZero( $Cha_StatsPoint );
    CheckNumZero( $Cha_SkillPointBegin );
    CheckNumZero( $Cha_SkillPoint );
    CheckNumZero( $Login_PointFirst );
    if ( $PassMD5 != 0 && $PassMD5 != 1 ) $PassMD5 = 0;
    if ( $ParentID != 0 && $ParentID != 1 ) $ParentID = 0;
    if ( $BonusPoint != 0 && $BonusPoint != 1 ) $BonusPoint = 0;
    if ( $BonusPointEx != 0 && $BonusPointEx != 1 ) $BonusPointEx = 0;
    
    $pSetData->Cha_StatsPointBegin = $Cha_StatsPointBegin;
    $pSetData->Cha_StatsPoint = $Cha_StatsPoint;
    $pSetData->Cha_SkillPointBegin = $Cha_SkillPointBegin;
    $pSetData->Cha_SkillPoint = $Cha_SkillPoint;
    $pSetData->Login_PointFirst = $Login_PointFirst;
    $pSetData->ParentID = $ParentID;
    $pSetData->BonusPoint = $BonusPoint;
    $pSetData->BonusPointEx = $BonusPointEx;
    
    $pSetData->Update2DB( $MemNum );
    
    $cAdmin->PassMD5 = $PassMD5;
    $cAdmin->UpdatePassMD5();
    
    CInput::GetInstance()->AddValue( $_CONFIG["ADM"]["SESSION"] , serialize( $cAdmin ) , IN_SESSION );
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSetData ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS:WORK";
}

function CMD_UI_P0()
{
    global $pSetData;
    global $cAdmin;
    global $page;
?>
<table class="gridtable">
    <tr>
        <td colspan="2"><b><u>เกี่ยวกับตัวละคร</u></b></td>
    </tr>
    <tr>
        <td style="width:309px;">แต้มสเตตัสเริ่มต้นของตัวละคร</td>
        <td style="width:159px;"><input type="text" id="Cha_StatsPointBegin" value="<?php echo $pSetData->Cha_StatsPointBegin; ?>" style="width:39px"></td>
    </tr>
    <tr>
        <td>แต้มสเตตัสต่อเลเวลของตัวละคร</td>
        <td><input type="text" id="Cha_StatsPoint" value="<?php echo $pSetData->Cha_StatsPoint; ?>" style="width:39px"></td>
    </tr>
    <tr>
        <td>แต้มสกิลเริ่มต้นของตัวละคร</td>
        <td><input type="text" id="Cha_SkillPointBegin" value="<?php echo $pSetData->Cha_SkillPointBegin; ?>" style="width:39px"></td>
    </tr>
    <tr>
        <td>แต้มสกิลต่อเลเวลของตัวละคร</td>
        <td><input type="text" id="Cha_SkillPoint" value="<?php echo $pSetData->Cha_SkillPoint; ?>" style="width:39px"></td>
    </tr>
    <tr>
        <td colspan="2"><b><u>ทั่วไป</u></b></td>
    </tr>
    <tr>
        <td>พ้อยล็อกอินครั้งแรก</td>
        <td><input type="text" id="Login_PointFirst" value="<?php echo $pSetData->Login_PointFirst; ?>" style="width:39px"><button onclick="aPage1(this);">เคลียร์</button><a href="">(?)</a></td>
    </tr>
    <tr>
        <td>เข้ารหัสผ่านด้วย MD5</td>
        <td>
        <?php
        $onff = array( "OFF" , "ON" );
        echo buildSelectText("PassMD5", "PassMD5", $cAdmin->PassMD5, $onff)
        ?>
        </td>
    </tr>
    <tr>
        <td>เปิดให้ใช้ระบบ Invite Friends</td>
        <td>
        <?php
        $onff = array( "OFF" , "ON" );
        echo buildSelectText("ParentID", "ParentID", $pSetData->ParentID, $onff)
        ?>
        </td>
    </tr>
    <tr>
        <td>เปิดให้ใช้ระบบ เติมพ้อยเก็บสะสมแต้ม</td>
        <td>
        <?php
        $onff = array( "OFF" , "ON" );
        echo buildSelectText("BonusPoint", "BonusPoint", $pSetData->BonusPoint, $onff)
        ?>
        <input type="checkbox" id="BonusPointEx" value="1" <?php if ( $pSetData->BonusPointEx ) echo "checked"; ?>> ต้องการใช้บัตรในการเติมแต้มอย่างเดียว<br>
        </td>
    </tr>
    <tr>
        <td colspan="2"><div align='right'><button onclick="savePage('<?php echo $page; ?>');">บันทึก</button></div></td>
    </tr>
</table>
<?php
}

function CMD_PROCESS_P1()
{
    global $arrON;
    global $CURRENT_SESSION;
    global $pSetData;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $School = CInput::GetInstance()->GetValueInt( "School" , IN_POST );
    $School_P = CInput::GetInstance()->GetValueInt( "School_P" , IN_POST );
    $CharMad = CInput::GetInstance()->GetValueInt( "CharMad" , IN_POST );
    $CharMad_P = CInput::GetInstance()->GetValueInt( "CharMad_P" , IN_POST );
    $SkillOn = CInput::GetInstance()->GetValueInt( "SkillOn" , IN_POST );
    $Online2Point = CInput::GetInstance()->GetValueInt( "Online2Point" , IN_POST );
    $OnlineGetPoint = CInput::GetInstance()->GetValueInt( "OnlineGetPoint" , IN_POST );
    $OnlineTime = CInput::GetInstance()->GetValueInt( "OnlineTime" , IN_POST );
    $StatOn = CInput::GetInstance()->GetValueInt( "StatOn" , IN_POST );
    $StatPoint = CInput::GetInstance()->GetValueInt( "StatPoint" , IN_POST );
    $ResetSkill = CInput::GetInstance()->GetValueInt( "ResetSkill" , IN_POST );
    $ResetSkillPoint = CInput::GetInstance()->GetValueInt( "ResetSkillPoint" , IN_POST );
    $ChangeName = CInput::GetInstance()->GetValueInt( "ChangeName" , IN_POST );
    $ChangeNamePoint = CInput::GetInstance()->GetValueInt( "ChangeNamePoint" , IN_POST );
    $cMemBuySkillPointModeOn = CInput::GetInstance()->GetValueInt( "cMemBuySkillPointModeOn" , IN_POST );
    $cMemBuySkillPointSkillPoint = CInput::GetInstance()->GetValueInt( "cMemBuySkillPointSkillPoint" , IN_POST );
    $cMemBuySkillPointUsePoint = CInput::GetInstance()->GetValueInt( "cMemBuySkillPointUsePoint" , IN_POST );
    $ChaDelete = CInput::GetInstance()->GetValueInt( "ChaDelete" , IN_POST );

    if ( !arrkeycheck($arrON, $School) ) $School = 0;
    if ( !arrkeycheck($arrON, $CharMad) ) $CharMad = 0;
    if ( !arrkeycheck($arrON, $SkillOn) ) $SkillOn = 0;
    if ( !arrkeycheck($arrON, $Online2Point) ) $Online2Point = 0;
    if ( !arrkeycheck($arrON, $StatOn) ) $StatOn = 0;
    if ( !arrkeycheck($arrON, $ResetSkill) ) $ResetSkill = 0;
    if ( !arrkeycheck($arrON, $ChangeName) ) $ChangeName = 0;
    if ( !arrkeycheck($arrON, $cMemBuySkillPointModeOn) ) $cMemBuySkillPointModeOn = 0;
    if ( !arrkeycheck($arrON, $ChaDelete) ) $ChaDelete = 0;
    
    CheckNumZero($School_P);
    CheckNumZero($CharMad_P);
    CheckNumZero($School_P);
    CheckNumZero($OnlineGetPoint);
    CheckNumZero($StatOn);
    CheckNumZero($OnlineTime);
    CheckNumZero($StatPoint);
    CheckNumZero($ResetSkillPoint);
    CheckNumZero($ChangeNamePoint);
    CheckNumZero($cMemBuySkillPointSkillPoint);
    CheckNumZero($cMemBuySkillPointUsePoint);
    
    $pSetData->School = $School;
    $pSetData->School_P = $School_P;
    $pSetData->CharMad = $CharMad;
    $pSetData->CharMad_P = $CharMad_P;
    $pSetData->SkillOn = $SkillOn;
    $pSetData->Online2Point = $Online2Point;
    $pSetData->OnlineGetPoint = $OnlineGetPoint;
    $pSetData->OnlineTime = $OnlineTime;
    $pSetData->StatOn = $StatOn;
    $pSetData->StatPoint = $StatPoint;
    $pSetData->ResetSkill = $ResetSkill;
    $pSetData->ResetSkillPoint = $ResetSkillPoint;
    $pSetData->ChangeName = $ChangeName;
    $pSetData->ChangeNamePoint = $ChangeNamePoint;
    $pSetData->cMemBuySkillPoint->SkillPoint = $cMemBuySkillPointSkillPoint;
    $pSetData->cMemBuySkillPoint->UsePoint = $cMemBuySkillPointUsePoint;
    $pSetData->cMemBuySkillPoint->ModeOn = $cMemBuySkillPointModeOn;
    $pSetData->ChaDelete = $ChaDelete;
    
    $pSetData->Update2DB( $MemNum );
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSetData ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS:WORK";
}

function CMD_UI_P1()
{
    global $pSetData;
    global $arrON;
    global $page;
?>
<table class="gridtable">
    <tr>
        <td style="width:309px;">เปลี่ยนโรงเรียน</td>
        <td style="width:259px;">
<?php
echo buildSelectText( "School", "School", $pSetData->School, $arrON );
?>
            <input type="text" id="School_P" value="<?php echo $pSetData->School_P; ?>" style="width:39px">
        </td>
    </tr>
    <tr>
        <td>ระบบแก้หัวแดง</td>
        <td>
<?php
echo buildSelectText( "CharMad", "CharMad", $pSetData->CharMad, $arrON );
?>
            <input type="text" id="CharMad_P" value="<?php echo $pSetData->CharMad_P; ?>" style="width:39px">
        </td>
    </tr>
    <tr>
        <td>เมนูลดขั้นสกิล</td>
        <td>
<?php
echo buildSelectText( "SkillOn", "SkillOn", $pSetData->SkillOn, $arrON );
?>
        </td>
    </tr>
    <tr>
        <td>ออนไลน์ได้พ้อย</td>
        <td>
<?php
echo buildSelectText( "Online2Point", "Online2Point", $pSetData->Online2Point, $arrON );
?>
            <input type="text" id="OnlineTime" value="<?php echo $pSetData->OnlineTime; ?>" style="width:39px"> นาที ,
            <input type="text" id="OnlineGetPoint" value="<?php echo $pSetData->OnlineGetPoint; ?>" style="width:39px"> พ้อย
        </td>
    </tr>
    <tr>
        <td>เมนูปรับแต่งสเตตัส</td>
        <td>
<?php
echo buildSelectText( "StatOn", "StatOn", $pSetData->StatOn, $arrON );
?>
            <input type="text" id="StatPoint" value="<?php echo $pSetData->StatPoint; ?>" style="width:39px">
        </td>
    </tr>
    <tr>
        <td>เมนูปรับแต่งสกิล</td>
        <td>
<?php
echo buildSelectText( "ResetSkill", "ResetSkill", $pSetData->ResetSkill, $arrON );
?>
            <input type="text" id="ResetSkillPoint" value="<?php echo $pSetData->ResetSkillPoint; ?>" style="width:39px">
        </td>
    </tr>
    <tr>
        <td>เมนูแก้ไขชื่อตัวละคร</td>
        <td>
<?php
echo buildSelectText( "ChangeName", "ChangeName", $pSetData->ChangeName, $arrON );
?>
            <input type="text" id="ChangeNamePoint" value="<?php echo $pSetData->ChangeNamePoint; ?>" style="width:39px">
        </td>
    </tr>
    <tr>
        <td>เมนูชื่อแต้มสกิล</td>
        <td>
<?php
echo buildSelectText( "cMemBuySkillPointModeOn", "cMemBuySkillPointModeOn", $pSetData->cMemBuySkillPoint->ModeOn, $arrON );
?>
            <input type="text" id="cMemBuySkillPointUsePoint" value="<?php echo $pSetData->cMemBuySkillPoint->UsePoint; ?>" style="width:39px"> พ้อย ,
            <input type="text" id="cMemBuySkillPointSkillPoint" value="<?php echo $pSetData->cMemBuySkillPoint->SkillPoint; ?>" style="width:39px"> แต้มสกิล
        </td>
    </tr>
    <tr>
        <td>เมนูลบตัวละคร</td>
        <td>
<?php
echo buildSelectText( "ChaDelete", "ChaDelete", $pSetData->ChaDelete, $arrON );
?>
        </td>
    </tr>
    <tr>
        <td colspan="2"><div align='right'><button onclick="savePage('<?php echo $page; ?>');">บันทึก</button></div></td>
    </tr>
</table>
<?php
}

function CMD_PROCESS_P2()
{
    global $arrON;
    global $pSetData;
    global $CURRENT_SESSION;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $Class = CInput::GetInstance()->GetValueInt( "Class" , IN_POST );
    $Class_P = CInput::GetInstance()->GetValueInt( "Class_P" , IN_POST );
    $Class_Change_CheckStat = CInput::GetInstance()->GetValueInt( "Class_Change_CheckStat" , IN_POST );
    $Class_Change_CheckSkill = CInput::GetInstance()->GetValueInt( "Class_Change_CheckSkill" , IN_POST );
    
    $Class1_On = CInput::GetInstance()->GetValueInt( "Class1_On" , IN_POST );
    $Class1_Name = CInput::GetInstance()->GetValueString( "Class1_Name" , IN_POST );
    $Class64_On = CInput::GetInstance()->GetValueInt( "Class64_On" , IN_POST );
    $Class64_Name = CInput::GetInstance()->GetValueString( "Class64_Name" , IN_POST );
    $Class2_On = CInput::GetInstance()->GetValueInt( "Class2_On" , IN_POST );
    $Class2_Name = CInput::GetInstance()->GetValueString( "Class2_Name" , IN_POST );
    $Class128_On = CInput::GetInstance()->GetValueInt( "Class128_On" , IN_POST );
    $Class128_Name = CInput::GetInstance()->GetValueString( "Class128_Name" , IN_POST );
    $Class256_On = CInput::GetInstance()->GetValueInt( "Class256_On" , IN_POST );
    $Class256_Name = CInput::GetInstance()->GetValueString( "Class256_Name" , IN_POST );
    $Class4_On = CInput::GetInstance()->GetValueInt( "Class4_On" , IN_POST );
    $Class4_Name = CInput::GetInstance()->GetValueString( "Class4_Name" , IN_POST );
    $Class512_On = CInput::GetInstance()->GetValueInt( "Class512_On" , IN_POST );
    $Class512_Name = CInput::GetInstance()->GetValueString( "Class512_Name" , IN_POST );
    $Class8_On = CInput::GetInstance()->GetValueInt( "Class8_On" , IN_POST );
    $Class8_Name = CInput::GetInstance()->GetValueString( "Class8_Name" , IN_POST );
    $Class1024_On = CInput::GetInstance()->GetValueInt( "Class1024_On" , IN_POST );
    $Class1024_Name = CInput::GetInstance()->GetValueString( "Class1024_Name" , IN_POST );
    $Class2048_On = CInput::GetInstance()->GetValueInt( "Class2048_On" , IN_POST );
    $Class2048_Name = CInput::GetInstance()->GetValueString( "Class2048_Name" , IN_POST );
    $Class16_On = CInput::GetInstance()->GetValueInt( "Class16_On" , IN_POST );
    $Class16_Name = CInput::GetInstance()->GetValueString( "Class16_Name" , IN_POST );
    $Class32_On = CInput::GetInstance()->GetValueInt( "Class32_On" , IN_POST );
    $Class32_Name = CInput::GetInstance()->GetValueString( "Class32_Name" , IN_POST );
    $Class4096_On = CInput::GetInstance()->GetValueInt( "Class4096_On" , IN_POST );
    $Class4096_Name = CInput::GetInstance()->GetValueString( "Class4096_Name" , IN_POST );
    $Class8192_On = CInput::GetInstance()->GetValueInt( "Class8192_On" , IN_POST );
    $Class8192_Name = CInput::GetInstance()->GetValueString( "Class8192_Name" , IN_POST );
    
    if ( !arrkeycheck($arrON, $Class) ) $Class = 0;
    if ( !arrkeycheck($arrON, $Class_Change_CheckStat) ) $Class_Change_CheckStat = 0;
    if ( !arrkeycheck($arrON, $Class_Change_CheckSkill) ) $Class_Change_CheckSkill = 0;
    if ( !arrkeycheck($arrON, $Class1_On) ) $Class1_On = 0;
    if ( !arrkeycheck($arrON, $Class64_On) ) $Class64_On = 0;
    if ( !arrkeycheck($arrON, $Class2_On) ) $Class2_On = 0;
    if ( !arrkeycheck($arrON, $Class128_On) ) $Class128_On = 0;
    if ( !arrkeycheck($arrON, $Class256_On) ) $Class256_On = 0;
    if ( !arrkeycheck($arrON, $Class4_On) ) $Class4_On = 0;
    if ( !arrkeycheck($arrON, $Class512_On) ) $Class512_On = 0;
    if ( !arrkeycheck($arrON, $Class8_On) ) $Class8_On = 0;
    if ( !arrkeycheck($arrON, $Class1024_On) ) $Class1024_On = 0;
    if ( !arrkeycheck($arrON, $Class2048_On) ) $Class2048_On = 0;
    if ( !arrkeycheck($arrON, $Class16_On) ) $Class16_On = 0;
    if ( !arrkeycheck($arrON, $Class32_On) ) $Class32_On = 0;
    if ( !arrkeycheck($arrON, $Class4096_On) ) $Class4096_On = 0;
    if ( !arrkeycheck($arrON, $Class8192_On) ) $Class8192_On = 0;
    
    CheckNumZero($Class_P);
    
    $len = 16;
    
    $Class1_Name = CBinaryCover::utf8_to_tis620( $Class1_Name );
    $Class2_Name = CBinaryCover::utf8_to_tis620( $Class2_Name );
    $Class4_Name = CBinaryCover::utf8_to_tis620( $Class4_Name );
    $Class8_Name = CBinaryCover::utf8_to_tis620( $Class8_Name );
    $Class16_Name = CBinaryCover::utf8_to_tis620( $Class16_Name );
    $Class32_Name = CBinaryCover::utf8_to_tis620( $Class32_Name );
    $Class64_Name = CBinaryCover::utf8_to_tis620( $Class64_Name );
    $Class128_Name = CBinaryCover::utf8_to_tis620( $Class128_Name );
    $Class256_Name = CBinaryCover::utf8_to_tis620( $Class256_Name );
    $Class512_Name = CBinaryCover::utf8_to_tis620( $Class512_Name );
    $Class1024_Name = CBinaryCover::utf8_to_tis620( $Class1024_Name );
    $Class2048_Name = CBinaryCover::utf8_to_tis620( $Class2048_Name );
    $Class4096_Name = CBinaryCover::utf8_to_tis620( $Class4096_Name );
    $Class8192_Name = CBinaryCover::utf8_to_tis620( $Class8192_Name );
    
    CheckStringLen( $Class1_Name , $len );
    CheckStringLen( $Class2_Name , $len );
    CheckStringLen( $Class4_Name , $len );
    CheckStringLen( $Class8_Name , $len );
    CheckStringLen( $Class16_Name , $len );
    CheckStringLen( $Class32_Name , $len );
    CheckStringLen( $Class64_Name , $len );
    CheckStringLen( $Class128_Name , $len );
    CheckStringLen( $Class256_Name , $len );
    CheckStringLen( $Class512_Name , $len );
    CheckStringLen( $Class1024_Name , $len );
    CheckStringLen( $Class2048_Name , $len );
    CheckStringLen( $Class4096_Name , $len );
    CheckStringLen( $Class8192_Name , $len );
    
    $pSetData->cMemClass->Class1_On = $Class1_On;
    $pSetData->cMemClass->Class1_Name = $Class1_Name;
    $pSetData->cMemClass->Class64_On = $Class64_On;
    $pSetData->cMemClass->Class64_Name = $Class64_Name;
    $pSetData->cMemClass->Class2_On = $Class2_On;
    $pSetData->cMemClass->Class2_Name = $Class2_Name;
    $pSetData->cMemClass->Class128_On = $Class128_On;
    $pSetData->cMemClass->Class128_Name = $Class128_Name;
    $pSetData->cMemClass->Class256_On = $Class256_On;
    $pSetData->cMemClass->Class256_Name = $Class256_Name;
    $pSetData->cMemClass->Class4_On = $Class4_On;
    $pSetData->cMemClass->Class4_Name = $Class4_Name;
    $pSetData->cMemClass->Class512_On = $Class512_On;
    $pSetData->cMemClass->Class512_Name = $Class512_Name;
    $pSetData->cMemClass->Class8_On = $Class8_On;
    $pSetData->cMemClass->Class8_Name = $Class8_Name;
    $pSetData->cMemClass->Class1024_On = $Class1024_On;
    $pSetData->cMemClass->Class1024_Name = $Class1024_Name;
    $pSetData->cMemClass->Class2048_On = $Class2048_On;
    $pSetData->cMemClass->Class2048_Name = $Class2048_Name;
    $pSetData->cMemClass->Class16_On = $Class16_On;
    $pSetData->cMemClass->Class16_Name = $Class16_Name;
    $pSetData->cMemClass->Class32_On = $Class32_On;
    $pSetData->cMemClass->Class32_Name = $Class32_Name;
    $pSetData->cMemClass->Class4096_On = $Class4096_On;
    $pSetData->cMemClass->Class4096_Name = $Class4096_Name;
    $pSetData->cMemClass->Class8192_On = $Class8192_On;
    $pSetData->cMemClass->Class8192_Name = $Class8192_Name;
    $pSetData->cMemClass->UpdateDB( );
    
	$pSetData->Class = $Class;
	$pSetData->Class_P = $Class_P;
	$pSetData->Class_Change_CheckStat = $Class_Change_CheckStat;
	$pSetData->Class_Change_CheckSkill = $Class_Change_CheckSkill;
    $pSetData->Update2DB( $MemNum );
    
    $pSetData->cMemClass->Class1_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class1_Name );
    $pSetData->cMemClass->Class2_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class2_Name );
    $pSetData->cMemClass->Class4_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class4_Name );
    $pSetData->cMemClass->Class8_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class8_Name );
    $pSetData->cMemClass->Class16_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class16_Name );
    $pSetData->cMemClass->Class32_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class32_Name );
    $pSetData->cMemClass->Class64_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class64_Name );
    $pSetData->cMemClass->Class128_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class128_Name );
    $pSetData->cMemClass->Class256_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class256_Name );
    $pSetData->cMemClass->Class512_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class512_Name );
    $pSetData->cMemClass->Class1024_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class1024_Name );
    $pSetData->cMemClass->Class2048_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class2048_Name );
    $pSetData->cMemClass->Class4096_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class4096_Name );
    $pSetData->cMemClass->Class8192_Name = CBinaryCover::tis620_to_utf8( $pSetData->cMemClass->Class8192_Name );
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSetData ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS:WORK";
}

function CMD_UI_P2()
{
    global $arrON;
    global $page;
    global $pSetData;
    global $cAdmin;
?>
<table class="gridtable">
    <tr>
        <td colspan="2"><b><u>ระบบเปลี่ยนอาชีพ</u></b></td>
    </tr>
    <tr>
        <td style="width:309px;">โหมด</td>
        <td style="width:309px;"><?php echo buildSelectText( "Class", "Class", $pSetData->Class, $arrON ); ?></td>
    </tr>
    <tr>
        <td>พ้อยที่จำเป็นต้องใช้</td>
        <td><input type="text" id="Class_P" value="<?php echo $pSetData->Class_P; ?>" style="width:39px;"> พ้อย</td>
    </tr>
    <tr>
        <td>สเตตัสต้องว่างใช่หรือไม่</td>
        <td><?php echo buildSelectText( "Class_Change_CheckStat", "Class_Change_CheckStat", $pSetData->Class_Change_CheckStat, $arrON ); ?></td>
    </tr>
    <tr>
        <td>สกิลต้องว่างใช่หรือไม่</td>
        <td><?php echo buildSelectText( "Class_Change_CheckSkill", "Class_Change_CheckSkill", $pSetData->Class_Change_CheckSkill, $arrON ); ?></td>
    </tr>
    <tr>
        <td colspan="2"><b><u>ตั้งชื่อของแต่ละอาชีพ</u></b></td>
    </tr>
    <tr>
        <td>หมัด(ชาย)</td>
        <td>
            <?php echo buildSelectText( "Class1_On", "", $pSetData->cMemClass->Class1_On, $arrON ); ?>
            <input type="text" id="Class1_Name" value="<?php echo $pSetData->cMemClass->Class1_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
if ( $cAdmin->nServerType >= SERVTYPE_EP7 )
{
?>
    <tr>
        <td>หมัด(หญิง)</td>
        <td>
            <?php echo buildSelectText( "Class64_On", "", $pSetData->cMemClass->Class64_On, $arrON ); ?>
            <input type="text" id="Class64_Name" value="<?php echo $pSetData->cMemClass->Class64_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
}
?>
    <tr>
        <td>ดาบ(ชาย)</td>
        <td>
            <?php echo buildSelectText( "Class2_On", "", $pSetData->cMemClass->Class2_On, $arrON ); ?>
            <input type="text" id="Class2_Name" value="<?php echo $pSetData->cMemClass->Class2_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
if ( $cAdmin->nServerType >= SERVTYPE_EP7 )
{
?>
    <tr>
        <td>ดาบ(หญิง)</td>
        <td>
            <?php echo buildSelectText( "Class128_On", "", $pSetData->cMemClass->Class128_On, $arrON ); ?>
            <input type="text" id="Class128_Name" value="<?php echo $pSetData->cMemClass->Class128_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
}
if ( $cAdmin->nServerType >= SERVTYPE_EP7 )
{
?>
    <tr>
        <td>ธนู(ชาย)</td>
        <td>
            <?php echo buildSelectText( "Class256_On", "", $pSetData->cMemClass->Class256_On, $arrON ); ?>
            <input type="text" id="Class256_Name" value="<?php echo $pSetData->cMemClass->Class256_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
}
?>
    <tr>
        <td>ธนู(หญิง)</td>
        <td>
            <?php echo buildSelectText( "Class4_On", "", $pSetData->cMemClass->Class4_On, $arrON ); ?>
            <input type="text" id="Class4_Name" value="<?php echo $pSetData->cMemClass->Class4_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
if ( $cAdmin->nServerType >= SERVTYPE_EP7 )
{
?>
    <tr>
        <td>พระ(ชาย)</td>
        <td>
            <?php echo buildSelectText( "Class512_On", "", $pSetData->cMemClass->Class512_On, $arrON ); ?>
            <input type="text" id="Class512_Name" value="<?php echo $pSetData->cMemClass->Class512_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
}
?>
    <tr>
        <td>พระ(หญิง)</td>
        <td>
            <?php echo buildSelectText( "Class8_On", "", $pSetData->cMemClass->Class8_On, $arrON ); ?>
            <input type="text" id="Class8_Name" value="<?php echo $pSetData->cMemClass->Class8_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
if ( $cAdmin->nServerType == SERVTYPE_PLUSONLINE || $cAdmin->nServerType == SERVTYPE_EP8 )
{
?>
    <tr>
        <td>ปืน(ชาย)</td>
        <td>
            <?php echo buildSelectText( "Class1024_On", "", $pSetData->cMemClass->Class1024_On, $arrON ); ?>
            <input type="text" id="Class1024_Name" value="<?php echo $pSetData->cMemClass->Class1024_Name; ?>" style="width:179px;">
        </td>
    </tr>
    <tr>
        <td>ปืน(หญิง)</td>
        <td>
            <?php echo buildSelectText( "Class2048_On", "", $pSetData->cMemClass->Class2048_On, $arrON ); ?>
            <input type="text" id="Class2048_Name" value="<?php echo $pSetData->cMemClass->Class2048_Name; ?>" style="width:179px;">
        </td>
    </tr>
    <tr>
        <td>นินจา(ชาย)</td>
        <td>
            <?php echo buildSelectText( "Class4096_On", "", $pSetData->cMemClass->Class4096_On, $arrON ); ?>
            <input type="text" id="Class4096_Name" value="<?php echo $pSetData->cMemClass->Class4096_Name; ?>" style="width:179px;">
        </td>
    </tr>
    <tr>
        <td>นินจา(หญิง)</td>
        <td>
            <?php echo buildSelectText( "Class8192_On", "", $pSetData->cMemClass->Class8192_On, $arrON ); ?>
            <input type="text" id="Class8192_Name" value="<?php echo $pSetData->cMemClass->Class8192_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
}
if ( $cAdmin->nServerType >= SERVTYPE_EP7 )
{
?>
    <tr>
        <td>สายผสม(ชาย)</td>
        <td>
            <?php echo buildSelectText( "Class16_On", "", $pSetData->cMemClass->Class16_On, $arrON ); ?>
            <input type="text" id="Class16_Name" value="<?php echo $pSetData->cMemClass->Class16_Name; ?>" style="width:179px;">
        </td>
    </tr>
    <tr>
        <td>สายผสม(หญิง)</td>
        <td>
            <?php echo buildSelectText( "Class32_On", "", $pSetData->cMemClass->Class32_On, $arrON ); ?>
            <input type="text" id="Class32_Name" value="<?php echo $pSetData->cMemClass->Class32_Name; ?>" style="width:179px;">
        </td>
    </tr>
<?php
}
?>
    <tr>
        <td colspan="2"><div align='right'><button onclick="savePage('<?php echo $page; ?>');">บันทึก</button></div></td>
    </tr>
</table>
<?php
}

function CMD_PROCESS_P3()
{
    global $CURRENT_SESSION;
    global $arrON;
    global $arrONP;
    global $pSetData;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $CharReborn = CInput::GetInstance()->GetValueInt( "CharReborn" , IN_POST );
    $CharReborn_P = CInput::GetInstance()->GetValueInt( "CharReborn_P" , IN_POST );
    $CharReborn_Free = CInput::GetInstance()->GetValueInt( "CharReborn_Free" , IN_POST );
    $CharReborn_Max = CInput::GetInstance()->GetValueInt( "CharReborn_Max" , IN_POST );
    $CharRebornLevCheck = CInput::GetInstance()->GetValueInt( "CharRebornLevCheck" , IN_POST );
    $CharRebornLevStart = CInput::GetInstance()->GetValueInt( "CharRebornLevStart" , IN_POST );
    $CharRebornFreeOn = CInput::GetInstance()->GetValueInt( "CharRebornFreeOn" , IN_POST );
    $CharRebornFreeCheck = CInput::GetInstance()->GetValueInt( "CharRebornFreeCheck" , IN_POST );
    $ChaRebornGetPoint_Lv = CInput::GetInstance()->GetValueInt( "ChaRebornGetPoint_Lv" , IN_POST );
    $ChaRebornGetPoint_Value = CInput::GetInstance()->GetValueInt( "ChaRebornGetPoint_Value" , IN_POST );
    
    if ( !arrkeycheck($arrON, $CharReborn) ) $CharReborn = 0;
    if ( !arrkeycheck($arrONP, $CharRebornFreeOn) ) $CharRebornFreeOn = 0;
    
    CheckNumZero($CharReborn_P);
    CheckNumZero($CharReborn_Free);
    CheckNumZero($CharReborn_Max);
    CheckNumZero($CharRebornLevCheck);
    CheckNumZero($CharRebornLevStart);
    CheckNumZero($CharRebornFreeCheck);
    CheckNumZero($ChaRebornGetPoint_Lv);
    CheckNumZero($ChaRebornGetPoint_Value);
    
    $pSetData->CharReborn = $CharReborn;
    $pSetData->CharReborn_P = $CharReborn_P;
    $pSetData->CharReborn_Free = $CharReborn_Free;
    $pSetData->CharReborn_Max = $CharReborn_Max;
    $pSetData->CharRebornLevCheck = $CharRebornLevCheck;
    $pSetData->CharRebornLevStart = $CharRebornLevStart;
    $pSetData->CharRebornFreeOn = $CharRebornFreeOn;
    $pSetData->CharRebornFreeCheck = $CharRebornFreeCheck;
    $pSetData->ChaRebornGetPoint_Lv = $ChaRebornGetPoint_Lv;
    $pSetData->ChaRebornGetPoint_Value = $ChaRebornGetPoint_Value;
    
    $pSetData->Update2DB( $MemNum );
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSetData ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS:WORK";
}

function CMD_UI_P3()
{
    global $page;
    global $arrON;
    global $arrONP;
    global $pSetData;
?>
<table class="gridtable">
    <tr>
        <td colspan="2"><b><u>ระบบจุติ</u></b></td>
    </tr>
    <tr>
        <td style="width:309px;">โหมด</td>
        <td style="width:309px;"><?php echo buildSelectText( "CharReborn", "CharReborn", $pSetData->CharReborn, $arrON ); ?></td>
    </tr>
    <tr>
        <td>พ้อยที่ใช้ในการจุติ</td>
        <td><input type="text" id="CharReborn_P" value="<?php echo $pSetData->CharReborn_P; ?>" style="width:39px;"> พ้อย</td>
    </tr>
    <tr>
        <td>จุติฟรี</td>
        <td><input type="text" id="CharReborn_Free" value="<?php echo $pSetData->CharReborn_Free; ?>" style="width:39px;"></td>
    </tr>
    <tr>
        <td>จุติทั้งหมด</td>
        <td><input type="text" id="CharReborn_Max" value="<?php echo $pSetData->CharReborn_Max; ?>" style="width:39px;"></td>
    </tr>
    <tr>
        <td>เลเวลที่สามารถจุติได้</td>
        <td><input type="text" id="CharRebornLevCheck" value="<?php echo $pSetData->CharRebornLevCheck; ?>" style="width:39px;"></td>
    </tr>
    <tr>
        <td>จุติเหลือเลเวล</td>
        <td><input type="text" id="CharRebornLevStart" value="<?php echo $pSetData->CharRebornLevStart; ?>" style="width:39px;"></td>
    </tr>
    <tr>
        <td>ปิดระบบจุติฟรี</td>
        <td><?php echo buildSelectText( "CharRebornFreeOn", "CharRebornFreeOn", $pSetData->CharRebornFreeOn, $arrONP ); ?></td>
    </tr>
    <tr>
        <td>จุติกี่ครั้งถึงจะเข้าเมนูได้</td>
        <td><input type="text" id="CharRebornFreeCheck" value="<?php echo $pSetData->CharRebornFreeCheck; ?>" style="width:39px;"></td>
    </tr>
    <tr>
        <td>ระบบจุติกดรับพ้อยฟรี</td>
        <td>จุติครบ <input type="text" id="ChaRebornGetPoint_Lv" value="<?php echo $pSetData->ChaRebornGetPoint_Lv; ?>" style="width:39px;"> ครั้ง,ได้รับ <input type="text" id="ChaRebornGetPoint_Value" value="<?php echo $pSetData->ChaRebornGetPoint_Value; ?>" style="width:39px;"> พ้อย</td>
    </tr>
    <tr>
        <td colspan="2"><div align='right'><button onclick="savePage('<?php echo $page; ?>');">บันทึก</button></div></td>
    </tr>
</table>
<?php
}

function CMD_PROCESS_P4()
{
    global $arrON;
    global $cAdmin;
    global $pSetData;
    global $CURRENT_SESSION;
    $MemNum = $cAdmin->GetMemNum();
	
	$CURRENT_SESSION_LIST = sprintf( "%d_skilltablelist" , $MemNum );
    $pSkillData = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_LIST , IN_SESSION ) );
    if ( !$pSkillData ) die("ERROR:pSkillData:NULL");
    
    $arrID = array( "d1" , "d2" , "d4" , "d8" , "d16" , "d32" , "d64" , "d128" , "d256" , "d512" , "d1024" , "d2048" );
    $arrIDN = array( 1 , 2 , 4 , 8 , 16 , 32 , 64 , 128 , 256 , 512 , 1024 , 2048 );
	$arrgettype = array( "add" , "del" );
	
	$get_classnum = CInput::GetInstance()->GetValueInt( "classnum" , IN_POST );
	$get_type = CInput::GetInstance()->GetValueString( "type" , IN_POST );
    $skillid = CInput::GetInstance()->GetValueString( "skillid" , IN_POST );
	$SkillSetOpen = CInput::GetInstance()->GetValueInt( "SkillSetOpen" , IN_POST );
    $SkillPoint = CInput::GetInstance()->GetValueInt( "SkillPoint" , IN_POST );
	
	if ( !arrkeycheck($arrON, $SkillSetOpen) ) $SkillSetOpen = 0;
    CheckNumZero($SkillPoint);
    if ( !arrkeycheck($arrIDN, $get_classnum) ) die("ERROR:CLASSNUM");
	if ( !arrkeycheck($arrgettype, $get_type) )
	{
		die("ERROR:TYPE");
	}
	if ( strlen( $skillid ) != 11 )
	{
		$pSetData->cSkillSet->UpdatePoint( $MemNum , $SkillPoint );
	    $pSetData->cSkillSet->UpdateOpen( $MemNum , $SkillSetOpen );
		$pSetData->cSkillSet->Save();
		CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSetData ) , IN_SESSION );
    	CInput::GetInstance()->UpdateSession();
		echo "SUCCESS:WORK";
		return ;
		//die( "ERROR:SKILLID" );
	}
	
	$get_classnum = (int)$get_classnum;
    
    //$pSetData->cSkillSet->Clear();
    
    $main = CNeoInject::sec_Int( substr( $skillid , 3 , 3 ) );
	$sub = CNeoInject::sec_Int( substr( $skillid , 7 , 3 ) );
	$lev = CNeoInject::sec_Int( substr( $skillid , -1 ) );
	
	$idskill = $pSetData->cSkillSet->FindSkill( $get_classnum , $main , $sub );
	switch( $get_type )
	{
		case $arrgettype[0]:
		{
			if ( $idskill == SKILL_ERROR )
			{
				$pSetData->cSkillSet->AddSkill( $get_classnum , $main , $sub , $lev );
			}
		}break;
		case $arrgettype[1]:
		{
			//printf( "%s:class:%d,skillid:%d,%d,%d" , $get_type , $get_classnum , $idskill , $main , $sub );
			if ( $idskill != SKILL_ERROR )
			{
				$pSetData->cSkillSet->DelSkill( $idskill );
			}else{
				echo "ERROR:";
			}
		}break;
	}
    
    $pSetData->cSkillSet->Save();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSetData ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS:WORK";
}

function CMD_UI_P4()
{
    global $page;
    global $arrON;
    global $pSetData;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    class __SkillData
    {
        public $SkillID = "";
        public $SkillName = "";
        public function __construct( $SkillID , $SkillName )
        {
            $this->SkillID = $SkillID;
            $this->SkillName = $SkillName;
        }
    }
    
    class SkillData
    {
        private $pData = array();
        private $nData = 0;
        
        public function GetRollData() { return $this->nData; }
        public function GetData( $index ){ return $this->pData[$index]; }

        public function AddData( $SkillID , $SkillName )
        {
            $this->pData[ $this->nData ] = new __SkillData( $SkillID , $SkillName );
            $this->nData++;
        }
    }
    
    $CURRENT_SESSION = sprintf( "%d_skilltablelist" , $MemNum );
    
    $pSkillData = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
    if ( !$pSkillData )
    {
        $pSkillData = new SkillData;
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp = sprintf("SELECT SkillID,SkillName FROM SkillTable WHERE MemNum = %d AND SkillDelete = 0",$MemNum);
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $skillid = $cNeoSQLConnectODBC->Result("SkillID",ODBC_RETYPE_ENG);
            $skillname = $cNeoSQLConnectODBC->Result("SkillName",ODBC_RETYPE_THAI);
            $skillname = CBinaryCover::tis620_to_utf8($skillname);
            $pSkillData->AddData($skillid, $skillname);

        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSkillData ) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
    }
    
    $pSkillList = array();
    for( $i = 0 ; $i < $pSkillData->GetRollData() ; $i++ )
    {
        $ppData = $pSkillData->GetData( $i );
        $pSkillList[ $i ] = sprintf( "%s : %s" , $ppData->SkillID , $ppData->SkillName );
    }
    
    function getNameL( $main , $sub , $pSkillData )
    {
        for( $i = 0 ; $i < $pSkillData->GetRollData() ; $i++ )
        {
            $ppData = $pSkillData->GetData( $i );
            if ( strcmp( $ppData->SkillID , sprintf( "SN_%03d_%03d" , $main , $sub ) ) != 0 ) continue;
            return $ppData->SkillName;
        }
        return "Unknows";
    }
    
    function dumpOut( &$arr , $nclass , $pSkillData )
    {
        global $pSetData;
        for( $i = 0 , $n = 0 ; $i < $pSetData->cSkillSet->SkillNum ; $i++ )
        {
            if ( $pSetData->cSkillSet->SkillClass[ $i ] != $nclass ) continue;
			
			/*
            $arr[ $n ] = sprintf( "SN_%03d_%03d(%d) : %s"
                    , $pSetData->cSkillSet->SkillMain[ $i ]
                    , $pSetData->cSkillSet->SkillSub[ $i ]
                    , $pSetData->cSkillSet->SkillLevel[ $i ]
                    , getNameL( $pSetData->cSkillSet->SkillMain[ $i ], $pSetData->cSkillSet->SkillSub[ $i ], $pSkillData )
                    );
			*/
			$nnid = sprintf( "SN_%03d_%03d%d" , $pSetData->cSkillSet->SkillMain[ $i ]
                    , $pSetData->cSkillSet->SkillSub[ $i ]
                    , $pSetData->cSkillSet->SkillLevel[ $i ] );
			$arr[ $nnid ] = sprintf( "SN_%03d_%03d(%d) : %s"
                    , $pSetData->cSkillSet->SkillMain[ $i ]
                    , $pSetData->cSkillSet->SkillSub[ $i ]
                    , $pSetData->cSkillSet->SkillLevel[ $i ]
                    , getNameL( $pSetData->cSkillSet->SkillMain[ $i ], $pSetData->cSkillSet->SkillSub[ $i ], $pSkillData )
                    );
            $n++;
        }
    }
    
    $pl_1 = array();
    $pl_2 = array();
    $pl_4 = array();
    $pl_8 = array();
    $pl_16 = array();
    $pl_32 = array();
    $pl_64 = array();
    $pl_128 = array();
    $pl_256 = array();
    $pl_512 = array();
    $pl_1024 = array();
    $pl_2048 = array();
    $pl_4092 = array();
    $pl_8192 = array();
    
    dumpOut( $pl_1 , 1 , $pSkillData );
    dumpOut( $pl_2 , 2 , $pSkillData );
    dumpOut( $pl_4 , 4 , $pSkillData );
    dumpOut( $pl_8 , 8 , $pSkillData );
    dumpOut( $pl_16 , 16 , $pSkillData );
    dumpOut( $pl_32 , 32 , $pSkillData );
    dumpOut( $pl_64 , 64 , $pSkillData );
    dumpOut( $pl_128 , 128 , $pSkillData );
    dumpOut( $pl_256 , 256 , $pSkillData );
    dumpOut( $pl_512 , 512 , $pSkillData );
    dumpOut( $pl_1024 , 1024 , $pSkillData );
    dumpOut( $pl_2048 , 2048 , $pSkillData );
    dumpOut( $pl_4096 , 4096 , $pSkillData );
    dumpOut( $pl_8192 , 8192 , $pSkillData );
    
    
    print("<script type=\"text/javascript\">var listskilltable = [");
    for( $i = 0 ; $i < $pSkillData->GetRollData() ; $i++ )
    {
        $ppData = $pSkillData->GetData( $i );
        printf( "['%s','%s']," , $ppData->SkillID , $ppData->SkillName );
    }
    print("];</script>");
?>
<table class="gridtable">
    <tr>
        <td colspan="2">
            <p><b><u>ระบบรับสกิล</u></b><br>
            <u>หมายเหตุ:</u> ถ้าหากไม่มีสกิลขึ้นมาให้เพิ่มนั้นแสดงว่าคุณยังไม่ได้ตั้งค่า SkillTable ให้ไปตั้ง SkillTable เสียก่อน
            </p>
        </td>
    </tr>
    <tr>
        <td style="width:199px;">โหมด</td>
        <td style="width:809px;"><?php echo buildSelectText( "SkillSetOpen", "SkillSetOpen", $pSetData->cSkillSet->SkillSetOpen, $arrON , "" , "onchange=\"savePage('p4');\"" ); ?></td>
    </tr>
    <tr>
        <td>พ้อยที่ใช้</td>
        <td><input type="text" id="SkillPoint" value="<?php echo $pSetData->cSkillSet->SkillPoint; ?>" style="width:39px;" onblur="savePage('p4');"> พ้อย</td>
    </tr>
<?php
	function __buildint( $classnum , $classpointer , $pSkillList , $classname )
	{
		$d = sprintf( "d%d" , $classnum );
		$a = sprintf( "a%d" , $classnum );
		$l = sprintf( "l%d" , $classnum );
		
		printf( '<tr>
        <td>%s</td>
        <td>
            <div align="left"><table border="0"><tr><td style=\"width:399px;\">สกิลที่มีแล้ว ></td><td>' , $classname );
		
		echo buildSelectText( $d, "", 0, $classpointer , "width:199px;");
	
		printf( '
                <button onclick="P4_del( \'%s\' , %d );">ลบ</button></td><tr><td style=\"width:399px;\">สกิลที่ต้องการเพิ่ม ></td><td>
			' , $d , $classnum );
		
echo buildSelectText( $a, "", 0, $pSkillList , "width:199px;");

		printf( ' ระดับ : ' );

echo buildSelect( $l, "", 0, 0, SKILL_MAX_LEVEL);

		printf( '
            <button onclick="P4_add( \'%s\' , \'%s\' , \'%s\' , %d );">เพิ่ม</button></td>
            </tr></table></div>
        </td>
    </tr>' , $a , $l , $d , $classnum );
	}
	
	$aadd = array(
				  array( 1 , $pl_1 , "หมัด(ชาย)" , SERVTYPE_DEFAUILT ) ,
				  array( 64 , $pl_64 , "หมัด(หญิง)" , SERVTYPE_EP7 ) ,
				  array( 2 , $pl_2 , "ดาบ(ชาย)" , SERVTYPE_DEFAUILT ) ,
				  array( 128 , $pl_128 , "ดาบ(หญิง)" , SERVTYPE_EP7 ) ,
				  array( 256 , $pl_256 , "ธนู(ชาย)" , SERVTYPE_EP7 ) ,
				  array( 4 , $pl_4 , "ธนู(หญิง)" , SERVTYPE_DEFAUILT ) ,
				  array( 512 , $pl_512 , "พระ(ชาย)" , SERVTYPE_EP7 ) ,
				  array( 8 , $pl_8 , "พระ(หญิง)" , SERVTYPE_DEFAUILT ) ,
				  array( 1024 , $pl_1024 , "ปืน(ชาย)" , SERVTYPE_PLUSONLINE ) ,
				  array( 2048 , $pl_2048 , "ปืน(หญิง)" , SERVTYPE_PLUSONLINE ) ,
				  array( 4096 , $pl_4096 , "นินจา(ชาย)" , SERVTYPE_PLUSONLINE ) ,
				  array( 8192 , $pl_8192 , "นินจา(หญิง)" , SERVTYPE_PLUSONLINE ) ,
				  array( 16 , $pl_16 , "สายผสม(ชาย)" , SERVTYPE_EP7 ) ,
				  array( 32 , $pl_32 , "สายผสม(หญิง)" , SERVTYPE_EP7 )
				  );
	foreach( $aadd as $key => $value )
    {
		$ppdd = $aadd[$key];
		if ( $cAdmin->nServerType >= $ppdd[3] )
		{
			__buildint( $ppdd[0] , $ppdd[1] , $pSkillList , $ppdd[2] );
		}
    }
	/*
	echo '<tr>
        <td colspan="2"><div align=\'right\'><button onclick="savePage(\'' . $page . '\');">บันทึก</button></div></td>
    </tr>';
	*/
	echo '</table>';
}
?>