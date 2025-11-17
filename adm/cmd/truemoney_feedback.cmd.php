<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");
if ( !$cAdmin->GetLoginPassCard() )
{
    require_once 'password_security.cmd.php';
    die("");
}

CInput::GetInstance()->BuildFrom( IN_POST );

$CURRENT_SESSION = "ADM_FEEDBACK_TRUEMONEY";

$MemNum = $cAdmin->GetMemNum();

$pItemPoint = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
if ( !$pItemPoint )
{
    $pItemPoint = new CItemPoint( $MemNum );
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pItemPoint) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
}

function CMD_PROC( $n )
{
    global $pItemPoint;
    global $CURRENT_SESSION;
    
    $ItemCount = CInput::GetInstance()->GetValueInt( "itemcount" , IN_POST );
    if ( $ItemCount < 0 || $ItemCount > ITEMPOINT_GET_FREE_BONUS ) die( "ERROR|OVERLOAD" );
    
    $pItemPoint->SetItemNum( $n , $ItemCount );
    
    for( $i = 1 ; $i <= $ItemCount ; $i ++ )
    {
        $key_m = CInput::GetInstance()->GetValueInt( "m". $i , IN_POST ) ;
        $key_s = CInput::GetInstance()->GetValueInt( "s". $i , IN_POST ) ;
        
        //echo $key_m . $key_s;
        
        $pItemPoint->SetItemMain( $n , $i-1 , $key_m );
        $pItemPoint->SetItemSub( $n , $i-1 , $key_s );
    }
    
    $pItemPoint->UpdateToBinary();
    $pItemPoint->Save();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pItemPoint) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CMD_UI( $n )
{
    global $pItemPoint;
    
    if ( $n < 0 || $n > 5 ) die("ERROR|SLOT");
    $card_array = array( 50 , 90 , 150 , 300 , 500 , 1000 );
    
    $NowItemEn = $pItemPoint->GetItemNum( $n );
?>

<table id="itemlistmain">
    <tr>
        <td colspan='2'><b><u>โบนัสไอเทมเมื่อเติมบัตรในราคา <?php echo $card_array[$n]; ?></u></b></td>
    </tr>
    <tr>
        <td colspan='2'>จำนวนไอเทม <?php printf("<span id=\"NowItemEn\">%d</span>/%d" , $NowItemEn , ITEMPOINT_GET_FREE_BONUS ); ?> ( รหัสไอเทมที่ใส่จะต้องเพิ่มลงในไอเทมช่อง <b><u>B</u></b> ก่อนเท่านั้น ) <button id="added">เพิ่ม</button><button id="deled">ลด</button></td>
    </tr>
    <tr>
        <td align="left">
            ItemMain
        </td>
        <td align="left">
            ItemSub
        </td>
    </tr>
</table>
<div>
    <input type="hidden" id="slot_type" value="<?php echo $n; ?>" />
    <input type="hidden" id="itemcount" value="<?php echo $NowItemEn; ?>" />
    <button id="submit_feedback">ตกลง</button>
    <button id="cancel_feedback">ย้อนกลับ</button>
</div>

<script type="text/javascript">
    
var showitemmax = <?php echo ITEMPOINT_GET_FREE_BONUS; ?>;
//var itemcount = <?php echo $NowItemEn; ?>;

<?php
$argList = "";
for( $i = 0 ; $i < $NowItemEn ; ++$i )
{
    if ( $i > 0 ) $argList .= ",";
    $argList .= "[" . $pItemPoint->GetItemMain( $n , $i ) . " , " . $pItemPoint->GetItemSub( $n , $i ) . "]";
}
//$argList = "\"[" . $argList . "];\"";
$argList = "[" . $argList . "];";
echo "var argList = ".$argList;
?>

for( var i = 0 ; i < argList.length ; i++ )
{
    addItemTable( i+1 , argList[i][0] , argList[i][1] );
}

</script>

<script type="text/javascript" src="js/truemoney.js"></script>

<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
$n = CInput::GetInstance()->GetValueInt( "i" , IN_POST );
if ( $submit )
    CMD_PROC( $n );
else
    CMD_UI( $n );

?>
