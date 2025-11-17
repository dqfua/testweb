<?php
// กำหนดค่าเริ่มต้นให้ตัวแปรทั้งหมด
$ItemNum = isset($ItemNum) ? $ItemNum : 0;
$ItemImage = isset($ItemImage) ? $ItemImage : 'default.jpg';
$ItemName = isset($ItemName) ? $ItemName : 'ไม่มีชื่อ';
$itemreborn = isset($itemreborn) ? $itemreborn : 0;
$ItemPrice = isset($ItemPrice) ? $ItemPrice : 0;
$ItemTimePrice = isset($ItemTimePrice) ? $ItemTimePrice : 0;
$ItemBonusPointPrice = isset($ItemBonusPointPrice) ? $ItemBonusPointPrice : 0;
?>
<table id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_Table1" cellspacing="0" cellpadding="0" border="0" style="border-width:0px;width:152px;border-collapse:collapse;">
  <tr id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableRow1">
    <td id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableCell1" align="center" valign="top" style="width:100%;"><img src="../images/right/shop_right_47.jpg" width="152" height="17" /></td>
  </tr>
  <tr id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableRow2">
    <td id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableCell2" align="center" valign="top" style="width:100%;"><table width="152" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="31"><img src="../images/right/shop_right_49.jpg" width="31" height="90" /></td>
        <td width="90"><a href="#item" onclick="load_item(<?php echo $ItemNum; ?>);"><img src="<?php echo PATH_UPLOAD_ITEMIMAGE . $ItemImage; ?>"  width="90" height="90" border="0" /></a></td>
        <td width="31"><img src="../images/right/shop_right_51.jpg" width="31" height="90" /></td>
      </tr>
    </table></td>
  </tr>
  <tr id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableRow3">
    <td id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableCell3" align="center" valign="top" style="width:100%;"><img src="../images/right/shop_right_52.jpg"  width="152" height="6" /></td>
  </tr>
  <tr id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableRow4">
    <td id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableCell4" align="center" valign="top" background="../images/right/shop_right_53.jpg" style="width:100%;"><table width="145" border="0" align="center" cellpadding="0" cellspacing="8">
      <tr>
        <td valign="top"><div align="center" class="style1">
          <p><a href="#item" onclick="load_item(<?php echo $ItemNum; ?>);"><span class="style8"><strong><?php echo htmlspecialchars($ItemName); ?></strong></span></a><br />
          </p>
        </div></td>
      </tr>
<?php
	if ( $itemreborn > 0 )
	{
		// ส่วนนี้ว่างเปล่า - เพิ่มโค้ดตามต้องการ
	}
	
	if ( $ItemPrice > 0 )
	{
		echo '<tr><td align="left"><div align="center" class="style1"><span class="style8">ราคา : ';
		echo number_format($ItemPrice);
		echo ' พ้อย</span></div></td></tr>';
	}
	
	if ( $ItemTimePrice > 0 )
	{
		echo '<tr><td align="left"><div align="center" class="style1"><span class="style8">เวลา : ';
		echo number_format($ItemTimePrice);
		echo ' นาที</span></div></td></tr>';
	}
	
	if ( $ItemBonusPointPrice > 0 )
	{
		echo '<tr><td align="left"><div align="center" class="style1"><span class="style8">แต้มสะสม : ';
		echo number_format($ItemBonusPointPrice);
		echo ' แต้ม</span></div></td></tr>';
	}
	
	if ( $ItemPrice == 0 && $ItemTimePrice == 0 )
	{
		echo '<tr><td align="left"><div align="center" class="style1"><span class="style8">ไอเทมฟรี</span></div></td></tr>';
	}
?>
      <tr>
        <td valign="top"><div style="height:19px;"></div></td>
      </tr>
    </table></td>
  </tr>
  <tr id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableRow5">
    <td id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableCell5" align="center" valign="top" style="width:100%;"><table width="152" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
      <tr>
        <td width="24">&nbsp;</td>
        <td width="105" align="center" bgcolor="#FFFFFF"><a href="#item" onclick="load_item(<?php echo $ItemNum; ?>);">
          <table border="0" cellpadding="0" cellspacing="0">
        	<tr>
            	<td background="../images/button/free.png" width="64" height="47" align="center" valign="middle">
                รายละเอียด
                </td>
            </tr>
        </table>
          </a></td>
        <td width="23"><img src="../images/right/shop_right_56.jpg" width="23" height="25" /><br />
          <img src="../images/right/shop_right_56.jpg" width="23" height="25" /></td>
      </tr>
    </table></td>
  </tr>
  <tr id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableRow6">
    <td id="ctl00_ContentPlaceHolder1_ucItemList1_DataList1_ctl00_TableCell6" align="center" valign="top" style="width:100%;"><img src="../images/right/shop_right_57.jpg" width="152" height="10" /></td>
  </tr>
</table>