<?php
/*
 * Type ของเมล์ที่ส่ง
 * 0 คือ เมล์ทั่วไป สามารถเปลี่ยนข้อความเองได้ก่อนส่ง
 * 1 คือ เมล์แจ้งเตือนวันว่าอีกกี่วันจะหมดอายุ
 * 2 คือ เมล์แจ้งเตือนครบอายุการใช้งาน
 * 3 คือ เมล์แจ้งเตือนเลยอายุการใช้งาน
 * 4 คือ เมล์แจ้งเตือนถูกตัดการใช้การเนื่องจากครบกำหนดอายุการใช้งาน
 */
$_LANG["MAIL"]["SHOP"]["SUBJECT"][0] = "ข้อความส่งจาก gamecentershop.com ถึงคุณ {USERNAME}";
$_LANG["MAIL"]["SHOP"]["MESSAGE"][0] = "สวัสดีคุณ <b>{USERNAME}</b><br>
นี่เป็นข้อความจาก gamecentershop.com เรามีข้อความถึงคุณ<br>
ลิ้ง Shop ของคุณคือ : <b><a href={FOLDERSHOP}>{FOLDERSHOP}></a></b><br>
ถ้าหากมีข้อสงใสเกี่ยวกับข้อความฉบับนี้ให้ติดต่อมาที่ <b>{EMAILCONTENT}</b><br>
";

$_LANG["MAIL"]["SHOP"]["SUBJECT"][1] = "แจ้งอายุการใช้งานที่เหลือของคุณ {USERNAME}";
$_LANG["MAIL"]["SHOP"]["MESSAGE"][1] = "สวัสดีคุณ <b>{USERNAME}</b><br>
นี่เป็นข้อความจาก gamecentershop.com เรามีข้อความถึงคุณ<br><hr>

อายุการใช้งานของคุณที่เหลือคือ : <b>{DAYTOUSE}</b> วัน<br>
อายุการใช้งานที่ผ่านมาคือ : <b>{DAYUSE}</b> วัน <br>
นี่คือลิ้ง Shop ของคุณ : <a href={FOLDERSHOP} target=_blank>{FOLDERSHOP}</a><br>
<br>
<b><u>รายละเอียดข้อมูลของคุณโดยคร่าวๆคือ</u></b><br>
ลงทะเบียนเมื่อ : <b>{REGTIME}</b><br>
ครบกำหนดอายุการใช้งานเมื่อ : <b>{ENDTIME}</b><br><br>

ถ้าหากมีข้อสงใสกรุณาติดต่อ : <b>{EMAILCONTENT}</b> Email และ Msn ค่ะ<br>
";

$_LANG["MAIL"]["SHOP"]["SUBJECT"][2] = "แจ้งครบกำหนดอายุการใช้งาน gamecentershop.com";
$_LANG["MAIL"]["SHOP"]["MESSAGE"][2] = "สวัสดีคุณ <b>{USERNAME}</b><br>
นี่เป็นข้อความจาก gamecentershop.com เรามีข้อความถึงคุณ<br><hr>
<br>
<font color=red><b>ครบกำหนดอายุการใช้งานของคุณ!!</b></font>
<br>
อายุการใช้งานของคุณที่เหลือคือ : <b>{DAYTOUSE}</b> วัน<br>
อายุการใช้งานที่ผ่านมาคือ : <b>{DAYUSE}</b> วัน <br>
นี่คือลิ้ง Shop ของคุณ : <a href={FOLDERSHOP} target=_blank>{FOLDERSHOP}</a><br>
<br>
<b><u>รายละเอียดข้อมูลของคุณโดยคร่าวๆคือ</u></b><br>
ลงทะเบียนเมื่อ : <b>{REGTIME}</b><br>
ครบกำหนดอายุการใช้งานเมื่อ : <b>{ENDTIME}</b><br><br><br>

<b>เมื่อคุณได้รับข้อความดังกล่าวนี้กรุณาติดต่อทีมงาน gamecentershop.com <u>ทันที</u> ถ้าคุณไม่ดำเนินการ
ทาง gamecentershop.com จะทำการยกเลิก <u>{FOLDERSHOP}</u> ออกจากระบบทันที<br><br>

<u>หลังจากที่ได้ทำการโอนเงินแล้วกรุณาส่งเมล์มาที่ : <b>{EMAILCONTENT}</b> จะดำเนินการภายในไม่เกิน 24 ซมค่ะ</u><br><br>

ถ้าหากมีข้อสงใสกรุณาติดต่อ : <b>{EMAILCONTENT}</b> Email และ Msn ค่ะ<br>
";

$_LANG["MAIL"]["SHOP"]["SUBJECT"][3] = "แจ้งเลยกำหนดอายุการใช้งาน {FOLDERSHOP}";
$_LANG["MAIL"]["SHOP"]["MESSAGE"][3] = "สวัสดีคุณ <b>{USERNAME}</b><br>
นี่เป็นข้อความจาก gamecentershop.com เรามีข้อความถึงคุณ<br><hr>
<br>
<font color=red><b>เลยครบกำหนดอายุการใช้งาน กรุณาติดต่อแจ้งชำระเงินโดยทันทีถ้าหากท่านต้องการต่ออายุ!!</b></font>
<br>
อายุการใช้งานของคุณที่เหลือคือ : <font color=red><b>{DAYTOUSE}</b></font> วัน<br>
อายุการใช้งานที่ผ่านมาคือ : <b>{DAYUSE}</b> วัน <br>
นี่คือลิ้ง Shop ของคุณ : <a href={FOLDERSHOP} target=_blank>{FOLDERSHOP}</a><br>
<br>
<b><u>รายละเอียดข้อมูลของคุณโดยคร่าวๆคือ</u></b><br>
ลงทะเบียนเมื่อ : <b>{REGTIME}</b><br>
ครบกำหนดอายุการใช้งานเมื่อ : <b>{ENDTIME}</b><br><br><br>

<b>เมื่อคุณได้รับข้อความดังกล่าวนี้กรุณาติดต่อทีมงาน gamecentershop.com <u>ทันที</u> ถ้าคุณไม่ดำเนินการ
ทาง gamecentershop.com จะทำการยกเลิก <u>{FOLDERSHOP}</u> ออกจากระบบทันที<br><br>

<u>หลังจากที่ได้ทำการโอนเงินแล้วกรุณาส่งเมล์มาที่ : <b>{EMAILCONTENT}</b> จะดำเนินการภายในไม่เกิน 24 ซมค่ะ</u><br><br>

ถ้าหากมีข้อสงใสกรุณาติดต่อ : <b>{EMAILCONTENT}</b> Email และ Msn ค่ะ<br>
";

$_LANG["MAIL"]["SHOP"]["SUBJECT"][4] = "ขอบคุณที่ใช้บริการ gamecentershop.com ค่ะ";
$_LANG["MAIL"]["SHOP"]["MESSAGE"][4] = "ลิ้ง {FOLDERSHOP} หมดอายุการใช้งานแล้ว<br>
ขอขอบคุณที่ไว้วางใจกับเราและใช้บริการของเรา<br>
<b>ขอบคุณค่ะ</b>
";
?>
