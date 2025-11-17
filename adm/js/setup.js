var bCheckSuccess = false;

$("#coneitem").click( function(){
	if ( confirm("คุณแน่ใจแล้วใช่หรือไม่ที่จะโคลนไอเทม กรุณาอ่านคำเตือนก่อนกดตกลง") )
	{
		var hins = this;
		var txtbutton = $(hins).text();
		var coneitemcode = $("#coneitemcode").val();
		$(hins).attr("disabled","disabled");
    	$(hins).text("กำลังโหลด...กรุณารอสักครู่");
		$("#coneitemarea").html("");
		setTimeout( function(){
			$.ajax({
				url : "process.php?cmd=setup&type=1103",
				type : "post",
				data : "coneitemcode="+coneitemcode,
				beforeSend : function(){
				},
				success : function( htmlResponse ){
					$(hins).removeAttr("disabled");
					$(hins).text( txtbutton );
					$("#coneitemarea").html(htmlResponse);
				}
			 });
		} , 1000 );
	}
} );

function MenuEnable()
{
    function __Enable( hins , enable )
    {
        if ( enable == false )
            $(hins).attr("disabled","disabled");
        else
            $(hins).removeAttr("disabled");
    }
    __Enable( $("#Dump2RanShop") , bCheckSuccess );
    __Enable( $("#Dump2Xml") , bCheckSuccess );
}

MenuEnable();

$("#Dump2Xml").click( function(){
    var hins = this;
    var txtbutton = $(hins).text();
    
    $(hins).attr("disabled","disabled");
    $(hins).text("กำลังโหลด...กรุณารอสักครู่");
    setTimeout( function(){
        $.ajax({
            url : "process.php?cmd=setup&type=1102",
            type : "post",
            data : null,
            beforeSend : function(){
            },
            success : function( htmlResponse ){
                $(hins).removeAttr("disabled");
                $(hins).text( txtbutton );
                alert( htmlResponse );
            }
         });
    } , 1000 );
});

$("#Dump2RanShop").click( function(){
    var hins = this;
    var txtbutton = $(hins).text();
    
    $(hins).attr("disabled","disabled");
    $(hins).text("กำลังโหลด...กรุณารอสักครู่");
    setTimeout( function(){
        $.ajax({
            url : "process.php?cmd=setup&type=1101",
            type : "post",
            data : null,
            beforeSend : function(){
            },
            success : function( htmlResponse ){
                $(hins).removeAttr("disabled");
                $(hins).text( txtbutton );
                if ( htmlResponse == "SUCCESS" ) alert("SUCCESS!!");
            }
         });
    } , 1000 );
});

$("#b_checkstatus").click( function() {
    var hins = this;
    var txtbutton = $(hins).text();
    
    $(hins).attr("disabled","disabled");
    $(hins).text("กำลังโหลด...กรุณารอสักครู่");
    $.ajax({
        url : "process.php?cmd=setup&type=9979",
        type : "post",
        data : null,
        beforeSend : function(){
        },
        success : function( htmlResponse ){
             $(hins).removeAttr("disabled");
             $(hins).text( txtbutton );
             switch( argGet( htmlResponse , 1 ) )
             {
                 case "0":
                    {
                        bCheckSuccess = false;
                    }break;
                 case "1":
                     {
                         bCheckSuccess = true;
                     }break;
             }
             $("#checktatus").html( argGet( htmlResponse , 0 ) );
             MenuEnable();
        }
     });
    /*
    setTimeout( function(){
        $.ajax({
            url : "process.php?cmd=setup&type=9979",
            type : "post",
            data : null,
            beforeSend : function(){
            },
            success : function( htmlResponse ){
                 $(hins).removeAttr("disabled");
                 $(hins).text( txtbutton );
                 switch( argGet( htmlResponse , 1 ) )
                 {
                     case "0":
                        {
                            bCheckSuccess = false;
                        }break;
                     case "1":
                         {
                             bCheckSuccess = true;
                         }break;
                 }
                 $("#checktatus").html( argGet( htmlResponse , 0 ) );
                 MenuEnable();
            }
         });
    } , 1000 );
    */
} )