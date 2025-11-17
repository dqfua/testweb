function submitTrue()
{
    var senddata = "";
    function UpdateData( key , value , added )
    {
        if ( added )
            senddata += key + "=" + value;
        else
            senddata += "&" + key + "=" + value;
    }
    var argTrueMoney = [ "t0" , "t1" , "t2" , "t3" , "t4" , "t5" , "r0" , "r1" , "r2" , "r3" , "r4" , "r5" , "b0" , "b1" , "b2" , "b3" , "b4" , "b5" , "id" ]; 
    for( var i = 0 ; i < argTrueMoney.length ; i ++ )
    {
        var value = $("#"+argTrueMoney[i]).val();
        if ( i == 0 )
            UpdateData( argTrueMoney[i] , value , true );
        else
            UpdateData( argTrueMoney[i] , value , false );
    }
    
    var htmlCode = $("#main_truemoney").html();
    
    loading_div = "main_truemoney";
    nowloading = true;
    renderLoading();
    
    $.ajax({
       url : "process.php?cmd=truemoney",
       type : "post",
       data : senddata+"&submit=1",
       success : function( htmlResponse ) {
           nowloading = false;
           $("#main_truemoney").html(htmlCode);
           switch( argGet( htmlResponse , 0 ) )
           {
               case "SUCCESS":
                   {
                       $("#card").html( "แก้ไขสำเร็จ" );
                   }break;
           }
       }
    });
};

function switchDiv( div_hide , div_show )
{
    $( "#"+div_hide ).slideUp();
    //$( "#"+div_show ).css("display","block");
    if ( $( "#"+div_show ).is(":hidden") )
    {
        $( "#"+div_show ).slideDown()();
    }
}

/*
function buttonSwitch()
{
    var bt_arr = [ "bt0","bt1","bt2","bt3","bt4","bt5" ];
    for( var i = 0 ; i < bt_arr.length ; i++ )
    {
        $("#"+bt_arr[i]).click( function() {
            alert( bt_arr[i] );
            alert( i );
            $.ajax( {
                url : "process.php?cmd=truemoney_feedback",
                type : "post",
                data : "i="+i,
                success: function( htmlResponse ){
                    $("#refill_feedback").html( htmlResponse );
                }
            } );
            switchDiv( "card" , "refill_feedback" );
        });
    }
}
*/
//buttonSwitch();

function buttonSwitch( id , value )
{
    $("#"+id).click( function() {
        $.ajax( {
           url : "process.php?cmd=truemoney_feedback",
           type : "post",
           data : "i="+value,
           success : function ( htmlResponse ) {
               $("#refill_feedback").html( htmlResponse );
               switchDiv( "card" , "refill_feedback" );
           }
        });
    });
}

buttonSwitch( "bt0" , 0 );
buttonSwitch( "bt1" , 1 );
buttonSwitch( "bt2" , 2 );
buttonSwitch( "bt3" , 3 );
buttonSwitch( "bt4" , 4 );
buttonSwitch( "bt5" , 5 );


$("#cancel_feedback").click( function() {
    switchDiv( "refill_feedback" , "card" );
});

$("#submit_feedback").click( function( ) {
    var slot_type = $("#slot_type").val();
    var senddata = "";
    
    function UpdateData( key , value )
    {
        senddata += "&"+key+"="+value;
    }
    
    var itemcount = $("#itemcount").val();
    for( var i = 1 ; i <= itemcount ; i++)
    {
        var key_m = $("#m"+i).val();
        var key_s = $("#s"+i).val();
        UpdateData( "m"+i , key_m );
        UpdateData( "s"+i , key_s );
    }
    
    //alert( senddata );
    
    $.ajax({
        url : "process.php?cmd=truemoney_feedback",
        type : "post",
        data : "i="+slot_type+senddata+"&itemcount="+itemcount+"&submit=1",
        success : function ( htmlResponse ) {
            //alert( htmlResponse );
            
            switchDiv( "refill_feedback" , "card" );
            $("#refill_feedback").html( "" );
        }
    });
});

$("#added").click( function() {
    var itemcount = $("#itemcount").val();
    if ( itemcount < showitemmax ) itemcount++; else return ;
    $("#NowItemEn").text( itemcount );
    $("#itemcount").val( itemcount );
    addItemTable( itemcount , 65535 , 65535 );
    return ;
});

$("#deled").click( function(){
    var itemcount = $("#itemcount").val();
    if ( itemcount > 0 ) itemcount--; else return ;
    $("#NowItemEn").text( itemcount );
    $("#itemcount").val( itemcount );
    
    $("#itemlistmain tr:last").remove();
});

function addItemTable( i , itemmain , itemsub )
{
    $("#itemlistmain").last().append( "<tr><td><b>"+i+".</b> <input type=\"text\" id=\"m"+i+"\" value=\""+itemmain+"\" class=\"edittext\" /></td><td><input type=\"text\" id=\"s"+i+"\" value=\""+itemsub+"\" class=\"edittext\" /></td></tr>" );
}

function updateItemShow( itemshow , itemmax )
{
    //
    alert( itemmax );
    alert( itemshow );
}
