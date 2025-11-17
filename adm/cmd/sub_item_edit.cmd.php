<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

include("itemproject.cmd.php");

CInput::GetInstance()->BuildFrom( IN_POST );

$CURRENT_SESSION = "ADM_SUB_ITEM_DATA";

function CMD_PROC( $subnum )
{
    global $cAdmin;
    global $CURRENT_SESSION;
    $MemNum = $cAdmin->GetMemNum();
    
    $choose_type = CInput::GetInstance()->GetValueInt( "choose_type" , IN_POST );
    $applytoallitem = CInput::GetInstance()->GetValueInt( "applytoallitem" , IN_POST );
    $subname = CInput::GetInstance()->GetValueString( "text" , IN_POST );
    
    if ( $choose_type < 0 || $choose_type > 2 ) die("ERROR|OVERLOAD");
    
    $subrank = CNeoInject::sec_Int( $subname );
    
    //utf-8 to tis620
    $subname = CBinaryCover::utf8_to_tis620( $subname );
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = sprintf( "UPDATE SubProject SET SubName = '%s' , SubShow = %d , SubRollRank = %d WHERE SubNum = %d AND MemNum = %d" , $subname , $choose_type , $subrank , $subnum , $MemNum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    
    if ( $applytoallitem == 1 )
    {
        $szTemp = sprintf( "UPDATE ItemProject SET ItemShow = %d WHERE MemNum = %d AND SubNum = %d" , $choose_type , $MemNum , $subnum );
        $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    }
    
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , NULL , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    $CURRENT_SESSION = CurrentSession_Sublist($MemNum);
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , NULL , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    printf( "SUCCESS:%d" , $applytoallitem );
}

function CMD_UI( $subnum )
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();

    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = sprintf( "SELECT TOP 1 SubName,SubShow FROM SubProject WHERE MemNum = %d AND SubDel = 0 AND SubNum = %d" , $MemNum , $subnum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    
    $SubName = "";
    $SubType = 0;
    
    while( $cNeoSQLConnectODBC->FetchRow() )
    {
        $SubName = $cNeoSQLConnectODBC->Result("SubName",ODBC_RETYPE_THAI);
        $SubType = $cNeoSQLConnectODBC->Result("SubShow",ODBC_RETYPE_INT);

        $SubName = CBinaryCover::tis620_to_utf8( $SubName );
    }

    $cNeoSQLConnectODBC->CloseRanWeb();
?>

<p><b><u>แก้ไขหมวดหมู่ไอเทม</u></b></p>
<table>
    <tr>
        <td align="right" style="width:99px;">ชื่อหมวดหมู่ :</td>
        <td align="left" style="width:299px;"><input type="text" id="subname" class="edittext" value="<?php echo $SubName; ?>" /></td>
    </tr>
    <tr>
        <td align="right">
            สถานะ
        </td>
        <td>
            <?php echo buildSelectText("choose_type", "choose_type", $SubType, ItemShowTypeData() ); ?>
            <input type="checkbox" id="applytoallitem" value="0"> ทำกับไอเทมในหมวดหมู่นี้ทั้งหมด
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="hidden" id="subnum" value="<?php echo $subnum; ?>"/>
            <button id="submit_sub_item_edit">แก้ไข</button>
        </td>
    </tr>
</table>

<script type="text/javascript" src="js/sub_item.js"></script>
<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
$subnum = CInput::GetInstance()->GetValueInt( "subnum" , IN_POST );
if ( $submit )
    CMD_PROC( $subnum );
else
    CMD_UI( $subnum );
?>
