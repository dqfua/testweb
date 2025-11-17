<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");
if ( !$cAdmin->GetLoginPassCard() )
{
    require_once 'password_security.cmd.php';
    die("");
}

CInput::GetInstance()->BuildFrom( IN_POST );

$CURRENT_SESSION = "ADM_TRUEMONEY";

class __Truemoney
{
    public $t0;
    public $t1;
    public $t2;
    public $t3;
    public $t4;
    public $t5;
    
    public $r0;
    public $r1;
    public $r2;
    public $r3;
    public $r4;
    public $r5;
    
    public $b0;
    public $b1;
    public $b2;
    public $b3;
    public $b4;
    public $b5;
    
    public $id;
    
    public function __construct( $t0 , $t1 , $t2 , $t3 , $t4 , $t5 , $r0 , $r1 , $r2 , $r3 , $r4 , $r5 , $b0 , $b1 , $b2 , $b3 , $b4 , $b5 , $id )
    {
        $this->t0 = $t0;
        $this->t1 = $t1;
        $this->t2 = $t2;
        $this->t3 = $t3;
        $this->t4 = $t4;
        $this->t5 = $t5;
        
        $this->r0 = $r0;
        $this->r1 = $r1;
        $this->r2 = $r2;
        $this->r3 = $r3;
        $this->r4 = $r4;
        $this->r5 = $r5;
        
        $this->b0 = $b0;
        $this->b1 = $b1;
        $this->b2 = $b2;
        $this->b3 = $b3;
        $this->b4 = $b4;
        $this->b5 = $b5;
        
        $this->id = $id;
    }
};

class Truemoney// extends __Truemoney
{
    private $pData;
    private static $Instance;
    
    public static function GetInstance()
    {
        if ( !self::$Instance )
            self::$Instance = new self();
        return self::$Instance;
    }
    
    public function __construct() {
        ;
    }
    
    public function GetData(){ return $this->pData; }
    public function SetData( $data ) { $this->pData = $data; }
    
    public function GetValue( $type )
    {
        switch( $type )
        {
            case 0: return $this->pData->t0; break;
            case 1: return $this->pData->t1; break;
            case 2: return $this->pData->t2; break;
            case 3: return $this->pData->t3; break;
            case 4: return $this->pData->t4; break;
            case 5: return $this->pData->t5; break;
            case 6: return $this->pData->id; break;
            default: return 0;
        
        }
    }
	
    public function GetValue2( $type )
    {
        switch( $type )
        {
            case 0: return $this->pData->r0; break;
            case 1: return $this->pData->r1; break;
            case 2: return $this->pData->r2; break;
            case 3: return $this->pData->r3; break;
            case 4: return $this->pData->r4; break;
            case 5: return $this->pData->r5; break;
            case 6: return $this->pData->id; break;
            default: return 0;
        
        }
    }
    
    public function GetValue3( $type )
    {
        switch( $type )
        {
            case 0: return $this->pData->b0; break;
            case 1: return $this->pData->b1; break;
            case 2: return $this->pData->b2; break;
            case 3: return $this->pData->b3; break;
            case 4: return $this->pData->b4; break;
            case 5: return $this->pData->b5; break;
            case 6: return $this->pData->id; break;
            default: return 0;
        
        }
    }
    
    public function UpdateFromDB()
    {
        global $cAdmin;
        global $CURRENT_SESSION;
        
        $MemNum = $cAdmin->GetMemNum();
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp_SELECT = sprintf("SELECT MemNum,Point_50,Point_90,Point_150,Point_300,Point_500,Point_1000,RePoint_50,RePoint_90,RePoint_150,RePoint_300,RePoint_500,RePoint_1000,BonusPoint_50,BonusPoint_90,BonusPoint_150,BonusPoint_300,BonusPoint_500,BonusPoint_1000,merchant_id FROM MemPoint  WHERE MemNum = %d  ",$MemNum);
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp_SELECT );
        $_MemNum = $cNeoSQLConnectODBC->Result( "MemNum" , ODBC_RETYPE_INT );
        if ( $_MemNum <= 0 || empty( $_MemNum ) )
        {
            $szTemp = sprintf("INSERT INTO MemPoint(MemNum) VALUES(%d)",$MemNum);
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
            $cNeoSQLConnectODBC->QueryRanWeb( $szTemp_SELECT );
        }

        $Point_50 = $cNeoSQLConnectODBC->Result( "Point_50" , ODBC_RETYPE_INT );
        $Point_90 = $cNeoSQLConnectODBC->Result( "Point_90" , ODBC_RETYPE_INT );
        $Point_150 = $cNeoSQLConnectODBC->Result( "Point_150" , ODBC_RETYPE_INT );
        $Point_300 = $cNeoSQLConnectODBC->Result( "Point_300" , ODBC_RETYPE_INT );
        $Point_500 = $cNeoSQLConnectODBC->Result( "Point_500" , ODBC_RETYPE_INT );
        $Point_1000 = $cNeoSQLConnectODBC->Result( "Point_1000" , ODBC_RETYPE_INT );
        
        $RePoint_50 = $cNeoSQLConnectODBC->Result( "RePoint_50" , ODBC_RETYPE_INT );
        $RePoint_90 = $cNeoSQLConnectODBC->Result( "RePoint_90" , ODBC_RETYPE_INT );
        $RePoint_150 = $cNeoSQLConnectODBC->Result( "RePoint_150" , ODBC_RETYPE_INT );
        $RePoint_300 = $cNeoSQLConnectODBC->Result( "RePoint_300" , ODBC_RETYPE_INT );
        $RePoint_500 = $cNeoSQLConnectODBC->Result( "RePoint_500" , ODBC_RETYPE_INT );
        $RePoint_1000 = $cNeoSQLConnectODBC->Result( "RePoint_1000" , ODBC_RETYPE_INT );
        
        $BonusPoint_50 = $cNeoSQLConnectODBC->Result( "BonusPoint_50" , ODBC_RETYPE_INT );
        $BonusPoint_90 = $cNeoSQLConnectODBC->Result( "BonusPoint_90" , ODBC_RETYPE_INT );
        $BonusPoint_150 = $cNeoSQLConnectODBC->Result( "BonusPoint_150" , ODBC_RETYPE_INT );
        $BonusPoint_300 = $cNeoSQLConnectODBC->Result( "BonusPoint_300" , ODBC_RETYPE_INT );
        $BonusPoint_500 = $cNeoSQLConnectODBC->Result( "BonusPoint_500" , ODBC_RETYPE_INT );
        $BonusPoint_1000 = $cNeoSQLConnectODBC->Result( "BonusPoint_1000" , ODBC_RETYPE_INT );
        
        $id = $cNeoSQLConnectODBC->Result( "merchant_id" , ODBC_RETYPE_ENG );
        
        $this->pData = new __Truemoney( $Point_50 , $Point_90 , $Point_150 , $Point_300 , $Point_500 , $Point_1000 , $RePoint_50 , $RePoint_90 , $RePoint_150 , $RePoint_300 , $RePoint_500 , $RePoint_1000 , $BonusPoint_50 , $BonusPoint_90 , $BonusPoint_150 , $BonusPoint_300 , $BonusPoint_500 , $BonusPoint_1000 , $id );

        $cNeoSQLConnectODBC->CloseRanWeb();
    }
};

//$pTruemoney = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
if ( !$pTruemoney )
{
    Truemoney::GetInstance()->UpdateFromDB();
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( TrueMoney::GetInstance()->GetData() ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    //echo "FROM DB";
}else{
    Truemoney::GetInstance()->SetData( $pTruemoney );
    
    //echo "FROM SESSION";
}

function CMD_PROC()
{
    global $cAdmin;
    global $CURRENT_SESSION;
    
    $MemNum = $cAdmin->GetMemNum();
    $id = CInput::GetInstance()->GetValueString( "id" , IN_POST );
    if ( strcmp( Truemoney::GetInstance()->GetValue( 6 ) , $id ) != 0 )
    {
        $cLang = new CLang;
        $cLang->SetDataFromDB($MemNum);
        $mail_subject = "พบมีการเปลี่ยนแปลง Merchart ID จาก {USERNAME} ส่งจาก GameCenterShop";
        $mail_message = "สวัสคุณ <b>{USERNAME}</b><br>พบมีการเปลี่ยนแปลง Merchart ID เมื่อ <b>%s</b> จาก IP <b>%s</b><br>เปลี่ยนแปลงจาก <font color=\"red\">%s</font> => <font color=\"green\">%s</font><br>อีเมล์นี้ส่งมาเพื่อแจ้งการเปลี่ยนข้อมูลสำคัญของผู้เล่นหากคุณไม่ได้เป็นคนเปลี่ยนกรุณาตรวจสอบโดยด่วน!!<br>ลิ้งร้านค้าของคุณคือ : <b><a href={FOLDERSHOP}>{FOLDERSHOP}</a></b>";
        $mail_message = sprintf( $mail_message , GetToday() , CGlobal::getIP() , Truemoney::GetInstance()->GetValue( 6 ) , $id );
        $cLang->TransCode($mail_subject);
        $cLang->TransCode($mail_message);
        //$mail_subject_utf8 = CBinaryCover::tis620_to_utf8( $mail_subject );
        //$mail_message_utf8 = CBinaryCover::tis620_to_utf8( $mail_message );
        //sendMail( $cAdmin->GetEmail() , $mail_subject_utf8 , $mail_message_utf8 );
        sendMail( $cAdmin->GetEmail() , $mail_subject , $mail_message ); // ส่งทางนี้เพราะหน้าแอดมินรันบน utf-8 อยู่แล้ว
    }
    
    $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
    $cNeoSQLConnectODBC->ConnectRanWeb();
    $szTemp = sprintf("UPDATE MemPoint SET Point_50 = %d , Point_90 = %d , Point_150 = %d , Point_300 = %d , Point_500 = %d , Point_1000 = %d , RePoint_50 = %d , RePoint_90 = %d , RePoint_150 = %d , RePoint_300 = %d , RePoint_500 = %d , RePoint_1000 = %d , BonusPoint_50 = %d , BonusPoint_90 = %d , BonusPoint_150 = %d , BonusPoint_300 = %d , BonusPoint_500 = %d , BonusPoint_1000 = %d , merchant_id = '%s' WHERE MemNum = %d"
                                                                    , CInput::GetInstance()->GetValueInt( "t0" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "t1" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "t2" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "t3" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "t4" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "t5" , IN_POST )
            
                                                                    , CInput::GetInstance()->GetValueInt( "r0" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "r1" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "r2" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "r3" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "r4" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "r5" , IN_POST )
            
                                                                    , CInput::GetInstance()->GetValueInt( "b0" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "b1" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "b2" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "b3" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "b4" , IN_POST )
                                                                    , CInput::GetInstance()->GetValueInt( "b5" , IN_POST )
            
                                                                    , $id
                                                                    ,$MemNum
                                      );
    $cNeoSQLConnectODBC->QueryRanWeb($szTemp);
    $cNeoSQLConnectODBC->CloseRanWeb();
    
    echo "SUCCESS";
}

function CMD_UI()
{
?>
<div id='main_truemoney' class='main_truemoney'>
    
    <div id='card' class="card">
      <table width="800" border="0" cellpadding="0" cellspacing="10">
      <tr>
          <td bgcolor="#333333"><b><u>ระบบเติมเงินทาง Truemoney</u></b></td>
          </tr>
          <tr>
            <td><table width="800" border='0' cellpadding='0' cellspacing='10'>
              <tr>
                <td align='right' valign='top' bgcolor="#333333" style='width:139px;'><b>บัตรราคา</b></td>
                <td align='left' valign='top' bgcolor="#333333" style='width:199px;'><b>จำนวนพ้อยที่ได้</b></td>
                <td align='left' valign='top' bgcolor="#333333" style='width:199px;'><b>โบนัสพ้อย Invite Friends</b></td>
                <td align='left' valign='top' bgcolor="#333333" style='width:199px;'><b>แต้มพ้อย</b></td>
                <td align='left' valign='top' bgcolor="#333333" style='width:299px;'>&nbsp;</td>
              </tr>
              <tr>
                <td align='right' valign='middle' bgcolor="#666666">50</td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='t0' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue( 0 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='r0' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue2( 0 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='b0' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue3( 0 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><button id='bt0'>ตั้งโบนัส</button></td>
              </tr>
              <tr>
                <td align='right' valign='middle'>90</td>
                <td align='left' valign='middle'><input type='text' id='t1' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue( 1 ); ?>' /></td>
                <td align='left' valign='middle'><input type='text' id='r1' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue2( 1 ); ?>' /></td>
                <td align='left' valign='middle'><input type='text' id='b1' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue3( 1 ); ?>' /></td>
                <td align='left' valign='middle'><button id='bt1'>ตั้งโบนัส</button></td>
              </tr>
              <tr>
                <td align='right' valign='middle' bgcolor="#666666">150</td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='t2' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue( 2 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='r2' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue2( 2 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='b2' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue3( 2 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><button id='bt2'>ตั้งโบนัส</button></td>
              </tr>
              <tr>
                <td align='right' valign='middle'>300</td>
                <td align='left' valign='middle'><input type='text' id='t3' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue( 3 ); ?>' /></td>
                <td align='left' valign='middle'><input type='text' id='r3' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue2( 3 ); ?>' /></td>
                <td align='left' valign='middle'><input type='text' id='b3' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue3( 3 ); ?>' /></td>
                <td align='left' valign='middle'><button id='bt3'>ตั้งโบนัส</button></td>
              </tr>
              <tr>
                <td align='right' valign='middle' bgcolor="#666666">500</td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='t4' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue( 4 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='r4' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue2( 4 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><input type='text' id='b4' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue3( 4 ); ?>' /></td>
                <td align='left' valign='middle' bgcolor="#666666"><button id='bt4'>ตั้งโบนัส</button></td>
              </tr>
              <tr>
                <td align='right' valign='middle'>1000</td>
                <td align='left' valign='middle'><input type='text' id='t5' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue( 5 ); ?>' /></td>
                <td align='left' valign='middle'><input type='text' id='r5' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue2( 5 ); ?>' /></td>
                <td align='left' valign='middle'><input type='text' id='b5' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue3( 5 ); ?>' /></td>
                <td align='left' valign='middle'><button id='bt5'>ตั้งโบนัส</button></td>
              </tr>
              <tr>
                <td align='right' valign='middle' bgcolor="#666666">Merchart ID</td>
                <td colspan="3" align='left' valign='middle' bgcolor="#666666"><input type='text' id='id' class='edittext' value='<?php echo Truemoney::GetInstance()->GetValue( 6 ); ?>' />                  <b><u>ใช้ของ TMPAY  เท่านั้น!!</u></b></td>
              </tr>
            </table></td>
          </tr>
          <tr>
              <td align="center"><button id='submit_truemoney' onclick="submitTrue();">ตกลง</button></td>
          </tr>
        </table>
    </div>
    <div id='refill_feedback' class='refill_feedback' style="display:none;">
    </div>
    
</div>
<script type="text/javascript" src="js/classicloading.js"></script>
<script type="text/javascript" src="js/truemoney.js"></script>
<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );
if ( $submit )
    CMD_PROC();
else
    CMD_UI();

?>
