<?php
if ( !defined("SHOPNEOCP") ) die("HACKING....");

CInput::GetInstance()->BuildFrom( IN_GET );
$membernum = CInput::GetInstance()->GetValueInt( "membernum" , IN_GET );

if ( $membernum == 0 ) die("ERROR|MEMBER|FAIL");

$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();

{//table App_Log_Admin_SendMail
	$szTemp = sprintf( "DELETE App_Log_Admin_SendMail WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table BannerProject
	$szTemp = sprintf( "DELETE BannerProject WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table GameList
	$szTemp = sprintf( "DELETE GameList WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table GameLogItemBank
	$szTemp = sprintf( "DELETE GameLogItemBank WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table GameLogSerial
	$szTemp = sprintf( "DELETE GameLogSerial WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table ItemProject
	$szTemp = sprintf( "DELETE ItemProject WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_AdminBanUser
	$szTemp = sprintf( "DELETE Log_AdminBanUser WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_Buy
	$szTemp = sprintf( "DELETE Log_Buy WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_BuySkillPoint
	$szTemp = sprintf( "DELETE Log_BuySkillPoint WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_ChangeClass
	$szTemp = sprintf( "DELETE Log_ChangeClass WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_ChangeName
	$szTemp = sprintf( "DELETE Log_ChangeName WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_ChangeSchool
	$szTemp = sprintf( "DELETE Log_ChangeSchool WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_ChaReborn
	$szTemp = sprintf( "DELETE Log_ChaReborn WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_CharMad
	$szTemp = sprintf( "DELETE Log_CharMad WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_DoPass
	$szTemp = sprintf( "DELETE Log_DoPass WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_Forgetpassword
	$szTemp = sprintf( "DELETE Log_Forgetpassword WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_ItemProject
	$szTemp = sprintf( "DELETE Log_ItemProject WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_Login
	$szTemp = sprintf( "DELETE Log_Login WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_MapWarp
	$szTemp = sprintf( "DELETE Log_MapWarp WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_Reborn
	$szTemp = sprintf( "DELETE Log_Reborn WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_Resell
	$szTemp = sprintf( "DELETE Log_Resell WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_ResetSkill
	$szTemp = sprintf( "DELETE Log_ResetSkill WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_SessionLogin
	$szTemp = sprintf( "DELETE Log_SessionLogin WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_Stat
	$szTemp = sprintf( "DELETE Log_Stat WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_SysItemPointGet
	$szTemp = sprintf( "DELETE Log_SysItemPointGet WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_Time2Point
	$szTemp = sprintf( "DELETE Log_Time2Point WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Log_UserPoint
	$szTemp = sprintf( "DELETE Log_UserPoint WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table LoginFist
	$szTemp = sprintf( "DELETE LoginFist WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table MemChangeClass
	$szTemp = sprintf( "DELETE MemChangeClass WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table MemMapSet
	$szTemp = sprintf( "DELETE MemMapSet WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table MemPoint
	$szTemp = sprintf( "DELETE MemPoint WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table MemSkillPoint
	$szTemp = sprintf( "DELETE MemSkillPoint WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Refill
	$szTemp = sprintf( "DELETE Refill WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table SkillTable
	$szTemp = sprintf( "DELETE SkillTable WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table SubProject
	$szTemp = sprintf( "DELETE SubProject WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table UserInfo
	$szTemp = sprintf( "DELETE UserInfo WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table Work_Resell
	$szTemp = sprintf( "DELETE Work_Resell WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table MemSys
	$szTemp = sprintf( "DELETE MemSys WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table MemSQL
	$szTemp = sprintf( "DELETE MemSQL WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table MemSkillSet
	$szTemp = sprintf( "DELETE MemSkillSet WHERE MemNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

{//table MemberInfo
	$szTemp = sprintf( "DELETE MemberInfo WHERE MemberNum = %d" , $membernum );
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	printf("%s<br>\n",$szTemp);
}

$cNeoSQLConnectODBC->CloseRanWeb();
CGlobal::gopageQ( "home.php?pid=finddeluser" );
?>
