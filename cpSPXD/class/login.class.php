<?php
class CCPLogin
{
    public $UserName = "";
    public $Password = "";
    public function Login()
    {
        global $_CONFIG;
        foreach($_CONFIG["ADMINISTRATOR"]["USER"] as $key=>$val)
        {
            if ( strcmp( $_CONFIG["ADMINISTRATOR"]["USER"][$key] , $this->UserName ) == 0 )
            {
                if ( strcmp( $_CONFIG["ADMINISTRATOR"]["PASS"][$key] , $this->Password ) == 0 )
                {
                    CGlobal::SetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] , serialize( $this ) );
                    return true;
                }
                return false;
            }
        }
        /*
        if ( strcmp( $_CONFIG["ADMINISTRATOR"]["USER"] , $this->UserName ) == 0 &&
             strcmp( $_CONFIG["ADMINISTRATOR"]["PASS"] , $this->Password ) == 0
           )
        {
            CGlobal::SetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] , serialize( $this ) );
            return true;
        }
        */
        return false;
    }
    public function Logout()
    {
        global $_CONFIG;
        CGlobal::SetSes( $_CONFIG["ADMINISTRATOR"]["SESSION"] , NULL );
        return true;
    }
    static public function CheckLogin( $cCpLogin )
    {
        global $_CONFIG;
        foreach($_CONFIG["ADMINISTRATOR"]["USER"] as $key=>$val)
        {
            if ( strcmp( $_CONFIG["ADMINISTRATOR"]["USER"][$key] , $cCpLogin->UserName ) == 0 )
            {
                if ( strcmp( $_CONFIG["ADMINISTRATOR"]["PASS"][$key] , $cCpLogin->Password ) == 0 )
                {
                    return true;
                }
                return false;
            }
        }
        /*
        if ( strcmp( $_CONFIG["ADMINISTRATOR"]["USER"] , $cCpLogin->UserName ) == 0 &&
             strcmp( $_CONFIG["ADMINISTRATOR"]["PASS"] , $cCpLogin->Password ) == 0
           )
        {
            return true;
        }
        */
        return false;
    }
}
?>
