<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

include("itemproject.cmd.php");

CInput::GetInstance()->BuildFrom( IN_POST );

function CMD_PROC()
{
    global $cAdmin;
    
    $MemNum = $cAdmin->GetMemNum();
    
    $choose_type = CInput::GetInstance()->GetValueInt( "choose_type" , IN_POST );
    $subname = CInput::GetInstance()->GetValueString( "text" , IN_POST );
    
    if ( $choose_type < 0 || $choose_type > 2 ) die("ERROR|OVERLOAD");
    
    $subrank = CNeoInject::sec_Int( $subname );
    
    //utf-8 to tis620
    $subname = CBinaryCover::utf8_to_tis620( $subname );
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = sprintf( "INSERT SubProject(MemNum , SubName , SubShow , SubRollRank ) VALUES( %d,'%s',%d,'%s' )" , $MemNum , $subname , $choose_type , $subrank );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    $CURRENT_SESSION = "ADM_SUB_ITEM_DATA";
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , NULL , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    $CURRENT_SESSION = CurrentSession_Sublist($MemNum);
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , NULL , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CMD_UI()
{
?>

<div class="main_sub_item">

<p><b><u>เพิ่มหมวดหมู่ไอเทม</u></b></p>
<table>
    <tr>
        <td align="right" style="width:99px;">ชื่อหมวดหมู่ :</td>
        <td align="left" style="width:139px;"><input type="text" id="subname" class="edittext" /></td>
    </tr>
    <tr>
        <td align="right">
            สถานะ
        </td>
        <td>
            <select id="choose_type">
                <option value="0" selected="selected">แสดง</option>
                <option value="1">ซ่อน</option>
                <option value="2">เฉพาะ GM</option>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <button id="submit_sub_item_add">เพิ่ม</button>
        </td>
    </tr>
</table>

</div>

<script type="text/javascript" src="js/sub_item.js"></script>
<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $submit )
    CMD_PROC();
else
    CMD_UI();

?>
