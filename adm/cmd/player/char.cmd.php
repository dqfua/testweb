<?php
$arrType = array( "ChaNum" , "ChaName" , "UserNum" );
$SESSION_CHASEL = "selectdata_wow";

class ChaList
{
    private $pData = array();
    private $nData = 0;
    public function GetData( $index ){ return $this->pData[ $index ]; }
    public function GetRollData(){ return $this->nData; }
    public function AddData( $ChaInfo )
    {
        $this->pData[ $this->nData ] = $ChaInfo;
        $this->nData++;
    }
};

function CHARACTER_EDITOR_MODE()
{
    printf( "<u>เมนูจัดการ</u> : 
        <button onclick=\"chaeditormode(0);\">แก้ไขทั่วไป</button> |
        <button onclick=\"chaeditormode(1);\">แก้ไขไอเทมในกระเป๋า</button> |
        <button onclick=\"chaeditormode(2);\">แก้ไขไอเทมในตัวละคร</button> |
        <button onclick=\"chaeditormode(3);\">แก้ไขสกิลในตัวละคร</button>
        <div id=\"player_process_ui\"></div>" );
}

function CHARACTER_SEL()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = new ChaInfo();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $chanum = CInput::GetInstance()->GetValueInt( "chanum" , IN_POST );
    if ( $chanum < 0 ) $chanum = 0;
    
    $szTemp = sprintf( "SELECT
                        ChaNum,UserNum,ChaName,ChaGuName,GuNum,
                        ChaClass,ChaSchool,ChaHair,ChaFace,ChaLevel,ChaExp,ChaMoney,ChaPower,ChaStrong,
                        ChaStrength,ChaSpirit,ChaDex,ChaStRemain,ChaStartMap,ChaSaveMap,ChaReturnMap,
                        ChaBright,ChaPK,ChaSkillPoint,ChaInvenLine,ChaDeleted,ChaReborn,
                        ChaInven,ChaPutOnItems,ChaSkills
                        FROM ChaInfo
                        WHERE ChaNum = %d" , $chanum );
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
    $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $pChaInfo->ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
        $pChaInfo->UserNum = $cNeoSQLConnectODBC->Result( "UserNum" , ODBC_RETYPE_INT );
        $pChaInfo->GuNum = $cNeoSQLConnectODBC->Result( "GuNum" , ODBC_RETYPE_INT );
        
        $pChaInfo->ChaClass = $cNeoSQLConnectODBC->Result( "ChaClass" , ODBC_RETYPE_INT );
        $pChaInfo->ChaSchool = $cNeoSQLConnectODBC->Result( "ChaSchool" , ODBC_RETYPE_INT );
        $pChaInfo->ChaHair = $cNeoSQLConnectODBC->Result( "ChaHair" , ODBC_RETYPE_INT );
        $pChaInfo->ChaFace = $cNeoSQLConnectODBC->Result( "ChaFace" , ODBC_RETYPE_INT );
        $pChaInfo->ChaLevel = $cNeoSQLConnectODBC->Result( "ChaLevel" , ODBC_RETYPE_INT );
        $pChaInfo->ChaExp = $cNeoSQLConnectODBC->Result( "ChaExp" , ODBC_RETYPE_ENG );
        $pChaInfo->ChaMoney = $cNeoSQLConnectODBC->Result( "ChaMoney" , ODBC_RETYPE_ENG );
        $pChaInfo->ChaPower = $cNeoSQLConnectODBC->Result( "ChaPower" , ODBC_RETYPE_INT );
        $pChaInfo->ChaStrong = $cNeoSQLConnectODBC->Result( "ChaStrong" , ODBC_RETYPE_INT );
        $pChaInfo->ChaStrength = $cNeoSQLConnectODBC->Result( "ChaStrength" , ODBC_RETYPE_INT );
        $pChaInfo->ChaSpirit = $cNeoSQLConnectODBC->Result( "ChaSpirit" , ODBC_RETYPE_INT );
        $pChaInfo->ChaDex = $cNeoSQLConnectODBC->Result( "ChaDex" , ODBC_RETYPE_INT );
        $pChaInfo->ChaStRemain = $cNeoSQLConnectODBC->Result( "ChaStRemain" , ODBC_RETYPE_INT );
        $pChaInfo->ChaStartMap = $cNeoSQLConnectODBC->Result( "ChaStartMap" , ODBC_RETYPE_INT );
        $pChaInfo->ChaSaveMap = $cNeoSQLConnectODBC->Result( "ChaSaveMap" , ODBC_RETYPE_INT );
        $pChaInfo->ChaReturnMap = $cNeoSQLConnectODBC->Result( "ChaReturnMap" , ODBC_RETYPE_INT );
        
        $pChaInfo->ChaBright = $cNeoSQLConnectODBC->Result( "ChaBright" , ODBC_RETYPE_INT );
        $pChaInfo->ChaPK = $cNeoSQLConnectODBC->Result( "ChaPK" , ODBC_RETYPE_INT );
        $pChaInfo->ChaSkillPoint = $cNeoSQLConnectODBC->Result( "ChaSkillPoint" , ODBC_RETYPE_INT );
        $pChaInfo->ChaInvenLine = $cNeoSQLConnectODBC->Result( "ChaInvenLine" , ODBC_RETYPE_INT );
        $pChaInfo->ChaDeleted = $cNeoSQLConnectODBC->Result( "ChaDeleted" , ODBC_RETYPE_INT );
        $pChaInfo->ChaReborn = $cNeoSQLConnectODBC->Result( "ChaReborn" , ODBC_RETYPE_INT );
        
        $pChaInfo->ChaInven = $cNeoSQLConnectODBC->Result( "ChaInven" , ODBC_RETYPE_BINARY );
        $pChaInfo->ChaPutOnItems = $cNeoSQLConnectODBC->Result( "ChaPutOnItems" , ODBC_RETYPE_BINARY );
        $pChaInfo->ChaSkills = $cNeoSQLConnectODBC->Result( "ChaSkills" , ODBC_RETYPE_BINARY );
        
        $pChaInfo->ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
        $pChaInfo->ChaGuName = $cNeoSQLConnectODBC->Result( "ChaGuName" , ODBC_RETYPE_THAI );
        
        //cover tis to utf
        $pChaInfo->ChaName = CBinaryCover::tis620_to_utf8( $pChaInfo->ChaName );
        $pChaInfo->ChaGuName = CBinaryCover::tis620_to_utf8( $pChaInfo->ChaGuName );
        
        echo "SUCCESS";
    }
    $cNeoSQLConnectODBC->CloseRanGame();
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
}

function CHARACTER_EDITOR_CHARNAME()
{
	global $SESSION_CHASEL;
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    function buildInputText( $textid , $value , $readonly = "readonly" )
    {
        printf( '<tr>
                    <td style="width: 119px;" align="left">%s</td>
                    <td style="width: 199px;" align="left">
                        <input type="text" id="%s" class="" %s value="%s">
                    </td>
                </tr>' , $textid , $textid , $readonly , $value );
    }
    echo '<table>';
    buildInputText( "ChaNum" , $pChaInfo->ChaNum );
    buildInputText( "ChaName" , $pChaInfo->ChaName , "" );
	echo '
		<tr>
			<td colspan="2">
				<div align="center">
					<button onclick="submit_editcharactername();" style="width:199px;">เปลี่ยนชื่อ</button>
				</div>
			</td>
		</tr>
		</table>
	';
}

function CHARACTER_PROC_CHARNAME()
{
	global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    function __trynow( &$valset , $id , $type = 0 )
    {
        switch( $type )
        {
            case 0:
            {
                $valset = CInput::GetInstance()->GetValueInt( $id , IN_POST );
            }break;
            case 1:
            {
                $valset = CInput::GetInstance()->GetValueString( $id , IN_POST );
            }break;
        }
    }
	
    __trynow( $pChaInfo->ChaName , "ChaName" , 1 );
    //cover tis to utf
    $ChaName = CBinaryCover::utf8_to_tis620( $pChaInfo->ChaName );
    
    $szTemp = "UPDATE ChaInfo SET ";
    $szTemp .= sprintf( "ChaName = '%s'" , $ChaName );
    $szTemp .= sprintf( "WHERE ChaNum = %d" , $pChaInfo->ChaNum );
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
    $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
    $cNeoSQLConnectODBC->CloseRanGame();
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CHARACTER_EDITOR_CHARGUNAME()
{
	global $SESSION_CHASEL;
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    function buildInputText( $textid , $value , $readonly = "readonly" )
    {
        printf( '<tr>
                    <td style="width: 119px;" align="left">%s</td>
                    <td style="width: 199px;" align="left">
                        <input type="text" id="%s" class="" %s value="%s">
                    </td>
                </tr>' , $textid , $textid , $readonly , $value );
    }
    echo '<table>';
    buildInputText( "ChaNum" , $pChaInfo->ChaNum );
    buildInputText( "ChaGuName" , $pChaInfo->ChaGuName , "" );
	echo '
		<tr>
			<td colspan="2">
				<div align="center">
					<button onclick="submit_editcharacterguname();" style="width:199px;">เปลี่ยนชื่อ</button>
				</div>
			</td>
		</tr>
		</table>
	';
}

function CHARACTER_PROC_CHARGUNAME()
{
	global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    function __trynow( &$valset , $id , $type = 0 )
    {
        switch( $type )
        {
            case 0:
            {
                $valset = CInput::GetInstance()->GetValueInt( $id , IN_POST );
            }break;
            case 1:
            {
                $valset = CInput::GetInstance()->GetValueString( $id , IN_POST );
            }break;
        }
    }
	
    __trynow( $pChaInfo->ChaGuName , "ChaGuName" , 1 );
    //cover tis to utf
    $ChaGuName = CBinaryCover::utf8_to_tis620( $pChaInfo->ChaGuName );
    
    $szTemp = "UPDATE ChaInfo SET ";
    $szTemp .= sprintf( "ChaGuName = '%s'" , $ChaGuName );
    $szTemp .= sprintf( "WHERE ChaNum = %d" , $pChaInfo->ChaNum );
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
    $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
    $cNeoSQLConnectODBC->CloseRanGame();
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CHARACTER_EDITOR_ITEMEDITOR_SAVE()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $ItemMain = CInput::GetInstance()->GetValueInt( "ItemMain" , IN_POST );
    $ItemSub = CInput::GetInstance()->GetValueInt( "ItemSub" , IN_POST );
    $ItemDamage = CInput::GetInstance()->GetValueInt( "ItemDamage" , IN_POST );
    $ItemDefense = CInput::GetInstance()->GetValueInt( "ItemDefense" , IN_POST );
    $Item_TurnNum = CInput::GetInstance()->GetValueInt( "Item_TurnNum" , IN_POST );
    $Item_Drop = CInput::GetInstance()->GetValueInt( "Item_Drop" , IN_POST );
    $Item_Res_Ele = CInput::GetInstance()->GetValueInt( "Item_Res_Ele" , IN_POST );
    $Item_Res_Fire = CInput::GetInstance()->GetValueInt( "Item_Res_Fire" , IN_POST );
    $Item_Res_Ice = CInput::GetInstance()->GetValueInt( "Item_Res_Ice" , IN_POST );
    $Item_Res_Poison = CInput::GetInstance()->GetValueInt( "Item_Res_Poison" , IN_POST );
    $Item_Res_Spirit = CInput::GetInstance()->GetValueInt( "Item_Res_Spirit" , IN_POST );
    $Item_Op1 = CInput::GetInstance()->GetValueInt( "Item_Op1" , IN_POST );
    $Item_Op2 = CInput::GetInstance()->GetValueInt( "Item_Op2" , IN_POST );
    $Item_Op3 = CInput::GetInstance()->GetValueInt( "Item_Op3" , IN_POST );
    $Item_Op4 = CInput::GetInstance()->GetValueInt( "Item_Op4" , IN_POST );
    $Item_Op1_Value = CInput::GetInstance()->GetValueInt( "Item_Op1_Value" , IN_POST );
    $Item_Op2_Value = CInput::GetInstance()->GetValueInt( "Item_Op2_Value" , IN_POST );
    $Item_Op3_Value = CInput::GetInstance()->GetValueInt( "Item_Op3_Value" , IN_POST );
    $Item_Op4_Value = CInput::GetInstance()->GetValueInt( "Item_Op4_Value" , IN_POST );
    
    $Item_InvenX = CInput::GetInstance()->GetValueInt( "Item_InvenX" , IN_POST );
    $Item_InvenY = CInput::GetInstance()->GetValueInt( "Item_InvenY" , IN_POST );
    
    CheckNumZero($ItemMain);
    CheckNumZero($ItemSub);
    CheckNumZero($ItemDamage);
    CheckNumZero($ItemDefense);
    CheckNumZero($Item_TurnNum);
    CheckNumZero($Item_Res_Ele);
    CheckNumZero($Item_Res_Fire);
    CheckNumZero($Item_Res_Ice);
    CheckNumZero($Item_Res_Poison);
    CheckNumZero($Item_Res_Spirit);
    //CheckNumZero($Item_Op1_Value);
    //CheckNumZero($Item_Op2_Value);
    //CheckNumZero($Item_Op3_Value);
    //CheckNumZero($Item_Op4_Value);
    
    CheckNumZero($Item_InvenX);
    CheckNumZero($Item_InvenY);
    
    if ( !arrkeycheck(ItemDropData(), $Item_Drop ) ) die("ERROR:ITEMDROP");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op1 ) ) die("ERROR:ITEMOP1");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op2 ) ) die("ERROR:ITEMOP2");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op3 ) ) die("ERROR:ITEMOP3");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op4 ) ) die("ERROR:ITEMOP4");
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaInven( $MemNum );
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cWeb->GetSysmFromDB( $MemNum );
    $cNeoSerialMemory = CRanShop::BuildItem2ChaInven($Item_InvenX, $Item_InvenY, $ItemMain, $ItemSub, time(), $Item_TurnNum, $Item_Drop, $ItemDamage, $ItemDefense, $Item_Res_Fire, $Item_Res_Ice, $Item_Res_Ele, $Item_Res_Poison, $Item_Res_Spirit, $Item_Op1, $Item_Op2, $Item_Op3, $Item_Op4, $Item_Op1_Value, $Item_Op2_Value, $Item_Op3_Value, $Item_Op4_Value, $cWeb->GetServerType() );
    
    $idm = $pChaInfo->NeoChaInven->InvenFind( $Item_InvenX , $Item_InvenY );
    if ( $idm != ITEM_ERROR )
    {
        $pChaInfo->NeoChaInven->DeleteItem( $idm );
    }
    
    $pChaInfo->NeoChaInven->AddItem( $cNeoSerialMemory );
    $pChaInfo->NeoChaInven->UpdateVar2Binary();
    $pBuffer = $pChaInfo->NeoChaInven->SaveChaInven();
    $pChaInfo->ChaInven = $pBuffer;
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "<span class=\"info\">บันทึกเรียบร้อยแล้ว!!</span>";
}

function CHARACTER_EDITOR_ITEMEDITOR_UI()
{
    global $SESSION_CHASEL;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $x = CInput::GetInstance()->GetValueInt( "x" , IN_POST );
    $y = CInput::GetInstance()->GetValueInt( "y" , IN_POST );
    $p = CInput::GetInstance()->GetValueInt( "p" , IN_POST );
    if ( $p < 0 || $p > 1 ) die("ERROR:MODE:UI:EDITOR:FAILED");
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaInven( $MemNum );
    
    if ( $p == 1 )
    {
        $idm = $pChaInfo->NeoChaInven->InvenFind( $x , $y );
        if ( $idm != ITEM_ERROR )
        {
            $pChaInfo->NeoChaInven->DeleteItem( $idm );
        }
        $pChaInfo->NeoChaInven->SaveChaInven();
    
        $pBuffer = $pChaInfo->NeoChaInven->SaveChaInven();
        $pChaInfo->ChaInven = $pBuffer;
        
        CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
        echo "<span class=\"info\">ลบไอเทมเรียบร้อย</span>";
        return ;
    }
    
    $pItemTemp = new ItemProjectTEMP;
    $idm = $pChaInfo->NeoChaInven->InvenFind( $x , $y );
    buildItemCustomEditor($pItemTemp, $pChaInfo->NeoChaInven, $idm, sprintf( "<button onclick=\"item_chainven_save(%d,%d);\">Save</button>" , $x , $y ));
}

function CHARACTER_EDITOR_CHAINVEN_UI()
{
    global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    $pChaInfo->BuildNeoChaInven( $MemNum );
    
    printf( "<p>จำนวนไอเทมในกระเป๋าทั้งหมดคือ : <b>%d</b><br></p>" , $pChaInfo->NeoChaInven->GetItemNum() );
    
    echo "<table border=\"3\" cellspacing=\"3\" cellpadding=\"3\">";
    for( $y = 0 ; $y < 10 ; $y ++ )
    {
        echo "<tr>";
        for( $x = 0 ; $x < 6 ; $x ++ )
        {
            $itemname = "";
            $bgcolor = "FF0000";
            $idm = $pChaInfo->NeoChaInven->InvenFind( $x , $y );
            if ( $idm == ITEM_ERROR )
            {
                $itemname = sprintf( "<button onclick=\"item_chainven_add( %d , %d );\">ADD</button>" , $x , $y );
            }else{
                $itemmain = $pChaInfo->NeoChaInven->GetItemMain( $idm );
                $itemsub = $pChaInfo->NeoChaInven->GetItemSub( $idm );
                $itemname = sprintf( "<p>%03d:%03d<br><button onclick=\"item_chainven_edit( %d , %d );\">Edit</button><br><button onclick=\"item_chainven_del( %d , %d );\">Del</button></p>" , $itemmain , $itemsub , $x , $y , $x , $y );
                $bgcolor = "30FF50";
            }
            printf( "<td style=\"width:99px;height:99px;background-color:#%s\"><div align=\"center\">%s</div></td>" , $bgcolor , $itemname );
        }
        echo "</tr>";
    }
    echo "</table>";
}

function CHARACTER_EDITOR_CHAPUTONITEMS_SAVE( $del = false )
{
    global $SESSION_CHASEL;
    global $cAdmin;
    
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaPutOnItems( $MemNum );
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $ItemMain = CInput::GetInstance()->GetValueInt( "ItemMain" , IN_POST );
    $ItemSub = CInput::GetInstance()->GetValueInt( "ItemSub" , IN_POST );
    $ItemDamage = CInput::GetInstance()->GetValueInt( "ItemDamage" , IN_POST );
    $ItemDefense = CInput::GetInstance()->GetValueInt( "ItemDefense" , IN_POST );
    $Item_TurnNum = CInput::GetInstance()->GetValueInt( "Item_TurnNum" , IN_POST );
    $Item_Drop = CInput::GetInstance()->GetValueInt( "Item_Drop" , IN_POST );
    $Item_Res_Ele = CInput::GetInstance()->GetValueInt( "Item_Res_Ele" , IN_POST );
    $Item_Res_Fire = CInput::GetInstance()->GetValueInt( "Item_Res_Fire" , IN_POST );
    $Item_Res_Ice = CInput::GetInstance()->GetValueInt( "Item_Res_Ice" , IN_POST );
    $Item_Res_Poison = CInput::GetInstance()->GetValueInt( "Item_Res_Poison" , IN_POST );
    $Item_Res_Spirit = CInput::GetInstance()->GetValueInt( "Item_Res_Spirit" , IN_POST );
    $Item_Op1 = CInput::GetInstance()->GetValueInt( "Item_Op1" , IN_POST );
    $Item_Op2 = CInput::GetInstance()->GetValueInt( "Item_Op2" , IN_POST );
    $Item_Op3 = CInput::GetInstance()->GetValueInt( "Item_Op3" , IN_POST );
    $Item_Op4 = CInput::GetInstance()->GetValueInt( "Item_Op4" , IN_POST );
    $Item_Op1_Value = CInput::GetInstance()->GetValueInt( "Item_Op1_Value" , IN_POST );
    $Item_Op2_Value = CInput::GetInstance()->GetValueInt( "Item_Op2_Value" , IN_POST );
    $Item_Op3_Value = CInput::GetInstance()->GetValueInt( "Item_Op3_Value" , IN_POST );
    $Item_Op4_Value = CInput::GetInstance()->GetValueInt( "Item_Op4_Value" , IN_POST );
    
    $pos = CInput::GetInstance()->GetValueInt( "pos" , IN_POST );
    
    if ( $del )
    {
        $ItemMain = 65535;
        $ItemSub = 65535;
    }
    
    CheckNumZero($ItemMain);
    CheckNumZero($ItemSub);
    CheckNumZero($ItemDamage);
    CheckNumZero($ItemDefense);
    CheckNumZero($Item_TurnNum);
    CheckNumZero($Item_Res_Ele);
    CheckNumZero($Item_Res_Fire);
    CheckNumZero($Item_Res_Ice);
    CheckNumZero($Item_Res_Poison);
    CheckNumZero($Item_Res_Spirit);
    //CheckNumZero($Item_Op1_Value);
    //CheckNumZero($Item_Op2_Value);
    //CheckNumZero($Item_Op3_Value);
    //CheckNumZero($Item_Op4_Value);
    
    CheckNumZero($pos);
    if ( $pos < 0 || $pos > $pChaInfo->NeoChaPutOnItems->GetItemNum() ) die( "ERROR:POS" );
    
    if ( !arrkeycheck(ItemDropData(), $Item_Drop ) ) die("ERROR:ITEMDROP");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op1 ) ) die("ERROR:ITEMOP1");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op2 ) ) die("ERROR:ITEMOP2");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op3 ) ) die("ERROR:ITEMOP3");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op4 ) ) die("ERROR:ITEMOP4");
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cWeb->GetSysmFromDB( $MemNum );
    $cNeoSerialMemory = CRanShop::BuildItem2PutOnItem( $ItemMain, $ItemSub, time(), $Item_TurnNum, $Item_Drop, $ItemDamage, $ItemDefense, $Item_Res_Fire, $Item_Res_Ice, $Item_Res_Ele, $Item_Res_Poison, $Item_Res_Spirit, $Item_Op1, $Item_Op2, $Item_Op3, $Item_Op4, $Item_Op1_Value, $Item_Op2_Value, $Item_Op3_Value, $Item_Op4_Value, $cWeb->GetServerType() );
    
    $pChaInfo->NeoChaPutOnItems->AddItem( $cNeoSerialMemory , $pos );
    $pChaInfo->NeoChaPutOnItems->UpdateVar2Binary();
    $pBuffer = $pChaInfo->NeoChaPutOnItems->SaveChaPutOnItems();
    $pChaInfo->ChaPutOnItems = $pBuffer;
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    echo "SUCCESS";
}

function CHARACTER_EDITOR_CHAPUTONITEMS_EDITOR_UI()
{
    global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaPutOnItems( $MemNum );
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $pos = CInput::GetInstance()->GetValueInt( "pos" , IN_POST );
    CheckNumZero($pos);
    if ( $pos < 0 || $pos > $pChaInfo->NeoChaPutOnItems->GetItemNum() ) die( "ERROR:POS" );
    
    $pItemTemp = new ItemProjectTEMP;
    buildItemCustomEditor($pItemTemp, $pChaInfo->NeoChaPutOnItems, $pos, sprintf( "<button onclick=\"putonitem_save(%d);\">Save</button>" , $pos ));
}

function CHARACTER_EDITOR_CHAPUTONITEMS_UI()
{
    global $PIECE_POS_TYPE;
    global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaPutOnItems( $MemNum );
    
    
    echo "<table border=\"2\" cellspacing=\"3\" cellpadding=\"3\">";
    printf( "<tr><td style=\"width:159px;\"><b>ตำแหน่ง</b></td><td style=\"width:359px;\"><b>ไอเทม</b></td></tr>" );
    for( $i = 0 ; $i < $pChaInfo->NeoChaPutOnItems->GetItemNum()-1 ; $i++ )
    {
        $itemname = sprintf( "Empty <button onclick=\"putonitem_edit(this,%d);\">Add</button>" , $i );
        $pos = "Other";
        if ( arrkeycheck( $PIECE_POS_TYPE , $i ) ) $pos = $PIECE_POS_TYPE[ $i ];
        
        if ( $pChaInfo->NeoChaPutOnItems->GetItemMain( $i ) != 65535 && $pChaInfo->NeoChaPutOnItems->GetItemSub( $i ) != 65535 )
            $itemname = sprintf( "%03d:%03d <button onclick=\"putonitem_edit(this,%d);\">Edit</button><button onclick=\"putonitem_del(this,%d);\">Del</button>"
                                , $pChaInfo->NeoChaPutOnItems->GetItemMain( $i ) , $pChaInfo->NeoChaPutOnItems->GetItemSub( $i ) , $i , $i );
        
        printf( "<tr><td>%s</td><td>%s</td></tr>" , $pos , $itemname );
    }
    echo "</table>";
}

function CHARACTER_EDITOR_CHASKILL_ADD()
{
    global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaSkill();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    $main = CInput::GetInstance()->GetValueInt( "main" , IN_POST );
    $sub = CInput::GetInstance()->GetValueInt( "sub" , IN_POST );
    $lev = CInput::GetInstance()->GetValueInt( "lev" , IN_POST );
    
    CheckNumZero($main);
    CheckNumZero($sub);
    CheckNumZero($lev);
    
    if ( $pChaInfo->NeoChaSkill->FindID( $main , $sub ) != SKILL_ERROR ) die("ERROR:SKILL:ALREADY");
    
    $pChaInfo->NeoChaSkill->Main[ $pChaInfo->NeoChaSkill->SkillNum ] = $main;
    $pChaInfo->NeoChaSkill->Sub[ $pChaInfo->NeoChaSkill->SkillNum ] = $sub;
    $pChaInfo->NeoChaSkill->Level[ $pChaInfo->NeoChaSkill->SkillNum ] = $lev;
    $pChaInfo->NeoChaSkill->SkillNum++;
    
    $pChaInfo->ChaSkills = $pChaInfo->NeoChaSkill->GetBuffer()->GetBuffer();
    
    $pChaInfo->NeoChaSkill->UpdateDBM( $pChaInfo->NeoChaSkill->GetBuffer() , $pChaInfo->ChaNum , $MemNum );
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CHARACTER_EDITOR_CHASKILL_EDIT()
{
    global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaSkill();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $nid = CInput::GetInstance()->GetValueInt( "nid" , IN_POST );
    if ( $nid < 0 || $nid > $pChaInfo->NeoChaSkill->SkillNum ) die("ERROR");
    
    $main = CInput::GetInstance()->GetValueInt( "main" , IN_POST );
    $sub = CInput::GetInstance()->GetValueInt( "sub" , IN_POST );
    $lev = CInput::GetInstance()->GetValueInt( "lev" , IN_POST );
    
    CheckNumZero($main);
    CheckNumZero($sub);
    CheckNumZero($lev);
    
    $pChaInfo->NeoChaSkill->Main[ $nid ] = $main;
    $pChaInfo->NeoChaSkill->Sub[ $nid ] = $sub;
    $pChaInfo->NeoChaSkill->Level[ $nid ] = $lev;
    
    $pChaInfo->ChaSkills = $pChaInfo->NeoChaSkill->GetBuffer()->GetBuffer();
    
    $pChaInfo->NeoChaSkill->UpdateDBM( $pChaInfo->NeoChaSkill->GetBuffer() , $pChaInfo->ChaNum , $MemNum );
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CHARACTER_EDITOR_CHASKILL_DEL()
{
    global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaSkill();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $nid = CInput::GetInstance()->GetValueInt( "nid" , IN_POST );
    if ( $nid < 0 || $nid > $pChaInfo->NeoChaSkill->SkillNum ) die("ERROR");
    
    $pChaInfo->NeoChaSkill->DelSkill( $nid );
    
    $pChaInfo->ChaSkills = $pChaInfo->NeoChaSkill->GetBuffer()->GetBuffer();
    
    $pChaInfo->NeoChaSkill->UpdateDBM( $pChaInfo->NeoChaSkill->GetBuffer() , $pChaInfo->ChaNum , $MemNum );
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CHARACTER_EDITOR_CHASKILL_UI()
{
    global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    $pChaInfo->BuildNeoChaSkill();
    
    echo '<script type="text/javascript">';
    echo 'var skilllevoptionui = \'' . buildSelect( "462a" , "" , $pChaInfo->NeoChaSkill->Level[ $i ] , 0 , SKILL_MAX_LEVEL ) . '\';';
    echo '</script>';
    
    echo "<input type=\"hidden\" id=\"skillnum\" value=\"" . $pChaInfo->NeoChaSkill->SkillNum . "\">";
    echo "<table id=\"skill_editor_ui\" border=\"2\" cellspacing=\"3\" cellpadding=\"3\"><thead>";
    
    for( $i = 0 ; $i < $pChaInfo->NeoChaSkill->SkillNum ; $i++ )
    {
        printf( "<tr><td><input type=\"text\" id=\"main_%d\" value=\"%d\" style=\"width:39px;\">:<input type=\"text\" id=\"sub_%d\" value=\"%d\" style=\"width:39px;\">:%s<button onclick=\"skill_edit(this,%d);\">Edit</button><button onclick=\"skill_del(this,%d);\">Del</button></td></tr>"
                , $i , $pChaInfo->NeoChaSkill->Main[ $i ]
                , $i , $pChaInfo->NeoChaSkill->Sub[ $i ]
                , buildSelect( sprintf( "lev_%d" , $i ) , "" , $pChaInfo->NeoChaSkill->Level[ $i ] , 0 , SKILL_MAX_LEVEL )
                , $i , $i );
    }
    
    printf( "</thead><tbody></tbody><tfoot><tr><td><input type=\"text\" id=\"add_main\" value=\"0\" style=\"width:39px;\">:<input type=\"text\" id=\"add_sub\" value=\"0\" style=\"width:39px;\">:%s<button onclick=\"skill_add(this);\">Add</button></td></tr></tfoot>"
            , buildSelect( "add_lev" , "" , $pChaInfo->NeoChaSkill->Level[ $i ] , 0 , SKILL_MAX_LEVEL ) );
    
    echo "</table>";
}

function CHARACTER_EDITOR_UI()
{
    global $SESSION_CHASEL;
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    function buildInputText( $textid , $value , $readonly = "readonly" )
    {
        printf( '<tr>
                    <td style="width: 119px;" align="left">%s</td>
                    <td style="width: 199px;" align="left">
                        <input type="text" id="%s" class="" %s value="%s">
                    </td>
                </tr>' , $textid , $textid , $readonly , $value );
    }
    echo '<table>';
    buildInputText( "ChaNum" , $pChaInfo->ChaNum );
    buildInputText( "UserNum" , $pChaInfo->UserNum , "" );
    buildInputText( "GuNum" , $pChaInfo->GuNum , "" );
	
	echo '
		<tr>
			<td colspan="2">
				<div align="center">
					<button onclick="chaeditormode(4);" style="width:199px;">เปลี่ยนชื่อ</button>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<div align="center">
					<button onclick="chaeditormode(5);" style="width:199px;">เปลี่ยนฉายา</button>
				</div>
			</td>
		</tr>
	';
	
    buildInputText( "ChaClass" , $pChaInfo->ChaClass , "" );
    buildInputText( "ChaSchool" , $pChaInfo->ChaSchool , "" );
    buildInputText( "ChaHair" , $pChaInfo->ChaHair , "" );
    buildInputText( "ChaFace" , $pChaInfo->ChaFace , "" );
    buildInputText( "ChaLevel" , $pChaInfo->ChaLevel , "" );
    buildInputText( "ChaExp" , $pChaInfo->ChaExp , "" );
    buildInputText( "ChaMoney" , $pChaInfo->ChaMoney , "" );
    buildInputText( "ChaPower" , $pChaInfo->ChaPower , "" );
    buildInputText( "ChaStrong" , $pChaInfo->ChaStrong , "" );
    buildInputText( "ChaStrength" , $pChaInfo->ChaStrength , "" );
    buildInputText( "ChaSpirit" , $pChaInfo->ChaSpirit , "" );
    buildInputText( "ChaDex" , $pChaInfo->ChaDex , "" );
    buildInputText( "ChaStRemain" , $pChaInfo->ChaStRemain , "" );
    buildInputText( "ChaStartMap" , $pChaInfo->ChaStartMap , "" );
    buildInputText( "ChaSaveMap" , $pChaInfo->ChaSaveMap , "" );
    buildInputText( "ChaReturnMap" , $pChaInfo->ChaReturnMap , "" );
    buildInputText( "ChaBright" , $pChaInfo->ChaBright , "" );
    buildInputText( "ChaPK" , $pChaInfo->ChaPK , "" );
    buildInputText( "ChaSkillPoint" , $pChaInfo->ChaSkillPoint , "" );
    buildInputText( "ChaInvenLine" , $pChaInfo->ChaInvenLine , "" );
    buildInputText( "ChaDeleted" , $pChaInfo->ChaDeleted , "" );
    buildInputText( "ChaReborn" , $pChaInfo->ChaReborn , "" );
    
    //buildInputText( "ChaName" , $pChaInfo->ChaName , "" );
    //buildInputText( "ChaGuName" , $pChaInfo->ChaGuName , "" );
    
    echo '
        <tr>
            <td colspan="2">
                <button onclick="submit_editcharacter();">แก้ไข</button>
            </td>
        </tr>
        </table>
    ';
}

function CHARACTER_EDITOR_PROC()
{
    global $cAdmin;
    global $SESSION_CHASEL;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    
    function __trynow( &$valset , $id , $type = 0 )
    {
        switch( $type )
        {
            case 0:
            {
                $valset = CInput::GetInstance()->GetValueInt( $id , IN_POST );
            }break;
            case 1:
            {
                $valset = CInput::GetInstance()->GetValueString( $id , IN_POST );
            }break;
        }
    }
    
    __trynow( $pChaInfo->UserNum , "UserNum" );
    __trynow( $pChaInfo->GuNum , "GuNum" );
    __trynow( $pChaInfo->ChaClass , "ChaClass" );
    __trynow( $pChaInfo->ChaSchool , "ChaSchool" );
    __trynow( $pChaInfo->ChaHair , "ChaHair" );
    __trynow( $pChaInfo->ChaFace , "ChaFace" );
    __trynow( $pChaInfo->ChaLevel , "ChaLevel" );
    __trynow( $pChaInfo->ChaExp , "ChaExp" , 1 );
    __trynow( $pChaInfo->ChaMoney , "ChaMoney" , 1 );
    __trynow( $pChaInfo->ChaPower , "ChaPower" );
    __trynow( $pChaInfo->ChaStrong , "ChaStrong" );
    __trynow( $pChaInfo->ChaStrength , "ChaStrength" );
    __trynow( $pChaInfo->ChaSpirit , "ChaSpirit" );
    __trynow( $pChaInfo->ChaDex , "ChaDex" );
    __trynow( $pChaInfo->ChaStRemain , "ChaStRemain" );
    __trynow( $pChaInfo->ChaStartMap , "ChaStartMap" );
    __trynow( $pChaInfo->ChaSaveMap , "ChaSaveMap" );
    __trynow( $pChaInfo->ChaReturnMap , "ChaReturnMap" );
    __trynow( $pChaInfo->ChaBright , "ChaBright" );
    __trynow( $pChaInfo->ChaPK , "ChaPK" );
    __trynow( $pChaInfo->ChaSkillPoint , "ChaSkillPoint" );
    __trynow( $pChaInfo->ChaInvenLine , "ChaInvenLine" );
    __trynow( $pChaInfo->ChaDeleted , "ChaDeleted" );
    __trynow( $pChaInfo->ChaReborn , "ChaReborn" );
    __trynow( $pChaInfo->ChaName , "ChaName" , 1 );
    __trynow( $pChaInfo->ChaGuName , "ChaGuName" , 1 );
    
    //cover tis to utf
    $ChaName = CBinaryCover::utf8_to_tis620( $pChaInfo->ChaName );
    $ChaGuName = CBinaryCover::utf8_to_tis620( $pChaInfo->ChaGuName );
    
    $szTemp = "UPDATE ChaInfo SET ";
    
    $szTemp .= sprintf( "UserNum = %d," , $pChaInfo->UserNum );
    $szTemp .= sprintf( "GuNum = %d," , $pChaInfo->GuNum );
    $szTemp .= sprintf( "ChaClass = %d," , $pChaInfo->ChaClass );
    $szTemp .= sprintf( "ChaSchool = %d," , $pChaInfo->ChaSchool );
    $szTemp .= sprintf( "ChaHair = %d," , $pChaInfo->ChaHair );
    $szTemp .= sprintf( "ChaFace = %d," , $pChaInfo->ChaFace );
    $szTemp .= sprintf( "ChaLevel = %d," , $pChaInfo->ChaLevel );
    //$szTemp .= sprintf( "ChaExp = %d," , $pChaInfo->ChaExp );
    //$szTemp .= sprintf( "ChaMoney = %d," , $pChaInfo->ChaMoney );
    $szTemp .= "ChaExp = ".$pChaInfo->ChaExp.",";
    $szTemp .= "ChaMoney = ".$pChaInfo->ChaMoney.",";
    $szTemp .= sprintf( "ChaPower = %d," , $pChaInfo->ChaPower );
    $szTemp .= sprintf( "ChaStrong = %d," , $pChaInfo->ChaStrong );
    $szTemp .= sprintf( "ChaStrength = %d," , $pChaInfo->ChaStrength );
    $szTemp .= sprintf( "ChaSpirit = %d," , $pChaInfo->ChaSpirit );
    $szTemp .= sprintf( "ChaDex = %d," , $pChaInfo->ChaDex );
    $szTemp .= sprintf( "ChaStRemain = %d," , $pChaInfo->ChaStRemain );
    $szTemp .= sprintf( "ChaStartMap = %d," , $pChaInfo->ChaStartMap );
    $szTemp .= sprintf( "ChaSaveMap = %d," , $pChaInfo->ChaSaveMap );
    $szTemp .= sprintf( "ChaReturnMap = %d," , $pChaInfo->ChaReturnMap );
    $szTemp .= sprintf( "ChaBright = %d," , $pChaInfo->ChaBright );
    $szTemp .= sprintf( "ChaPK = %d," , $pChaInfo->ChaPK );
    $szTemp .= sprintf( "ChaSkillPoint = %d," , $pChaInfo->ChaSkillPoint );
    $szTemp .= sprintf( "ChaInvenLine = %d," , $pChaInfo->ChaInvenLine );
    $szTemp .= sprintf( "ChaDeleted = %d," , $pChaInfo->ChaDeleted );
    $szTemp .= sprintf( "ChaReborn = %d" , $pChaInfo->ChaReborn );
    
    //$szTemp .= sprintf( "ChaName = '%s'," , $ChaName );
    //$szTemp .= sprintf( "ChaGuName = '%s'" , $ChaGuName );
    
    $szTemp .= sprintf( "WHERE ChaNum = %d" , $pChaInfo->ChaNum );
    
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
    $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
    $cNeoSQLConnectODBC->CloseRanGame();
    
    CInput::GetInstance()->AddValue( $SESSION_CHASEL , serialize( $pChaInfo ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CHARACTER_VIEWINFO_HEAD()
{
    global $SESSION_CHASEL;
    $pChaInfo = unserialize( CInput::GetInstance()->GetValue( $SESSION_CHASEL , IN_SESSION ) );
    if ( !$pChaInfo ) die( "ERROR:CHARACTER:NULL" );
    printf( "<p><u>ตัวละครที่คุณเลือกในปัจจุบันนี้คือ ChaNum : %d , ChaName : %s</u><button onclick=\"char2user( %d );\">ไปยังข้อมูลไอดีของตัวละครนี้</button></p>" , $pChaInfo->ChaNum , $pChaInfo->ChaName , $pChaInfo->UserNum );
}

function CHARACTER_SEARCH()
{
    global $arrType;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    $search_type = CInput::GetInstance()->GetValueInt( "search_type" , IN_POST );
    
    $szTemp = "SELECT
                ChaNum,UserNum,ChaName,ChaGuName,ChaInven,ChaPutOnItems,ChaSkills
                FROM ChaInfo
                WHERE ";
    
    if ( !arrkeycheck($arrType, $search_type) ) $search_type = 0;
    switch( $search_type )
    {
        case 0:
        {
            $ChaNum = CInput::GetInstance()->GetValueInt( "text" , IN_POST );
            $szTemp .= "ChaNum = " . $ChaNum;
        }break;
    
        case 1:
        {
            $ChaName = CInput::GetInstance()->GetValueString( "text" , IN_POST );
            $ChaName = CBinaryCover::utf8_to_tis620( $ChaName );
            if ( strlen( $ChaName ) < 1 ) die( "ERROR:CHANAME:LENGTH" );
            $szTemp .= "ChaName LIKE '%" . $ChaName . "%'";
        }break;
    
        case 2:
        {
            $UserNum = CInput::GetInstance()->GetValueInt( "text" , IN_POST );
            $szTemp .= "UserNum = " . $UserNum;
        }break;
        default: die("ERROR:OPTION:SECTOR:OVER:LOAD");
    }
    
    //die( $szTemp );
    
    $pChaSector = array();
    $cWeb = new CNeoWeb;
    $cWeb->GetDBInfoFromWebDB( $MemNum );
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC(  );
    $cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP() , $cWeb->GetRanGame_User() , $cWeb->GetRanGame_Pass() , $cWeb->GetRanGame_DB() );
    $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
        $ChaName = $cNeoSQLConnectODBC->Result( "ChaName" , ODBC_RETYPE_THAI );
        $ChaName = CBinaryCover::tis620_to_utf8( $ChaName );
        
        $pChaSector[ $ChaNum ] = $ChaName;
    }
    $cNeoSQLConnectODBC->CloseRanGame();
    
    echo "กรุณาเลือกตัวละคร : ";
    echo buildSelectText( "chanum" , "" , 0, $pChaSector );
    echo "<button onclick=\"selcharacter();\">เลือก</button>";
}

function CHARACTER_UI()
{
    global $arrType;
    //echo 'ใส่ข้อมูลที่ต้องการค้นหา<br>';
    echo buildSelectText( "search_type" , "" , 0 , $arrType );
    echo "<input type=\"text\" id=\"text\"></input><button onclick=\"search( this );\">ค้นหา</button>";
}

?>
