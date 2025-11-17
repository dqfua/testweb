function pp_checkname( url,name,servernum ){
	newwindow = window.open( url+"?MemNum="+servernum+"&ChaName="+name , 'pp_chaname' , 'height=100,width=200' );
	if ( window.focus ){ newwindow.focus() }
}

function pp_changename( url,name,servernum ){
	newwindow = window.open( url+"?MemNum="+servernum+"&ChaName="+name+"&submit=1" , 'pp_chaname' , 'height=100,width=200' );
	if ( window.focus ){ newwindow.focus() }
}

function forgetpassword_popup( url , servernum, width , height , logo , bgbody , bgcolor ){
        var strOption = Substitute( "width={0},height={1}" , width, height );
        var strValue = Substitute( "{0}?memnum={1}&logo={2}&bgbody={3}&bgcolor={4}" , url , servernum , logo , bgbody , bgcolor );
	newwindow = window.open( strValue , 'forgetpassword' , strOption );
	if ( window.focus ){ newwindow.focus() }
}
