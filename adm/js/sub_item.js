$("#submit_sub_item_add").click( function(){
    var text = $("#subname").val();
    var choose_type = $("#choose_type").val();
    
    $.ajax({
        url : "process.php?cmd=sub_item_add",
        type : "post",
        data : "text="+text+"&choose_type="+choose_type+"&submit=1",
        success : function( htmlResponse ) {
            $("#subname").val( "" );
            alert( htmlResponse );
        }
    });
});

$("#submit_sub_item_edit").click( function(){
    var text = $("#subname").val();
    var choose_type = $("#choose_type").val();
    var applytoallitem = $("#applytoallitem").is(':checked');
    if ( applytoallitem == true ) applytoallitem = 1
    else applytoallitem = 0;
    
    var subnum = $("#subnum").val();
    
    $.ajax({
        url : "process.php?cmd=sub_item_edit",
        type : "post",
        data : "text="+text+"&choose_type="+choose_type+"&subnum="+subnum+"&applytoallitem="+applytoallitem+"&submit=1",
        success : function( htmlResponse ) {
            switch( argGet( htmlResponse , 0 ) )
            {
                case "SUCCESS":
                {
                    var htmlRes = "แก้ไขเรียบร้อย<br>";
                    if ( argGet( htmlResponse , 1 ) == 1 )
                    {
                        htmlRes = "แก้ไขการแสดงผลกับทุกไอเทม<br>" + htmlRes;
                    }
                    
                    $("#main_sub_item").html( htmlRes );
                }break;
                
                default : alert(htmlResponse); break;
            }
        }
    });
});

function EditTo( hins , subnum )
{
    $.ajax({
        url : "process.php?cmd=sub_item_edit",
        type : "post",
        data : "subnum="+subnum,
        success : function( htmlResponse ) {
            $("#main_sub_item").html( htmlResponse );
        }
    });
}

function DelTo( hins , subnum )
{
    if ( confirm("ต้องการที่จะลบใช่หรือไม่") )
    {
        $(hins).parent().parent().remove();
    
        $.ajax({
            url : "process.php?cmd=sub_item_list",
            type : "post",
            data : "subnum="+subnum+"&submit=1",
            success : function( htmlResponse ) {
                //alert( htmlResponse );
            }
        });
    }
}