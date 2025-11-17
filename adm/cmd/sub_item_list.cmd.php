<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

include("itemproject.cmd.php");
require_once 'subproject.cmd.php';

CInput::GetInstance()->BuildFrom( IN_POST );
$CURRENT_SESSION = $CURRENT_SESSION_LISTDATA;

function CMD_PROC() // do delete sub
{
    global $cAdmin;
    global $CURRENT_SESSION;
    
    $MemNum = $cAdmin->GetMemNum();
    $SubNum = CInput::GetInstance()->GetValueInt( "subnum" , IN_POST );
    if ( $SubNum <= 0 ) die("ERROR|OVERLOAD");
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = sprintf( "UPDATE SubProject SET SubDel = 1 WHERE MemNum = %d AND SubNum = %d" , $MemNum , $SubNum );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    $cNeoSQLConnectODBC->CloseRanWeb();
    
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

<div id="main_sub_item" class="main_sub_item">
    
<table id="sublist">
    <tr>
        <td style="width:139px" align="left">
            <b><u>ชื่อหมวดหมู่</u></b>
        </td>
        <td style="width:139px;" align="left">
            <b><u>สถานะ</u></b>
        </td>
    </tr>
<?php
for( $i = 0 ; $i < count( SubMain::GetInstance()->GetData() ) ; $i++ )
{
    $pData = SubMain::GetInstance()->GetData();
    printf( "<tr>
        <td>
            %s
        </td>
        <td>
            <button onclick=\"EditTo(this,%d);\">แก้ไข</button id='test'><button onclick=\"DelTo(this,%d);\">ลบ</button>
        </td>
    </tr>" , $pData[ $i ]->SubName , $pData[ $i ]->SubNum , $pData[ $i ]->SubNum );
}
?>
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
