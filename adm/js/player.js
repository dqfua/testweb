function user_movetoban( szIP )
{
    $.ajax( {
        url : "process.php?cmd=player&type=100000",
        type : "post",
        data : "ip="+szIP,
        success: function(h){
            $("#player_main > #player_process > #userprocess").html( h );
        }
    });
}

function user_setbanip()
{
    var tt = "โปรดรอสักครู่...";
    var stbt = $("#stbt").text();
    
    if ( stbt != tt )
    {
        $("#stbt").text(tt);
        var szIP = $("#banip").val();
        $.ajax( {
            url : "process.php?cmd=player&type=100001",
            type : "post",
            data : "ip="+szIP,
            success: function(h){
                $("#player_main > #player_process > #userprocess").html( h );
            }
        });
        //alert("WORKING");
    }
}

//user log
function userlog_logingame( hins )
{
    var oldbuttomtext = $(hins).text();
    $(hins).text("กำลังโหลด");
    $(hins).attr("disabled","disabled");
    
    setTimeout( function(){
        for( var i = logviewroll , c = 0 ; i < logview_logingame.length && c < 30 ; i++ , c++ )
        {
            var htmlData = "<tr>";
            htmlData += "<td>"+logview_logingame[i][0]+"</td>";
            htmlData += "<td>";
            htmlData += logview_logingame[i][1];
            if ( logview_logingame[i][1].length > 0 ) htmlData += "<button onclick=\"user_movetoban('"+logview_logingame[i][1]+"');\">แบน</button>";
            htmlData += "</td>";
            htmlData += "<td>"+logview_logingame[i][2]+"</td>";
            htmlData += "</tr>";
            //$("#gridtable > tbody:first").after(htmlData);
            $("#gridtable > tbody:last").append(htmlData);
            logviewroll ++;
        }
        if ( logviewroll < logview_logingame.length )
        {
            $(hins).removeAttr("disabled");
        }
        $(hins).text(oldbuttomtext);
    }, 1000 );
}

//user
function usermenumode( modenum )
{
    var tt = 0;
    switch( modenum )
    {
        //editor
        case 0:
        {
            tt = 40000;
        }break;
        case 1:
        {
            tt = 41000;
        }break;
        case 2:
        {
            tt = 3100;
        }break;
        
        //log viewer
        case 100:
        case 101:
        case 102:
        case 103:
        case 104:
        case 105:
        case 106:
        case 107:
        case 108:
        case 109:
        case 110:
        case 111:
        case 112:
        {
            tt = 3000 + ( modenum - 100 );
        }break;
    }
    
    $.ajax( {
        url : "process.php?cmd=player&type="+tt,
        type : "post",
        data : "",
        failed : function(){
        },
        success : function( htmlResponse ){
            $("#player_main > #player_process > #userprocess").html( htmlResponse );
        }
    } );
}

function shoppurchase_delete( purkey )
{
    $.ajax( {
        url : "process.php?cmd=player&type=3013",
        type : "post",
        data : "purkey=" + purkey,
        failed : function(){
        },
        success : function( htmlResponse ){
            $("#player_main > #player_process > #userprocess").html( htmlResponse );
        }
    } );
}

function locker_del( l , x , y )
{
    if ( !confirm( "แน่ใจหรือว่าที่จะลบไอเทมดังกล่าว" ) ) return ;
    
    var senddata = "";
    senddata += "l="+l;
    senddata += "&Item_InvenX="+x;
    senddata += "&Item_InvenY="+y;
    
    $.ajax( {
        url : "process.php?cmd=player&type=41003",
        type : "post",
        data : senddata,
        success : function( htmlResponse ){
            $("#player_main > #player_process > #userprocess").html( htmlResponse );
        }
    } );
}

function locker_save( l , x , y )
{
    if ( !confirm( "คุณต้องการบันทึกข้อมูลใช่หรือไม่" ) ) return ;
    
    var senddata = "";
    
    function updatemsg( dd , added )
    {
        if ( added ) senddata += "&";
        senddata += dd + "=" + $("#"+dd).val();
    }
    
    updatemsg( "ItemMain" , false );
    updatemsg( "ItemSub" , true );
    updatemsg( "ItemDamage" , true );
    updatemsg( "ItemDefense" , true );
    updatemsg( "Item_TurnNum" , true );
    updatemsg( "Item_Drop" , true );
    updatemsg( "Item_Res_Ele" , true );
    updatemsg( "Item_Res_Fire" , true );
    updatemsg( "Item_Res_Ice" , true );
    updatemsg( "Item_Res_Poison" , true );
    updatemsg( "Item_Res_Spirit" , true );
    updatemsg( "Item_Op1" , true );
    updatemsg( "Item_Op2" , true );
    updatemsg( "Item_Op3" , true );
    updatemsg( "Item_Op4" , true );
    updatemsg( "Item_Op1_Value" , true );
    updatemsg( "Item_Op2_Value" , true );
    updatemsg( "Item_Op3_Value" , true );
    updatemsg( "Item_Op4_Value" , true );
    
    senddata += "&Item_InvenX="+x;
    senddata += "&Item_InvenY="+y;
    senddata += "&l="+l;
    
    $.ajax( {
        url : "process.php?cmd=player&type=41002",
        type : "post",
        data : senddata,
        success : function( htmlResponse ){
            $("#player_main > #player_process > #userprocess").html( htmlResponse );
        }
    } );
}

function locker_edit( l , x , y )
{
    $.ajax( {
        url : "process.php?cmd=player&type=41001",
        type : "post",
        data : "l="+l+"&x="+x+"&y="+y,
        failed : function(){
        },
        success : function( htmlResponse ){
            $("#player_main > #player_process > #userprocess").html( htmlResponse );
        }
    } );
}

function locker_show( i )
{
    var hins = $("#locker_slot_"+i);
    if ( hins.css("display") == "none" ) hins.show( "slow" ); else hins.hide( "slow" );
}

function user_passsave( type )
{
    var Pass = $("#Pass").val();
    var Pass2 = $("#Pass2").val();
    if ( Pass.length < 4 )
    {
        alert( "รหัสผ่านสั่นเกินไป" );
        return ;
    }
    if ( Pass != Pass2 )
    {
        alert( "รหัสทั้งสองช่องไม่ตรงกัน" );
        return ;
    }
    switch( type )
    {
        case 0:
        {
            type = 40004;
        }break;
        case 1:
        {
            type = 40005;
        }break;
    }
    $.ajax( {
        url : "process.php?cmd=player&type="+type,
        type : "post",
        data : "Pass="+Pass,
        failed : function(){
        },
        success : function( htmlResponse ){
            $("#player_main > #player_process > #userprocess").html( htmlResponse );
        }
    } );
}

function user_changepass( type )
{
    type = 40002+type;
    $.ajax( {
        url : "process.php?cmd=player&type="+type,
        type : "post",
        data : null,
        failed : function(){
        },
        success : function( htmlResponse ){
            $("#player_main > #player_process > #userprocess").html( htmlResponse );
        }
    } );
}

function user_save( usernum )
{
    var senddata = "";
    
    function updatemsg( dd , added )
    {
        if ( added ) senddata += "&";
        senddata += dd + "=" + $("#"+dd).val();
    }
    
    updatemsg( "UserName" , false );
    updatemsg( "UserID" , true );
    updatemsg( "UserType" , true );
    updatemsg( "UserLoginState" , true );
    updatemsg( "ChaRemain" , true );
    updatemsg( "UserPoint" , true );
    updatemsg( "BonusPoint" , true );
    updatemsg( "UserGameOnlineTime" , true );
    updatemsg( "UserBlock" , true );
    updatemsg( "UserEmail" , true );
    updatemsg( "ParentID" , true );
    senddata += "&UserNum="+usernum;
    
    $.ajax( {
        url : "process.php?cmd=player&type=40001",
        type : "post",
        data : senddata,
        failed : function(){
        },
        success : function( htmlResponse ){
            $("#player_main > #player_process > #userprocess").html( htmlResponse );
        }
    } );
}

function user2char(chanum)
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
                default:
                {
                    alert( "ไม่พบข้อมูลตัวละคร" );
                }
            }
        }
    } );
}

function user_search( hins )
{
    $.ajax( {
        url : "process.php?cmd=player&type=2002",
        type : "post",
        data : "text=" + $("#text").val() + "&search_type=" + $("#search_type").val(),
        failed : function(){
        },
        success : function( htmlResponse ){
            $("#player_main > #player_process").html( htmlResponse );
        }
    } );
}

function seluser()
{
    $.ajax( {
        url : "process.php?cmd=player&type=2003",
        type : "post",
        data : "usernum=" + $("#usernum").val(),
        failed : function(){
        },
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":
                    {
                        $.ajax( {
                            url : "process.php?cmd=player&type=2004",
                            type : "post",
                            data : null,
                            failed : function(){
                                
                            },
                            success : function( h ){
                                $("#player_main > #player_process").html( h );
                            }
                        });
                    }break;
                    default : alert("มีปัญหาบางอย่างไม่สามารถล็อกอิน!!" );
            }
        }
    } );
}

//char viewer
function charlogviewmode( modenum )
{
    var type = 100 + modenum;
    
    $.ajax( {
        url : "process.php?cmd=player&type="+type,
        type : "post",
        data : null,
        failed : function(){

        },
        success : function( htmlResponse ){
            $("#player_main > #player_process > #player_process_ui").html( htmlResponse );
        }
    } );
}

//char editor
var html_search = "";
var searchtype = 0;

$("#player_main > #player_menu > p > #character_mode").click( function(){
    $.ajax( {
        url : "process.php?cmd=player&type=1001",
        success : function(h){
            $("#player_main > #player_process").html( h );
            searchtype = 0;
        }
    } );
});

$("#player_main > #player_menu > p > #user_mode").click( function(){
    $.ajax( {
        url : "process.php?cmd=player&type=2001",
        success : function(h){
            $("#player_main > #player_process").html( h );
            searchtype = 1;
        }
    } );
});

function char2user( usernum )
{
    char2user_div( usernum , "#player_main > #player_process" );
}

function char2user_div( usernum , div )
{
    $.ajax( {
        url : "process.php?cmd=player&type=2003",
        type : "post",
        data : "usernum=" + usernum,
        failed : function(){
        },
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":
                    {
                        $.ajax( {
                            url : "process.php?cmd=player&type=2004",
                            type : "post",
                            data : null,
                            failed : function(){
                                
                            },
                            success : function( h ){
                                $(div).html( h );
                            }
                        });
                    }break;
                    default : alert("มีปัญหาบางอย่างไม่สามารถล็อกอิน!!" );
            }
        }
    } );
}

function putonitem_save( pos )
{
    if ( !confirm( "คุณต้องการบันทึกข้อมูลใช่หรือไม่" ) ) return ;
    
    var senddata = "";
    
    function updatemsg( dd , added )
    {
        if ( added ) senddata += "&";
        senddata += dd + "=" + $("#"+dd).val();
    }
    
    updatemsg( "ItemMain" , false );
    updatemsg( "ItemSub" , true );
    updatemsg( "ItemDamage" , true );
    updatemsg( "ItemDefense" , true );
    updatemsg( "Item_TurnNum" , true );
    updatemsg( "Item_Drop" , true );
    updatemsg( "Item_Res_Ele" , true );
    updatemsg( "Item_Res_Fire" , true );
    updatemsg( "Item_Res_Ice" , true );
    updatemsg( "Item_Res_Poison" , true );
    updatemsg( "Item_Res_Spirit" , true );
    updatemsg( "Item_Op1" , true );
    updatemsg( "Item_Op2" , true );
    updatemsg( "Item_Op3" , true );
    updatemsg( "Item_Op4" , true );
    updatemsg( "Item_Op1_Value" , true );
    updatemsg( "Item_Op2_Value" , true );
    updatemsg( "Item_Op3_Value" , true );
    updatemsg( "Item_Op4_Value" , true );
    
    senddata += "&pos="+pos;
    
    $.ajax( {
        url : "process.php?cmd=player&type=20003",
        type : "post",
        data : senddata,
        success : function( htmlResponse ){
            //alert( htmlResponse );
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":
                    {
                        $("#player_main > #player_process > #player_process_ui").html( "<span class=\"info\">บันทึกเรียบร้อย</span>" );
                    }break;
                default : alert( "มีปัญหาบางประการไม่สามารถบันทึกข้อมูล" );
            }
        }
    } );
}

function putonitem_edit( hins , pos )
{
    $.ajax( {
        url : "process.php?cmd=player&type=20002",
        type : "post",
        data : "pos="+pos,
        failed : function(){},
        success : function(htmlResponse){
            $("#player_main > #player_process > #player_process_ui").html( htmlResponse );
        }
    } );
}

function putonitem_del( hins , pos )
{
    if ( !confirm( "คุณต้องการลบไอเทมชิ้นนี้แน่นอนหรือไม่" ) ) return ;
    
    $.ajax( {
        url : "process.php?cmd=player&type=20004",
        type : "post",
        data : "pos="+pos,
        failed : function(){},
        success : function(htmlResponse){
            $(hins).parent().html( "Empty <button onclick=\"putonitem_edit(this," + pos + ");\">Add</button>" );
        }
    } );
}

function skill_add( hins )
{
    //$(hins).attr( "disabled" , "disabled" );
    
    var skillnum = $("#skillnum").val();
    var main = $("#add_main").val();
    var sub = $("#add_sub").val();
    var lev = $("#add_lev").val();
    
    $.ajax( {
        url : "process.php?cmd=player&type=30004",
        type : "post",
        data : "main="+main +"&sub="+sub +"&lev="+lev,
        timeout : 5000,
        failed : function(){},
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":{
                        $("#skillnum").val( skillnum+1 );
    
                        var levui = skilllevoptionui;
                        levui = levui.replace( "id=\"462a\"" , "id=\"lev_" + skillnum + "\"" );

                        var htmlData = "";
                        htmlData += "<tr>";
                        htmlData += "<td>";
                        htmlData += "<input type=\"text\" id=\"main_" + skillnum + "\" value=\""+main+"\" style=\"width:39px;\">:";
                        htmlData += "<input type=\"text\" id=\"sub_" + skillnum + "\" value=\""+sub+"\" style=\"width:39px;\">:" + levui;
                        htmlData += "<button onclick=\"skill_edit(this,"+ skillnum +");\">Edit</button><button onclick=\"skill_del(this,"+ skillnum +");\">Del</button>"
                        htmlData += "</td>";
                        htmlData += "</tr>";

                        $("#skill_editor_ui > tbody:last").before( htmlData );
                        $("#lev_"+skillnum).val( lev );
                }break;
                
                default : {
                        alert( "ไม่สามารถเพิ่มสกิลนี้ได้หรืออาจจะมีอยู่ในระบบแล้ว" );
                }
            }
        }
    });
}

function skill_edit( hins , nid )
{
    $(hins).attr( "disabled" , "disabled" );
    
    var main = $("#main_"+nid).val();
    var sub = $("#sub_"+nid).val();
    var lev = $("#lev_"+nid).val();
    
    $.ajax( {
        url : "process.php?cmd=player&type=30003",
        type : "post",
        data : "nid=" + nid +"&main="+main +"&sub="+sub +"&lev="+lev,
        timeout : 5000,
        failed : function(){},
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":{
                        $(hins).removeAttr( "disabled" );
                }break;
                
                default : {
                        alert( "มีปัญหาบางประการไม่สามารถแก้ไขได้" );
                }
            }
        }
    });
}

function skill_del( hins , nid )
{
    if ( !confirm( "คุณต้องการลบสกิลนี่แน่นอนใช่ไหม" ) ) return ;
    
    $("#skillnum").val( $("#skillnum").val( )-1 );
    
    $.ajax( {
        url : "process.php?cmd=player&type=30002",
        type : "post",
        data : "nid=" + nid,
        timeout : 5000,
        failed : function(){},
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":{
                        $(hins).parent().parent().remove();
                }break;
                
                default : {
                        alert( "มีปัญหาบางประการไม่สามารถลบสกิลนี้ได้" );
                }
            }
        }
    });
}

function item_chainven_add( x , y )
{
    $.ajax( {
        url : "process.php?cmd=player&type=10002",
        type : "post",
        data : "p=0&x=" + x + "&y=" + y,
        success : function( htmlResponse ){
            $("#player_main > #player_process > #player_process_ui").html( htmlResponse );
        }
    } );
}

function item_chainven_edit( x , y )
{
    $.ajax( {
        url : "process.php?cmd=player&type=10002",
        type : "post",
        data : "p=0&x=" + x + "&y=" + y,
        success : function( htmlResponse ){
            $("#player_main > #player_process > #player_process_ui").html( htmlResponse );
        }
    } );
}

function item_chainven_del( x , y )
{
    if ( !confirm( "คุณต้องการที่จะลบรายการนี้แน่นอนใช่หรือไม่" ) ) return ;
    
    $.ajax( {
        url : "process.php?cmd=player&type=10002",
        type : "post",
        data : "p=1&x=" + x + "&y=" + y,
        success : function( htmlResponse ){
            $("#player_main > #player_process > #player_process_ui").html( htmlResponse );
        }
    } );
}

function item_chainven_save( x , y )
{
    if ( !confirm( "คุณต้องการบันทึกข้อมูลใช่หรือไม่" ) ) return ;
    
    var senddata = "";
    
    function updatemsg( dd , added )
    {
        if ( added ) senddata += "&";
        senddata += dd + "=" + $("#"+dd).val();
    }
    
    updatemsg( "ItemMain" , false );
    updatemsg( "ItemSub" , true );
    updatemsg( "ItemDamage" , true );
    updatemsg( "ItemDefense" , true );
    updatemsg( "Item_TurnNum" , true );
    updatemsg( "Item_Drop" , true );
    updatemsg( "Item_Res_Ele" , true );
    updatemsg( "Item_Res_Fire" , true );
    updatemsg( "Item_Res_Ice" , true );
    updatemsg( "Item_Res_Poison" , true );
    updatemsg( "Item_Res_Spirit" , true );
    updatemsg( "Item_Op1" , true );
    updatemsg( "Item_Op2" , true );
    updatemsg( "Item_Op3" , true );
    updatemsg( "Item_Op4" , true );
    updatemsg( "Item_Op1_Value" , true );
    updatemsg( "Item_Op2_Value" , true );
    updatemsg( "Item_Op3_Value" , true );
    updatemsg( "Item_Op4_Value" , true );
    
    senddata += "&Item_InvenX="+x;
    senddata += "&Item_InvenY="+y;
    
    //alert( senddata );
    
    $.ajax( {
        url : "process.php?cmd=player&type=10003",
        type : "post",
        data : senddata,
        success : function( htmlResponse ){
            $("#player_main > #player_process > #player_process_ui").html( htmlResponse );
        }
    } );
}

function submit_editcharacter()
{
    function updatemsg( dd , added )
    {
        if ( added ) senddata += "&";
        senddata += dd + "=" + $("#"+dd).val();
    }
    
    var senddata = "";
    updatemsg( "UserNum" , false );
    updatemsg( "GuNum" , true );
    updatemsg( "ChaClass" , true );
    updatemsg( "ChaSchool" , true );
    updatemsg( "ChaHair" , true );
    updatemsg( "ChaFace" , true );
    updatemsg( "ChaLevel" , true );
    updatemsg( "ChaExp" , true );
    updatemsg( "ChaMoney" , true );
    updatemsg( "ChaPower" , true );
    updatemsg( "ChaStrong" , true );
    updatemsg( "ChaStrength" , true );
    updatemsg( "ChaSpirit" , true );
    updatemsg( "ChaDex" , true );
    updatemsg( "ChaStRemain" , true );
    updatemsg( "ChaStartMap" , true );
    updatemsg( "ChaSaveMap" , true );
    updatemsg( "ChaReturnMap" , true );
    updatemsg( "ChaBright" , true );
    updatemsg( "ChaPK" , true );
    updatemsg( "ChaSkillPoint" , true );
    updatemsg( "ChaInvenLine" , true );
    updatemsg( "ChaDeleted" , true );
    updatemsg( "ChaReborn" , true );
    updatemsg( "ChaName" , true );
    updatemsg( "ChaGuName" , true );
    
    $.ajax( {
        url : "process.php?cmd=player&type=7002",
        type : "post",
        data : senddata,
        failed : function(){
        },
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":
                {
                    $("#player_main > #player_process > #player_process_ui").html( "<span class=\"info\">แก้ไขสำเร็จ</span>" );
                }break;
                
                case "ERROR":
                {
                    alert( "ไม่สามารถแก้ไขได้เนื่องจากมีปัญหาบางประการ" );
                }break;
            }
        }
    });
}

function submit_editcharactername()
{
    function updatemsg( dd , added )
    {
        if ( added ) senddata += "&";
        senddata += dd + "=" + $("#"+dd).val();
    }
    
    var senddata = "";
    updatemsg( "ChaName" , false );
    
    $.ajax( {
        url : "process.php?cmd=player&type=400002",
        type : "post",
        data : senddata,
        failed : function(){
        },
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":
                {
                    $("#player_main > #player_process > #player_process_ui").html( "<span class=\"info\">แก้ไขสำเร็จ</span>" );
                }break;
                
                case "ERROR":
                {
                    alert( "ไม่สามารถแก้ไขได้เนื่องจากมีปัญหาบางประการ" );
                }break;
            }
        }
    });
}

function submit_editcharacterguname()
{
    function updatemsg( dd , added )
    {
        if ( added ) senddata += "&";
        senddata += dd + "=" + $("#"+dd).val();
    }
    
    var senddata = "";
    updatemsg( "ChaGuName" , false );
    
    $.ajax( {
        url : "process.php?cmd=player&type=500002",
        type : "post",
        data : senddata,
        failed : function(){
        },
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":
                {
                    $("#player_main > #player_process > #player_process_ui").html( "<span class=\"info\">แก้ไขสำเร็จ</span>" );
                }break;
                
                case "ERROR":
                {
                    alert( "ไม่สามารถแก้ไขได้เนื่องจากมีปัญหาบางประการ" );
                }break;
            }
        }
    });
}

function chaeditormode( modenum )
{
    var type = 0;
    switch( modenum )
    {
        case 0:
        {
            type = 7001;
        }break;
        
        case 1:
        {
            type = 10001;
        }break;
        
        case 2:
        {
            type = 20001;
        }break;
        
        case 3:
        {
            type = 30001;
        }break;
		
		case 4:
        {
            type = 400001;
        }break;
		
		case 5:
        {
            type = 500001;
        }break;
    }
    
    $.ajax( {
        url : "process.php?cmd=player&type="+type,
        type : "post",
        data : null,
        failed : function(){

        },
        success : function( htmlResponse ){
            $("#player_main > #player_process > #player_process_ui").html( htmlResponse );
        }
    } );
}

function selcharacter()
{
    var chanum = $("#chanum").val();
    
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
                default:
                {
                    alert( "ไม่พบข้อมูลตัวละคร" );
                }
            }
        }
    } );
}

function search( hins )
{
    var text = $("#text").val();
    var search_type = $("#search_type").val();
    
    var type = 2002;
    if ( searchtype == 0 ){
        type = 1002;
    }
    
    $(hins).attr( "disabled" , "disabled" );
    $(hins).text( "กำลังโหลด.." );
    
    $.ajax( { 
        url : "process.php?cmd=player&type="+type,
        type : "post",
        data : "text="+text+"&search_type="+search_type,
        //timeout : 10000,
        failed : function(){
        },
        success : function(h){
            html_search = $("#player_main > #player_process").html( );
            $("#player_main > #player_process").html( h );
        }
    } );
};

function resell_itemview(itemview)
{
    var hins = $("#itemview_"+itemview);
    if ( hins.css("display") == "none" ) hins.show( "slow" ); else hins.hide( "slow" );
}
