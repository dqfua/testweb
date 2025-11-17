var isMoveSingleID = false;
var extraUserID = "";

function createExportKey()
{
    $.ajax({
        url : "process.php?cmd=export&type=key",
        type : "post",
        data : "",
        success : function( htmlResponse ){
            $("#exportKey").html( htmlResponse );
        }
    });
}

function deleteSubmit()
{
    $.ajax({
        url : "process.php?cmd=export&type=delete",
        type : "post",
        data : "",
        success : function( htmlResponse ){
            $("#exportKey").html( htmlResponse );
        }
    });
}

function importSubmit()
{
    var importKey = $("#importKey").val();
    
    loading_div = "exportKey";
    nowloading = true;
    renderLoading();
    
    $.ajax({
        url : "process.php?cmd=export&type=submit",
        type : "post",
        data : "importKey=" + importKey,
        
        success : function( htmlResponse ){
            nowloading = false;
            $("#exportKey").html( htmlResponse );
            //alert("finish");
        }
    });
}

function update_status( text )
{
    $("#importStatus").text(text);
}

function update_Character( v )
{
    $("#importCharacter").text( v );
}

function update_User( v )
{
    $("#importUser").text( v );
}

function avg(arr)
{
    var sum = 0;
    for (var i = 0; i < arr.length; i++)
    {
      sum += parseFloat(arr[i]);
    }
    return sum / i;
}

var import_start = false;
var current_user = 0;
var max_user = 0;

function setMaxUser( v )
{
    max_user = v;
}

function Set_CurrentUser( v )
{
    current_user = v;
}

function importStop()
{
    import_start = false;
    update_status( 'Standby' );
    $("#beginImportButton").text( "Begin Import" );
    $("#beginImportButton").attr("onClick","importBegin(false)");
    
    exportLog_AddText( "Request Stop" );
}

function importBegin(isSingleID)
{
    isMoveSingleID = isSingleID;
    
    alert("ในการรวมเซิบมีความเสี่ยงที่จะผิดพลาด และไม่สามารถกู้คืนได้");
    alert("ก่อนรวมเซิบควร Backup Database ของเซิบต้นทางและปลายทางไว้ก่อนเสมอ");
    alert("และควรปิดเซิบเวอร์ก่อนทำการรวมเซิบเวอร์เพื่อป้องกันการผิดพลาดอื่นๆที่จะตามมา");
    alert("กรุณาอ่านคู่มือการใช้งานและสิ่งที่ต้องทำก่อนรวมเซิบเวอร์ให้เข้าใจก่อนจะทำการรวมเซิบเวอร์");
    alert("หากมีอะไรผิดพลาดใดๆทาง Shop ไม่มีการ Backup สำรองไว้ให้และไม่สามารกู้คืนอะไรใดๆให้ได้");
    
    if(!confirm("คุณแน่ใจแล้วใช่หรือไม่ที่จะทำการรวมเซิบเวอร์")) return;
    
    var total_User = parseInt( $("#importUser").text() );
    if ( total_User >= max_user )
    {
        alert("การย้ายสิ้นสุดลงแล้ว");
        return;
    }
    
    var extSameID = $("#extSameID").val();
    var extSameChar = $("#extSameChar").val();
    extraUserID = $("#extraUserID").val();
    
    exportLog_AddText( "Request Start" );
    
    $.ajax({
        url : "process.php?cmd=export&type=importprocessbegin",
        type : "post",
        data : "extSameID=" + extSameID + "&extSameChar=" + extSameChar,
        failed : function(){
            exportLog_AddText( "Start Failed" );
        },
        success : function( htmlResponse ){
            if ( htmlResponse == "S" )
            {
                //$("#importProcessing").html( htmlResponse );

                import_start = true;
                update_status( 'Process...' );
                
                if ( isMoveSingleID )
                {
                    $("#buttonImportSingleID").text( "Processing..." );
                    $("#buttonImportSingleID").attr("onClick","alert('Processing...');");
                    
                    setTimeout( import_process_single , 2000 );
                }else{
                    $("#beginImportButton").text( "Stop" );
                    $("#beginImportButton").attr("onClick","importStop()");
                    
                    setTimeout( import_process , 2000 );
                }
                
                exportLog_AddText( "Starting..." );
            }else{
                exportLog_AddText( "Start Failed by Option" );
            }
        }
    });
}

function import_response_callback( htmlResponse )
{
    current_user = parseInt( argGet( htmlResponse , 2 ) );
                    
    var total_User = parseInt( $("#importUser").text() );
    update_User( total_User + parseInt( argGet( htmlResponse , 3 ) ) );

    var moveSize = parseInt( argGet( htmlResponse , 3 ) );
    var sizeStruct = 0;
    for( var i = 0 ; i < moveSize ; i++)
    {
        var userID = argGet( htmlResponse , 4 + sizeStruct );
        sizeStruct++;

        var userNum = argGet( htmlResponse , 4 + sizeStruct );
        sizeStruct++;

        var newUserNum = argGet( htmlResponse , 4 + sizeStruct );
        sizeStruct++;

        var characterMoveSize = parseInt( argGet( htmlResponse , 4 + sizeStruct ) );
        sizeStruct++;

        exportLog_AddText( "Move ID " + userID + "(" + userNum.toString() + " > " + newUserNum.toString() + ")" + ", Character Count " + characterMoveSize.toString() );

        for( var n = 0 ; n < characterMoveSize ; n++ )
        {
            var chaName = argGet( htmlResponse , 4 + sizeStruct );
            sizeStruct++;

            var chaNum = argGet( htmlResponse , 4 + sizeStruct );
            sizeStruct++;

            /*
            var newChaNum = argGet( htmlResponse , 4 + sizeStruct );
            sizeStruct++;
            */

            //exportLog_AddText( "   - Character " + chaName + "(" + chaNum.toString() + " > " + newChaNum.toString() + ")" );
            exportLog_AddText( "   - Character " + chaName );
        }

        var total_Character = parseInt( $("#importCharacter").text() );
        update_Character( total_Character + characterMoveSize );
    }

    exportLog_AddText( "took " + argGet( htmlResponse , 1 ) + " ms" );
}

function import_process()
{
    if( !import_start ) return ;
    
    $.ajax({
        url : "process.php?cmd=export&type=importprocess",
        type : "post",
        data : "current_user="+current_user,
        failed : function(){
            $("#importFailed").text( parseInt( $("#importFailed").text() ) + 1 );
            exportLog_AddText( "failed:" + current_user );
        },
        success : function( htmlResponse ){
            switch( argGet( htmlResponse , 0 ) )
            {
                case "S":
                {
                    import_response_callback( htmlResponse );
                }break;
                
                case "F":
                {
                    exportLog_AddText( "ERR:FAILED:CODE:" + argGet( htmlResponse , 1 ).toString() );
                }break;
                
                case "END":
                {
                    exportLog_AddText( "END." );
                }break;
                
                default:
                {
                    import_start = false;
                }break;
            }
            //$("#importProcessing").html( htmlResponse );
        }
    });
    
    var total_User = parseInt( $("#importUser").text() );
    if ( total_User >= max_user )
    {
        update_status( "Completed" );
        exportLog_AddText( "Completed" );
        
        $("#beginImportButton").text( "Begin Import" );
        $("#beginImportButton").attr("onClick","importBegin(false)");
        
        import_start = false;
    }
    
    setTimeout( import_process , 1000 );
}

function import_process_single()
{
    alert( "extraUserID="+extraUserID );
    
    $.ajax({
        url : "process.php?cmd=export&type=importprocesssingle",
        type : "post",
        data : "extraUserID="+extraUserID,
        failed : function(){
            exportLog_AddText( "failed:" + extraUserID );
        },
        success : function( htmlResponse ){
            switch( argGet( htmlResponse , 0 ) )
            {
                case "S":
                {
                    import_response_callback( htmlResponse );
                }break;
                
                case "F":
                {
                    exportLog_AddText( "ERR:FAILED:CODE:" + argGet( htmlResponse , 1 ).toString() );
                }break;
                
                case "END":
                {
                    exportLog_AddText( "Not found ID" );
                }break;
                
                default:
                {
                    exportLog_AddText( "Unknow return value : " + htmlResponse );
                }break;
            }
        }
    });
    
    $("#buttonImportSingleID").text( "Import Single ID" );
    $("#buttonImportSingleID").attr("onClick","importBegin(true)");
    
    update_status( "Completed" );
    exportLog_AddText( "Completed" );
}

function export_Rollback_process()
{
    $.ajax({
            url : "process.php?cmd=export&type=importrollbackprocess",
            type : "post",
            data : null,
            failed : function(){
            },
            success : function( htmlResponse ){
                //alert( htmlResponse );
                switch( argGet( htmlResponse , 0 ) )
                {
                    case "S":
                    {
                        exportLog_AddText( htmlResponse );
                        setTimeout( export_Rollback_process , 300 );
                    }break;
                    
                    case "F":
                    {
                        exportLog_AddText( "Rollback success done." );
                    }break;
                }
            }
        });
}

function export_Button_Rollback()
{
    alert("คุณแน่ใจแล้วใช่หรือไม่ที่จะทำการ Rollback ข้อมูลที่ย้ายมาจากเซิบเวอร์ต้นทาง");
    alert("ในการกด Rollback นี้ไม่สามารถกู้กลับมาได้อีก");
    if ( confirm("คุณแน่ใจแล้วใช่หรือไม่"))
    {
        loading_div = "exportKey";
        nowloading = true;
        renderLoading();
        
        $.ajax({
            url : "process.php?cmd=export&type=importrollback",
            type : "post",
            data : null,
            failed : function(){
            },
            success : function( htmlResponse ){
                nowloading = false;
                $("#exportKey").html( htmlResponse );
            }
        });
    }
}

function exportLog_AddText( text )
{
    var d = new Date();
    
    var exportLogSize = $("#exportlog option").size();
    if( exportLogSize >= 1000 ) $("#exportlog option").first().remove();
    $("#exportlog option:selected").removeAttr("selected");
    $("#exportlog").append( "<option selected=\"selected\">["+ d.toTimeString().substr(0,8) +"]" + text + "</option>" );
}

function switchDiv( div_hide , div_show )
{
    $( "#"+div_hide ).slideUp();
    //$( "#"+div_show ).css("display","block");
    if ( $( "#"+div_show ).is(":hidden") )
    {
        $( "#"+div_show ).slideDown()();
    }
}

function button_importAllID()
{
    switchDiv( "areaExtraExport" , "areaMenuMain" );
}

function button_importSingleID()
{
    switchDiv( "areaMenuMain" , "areaExtraExport" );
}
