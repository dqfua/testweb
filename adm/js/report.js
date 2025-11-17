var SEL_TYPE_NONE = 0;
var SEL_TYPE_DAY  = 1;
var SEL_TYPE_MONTH = 2;
var SEL_TYPE_YEAR = 3;

var sel_type_main = SEL_TYPE_NONE;

function main_sel( me , d , m , y )
{
    var varspeed = "fast";
    if ( me == false )
    {
        $("#main_sel").fadeOut(varspeed);
        $("#main_sel_smart").fadeOut(varspeed);
    }else{
        $("#main_sel").fadeIn(varspeed);
        $("#main_sel_smart").fadeIn(varspeed);
    }
    if ( d == false ) $("#sel_d").fadeOut(varspeed); else  $("#sel_d").fadeIn(varspeed);
    if ( m == false ) $("#sel_m").fadeOut(varspeed); else  $("#sel_m").fadeIn(varspeed);
    if ( y == false ) $("#sel_y").fadeOut(varspeed); else  $("#sel_y").fadeIn(varspeed);
}

function menuOut( main , me , d , m , y )
{
    var varspeed = "fast";
    var fadeTime = 500;
    
    if ( main == false )
    {
        main_sel( false , false , false , false );
        setTimeout( function(){
            $("#mini_menu").fadeIn(varspeed);
        } , fadeTime );
    }else{
        $("#mini_menu").fadeOut(varspeed);
        setTimeout( function(){
            main_sel( me , d , m , y );
        } , fadeTime );
    }
}

function showcardInfo( num , usernum )
{
    var idele = $("#cardinfo_"+num) , varspeed = "slow";
    if ( idele.css("display") == "none" )
    {
        if ( $("#userinfo_id_"+num).text() == "" )
        {
            function userinfo_set( text )
            {
                $("#userinfo_id_"+num).text( text );
            }
            $.ajax({
                url : "process.php?cmd=report&type=1101",
                type : "post",
                data : "usernum="+usernum,
                beforeSend : function(){
                    userinfo_set( "กรุณารอสักครู่" );
                },
                success : function( htmlResponse ){
                    userinfo_set( htmlResponse );
                }
            });
        }
        
        idele.slideDown(varspeed);
    }else{
        idele.slideUp(varspeed);
        //$("#userinfo_id_"+num).text( "" );
    }
}

function needinfoAdd( hins )
{
    //cardRoll int,
    //cardDataInfo = [ [ xxx ]  ]
    var oldbuttomtext = $(hins).text();
    $(hins).text("กำลังโหลด");
    $(hins).attr("disabled","disabled");
    
    setTimeout( function(){
        for( var i = cardRoll , c = 0 ; i < cardDataInfo.length && c < 10 ; i++ , c++ )
        {
            var htmlData = "<tr>";
            htmlData += "<td>"+cardDataInfo[i][1]+"</td>";
            htmlData += "<td>"+cardDataInfo[i][2]+
                    " <button onclick=\"showcardInfo("+i+
                    ","+cardDataInfo[i][3]+
                    ");\">รายละเอียด</button><div id=\"cardinfo_"+i+
                    "\" style=\"display:none;\">ทำรายการเมื่อ : "+cardDataInfo[i][4]+
                    "<br>ถูกตรวจสอบเมื่อ : "+cardDataInfo[i][5]+
                    "<br>สถานะ : "+cardDataInfo[i][6]+
                    "<br>บัตรมูลค่า : "+cardDataInfo[i][7]+
                    "<br>เติมเข้าไอดี : <span id=\"userinfo_id_"+i+
                    "\"></span>("+cardDataInfo[i][3]+")</div></td>";
            htmlData += "</tr>";
            //alert( htmlData );
            //$("#cardInfoTable tr:last").after("<tr><td>...</td><td>xx2</td></tr>");
            //$("#cardInfoTable > tbody:last").after("<tr><td>...</td><td>xx2</td></tr>");
            $("#cardInfoTable > tbody:last").after(htmlData);
            cardRoll ++;
            //break;// debug
        }
        if ( cardRoll < cardDataInfo.length )
        {
            $(hins).removeAttr("disabled");
        }
        $(hins).text(oldbuttomtext);
    }, 1000 );
}

function onClick_ReportTopUserPoint()
{
    $.ajax({
        url : "process.php?cmd=report&type=1000",
        type : "post",
        data : null,
        beforeSend : function(){
            nowloading = true;
            loading_div = "main_show";
            renderLoading();
            $("#main_show").slideDown("fast");
        },
        success: function( htmlResponse ){
            nowloading = false;
            $("#main_show").html( htmlResponse );
        }
    });
}

function onClick_ReportTopChaMoney()
{
    $.ajax({
        url : "process.php?cmd=report&type=1003",
        type : "post",
        data : null,
        beforeSend : function(){
            nowloading = true;
            loading_div = "main_show";
            renderLoading();
            $("#main_show").slideDown("fast");
        },
        success: function( htmlResponse ){
            nowloading = false;
            $("#main_show").html( htmlResponse );
        }
    });
}

function onClick_ReportTopBonusPoint()
{
    $.ajax({
        url : "process.php?cmd=report&type=1002",
        type : "post",
        data : null,
        beforeSend : function(){
            nowloading = true;
            loading_div = "main_show";
            renderLoading();
            $("#main_show").slideDown("fast");
        },
        success: function( htmlResponse ){
            nowloading = false;
            $("#main_show").html( htmlResponse );
        }
    });
}

function onClick_ReportGMAll()
{
    $.ajax({
        url : "process.php?cmd=report&type=1001",
        type : "post",
        data : null,
        beforeSend : function(){
            nowloading = true;
            loading_div = "main_show";
            renderLoading();
            $("#main_show").slideDown("fast");
        },
        success: function( htmlResponse ){
            nowloading = false;
            $("#main_show").html( htmlResponse );
        }
    });
}

$("#menu_bshow").click( function(){
    switch( sel_type_main )
    {
        case SEL_TYPE_DAY:
        case SEL_TYPE_MONTH:
        case SEL_TYPE_YEAR:
            break;
        default: return ;
    }
    
    var ddd = $("#ddd").val();
    var mmm = $("#mmm").val();
    var yyy = $("#yyy").val();
    
    $.ajax({
        url : "process.php?cmd=report&type="+sel_type_main,
        type : "post",
        data : "ddd="+ddd+"&mmm="+mmm+"&yyy="+yyy,
        beforeSend : function(){
            nowloading = true;
            loading_div = "small_smart";
            renderLoading();
            $("#small_smart").slideDown("fast");
        },
        success: function( htmlResponse ){
            nowloading = false;
            $("#small_smart").html( htmlResponse );
        }
    });
});

$("#menu_back").click( function(){
    menuOut( false , false , false , false , false );
    $("#small_smart").slideUp("fast");
});

$("#bday").click( function(){
    sel_type_main = SEL_TYPE_DAY;
    menuOut( true , true , true , true , true )
});

$("#bmonth").click( function(){
    sel_type_main = SEL_TYPE_MONTH;
    menuOut( true , true , false , true , true );
});

$("#byear").click( function(){
    sel_type_main = SEL_TYPE_YEAR;
    menuOut( true , true , false , false , true );
});