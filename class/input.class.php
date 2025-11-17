<?php
define( "IN_NONE" , 0 );
define( "IN_GET" , 1 );
define( "IN_POST" , 2 );
define( "IN_COOKIE" , 3 );
define( "IN_SESSION" , 4 );
define( "IN_FILES" , 5 );

class __CInputFromClient
{
	public $key;
	public $var;
	public $type;
	
	public function __construct( $key , $var , $type )
	{
		$this->key = $key;
		$this->var = $var;
		$this->type = $type;
	}
};

class CInput
{
	private static $Instance;

	public $__DATA = array();
	public $pData = array();
	public $pData_GET = array();
	public $pData_POST = array();
	public $pData_COOKIE = array();
	public $pData_SESSION = array();
        public $pData_FILES = array();

	//public $strBlock = array( "'","\"","\\"," ","?","\$","%" );
	//public $strChangeBlock = array( "&#39" , "&#34;" , "&#92" , "&#32;" ,"&#63;","","&#38" );
        public $strBlock = array( "'","\"","\\"," ","?","\$","--" );
	public $strChangeBlock = array( "" , "" , "&#92" , "&#32;" ,"&#63;","","" );
	
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
	}
        
        public function UpdateSession()
        {
            foreach( $_SESSION as $key => $value )
            {
                unset( $_SESSION[ $key ] );
            }
            foreach ($this->pData_SESSION as $key => $value)
            {
                $_SESSION[ $key ] = $this->pData_SESSION[ $key ]->var;
            }
        }

        public function OnlyInter( &$var )
        {
            //$var = preg_replace( "[a-ZA-Z0-9.,]", "", $var);
        }
        
        public function OnlyIP( &$var )
        {
            //echo preg_replace( "/([0-9]+\\.[0-9]+\\.[0-9]+)\\.[0-9]+/", "", $var);
        }
        
        public function BuildVarBack( &$var )
	{
		//$var = str_replace( array( "&nbsp;" ) , array( " " ) , $var );
	}

        public function BuildVar( &$var )
	{
		if (is_a($var, 'DateTime'))
		{
			$var = $var->format('Y-m-d H:i:s');
			$this->BuildVar( $var );
			return;
		}
		$var = str_replace( $this->strBlock , $this->strChangeBlock , $var );
		$var = str_replace( '\'' , '' , $var );
		$var = str_replace( '"' , '' , $var );
		$var = str_replace( '\\' , '' , $var );
                $var = trim($var);
		if(isset($$var) == true)
		{
			unset($$var);
		}
	}

	public function AddValue( $key , $var , $type = IN_NONE )
	{
                if ( $type != IN_SESSION && $type != IN_FILES )
                {
                    self::BuildVar( $key );
                    self::BuildVar( $var );
                }
		switch( $type )
		{
			case IN_NONE:
			{
				$this->pData[ $key ] = new __CInputFromClient( $key , $var , $type );
			}break;
			case IN_GET:
			{
				$this->pData_GET[ $key ] = new __CInputFromClient( $key , $var , $type );
			}break;
			case IN_POST:
			{
				$this->pData_POST[ $key ] = new __CInputFromClient( $key , $var , $type );
			}break;
			case IN_COOKIE:
			{
				$this->pData_COOKIE[ $key ] = new __CInputFromClient( $key , $var , $type );
			}break;
			case IN_SESSION:
			{
				$this->pData_SESSION[ $key ] = new __CInputFromClient( $key , $var , $type );
			}break;
                        case IN_FILES:
                        {
                                $this->pData_FILES[ $key ] = new __CInputFromClient( $key , $var , $type );
                        }break;
		}
	}
        
        public function DelValue( $key , $type )
        {
            switch( $type )
            {
                    case IN_NONE:
                    {
                            unset( $this->pData[ $key ] );
                    }break;
                    case IN_GET:
                    {
                            unset( $this->pData_GET[ $key ] );
                    }break;
                    case IN_POST:
                    {
                            unset( $this->pData_POST[ $key ] );
                    }break;
                    case IN_COOKIE:
                    {
                            unset( $this->pData_COOKIE[ $key ] );
                    }break;
                    case IN_SESSION:
                    {
                            unset( $this->pData_SESSION[ $key ] );
                    }break;
                    case IN_FILES:
                    {
                            unset( $this->pData_FILES[ $key ] );
                    }break;
            }
        }


        public function GetValue( $key , $type )
	{
		$pArray = array();
		switch( $type )
		{
			case IN_NONE:
			{
				$pArray = $this->pData;
			}break;
			case IN_GET:
			{
				$pArray = $this->pData_GET;
			}break;
			case IN_POST:
			{
				$pArray = $this->pData_POST;
			}break;
			case IN_COOKIE:
			{
				$pArray = $this->pData_COOKIE;
			}break;
			case IN_SESSION:
			{
				$pArray = $this->pData_SESSION;
			}break;
                        case IN_FILES:
                        {
                            $pArray = $this->pData_FILES;
                        }break;
		}
		return @$pArray[ $key ]->var;
	}

	public function GetValueFloat( $key , $type )
	{
		$ret = self::GetValue( $key , $type );
		$ret = sprintf( "%f" , $ret );
		return $ret;
	}
	
	public function GetValueLong( $key , $type )
	{
		$ret = self::GetValue( $key , $type );
		$ret = sprintf( "%.0f" , $ret );
		return $ret;
	}
	
	public function GetValueInt( $key , $type )
	{
		$ret = self::GetValue( $key , $type );
		$ret = sprintf( "%d" , $ret );
		return $ret;
	}
	
	public function GetValueString( $key , $type )
	{
		$ret = self::GetValue( $key , $type );
		$ret = sprintf( "%s" , $ret );
		return $ret;
	}
	
	public function GetData( $array , $type )
	{
		if ( !sizeof($array) ) return ;
		foreach ( $array as $key => $value )
		{
			self::AddValue( $key , $value , $type );
		}
	}
	
	public function BuildFrom( $type = IN_NONE )
	{
		switch( $type )
		{
			case IN_GET:
			{
				self::GetData( $_GET , $type );
			}break;
			case IN_POST:
			{
				self::GetData( $_POST , $type );
			}break;
			case IN_COOKIE:
			{
				self::GetData( $_COOKIE , $type );
			}break;
			case IN_SESSION:
			{
				self::GetData( @$_SESSION , $type );
			}break;
                        case IN_FILES:
                        {
                            self::GetData( $_FILES , $type );
                        }break;
		}
	}
};

//how to use
//CInput::GetInstance()->BuildFrom( IN_GET );
//CInput::GetInstance()->AddValue( "hand" , "'Hello' \\\"World\" <br>\"" );

//echo CInput::GetInstance()->GetValueString( "var2" , IN_GET ) . "<br>\n";
//echo CInput::GetInstance()->GetValueLong( "var2" , IN_GET ) . "<br>\n";
//echo CInput::GetInstance()->GetValueFloat( "var2" , IN_GET ) . "<br>\n";
//echo CInput::GetInstance()->GetValueInt( "var2" , IN_GET ) . "<br>\n";

//echo CInput::GetInstance()->GetValueString( "hand" , IN_NONE ) . "<br>\n";

?>
