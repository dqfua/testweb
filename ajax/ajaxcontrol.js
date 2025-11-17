function login()
{
	var id = $("#id").val();
	var password = $("#password").val();
	var sescode = $("#sescode").val();
	loadpage("login&submit=1","area","id="+id+"&password="+password+"&sescode="+sescode);
	//loadmenu();
	delaydef( 3000 );
}
function logout()
{
	loadpage("logout","area",null);
	setTimeout( loaddefault2 , 3000 );
}
function copyright_foot()
{
	loadpage_h("copyright_foot","copyright_foot",null);
}
function disconnect()
{
	var pass = $("#pass").val();
	//var pass2 = $("#pass2").val();
	loadpage("disconnect&submit=1","area","pass="+pass);
}
function changeschool()
{
	var pass = $("#pass").val();
	var school = $("#school").val();
	loadpage("changeschool&submit=1","area","pass="+pass+"&school="+school);
}
function changeclass()
{
	var pass = $("#pass").val();
	var select = $("#select").val();
	loadpage("changeclass&submit=1","area","pass="+pass+"&class="+select);
}
function refill()
{
	var serial = $("#serial").val();
	loadpage("refill2","refill","serial="+serial);
}
function refill_bonus()
{
	var serial = $("#serial").val();
	loadpage("refill_bonus","refill","serial="+serial);
}
function changepassgame()
{
	var passdel = $("#passdel").val();
	var oldpass = $("#oldpass").val();
	var pass = $("#pass").val();
	var pass2 = $("#pass2").val();
	loadpage("changepassgame&submit=1","area","oldpass="+oldpass+"&pass="+pass+"&pass2="+pass2+"&passdel="+passdel);
}
function changest()
{
	var strremain = parseInt( $("#strremain").val() );
	if ( strremain < 0 )
	{
		alert('สเตตัสทีเหลือไม่เพียงพอ!!');
		return false;
	}
	loadpage('st_change&submit=1','area','password='+$('#pass').val()+'&dex='+$('#dex').val()+'&pow='+$('#power').val()+'&spirit='+$('#spirit').val()+'&str='+$('#str').val()+'&stm='+$('#stm').val()+'&strremain='+$('#strremain').val()+'&mainpass='+$('#mainpass').val()  );
	return true;
}
function checkcharpass()
{
	var oldpass = $("#oldpass").val();
	var pass = $("#pass").val();
	var pass2 = $("#pass2").val();
	loadpage("checkcharpass&submit=1","area","oldpass="+oldpass+"&pass="+pass+"&pass2="+pass2);
}
function buyi( itemnum , code , codeok )
{
	if ( code != codeok ) { CSS_dis( 'areawrongpass' ); setTimeout( function(){ CSS_dis( 'areawrongpass' ); } , 3000 ); setTimeout( clearcode , 3000 ); return false; }
	CSS_dis( 'areawait' );
	setTimeout( function(){ resell_item( n , code ); } , 3000 );
	setTimeout( function(){ CSS_dis( 'areawait' ); loadpage("buy&submit=1","area","itemnum="+itemnum+"&code="+code); } , 3000 );
	setTimeout( clearcode , 15000 );
	//setTimeout( function(){ location.reload(true); } , 15000 );
	//delaydef( 5000 );
}
function buy( itemnum , usetimepoint )
{
	var tt = "พ้อย";
	if ( usetimepoint == 1 ) tt = "เวลาออนไลน์";
	if ( !confirm( "คุณต้องการซื้อไอเทมชิ้นนี้ด้วย \""+ tt +"\" ใช่หรือไม่" ) ) return ;
	//CSS_dis( 'areawait' );
	//setTimeout( function(){ CSS_dis( 'areawait' ); loadpage("buy&submit=1","area","itemnum="+itemnum); } , 3000 );
	loadpage("buy&submit=1","area","itemnum="+itemnum+"&usetimepoint="+usetimepoint);
	//delaydef( 5000 );
}
function regcccckk( /*codecheck , */memnum )
{
	var id = $("#id").val();
	var pass = $("#pass").val();
	var pass2 = $("#pass2").val();
	var passdel = $("#passdel").val();
	var repassdel = $("#repassdel").val();
	var email = $("#email").val();
	var seccode = $("#seccode").val();
	var ParentID = $("#ParentID").val();
	function ccclen( vvv )
	{
		if ( vvv.length < 4 || vvv.length > 16 )
			return false;
		else
			return true;
	}
	if ( !ccclen( id ) || !ccclen( pass ) || !ccclen( pass2 ) || !ccclen( passdel ) )
	{
		alert( "ข้อมูลที่กรอกไม่ถูกต้อง" );
		return false;
	}
	if ( pass != pass2 )
	{
		alert( "รหัสเข้าเกมส์ทั้ง 2 ช่องไม่ตรงกัน!!" );
		return false;
	}
	if ( passdel != repassdel )
	{
		alert( "รหัสลบตัวละครทั้ง 2 ช่องไม่ตรงกัน!!" );
		return false;
	}
	/*
	if ( seccode != codecheck )
	{
		alert( "รหัสความปลอดภัยไม่ถูกต้อง!!" );
		return false;
	}
	*/
	loadpage("register&submit=1&memnum="+memnum,"area_register","id="+id+"&pass="+pass+"&pass2="+pass2+"&passdel="+passdel+"&email="+email+"&code="+seccode+"&ParentID="+ParentID);
	return true;
}
function clearcode()
{
	//document.getcodeform.getcode.value = "";
	//document.getcodeform2.getcode2.value = "";
	//document.freeform.valuegetfreecode.value = "";
	//resetcode();
}
function resetcode()
{
	var xml = GetXMLHttp();
	xml.onreadystatechange=function(){

			if ( xml.readyState == 4 ){
				getnewcode();
			}else{
				//pageloadprocess( area );
			}
		};
	xml.open("POST", "../client/captchacode.php" , true );
	xml.setRequestHeader ( "Content-Type", "application/x-www-form-urlencoded;charset=tis-620" );
	xml.send(null);
}
function getnewcode()
{
	var xml = GetXMLHttp();
	xml.onreadystatechange=function(){
			if ( xml.readyState == 4 ){
				document.getElementById("areacodeset1").innerHTML = xml.responseText;
				document.getElementById("areacodeset2").innerHTML = xml.responseText;
				document.freeform.valuegetfreecode.value = xml.responseText;
			}else{
				//pageloadprocess( area );
			}
		};
	xml.open("POST", "../client/captchaget.php" , true );
	xml.setRequestHeader ( "Content-Type", "application/x-www-form-urlencoded;charset=tis-620" );
	xml.send(null);
}
function chalogin( chanum )
{
	loadpage("chalogin&submit=1","area","chanum="+chanum);
	delaydef( 1 );
}
function chaDelete( chanum )
{
	loadpage("chadelete","area","chanum="+chanum);
}
function chaDelete_submit( chanum )
{
	var pass2 = $("#pass2").val();
	if ( pass2.length < 4 )
	{
		alert("กรุณาใส่รหัสผ่านให้ถูกต้อง");
		return;
	}
	loadpage("chadelete&submit=1","area","chanum="+chanum+"&pass2="+pass2);
}
function autoupdatesomeinfo()
{
	loadpage_h("showsomeinfo","indexsomeinfo",null);
	//setTimeout(  autoupdatesomeinfo , 30000 );
}
function autosessionout()
{
	loadpage_hide("sessionout","sessionout",null);
	setTimeout(  autosessionout , 15000 );
}
function loadprivatemenu()
{
	loadpage_h("privatemenu","indexprivatemenu",null);
}

function loaddefault( bNewItemornot )
{
	//loadareahelp();
	loadmenuman();
	load_menu();
	//load_hotitem(bNewItemornot);
	load_newitem();
	//loadmenu();
	loadmenunew();
	copyright_foot();
	//clearcode();
	loadprivatemenu();
	autoupdatesomeinfo();
	//autosessionout();
}

function loaddefault2( )
{
	//loadareahelp();
	loadmenuman();
	load_menu();
	//load_hotitem(g_bNewItemornot);
	load_newitem();
	//loadmenu();
	loadmenunew();
	copyright_foot();
	//clearcode();
	//loadprivatemenu();
	autoupdatesomeinfo();
	//autosessionout();
}

function loadareahelp()
{
	loadpage("helppage","area_help",null);
}
function loadmenuman()
{
	loadpage("headman_menu","area_man_menu",null);
}
function loadmenu()
{
	loadpage("indexmenu","indexmenu",null);
}
function loadmenunew()
{
	loadpage_h("indexmenunew","indexmenunew",null);
}
function load_item(n)
{
	if ( n <= 0 )
	{
		alert("ไม่พบไอเทม");
		return false;
	}
	loadpage("item&n="+n,"area");
	return true;
}
function resell_getcode( n , code , codeok )
{
	/*
	if ( code != codeok ) { CSS_dis( 'areawrongpass' ); setTimeout( function(){ CSS_dis( 'areawrongpass' ); } , 3000 ); setTimeout( clearcode , 3000 ); return false; }
	//resell_item( n );
	CSS_dis( 'areawait' );
	setTimeout( function(){ resell_item( n , code ); } , 3000 );
	setTimeout( function(){ CSS_dis( 'areawait' ); } , 3000 );
	setTimeout( clearcode , 10000 );
	*/
	resell_item( n , code );
	//setTimeout( function(){ loadpage('work_resell','area_resell_work',''); } , 5000 );
	//setTimeout( function(){ location.reload(true); } , 10000 );
	return true;
}
function resell_itemfrist(n)
{
	if ( n < 0 )
	{
		alert("ไม่พบไอเทม");
		return false;
	}
	if ( !confirmText("คุณต้องการทำรายการนี้แน่นอนหรือไม่") ) return false;
	loadpage("resell&getcode=1","area","n="+n);
	return true;
}
function resell_item(n,code)
{
	if ( n < 0 )
	{
		alert("ไม่พบไอเทม");
		return false;
	}
	//if ( !confirmText("คุณต้องการทำรายการนี้แน่นอนหรือไม่") ) return false;
	loadpage("resell&submit=1","area","n="+n+"&code="+code);
	return true;
}

function load_newitem( )
{
	loadpage("","area");
}

function load_hotitem( bNewItemornot )
{
	loadpage("hotitem","hotitem","bNewItemornot="+bNewItemornot);
	//if ( bNewItemornot )
	//	setTimeout( function(){ load_hotitem2( bNewItemornot ); } , 5000 );
}

function load_hotitem2( bNewItemornot )
{
	loadpage_h("hotitem","hotitem","bNewItemornot="+bNewItemornot);
	if ( bNewItemornot )
		setTimeout( function(){ load_hotitem2( bNewItemornot ); } , 5000 );
}

function load_menu()
{
	loadpage("menu","menu");
}