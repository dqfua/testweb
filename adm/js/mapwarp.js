$("#status").change( function(){
    var mapopen = $(this).val();
    $.ajax({
        url : "process.php?cmd=mapwarp&type=1001",
        type : "post",
        data : "mapopen="+mapopen,
        success : function( ){
        }
    });
});

function delList ( hins ){
    if ( confirm( "คุณต้องการจะลบจริงๆใช่หรือไม่" ) )
    {
        var nativeid = $(hins).val();
        var idmain = argGet( nativeid , 0 );
        var idsub = argGet( nativeid , 1 );

        $.ajax( {
            url : "process.php?cmd=mapwarp&type=1201",
            type : "post",
            data : "idmain="+idmain+"&idsub="+idsub,
            success : function( htmlResponse ){
                //alert( htmlResponse );
            }
        } );

        $(hins).parent().parent().remove();
    }
};

var main_process = $("#process");

function editList( hins ){
    var nativeid = $(hins).val();
    var idmain = argGet( nativeid , 0 );
    var idsub = argGet( nativeid , 1 );

    $.ajax( {
        url : "process.php?cmd=mapwarp&type=1202",
        type : "post",
        data : "idmain="+idmain+"&idsub="+idsub,
        success : function( htmlResponse ){
            main_process.html( htmlResponse );
        }
    } );
}

$("#addMap").click( function(){
    var mapname = $("#mapname").val();
    var mapmain = $("#mapmain").val();
    var mapsub = $("#mapsub").val();
    var mappoint = $("#mappoint").val();
    $.ajax({
        url : "process.php?cmd=mapwarp&type=1101",
        type : "post",
        data : "mapname="+mapname+"&mapmain="+mapmain+"&mapsub="+mapsub+"&mappoint="+mappoint,
        beforeSend : function( ){
            main_process.slideUp( "fast" );
        },
        success : function( htmlResponse ){
            switch( argGet( htmlResponse , 0 ) )
            {
                case "SUCCESS":
                    {
                        main_process.html( "เพิ่มเรียบร้อย" );
                    }break;
                case "ERROR":
                    {
                        switch( argGet( htmlResponse , 1 ) )
                        {
                            case "MAPNAME":{
                                    main_process.html( "ชื่อแผนที่มีปัญหาไม่สามารถเพิ่มได้ กรุณาตรวจสอบให้ดีอีกครั้ง" );
                            }
                        }
                    }break;
                default:
                    {
                        main_process.html( "ไม่สามารถเพิ่มได้เนื่องจากมีปัญหาบางอย่าง" );
                    }break;
            }
            main_process.slideDown( "fast" );
        }
    });
});

$("#editMap").click( function(){
    var mapid = $("#mapid").val();
    var mapname = $("#mapname").val();
    var mapmain = $("#mapmain").val();
    var mapsub = $("#mapsub").val();
    var mappoint = $("#mappoint").val();
    $.ajax({
        url : "process.php?cmd=mapwarp&type=1203",
        type : "post",
        data : "mapname="+mapname+"&mapmain="+mapmain+"&mapsub="+mapsub+"&mappoint="+mappoint+"&mapid="+mapid,
        beforeSend : function( ){
            main_process.slideUp( "fast" );
        },
        success : function( htmlResponse ){
            switch( argGet( htmlResponse , 0 ) )
            {
                case "SUCCESS":
                    {
                        main_process.html( "แก้ไขเรียบร้อย" );
                    }break;
                case "ERROR":
                    {
                        switch( argGet( htmlResponse , 1 ) )
                        {
                            case "MAPNAME":{
                                    main_process.html( "ชื่อแผนที่มีปัญหาไม่สามารถแก้ไขได้ กรุณาตรวจสอบให้ดีอีกครั้ง" );
                            }
                        }
                    }break;
                default:
                    {
                        main_process.html( "ไม่สามารถแก้ไขได้เนื่องจากมีปัญหาบางอย่าง" );
                    }break;
            }
            main_process.slideDown( "fast" );
        }
    });
});

$("#txtAdd").click( function(){
    $.ajax({
        url : "process.php?cmd=mapwarp&type=1100",
        type : "post",
        data : null,
        success : function( htmlResponse ){
            main_process.html( htmlResponse );
        }
    });
});

$("#txtEdit").click( function(){
    $.ajax({
        url : "process.php?cmd=mapwarp&type=1200",
        type : "post",
        data : null,
        success : function( htmlResponse ){
            main_process.html( htmlResponse );
        }
    });
});