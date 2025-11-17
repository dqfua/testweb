var __strArg = ":";

function argLength( data )
{
    var str = data;
    var argLen = 0;
    var npos = str.search(__strArg);
    while( npos !== -1 )
    {
        argLen ++;
        str = str.substr( npos+1 );
        npos = str.search(__strArg);
    }
    return argLen+1;
}

function argToArray( data )
{
    var dataArg = [];
    var str = data;
    var npos = str.search(__strArg);
    
    for( var i = 0 ; npos !== -1 ; i++ )
    {
        dataArg[ i ] = str.substr( 0 , npos );
        str = str.substr( npos+1 );
        npos = str.search(__strArg);
    }
    dataArg[ i ] = str;
    
    return dataArg;
}

function argGet( data , arg )
{
    if ( arg < 0 || arg > argLength( data ) ) return null;
    var dataArg = argToArray( data );
    if ( arg > dataArg.length ) return null;
    return dataArg[ arg ];
}