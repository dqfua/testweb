var upload_UI = $("#upload_UI").val();
if ( upload_UI == 1 )
{
    var idbutton = "uploader";
    var uploader = document.getElementById(idbutton);
    
    var or_text = $("#"+idbutton).text();
    
    upclick(
      {
       element: uploader,
       action: 'process.php?cmd=skilltable&type=1101', 
       onstart:
         function(filename)
         {
             $("#"+idbutton).attr("disabled","disabled");
             $("#"+idbutton).text("กำลังอัพโหลด กรุณารอสักครู่..");
         },
       oncomplete:
         function(htmlResponse)
         {
             $("#"+idbutton).removeAttr("disabled");
             $("#"+idbutton).text(or_text);
             
             var eleproc = $("#process");
             switch( argGet( htmlResponse , 0 ) )
             {
                 case "SUCCESS":{
                         eleproc.html( "อัพโหลดสำเร็จ..." );
                 }break;
                 default : {
                         eleproc.html("อัพโหลดผิดพลาด ไฟล์อาจมีปัญหากรุณาตรวจสอบไฟล์ของคุณ");
                 }break;
             }
         }
      });
}

$("#uploadSet").click( function() {
    $.ajax( {
        url : "process.php?cmd=skilltable&type=101",
        type : "post",
        data : null,
        success : function( htmlResponse ){
            $("#main_skill").html( htmlResponse );
        }
    });
});

$("#showSkillList").click( function() {
    $.ajax( {
        url : "process.php?cmd=skilltable&type=77",
        type : "post",
        data : null,
        success : function( htmlResponse ){
            $("#main_skill").html( htmlResponse );
        }
    });
});

function showSkillTable( hins )
{
    var oldbuttomtext = $(hins).text();
    $(hins).text("กำลังโหลด");
    $(hins).attr("disabled","disabled");
    
    setTimeout( function(){
        for( var i = showRoll , c = 0 ; i < showDataInfo.length && c < 20 ; i++ , c++ )
        {
            var htmlData = "<tr>";
            htmlData += "<td>"+showDataInfo[i][0]+"</td>";
            htmlData += "<td>"+showDataInfo[i][1]+"</td>";
            htmlData += "</tr>";
            $("#showInfoTable\ > tfoot:last").after(htmlData);
            showRoll ++;
        }
        if ( showRoll < showDataInfo.length )
        {
            $(hins).removeAttr("disabled");
        }
        $(hins).text(oldbuttomtext);
    } , 1000);
}