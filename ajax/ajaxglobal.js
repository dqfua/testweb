function checkchaname( text,area )
{
	var chaname = $('#'+text+'').val();
	//alert(chaname);
	//chaname = Base64.encode( chaname );
	//alert(chaname);
	loadpage("checkname",area,"chaname="+chaname);
}
function setchaname( name )
{
	loadpage("checkname&submit=1","chaname","chaname="+name);
}