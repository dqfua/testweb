ajax_part = "";
ajax_data = "";
// Ajax
function GetXMLHttp(){
	var xmlHttp=null;
	try{
		xmlHttp = new XMLHttpRequest();
	}catch(e){
		try{
			xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
		}catch(e){
			try{
				xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
			}catch(e){
				alert("Web browser is not support..");
				xmlHttp=null;
			}
		}
	}
	return xmlHttp;
}

function clearDataAjax()
{
    ajax_part = "";
    ajax_data = "";
}

function loadpage( url , __areachange , data ){
	var xml = GetXMLHttp();
	var _part = "loadpage.php?page="+url;
	
	if ( _part == ajax_part && data == ajax_data ) return false;
	
	xml.onreadystatechange=function(){
			if ( xml.readyState == 4 ){
				document.getElementById(__areachange).innerHTML = xml.responseText;
				ajax_part = _part;
				ajax_data = data;
			}else{
				pageloadprocess( __areachange );
			}
		};
	xml.open("POST", _part , true );
	xml.setRequestHeader ( "Content-Type", "application/x-www-form-urlencoded;charset=tis-620" );
	xml.send(data);
	return true;
}

function loadpage_h( url , __areachange , data ){
	var xml = GetXMLHttp();
	var _part = "loadpage.php?page="+url;
	
	if ( _part == ajax_part && data == ajax_data ) return false;
	
	xml.onreadystatechange=function(){
			if ( xml.readyState == 4 ){
				document.getElementById(__areachange).innerHTML = xml.responseText;
				ajax_part = _part;
				ajax_data = data;
			}
		};
	xml.open("POST", _part , true );
	xml.setRequestHeader ( "Content-Type", "application/x-www-form-urlencoded;charset=tis-620" );
	xml.send(data);
	return true;
}

function loadpage_hide( url , data ){
	var xml = GetXMLHttp();
	var _part = "loadpage.php?page="+url;

	if ( _part == ajax_part && data == ajax_data ) return false;
	
	xml.onreadystatechange=function(){
			if ( xml.readyState == 4 ){
				document.getElementById("sessionout").innerHTML = xml.responseText;
				ajax_part = _part;
				ajax_data = data;
			}
		};
	xml.open("POST", _part , true );
	xml.setRequestHeader ( "Content-Type", "application/x-www-form-urlencoded;charset=tis-620" );
	xml.send(data);
	return true;
}

function randomString( string_length ) {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}

function Substitute() {
var args=Substitute.arguments;
var Base=args[0];
var Seek,Len,ix1,ix2,ix3;
for (ix1=1; ix1<args.length; ix1++) {
ix2=ix1-1;
Seek='{'+ix2+'}';
if ((ix3=Base.indexOf(Seek)) > -1) {
Len=Seek.length;
Base=Base.substring(0,ix3)+args[ix1]+Base.substring(ix3+Len);
}
}
return Base;
}

function copy(text) {

 if (window.clipboardData) {

	window.clipboardData.setData("Text",text);

 }else{
 	alert( "Can't copy because your browser not support please manual copy" );
 }

}

function delaydef( time )
{
	setTimeout( loaddefault2 , time );
}

function delaypage( url, time )
{
	setTimeout( function(){ gopage(url); } , time );
}

function gopage( url )
{
	window.location = url;
}

function pageloadprocess( __areachange ){
	document.getElementById(__areachange).innerHTML = "<div align='center'><img src='../images/loading.gif' border='0' ></div>";
}

function CSS_dis( area ) {
	var sss = $('#'+area).css('display');
	if ( sss == "block" )
	$('#'+area).hide("slow");
	else
	$('#'+area).show("slow");
}

function confirmText( txt ) {
    agree=confirm(txt);
    if (agree) {
        return true;
    } else  {
        return false;
    }
}

function check_number() {
e_k=event.keyCode
//if (((e_k < 48) || (e_k > 57)) && e_k != 46 ) {
if (e_k != 13 && (e_k < 48) || (e_k > 57)) {
event.returnValue = false;
alert("ต้องเป็นตัวเลขเท่านั้น... \nกรุณาตรวจสอบข้อมูลของท่านอีกครั้ง...");
}
}

function check_over( statusall )
{
	var dex = $("#dex").val();
	if ( dex < 0 ) 
		document.getElementById( "dex" ).value = 0;
	//else if ( dex> 65535 )
	//	document.getElementById( "dex" ).value = 65535;
		
	var power = $("#power").val();
	if ( power < 0 ) 
		document.getElementById( "power" ).value = 0;
	//else if ( power> 65535 )
	//	document.getElementById( "power" ).value = 65535;
		
	var spirit = $("#spirit").val();
	if ( spirit < 0 ) 
		document.getElementById( "spirit" ).value = 0;
	//else if ( spirit> 65535 )
	//	document.getElementById( "spirit" ).value = 65535;
		
	var str = $("#str").val();
	if ( str < 0 ) 
		document.getElementById( "str" ).value = 0;
	//else if ( str> 65535 )
	//	document.getElementById( "str" ).value = 65535;
		
	var stm = $("#stm").val();
	if ( stm < 0 ) 
		document.getElementById( "stm" ).value = 0;
	//else if ( stm> 65535 )
	//	document.getElementById( "stm" ).value = 65535;
		
	var strremain = $("#strremain").val();
	if ( strremain < 0 ) 
		document.getElementById( "strremain" ).value = 0;
	//else if ( strremain> 65535 )
	//	document.getElementById( "strremain" ).value = 65535;
}

function str_set( str , down )
{
	var strremain = $("#strremain").val();
	
	var canup = false;
	
	if ( strremain > 0 ) canup = true;
	else
	canup = false;
	
	if ( strremain >= 65535 ) return false;
	
	switch( str )
	{
		
		case "stm":
		{
			
			var stm = $("#stm").val();
			if ( down )
			{
				if ( document.form1.stm.value <= 0 ) return false;
				document.form1.stm.value = stm-1;
				document.form1.strremain.value = parseInt(strremain)+1;
			}else{
				if ( canup )
				{
					document.form1.stm.value = parseInt(stm)+1;
					document.form1.strremain.value = strremain-1;
				}
			}
		}break;
		
		case "str":
		{
			
			var str = $("#str").val();
			if ( down )
			{
				if ( document.form1.str.value <= 0 ) return false;
				document.form1.str.value = str-1;
				document.form1.strremain.value = parseInt(strremain)+1;
			}else{
				if ( canup )
				{
					document.form1.str.value = parseInt(str)+1;
					document.form1.strremain.value = strremain-1;
				}
			}
		}break;
		
		case "stm":
		{
			
			var stm = $("#stm").val();
			if ( down )
			{
				if ( document.form1.stm.value <= 0 ) return false;
				document.form1.stm.value = stm-1;
				document.form1.strremain.value = parseInt(strremain)+1;
			}else{
				if ( canup )
				{
					document.form1.stm.value = parseInt(stm)+1;
					document.form1.strremain.value = strremain-1;
				}
			}
		}break;
		
		case "spirit":
		{
			
			var spirit = $("#spirit").val();
			if ( down )
			{
				if ( document.form1.spirit.value <= 0 ) return false;
				document.form1.spirit.value = spirit-1;
				document.form1.strremain.value = parseInt(strremain)+1;
			}else{
				if ( canup )
				{
					document.form1.spirit.value = parseInt(spirit)+1;
					document.form1.strremain.value = strremain-1;
				}
			}
		}break;
		
		case "power":
		{
			
			var power = $("#power").val();
			if ( down )
			{
				if ( document.form1.power.value <= 0 ) return false;
				document.form1.power.value = power-1;
				document.form1.strremain.value = parseInt(strremain)+1;
			}else{
				if ( canup )
				{
					document.form1.power.value = parseInt(power)+1;
					document.form1.strremain.value = strremain-1;
				}
			}
		}break;
		
		case "dex":
		{
			
			var dex = $("#dex").val();
			if ( down )
			{
				if ( document.form1.dex.value <= 0 ) return false;
				document.form1.dex.value = dex-1;
				document.form1.strremain.value = parseInt(strremain)+1;
			}else{
				if ( canup )
				{
					document.form1.dex.value = parseInt(dex)+1;
					document.form1.strremain.value = strremain-1;
				}
			}
		}break;
		
	}
	
	return true;
}

function stat_reset()
{
	var varall = parseInt( $("#strremain").val() );
	varall += parseInt( $("#dex").val() );
	varall += parseInt( $("#power").val() );
	varall += parseInt( $("#spirit").val() );
	varall += parseInt( $("#stm").val() );
	varall += parseInt( $("#str").val() );
	if ( varall == 0 ) return false;
	document.form1.strremain.value = varall;
	document.form1.dex.value = 0;
	document.form1.power.value = 0;
	document.form1.spirit.value = 0;
	document.form1.stm.value = 0;
	document.form1.str.value = 0;
	return true;
}

function updatest( )
{
	var varall = parseInt( $("#allstcheck").val() );
	varall -= parseInt( $("#dex").val() );
	varall -= parseInt( $("#power").val() );
	varall -= parseInt( $("#spirit").val() );
	varall -= parseInt( $("#stm").val() );
	varall -= parseInt( $("#str").val() );
	document.getElementById('strremain').value = varall;
	/*
	varall -= document.form1.dex.value;
	varall -= document.form1.power.value;
	varall -= document.form1.spirit.value;
	varall -= document.form1.stm.value;
	varall -= document.form1.str.value;
	document.form1.strremain.value = varall;
	*/
}

function ClipBoard(Obj){
	if( window.clipboardData){
		clipboardData.setData("Text", Obj);
	  }else{
		alert('Can not copy link to your clipboard because your browser does 	not support.\nPlease manual copy.');
	  }
   }
