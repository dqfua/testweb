<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

$SELECT_OPTION = array( 0 => "ไม่เปิดใช้งาน" , 1 => "เปิดใช้งาน" );

class MapWarp
{
    public $bMapOpen = false;
    public $pMapList = NULL;
};

$CURRENT_SESSION = sprintf( "%d_mapwarpdataset" , $cAdmin->GetMemNum() );

function CMD_STATUS_CHANGE()
{
    global $SELECT_OPTION;
    global $CURRENT_SESSION;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pMapList = unserialize( CInput::GetInstance()->GetValue($CURRENT_SESSION,IN_SESSION) );
    if ( !$pMapList ) die("ERROR|SESSION|MAPLIST|NONE");
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $bMapOpen = CInput::GetInstance()->GetValueInt( "mapopen" , IN_POST );
    
    if ( !arrkeycheck( $SELECT_OPTION , $bMapOpen ) ) die("ERROR|OPTION");
    
    global $cAdmin;
    global $CURRENT_SESSION;
    $MemNum = $cAdmin->GetMemNum();
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $cNeoSQLConnectODBC->QueryRanWeb( sprintf( "UPDATE MemMapSET SET MapOpen = %d WHERE MemNum = %d" , $bMapOpen , $MemNum ) );
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    $pMapList->bMapOpen = $bMapOpen;
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pMapList) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CMD_EDIT_PROC()
{
    global $SELECT_OPTION;
    global $CURRENT_SESSION;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pMapList = unserialize( CInput::GetInstance()->GetValue($CURRENT_SESSION,IN_SESSION) );
    if ( !$pMapList ) die("ERROR|SESSION|MAPLIST|NONE");
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $MapID = CInput::GetInstance()->GetValueString( "mapid" , IN_POST );
    $MapName = CInput::GetInstance()->GetValueString( "mapname" , IN_POST );
    $MapMain = CInput::GetInstance()->GetValueInt( "mapmain" , IN_POST );
    $MapSub = CInput::GetInstance()->GetValueInt( "mapsub" , IN_POST );
    $MapPoint = CInput::GetInstance()->GetValueInt( "mappoint" , IN_POST );
    
    if ( $MapName == "" || empty( $MapName ) ) die( "ERROR|MAPNAME" );
    
    $pMapList->pMapList->EditMap( $MapID , $MapName , $MapMain , $MapSub , $MapPoint );
    $pMapList->pMapList->Save();
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pMapList) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CMD_EDIT_UI()
{
    global $CURRENT_SESSION;
    
    $pMapList = unserialize( CInput::GetInstance()->GetValue($CURRENT_SESSION,IN_SESSION) );
    if ( !$pMapList ) die("ERROR|SESSION|MAPLIST|NONE");
    CInput::GetInstance()->BuildFrom( IN_POST );
    $IDMain = CInput::GetInstance()->GetValueString( "idmain" , IN_POST );
    $IDSub = CInput::GetInstance()->GetValueInt( "idsub" , IN_POST );
    
    $MapID = $pMapList->pMapList->FindMap( $IDMain , $IDSub );
    if ( $MapID == MAP_ERROR ) die( "ERROR|MAP|FIND|FAILED" );
    
    $MapName = $pMapList->pMapList->MapName[ $MapID ];
    $MapMain = $pMapList->pMapList->MapMain[ $MapID ];
    $MapSub = $pMapList->pMapList->MapSub[ $MapID ];
    $MapPoint = $pMapList->pMapList->MapPoint[ $MapID ];
?>
<table>
    <tr>
        <td style="width:159px;">ชื่อแผนที่</td>
        <td style="width:199px;"><input type="text" id="mapname" style="width:199px;" value="<?php echo $MapName; ?>"></td>
    </tr>
    <tr>
        <td>รหัสแผนที่</td>
        <td>Main : <input type="text" id="mapmain" style="width:39px;" value="<?php echo $MapMain; ?>"> Sub : <input type="text" id="mapsub" style="width:39px;" value="<?php echo $MapSub; ?>"></td>
    </tr>
    <tr>
        <td>พ้อยในการเข้าแผนที่</td>
        <td><input type="text" id="mappoint" style="width:39px;" value="<?php echo $MapPoint; ?>"></td>
    </tr>
    <tr>
        <td colspan="2"><button id="editMap">แก้ไจ</button></td>
    </tr>
</table>
<input type="hidden" id="mapid" value="<?php echo $MapID; ?>">
<script type="text/javascript" src="js/mapwarp.js"></script>
<?php
}

function CMD_ADD_SUBMIT()
{
    global $SELECT_OPTION;
    global $CURRENT_SESSION;
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    $pMapList = unserialize( CInput::GetInstance()->GetValue($CURRENT_SESSION,IN_SESSION) );
    if ( !$pMapList ) die("ERROR|SESSION|MAPLIST|NONE");
    
    CInput::GetInstance()->BuildFrom( IN_POST );
    $MapName = CInput::GetInstance()->GetValueString( "mapname" , IN_POST );
    $MapMain = CInput::GetInstance()->GetValueInt( "mapmain" , IN_POST );
    $MapSub = CInput::GetInstance()->GetValueInt( "mapsub" , IN_POST );
    $MapPoint = CInput::GetInstance()->GetValueInt( "mappoint" , IN_POST );
    
    if ( $MapName == "" || empty( $MapName ) ) die( "ERROR|MAPNAME" );
    
    
    if ( $pMapList->pMapList->FindMap( $MapMain , $MapSub ) == MAP_ERROR )
    {
        $pMapList->pMapList->AddMap( $MapName , $MapMain , $MapSub , $MapPoint );
        $pMapList->pMapList->Save();
    }
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pMapList) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CMD_LIST_DEL()
{
    global $CURRENT_SESSION;
    
    $pMapList = unserialize( CInput::GetInstance()->GetValue($CURRENT_SESSION,IN_SESSION) );
    if ( !$pMapList ) die("ERROR|SESSION|MAPLIST|NONE");
    CInput::GetInstance()->BuildFrom( IN_POST );
    $IDMain = CInput::GetInstance()->GetValueString( "idmain" , IN_POST );
    $IDSub = CInput::GetInstance()->GetValueInt( "idsub" , IN_POST );
    
    $MapID = $pMapList->pMapList->FindMap( $IDMain , $IDSub );
    $pMapList->pMapList->DelMap( $MapID );
    $pMapList->pMapList->Save();
    
    
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pMapList) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CMD_LIST_UI()
{
    global $CURRENT_SESSION;
    $pMapList = unserialize( CInput::GetInstance()->GetValue($CURRENT_SESSION,IN_SESSION) );
    if ( !$pMapList ) die("ERROR|SESSION|MAPLIST|NONE");
?>
<table>
    <tr>
        <td style="width:199px;">ชื่อแผนที่</td>
        <td style="width:159px;">รหัสแผนที่</td>
        <td style="width:159px;">ราคาวาร์ป</td>
        <td style="width:159px;">การจัดการ</td>
    </tr>
<?php
for( $i = 0 ; $i < $pMapList->pMapList->MapNum ; $i++ )
{
?>
    <tr>
        <td><?php echo $pMapList->pMapList->MapName[ $i ]; ?></td>
        <td><?php printf("%d:%d" , $pMapList->pMapList->MapMain[ $i ] , $pMapList->pMapList->MapSub[ $i ] ); ?></td>
        <td><?php echo $pMapList->pMapList->MapPoint[ $i ]; ?></td>
        <td><button onclick="editList(this);" value="<?php printf("%d:%d" , $pMapList->pMapList->MapMain[ $i ] , $pMapList->pMapList->MapSub[ $i ] ); ?>">แก้ไข</button> | <button onclick="delList(this);" value="<?php printf("%d:%d" , $pMapList->pMapList->MapMain[ $i ] , $pMapList->pMapList->MapSub[ $i ] ); ?>">ลบ</button></td>
    </tr>
<?php
}
?>
</table>

<script type="text/javascript" src="js/mapwarp.js"></script>

<?php
}

function CMD_ADD_UI()
{
?>
<table>
    <tr>
        <td style="width:159px;">ชื่อแผนที่</td>
        <td style="width:199px;"><input type="text" id="mapname" style="width:199px;"></td>
    </tr>
    <tr>
        <td>รหัสแผนที่</td>
        <td>Main : <input type="text" id="mapmain" style="width:39px;"> Sub : <input type="text" id="mapsub" style="width:39px;"></td>
    </tr>
    <tr>
        <td>พ้อยในการเข้าแผนที่</td>
        <td><input type="text" id="mappoint" style="width:39px;"></td>
    </tr>
    <tr>
        <td colspan="2"><button id="addMap">เพิ่ม</button></td>
    </tr>
</table>

<script type="text/javascript" src="js/mapwarp.js"></script>

<?php
}

function CMD_UI()
{
    global $SELECT_OPTION;
    global $cAdmin;
    global $CURRENT_SESSION;
    $MemNum = $cAdmin->GetMemNum();
    
    $pMapList = unserialize( CInput::GetInstance()->GetValue($CURRENT_SESSION,IN_SESSION) );
    if ( !$pMapList )
    {
        $pMapList = new MapWarp;
        $szTemp = sprintf("IF NOT EXISTS( SELECT MapSetNum,MapOpen,MapList FROM MemMapSet WHERE MemNum = %d )
                            BEGIN
                                INSERT INTO MemMapSet( MemNum,MapOpen,MapList ) VALUES( %d,0,NULL )
                                SELECT MapSetNum,MapOpen,MapList FROM MemMapSet WHERE MemNum = %d
                            END
                            ELSE
                            BEGIN
                                SELECT MapSetNum,MapOpen,MapList FROM MemMapSet WHERE MemNum = %d
                            END
                            " , $MemNum , $MemNum , $MemNum , $MemNum );

        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC();
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
        while( $cNeoSQLConnectODBC->FetchRow() )
        {
            $bMapOpen = $cNeoSQLConnectODBC->Result("MapOpen",ODBC_RETYPE_INT);
            $pMapList->bMapOpen = $bMapOpen;
        }
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        $cMapList = new CMapList;
        $cMapList->LoadMapData($MemNum);
        $pMapList->pMapList = $cMapList;
        
        //echo $pMapList->pMapList->MapNum;
        
        CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize($pMapList) , IN_SESSION );
        CInput::GetInstance()->UpdateSession();
    }
?>

<div id="main_mapwarp">
    <table>
        <tr>
            <td style="width:159px;">สถานะ</td>
            <td style="width:599px;">
                <?php echo buildSelectText( "status", "status", $pMapList->bMapOpen, $SELECT_OPTION); ?>
            </td>
        </tr>
        <tr>
            <td>เมนู</td>
            <td><button id="txtAdd">เพิ่ม</button> | <button id="txtEdit">แก้ไข</button></td>
        </tr>
        <tr>
            <td colspan="2"><div id="process"></div></td>
        </tr>
    </table>
</div>

<script type="text/javascript" src="js/mapwarp.js"></script>

<?php
}

$type = CInput::GetInstance()->GetValueInt("type",IN_GET);

switch( $type )
{
    case 1001:
    {
        CMD_STATUS_CHANGE();
    }break;

    case 1100:
    {
        CMD_ADD_UI();
    }break;

    case 1101:
    {
        CMD_ADD_SUBMIT();
    }break;

    case 1200:
    {
        CMD_LIST_UI();
    }break;

    case 1201:
    {
        CMD_LIST_DEL();
    }break;

    case 1202:
    {
        CMD_EDIT_UI();
    }break;

    case 1203:
    {
        CMD_EDIT_PROC();
    }break;

    default:
    {
        CMD_UI();
    }break;
}
?>
