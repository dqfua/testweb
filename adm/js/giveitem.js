function showFade( eleid )
{
    var speed = "fast";
    if ( !eleid.is(":hidden") ) return false;
    eleid.slideDown( speed );
    setTimeout( function(){
        eleid.slideUp( speed );
    },1000);
}

function tr_switch( id , visible )
{
    if ( visible == 0 )
        $("#"+id).hide();
    else
        $("#"+id).show();
}

function pageType( hins , type )
{
    $("#giveitem_proc").css("display","block");
    $("#giveitem_table_proc").css("display","block");
    $("#modetxtShow").text( $(hins).text() );
    
    $("#pageType").val( type );
    
    $("#giveitem_proc_report").hide();
    
    var eleidMainParent = [ "tableid" , "tabledate" ];
    
    switch( type )
    {
        case 0:
        {
            tr_switch( eleidMainParent[0] , 0 );
            tr_switch( eleidMainParent[1] , 0 );
        }break;
        case 1:
        {
            tr_switch( eleidMainParent[0] , 1 );
            tr_switch( eleidMainParent[1] , 0 );
        }break;
        case 2:
        {
            tr_switch( eleidMainParent[0] , 0 );
            tr_switch( eleidMainParent[1] , 1 );
        }break;
    }
}

$("#doProc").click( function(){
    if ( !confirm( "การแจกไอเทมนั้นเมื่อแจกไปแล้วจะไม่สามารถกู้คืนได้ กรุณาตรวจสอบรายละเอียดให้แน่ชัดก่อนแจกทุกครั้ง" ) ) return ;
    
    var speed = "fast";
    var pagetype = $("#pageType").val( );
    
    var senddata = "";
    senddata += "&userid=" + $("#userid").val( );
    senddata += "&ddd=" + $("#ddd").val( );
    senddata += "&mmm=" + $("#mmm").val( );
    senddata += "&yyy=" + $("#yyy").val( );
    senddata += "&itemlist=" + $("#itemlist").val( );
    
    $.ajax( {
        url : "process.php?cmd=giveitem",
        type : "post",
        data : "submit=1&pagetype="+pagetype+senddata,
        beforeSend : function(){
            $("#giveitem_table_proc").slideUp(speed);
            $("#giveitem_proc_report").slideDown(speed);
            nowloading = true;
            loading_div = "giveitem_proc_report";
            renderLoading( );
        },
        success : function( htmlResponse ) {
            nowloading = false;
            $("#giveitem_proc_report").slideUp(speed);
            setTimeout( function(){
                var htmlRes = argToArray( htmlResponse );
				//alert(htmlResponse);
                switch( htmlRes[0] )
                {
                    case "SUCCESS":
                    {
                        var htmlReport = "<span class=\"info\">";
                        switch( htmlRes[1] )
                        {
                            case "0":
                            {
                                //all
                                htmlReport += "ไอเทมถูกส่งไปยังไอดีทั้งหมด <b>"+htmlRes[2]+"</b><br><p>ไอดีที่ส่งไปมีดังนี้ <b>"+htmlRes[3]+"</b> เรียบร้อยแล้ว!!</p>";
                            }break;
                            case "1":
                            {
                                //idonly
                               	htmlReport += "ไอเทมถูกส่งไปยังไอดี <b>"+htmlRes[2]+"</b> เรียบร้อยแล้ว!!";
                            }break;
                            case "2":
                            {
                                //dateonly
                                htmlReport += "ไอเทมถูกส่งไปยังไอดีทั้งหมด <b>"+htmlRes[2]+"</b><br><p>ไอดีที่ส่งไปมีดังนี้ <b>"+htmlRes[3]+"</b> เรียบร้อยแล้ว!!</p>";
                            }break;
                        }
                        htmlReport += "</span>";
                        $("#giveitem_proc_report").html( htmlReport );
                    }break;
                    case "ERROR":
                    {
                        var htmlReport = "<span class=\"info_err\">";
                        switch( htmlRes[1] )
                        {
                            case "USERID":
                            {
                                switch( htmlRes[2] )
                                {
                                    case "NOTFOUND":
                                    {
                                        htmlReport += "ไม่พบไอดี <b>"+htmlRes[3]+"</b> ในระบบ";
                                    }break;
                                    default:
                                    {
                                        htmlReport += "ไอดีไม่ถูกต้องกรุณาตรวจสอบอีกครั้ง";
                                    }break;
                                }
                            }break;
                            default :
                            {
                                htmlReport += "มีปัญหาบางอย่างไม่สามารถส่งไอเทมได้";
                            }break;
                        }
                        htmlReport += "</span>";
                        $("#giveitem_proc_report").html( htmlReport );
                    }break;
                }
                $("#giveitem_proc_report").slideDown()(speed);
            } , 1000);
        }
    })
});