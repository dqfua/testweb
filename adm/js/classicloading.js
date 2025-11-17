var showPointLoading = 0;
var nowloading = false;
var loading_div = "";
function  renderLoading( )
{
    if ( !nowloading ) return ;
    var text = "Now Loading";
    for( var i = 0 ; i < showPointLoading ; i++ )
    {
        text += ".";
    }
    $("#"+loading_div).html( text );
    showPointLoading++;
    if ( showPointLoading > 5 ) showPointLoading = 0;
    setTimeout( renderLoading , 500 );
    return ;
}