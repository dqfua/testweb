<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");
if ( !$cAdmin->GetLoginPassCard() )
{
    require_once 'password_security.cmd.php';
    die("");
}

CInput::GetInstance()->BuildFrom( IN_POST );

$CURRENT_SESSION = "ADM_DATABASE_SESSION";

class __Database
{
    public $IP = "";
    public $USER = "";
    public $PASSWORD = "";
    public $DATABASE = "";
    
    public function __construct( $ip , $user , $pass , $database ) {
        $this->IP = $ip;
        $this->USER = $user;
        $this->PASSWORD = $pass;
        $this->DATABASE = $database;
    }
};

class Main_Database
{
    private $pDatabase = array();
    private static $Instance;
    
    public $DBKeyName = array( "RANGAME" , "RANSHOP" , "RANUSER" );
    public $TypeKeyName = array( "IP" , "USER" , "PASSWORD" , "DATABASE" );
    
    public static function GetInstance()
    {
            if ( !self::$Instance )
            {
                    self::$Instance = new self();
            }
            return self::$Instance;
    }
    
    public function __construct()
    {
        ;
    }
    
    public function GetData()
    {
        return $this->pDatabase;
    }
    
    public function SetData( $data )
    {
        $this->pDatabase = $data;
    }
    
    public function UpdateToDB()
    {
        global $cAdmin;
        global $CURRENT_SESSION;
        
        $MemNum = $cAdmin->GetMemNum();
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanWeb();
	$szTemp = sprintf( "UPDATE MemSQL SET
					  RanShop_IP = '%s',
					  RanShop_User = '%s',
					  RanShop_Pass = '%s',
					  RanShop_DB = '%s',
					  
					  RanUser_IP = '%s',
					  RanUser_User = '%s',
					  RanUser_Pass = '%s',
					  RanUser_DB = '%s',
					  
					  RanGame_IP = '%s',
					  RanGame_User = '%s',
					  RanGame_Pass = '%s',
					  RanGame_DB = '%s'
					  
					  WHERE MemNum = %d
					  "
					  ,self::GetDatabaseValue( $this->DBKeyName[ 1 ] , $this->TypeKeyName[ 0 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 1 ] , $this->TypeKeyName[ 1 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 1 ] , $this->TypeKeyName[ 2 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 1 ] , $this->TypeKeyName[ 3 ] )
					  
					  ,self::GetDatabaseValue( $this->DBKeyName[ 2 ] , $this->TypeKeyName[ 0 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 2 ] , $this->TypeKeyName[ 1 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 2 ] , $this->TypeKeyName[ 2 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 2 ] , $this->TypeKeyName[ 3 ] )
					  
					  ,self::GetDatabaseValue( $this->DBKeyName[ 0 ] , $this->TypeKeyName[ 0 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 0 ] , $this->TypeKeyName[ 1 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 0 ] , $this->TypeKeyName[ 2 ] )
					  ,self::GetDatabaseValue( $this->DBKeyName[ 0 ] , $this->TypeKeyName[ 3 ] )
					  
					  ,$MemNum
					  );
	$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
	$cNeoSQLConnectODBC->CloseRanWeb();
    }
    
    public function UpdateFromDB()
    {
        global $cAdmin;
        global $CURRENT_SESSION;
        
        $MemNum = $cAdmin->GetMemNum();
        
        $cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
        $cNeoSQLConnectODBC->ConnectRanWeb();
        $szTemp = sprintf( "SELECT MemNum,RanUser_IP,RanUser_User,RanUser_Pass,RanUser_DB,RanGame_IP,RanGame_User,RanGame_Pass,RanGame_DB,RanShop_IP,RanShop_User,RanShop_Pass,RanShop_DB FROM MemSQL WHERE MemNum = %d",$MemNum );
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );

        $_MemNum = $cNeoSQLConnectODBC->Result("MemNum",ODBC_RETYPE_INT);

        if ( $_MemNum <= 0 || empty($_MemNum) )
        {
        $szTemp = sprintf( "INSERT INTO MemSQL(MemNum) VALUES(%d)",$MemNum );
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );

        $szTemp = sprintf( "SELECT MemNum,RanUser_IP,RanUser_User,RanUser_Pass,RanUser_DB,RanGame_IP,RanGame_User,RanGame_Pass,RanGame_DB,RanShop_IP,RanShop_User,RanShop_Pass,RanShop_DB FROM MemSQL WHERE MemNum = %d",$MemNum );
        $cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
        }
        
        $RanUser_IP = $cNeoSQLConnectODBC->Result("RanUser_IP",ODBC_RETYPE_THAI);
        $RanUser_User = $cNeoSQLConnectODBC->Result("RanUser_User",ODBC_RETYPE_THAI);
        $RanUser_Pass = $cNeoSQLConnectODBC->Result("RanUser_Pass",ODBC_RETYPE_THAI);
        $RanUser_DB = $cNeoSQLConnectODBC->Result("RanUser_DB",ODBC_RETYPE_THAI);

        $RanGame_IP = $cNeoSQLConnectODBC->Result("RanGame_IP",ODBC_RETYPE_THAI);
        $RanGame_User = $cNeoSQLConnectODBC->Result("RanGame_User",ODBC_RETYPE_THAI);
        $RanGame_Pass = $cNeoSQLConnectODBC->Result("RanGame_Pass",ODBC_RETYPE_THAI);
        $RanGame_DB = $cNeoSQLConnectODBC->Result("RanGame_DB",ODBC_RETYPE_THAI);

        $RanShop_IP = $cNeoSQLConnectODBC->Result("RanShop_IP",ODBC_RETYPE_THAI);
        $RanShop_User = $cNeoSQLConnectODBC->Result("RanShop_User",ODBC_RETYPE_THAI);
        $RanShop_Pass = $cNeoSQLConnectODBC->Result("RanShop_Pass",ODBC_RETYPE_THAI);
        $RanShop_DB = $cNeoSQLConnectODBC->Result("RanShop_DB",ODBC_RETYPE_THAI);
        
        $cNeoSQLConnectODBC->CloseRanWeb();
        
        $RanUser_IP = CBinaryCover::tis620_to_utf8( $RanUser_IP );
        $RanUser_User = CBinaryCover::tis620_to_utf8( $RanUser_User );
        $RanUser_Pass = CBinaryCover::tis620_to_utf8( $RanUser_Pass );
        $RanUser_DB = CBinaryCover::tis620_to_utf8( $RanUser_DB );
        
        $RanGame_IP = CBinaryCover::tis620_to_utf8( $RanGame_IP );
        $RanGame_User = CBinaryCover::tis620_to_utf8( $RanGame_User );
        $RanGame_Pass = CBinaryCover::tis620_to_utf8( $RanGame_Pass );
        $RanGame_DB = CBinaryCover::tis620_to_utf8( $RanGame_DB );
        
        $RanShop_IP = CBinaryCover::tis620_to_utf8( $RanShop_IP );
        $RanShop_User = CBinaryCover::tis620_to_utf8( $RanShop_User );
        $RanShop_Pass = CBinaryCover::tis620_to_utf8( $RanShop_Pass );
        $RanShop_DB = CBinaryCover::tis620_to_utf8( $RanShop_DB );
        
        unset( $this->pDatabase );
        
        $this->pDatabase[ $this->DBKeyName[0] ] = new __Database( $RanGame_IP , $RanGame_User , $RanGame_Pass , $RanGame_DB );
        $this->pDatabase[ $this->DBKeyName[1] ] = new __Database( $RanShop_IP , $RanShop_User , $RanShop_Pass , $RanShop_DB );
        $this->pDatabase[ $this->DBKeyName[2] ] = new __Database( $RanUser_IP , $RanUser_User , $RanUser_Pass , $RanUser_DB );
    }
    
    public function GetDatabaseValue( $key , $type )
    {
        $ret;
        switch( $type )
        {
        case $this->TypeKeyName[ 0 ]:
            {
                $ret = $this->pDatabase[ $key ]->IP;
            }break;
        case $this->TypeKeyName[ 1 ]:
            {
                $ret = $this->pDatabase[ $key ]->USER;
            }break;
        case $this->TypeKeyName[ 2 ]:
            {
                $ret = $this->pDatabase[ $key ]->PASSWORD;
            }break;
        case $this->TypeKeyName[ 3 ]:
            {
                $ret = $this->pDatabase[ $key ]->DATABASE;
            }break;
        }
        return $ret;
    }
    
    public function SetDatabaseValue( $key , $type , $value )
    {
        switch( $type )
        {
        case $this->TypeKeyName[ 0 ]:
            {
                CInput::GetInstance()->OnlyIP($value);
                $this->pDatabase[ $key ]->IP = $value;
            }break;
        case $this->TypeKeyName[ 1 ]:
            {
                $this->pDatabase[ $key ]->USER = $value;
            }break;
        case $this->TypeKeyName[ 2 ]:
            {
                $this->pDatabase[ $key ]->PASSWORD = $value;
            }break;
        case $this->TypeKeyName[ 3 ]:
            {
                $this->pDatabase[ $key ]->DATABASE = $value;
            }break;
        }
    }
};

$pDatabase = unserialize( CInput::GetInstance()->GetValue( $CURRENT_SESSION , IN_SESSION ) );
if ( !$pDatabase )
{
    Main_Database::GetInstance()->UpdateFromDB();
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( Main_Database::GetInstance()->GetData() ) , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
}else{
    Main_Database::GetInstance()->SetData( $pDatabase );
}

function CMD_PROC()
{
    global $CURRENT_SESSION;
    
    $keydbname = array( "RANGAME" , "RANSHOP" , "RANUSER" );
    $keytypename = array( "_IP" , "_USER" , "_PASSWORD" , "_DATABASE" );
    for( $i = 0 ; $i < count( $keydbname ) ; $i++ )
    {
        for($n = 0 ; $n < count( $keytypename ) ; $n ++  )
        {
            $value = CInput::GetInstance()->GetValueString( $keydbname[$i] . $keytypename[$n] , IN_POST );
            //ภาษาไทย utf-8 to tis-620
            $value = CBinaryCover::utf8_to_tis620( $value );
            
            Main_Database::GetInstance()->SetDatabaseValue( Main_Database::GetInstance()->DBKeyName[ $i ] , Main_Database::GetInstance()->TypeKeyName[ $n ] , $value );
        }
    }
    Main_Database::GetInstance()->UpdateToDB(  );
    
    //CInput::GetInstance()->AddValue( $CURRENT_SESSION , serialize( Main_Database::GetInstance()->GetData() ) , IN_SESSION );
    CInput::GetInstance()->AddValue( $CURRENT_SESSION , null , IN_SESSION );
    CInput::GetInstance()->UpdateSession();
    
    echo "SUCCESS";
}

function CMD_UI()
{
?>

<div id="main_database" class='main_database'>
<table>
    <tr>
        <td valign="middle" style="width:99px;" align='center'>RanGame
        </td>
        <td valign="top" style="width:399px;" align="left">
            <table>
                <tr>
                    <td style="width:99px;" valign="top" align="right">IP:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANGAME_IP" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANGAME" , "IP" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">User:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANGAME_USER" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANGAME" , "USER" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">Password:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="password" class="edittext" id="RANGAME_PASSWORD" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANGAME" , "PASSWORD" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">Database:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANGAME_DATABASE" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANGAME" , "DATABASE" ); ?>' /></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="middle" style="width:99px;" align='center'>RanShop
        </td>
        <td valign="top" style="width:399px;" align="left">
            <table>
                <tr>
                    <td style="width:99px;" valign="top" align="right">IP:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANSHOP_IP" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANSHOP" , "IP" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">User:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANSHOP_USER" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANSHOP" , "USER" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">Password:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="password" class="edittext" id="RANSHOP_PASSWORD" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANSHOP" , "PASSWORD" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">Database:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANSHOP_DATABASE" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANSHOP" , "DATABASE" ); ?>' /></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td valign="middle" style="width:99px;" align='center'>RanUser
        </td>
        <td valign="top" style="width:399px;" align="left">
            <table>
                <tr>
                    <td style="width:99px;" valign="top" align="right">IP:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANUSER_IP" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANUSER" , "IP" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">User:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANUSER_USER" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANUSER" , "USER" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">Password:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="password" class="edittext" id="RANUSER_PASSWORD" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANUSER" , "PASSWORD" ); ?>' /></td>
                </tr>
                <tr>
                    <td style="width:99px;" valign="top" align="right">Database:</td>
                    <td style="width:300px;" valign="top" align="left"><input type="text" class="edittext" id="RANUSER_DATABASE" value='<?php echo Main_Database::GetInstance()->GetDatabaseValue( "RANUSER" , "DATABASE" ); ?>' /></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <div align='center'><button id='submit_database'>แก้ไข</button></div>
        </td>
    </tr>
</table>
    
</div>

<script type="text/javascript" src="js/database.js"></script>

<?php
}

$submit = CInput::GetInstance()->GetValueInt( "submit" , IN_POST );

if ( !$submit )
    CMD_UI();
else
    CMD_PROC();

?>
