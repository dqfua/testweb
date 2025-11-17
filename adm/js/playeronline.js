function playeronline2chanum(chanum)
{
    $.ajax( { 
        url : "process.php?cmd=player&type=7000",
        type : "post",
        data : "chanum="+chanum,
        timeout : 10000,
        failed : function(){
        },
        success : function(h){
            var argData = argToArray( h );
            switch( argData[0] )
            {
                case "SUCCESS":
                {
                    $.ajax( {
                        url : "process.php?cmd=player&type=5000",
                        type : "post",
                        data : null,
                        failed : function(){
                            
                        },
                        success : function( htmlResponse ){
                            $("#player_main > #player_process").html( htmlResponse );
                        }
                    } );
                }break;
            }
        }
    } );
}
