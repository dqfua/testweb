<?php
include("../global.loader.php");
?>
<html>
<head>
<title>Shop-Center Register :: Work || VeryWork</title>
</head>
<body>
<center><font color=red><b>ติดต่อบริการเช่า shop ของเราได้ที่ x-cyber@windowslive.com เท่านั้นไม่มีที่อื่น!!</b></font></center><br>
<?php
CInput::GetInstance()->BuildFrom( IN_GET );
CInput::GetInstance()->BuildFrom( IN_POST );

$nSubmit = CInput::GetInstance()->GetValueInt( "submit" , IN_GET );
if ( $nSubmit == 1 )
{
	$id = CInput::GetInstance()->GetValueString( "id" , IN_POST );
	$pass = CInput::GetInstance()->GetValueString( "pass" , IN_POST );
	$pass2 = CInput::GetInstance()->GetValueString( "pass2" , IN_POST );
        $passcard = CInput::GetInstance()->GetValueString( "passcard" , IN_POST );
	$email = CInput::GetInstance()->GetValueString( "email" , IN_POST );
	$servername = CInput::GetInstance()->GetValueString( "servername" , IN_POST );
	$servertype = CInput::GetInstance()->GetValueInt( "servertype" , IN_POST );
	if ( strlen($id) < 4 || strlen($id) > 16 )
	{
		echo "ความยาวของไอดีจะต้องมีจำนวน 4 น้อยกว่า 16 ตัวอักษร <br>";
		exit;
	}
	if ( strlen($pass) < 4 || strlen($pass) > 16 )
	{
		echo "ความยาวของรหัสเข้า 4 น้อยกว่า 16 <br>";
		exit;
	}
        if ( strlen($passcard) < 4 || strlen($passcard) > 16 )
	{
		echo "ความยาวของรหัสดูรายรับต้องมีความยาว 4 น้อยกว่า 16 <br>";
		exit;
	}
	if ( strcmp($pass,$pass2) != 0 )
	{
		echo "การยืนยันรหัสไม่ถูกต้อง กรุณาใส่ยืนยันรหัสให้ตรงกัน!!<br>";
		exit;
	}
	if ( strlen($email) < 4 || empty($email) )
	{
		echo "กรุณาใส่ อีเมล์ ที่สามารถติดต่อได้<br>";
		exit;
	}
	if ( strlen($servername) < 4 || empty($servername) )
	{
		echo "กรุณาใส่ ชื่อเซิร์ฟเวอร์ ด้วยค่ะ<br>";
		exit;
	}
	if (  $servertype  != SERVTYPE_EP3 && $servertype != SERVTYPE_EP7 && $servertype != SERVTYPE_PLUSONLINE )
	{
		die( "กรุณาเลือก ชนิดเซิร์ฟเวอร์ ให้ถูกต้อง!!" );
	}
	$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
	$cNeoSQLConnectODBC->ConnectRanWeb();
	$szTemp = sprintf("SELECT MemberNum FROM MemberInfo WHERE MemID = '%s' ",$id);
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	$bOK = false;
	while( !$cNeoSQLConnectODBC->FetchRow() )
	{
		$szTemp = sprintf("INSERT INTO MemberInfo(MemID,MemPass,MemPass_Card,MemType,EMail,ServerName,ServerType) VALUES('%s','%s','%s',1,'%s','%s',%d) ",$id,$pass,$passcard,$email,$servername,$servertype);
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		
		$szTemp = sprintf("SELECT MemberNum FROM MemberInfo WHERE MemID = '%s' ",$id);
		$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
		$MemNum = $cNeoSQLConnectODBC->Result("MemberNum",ODBC_RETYPE_INT);
		
		$szTemp = sprintf("INSERT INTO MemPoint(MemNum) VALUES(%d)",$MemNum);
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		
		$szTemp = sprintf( "INSERT INTO MemSys(MemNum) VALUES(%d)",$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		
		$szTemp = sprintf( "INSERT INTO MemSQL(MemNum) VALUES(%d)",$MemNum );
		$cNeoSQLConnectODBC->QueryRanWeb( $szTemp );
		
		
		$bOK = TRUE;
		break;
	}
	$cNeoSQLConnectODBC->CloseRanWeb();
	echo "หลังจากลงทะเบียนใช้งาน กรุณาแจ้ง Member No. ของคุณ และชื่อไอดีของคุณให้กับทีมงานผู้ดูแลเพื่อเปิดใข้งาน<br>";
	echo "<a href='../admin/'><b>คลิกเพื่อไปหน้าล็อกอิน</b></a><br>";
	if ( $bOK )
		die("สมัครเรียบร้อย<br>");
	else
		die("ID มีอยู่ในระบบแล้ว");
}
?>

<form id="form1" name="form1" method="post" action="?submit=1">
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="5">
    <tr>
      <td colspan="2" align="center"><strong>สมัครสมาชิค</strong></td>
    </tr>
    <tr>
      <td width="224">ไอดี:</td>
      <td width="361"><label>
        <input type="text" name="id" id="id" />
      </label></td>
    </tr>
    <tr>
      <td width="224">ชื่อเซิร์ฟเวอร์:</td>
      <td width="361"><label>
        <input type="text" name="servername" id="servername" />
      </label></td>
    </tr>
    <tr>
      <td width="224">อีเมล์</td>
      <td width="361"><label>
        <input type="text" name="email" id="email" /> : <b>ต้องสามารถติดต่อได้จริง!!</b>
      </label></td>
    </tr>
    <tr>
      <td>รหัสผ่าน :</td>
      <td><input type="password" name="pass" id="pass" /></td>
    </tr>
    <tr>
      <td>ยืนยันรหัสผ่าน:</td>
      <td><input type="password" name="pass2" id="pass2" /></td>
    </tr>
    <tr>
      <td>รหัสผ่าน - ลับ :</td>
      <td><input type="password" name="passcard" id="passcard" /></td>
    </tr>
    <tr>
      <td>ชนิดเซิร์ฟเวอร์</td>
      <td><label>
        <select name="servertype" id="servertype">
          <option value="<?php echo SERVTYPE_EP3; ?>" selected="selected">EP3</option>
          <option value="<?php echo SERVTYPE_EP7; ?>">EP6 , EP7</option>
          <option value="<?php echo SERVTYPE_PLUSONLINE; ?>">PLUS_ONLINE</option>
        </select>
      </label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="button" id="button" value="สมัครสมาชิค" />
      </label></td>
    </tr>
  </table>
</form>
</body>
</html>