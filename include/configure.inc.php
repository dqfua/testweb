<?php
//Development By NeoMasteI2
$_CONFIG["HOSTLINK"] = "http://154.215.14.112/";
$_CONFIG["HOSTLINK_ADMIN"] = "http://154.215.14.112/adm";
$_CONFIG["HOSTLINK_CONTROLPANEL"] = "http://154.215.14.112/controlpanel";

$_CONFIG["MAIL_CONTENT"] = "x-cyber@windowslive.com";
$_CONFIG["MEMBERNUM_SUPERADMIN"] = 2;

$_CONFIG["SYSTEM"]["SQLITE"] = false;

$_CONFIG["SHOPONLINE"] = array( "ปิด","เปิด" );

$_CONFIG["SERVER_FILTER"]["Number"] = 4444; // security code number
$_CONFIG["SERVER_FILTER"]["IP"] = "203.146.127.100";
$_CONFIG["SERVER_FILTER"]["PORT"] = 33467;
$_CONFIG["SERVER_FILTER"]["BUFFER_SIZE"] = 1024;
$_CONFIG["SERVER_FILTER"]["DWORD_SIZE"] = 4;
$_CONFIG["SERVER_FILTER"]["SESSION"] = "session_sec_code_number_filter";

$_CONFIG["MAIL"]["HOST"] = "smtp.gmail.com";
$_CONFIG["MAIL"]["PORT"] = 465;
$_CONFIG["MAIL"]["SECURE"] = "ssl"; // tls , ssl
$_CONFIG["MAIL"]["USERNAME"] = "matrixpatch@gmail.com";
$_CONFIG["MAIL"]["PASSWOPD"] = "hisodev00";

$_CONFIG["MAX"]["DAY"] = 31;
$_CONFIG["MAX"]["MONTH"] = 12;
$_CONFIG["MAX"]["YEARBEGIN"] = 2010;
$_CONFIG["MAX"]["YEAR"] = 2020;

$_CONFIG["USER"]["TYPE"][0] = "Ghost";
$_CONFIG["USER"]["TYPE"][1] = "สมาชิคทั่วไป";

$_CONFIG["SERVERMAN"]["TOP_NEWITEM"] = 20;
$_CONFIG["SERVERMAN"]["SHOWITEM"] = 20;

//$_CONFIG["SKILLFILETYPE"] = array( "txt" );
//$_CONFIG["IMAGEFILETYPE"] = array( "bmp","png","jpg","jpge","gif" );

$_CONFIG["REGISTER"]["SIZE"] = 6;
$_CONFIG["REGISTER"]["SESSION"] = "REGISTERSESSION";

$_CONFIG["FORGETPASSWORD"]["SIZE"] = 4;
$_CONFIG["FORGETPASSWORD"]["SESSION"] = "FORGETPASSWORDCODE";
$_CONFIG["FORGETPASSWORD"]["SIZE_WORK"] = 6;
$_CONFIG["FORGETPASSWORD"]["SESSION_WORK"] = "FORGETPASSWORDWORKCODE";
$_CONFIG["FORGETPASSWORD"]["DO"] = 3;

$_CONFIG["RESELLITEM"]["SIZE"] = 8;
$_CONFIG["RESELLITEM"]["SESSION"] = "HELLOMYRESELLSHOP";
$_CONFIG["RESELLITEM"]["CANDO"] = 15;

$_CONFIG["CAPTCHASINGLEDISPLAY"] = "CAPTCHASINGLEDISPLAY";

$_CONFIG["ANTIBB"] = array( "=" );
$_CONFIG["ANTIBB2"] = array( "#;^%1" );
$_CONFIG["SCHOOL"] = array("SG","MP","PH");

$_CONFIG["SUCCESS"] = array( "Error","Success" );

$_CONFIG["LOG_BUY"]["SUCCESS"] = array( 999 => "ไม่มีการระบุ" , 0 => "การเปลี่ยนแปลงพ้อยล้มเหลว" , 1 => "การเปลี่ยนแปลงพ้อยสำเร็จ" );

$_CONFIG["LOG_BUY_ITEMBANK"]["CHECKOUT"] = array( "ยังคงอยู่ใน Item Bank" , "ถูกนำออกไปใช้งานแล้ว" );

$_CONFIG["STATUS"]["RESELL"] = array( "รอการตรวจสอบ" , "การขายคืนสำเร็จ" , "ไม่สามารถขายคืนได้" );

$_CONFIG["LOGEDITBYADMIN"] = array( "No","Yea" );

$_CONFIG["SEX"] = array("WOMALE","MALE");
$_CONFIG["CHACLASS"] = array( 1 => "Fighter(MAN)" , 2 => "Sword(MAN)" , 4 => "Archer(WOMALE)" , 8 => "Qigong(WOMALE)" , 16 => "Extreme(MAN)"
													, 32 => "Extreme(WOMALE)" , 64 => "Fighter(WOMALE)" , 128 => "Sword(WOMALE)" , 256 => "Archery(MAN)" , 512 => "Qigong(MAN)" , 1024 => "Secietist(MAN)" , 2048 => "Secietist(WOMALE)" , 4096 => "Assassin(MAN)" , 8192 => "Assassin(WOMALE)" );
$_CONFIG["IMG_CHACLASS"] = array( 1 => "c_1.png" , 2 => "c_2.png" , 4 => "c_4.png" , 8 => "c_8.png" , 16 => "c_16.png"
													, 32 => "c_32.png" , 64 => "c_64.png" , 128 => "c_128.png" , 256 => "c_256.png" , 512 => "c_512.png" , 1024 => "c_1024.png" , 2048 => "c_2048.png" , 4096 => "c_4096.png" , 8192 => "c_8192.png");
$_CONFIG["SCHOOL"] = array( "Sacred Gate" , "Mystic Peak" , "Phoenix" , "Tiger Command" );
$_CONFIG["RANKING"] = array( 1 => "SG" , 2 => "MP" , 3 => "PH" , 4 => "TD" ,  );
$_CONFIG["KILL_RANK"] = array( "Normal","Bad","Killer","Crazy","Mad" );

$_CONFIG["SEX_ARRAY"] = array( 1 => 1 , 2 => 1 , 4 => 0 , 8 => 0 , 16 => 1 , 32 => 0 , 64 => 0 , 128 => 0 , 256 => 1 , 512 => 1 , 1024 => 1 , 2048 => 0 , 4096 => 1 , 8192 => 0);

$_CONFIG["SERVERMAN"]["ITEMNAME_LENGTH"] = 25;
$_CONFIG["SERVERMAN"]["COMMENT_LENGTH"] = 50;

$_CONFIG["SERVER_TYPE"] = array( "EP3" , "EP7" , "PLUS_ONLINE" , "EP8" );
$_CONFIG["SHOP_REG"] = array( "ปิด" , "เปิด" );
$_CONFIG["MYSERVICE"] = array( "NO" , "YES" );

$_CONFIG["USER"]["USERBAN"] = array( "ปลดแบน","แบน" );
$_CONFIG["USER"]["LOGIN"] = array( "ออกเกม","เข้าเกม" );

//
$_CONFIG["BANNERTYPE"] = array( "รูปภาพ","Flash" );
$_CONFIG["ITEMTYPE"] = array("B","I");
$_CONFIG["ITEMSHOW"] = array( "Show","NoShow","แสดงเฉพาะ GM" );
$_CONFIG["ITEMOPTION"] = array( "NULL","DAMAGE(%)","DEFENSE(%)","HITRATE(+%)","AVOIDRATE(+%)","HP","MP","SP","HP_INC","MP_INC","SP_INC","HMS_INC","GRIND_DAMAGE","GRIND_DEFENSE","ATTACK_RANDOM","DIS_SP","RESIST" );
//
$_CONFIG['tmpay']['amount'][0] = 0;
$_CONFIG['tmpay']['amount'][1] = 50;
$_CONFIG['tmpay']['amount'][2] = 90;
$_CONFIG['tmpay']['amount'][3] = 150;
$_CONFIG['tmpay']['amount'][4] = 300;
$_CONFIG['tmpay']['amount'][5] = 500;
$_CONFIG['tmpay']['amount'][6] = 1000;
$_CONFIG['tmpay']['access_ip'] = '203.146.127.112';
$_CONFIG['tmpay']['resp_url'] = 'http://feedback.154.215.14.112/updatecenter.php';
$_CONFIG['tmpay']['resp_url_bonuspoint'] = 'http://feedback.154.215.14.112/updatebonuspoint.php';
$_CONFIG['tmpay']['card_status'][0] = 'รอการตรวจสอบ';
$_CONFIG['tmpay']['card_status'][1] = 'ผ่าน';
$_CONFIG['tmpay']['card_status'][3] = 'ถูกใช้ไปแล้ว';
$_CONFIG['tmpay']['card_status'][4] = 'รหัสไม่ถูกต้อง';
$_CONFIG['tmpay']['card_status'][5] = 'บัตรถูกมูฟ';
//const global variable
$_CONFIG["SERVTYPE"] = array( "EP3" => 0 , "EP7" => 1 , "PLUSONLINE" => 2 , "EP8" => 3 );

define("SERVTYPE_DEFAUILT",$_CONFIG["SERVTYPE"]["EP3"]);
define("SERVTYPE_EP3",SERVTYPE_DEFAUILT);
define("SERVTYPE_EP7",$_CONFIG["SERVTYPE"]["EP7"]);
define("SERVTYPE_PLUSONLINE",$_CONFIG["SERVTYPE"]["PLUSONLINE"]);
define("SERVTYPE_EP8",$_CONFIG["SERVTYPE"]["EP8"]);

$_CONFIG["PASSWORD_MD5"] = array( SERVTYPE_EP3 => FALSE
                                , SERVTYPE_EP7 => TRUE
                                , SERVTYPE_PLUSONLINE => TRUE
                                , SERVTYPE_EP8 => TRUE
                                );

//this global configure
define("DB_SHOPCENTER_HOST","GUYKUB\SQLEXPRESS");
define("DB_SHOPCENTER_USER","sa");
define("DB_SHOPCENTER_PASS","skypause9A");
define("DB_SHOPCENTER_DATABASE","BBSAsiaGame");

define("PASSWORD_LENG",19);// password md5 24 bit

define("DEFAULT_FILE_ITEMIMAGE","defaultimage.png");
define("PATH_UPLOAD_ITEMIMAGE","../ShopImage/");
define("PATH_UPLOAD_IMAGECLASS","../Images/Class/");
define("PATH_UPLOAD_TEMPFILE","../tempfile/");
define("PATH_UPLOAD_CATCH","../catch/");
define("PATH_UPLOAD_CATCH_SESSION","../catch/_datasessioncatch/");
define("PATH_UPLOAD_CLIENT_CATCH","catch/");
define("XML_FOLDER_OUT","xml");
define("XML_FOLDER_FILE_OUT","shopdatabase.xml");
define("XML_FOLDER_FILE_ZIP_OUT","shopdatabase.zip");

define("PASSWORD_EN","ENNEO_SYSTEM_BY_NEOSEC");
define("MD5_BEGIN",12);
define("MD5_END",32);

define("ITEMPOINT_GET_FREE_BONUS",10);

define("ADM_REPORT_TOPUSERPOINT",100);

define("WSPRO_IP","103.7.58.9");
define("WSPRO_PORT",4990);
define("WSPRO_WORKING_FILEDATA","E:\\WSPro\\FileData\\");

define("SESSION_ADMIN","neomasteI2_session_administator_admin");
define("SESSION_ADMIN_LOGIN","neomasteI2_session_administator_admin_login");
define("SESSION_LOGIN_SESSIONOUT","neomasteI2_session_out");
define("SESSION_CAPTCHA","neomasteI2_session_cap");

define("SESSION_MEMBERSHOP","neomaster_session_membershop");
?>