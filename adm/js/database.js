$("#submit_database").click( function(){
    var dbname = [ "RANGAME" , "RANSHOP" , "RANUSER" ];
    var keyname = [ "_IP" , "_USER" , "_PASSWORD" , "_DATABASE" ];
    var senddata = "";
    function UpdateData( key , value , added )
    {
        if ( added )
            senddata += "&" + key + "=" + value;
        else
            senddata += key + "=" + value;
    }
    for( var i = 0 ; i < dbname.length ; i ++ )
    {
        for( var n = 0 ; n < keyname.length ; n ++ )
        {
            var keydata = dbname[i] + keyname[n];
            var keyvalue = $( "#"+keydata ).val();
            if ( i == 0 && n == 0 )
                UpdateData( keydata , keyvalue , false );
            else
                UpdateData( keydata , keyvalue , true );
        }
    }
    $.ajax({
        url : "process.php?cmd=database",
        type : "post",
        data : senddata+"&submit=1",
        success : function( htmlResponse ){
            switch( argGet( htmlResponse , 0 ) )
            {
                case "SUCCESS":
                    {
                        $("#main_database").html( "ปรับปรุงสำเร็จ" );
                    }break;
                default :
                    {
                        alert( "FAILED!!" );
                        //alert( htmlResponse );
                    }break;
            }
        }
    });
});
