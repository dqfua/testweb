<?php
function utf8_to_tis620($string) {
    return iconv('UTF-8', 'TIS-620', $string);
}

 $close_string = sprintf(
                        "
                        <title>Shop-center.</title>
                        <div align=center>%s<font color=red><b>
                        </b></font></div>
                        "
                        , utf8_to_tis620("ขออภัยในความไม่สะดวกจะสามารถใช้งานได้อีกครั้งในเวลา 21.00น")
                        );
if ( !defined("CLOSEWEBSITE") )
define("CLOSEWEBSITE",false);
if ( CLOSEWEBSITE )
{
    if ( !defined("DEBUG") )
        die($close_string);
    else
    {
        if ( DEBUG == false )
        die($close_string);
    }
}
?>