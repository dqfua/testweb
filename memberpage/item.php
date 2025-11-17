<?php
$bLogin = false;
$cUser = NULL;
$bUserLoginCheck = false;
if ( COnline::OnlineGoodCheck( $cUser ) == ONLINE )
{
	$cUser->Login( $cUser->GetUserID() , $cUser->GetUserPass() );
	CGlobal::SetSesUser( serialize($cUser) );
	$cUser = unserialize( CGlobal::GetSesUser() );
	$UserID = $cUser->GetUserID();
	$UserNum = $cUser->GetUserNum();
	$UserPoint = $cUser->GetUserPoint();
	$GameTime = $cUser->GetGameTime();
	$BonusPoint = $cUser->GetBonusPoint();
	$bLogin = true;
	if ( $UserNum > 0 && $cUser->GetUserType() >= 30 )
	{
		$bUserLoginCheck = true;
	}
}
if ( $bLogin && !CSessionNeo::checksessionout( $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"] , $cUser->GetUserNum() , CGlobal::GetSesLoginOut() , $cUser->LogIP ) )
{
	exit;
}

CSec::Begin();

CInput::GetInstance()->BuildFrom( IN_GET );

$n = CInput::GetInstance()->GetValueInt( "n" , IN_GET );
$cNeoSQLConnectODBC = new CNeoSQLConnectODBC;
$cNeoSQLConnectODBC->ConnectRanWeb();

// แก้ไข: เพิ่ม MemNum ใน SELECT
if ( $bUserLoginCheck )
	$szTemp = sprintf("SELECT TOP 1 MemNum,ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemTimePrice,ItemBonusPointPrice,ItemSell,ItemType,ItemSock,Item_MaxReborn FROM ItemProject WHERE MemNum = %d AND ( ItemShow = 0 OR ItemShow = 2 ) AND ItemDelete = 0 AND ItemNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$n);
else
	$szTemp = sprintf("SELECT TOP 1 MemNum,ItemNum,SubNum,ItemMain,ItemSub,ItemName,ItemComment,ItemImage,ItemPrice,ItemTimePrice,ItemBonusPointPrice,ItemSell,ItemType,ItemSock,Item_MaxReborn FROM ItemProject WHERE MemNum = %d AND ItemShow = 0 AND ItemDelete = 0 AND ItemNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$n);

$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
while( $cNeoSQLConnectODBC->FetchRow() )
{
	$MemNum = $cNeoSQLConnectODBC->Result("MemNum",ODBC_RETYPE_INT);
	$SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
	$ItemNum = $cNeoSQLConnectODBC->Result("ItemNum",ODBC_RETYPE_INT);
	$ItemMain = $cNeoSQLConnectODBC->Result("ItemMain",ODBC_RETYPE_INT);
	$ItemSub = $cNeoSQLConnectODBC->Result("ItemSub",ODBC_RETYPE_INT);
	$ItemName = $cNeoSQLConnectODBC->Result("ItemName",ODBC_RETYPE_THAI);
	$ItemComment = $cNeoSQLConnectODBC->Result("ItemComment",ODBC_RETYPE_THAI);
	$ItemImage = $cNeoSQLConnectODBC->Result("ItemImage",ODBC_RETYPE_THAI);
	$ItemPrice = $cNeoSQLConnectODBC->Result("ItemPrice",ODBC_RETYPE_INT);
	$ItemTimePrice = $cNeoSQLConnectODBC->Result("ItemTimePrice",ODBC_RETYPE_INT);
	$ItemBonusPointPrice = $cNeoSQLConnectODBC->Result("ItemBonusPointPrice",ODBC_RETYPE_INT);
	$ItemSell = $cNeoSQLConnectODBC->Result("ItemSell",ODBC_RETYPE_INT);
	$ItemType = $cNeoSQLConnectODBC->Result("ItemType",ODBC_RETYPE_INT);
	$ItemSock = $cNeoSQLConnectODBC->Result("ItemSock",ODBC_RETYPE_INT);
	$Item_MaxReborn = $cNeoSQLConnectODBC->Result("Item_MaxReborn",ODBC_RETYPE_INT);

	$strReplace = str_replace( "/" , "" , PATH_UPLOAD_ITEMIMAGE );
	$ItemImage = str_replace( $strReplace , "" , $ItemImage );
		
	if ( $ItemType == 0 ) $Item_MaxReborn = 0;
	$szTemp = sprintf("SELECT SubNum,SubName FROM SubProject WHERE MemNum = %d AND SubNum = %d",$_CONFIG["SERVERMAN"]["SERVER_MEMNUM"],$SubNum);
	$cNeoSQLConnectODBC->QueryRanWeb($szTemp);
	while( $cNeoSQLConnectODBC->FetchRow() )
	{
		$SubNum = $cNeoSQLConnectODBC->Result("SubNum",ODBC_RETYPE_INT);
		$SubName = $cNeoSQLConnectODBC->Result("SubName",ODBC_RETYPE_THAI);
	}
}
?>
<table width="647" border="0" align="center" cellpadding="0" cellspacing="0" background="../images/item/shop_item_11.jpg">
  <tr>
    <td><img src="../images/item/shop_item_09.jpg" width="647" height="20" /></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td valign="top"><table width="647" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="32">&nbsp;</td>
        <td width="580" valign="top"><table width="580" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table width="580" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="173" valign="top"><table width="173" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><img src="../images/item/shop_item_14.jpg" width="173" height="9" /></td>
                  </tr>
                  <tr>
                    <td><table width="173" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="9"><img src="../images/item/shop_item_18.jpg" width="9" height="155" /></td>
                        <td width="155"><img src="<?php echo PATH_UPLOAD_ITEMIMAGE . $ItemImage; ?>" width="155" height="155" /></td>
                        <td width="9"><img src="../images/item/shop_item_20.jpg" width="9" height="155" /></td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td><img src="../images/item/shop_item_25.jpg" width="173" height="9" /></td>
                  </tr>
                </table></td>
                <td width="15">&nbsp;</td>
                <td width="392" valign="top"><table width="392" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><img src="../images/item/shop_item_16.jpg" width="392" height="9" /></td>
                  </tr>
                  <tr>
                    <td height="155" valign="top" background="../images/item/shop_item_21.jpg"><table width="370" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td width="18">&nbsp;</td>
                        <td width="374" class="style18"><b><?php printf( "<a href='#see' onclick=\"loadpage('seeitem','area','n=%d');\"><font color=black>%s" , $SubNum , $SubName ); ?> -> <?php echo $ItemName."</font></a>"; ?></b></td>
                      </tr>
                      <tr>
                        <td colspan="2"><img src="../images/item/shop_item_23.jpg" width="392" height="3" /></td>
                      </tr>
                      <tr>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="style21"><font color="black"><strong>ชื่อไอเทม</strong></font></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="style22"><font color="black"><?php echo $ItemName; ?></font></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="style21"><font color="black"><strong>ชนิดไอเทม</strong></font></span></td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="style22"><font color="black"><?php echo $_CONFIG["ITEMTYPE"][$ItemType]; ?><br />
                          <font color=red>
                          ถ้าแสดงว่า B แสดงว่าซื้อแล้วจะเข้าอยู่ในช่อง B<br />
                          แต่ถ้าแสดงว่า I มันจะอยู่ในกระเป๋าของตัวละครของคุณ
                          </font>
                        </font></span></td>
                      </tr>
                      <?php
					  if ( $Item_MaxReborn > 0 )
					  {
					  ?>
                      <tr>
                        <td width="18">&nbsp;</td>
                        <td><span class="style21"><font color=black><strong>จำนวนจุติ</strong></font></span></td>
                      </tr>
                      <tr>
                        <td width="18">&nbsp;</td>
                        <td><font color="black"><?php echo $Item_MaxReborn; ?></font></td>
                      </tr>
                      <?php
					  }
					  if (  $ItemPrice > 0 )
					  {
						  echo '
						  <tr>
							<td width="18">&nbsp;</td>
							<td><span class="style21"><font color=black><strong>ราคา</strong></font></span></td>
						  </tr>
						  <tr>
							<td width="18">&nbsp;</td>
							<td><font color="black">';
							echo $ItemPrice;
							echo ' พ้อย</font></td>
						  </tr>
						  ';
					  }
					  if (  $ItemTimePrice > 0 )
					  {
						  echo '
						  <tr>
							<td width="18">&nbsp;</td>
							<td><span class="style21"><font color=black><strong>ราคา(เวลาออนไลน์)</strong></font></span></td>
						  </tr>
						  <tr>
							<td width="18">&nbsp;</td>
							<td><font color="black">';
							echo $ItemTimePrice;
							echo ' นาที</font></td>
						  </tr>
						  ';
					  }
					  if (  $ItemBonusPointPrice > 0 )
					  {
						  echo '
						  <tr>
							<td width="18">&nbsp;</td>
							<td><span class="style21"><font color=black><strong>ราคา(แต้มสะสม)</strong></font></span></td>
						  </tr>
						  <tr>
							<td width="18">&nbsp;</td>
							<td><font color="black">';
							echo $ItemBonusPointPrice;
							echo ' แต้ม</font></td>
						  </tr>
						  ';
					  }
					  if (  $ItemPrice == 0 && $ItemTimePrice == 0 && $ItemBonusPointPrice == 0 )
					  {
						  echo '
						  <tr>
							<td width="18">&nbsp;</td>
							<td><span class="style21"><font color=black>ไอเทมฟรี</font></span></td>
						  </tr>
						  ';
					  }
					  ?>
                    </table></td>
                  </tr>
                  <tr>
                    <td><img src="../images/item/shop_item_26.jpg" width="392" height="9" /></td>
                  </tr>
                  <tr>
                    <td><table width="392" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="right">
<?php
if ( $bLogin == false )
{
	echo '<a href="#buy" onclick="alert(\'กรุณาเข้าสู่ระบบก่อนใช้งานระบบนี้!!\');loadpage(\'login\',\'area\',null);">';
}else{
	if ( $UserPoint < $ItemPrice && $ItemPrice > 0 )
	{
		echo '<a href="#buy" onclick="alert(\'พ้อยของคุณไม่เพียงพอ!!\');">';
	}else if ( $BonusPoint < $ItemBonusPointPrice && $ItemBonusPointPrice > 0 )
	{
		echo '<a href="#buy" onclick="alert(\'แต้มสะสมของคุณไม่เพียงพอ!!\');">';
	}else{
		if ( $ItemSock <= 0 )
		{ 
			echo '<a href="#buy" onclick="alert(\'เสียใจด้วยไอเทมชิ้นนี้หมดสะแล้ว!!\');">';
		}else{
			if ( $ItemType == 1 )
			{
				$pCha = NULL;
				if ( CChaOnline::OnlineGoodCheck( $pCha ) == ONLINE )
				{
					$ChaNum = $pCha->GetChaNum();
					if ( $ChaNum > 0 )
					{
						echo '<a href="#buy" onclick="buy( ' .$ItemNum.' , 0 );">';
					}else{
						echo '<a href="#buy" onclick="alert(\'กรุณาเลือกตัวละครก่อนทำรายการ!!\');loadpage(\'login\',\'area\',null);">';
					}
				}else{
					echo '<a href="#buy" onclick="alert(\'กรุณาเลือกตัวละครก่อนทำรายการ!!\');loadpage(\'login\',\'area\',null);">';
				}
			}else{
				echo '<a href="#buy" onclick="buy( ' .$ItemNum.' , 0 );">';
			}
		}
	}
}
?>
                        <img src="../images/item/shop_item_30.jpg" name="Image86" width="110" height="57" border="0" id="Image86" <?php if ( $ItemTimePrice > 0 && $ItemPrice == 0 ) echo 'style = "display:none;"'; ?> /></a>
                        <?php
						if (  $ItemTimePrice > 0 )
						{
							if ( $bLogin == false )
							{
								echo '<a href="#buy" onclick="alert(\'กรุณาเข้าสู่ระบบก่อนใช้งานระบบนี้!!\');loadpage(\'login\',\'area\',null);">';
							}else if ( $GameTime < $ItemTimePrice && $ItemTimePrice > 0 )
							{
								echo '<a href="#buy" onclick="alert(\'เวลาออนไลน์ของคุณไม่เพียงพอ!!\');">';
							}else if ( $BonusPoint < $ItemBonusPointPrice && $ItemBonusPointPrice > 0 )
							{
								echo '<a href="#buy" onclick="alert(\'แต้มสะสมของคุณไม่เพียงพอ!!\');">';
							}else{
								if ( $ItemSock <= 0 )
								{ 
									echo '<a href="#buy" onclick="alert(\'เสียใจด้วยไอเทมชิ้นนี้หมดสะแล้ว!!\');">';
								}else{
								if ( $ItemType == 1 )
								{
								$pCha = NULL;
								if ( CChaOnline::OnlineGoodCheck( $pCha ) == ONLINE )
								{
									$ChaNum = $pCha->GetChaNum();
									if ( $ChaNum > 0 )
									{
										echo '<a href="#buy" onclick="buy( ' .$ItemNum.' , 1 );">';
									}else{
										echo '<a href="#buy" onclick="alert(\'กรุณาเลือกตัวละครก่อนทำรายการ!!\');loadpage(\'login\',\'area\',null);">';
									}
								}else{
									echo '<a href="#buy" onclick="alert(\'กรุณาเลือกตัวละครก่อนทำรายการ!!\');loadpage(\'login\',\'area\',null);">';
								}
								}else{
									echo '<a href="#buy" onclick="buy( ' .$ItemNum.' , 1 );">';
								}
								}
							}
							echo '<img src="../images/item/shopbuybytime.jpg" name="Image86" width="110" height="57" border="0" id="Image86" /></a>';
						}
						?>
                        </td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><img src="../images/item/shop_item_31.jpg" width="580" height="7" /></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>
            <font color="black"><strong>รายละเอียดไอเทม</strong> <strong>:</strong></font> <font color="black"><?php echo CGlobal::BBCode( @CNeoInject::ReplaceBBCode2Def( $ItemComment ) ); ?></font>
            </td>
          </tr>
          <tr>
            <td>&nbsp;

            </td>
          </tr>
          </table></td>
        <td width="35">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><img src="../images/item/shop_item_47.jpg" width="647" height="9" /></td>
  </tr>
</table>
<?php
$cNeoSQLConnectODBC->CloseRanWeb();
/*
<div id="area_facebook_comment" align='left' style='background-color:#FFF;width=647px;'>
<iframe src="../facebook/fb_comment_box.php?memnum=<?php echo $_CONFIG["SERVERMAN"]["SERVER_MEMNUM"]; ?>&itemprojectnum=<?php echo $ItemNum; ?>" scrolling="yes" frameborder="0" style="border:none; overflow:hidden; width:647px; height:600px;" allowTransparency="true"></iframe>
</div>
 */
?>