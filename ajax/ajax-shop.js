function register_updatecode( link_host , memnum )
{
	var gg_width = $("#reg_width").val();
	var gg_height = $("#reg_height").val();
	var gg_logo = $("#reg_logo").val();
	var gg_bgcolor = $("#reg_bgcolor").val();
	var gg_bgcolor_get= $("#reg_bgcolor_get").val();
	document.getElementById('reg_code').value = Substitute( '<iframe src="'+link_host+'popup/register.php?memnum='+memnum+'&logo={2}&bgbody={3}&bgcolor={4}" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:{0}px; height:{1}px;" allowTransparency="true"></iframe>' , gg_width , gg_height , gg_logo , gg_bgcolor , gg_bgcolor_get );
}

function shoppop_reg_copy_to_clip()
{
	copy( document.getElementById('reg_code').value );
}