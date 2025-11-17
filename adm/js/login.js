$("#login").click( function(){
    var id = $("#id").val();
    var password = $("#password").val();
    var sescode = $("#sescode").val();
    $.ajax({
        url: "process.php",
        type: "post",
        data : "id="+id+"&password="+password+"&sescode="+sescode+"&submit=1",
        success: function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[1] )
            {
                case "1":{
                    $.ajax({
                        url: "process.php",
                        success: function( html ) {
                            $("#main").html( html );
                        }});
                }break;
                default : {
                    //alert( htmlResponse );
                    //$("#sescode").val(argData[0]);
                    //$("#password").val("");
                    switch( argData[2] )
                    {
                        case "D":
                        {
                            alert( "กรุณาลองใหม่อีกครั้งใน " + argData[3] + " วินาที" );
                        }break;
                        
                        default:
                        {
                            alert( "ไม่สามารถล็อกอินได้" );
                        }break;
                    }
                    
                    $("#captchasingle").attr("src","displaycaptcha.php");
                    
                    $.ajax({
                        url : "process.php",
                        data : null,
                        success : function( h ){
                            $("#main").html( h );
                        }
                    });
                }break;
            }
        }
    });
});
