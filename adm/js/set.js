//abiliry
function aPage1(hins)
{
    if ( !confirm("คุณแน่ใจหรือว่าต้องการที่จะเคลียร์รายการนี้") ) return ;
    $.ajax( {
        url : "process.php?cmd=set&page=p0",
        type : "post",
        data : "submit=1&clear=1",
        success : function( htmlResponse ){
            var argData = argToArray( htmlResponse );
            switch( argData[0] )
            {
                case "SUCCESS":
                {
                    $(hins).attr("disabled","disabled");
                    alert( "เคลียร์เรียบร้อยแล้ว" );
                }break;
                default:
                {
                    alert( "มีปัญหาบางประการไม่สามารถเคลียร์ได้" );
                }
            }
        }
    });
}

function SendData( key , value )
{
    return "&"+key+"="+value;
}

function saveP0()
{
    var sendData = "";
    
    var Cha_StatsPointBegin = $("#Cha_StatsPointBegin").val();
    var Cha_StatsPoint = $("#Cha_StatsPoint").val();
    var Cha_SkillPointBegin = $("#Cha_SkillPointBegin").val();
    var Cha_SkillPoint = $("#Cha_SkillPoint").val();
    var Login_PointFirst = $("#Login_PointFirst").val();
    var PassMD5 = $("#PassMD5").val();
    var ParentID = $("#ParentID").val();
    var BonusPoint = $("#BonusPoint").val();
    var BonusPointEx = $("#BonusPointEx").is(":checked");
    
    if ( BonusPointEx ) BonusPointEx = 1; else BonusPointEx = 0;
    //alert( BonusPointEx );
    
    sendData += SendData( "Cha_StatsPointBegin" , Cha_StatsPointBegin );
    sendData += SendData( "Cha_StatsPoint" , Cha_StatsPoint );
    sendData += SendData( "Cha_SkillPointBegin" , Cha_SkillPointBegin );
    sendData += SendData( "Cha_SkillPoint" , Cha_SkillPoint );
    sendData += SendData( "Login_PointFirst" , Login_PointFirst );
    sendData += SendData( "PassMD5" , PassMD5 );
    sendData += SendData( "ParentID" , ParentID );
    sendData += SendData( "BonusPoint" , BonusPoint );
    sendData += SendData( "BonusPointEx" , BonusPointEx );
    
    return sendData;
}

function saveP1()
{
    var sendData = "";
    
    var School = $("#School").val();
    var School_P = $("#School_P").val();
    var CharMad = $("#CharMad").val();
    var CharMad_P = $("#CharMad_P").val();
    var SkillOn = $("#SkillOn").val();
    var School_P = $("#School_P").val();
    var Online2Point = $("#Online2Point").val();
    var OnlineGetPoint = $("#OnlineGetPoint").val();
    var OnlineTime = $("#OnlineTime").val();
    var StatOn = $("#StatOn").val();
    var StatPoint = $("#StatPoint").val();
    var ResetSkill = $("#ResetSkill").val();
    var ResetSkillPoint = $("#ResetSkillPoint").val();
    var ChangeName = $("#ChangeName").val();
    var ChangeNamePoint = $("#ChangeNamePoint").val();
    var cMemBuySkillPointModeOn = $("#cMemBuySkillPointModeOn").val();
    var cMemBuySkillPointSkillPoint = $("#cMemBuySkillPointSkillPoint").val();
    var cMemBuySkillPointUsePoint = $("#cMemBuySkillPointUsePoint").val();
    var ChaDelete = $("#ChaDelete").val();
    
    sendData += SendData ( "School" , School );
    sendData += SendData ( "School_P" , School_P );
    sendData += SendData ( "CharMad" , CharMad );
    sendData += SendData ( "CharMad_P" , CharMad_P );
    sendData += SendData ( "SkillOn" , SkillOn );
    sendData += SendData ( "School_P" , School_P );
    sendData += SendData ( "Online2Point" , Online2Point );
    sendData += SendData ( "OnlineGetPoint" , OnlineGetPoint );
    sendData += SendData ( "OnlineTime" , OnlineTime );
    sendData += SendData ( "StatOn" , StatOn );
    sendData += SendData ( "StatPoint" , StatPoint );
    sendData += SendData ( "ResetSkill" , ResetSkill );
    sendData += SendData ( "ResetSkillPoint" , ResetSkillPoint );
    sendData += SendData ( "ChangeName" , ChangeName );
    sendData += SendData ( "ChangeNamePoint" , ChangeNamePoint );
    sendData += SendData ( "cMemBuySkillPointModeOn" , cMemBuySkillPointModeOn );
    sendData += SendData ( "cMemBuySkillPointSkillPoint" , cMemBuySkillPointSkillPoint );
    sendData += SendData ( "cMemBuySkillPointUsePoint" , cMemBuySkillPointUsePoint );
    sendData += SendData ( "ChaDelete" , ChaDelete );
    
    return sendData;
}

function saveP2()
{
    var sendData = "";
    
    var Class = $("#Class").val();
    var Class_P = $("#Class_P").val();
    var Class_Change_CheckStat = $("#Class_Change_CheckStat").val();
    var Class_Change_CheckSkill = $("#Class_Change_CheckSkill").val();
    
    var Class1_On = $("#Class1_On").val();
    var Class1_Name = $("#Class1_Name").val();
    var Class64_On = $("#Class64_On").val();
    var Class64_Name = $("#Class64_Name").val();
    var Class2_On = $("#Class2_On").val();
    var Class2_Name = $("#Class2_Name").val();
    var Class128_On = $("#Class128_On").val();
    var Class128_Name = $("#Class128_Name").val();
    var Class256_On = $("#Class256_On").val();
    var Class256_Name = $("#Class256_Name").val();
    var Class4_On = $("#Class4_On").val();
    var Class4_Name = $("#Class4_Name").val();
    var Class512_On = $("#Class512_On").val();
    var Class512_Name = $("#Class512_Name").val();
    var Class8_On = $("#Class8_On").val();
    var Class8_Name = $("#Class8_Name").val();
    var Class1024_On = $("#Class1024_On").val();
    var Class1024_Name = $("#Class1024_Name").val();
    var Class2048_On = $("#Class2048_On").val();
    var Class2048_Name = $("#Class2048_Name").val();
    var Class16_On = $("#Class16_On").val();
    var Class16_Name = $("#Class16_Name").val();
    var Class32_On = $("#Class32_On").val();
    var Class32_Name = $("#Class32_Name").val();
    var Class4096_On = $("#Class4096_On").val();
    var Class4096_Name = $("#Class4096_Name").val();
    var Class8192_On = $("#Class8192_On").val();
    var Class8192_Name = $("#Class8192_Name").val();
    
    sendData += SendData ( "Class" , Class );
    sendData += SendData ( "Class_P" , Class_P );
    sendData += SendData ( "Class_Change_CheckStat" , Class_Change_CheckStat );
    sendData += SendData ( "Class_Change_CheckSkill" , Class_Change_CheckSkill );
    
    sendData += SendData ( "Class1_On" , Class1_On );
    sendData += SendData ( "Class1_Name" , Class1_Name );
    sendData += SendData ( "Class64_On" , Class64_On );
    sendData += SendData ( "Class64_Name" , Class64_Name );
    sendData += SendData ( "Class2_On" , Class2_On );
    sendData += SendData ( "Class2_Name" , Class2_Name );
    sendData += SendData ( "Class128_On" , Class128_On );
    sendData += SendData ( "Class128_Name" , Class128_Name );
    sendData += SendData ( "Class256_On" , Class256_On );
    sendData += SendData ( "Class256_Name" , Class256_Name );
    sendData += SendData ( "Class4_On" , Class4_On );
    sendData += SendData ( "Class4_Name" , Class4_Name );
    sendData += SendData ( "Class512_On" , Class512_On );
    sendData += SendData ( "Class512_Name" , Class512_Name );
    sendData += SendData ( "Class8_On" , Class8_On );
    sendData += SendData ( "Class8_Name" , Class8_Name );
    sendData += SendData ( "Class1024_On" , Class1024_On );
    sendData += SendData ( "Class1024_Name" , Class1024_Name );
    sendData += SendData ( "Class2048_On" , Class2048_On );
    sendData += SendData ( "Class2048_Name" , Class2048_Name );
    sendData += SendData ( "Class16_On" , Class16_On );
    sendData += SendData ( "Class16_Name" , Class16_Name );
    sendData += SendData ( "Class32_On" , Class32_On );
    sendData += SendData ( "Class32_Name" , Class32_Name );
    sendData += SendData ( "Class4096_On" , Class4096_On );
    sendData += SendData ( "Class4096_Name" , Class4096_Name );
    sendData += SendData ( "Class8192_On" , Class8192_On );
    sendData += SendData ( "Class8192_Name" , Class8192_Name );
    
    return sendData;
}

function saveP3()
{
    var sendData = "";
    
    var CharReborn = $("#CharReborn").val();
    var CharReborn_P = $("#CharReborn_P").val();
    var CharReborn_Free = $("#CharReborn_Free").val();
    var CharReborn_Max = $("#CharReborn_Max").val();
    var CharRebornLevCheck = $("#CharRebornLevCheck").val();
    var CharRebornLevStart = $("#CharRebornLevStart").val();
    var CharRebornFreeOn = $("#CharRebornFreeOn").val();
    var CharRebornFreeCheck = $("#CharRebornFreeCheck").val();
    var ChaRebornGetPoint_Lv = $("#ChaRebornGetPoint_Lv").val();
    var ChaRebornGetPoint_Value = $("#ChaRebornGetPoint_Value").val();
    
    sendData += SendData ( "CharReborn" , CharReborn );
    sendData += SendData ( "CharReborn_P" , CharReborn_P );
    sendData += SendData ( "CharReborn_Free" , CharReborn_Free );
    sendData += SendData ( "CharReborn_Max" , CharReborn_Max );
    sendData += SendData ( "CharRebornLevCheck" , CharRebornLevCheck );
    sendData += SendData ( "CharRebornLevStart" , CharRebornLevStart );
    sendData += SendData ( "CharRebornFreeOn" , CharRebornFreeOn );
    sendData += SendData ( "CharRebornFreeCheck" , CharRebornFreeCheck );
    sendData += SendData ( "ChaRebornGetPoint_Lv" , ChaRebornGetPoint_Lv );
    sendData += SendData ( "ChaRebornGetPoint_Value" , ChaRebornGetPoint_Value );
    
    return sendData;
}

function saveP4()
{
    var sendData = "";
    
    var SkillSetOpen = $("#SkillSetOpen").val();
    var SkillPoint = $("#SkillPoint").val();
    
    sendData += SendData ( "SkillSetOpen" , SkillSetOpen );
    sendData += SendData ( "SkillPoint" , SkillPoint );
    
    return  sendData;
}

function P4_add( a , l , ll , classnum )
{
    var sel = $("#"+a).val();
    var lv = $("#"+l).val();
    
    var ppdata = listskilltable[ sel ];
    
    var SkillID = ppdata[ 0 ];
    var SkillName = ppdata[ 1 ];
    
    var txt = SkillID + "("+lv+") " + " : " +SkillName;
    //$("#"+ll+" option").length

    var aabbcdef = $("#"+ll+" option");
    for( var i = 0 ; i < aabbcdef.length ; i++ )
    {
		if ( aabbcdef[i].text.substr(3,7) == txt.substr(3,7) )
        {
            alert( "สกิลมีอยู่ในระบบแล้ว" );
            return ;
        }
    }
	
	$.ajax({
            url : "process.php?cmd=set&page=p4",
            type : "post",
            data : "submit=1"+"&classnum="+classnum+"&type=add&skillid="+SkillID+lv,
            success: function( htmlResponse ){
                //alert( htmlResponse );
                var argData = argToArray( htmlResponse );
                switch( argData[0] )
                {
                    case "SUCCESS":{
						$('#'+ll).append('<option value="'+SkillID+lv+'" selected="selected">'+txt+'</option>');
                    }break;
                    default:{
                    }break;
                }
            }
        });
}

function P4_del( a , classnum )
{
	$.ajax({
            url : "process.php?cmd=set&page=p4",
            type : "post",
            data : "submit=1"+"&classnum="+classnum+"&type=del&skillid="+$("#"+a).val(),
            success: function( htmlResponse ){
                //alert( htmlResponse );
                var argData = argToArray( htmlResponse );
                switch( argData[0] )
                {
                    case "SUCCESS":{
						$("#"+a+" option[value='"+$("#"+a).val()+"']").remove();
                    }break;
                    default:{
                    }break;
                }
            }
        });
}

function savePage( page )
{
    var senddata , speed = "fast";
    switch( page )
    {
        case "p0":{
                senddata = saveP0();
        }break;
        case "p1":{
                senddata = saveP1();
        }break;
        case "p2":{
                senddata = saveP2();
        }break;
        case "p3":{
                senddata = saveP3();
        }break;
        case "p4":{
                senddata = saveP4();
        }break;
    }
    if ( senddata )
    {
		//alert(senddata);
        $.ajax({
            url : "process.php?cmd=set&page="+page,
            type : "post",
            data : "submit=1"+senddata,
            success: function( htmlResponse ){
                //alert( htmlResponse );
                var argData = argToArray( htmlResponse );
                switch( argData[0] )
                {
                    case "SUCCESS":{
                            $("#workSET").slideDown( speed );
                            setTimeout( function(){
                                $("#workSET").slideUp( speed );
                            } , 3000);
                    }break;
                    default:{
                            $("#workFAIL").slideDown( speed );
                            setTimeout( function(){
                                $("#workFAIL").slideUp( speed );
                            } , 3000);
                    }break;
                }
            }
        });
    }
}

function switchMenu( page )
{
    var ide = $("#set_process");
    $.ajax({
        url : "process.php?cmd=set&page="+page,
        type : "post",
        data : null,
        beforeSend : function(){
            ide.html( "<span class=\"info\">กรุณารอสักครู่</span>" );
        },
        success : function(htmlResponse){
            ide.html( htmlResponse );
        }
    })
}