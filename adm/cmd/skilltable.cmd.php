<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");

CInput::GetInstance()->BuildFrom( IN_POST );

function CMD_UPLOAD_PROC()
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    
    CInput::GetInstance()->BuildFrom( IN_FILES );
    $pFile = CInput::GetInstance()->GetValue( "Filedata" , IN_FILES );
    if ( !$pFile ) die( "ERROR:FILE" );
    
    if (!isset($pFile) || !is_uploaded_file($pFile["tmp_name"]) || $pFile["error"] != 0) die( "ERROR:invalid upload" );
    
    $lines = file( $pFile["tmp_name"] );
    if ( !$lines ) die( "ERROR:FILE:FILE" );
    
    $strquery = array();
    
	$nii = 0;
    foreach ($lines as $line_num => $line)
    {
        /*
         * SN_XXX_XXX NAME
         */
        if ( "SN_" == substr($line, 0 , 3) )
        {
            $get_itemmain =  @CNeoInject::sec_Int( substr($line,3,3) );
            $get_itemsub =  @CNeoInject::sec_Int( substr($line,7,3) );
            $line = substr( $line , 10 );
            $line = trim($line);
            if ( empty($line) ) continue;
            $skillid = sprintf( "SN_%03d_%03d" , $get_itemmain , $get_itemsub );
            $skillname = $line;
            CInput::GetInstance()->BuildVar( $skillname );
            $skillname = @CNeoInject2::sec_Thai( $skillname );
            $strquery[$nii] = sprintf( "INSERT INTO SkillTable( MemNum,SkillID,SkillName ) VALUES( %d,'%s','%s' )" , $MemNum , $skillid , $skillname );
            //printf( "%03d : %03d == %s => %s<br>" , $get_itemmain , $get_itemsub , $skillid , $skillname );
			$nii++;
        }
    }
    
    //echo $strquery;
    
    fclose( $lines );
    unlink( $pFile["tmp_name"] );
    if ( empty( $strquery ) || $strquery == "" ) die("ERROR:FILE:DATA:NONE");
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = sprintf("DELETE SkillTable WHERE MemNum = %d",$MemNum);
    $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
	for( $i = 0 ; $i < $nii ; $i++ )
	{
	    $cNeoSQLConnectODBC->QueryRanWeb( $strquery[ $i ] );
	}
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    //clear memory data list
    $CURRENT_SESSION = sprintf( "%d_skilltablelist" , $MemNum );
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( $pSkillData ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CMD_UPLOAD_UI()
{
    global $cAdmin;
    $MemNum = $cAdmin->GetMemNum();
    $CURRENT_SESSION = sprintf( "%d_skilltableupload" , $MemNum );
    phpFastCache::set($CURRENT_SESSION, $MemNum , 60*5); // 5 นาที
?>
<div id="app_ui" style="margin: 19px;">
    <div>กรุณาเลือกไฟล์ที่ต้องการอัพโหลดและอย่าลืมอ่านเงื่อนไขข้างบนก่อน!!</div>
    <div id="upload">
        <div style="float: left;">ขนาดไฟล์ไม่เกิน 2MB ชนิดไฟล์ .txt</div>
        <div id="app_upload_ui" style="margin-left:9px;"><button type="button" id="uploader">อัพโหลด</button></div>
        <div style="clear: left;"></div>
    </div>
    <div id="process"></div>
</div>

<input type="hidden" id="upload_UI" value="1">

<script type="text/javascript" src="../js/upclick.js"></script>
<script type="text/javascript" src="js/skilltable.js"></script>

<?php
}

function CMD_UI()
{
?>
<div class="main_skilltable">
    <div id="info" class="info">
        <p>เมนูนี้จะเป็นการตั้งชื่อสกิล<br>
        <b>ถ้าคุณเลือกที่จะอัพโหลด</b>จากไฟล์คุณจำเป็นจะต้องถอดรหัสไฟล์ให้ Notepad สามารถเปิดอ่านได้<br>
        มิเช่นนั้นจะไม่สามารถทำรายการได้<br>
        <b><u>สำคัญมาก!!</u></b>การอัพโหลดจากไฟล์นั้นถ้าคุณเคยมีการจัดการเกี่ยวกับชื่อสกิลไว้ก่อนจะถูกล้างออกทั้งหมด
        </p>
    </div>
    <div>
        กรุณาเลือกช่องทางที่จะทำรายการ <button id="uploadSet">อัพโหลดจากไฟล์</button><button id="showSkillList">แสดงรายชื่อสกิล</button>
    </div>
    <div id="main_skill">
    </div>
</div>

<script type="text/javascript" src="js/skilltable.js"></script>
<?php
}

function CMD_SHOWSKILLIST()
{
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
        $szTemp = sprintf("SELECT SkillID,SkillName FROM SkillTable WHERE MemNum = %d",$MemNum);
        //echo $szTemp;
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
    
    printf( "<script type=\"text/javascript\">" );
    printf( "var showRoll = 0 , showDataInfo = [" );
    
    for( $i = 0 ; $i < $pSkillData->GetRollData() ; $i++ )
    {
        $ppData = $pSkillData->GetData($i);
        $skillid = $ppData->SkillID;
        $skillname = $ppData->SkillName;
        printf( "[ \"%s\" , \"%s\" ],"
                , $skillid
                , $skillname
                );
    }
    
    printf( "]" );
    printf( "</script>" );
    
    printf( "<table id=\"showInfoTable\">
        <tbody>
            <tr><td style=\"width:99px;\">รหัสสกิล</<td><td style=\"width:199px;\">ชื่อสกิล</td></tr>
            <tr>
                <td colspan=\"2\"><button onclick=\"showSkillTable(this);\">แสดงข้อมูลเพิ่มเติม</button></td>
            </tr>
        </tbody>
        <tfoot></tfoot>
        </table>" );
}

$type= CInput::GetInstance()->GetValueInt( "type" , IN_GET );
switch( $type )
{
    case 77:
    {
        CMD_SHOWSKILLIST();
    }break;

    case 101:
    {
        CMD_UPLOAD_UI();
    }break;
    case 1101:
    {
        CMD_UPLOAD_PROC();
    }break;

    default :
    {
        CMD_UI();
    }break;
}
?>
