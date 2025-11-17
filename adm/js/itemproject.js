var table_Ability = [ "table_ItemDamage" , "table_ItemDefense" , "table_Item_TurnNum" , "table_ItemDay" , "table_Res" , "table_Rate" , "table_Drop" , "table_Resell" ];

//on load
tableSwitch_Loader();
Preview_Img_Loader();
Preview_Resell_Loader();

function tableSwitch_Loader()
{
    var itemType = $("#ItemType").val();
    
    for( var i = 0 ; i < table_Ability.length ; i++ )
    {
        if ( itemType == "0" )
            tr_switch( table_Ability[i] , 0 );
        else
            tr_switch( table_Ability[i] , 1 );
    }
}

function tr_switch( id , visible )
{
    if ( visible == 0 )
        $("#"+id).hide();
    else
        $("#"+id).show();
}


$("#ItemType").change( function(){
    tableSwitch_Loader();
});

{
    var idbutton = "uploader";
    var uploader = document.getElementById(idbutton);
    
    if ( uploader != null )
    {
        var or_text = $("#"+idbutton).text();
        upclick(
          {
           element: uploader,
           action: 'process.php?cmd=itemproject_list&type=1101', 
           onstart:
             function(filename)
             {
                 Preview_Img_Loading();
             },
           oncomplete:
             function(htmlResponse)
             {
                 switch( argGet( htmlResponse , 0 ) )
                 {
                     case "SUCCESS":{
                             $("#ItemImage").val( argGet( htmlResponse , 1 ) );
                             Preview_Img_ShowInfo( false );
                             Preview_Img_Loader();
                     }break;
                     default : {
                             Preview_Img_ShowInfo( true );
                     }break;
                 }
             }
          });
    }
}

function Preview_Img_Loader()
{
    var urlImg = $("#ItemImage").val();
    $("#previewImg").html( "<img border=\"0\" src=\""+urlImg+"\" style=\"width:150px;height:150px;\">" );
}

function Preview_Img_ShowInfo( error )
{
    var speed = "slow" , delay = 3000;
    if ( error == true )
    {
        $("#previewImg_ERROR").slideDown( speed );
        setTimeout( function(){
            $("#previewImg_ERROR").slideUp( speed );
        } , delay );
    }else{
        $("#previewImg_SUCCESS").slideDown( speed );
        setTimeout( function(){
            $("#previewImg_SUCCESS").slideUp( speed );
        } , delay );
    }
}

function Preview_Img_Loading()
{
    var speed = "slow" , delay = 1000;
    $("#previewImg_LOADING").slideDown( speed );
    setTimeout( function(){
        $("#previewImg_LOADING").slideUp( speed );
    } , delay );
}


function Preview_Resell_Loader()
{
    var itemPrice = $("#ItemPrice").val();
    var itemResellPercent = $("#Item_Resell_Percent").val();
    var returnPoint = 0;
    if ( itemPrice == 0 || itemResellPercent == 0 )
    {
        $("#previewResell").html( "พ้อยที่ได้รับเมื่อขายคืนคือ : "+returnPoint );
        return ;
    }
    
    returnPoint = itemPrice-(itemPrice*itemResellPercent/100);
    if ( returnPoint > itemPrice )
    {
            returnPoint = itemPrice;
    }
    
    if ( returnPoint < 0 ) returnPoint = 0;
    
    $("#previewResell").html( "พ้อยที่ได้รับเมื่อขายคืนคือ : "+Math.floor( returnPoint ) );
}

$("#Item_Resell_Percent").change( function(){
    Preview_Resell_Loader();
});

$("#addItem").click( function(){
    var SubNum = $("#SubNum").val();
    var ItemType = $("#ItemType").val();
    var ItemMain = $("#ItemMain").val();
    var ItemSub = $("#ItemSub").val();
    var ItemName = $("#ItemName").val();
    var ItemComment = $("#ItemComment").val();
    var ItemImage = $("#ItemImage").val();
    var ItemPrice = $("#ItemPrice").val();
    var ItemTimePrice = $("#ItemTimePrice").val();
    var ItemBonusPointPrice = $("#ItemBonusPointPrice").val();
    var ItemSock = $("#ItemSock").val();
    var ItemDamage = $("#ItemDamage").val();
    var ItemDefense = $("#ItemDefense").val();
    var Item_TurnNum = $("#Item_TurnNum").val();
    var ItemDay = $("#ItemDay").val();
    var Item_Res_Ele = $("#Item_Res_Ele").val();
    var Item_Res_Fire = $("#Item_Res_Fire").val();
    var Item_Res_Ice = $("#Item_Res_Ice").val();
    var Item_Res_Poison = $("#Item_Res_Poison").val();
    var Item_Res_Spirit = $("#Item_Res_Spirit").val();
    var Item_Op1 = $("#Item_Op1").val();
    var Item_Op1_Value = $("#Item_Op1_Value").val();
    var Item_Op2 = $("#Item_Op2").val();
    var Item_Op2_Value = $("#Item_Op2_Value").val();
    var Item_Op3 = $("#Item_Op3").val();
    var Item_Op3_Value = $("#Item_Op3_Value").val();
    var Item_Op4 = $("#Item_Op4").val();
    var Item_Op4_Value = $("#Item_Op4_Value").val();
    var ItemDrop = $("#ItemDrop").val();
    var Item_MaxReborn = $("#Item_MaxReborn").val();
    var Item_Resell = $("#Item_Resell").val();
    var Item_Resell_Percent = $("#Item_Resell_Percent").val();
    var ItemShow = $("#ItemShow").val();
    
    var senddata = "";
    
    function AddMSG( name , val , added )
    {
        if ( added ) senddata += name + "=" + val; else senddata += "&" + name + "=" + val; 
    }
    AddMSG( "SubNum" , SubNum , true );
    AddMSG( "ItemType" , ItemType , false );
    AddMSG( "ItemMain" , ItemMain , false );
    AddMSG( "ItemSub" , ItemSub , false );
    AddMSG( "ItemName" , ItemName , false );
    AddMSG( "ItemComment" , ItemComment , false );
    AddMSG( "ItemImage" , ItemImage , false );
    AddMSG( "ItemPrice" , ItemPrice , false );
    AddMSG( "ItemTimePrice" , ItemTimePrice , false );
    AddMSG( "ItemBonusPointPrice" , ItemBonusPointPrice , false );
    AddMSG( "ItemSock" , ItemSock , false );
    AddMSG( "ItemDamage" , ItemDamage , false );
    AddMSG( "ItemDefense" , ItemDefense , false );
    AddMSG( "Item_TurnNum" , Item_TurnNum , false );
    AddMSG( "ItemDay" , ItemDay , false );
    AddMSG( "Item_Res_Ele" , Item_Res_Ele , false );
    AddMSG( "Item_Res_Fire" , Item_Res_Fire , false );
    AddMSG( "Item_Res_Ice" , Item_Res_Ice , false );
    AddMSG( "Item_Res_Poison" , Item_Res_Poison , false );
    AddMSG( "Item_Res_Spirit" , Item_Res_Spirit , false );
    AddMSG( "Item_Op1" , Item_Op1 , false );
    AddMSG( "Item_Op1_Value" , Item_Op1_Value , false );
    AddMSG( "Item_Op2" , Item_Op2 , false );
    AddMSG( "Item_Op2_Value" , Item_Op2_Value , false );
    AddMSG( "Item_Op3" , Item_Op3 , false );
    AddMSG( "Item_Op3_Value" , Item_Op3_Value , false );
    AddMSG( "Item_Op4" , Item_Op4 , false );
    AddMSG( "Item_Op4_Value" , Item_Op4_Value , false );
    AddMSG( "ItemDrop" , ItemDrop , false );
    AddMSG( "Item_MaxReborn" , Item_MaxReborn , false );
    AddMSG( "Item_Resell" , Item_Resell , false );
    AddMSG( "Item_Resell_Percent" , Item_Resell_Percent , false );
    AddMSG( "ItemShow" , ItemShow , false );
    
    var speed = "fast";
    
    $.ajax({
        url : "process.php?cmd=itemproject_add&type=2000",
        type : "post",
        data : senddata,
        beforeSend : function(){
            $("#table_Project").slideUp( speed );
            $("#showWORK").text( "กรุณารอสักครู่" );
            $("#showWORK").slideDown( speed );
        },
        success : function( htmlResponse ){
            //alert( htmlResponse );
            var argReturn = argToArray( htmlResponse );
            switch( argReturn[0] )
            {
                case "SUCCESS":
                {
                    $("#showWORK").text( "เพิ่มไอเทมเรียบร้อยแล้ว" );
                    setTimeout( function(){
                        $("#showWORK").slideUp( speed );
                    } , 3000 );
                }break;
                default:
                {
                    $("#showWORK").hide();
                    $("#showERROR").show();
                    var otext = $("#showERROR").text( );
                    $("#showERROR").text( otext + htmlResponse );
                    setTimeout( function(){
                        $("#showERROR").slideUp( speed );
                    } , 3000 );
                };
            }
            setTimeout( function(){
                    $("#table_Project").slideDown( speed );
                } , 1500 );
        }
    });
});

$("#editItem").click( function(){
    var arrItemShow = [ "Show" , "No Show" , "แสดงเฉพาะ GM" ];
    var arrItemType = ["item Bank","Item Inventory"];
    
    var ItemNum = $("#ItemNum").val();
    var SubNum = $("#SubNum").val();
    var ItemType = $("#ItemType").val();
    var ItemMain = $("#ItemMain").val();
    var ItemSub = $("#ItemSub").val();
    var ItemName = $("#ItemName").val();
    var ItemComment = $("#ItemComment").val();
    var ItemImage = $("#ItemImage").val();
    var ItemPrice = $("#ItemPrice").val();
    var ItemTimePrice = $("#ItemTimePrice").val();
    var ItemBonusPointPrice = $("#ItemBonusPointPrice").val();
    var ItemSock = $("#ItemSock").val();
    var ItemDamage = $("#ItemDamage").val();
    var ItemDefense = $("#ItemDefense").val();
    var Item_TurnNum = $("#Item_TurnNum").val();
    var ItemDay = $("#ItemDay").val();
    var Item_Res_Ele = $("#Item_Res_Ele").val();
    var Item_Res_Fire = $("#Item_Res_Fire").val();
    var Item_Res_Ice = $("#Item_Res_Ice").val();
    var Item_Res_Poison = $("#Item_Res_Poison").val();
    var Item_Res_Spirit = $("#Item_Res_Spirit").val();
    var Item_Op1 = $("#Item_Op1").val();
    var Item_Op1_Value = $("#Item_Op1_Value").val();
    var Item_Op2 = $("#Item_Op2").val();
    var Item_Op2_Value = $("#Item_Op2_Value").val();
    var Item_Op3 = $("#Item_Op3").val();
    var Item_Op3_Value = $("#Item_Op3_Value").val();
    var Item_Op4 = $("#Item_Op4").val();
    var Item_Op4_Value = $("#Item_Op4_Value").val();
    var ItemDrop = $("#ItemDrop").val();
    var Item_MaxReborn = $("#Item_MaxReborn").val();
    var Item_Resell = $("#Item_Resell").val();
    var Item_Resell_Percent = $("#Item_Resell_Percent").val();
    var ItemShow = $("#ItemShow").val();
    
    if ( Item_MaxReborn == "" ) Item_MaxReborn = 0;
    
    var senddata = "";
    
    function AddMSG( name , val , added )
    {
        if ( added ) senddata += name + "=" + val; else senddata += "&" + name + "=" + val; 
    }
    AddMSG( "SubNum" , SubNum , true );
    AddMSG( "ItemType" , ItemType , false );
    AddMSG( "ItemMain" , ItemMain , false );
    AddMSG( "ItemSub" , ItemSub , false );
    AddMSG( "ItemName" , ItemName , false );
    AddMSG( "ItemComment" , ItemComment , false );
    AddMSG( "ItemImage" , ItemImage , false );
    AddMSG( "ItemPrice" , ItemPrice , false );
    AddMSG( "ItemTimePrice" , ItemTimePrice , false );
    AddMSG( "ItemBonusPointPrice" , ItemBonusPointPrice , false );
    AddMSG( "ItemSock" , ItemSock , false );
    AddMSG( "ItemDamage" , ItemDamage , false );
    AddMSG( "ItemDefense" , ItemDefense , false );
    AddMSG( "Item_TurnNum" , Item_TurnNum , false );
    AddMSG( "ItemDay" , ItemDay , false );
    AddMSG( "Item_Res_Ele" , Item_Res_Ele , false );
    AddMSG( "Item_Res_Fire" , Item_Res_Fire , false );
    AddMSG( "Item_Res_Ice" , Item_Res_Ice , false );
    AddMSG( "Item_Res_Poison" , Item_Res_Poison , false );
    AddMSG( "Item_Res_Spirit" , Item_Res_Spirit , false );
    AddMSG( "Item_Op1" , Item_Op1 , false );
    AddMSG( "Item_Op1_Value" , Item_Op1_Value , false );
    AddMSG( "Item_Op2" , Item_Op2 , false );
    AddMSG( "Item_Op2_Value" , Item_Op2_Value , false );
    AddMSG( "Item_Op3" , Item_Op3 , false );
    AddMSG( "Item_Op3_Value" , Item_Op3_Value , false );
    AddMSG( "Item_Op4" , Item_Op4 , false );
    AddMSG( "Item_Op4_Value" , Item_Op4_Value , false );
    AddMSG( "ItemDrop" , ItemDrop , false );
    AddMSG( "Item_MaxReborn" , Item_MaxReborn , false );
    AddMSG( "Item_Resell" , Item_Resell , false );
    AddMSG( "Item_Resell_Percent" , Item_Resell_Percent , false );
    AddMSG( "ItemShow" , ItemShow , false );
    AddMSG( "ItemNum" , ItemNum , false );
    
    var speed = "fast";
    
    $.ajax({
        url : "process.php?cmd=itemproject_list&type=10001",
        type : "post",
        data : senddata,
        beforeSend : function(){
            $("#table_Project").slideUp( speed );
            $("#showWORK").text( "กรุณารอสักครู่" );
            $("#showWORK").slideDown( speed );
        },
        success : function( htmlResponse ){
            //alert( ItemImage );
            //alert( htmlResponse );
            var argReturn = argToArray( htmlResponse );
            switch( argReturn[0] )
            {
                case "SUCCESS":
                {
                    $("#showWORK").text( "แก้ไขไอเทมเรียบร้อยแล้ว" );
                    setTimeout( function(){
                        $("#showWORK").slideUp( speed );
                    } , 3000 );
                    
                    for( var i = 0 ; i < sublistdata.length ; i++ )
                    {
                        var ppData;
                        ppData = sublistdata[ i ];
                        if ( ppData[0] == ItemNum )
                        {
                            ppData[1] = SubNum;
                            ppData[2] = ItemType;
                            ppData[3] = ItemMain;
                            ppData[4] = ItemSub;
                            ppData[5] = ItemName;
                            ppData[6] = ItemShow;
                            //ppData[7] = ItemSell;
                            ppData[8] = ItemSock;
                            ppData[9] = ItemDay;
                            ppData[10] = ItemPrice;
                            ppData[11] = ItemMain + ":" + ItemSub;
                            ppData[12] = arrItemType[ ItemType ];
                            ppData[13] = arrItemShow[ ItemShow ];
                            ppData[14] = Item_Resell;
                            ppData[15] = ItemDrop;
                            ppData[16] = Item_MaxReborn;
                            ppData[17] = ItemTimePrice;
                            ppData[18] = ItemBonusPointPrice;
                            break;
                        }
                    }
                   loaderListData();
                   setTimeout( function(){
                        backProject();
                    } , 2000 );
                }break;
                default:
                {
                    $("#showWORK").hide();
                    $("#showERROR").show();
                    setTimeout( function(){
                        $("#showERROR").slideUp( speed );
                    } , 3000 );
                    setTimeout( function(){
                    $("#table_Project").slideDown( speed );
                } , 1500 );
                };
            }
        }
    });
});

//on load > list
loaderListData();

function loaderListData()
{
    $("#listData tbody > tr").remove();
    
    var tdrop = [ "Trade" , "No Trade" ];
    var tresell = [ "ไม่รับคืน" , "รับคืน" ];
    var ttype = [ "Bank" , "Inven" ];
    
    var filter = $("#filter").val();
    var sel = $("#filtersub").val();
    var type = $("#filtertype").val();
    
    for( var i = 0 ; i < sublistdata.length ; i++ )
    {
        var ppData;
        ppData = sublistdata[ i ];
        if ( filter == 1 ) ppData = sublistdata[ (sublistdata.length-1) - i ];
        if ( ppData[1] != sel && sel != 0 ) continue;
        if ( ppData[2] != type ) continue;
        
        var thetype = "Unknow";
        
        for( var n =  0 ; n < sublistheader.length ; n++ )
        {
            var nData = sublistheader[n];
            if( nData[0] == ppData[ 1 ] )
            {
                thetype = nData[1];
            }
        }

        var htmlData = "";
        htmlData += "<tr>";
        htmlData += "<td><div align=\"center\">"+ ppData[ 11 ] +"</div></td>";
        htmlData += "<td><div align=\"left\">"+ thetype +"</div></td>";
        //htmlData += "<td><div align=\"center\">"+ ppData[ 12 ] +"</div></td>";
        htmlData += "<td><div align=\"left\">"+ ppData[ 5 ] +"</div></td>";
        //htmlData += "<td><div align=\"center\">"+ ppData[ 10 ] +"</div></td>";
        //htmlData += "<td><div align=\"center\">"+ ppData[ 7 ] +"</div></td>";
        //htmlData += "<td><div align=\"center\">"+ ppData[ 8 ] +"</div></td>";
        htmlData += "<td><div align=\"left\">"+ ttype[ ppData[ 2 ] ] + ", ราคา " + ppData[ 10 ] +  " พ้อย, ราคา(เวลาออนไลน์) " + ppData[ 17 ] + " นาที, แต้มสะสม " + ppData[ 18 ] + " แต้ม, จำหน่าย "+ ppData[ 7 ] + ", คงเหลือ " + ppData[ 8 ] + ", ";
        htmlData += ppData[ 13 ] + ", " + ppData[ 9 ] + " วัน, " + tdrop[ ppData[ 15 ] ] + ", " + tresell[ ppData[ 14 ] ] + ", " + ppData[ 16 ] + " จุติ" +"</div></td>";
        htmlData += "<td><div align=\"center\"><button onclick=\"editProject(this,"+ppData[ 0 ]+");\">แก้ไข</button><button onclick=\"delProject(this,"+ppData[ 0 ]+");\">ลบ</button></div></td>";
        htmlData += "</tr>";
        $("#listData > tbody").append(htmlData);
    }
}

$("#filter").change( function(){
    loaderListData();
});

$("#filtersub").change( function(){
    loaderListData();
});

$("#filtertype").change( function(){
    loaderListData();
});

function escapesublist( index ){
    //alert( sublistdata.length );
    for( var i = 0 ; i < sublistdata.length-1 ; i ++ )
    {
        var ppdata = sublistdata[ i ];
        if ( ppdata[ 0 ] == index )
        {
            for( var n = i ; n < sublistdata.length ; n ++ )
            {
                sublistdata[ n ] = sublistdata[ n+1 ];
            }
            //sublistdata[ sublistdata.length ] = null;
            sublistdata.pop();
            break;
        }
    }
    //alert( sublistdata.length );
}

function backProject()
{
    var speed = "fast";
    $("#main_itemproject #main_itemlist").slideUp(speed);
    $("#main_itemproject #listData").slideDown(speed);
    //ChooseMenu( 'itemproject_list' );
}

function editProject( hins , itemnum )
{
    var speed = "fast";
    $("#main_itemproject #listData").slideUp(speed);
    $.ajax({
        url : "process.php?cmd=itemproject_list&type=10000",
        type : "post",
        data : "itemnum="+itemnum,
        success : function( htmlResponse ){
            $("#main_itemproject #main_itemlist").html(htmlResponse);
        }
    });
    $("#main_itemproject #main_itemlist").slideDown(speed);
}

function delProject( hins , itemnum )
{
    if ( !confirm("คุณต้องการลบใช่หรือไม่") ) return ;
    $(hins).parent().parent().parent().remove();
    
    $.ajax({
        url : "process.php?cmd=itemproject_list&type=1000",
        type : "post",
        data : "itemnum="+itemnum,
        success : function( htmlResponse ){
            escapesublist( itemnum );
            //alert(htmlResponse);
        }
    });
}

