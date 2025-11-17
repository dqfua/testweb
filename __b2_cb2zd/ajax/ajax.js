ajax_info_num = 0;
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

function loadpage( url , __areachange , data ){
	var xml = GetXMLHttp();
	var _part = url;
	
	xml.onreadystatechange=function(){
			if ( xml.readyState == 4 ){
				document.getElementById(__areachange).innerHTML = xml.responseText;
			}
		};
	xml.open("POST", _part , true );
	xml.setRequestHeader ( "Content-Type", "application/x-www-form-urlencoded;charset=TIS-620" );
	xml.send(data);
	return true;
}

function working()
{
    var input_value = $('#input').val();
    var output_value = $('#output').val();
    loadpage('index.php?submit=1&ajax_info_num='+(ajax_info_num+1),'info_'+ajax_info_num,'input='+input_value+'&output='+output_value);
    ajax_info_num = (ajax_info_num+1);
    
    var x=document.getElementById("input");
    x.remove(x.selectedIndex);
}

function workingtest()
{
    var input_value = $('#input').val();
    var output_value = $('#output').val();
    loadpage('index_1.php?submit=1&ajax_info_num='+(ajax_info_num+1),'info_'+ajax_info_num,'input='+input_value+'&output='+output_value);
    ajax_info_num = (ajax_info_num+1);
    
    var x=document.getElementById("input");
    x.remove(x.selectedIndex);
}

function working_nonedel()
{
    var input_value = $("#input").val();
    var output_value = $("#output").val();
    loadpage('index.php?submit=1&ajax_info_num='+(ajax_info_num+1),'info_'+ajax_info_num,'input='+input_value+'&output='+output_value);
    ajax_info_num = (ajax_info_num+1);
    
    //var x=document.getElementById("input");
    //x.remove(x.selectedIndex);
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
	setTimeout( loaddefault , time );
}

function delaypage( url, time )
{
	setTimeout( function(){ gopage(url); } , time );
}

function gopage( url )
{
	window.location = url;
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
