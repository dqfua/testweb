function Load_MainMenu()
{
    $.ajax({
        url : "process.php",
        type : "get",
        data : "cmd=main_menu",
        success : function( htmlRespose ) {
           $("#main_menu").html( htmlRespose );
        }
    });
}

function Load_MainProc()
{
    $.ajax({
        url : "process.php",
        type : "get",
        data : "cmd=news",
        success : function( htmlRespose ) {
           $("#main_process").html( htmlRespose );
        }
    });
}

function ChooseMenu( cmenu )
{
    $.ajax({
        url : "process.php",
        type : "get",
        data : "cmd="+cmenu,
        success : function( htmlResponse ) {
            $("#main_process").html( htmlResponse );
        }
    });
}

function logOut()
{
    $.ajax({
        url : "process.php",
        type : "get",
        data : "cmd=logout",
        success : function() {
            $.ajax({
                url : "process.php",
                type : "get",
                data : null,
                success : function( htmlRespose ) {
                    $("#main").html( htmlRespose );
                }
            });
        }
    });
}

Load_MainMenu();
Load_MainProc();
