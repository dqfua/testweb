function js_popup(theURL,donow,width,height) { //v2.0
	leftpos = (screen.availWidth - width) / 2;
	toppos = (screen.availHeight - height) / 2;
	window.open(theURL, "viewdetails"+donow,"width=" + width + ",height=" + height + ",left=" + leftpos + ",top=" + toppos + ",scrollbars=1");
}