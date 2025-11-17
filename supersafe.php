<?php
//level 1 begin :::::::::::::::::::::::::::::::::::::
//$fbd_chr = array('\'','"',';','*','=',':',',','/','\\','(',')','-');
//$fbd_chr = array('\'','"',';','*',':',',','/','\\','(',')','-');
$fbd_chr = array('\'',"'",'"','*',',','//','/','\\','=','\$','%','^','<','>','[',']','?');

foreach($_GET as $key=>$val){
	$_GET[$key] = str_replace($fbd_chr,'',$val);
	if(isset($$key) == true)
	{
		unset($$key);
	}
}

foreach($_POST as $key=>$val){
	$_POST[$key] = str_replace($fbd_chr,'',$val);
	if(isset($$key) == true)
	{
		unset($$key);
	}
}

foreach($_COOKIE as $key=>$val){
	$_COOKIE[$key] = str_replace($fbd_chr,'',$val);
	if(isset($$key) == true)
	{
		unset($$key);
	}
}

foreach($_REQUEST as $key=>$val){
	$_REQUEST[$key] = str_replace($fbd_chr,'',$val);
	if(isset($$key) == true)
	{
		unset($$key);
	}
}
//level 1 end :::::::::::::::::::::::::::::::::::::

//level 2 begin ==========================================
$sql_inject_1 = array(
									"'"
									,"%"
									,'"'
									," "
									,"("
									,")"
									,"%"
									,"+"
									,"-"
									,":"
									); #Whoth need replace
$sql_inject_2 = array(
									""
									,""
									,"&quot;"
									,"&nbsp;"
									,"&#40;"
									,"&#41;"
									,"&#37;"
									,"&#43;"
									,"&#45;"
									,"&#58;"
									); #To wont replace
$GET_KEY = array_keys($_GET); #array keys from $_GET
$POST_KEY = array_keys($_POST); #array keys from $_POST
$COOKIE_KEY = array_keys($_COOKIE); #array keys from $_COOKIE

for($i=0;$i<count($GET_KEY);$i++)
{
	//$_GET[$GET_KEY[$i]] = str_replace($sql_inject_1, $sql_inject_2, HtmlSpecialChars($_GET[$GET_KEY[$i]]));
	$_GET[$GET_KEY[$i]] = str_replace($sql_inject_1, $sql_inject_2, $_GET[$GET_KEY[$i]]);
	$_GET[$GET_KEY[$i]] = trim( $_GET[$GET_KEY[$i]] );
}
for($i=0;$i<count($POST_KEY);$i++)
{
	//$_POST[$POST_KEY[$i]] = str_replace($sql_inject_1, $sql_inject_2, HtmlSpecialChars($_POST[$POST_KEY[$i]]));
	$_POST[$POST_KEY[$i]] = str_replace($sql_inject_1, $sql_inject_2, $_POST[$POST_KEY[$i]]);
	$_POST[$POST_KEY[$i]] = trim( $_POST[$POST_KEY[$i]] );
}
for($i=0;$i<count($COOKIE_KEY);$i++)
{
	//$_COOKIE[$COOKIE_KEY[$i]] = str_replace($sql_inject_1, $sql_inject_2, HtmlSpecialChars($_COOKIE[$COOKIE_KEY[$i]]));
	$_COOKIE[$COOKIE_KEY[$i]] = str_replace($sql_inject_1, $sql_inject_2, $_COOKIE[$COOKIE_KEY[$i]]);
	$_COOKIE[$COOKIE_KEY[$i]] = trim( $_COOKIE[$COOKIE_KEY[$i]] );
}
//level 2 end ==========================================
?>