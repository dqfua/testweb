<?php

class CApp
{
    static public function build_guimg( $binary_img , $name_code )
    {
            $___binary_img = "FF" . $binary_img;
            $strReturn = "<table border='0' cellpadding='0' cellspacing='0' width='16' height='11'>\n";
            for( $____iy = 0 ; $____iy < 11 ; $____iy ++ )
            {
                    $strReturn .= "<tr>\n";
                    for( $____ix = 0 ; $____ix < 16 ; $____ix++ )
                    {
                            $____color = substr( $___binary_img , 0 , 8 );
                            $____color = substr( $____color , 2 , strlen( $____color ) );
                            $____color_a = str_split( $____color , 2 );
                            $____color = $____color_a[ 2 ] . $____color_a[ 1 ] . $____color_a[ 0 ];
                            $___binary_img = substr( $___binary_img , 8 , strlen( $___binary_img ) );
                            $strReturn .= "<td style='width:1px;height:1px;background-color:#$____color' title='$name_code'>";
                            $strReturn .= "</td>\n";
                    }
                    $strReturn .= "</tr>\n";
            }
            $strReturn .= "</table>\n";
			return $strReturn;
    }
}
?>
