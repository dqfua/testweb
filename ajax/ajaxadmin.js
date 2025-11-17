function loadmanpage( url , area , data ){
	var xml = GetXMLHttp();
	xml.onreadystatechange=function(){
			if ( xml.readyState == 4 ){
				document.getElementById(area).innerHTML = xml.responseText;
			}else{
				mampageloadprocess(area);
			}
		};
	xml.open("POST", "loadpage.php?page="+url , true );
	xml.setRequestHeader ( "Content-Type", "application/x-www-form-urlencoded;charset=windows-874" );
	xml.send(data);
}
function loadmanpage_UTF8( url , area , data ){
	var xml = GetXMLHttp();
	xml.onreadystatechange=function(){
			if ( xml.readyState == 4 ){
				document.getElementById(area).innerHTML = xml.responseText;
			}else{
				mampageloadprocess(area);
			}
		};
	xml.open("POST", "loadpage.php?page="+url , true );
	xml.setRequestHeader ( "Content-Type", "application/x-www-form-urlencoded;charset=UTF-8" );
	xml.send(data);
}
function mampageloadprocess( __areachange ){
	document.getElementById(__areachange).innerHTML = "<div align=center><img src='../images/loading.gif' border=0 ></div>";
}

function sendMail( email , memid , memnum )
{
    var strValue = Substitute( "http://sendmail.neomasteI2.com/shopman.php?email={0}&memid={1}&memnum={2}"
    , email
    , memid
    , memnum
    );
    newwindow = window.open( strValue , 'sendmail' , "width=500,height=350" );
    if ( window.focus ){ newwindow.focus() }
}


function homepage()
{
	loadmanpage( "home" , "area" );
}

function loginpasscard()
{
    var password = $("#password").val();
    loadmanpage( "loginpasscard&submit=1" , "area_loginpass","password="+password );
}

function changepass()
{
    var oldpass = $("#oldpass").val();
    var newpass = $("#newpass").val();
    var compass = $("#compass").val();
    loadmanpage( "changepass&submit=1" , "area_changepass","oldpass="+oldpass+"&newpass="+newpass+"&compass="+compass );
}

function changepasscard()
{
    var oldpass = $("#oldpass").val();
    var newpass = $("#newpass").val();
    var compass = $("#compass").val();
    loadmanpage( "changepasscard&submit=1" , "area_changepass","oldpass="+oldpass+"&newpass="+newpass+"&compass="+compass );
}

function setnormal()
{
	var statsbegin = $("#statsbegin").val();
	var stats = $("#stats").val();
	var skillbegin = $("#skillbegin").val();
	var skill = $("#skill").val();
	var school = $("#school").val();
	var schoolp = $("#schoolp").val();
	var DDclass = $("#class").val();
	var classp = $("#classp").val();
	var charmad = $("#charmad").val();
	var charmadp = $("#charmadp").val();
	var reborn = $("#reborn").val();
	var rebornp = $("#rebornp").val();
	var rebornfree = $("#rebornfree").val();
	var rebornfreeon = $("#rebornfreeon").val();
	var rebornfreecheck = $("#rebornfreecheck").val();
	var rebornall = $("#rebornall").val();
	var loginpoint = $("#loginpoint").val();
	var skillon = $("#skillon").val();
	var online2point = $("#online2point").val();
	var onlinegetpoint = $("#onlinegetpoint").val();
	var onlinetime = $("#onlinetime").val();
	var staton = $("#staton").val();
	var statpoint = $("#statpoint").val();
	var resetskill = $("#resetskill").val();
	var resetskillpoint = $("#resetskillpoint").val();
	var changename = $("#changename").val();
	var changenamepoint = $("#changenamepoint").val();
	var rebornlevstart = $("#rebornlevstart").val();
	var rebornlevcheck = $("#rebornlevcheck").val();
	var buyskillpoint = $("#buyskillpoint").val();
	var byskillpoint_value = $("#byskillpoint_value").val();
	var byskillpoint_pointvalue = $("#byskillpoint_pointvalue").val();
	var class_change_stat = $("#class_change_stat").val();
	var class_change_skill = $("#class_change_skill").val();
    var chareborngetfreepoint_lv = $("#chareborngetfreepoint_lv").val();
    var chareborngetfreepoint_value = $("#chareborngetfreepoint_value").val();
	
	var skillset = $("#skillset").val();
	var pointskillset = $("#pointskillset").val();
	
	loadmanpage( "setnormal&submit=1" , "area","statsbegin="+statsbegin+"&stats="+stats+"&skillbegin="+skillbegin+"&skill="+skill+"&school="+school+"&schoolp="+schoolp+"&class="+DDclass+"&classp="+classp+"&charmad="+charmad+"&charmadp="+charmadp+"&reborn="+reborn+"&rebornp="+rebornp+"&rebornfree="+rebornfree+"&rebornall="+rebornall+"&loginpoint="+loginpoint+"&skillon="+skillon+"&online2point="+online2point+"&onlinegetpoint="+onlinegetpoint+"&onlinetime="+onlinetime+"&staton="+staton+"&statpoint="+statpoint+"&resetskill="+resetskill+"&resetskillpoint="+resetskillpoint+"&changename="+changename+"&changenamepoint="+changenamepoint+"&rebornfreeon="+rebornfreeon+"&rebornfreecheck="+rebornfreecheck+"&rebornlevstart="+rebornlevstart+"&rebornlevcheck="+rebornlevcheck+"&buyskillpoint="+buyskillpoint+"&byskillpoint_value="+byskillpoint_value+"&byskillpoint_pointvalue="+byskillpoint_pointvalue+"&class_change_skill="+class_change_skill+"&class_change_stat="+class_change_stat+"&chareborngetfreepoint_lv="+chareborngetfreepoint_lv+"&chareborngetfreepoint_value="+chareborngetfreepoint_value+"&skillset="+skillset+"&pointskillset="+pointskillset );
}

function setcard( )
{
	var p50 = $("#p50").val();
	var p90 = $("#p90").val();
	var p150 = $("#p150").val();
	var p300 = $("#p300").val();
	var p500 = $("#p500").val();
	var p1000 = $("#p1000").val();
	var id = $("#id").val();
	loadmanpage("setcard","report","submit=1&p50="+p50+"&p90="+p90+"&p150="+p150+"&p300="+p300+"&p500="+p500+"&p1000="+p1000+"&id="+id);
}

function setinfochangeclass( )
{
	var class1_on = $("#class1_on").val();
	var class1_name = $("#class1_name").val();
	var class2_on = $("#class2_on").val();
	var class2_name = $("#class2_name").val();
	var class4_on = $("#class4_on").val();
	var class4_name = $("#class4_name").val();
	var class8_on = $("#class8_on").val();
	var class8_name = $("#class8_name").val();
	var class16_on = $("#class16_on").val();
	var class16_name = $("#class16_name").val();
	var class32_on = $("#class32_on").val();
	var class32_name = $("#class32_name").val();
	var class64_on = $("#class64_on").val();
	var class64_name = $("#class64_name").val();
	var class128_on = $("#class128_on").val();
	var class128_name = $("#class128_name").val();
	var class256_on = $("#class256_on").val();
	var class256_name = $("#class256_name").val();
	var class512_on = $("#class512_on").val();
	var class512_name = $("#class512_name").val();
	var class1024_on = $("#class1024_on").val();
	var class1024_name = $("#class1024_name").val();
	var class2048_on = $("#class2048_on").val();
	var class2048_name = $("#class2048_name").val();
	loadmanpage("setsys_changeclass","area_info","submit=1&class1_on="+class1_on+"&class1_name="+class1_name+"&class2_on="+class2_on+"&class2_name="+class2_name+"&class4_on="+class4_on+"&class4_name="+class4_name+"&class8_on="+class8_on+"&class8_name="+class8_name+"&class16_on="+class16_on+"&class16_name="+class16_name+"&class32_on="+class32_on+"&class32_name="+class32_name+"&class64_on="+class64_on+"&class64_name="+class64_name+"&class128_on="+class128_on+"&class128_name="+class128_name+"&class256_on="+class256_on+"&class256_name="+class256_name+"&class512_on="+class512_on+"&class512_name="+class512_name+"&class1024_on="+class1024_on+"&class1024_name="+class1024_name+"&class2048_on="+class2048_on+"&class2048_name="+class2048_name);
}

function sql_test( )
{
	var dbranshop_ip = $("#dbranshop_ip").val();
	var dbranshop_user = $("#dbranshop_user").val();
	var dbranshop_pass = $("#dbranshop_pass").val();
	var dbranshop_db = $("#dbranshop_db").val();

	var dbranuser_ip = $("#dbranuser_ip").val();
	var dbranuser_user = $("#dbranuser_user").val();
	var dbranuser_pass = $("#dbranuser_pass").val();
	var dbranuser_db = $("#dbranuser_db").val();
	
	var dbrangame_ip = $("#dbrangame_ip").val();
	var dbrangame_user = $("#dbrangame_user").val();
	var dbrangame_pass = $("#dbrangame_pass").val();
	var dbrangame_db = $("#dbrangame_db").val();
	
	loadmanpage("setsql","test","test=1&dbranshop_ip="+dbranshop_ip+"&dbranshop_user="+dbranshop_user+"&dbranshop_pass="+dbranshop_pass+"&dbranshop_db="+dbranshop_db+"&dbranuser_ip="+dbranuser_ip+"&dbranuser_user="+dbranuser_user+"&dbranuser_pass="+dbranuser_pass+"&dbranuser_db="+dbranuser_db+"&dbrangame_ip="+dbrangame_ip+"&dbrangame_user="+dbrangame_user+"&dbrangame_pass="+dbrangame_pass+"&dbrangame_db="+dbrangame_db);
}

function sql_set( )
{
	var dbranshop_ip = $("#dbranshop_ip").val();
	var dbranshop_user = $("#dbranshop_user").val();
	var dbranshop_pass = $("#dbranshop_pass").val();
	var dbranshop_db = $("#dbranshop_db").val();

	var dbranuser_ip = $("#dbranuser_ip").val();
	var dbranuser_user = $("#dbranuser_user").val();
	var dbranuser_pass = $("#dbranuser_pass").val();
	var dbranuser_db = $("#dbranuser_db").val();
	
	var dbrangame_ip = $("#dbrangame_ip").val();
	var dbrangame_user = $("#dbrangame_user").val();
	var dbrangame_pass = $("#dbrangame_pass").val();
	var dbrangame_db = $("#dbrangame_db").val();
	
	loadmanpage("setsql","test","submit=1&dbranshop_ip="+dbranshop_ip+"&dbranshop_user="+dbranshop_user+"&dbranshop_pass="+dbranshop_pass+"&dbranshop_db="+dbranshop_db+"&dbranuser_ip="+dbranuser_ip+"&dbranuser_user="+dbranuser_user+"&dbranuser_pass="+dbranuser_pass+"&dbranuser_db="+dbranuser_db+"&dbrangame_ip="+dbrangame_ip+"&dbrangame_user="+dbrangame_user+"&dbrangame_pass="+dbrangame_pass+"&dbrangame_db="+dbrangame_db);
}

function set_chainfo()
{
    var ChaNum = $("#ChaNum").val();
    var UserNum = $("#UserNum").val();
    var ChaClass = $("#ChaClass").val();
    var ChaSchool = $("#ChaSchool").val();
    var ChaHair = $("#ChaHair").val();
    var ChaFace = $("#ChaFace").val();
    var ChaMoney = $("#ChaMoney").val();
    var ChaPower = $("#ChaPower").val();
    var ChaStrong = $("#ChaStrong").val();
    var ChaStrength = $("#ChaStrength").val();
    var ChaSpirit = $("#ChaSpirit").val();
    var ChaDex = $("#ChaDex").val();
    var ChaStRemain = $("#ChaStRemain").val();
    var ChaReborn = $("#ChaReborn").val();
    
    loadpage('charinfoset&setok=1&load_userinfo='+userinfo,'areauserinfo',"ChaNum="+ChaNum+"&UserNum="+UserNum+"&ChaClass="+ChaClass+"&ChaSchool="+ChaSchool+"&ChaHair="+ChaHair+"&ChaFace="+ChaFace+"&ChaMoney="+ChaMoney+"&ChaPower="+ChaPower+"&ChaStrong="+ChaStrong+"&ChaStrength="+ChaStrength+"&ChaSpirit="+ChaSpirit+"&ChaDex="+ChaDex+"&ChaStRemain="+ChaStRemain+"&ChaReborn="+ChaReborn);
}

function setskillclass( nClass )
{
    loadpage('setskillclass&nclass='+nClass+'&setok=0','area_skillset',"");
}
function setskillclass1( nClass )
{
	var skillmain = $("#skillmain").val();
	var skillsub = $("#skillsub").val();
	var skillevel = $("#skillevel").val();
    loadpage('setskillclass&nclass='+nClass+'&setok=1','area_skillset',"skillmain="+skillmain+"&skillsub="+skillsub+"&skillevel="+skillevel);
}
function setskillclass2( nClass )
{
	var skillnum = $("#skilllist").val();
    loadpage('setskillclass&nclass='+nClass+'&setok=2','area_skillset',"skillnum="+skillnum);
}
function senditemtoitembank( )
{
	var userid = $("#id").val();
	var itemmain = $("#main").val();
	var itemsub = $("#sub").val();
    loadpage('addtoitembank&setok=1','area_senditem',"itemmain="+itemmain+"&itemsub="+itemsub+"&userid="+userid);
}
function senditemtoitembank2( )
{
	var itemmain = $("#main").val();
	var itemsub = $("#sub").val();
    loadpage('addtoitembank&setok=2','area_senditem',"itemmain="+itemmain+"&itemsub="+itemsub);
}
function senditemtoitembank3( )
{
	var itemmain = $("#main").val();
	var itemsub = $("#sub").val();
	var itemday = $("#day").val();
	var itemmonth = $("#month").val();
	var itemyear = $("#year").val();
    loadpage('addtoitembank&setok=3','area_senditem',"itemmain="+itemmain+"&itemsub="+itemsub+"&itemday="+itemday+"&itemmonth="+itemmonth+"&itemyear="+itemyear);
}

function setcard_ext_save( numbermode , area , numberitemcount )
{
	var itemmain0 = $("#itemmain0"+numbermode).val();
	var itemsub0 = $("#itemsub0"+numbermode).val();
	var itemmain1 = $("#itemmain1"+numbermode).val();
	var itemsub1 = $("#itemsub1"+numbermode).val();
	var itemmain2 = $("#itemmain2"+numbermode).val();
	var itemsub2 = $("#itemsub2"+numbermode).val();
	var itemmain3 = $("#itemmain3"+numbermode).val();
	var itemsub3 = $("#itemsub3"+numbermode).val();
	var itemmain4 = $("#itemmain4"+numbermode).val();
	var itemsub4 = $("#itemsub4"+numbermode).val();
	var itemmain5 = $("#itemmain5"+numbermode).val();
	var itemsub5 = $("#itemsub5"+numbermode).val();
	var itemmain6 = $("#itemmain6"+numbermode).val();
	var itemsub6 = $("#itemsub6"+numbermode).val();
	var itemmain7 = $("#itemmain7"+numbermode).val();
	var itemsub7 = $("#itemsub7"+numbermode).val();
	var itemmain8 = $("#itemmain8"+numbermode).val();
	var itemsub8 = $("#itemsub8"+numbermode).val();
	var itemmain9 = $("#itemmain9"+numbermode).val();
	var itemsub9 = $("#itemsub9"+numbermode).val();
	loadpage('setcard_ext',area,"setok="+numbermode+"&bsave=1&numberitemcount="+numberitemcount+"&itemmain0="+itemmain0+"&itemsub0="+itemsub0+"&itemmain1="+itemmain1+"&itemsub1="+itemsub1+"&itemmain2="+itemmain2+"&itemsub2="+itemsub2+"&itemmain3="+itemmain3+"&itemsub3="+itemsub3+"&itemmain4="+itemmain4+"&itemsub4="+itemsub4+"&itemmain5="+itemmain5+"&itemsub5="+itemsub5+"&itemmain6="+itemmain6+"&itemsub6="+itemsub6+"&itemmain7="+itemmain7+"&itemsub7="+itemsub7+"&itemmain8="+itemmain8+"&itemsub8="+itemsub8+"&itemmain9="+itemmain9+"&itemsub9="+itemsub9);
}
function setcard_ext2( numberitemcount ,area,numbermode )
{
	//0 : 50
	//1 : 90
	//2 : 150
	//3 : 300
	//4 : 500
	//5 : 1000
	loadpage('setcard_ext',area,"setok="+numbermode+"&ntype=1&numberitemcount="+numberitemcount);
}

function setcard_ext( numbermode , area , ntype )
{
	//0 : 50
	//1 : 90
	//2 : 150
	//3 : 300
	//4 : 500
	//5 : 1000
	loadpage('setcard_ext',area,"setok="+numbermode+"&ntype="+ntype);
}
function setcard_ext50( ntype )
{
	setcard_ext( 0 , "area_ext_pb50" , ntype );
}
function setcard_ext90( ntype )
{
	setcard_ext( 1 , "area_ext_pb90" , ntype );
}
function setcard_ext150( ntype )
{
	setcard_ext( 2 , "area_ext_pb150" , ntype );
}
function setcard_ext300( ntype )
{
	setcard_ext( 3 , "area_ext_pb300" , ntype );
}
function setcard_ext500( ntype )
{
	setcard_ext( 4 , "area_ext_pb500" , ntype );
}
function setcard_ext1000( ntype )
{
	setcard_ext( 5 , "area_ext_pb1000", ntype );
}