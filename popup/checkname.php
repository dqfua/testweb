<?php
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874" />
<title>ตรวจสอบชื่อตัวละคร</title>

<?php
include( "../global.loader.php" );
?>

</head>

<body>
<div align="center">
<?php
/*
$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){echo "Work";exit;}
$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
COnline::OnlineSet( $cUser );
$UserID = $cUser->GetUserID();
$UserNum = $cUser->GetUserNum();
if ( $UserNum <= 0 ) exit;
$bChaLogin = CGlobal::GetSes( CGlobal::GetSesChaManLogin() );
if ( !$bChaLogin ) die("กรุณาเลือกตัวละครก่อนใช้งานระบบนี้<br>");
*/

CInput::GetInstance()->BuildFrom( IN_GET );
//$MemNum = @CNeoInject::sec_Int( CGlobal::__GET2("MemNum") );
//$ChaName = CGlobal::__GET2("ChaName");
$MemNum = CInput::GetInstance()->GetValueInt( "MemNum" , IN_GET );
$ChaName = CInput::GetInstance()->GetValueString( "ChaName" , IN_GET );
$ChaName = @CNeoInject::sec_Ban( $ChaName );
$ChaName = @CNeoInject::sec_Thai( $ChaName );

if ( $MemNum <= 0 ) exit;

printf( "ชื่อที่คุณต้องการคือ : %s <br>\n",$ChaName );
if ( strlen( $ChaName ) < 4 || strlen( $ChaName ) > 16 ) die( "<font color=red><b>ความยาวชื่อตัวละครของคุณไม่ถูกต้อง!!</font></b>" );

//session เอาไว้เก็บ userid และ รหัสผ่านของสมาชิค จำเป็นต้องเปลี่ยนแต่ละเซิยเวอร์ห้ามเหมือนกัน
$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] = $MemNum;
include("../client/inc/configure.inc.php");

//printf( "\$MemNum = %d <br>\n",$MemNum );
//printf( "\$ChaName = %s <br>\n",$ChaName );

$cWeb = new CNeoWeb;
$cWeb->GetDBInfoFromWebDB( $MemNum );
$cWeb->GetSysmFromDB( $MemNum );

if ( $cWeb->Sys_ChangeName != 1 ) { die("<font color=red><b>ระบบนี้ยังไม่เปิดให้ใช้บริการค่ะ</b></font>"); }

$cUser = NULL;
if ( COnline::OnlineGoodCheck( $cUser ) != ONLINE ){	exit;}
$UserPoint = $cUser->GetUserPoint();
$UsePoint = $cWeb->Sys_ChangeName_Point;
$NewPoint = $UserPoint-$UsePoint;

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanGame( $cWeb->GetRanGame_IP(), $cWeb->GetRanGame_User(), $cWeb->GetRanGame_Pass(), $cWeb->GetRanGame_DB() );
$szTemp = sprintf(  "SELECT ChaNum FROM ChaInfo WHERE ChaName = '%s' ",$ChaName );
$cNeoSQLConnectODBC->QueryRanGame( $szTemp );
$ChaNum = 0;
while( $cNeoSQLConnectODBC->FetchRow() ){
	$ChaNum = $cNeoSQLConnectODBC->Result( "ChaNum" , ODBC_RETYPE_INT );
}

if ( $ChaNum != 0 ){
	printf( "<font color=red><b>ชื่อนี้ถูกใช้งานแล้วไม่สามารถเปลี่ยนได้!!</b></font><br>\n" );
}else{
	printf( "<font color=green><b>ชื่อนี้สามารถใช้งานได้ค่ะ</b></font><br>\n" );
	if ( CInput::GetInstance()->GetValueInt( "submit" , IN_GET ) == 1 )
	{
		if ( $NewPoint < 0 )
		{
			echo "<font color=red><b>ไม่สามารถเปลียนได้เนื่องจากพ้อยของคุณไม่เพียงพอ!!</b></font><br>\n";
		}else{
			$error = 0;
			$pCha = NULL;
			if ( CChaOnline::OnlineGoodCheck( $pCha ) != ONLINE ) { $error++; }
			if ( $error == 0 )
			{
				if (  !$pCha->GetNowOnline() ){	$error++;	}
				if ( $error == 0 )
				{
					$ChaNameOld = $pCha->GetChaName();
					$ChaNum = $pCha->GetChaNum();
					$szTemp = sprintf(  "UPDATE ChaInfo SET ChaName = '%s' WHERE ChaNum = %d",$ChaName,$ChaNum );
					$pQuery = $cNeoSQLConnectODBC->QueryRanGame( $szTemp );
					$pCha->Update2Session_ChaName();
					CChaOnline::OnlineSet( $pCha );
					
					CDebugLog::Write( sprintf( "[%d] : %s" , $pQuery , $szTemp ) );
					
					$cUser->SetUserPoint( $NewPoint );
					$cUser->UpdateUserPointToDB();
					COnline::OnlineSet( $cUser );
					
					CNeoLog::LogChangeName( $MemNum,$cUser->GetUserNum(),$ChaNum,$ChaNameOld,$ChaName,$UserPoint,$NewPoint );
					
					printf( "ชื่อตัวละครเก่าคุณคือ : %s <br>\n ชื่อตัวละครที่คุณเปลี่ยนไปคือ : %s<br>\n",$ChaNameOld,$ChaName );
					printf( "<font color=green><b>เปลี่ยนชื่อตัวละครสำเร็จ!!</b></font>" );
				}else{
					printf( "<font color=red><b>ไม่สามารถเปลี่ยนได้เนื่องจากตัวละครอาจจะออนไลน์อยู่!!</b></font><br>\n" );
				}
			}else{
				echo "กรุณาเลือกตัวละครก่อน!!<br>\n";
			}
		}
	}
}

$cNeoSQLConnectODBC->CloseRanGame();
?>
</div>
</body>
</html>
