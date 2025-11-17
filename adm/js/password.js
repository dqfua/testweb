$("#change_password").click( function(){
   var current_password = $("#current_password").val();
   var password = $("#password").val();
   var password2 = $("#password2").val();
   $.ajax({ url : "process.php?cmd=password",
       type : "post",
       data : "current_password="+current_password+"&password="+password+"&password2="+password2+"&submit=1",
       success : function( htmlResponse ){
           switch( argGet( htmlResponse , 0 ) )
           {
               case "ERROR":
                   {
                       switch( argGet( htmlResponse , 1 ) )
                       {
                           case "EASY":
                               {
                                   alert("รหัสผ่านง่ายไป,กรุณาเปลี่ยนรหัสใหม่ให้ยากกว่านี้");
                               }break;
                           case "BANLANSE":
                               {
                                   alert("กรุณายืนยันรหัสผ่านให้ถูกต้อง");
                               }break;
                           case "NOTYES":
                               {
                                   alert("รหัสผ่านปัจจุบันไม่ถูกต้อง");
                               }break;
                       }
                   }break;
               case "SUCCESS":
                   {
                       $("#main_password").html(
                               "<p class=\"info_success\">เปลี่ยนรหัสผ่านสำเร็จ!</p><p>รหัสผ่านใหม่ของคุณคือ : <b>" + argGet( htmlResponse , 1 ) + "</b></p>"
                   );
                   }break;
           }
       }
   });
});

$("#change_password_2").click( function(){
   var current_password = $("#current_password").val();
   var password = $("#password").val();
   var password2 = $("#password2").val();
   $.ajax({ url : "process.php?cmd=password2",
       type : "post",
       data : "current_password="+current_password+"&password="+password+"&password2="+password2+"&submit=1",
       success : function( htmlResponse ){
		   //alert( htmlResponse );
           switch( argGet( htmlResponse , 0 ) )
           {
               case "ERROR":
                   {
                       switch( argGet( htmlResponse , 1 ) )
                       {
                           case "EASY":
                               {
                                   alert("รหัสผ่านง่ายไป,กรุณาเปลี่ยนรหัสใหม่ให้ยากกว่านี้");
                               }break;
                           case "BANLANSE":
                               {
                                   alert("กรุณายืนยันรหัสผ่านให้ถูกต้อง");
                               }break;
                           case "NOTYES":
                               {
                                   alert("รหัสผ่านปัจจุบันไม่ถูกต้อง");
                               }break;
                       }
                   }break;
               case "SUCCESS":
                   {
                       $("#main_password").html(
                               "<p class=\"info_success\">เปลี่ยนรหัสผ่านสำเร็จ!</p><p>รหัสผ่านใหม่ของคุณคือ : <b>" + argGet( htmlResponse , 1 ) + "</b></p>"
                   );
                   }break;
           }
       }
   });
});