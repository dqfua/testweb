<?php
// test_image_path.php

// !!! สำคัญ: ให้แก้ไขเส้นทางนี้ให้ตรงกับที่คุณพบใน captcha.class.php !!!
 $imagePath = '../images/captcha_bg.png'; 

echo "<h2>Testing Image Path Access</h2>";
echo "Attempting to check path: <b>" . $imagePath . "</b><br><br>";

// 1. ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
if (file_exists($imagePath)) {
    echo "✅ SUCCESS: File exists.<br>";

    // 2. ตรวจสอบว่า PHP สามารถอ่านไฟล์ได้หรือไม่
    if (is_readable($imagePath)) {
        echo "✅ SUCCESS: File is readable by PHP.<br>";
        echo "👉 หมายความว่าปัญหาน่าจะไม่ใช่เรื่องสิทธิ์หรือ open_basedir<br>";
        echo "👉 แต่อาจจะเป็นปัญหาภายในโค้ด captcha.class.php เอง";
    } else {
        echo "❌ ERROR: File exists but is NOT readable by PHP.<br>";
        echo "👉 นี่คือปัญหา <b>สิทธิ์การเข้าถึงไฟล์ (File Permission)</b><br>";
        echo "👉 คุณต้องไปตั้งค่าให้ User ของ Web Server (เช่น IIS_IUSRS) มีสิทธิ์อ่าน (Read) ไฟล์หรือโฟลเดอร์นี้";
    }

} else {
    echo "❌ ERROR: File does not exist at the specified path.<br>";
    echo "👉 สาเหตุที่เป็นไปได้:<br>";
    echo "   - เส้นทางใน captcha.class.php ผิด<br>";
    echo "   - ไฟล์ภาพหายไปจริงๆ<br>";
    echo "   - เส้นทางถูกบล็อกโดย <b>open_basedir</b> ใน php.ini<br>";
}

echo "<br><hr><br>";
echo "<b>Current PHP working directory:</b> " . getcwd() . "<br>";
echo "<b>Script location:</b> " . __FILE__ . "<br>";

?>