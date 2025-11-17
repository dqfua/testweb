<?php
if ( !defined("PROCESS_HEADER") ) die("ERROR|PROCESS_HEADER");


function __buildeasymenu_t( $text , $script )
{
    printf( '<tr><td><button onclick="%s" style="width:199px;">%s</button></td></tr>' , $script , $text );
}
echo '<table>';
__buildeasymenu_t( "หน้าหลัก" , "Load_MainProc();" );
__buildeasymenu_t( "Dashboards" , "ChooseMenu( 'dashboards' );" );
__buildeasymenu_t( "เปลี่ยนรหัสผ่าน" , "ChooseMenu( 'password' );" );
__buildeasymenu_t( "เปลี่ยนรหัสผ่านขั้นที่ 2" , "ChooseMenu( 'password2' );" );
__buildeasymenu_t( "ตั้งค่าดาต้าเบส" , "ChooseMenu( 'database' );" );
__buildeasymenu_t( "รวมเซิร์ฟเวอร์" , "ChooseMenu( 'export' );" );
__buildeasymenu_t( "ติดตั้ง" , "ChooseMenu( 'setup' );" );
__buildeasymenu_t( "ตั้งค่าทั่วไป" , "ChooseMenu( 'set' );" );
__buildeasymenu_t( "ตั้วค่าเติมเงิน" , "ChooseMenu( 'truemoney' );" );
__buildeasymenu_t( "เพิ่มหมวดหมู่ไอเทม" , "ChooseMenu( 'sub_item_add' );" );
__buildeasymenu_t( "แก้ไขหมวดหมู่ไอเทม" , "ChooseMenu( 'sub_item_list' );" );
__buildeasymenu_t( "เพิ่มไอเทม" , "ChooseMenu( 'itemproject_add' );" );
__buildeasymenu_t( "แก้ไขไอเทม" , "ChooseMenu( 'itemproject_list' );" );
__buildeasymenu_t( "ตั้งค่า SkillTable" , "ChooseMenu( 'skilltable' );" );
__buildeasymenu_t( "ตั้งค่า MapWarp" , "ChooseMenu( 'mapwarp' );" );
//__buildeasymenu_t( "ผู้เล่นออนไลน์" , "ChooseMenu( 'playeronline' );" );
__buildeasymenu_t( "ส่วนของผู้เล่น" , "ChooseMenu( 'player' );" );
__buildeasymenu_t( "แจกไอเทม" , "ChooseMenu( 'giveitem' );" );
__buildeasymenu_t( "ตรวจสอบ" , "ChooseMenu( 'report' );" );
__buildeasymenu_t( "ล็อกเอาร์" , "logOut();" );
echo '</table>';
?>