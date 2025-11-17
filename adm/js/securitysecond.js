function dosec()
{
    var linkBak = $("#linkBak").val();
    var password = $("#password").val();
    var htmlCode = $("#main_security").html();
    //$("#main_security").html( "<b>กรุณารอสักครู่....</b>" );
    loading_div = "main_security";
    nowloading = true;
    renderLoading();
    
    $.ajax({
        url : "process.php?cmd=password_security",
        type : "post",
        data : "submit=1&password="+password,
        success : function( htmlResponse ){
            nowloading = false;
            $("#main_security").html(htmlCode);
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":
                {
                    ChooseMenu( linkBak );
                }break;
                
                default:
                {
                    $("#password").val("");
                    alert( "รหัสผ่านไม่ถูกต้อง" );
                }
            }
        }
    });
};