<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

require_once "subproject.cmd.php";
require_once "itemproject.cmd.php";

$SELECT_FILTER = array( "เรียงจากใหม่ไปเก่า" , "เรียงจากเก่าไปใหม่" );

$CURRENT_SESSION = "ADM_ITEMPROJECT_SUBLIST";
//$CURRENT_SESSION_UPLOAD_IMG = sprintf( "nowitemproject_upload_img_edit" );

$SELECT_FILTER_SUB = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
if ( !$SELECT_FILTER_SUB )
{
    $SELECT_FILTER_SUB = array( "ALL" );
    $pData = SubMain::GetInstance()->GetData();
    foreach ( $pData as $key => $value )
    {
        $SELECT_FILTER_SUB[ $pData[$key]->SubNum ] = $pData[$key]->SubName;
    }
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($SELECT_FILTER_SUB) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
}

function CMD_EDIT_PROCESS()
{
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    global $CURRENT_SESSION_ITEMLIST;
    global $CURRENT_SESSION_UPLOAD_IMG;
    
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $ItemNum = CInput::GetInstance()->GetValueInt("ItemNum",IN_POST);
    if ( empty( $ItemNum ) || $ItemNum <= 0 ) die("ERROR:ITEMNUM");
    
    $CURRENT_SESSION = sprintf( "itemproject_edit_%d" , $itemnum );
    
    $SubNum = CInput::GetInstance()->GetValueInt( "SubNum",IN_POST);
    $ItemMain = CInput::GetInstance()->GetValueInt( "ItemMain",IN_POST);
    $ItemSub = CInput::GetInstance()->GetValueInt( "ItemSub",IN_POST);
    $ItemName = CInput::GetInstance()->GetValueString( "ItemName",IN_POST);
    $ItemComment = CInput::GetInstance()->GetValueString( "ItemComment",IN_POST);
    //$ItemImage = CInput::GetInstance()->GetValueString( "ItemImage",IN_POST);
    //$ItemImage Get FROM Session
    $Item_Resell = CInput::GetInstance()->GetValueInt( "Item_Resell",IN_POST);
    $Item_Resell_Percent = CInput::GetInstance()->GetValueInt( "Item_Resell_Percent",IN_POST);
    $ItemPrice = CInput::GetInstance()->GetValueInt( "ItemPrice",IN_POST);
    $ItemTimePrice = CInput::GetInstance()->GetValueInt( "ItemTimePrice",IN_POST);
    $ItemBonusPointPrice = CInput::GetInstance()->GetValueInt( "ItemBonusPointPrice",IN_POST);
    $ItemSell = CInput::GetInstance()->GetValueInt( "ItemSell",IN_POST);
    $ItemType = CInput::GetInstance()->GetValueInt( "ItemType",IN_POST);
    $ItemSock = CInput::GetInstance()->GetValueInt( "ItemSock",IN_POST);
    $ItemShow = CInput::GetInstance()->GetValueInt( "ItemShow",IN_POST);
    $ItemDay = CInput::GetInstance()->GetValueInt( "ItemDay",IN_POST);
    $ItemDrop = CInput::GetInstance()->GetValueInt( "ItemDrop",IN_POST);
    $ItemDamage = CInput::GetInstance()->GetValueInt( "ItemDamage",IN_POST);
    $ItemDefense = CInput::GetInstance()->GetValueInt( "ItemDefense",IN_POST);
    $Item_TurnNum = CInput::GetInstance()->GetValueInt( "Item_TurnNum",IN_POST);
    $Item_Res_Ele = CInput::GetInstance()->GetValueInt( "Item_Res_Ele",IN_POST);
    $Item_Res_Fire = CInput::GetInstance()->GetValueInt( "Item_Res_Fire",IN_POST);
    $Item_Res_Ice = CInput::GetInstance()->GetValueInt( "Item_Res_Ice",IN_POST);
    $Item_Res_Poison = CInput::GetInstance()->GetValueInt( "Item_Res_Poison",IN_POST);
    $Item_Res_Spirit = CInput::GetInstance()->GetValueInt( "Item_Res_Spirit",IN_POST);
    $Item_Op1 = CInput::GetInstance()->GetValueInt( "Item_Op1",IN_POST);
    $Item_Op1_Value = CInput::GetInstance()->GetValueInt( "Item_Op1_Value",IN_POST);
    $Item_Op2 = CInput::GetInstance()->GetValueInt( "Item_Op2",IN_POST);
    $Item_Op2_Value = CInput::GetInstance()->GetValueInt( "Item_Op2_Value",IN_POST);
    $Item_Op3 = CInput::GetInstance()->GetValueInt( "Item_Op3",IN_POST);
    $Item_Op3_Value = CInput::GetInstance()->GetValueInt( "Item_Op3_Value",IN_POST);
    $Item_Op4 = CInput::GetInstance()->GetValueInt( "Item_Op4",IN_POST);
    $Item_Op4_Value = CInput::GetInstance()->GetValueInt( "Item_Op4_Value",IN_POST);
    $Item_MaxReborn = CInput::GetInstance()->GetValueInt( "Item_MaxReborn",IN_POST);
    
    CheckNumZero($SubNum);CheckNumZero($ItemMain);CheckNumZero($ItemSub);CheckNumZero($Item_Resell);
    CheckNumZero($Item_Resell_Percent);CheckNumZero($ItemPrice);CheckNumZero($ItemTimePrice);CheckNumZero($ItemBonusPointPrice);
    CheckNumZero($ItemSell);CheckNumZero($ItemType);
    CheckNumZero($ItemSock);CheckNumZero($ItemShow);CheckNumZero($ItemDay);CheckNumZero($ItemDrop);
    CheckNumZero($ItemDamage);CheckNumZero($ItemDefense);CheckNumZero($Item_TurnNum);CheckNumZero($Item_Res_Ele);
    CheckNumZero($Item_Res_Fire);CheckNumZero($Item_Res_Ice);CheckNumZero($Item_Res_Poison);CheckNumZero($Item_Res_Spirit);
    CheckNumZero($Item_Op1);
    //CheckNumZero($Item_Op1_Value);
    CheckNumZero($Item_Op2);
    //CheckNumZero($Item_Op2_Value);
    CheckNumZero($Item_Op3);
    //CheckNumZero($Item_Op3_Value);
    CheckNumZero($Item_Op4);
    //CheckNumZero($Item_Op4_Value);
    CheckNumZero($Item_MaxReborn);
    
    if ( empty( $ItemName ) ) die( "ERROR:ITEMNAME:EMPTY" );
    
    $pSubList = SubList_GetData($MemNum);
    if ( !$pSubList ) die( "ERROR:SUBLIST:EMPTY" );
    if ( !$pSubList->Exists($SubNum)) die( "ERROR:SUBLIST:NOTEXISTS" );
    
    $ItemImage = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_UPLOAD_IMG , IN_SESSION ));
    if ( !$ItemImage ) die("ERROR:IMG:EMPTY");
    
    if ( !arrkeycheck(ItemTypeData(), $ItemType ) ) die("ERROR:ITEMTYPE");
    if ( !arrkeycheck(ItemDropData(), $ItemDrop ) ) die("ERROR:ITEMDROP");
    if ( !arrkeycheck(ItemShowTypeData(), $ItemShow ) ) die("ERROR:ITEMSHOW");
    if ( !arrkeycheck(ItemResellData(), $Item_Resell ) ) die("ERROR:ITEMRESELL");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op1 ) ) die("ERROR:ITEMOP1");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op2 ) ) die("ERROR:ITEMOP2");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op3 ) ) die("ERROR:ITEMOP3");
    if ( !arrkeycheck(ItemOptionData(), $Item_Op4 ) ) die("ERROR:ITEMOP4");
    
    //utf-8 to tis620
    $ItemName = CBinaryCover::utf8_to_tis620( $ItemName );
    $ItemComment = CBinaryCover::utf8_to_tis620( $ItemComment );
    $ItemImage = CBinaryCover::utf8_to_tis620( $ItemImage );
    
    $szTemp = sprintf( "UPDATE ItemProject SET
                        SubNum = %d,ItemMain = %d,ItemSub = %d,ItemName = '%s',ItemComment = '%s',ItemImage = '%s',ItemPrice = %d
                        ,ItemType = %d,ItemSock = %d,ItemShow = %d,ItemDay = %d,ItemDamage = %d,ItemDefense = %d
                        ,Item_TurnNum = %d,Item_Res_Ele = %d,Item_Res_Fire = %d,Item_Res_Ice = %d,Item_Res_Poison = %d,Item_Res_Spirit = %d
                        ,Item_Op1 = %d,Item_Op1_Value = %d,Item_Op2 = %d,Item_Op2_Value = %d,Item_Op3 = %d,Item_Op3_Value = %d,Item_Op4 = %d,Item_Op4_Value = %d
                        ,ItemDrop = %d,Item_Resell = %d,Item_Resell_Percent = %d,Item_MaxReborn = %d,ItemTimePrice = %d,ItemBonusPointPrice = %d
                        WHERE ItemNum = %d AND MemNum = %d"
                  ,$SubNum,$ItemMain,$ItemSub,$ItemName,$ItemComment,$ItemImage,$ItemPrice,$ItemType,$ItemSock,$ItemShow,$ItemDay,$ItemDamage,$ItemDefense
                  ,$Item_TurnNum,$Item_Res_Ele,$Item_Res_Fire,$Item_Res_Ice,$Item_Res_Poison,$Item_Res_Spirit
                  ,$Item_Op1,$Item_Op1_Value,$Item_Op2,$Item_Op2_Value,$Item_Op3,$Item_Op3_Value,$Item_Op4,$Item_Op4_Value
                  ,$ItemDrop,$Item_Resell,$Item_Resell_Percent,$Item_MaxReborn,$ItemTimePrice,$ItemBonusPointPrice
                  ,$ItemNum ,$MemNum
               );
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , NULL , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION_ITEMLIST , NULL , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    CRanShop::InsertItemID( $MemNum , $ItemMain, $ItemSub );
    
    echo "SUCCESS:$ItemImage";
}

function CMD_EDIT_UI()
{
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    global $CURRENT_SESSION_UPLOAD_IMG;
    
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $itemnum = CInput::GetInstance()->GetValueInt("itemnum",IN_POST);
    
    if ( empty( $itemnum ) || $itemnum <= 0 ) die("ERROR:ITEMNUM");
    
    $pItemProjectTEMP = new ItemProjectTEMP();
        
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "
        SELECT TOP 1
        SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,Item_Resell,Item_Resell_Percent,
        ItemPrice,ItemTimePrice,ItemBonusPointPrice,ItemSell,ItemType,ItemSock,ItemShow,ItemDay,ItemDrop,ItemDamage,ItemDefense,Item_TurnNum,
        Item_Res_Ele,Item_Res_Fire,Item_Res_Ice,Item_Res_Poison,Item_Res_Spirit,
        Item_Op1,Item_Op1_Value,Item_Op2,Item_Op2_Value,Item_Op3,Item_Op3_Value,Item_Op4,Item_Op4_Value,
        Item_MaxReborn
        FROM ItemProject WHERE MemNum = %d AND ItemNum = %d
        " , $MemNum , $itemnum ) );
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
        $ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
        $ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
        $ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
        $ItemComment = $cNeoSQLConnectODBC->Result("ItemComment",ODBC_RETYPE_THAI);
        $ItemImage = $cNeoSQLConnectODBC->Result("ItemImage",ODBC_RETYPE_THAI);
        $Item_Resell = $cNeoSQLConnectODBC->Result("Item_Resell",ODBC_RETYPE_INT);
        $Item_Resell_Percent = $cNeoSQLConnectODBC->Result("Item_Resell_Percent",ODBC_RETYPE_INT);
        $ItemPrice = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
        $ItemTimePrice = $cNeoSQLConnectODBC->Result("ItemTimePrice",ODBC_RETYPE_INT);
        $ItemBonusPointPrice = $cNeoSQLConnectODBC->Result("ItemBonusPointPrice",ODBC_RETYPE_INT);
        $ItemSell = $cNeoSQLConnectODBC->Result("ItemSell",ODBC_RETYPE_INT);
        $ItemType = $cNeoSQLConnectODBC->Result("ItemType",ODBC_RETYPE_INT);
        $ItemSock = $cNeoSQLConnectODBC->Result("ItemSock",ODBC_RETYPE_INT);
        $ItemShow = $cNeoSQLConnectODBC->Result("ItemShow",ODBC_RETYPE_INT);
        $ItemDay = $cNeoSQLConnectODBC->Result("ItemDay",ODBC_RETYPE_INT);
        $ItemDrop = $cNeoSQLConnectODBC->Result("ItemDrop",ODBC_RETYPE_INT);
        $ItemDamage = $cNeoSQLConnectODBC->Result("ItemDamage",ODBC_RETYPE_INT);
        $ItemDefense = $cNeoSQLConnectODBC->Result("ItemDefense",ODBC_RETYPE_INT);
        $Item_TurnNum = $cNeoSQLConnectODBC->Result("Item_TurnNum",ODBC_RETYPE_INT);
        $Item_Res_Ele = $cNeoSQLConnectODBC->Result("Item_Res_Ele",ODBC_RETYPE_INT);
        $Item_Res_Fire = $cNeoSQLConnectODBC->Result("Item_Res_Fire",ODBC_RETYPE_INT);
        $Item_Res_Ice = $cNeoSQLConnectODBC->Result("Item_Res_Ice",ODBC_RETYPE_INT);
        $Item_Res_Poison = $cNeoSQLConnectODBC->Result("Item_Res_Poison",ODBC_RETYPE_INT);
        $Item_Res_Spirit = $cNeoSQLConnectODBC->Result("Item_Res_Spirit",ODBC_RETYPE_INT);
        $Item_Op1 = $cNeoSQLConnectODBC->Result("Item_Op1",ODBC_RETYPE_INT);
        $Item_Op1_Value = $cNeoSQLConnectODBC->Result("Item_Op1_Value",ODBC_RETYPE_INT);
        $Item_Op2 = $cNeoSQLConnectODBC->Result("Item_Op2",ODBC_RETYPE_INT);
        $Item_Op2_Value = $cNeoSQLConnectODBC->Result("Item_Op2_Value",ODBC_RETYPE_INT);
        $Item_Op3 = $cNeoSQLConnectODBC->Result("Item_Op3",ODBC_RETYPE_INT);
        $Item_Op3_Value = $cNeoSQLConnectODBC->Result("Item_Op3_Value",ODBC_RETYPE_INT);
        $Item_Op4 = $cNeoSQLConnectODBC->Result("Item_Op4",ODBC_RETYPE_INT);
        $Item_Op4_Value = $cNeoSQLConnectODBC->Result("Item_Op4_Value",ODBC_RETYPE_INT);
        $Item_MaxReborn = $cNeoSQLConnectODBC->Result("Item_MaxReborn",ODBC_RETYPE_INT);

        //tis620 to utf8
        $ItemName = CBinaryCover::tis620_to_utf8( $ItemName );
        $ItemComment = CBinaryCover::tis620_to_utf8( $ItemComment );
        $ItemImage = CBinaryCover::tis620_to_utf8( $ItemImage );

        $pItemProjectTEMP->SetData($SubNum, $ItemMain, $ItemSub, $ItemName, $ItemComment, $ItemImage, $Item_Resell, $Item_Resell_Percent, $ItemPrice, $ItemTimePrice, $ItemBonusPointPrice, $ItemSell, $ItemType, $ItemSock, $ItemShow, $ItemDay, $ItemDrop, $ItemDamage, $ItemDefense, $Item_TurnNum, $Item_Res_Ele, $Item_Res_Fire, $Item_Res_Ice, $Item_Res_Poison, $Item_Res_Spirit, $Item_Op1, $Item_Op1_Value, $Item_Op2, $Item_Op2_Value, $Item_Op3, $Item_Op3_Value, $Item_Op4, $Item_Op4_Value, $Item_MaxReborn);
    }
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION_UPLOAD_IMG , serialize( $pItemProjectTEMP->ItemImage ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
?>
<div id="main_itemproject">
    <span><b><u>แก้ไขไอเทม</u></b></span>
    <span id="showWORK" class="info" style="display:none;"></span>
    <span id="showERROR" class="info_err" style="display:none;">มีปัญหาไม่สามารถเพิ่มไอเทมได้ กรุณาตรวจสอบข้อมูลอีกครั้ง<br></span>
    <table id="table_Project">
        <tr>
            <td style="width:149px;">หมวดหมู่ไอเทม</td>
            <td style="width:299px;">
<?php
$pSubList = SubList_GetData($MemNum);
$arrlist = array();
for( $i = 0 ; $i < $pSubList->GetRoll() ; $i++ )
{
    $ppData = $pSubList->GetData($i);
    $arrlist[ $ppData->SubNum ] = $ppData->SubName;
}
echo buildSelectText("SubNum", "SubNum", $pItemProjectTEMP->SubNum, $arrlist);
?>
            </td>
        </tr>
        <tr>
            <td>ประเภทไอเทม</td>
            <td><?php echo buildSelectText("ItemType", "ItemType", $pItemProjectTEMP->ItemType, ItemTypeData()); ?></td>
        </tr>
        <tr>
            <td>รหัสไอเทม</td>
            <td>
                Main : <input type="text" id="ItemMain" value="<?php echo $pItemProjectTEMP->ItemMain; ?>" style="width:39px;">
                Sub : <input type="text" id="ItemSub" value="<?php echo $pItemProjectTEMP->ItemSub; ?>" style="width:39px;">
            </td>
        </tr>
        <tr>
            <td>ชื่อ</td>
            <td><input type="text" id="ItemName" value="<?php echo $pItemProjectTEMP->ItemName; ?>" style="width:299px;"></td>
        </tr>
        <tr>
            <td>รายละเอียด</td>
            <td><textarea id="ItemComment" rows="5" style="width:299px;"><?php echo $pItemProjectTEMP->ItemComment; ?></textarea></td>
        </tr>
        <tr>
            <td>รูป</td>
            <td>
                <div id="previewImg"></div>
                <input type="hidden" id="ItemImage" value="<?php echo PATH_UPLOAD_ITEMIMAGE . $pItemProjectTEMP->ItemImage; ?>">
                <span id="previewImg_SUCCESS" class="info" style="display:none;">เปลี่ยนแปลงสำเร็จ</span>
                <span id="previewImg_LOADING" class="info" style="display:none;">กรุณารอสักครู่..</span>
                <span id="previewImg_ERROR" class="info_err" style="display:none;">มีปัญหาไม่สามารถเปลี่ยนได้</span>
                <button type="button" id="uploader">เปลี่ยน</button>
            </td>
        </tr>
        <tr>
            <td>ราคา</td>
            <td><input text="text" id="ItemPrice" value="<?php echo $pItemProjectTEMP->ItemPrice; ?>" style="width:39px;"> พ้อย</td>
        </tr>
        <tr>
            <td>ราคา(เวลาออนไลน์)</td>
            <td><input text="text" id="ItemTimePrice" value="<?php echo $pItemProjectTEMP->ItemTimePrice; ?>" style="width:39px;"> นาที</td>
        </tr>
        <tr>
            <td>ราคา(แต้มสะสม)</td>
            <td><input text="text" id="ItemBonusPointPrice" value="<?php echo $pItemProjectTEMP->ItemBonusPointPrice; ?>" style="width:39px;"> แต้ม</td>
        </tr>
        <tr>
            <td>จำนวนที่จะวางขาย</td>
            <td><input text="text" id="ItemSock" value="<?php echo $pItemProjectTEMP->ItemSock; ?>" style="width:39px;"></td>
        </tr>
        <tr id="table_ItemDamage">
            <td>โจมตี(+)</td>
            <td><input text="text" id="ItemDamage" value="<?php echo $pItemProjectTEMP->ItemDamage; ?>" style="width:39px;"></td>
        </tr>
        <tr id="table_ItemDefense">
            <td>ป้องกัน(+)</td>
            <td><input text="text" id="ItemDefense" value="<?php echo $pItemProjectTEMP->ItemDefense; ?>" style="width:39px;"></td>
        </tr>
        <tr id="table_Item_TurnNum">
            <td>จำนวนไอเทม</td>
            <td><input text="text" id="Item_TurnNum" value="<?php echo $pItemProjectTEMP->Item_TurnNum; ?>" style="width:39px;"></td>
        </tr>
        <tr id="table_ItemDay">
            <td>อายุการใช้งาน</td>
            <td><input text="text" id="ItemDay" value="<?php echo $pItemProjectTEMP->ItemDay; ?>" style="width:39px;"></td>
        </tr>
        <tr id="table_Res">
            <td>ป้องกันธาตุ</td>
            <td>
                <table>
                    <tr>
                        <td style="width:59px;">ไฟฟ้า</td>
                        <td style="width:39px;"><input text="text" id="Item_Res_Ele" value="<?php echo $pItemProjectTEMP->Item_Res_Ele; ?>" style="width:39px;"></td>
                    </tr>
                    <tr>
                        <td>ไฟ</td>
                        <td><input text="text" id="Item_Res_Fire" value="<?php echo $pItemProjectTEMP->Item_Res_Fire; ?>" style="width:39px;"></td>
                    </tr>
                    <tr>
                        <td>น้ำแข็ง</td>
                        <td><input text="text" id="Item_Res_Ice" value="<?php echo $pItemProjectTEMP->Item_Res_Ice; ?>" style="width:39px;"></td>
                    </tr>
                    <tr>
                        <td>พิษ</td>
                        <td><input text="text" id="Item_Res_Poison" value="<?php echo $pItemProjectTEMP->Item_Res_Poison; ?>" style="width:39px;"></td>
                    </tr>
                    <tr>
                        <td>เวทย์</td>
                        <td><input text="text" id="Item_Res_Spirit" value="<?php echo $pItemProjectTEMP->Item_Res_Spirit; ?>" style="width:39px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr id="table_Rate">
            <td>เรท</td>
            <td>
                <table>
                    <tr>
                        <td style="width:139px;"><?php echo buildSelectText("Item_Op1", "Item_Op1", $pItemProjectTEMP->Item_Op1, ItemOptionData() ); ?></td>
                        <td style="width:39px;"><input type="text" id="Item_Op1_Value" value="<?php echo $pItemProjectTEMP->Item_Op1_Value; ?>" style="width:39px;"></td>
                    </tr>
                    <tr>
                        <td><?php echo buildSelectText("Item_Op2", "Item_Op2", $pItemProjectTEMP->Item_Op2, ItemOptionData() ); ?></td>
                        <td><input type="text" id="Item_Op2_Value" value="<?php echo $pItemProjectTEMP->Item_Op2_Value; ?>" style="width:39px;"></td>
                    </tr>
                    <tr>
                        <td><?php echo buildSelectText("Item_Op3", "Item_Op3", $pItemProjectTEMP->Item_Op3, ItemOptionData() ); ?></td>
                        <td><input type="text" id="Item_Op3_Value" value="<?php echo $pItemProjectTEMP->Item_Op3_Value; ?>" style="width:39px;"></td>
                    </tr>
                    <tr>
                        <td><?php echo buildSelectText("Item_Op4", "Item_Op4", $pItemProjectTEMP->Item_Op4, ItemOptionData() ); ?></td>
                        <td><input type="text" id="Item_Op4_Value" value="<?php echo $pItemProjectTEMP->Item_Op4_Value; ?>" style="width:39px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr id="table_Drop">
            <td>คุณสมบัติพิเศษ</td>
            <td><?php echo buildSelectText("ItemDrop", "ItemDrop", $pItemProjectTEMP->ItemDrop, ItemDropData()); ?></td>
        </tr>
        <tr>
            <td>เงื่อนไข</td>
            <td>
                <table>
                    <tr>
                        <td style="width: 39px;">จุติ</td>
                        <td style="width: 39px"><input type="text" id="Item_MaxReborn" value="<?php echo $pItemProjectTEMP->Item_MaxReborn; ?>" style="width:39px;"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr id="table_Resell">
            <td>พิเศษ(ขายคืน)</td>
            <td>
                <?php echo buildSelectText("Item_Resell", "Item_Resell", $pItemProjectTEMP->Item_Resell, ItemResellData() );?>
                ภาษี : <input type="text" id="Item_Resell_Percent" value="<?php echo $pItemProjectTEMP->Item_Resell_Percent; ?>" style="width:39px;">
                <div id="previewResell">abd</div>
            </td>
        </tr>
        <tr>
            <td>ตั้งค่า</td>
            <td><?php echo buildSelectText("ItemShow", "ItemShow", $pItemProjectTEMP->ItemShow, ItemShowTypeData() ); ?></td>
        </tr>
        <tr>
            <td colspan="2"><input type="hidden" id="ItemNum" value="<?php echo $itemnum; ?>"><button id="editItem">แก้ไขไอเทม</button><button onclick="backProject();">ย้อนกลับ</button></td>
        </tr>
    </table>
</div>

<script type="text/javascript" src="../js/upclick.js"></script>
<script type="text/javascript" src="js/itemproject.js"></script>
<?php
}

function CMD_DEL_PROC()
{
    CInput::GetInstance()->BuildFrom( IN_POST );
    
    global $CURRENT_SESSION_ITEMLIST;
    
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $itemnum = CInput::GetInstance()->GetValueInt("itemnum",IN_POST);
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $cNeoSQLConnectODBC->QueryRanWeb( sprintf("UPDATE ItemProject SET ItemDelete = 1 WHERE ItemNum = %d AND MemNum = %d" , $itemnum , $MemNum) );
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION_ITEMLIST , NULL , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    echo "SUCCESS";
}

function CMD_UI()
{
    global $SELECT_FILTER;
    global $SELECT_FILTER_SUB;
    
    global $_CONFIG;
    global $cAdmin;
    global $CURRENT_SESSION_ITEMLIST;
    $MemNum = $cAdmin->GetMemNum();
    
    global $arrItemType;
    global $arrItemDrop;
    global $arrItemOption;
    global $arrItemShowType;
    global $arrItemResell;
    
    $pItemList = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION_ITEMLIST , IN_SESSION ) );
    if ( !$pItemList )
    {
        $pItemList = new ItemList;
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb( sprintf("SELECT ItemNum,SubNum,ItemType,ItemMain,ItemSub,ItemName,ItemShow,ItemSell,ItemSock,ItemDay,ItemPrice,ItemTimePrice,ItemBonusPointPrice,Item_Resell,ItemDrop,Item_MaxReborn
                                                    FROM ItemProject WHERE MemNum = %d AND ItemDelete = 0 ORDER BY ItemNum DESC" , $MemNum) );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $ItemNum = $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT);
            $SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
            $ItemType = $cNeoSQLConnectODBC->Result("ItemType",ODBC_RETYPE_INT);
            $ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
            $ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
            $ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
            $ItemShow = $cNeoSQLConnectODBC->Result("ItemShow",ODBC_RETYPE_INT);
            $ItemSell = $cNeoSQLConnectODBC->Result("ItemSell",ODBC_RETYPE_INT);
            $ItemSock = $cNeoSQLConnectODBC->Result("ItemSock",ODBC_RETYPE_INT);
            $ItemDay = $cNeoSQLConnectODBC->Result("ItemDay",ODBC_RETYPE_INT);
            $ItemPrice = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
            $ItemTimePrice = $cNeoSQLConnectODBC->Result("ItemTimePrice",ODBC_RETYPE_INT);
            $ItemBonusPointPrice = $cNeoSQLConnectODBC->Result("ItemBonusPointPrice",ODBC_RETYPE_INT);
            $Item_Resell = $cNeoSQLConnectODBC->Result("Item_Resell",ODBC_RETYPE_INT);
            $ItemDrop = $cNeoSQLConnectODBC->Result("ItemDrop",ODBC_RETYPE_INT);
            $Item_MaxReborn = $cNeoSQLConnectODBC->Result("Item_MaxReborn",ODBC_RETYPE_INT);
            
            $ItemName = substr( $ItemName , 0 , ITEMLIST_NAME_LENGTH );
            
            //utf-8 to tis620
            $ItemName = CBinaryCover::tis620_to_utf8( $ItemName );
            
            $pItemProject = new ItemProjectTEMP();
            $pItemProject->ItemNum = $ItemNum;
            $pItemProject->SubNum = $SubNum;
            $pItemProject->ItemType = $ItemType;
            $pItemProject->ItemMain = $ItemMain;
            $pItemProject->ItemSub = $ItemSub;
            $pItemProject->ItemName = $ItemName;
            $pItemProject->ItemShow = $ItemShow;
            $pItemProject->ItemSell = $ItemSell;
            $pItemProject->ItemSock = $ItemSock;
            $pItemProject->ItemDay = $ItemDay;
            $pItemProject->ItemPrice = $ItemPrice;
            $pItemProject->ItemTimePrice = $ItemTimePrice;
            $pItemProject->ItemBonusPointPrice = $ItemBonusPointPrice;
            $pItemProject->Item_Resell = $Item_Resell;
            $pItemProject->ItemDrop = $ItemDrop;
            $pItemProject->Item_MaxReborn = $Item_MaxReborn;
            
            $pItemList->AddItem($pItemProject);
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        CInput::GetInstance()->AddValue( $CURRENT_SESSION_ITEMLIST , serialize($pItemList) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
    }
?>
<script type="text/javascript">
<?php
echo "var sublistdata = [";
for( $i = 0 ; $i < $pItemList->GetRoll() ; $i++ )
{
    $pItemProject = $pItemList->GetData($i);
    
    printf( "[ %d , %d , %d , %d , %d , '%s' , %d , %d , %d , %d , %d , '%s' , '%s' , '%s' , %d , %d , %d , %d , %d ] , "
            , $pItemProject->ItemNum
            , $pItemProject->SubNum
            , $pItemProject->ItemType
            , $pItemProject->ItemMain
            , $pItemProject->ItemSub
            , $pItemProject->ItemName
            , $pItemProject->ItemShow
            , $pItemProject->ItemSell
            , $pItemProject->ItemSock
            , $pItemProject->ItemDay
            , $pItemProject->ItemPrice
            , sprintf( "%03d:%03d" , $pItemProject->ItemMain , $pItemProject->ItemSub )
            , $arrItemType[ $pItemProject->ItemType ]
            , $_CONFIG["ITEMSHOW"][ $pItemProject->ItemShow ]
            , $pItemProject->Item_Resell
            , $pItemProject->ItemDrop
            , $pItemProject->Item_MaxReborn
            , $pItemProject->ItemTimePrice
            , $pItemProject->ItemBonusPointPrice
            );
}
echo "];";
echo "var sublistheader = [";
foreach( $SELECT_FILTER_SUB as $key => $value )
{
    printf( "[ %d , '%s' ] ," , $key , $value );
}
echo "]";
?>
</script>
<div id="main_itemproject">
    <span><b><u>รายการไอเทม</u></b></span>
    Filter : 
<?php
echo buildSelectText("filter", "", 0, $SELECT_FILTER);
echo buildSelectText("filtersub", "", 0, $SELECT_FILTER_SUB);
echo buildSelectText("filtertype", "", 0, $arrItemType);
?>
    <table id="listData" class="gridtable">
        <thead>
            <tr>
                <td style="width:59px;"><div align="center">รหัส</div></td>
                <td style="width:209px;"><div align="center">หมวดหมู่</div></td>
                <!-- <td style="width:109px;"><div align="center">ประเภท</div></td> -->
                <td style="width:279px;"><div align="center">ชื่อ</div></td>
                <!-- <td style="width:59px;"><div align="center">ราคา</div></td> -->
                <!-- <td style="width:69px;"><div align="center">จำหน่าย</div></td> -->
                <!-- <td style="width:69px;"><div align="center">คงเหลือ</div></td> -->
                <td style="width:669px;"><div align="center">สถานะ</div></td>
                <td style="width:159px;"><div align="center">ควบคุม</div></td>
            </tr>
        </thead>
        <tbody>
<?php
/*
for( $i = 0 ; $i < $pItemList->GetRoll() ; $i++ )
{
    $pItemProject = $pItemList->GetData($i);
    $SubCode = sprintf( "%03d:%03d" , $pItemProject->ItemMain , $pItemProject->ItemSub );
    printf( "<tr><td><div align=\"center\">%s</div></td>
            <td><div align=\"center\">%s</div></td>
            <td><div align=\"left\">%s</div></td>
            <td><div align=\"center\">%d</div></td>
            <td><div align=\"center\">%d</div></td>
            <td><div align=\"center\">%d</div></td>
            <td><div align=\"center\">%s</div></td>
            <td><div align=\"center\"><button onclick=\"editProject(this,%d);\">แก้ไข</button><button onclick=\"delProject(this,%d);\">ลบ</button></div></td></tr>"
            , $SubCode
            , $arrItemType[ $pItemProject->ItemType ]
            , $pItemProject->ItemName
            , $pItemProject->ItemPrice
            , $pItemProject->ItemSell
            , $pItemProject->ItemSock
            , $_CONFIG["ITEMSHOW"][ $pItemProject->ItemShow ]
            , $pItemProject->ItemNum
            , $pItemProject->ItemNum
            );
}
 */
?>
        </tbody>
    </table>
    <div id="main_itemlist" style="display: none;"></div>
</div>
<script type="text/javascript" src="js/itemproject.js"></script>
<?php
}

$type = CInput::GetInstance()->GetValueInt( "type" , IN_GET );
switch( $type )
{
    case 1000:
    {
        CMD_DEL_PROC();
    }break;
    case 1101:
    {
        CMD_UPLOAD_IMG( $CURRENT_SESSION_UPLOAD_IMG );
    }break;
    case 10000:
    {
        CMD_EDIT_UI();
    }break;
    case 10001:
    {
        CMD_EDIT_PROCESS();
    }break;
    default:
    {
        CMD_UI();
    }break;
}
?>
