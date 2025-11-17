<?php
function sendMail( $to , $subject , $message )
{
	global $_CONFIG;
	$mail = new PHPMailer();
	$mail->IsHTML(true);
	$mail->IsSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->Host       = $_CONFIG["MAIL"]["HOST"];      // SMTP server example, use smtp.live.com for Hotmail
	$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->SMTPSecure = $_CONFIG["MAIL"]["SECURE"];
	$mail->Port       = $_CONFIG["MAIL"]["PORT"];                   // SMTP port for the GMAIL server 465 or 587
	$mail->Username   = $_CONFIG["MAIL"]["USERNAME"];  // SMTP account username example
	$mail->Password   = $_CONFIG["MAIL"]["PASSWOPD"];            // SMTP account password example
	
	$mail->From = $_CONFIG["MAIL"]["USERNAME"];
	$mail->FromName = "GameCenterShop";
	$mail->AddAddress( $to , $to );
	$mail->Subject = $subject;
	$mail->Body = $message;
	
	if (!$mail->Send() ) echo $mail->ErrorInfo;
}

function HostDomainChecking( $arr )
{
    global $_CONFIG;
    global $_SERVER;
    return strcmp( $_SERVER[ "SERVER_NAME" ] , $_CONFIG[$arr] );
}

function buildItemCustomEditor( &$pItemTemp , $Inventory , $idm , $lasttext )
{
    global $ITEMGEN_GMEDIT;
    
    if ( $idm != ITEM_ERROR )
    {
        $pItemTemp->ItemMain = $Inventory->GetItemMain( $idm );
        $pItemTemp->ItemSub = $Inventory->GetItemSub( $idm );
        $pItemTemp->ItemDrop = $Inventory->GetItemDrop( $idm );
        $pItemTemp->Item_TurnNum = $Inventory->GetItemTrunNum( $idm );
        $pItemTemp->ItemDamage = $Inventory->GetItemDamage( $idm );
        $pItemTemp->ItemDefense = $Inventory->GetItemDefense( $idm );
        $pItemTemp->Item_Res_Ele = $Inventory->GetItemResistEle( $idm );
        $pItemTemp->Item_Res_Fire = $Inventory->GetItemResistFire( $idm );
        $pItemTemp->Item_Res_Ice = $Inventory->GetItemResistIce( $idm );
        $pItemTemp->Item_Res_Poison = $Inventory->GetItemResistPoison( $idm );
        $pItemTemp->Item_Res_Spirit = $Inventory->GetItemResistSpirit( $idm );
        $pItemTemp->Item_Op1 = $Inventory->GetItemOptType1( $idm );
        $pItemTemp->Item_Op2 = $Inventory->GetItemOptType2( $idm );
        $pItemTemp->Item_Op3 = $Inventory->GetItemOptType3( $idm );
        $pItemTemp->Item_Op4 = $Inventory->GetItemOptType4( $idm );
        $pItemTemp->Item_Op1_Value = $Inventory->GetItemOptVal1( $idm );
        $pItemTemp->Item_Op2_Value = $Inventory->GetItemOptVal2( $idm );
        $pItemTemp->Item_Op3_Value = $Inventory->GetItemOptVal3( $idm );
        $pItemTemp->Item_Op4_Value = $Inventory->GetItemOptVal4( $idm );
    }
    
    $pItemTemp->ItemDrop = ( $pItemTemp->ItemDrop == $ITEMGEN_GMEDIT ) ? true : false;
    
    echo "<table border=\"2\" cellspacing=\"3\" cellpadding=\"3\">";
    
    printf( "<tr><td colspan=\"4\"><div align=\"center\">ItemEditor UI ItemMain:%03d,ItemSub:%03d</div></td></tr>" , $pItemTemp->ItemMain , $pItemTemp->ItemSub );
    function buildtry( $s1 , $s2 , $s3 , $s4 )
    {
        printf( "<tr>
                    <td style=\"width:199px;\">%s</td>
                    <td style=\"width:279px;\">%s</td>
                    <td style=\"width:199px;\">%s</td>
                    <td style=\"width:279px;\">%s</td>
                </tr>" , $s1 , $s2 , $s3 , $s4 );
    }
    
    function buildinputtexttry( $idm , $val )
    {
        return sprintf( "<input type=\"text\" id=\"%s\" value=\"%d\" style=\"width:99px;\">" , $idm , $val );
    }
    
    buildtry( "Item Main" , buildinputtexttry( "ItemMain" , $pItemTemp->ItemMain ) , "Item Sub" , buildinputtexttry( "ItemSub" , $pItemTemp->ItemSub ) );
    buildtry( "Damage" , buildinputtexttry( "ItemDamage" , $pItemTemp->ItemDamage ) , "Resist Ele" , buildinputtexttry( "Item_Res_Ele" , $pItemTemp->Item_Res_Ele )  );
    buildtry( "Defense" , buildinputtexttry( "ItemDefense" , $pItemTemp->ItemDefense )  , "Resist Fire" , buildinputtexttry( "Item_Res_Fire" , $pItemTemp->Item_Res_Fire )  );
    buildtry( "Item Trunnum" , buildinputtexttry( "Item_TurnNum" , $pItemTemp->Item_TurnNum )  , "Resist Ice" , buildinputtexttry( "Item_Res_Ice" , $pItemTemp->Item_Res_Ice )  );
    buildtry( "Item Drop" , buildSelectText("Item_Drop", "", $pItemTemp->ItemDrop , ItemDropData() ) , "Resist Poison" , buildinputtexttry( "Item_Res_Poison" , $pItemTemp->Item_Res_Poison )  );
    buildtry( "" , "" , "Resist Spirit" , buildinputtexttry( "Item_Res_Spirit" , $pItemTemp->Item_Res_Spirit )  );
    buildtry( "Random Opt 1" , buildSelectText("Item_Op1", "", $pItemTemp->Item_Op1, ItemOptionData() ) . buildinputtexttry( "Item_Op1_Value" , $pItemTemp->Item_Op1_Value ) , "Random Opt 2" , buildSelectText("Item_Op2", "", $pItemTemp->Item_Op2, ItemOptionData() ) . buildinputtexttry( "Item_Op2_Value" , $pItemTemp->Item_Op2_Value ) );
    buildtry( "Random Opt 3" , buildSelectText("Item_Op3", "", $pItemTemp->Item_Op3, ItemOptionData() ) . buildinputtexttry( "Item_Op3_Value" , $pItemTemp->Item_Op3_Value ) , "Random Opt 4" , buildSelectText("Item_Op4", "", $pItemTemp->Item_Op4, ItemOptionData() ) . buildinputtexttry( "Item_Op4_Value" , $pItemTemp->Item_Op4_Value ) );
    
    printf( "<tr><td colspan=\"4\"><div align=\"center\">%s</div></td></tr>" , $lasttext );
    
    echo "</table>";
}

function table_log_easy_begin( $class , $id = "" )
{
    printf( "<table id=\"%s\" class=\"%s\">" , $id , $class );
}

function table_log_easy_end()
{
    echo "</table>";
}

function table_log_easy_line_begin()
{
    echo "<tr>";
}

function table_log_easy_line_end()
{
    echo "</tr>";
}

function table_log_createtbody()
{
    echo "<tbody>";
}

function table_log_closetbody()
{
    echo "</tbody>";
}

function table_log_closetfoot()
{
    echo "</tfoot>";
}

function table_log_createtfoot()
{
    echo "<tfoot>";
}

function table_log_createthead()
{
    echo "<thead>";
}

function table_log_closethead()
{
    echo "</thead>";
}

function table_log_easy_add_head_colume( $text , $col , $style )
{
    printf( '<td align="center" colspan="%s" style="%s"><b>%s</b></td>' , $col , $style , $text );
}

function table_log_easy_add_colume( $text , $col , $style )
{
    printf( '<td align="left" colspan="%s" style="%s">%s</td>' , $col , $style , $text );
}

function table_log_easy_title( $text , $col , $style )
{
    table_log_easy_line_begin();
    printf( '<td colspan="%s" style="%s" align="center"><u><b>%s</b></u></td>' , $col , $style , $text );
    table_log_easy_line_end();
}


//not work
function deleteFromArray(&$array, $deleteIt, $useOldKeys = FALSE)
{
    $key = array_search($deleteIt,$array,TRUE);
    if($key === FALSE)
        return FALSE;
    unset($array[$key]);
    if(!$useOldKeys)
        $array = array_values($array);
    return TRUE;
}

function CheckNumZero( &$value )
{
    if ( $value < 0 ) $value = 0;
}

function CheckStringLen( &$txt , $len)
{
    $txt = substr( $txt , 0 , $len );
}

function arrkeycheck( $array , $keycheck )
{
    foreach( $array as $key => $value )
    {
        if ( $key == $keycheck ) return true;
    }
    return false;
}

function buildSelectText( $idname , $classname , $sel , $arraydata , $sty = "" , $ocode )
{
    $szTemp = "";
    $szTemp .= sprintf( "<select id=\"%s\" class=\"%s\" style=\"%s\" %s>\n" , $idname , $classname , $sty , $ocode );
    
    foreach( $arraydata as $key => $value )
    {
        if ( $sel == $key )
        {
            $szTemp .= sprintf("<option value=\"%s\" selected=\"selected\">%s</option>\n" , $key , $value );
        }
        else
        {
            $szTemp .= sprintf("<option value=\"%s\">%s</option>\n" , $key , $value );
        }
    }
    
    $szTemp .= "</select>\n";
    
    return $szTemp;
}

function NumToEncode( $num )
{
    return strtoupper( substr( md5( substr( md5( $num ) , 3 , 10 ) ) , 10 , 10 ) );
}

function GetToday() { return GetNow(); }
function GetNow() { return date("Y-m-d H:i:s"); };

/*
$y1 = "";
$m1 = "";
$d1 = "";
$h1 = "";
$mi1 = "";
DateFromSQL2Data("2001-03-10 17:16:18", $y1, $m1, $d1 , $h1 , $mi1 );
*/
function DateFromSQL2Data( $strtime , &$y , &$m , &$d , &$h  , &$mi )
{
    $data = $strtime;
    
    $npos = 4;
    $y = substr( $data , 0 , $npos );
    $data = substr( $data , $npos+1 );
    
    $npos = 2;
    $m = substr( $data , 0 , $npos );
    $data = substr( $data , $npos+1 );
    
    $npos = 2;
    $d = substr( $data , 0 , $npos );
    $data = substr( $data , $npos+1 );
    
    $npos = 2;
    $h = substr( $data , 0 , $npos );
    $data = substr( $data , $npos+1 );
    
    $mi = substr( $data , 0 , 2 );
    
    //printf( "$y>$m>$d $h:$mi" );
}

function buildSelect( $idname , $classname , $sel , $begin , $end )
{
    $szTemp = "";
    $szTemp .= sprintf( "<select id=\"%s\" class=\"%s\">" , $idname , $classname );
    for( $i = $begin ; $i <= $end ; $i++ )
    {
        if ( $sel == $i )
            $szTemp .= sprintf("<option value=\"%d\" selected=\"selected\">%s</option>" , $i , $i);
        else
            $szTemp .= sprintf("<option value=\"%d\">%s</option>" , $i , $i);
    }
    $szTemp .= "</select>";
    return $szTemp;
}

function buildSelectDay( $idname , $classname , $sel )
{
    return buildSelect( $idname , $classname , $sel , 1 , 31 );
}

function buildSelectMonth( $idname , $classname , $sel )
{
    return buildSelect( $idname , $classname , $sel , 1 , 12 );
}

function buildSelectYear( $idname , $classname , $sel )
{
    global $_CONFIG;
    return buildSelect( $idname , $classname , $sel , $_CONFIG["MAX"]["YEARBEGIN"] , $_CONFIG["MAX"]["YEAR"] );
}

function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
     $platform = 'Unknown';
     $version= "";
 
    //First get the platform?
     if (preg_match('/linux/i', $u_agent)) {
         $platform = 'linux';
     }
     elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
         $platform = 'mac';
     }
     elseif (preg_match('/windows|win32/i', $u_agent)) {
         $platform = 'windows';
     }
     
    // Next get the name of the useragent yes seperately and for good reason
     if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
     $known = array('Version', $ub, 'other');
     $pattern = '#(?<browser>' . join('|', $known) .
     ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
     if (!@preg_match_all($pattern, $u_agent, $matches)) {
         // we have no matching number just continue
     }
     
    // see how many we have
     $i = count(@$matches['browser']);
     if ($i != 1) {
         //we will have two since we are not using 'other' argument yet
         //see if version is before or after the name
         if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
             $version= @$matches['version'][0];
         }
         else {
             $version= @$matches['version'][1];
         }
     }
     else {
         $version= @$matches['version'][0];
     }
     
    // check if we have a number
     if ($version==null || $version=="") {$version="?";}
     
    return array(
         'userAgent' => $u_agent,
         'name'      => $bname,
         'version'   => $version,
         'platform'  => $platform,
         'pattern'    => $pattern
     );
 }
function OpenSubtitlesHash($file)
{
    $handle = fopen($file, "rb");
    $fsize = filesize($file);
    
    $hash = array(3 => 0, 
                  2 => 0, 
                  1 => ($fsize >> 16) & 0xFFFF, 
                  0 => $fsize & 0xFFFF);
        
    for ($i = 0; $i < 8192; $i++)
    {
        $tmp = ReadUINT64($handle);
        $hash = AddUINT64($hash, $tmp);
    }
    
    $offset = $fsize - 65536;
    fseek($handle, $offset > 0 ? $offset : 0, SEEK_SET);
    
    for ($i = 0; $i < 8192; $i++)
    {
        $tmp = ReadUINT64($handle);
        $hash = AddUINT64($hash, $tmp);         
    }
    
    fclose($handle);
        return UINT64FormatHex($hash);
}

function ReadUINT64($handle)
{
    $u = @unpack("va/vb/vc/vd", fread($handle, 8));
    return array(0 => $u["a"], 1 => $u["b"], 2 => $u["c"], 3 => $u["d"]);
}

function AddUINT64($a, $b)
{
    $o = array(0 => 0, 1 => 0, 2 => 0, 3 => 0);

    $carry = 0;
    for ($i = 0; $i < 4; $i++) 
    {
        if (($a[$i] + $b[$i] + $carry) > 0xffff ) 
        {
            $o[$i] += ($a[$i] + $b[$i] + $carry) & 0xffff;
            $carry = 1;
        }
        else 
        {
            $o[$i] += ($a[$i] + $b[$i] + $carry);
            $carry = 0;
        }
    }
    
    return $o;   
}

function UINT64FormatHex($n)
{   
    return sprintf("%04x%04x%04x%04x", $n[3], $n[2], $n[1], $n[0]);
}

?>