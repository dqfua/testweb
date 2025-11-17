<?php
define( "FILE_TYPE_NULL" , 0 );
define( "FILE_TYPE_IMAGE" , 1 );
define( "FILE_TYPE_TEXT" , 2 );

define( "FILE_TYPE_IMAGE_PNG" ,		0 );
define( "FILE_TYPE_IMAGE_JPG" ,		 1 );
define( "FILE_TYPE_IMAGE_JPGE" ,	  2 );
define( "FILE_TYPE_IMAGE_GIF" ,        3 );
define( "FILE_TYPE_TEXT_FILE" ,        0 );

$_CONFIG["UPLOADFILE"]["SIZE_LIMIT"] = 1;//MB

$_CONFIG["SKILLFILETYPE"] = array( FILE_TYPE_TEXT_FILE => "txt" );
$_CONFIG["IMAGEFILETYPE"] = array(
																	FILE_TYPE_IMAGE_PNG => "png"
																	, FILE_TYPE_IMAGE_JPG => "jpg"
																	, FILE_TYPE_IMAGE_JPGE => "jpge"
																	, FILE_TYPE_IMAGE_GIF => "gif"
																	);

class CUpload
{
	protected $nType = FILE_TYPE_NULL;
	protected $szFileData = "";
	
	public function SetTypeImage(){ $this->nType = FILE_TYPE_IMAGE; }
	
	public function DoImage( &$fileoutput )
	{
		if ( $this->szFileData == "" ) return FALSE;
		if ( $this->nType != FILE_TYPE_IMAGE ) return FALSE;
		
		global $_CONFIG;
		
		$filesize = @CGlobal::bytetomb( filesize( $_FILES[$this->szFileData]['tmp_name'] ) );
		if ( $filesize > $_CONFIG["UPLOADFILE"]["SIZE_LIMIT"] ) return FALSE;
				
		$extention = CGlobal::get_file_extension( $_FILES[$this->szFileData]['name'] );
		if ( $extention == "" ) return FALSE;
		
		$img = NULL;
		switch( array_search( $extention , $_CONFIG["IMAGEFILETYPE"] ) )
		{
			case FILE_TYPE_IMAGE_PNG :
			{
				$img = imagecreatefrompng( $_FILES[$this->szFileData]['tmp_name'] );
			}break;
			case FILE_TYPE_IMAGE_JPG :
			case FILE_TYPE_IMAGE_JPGE:
			{
				$img = imagecreatefromjpeg( $_FILES[$this->szFileData]['tmp_name'] );
			}break;
			case FILE_TYPE_IMAGE_GIF :
			{
				$img = imagecreatefromgif( $_FILES[$this->szFileData]['tmp_name'] );
			}break;
			default:
			{
				return FALSE;
			}break;
		}
		if ( !$img ) return FALSE;
		
		$img_width = imagesx( $img );
		$img_height = imagesy( $img );
		if ( !$img_width || !$img_height ) return FALSE;
		
		$new_img = @imagecreatetruecolor( $img_width , $img_height );
		if ( !$new_img ) return FALSE;
		
		if (!@imagefilledrectangle($new_img, 0, 0, $img_width, $img_height, 0))
		{
			return FALSE;
		}
	
		if (!@imagecopyresampled($new_img, $img, 0, 0, 0, 0, $img_width, $img_height, $img_width, $img_height))
		{
			return FALSE;
		}
		$text_color = imagecolorallocate( $new_img  , 255 , 255 , 255 );
		@imagettftext(
						$new_img
						, 10
						, 0
						, 2
						, $img_height - 7
						, $text_color
						, 'arial.ttf'
						, sprintf("%s", $_CONFIG["HOSTLINK"])
						);
		$fileoutput = PATH_UPLOAD_ITEMIMAGE.$fileoutput.".png";
		return imagepng( $new_img , $fileoutput );
	}
	
	public function __destruct()
	{
		if ( $this->szFileData == "" ) return ;
		
		unlink( $_FILES[$this->szFileData]["tmp_name"] );
	}
	
	public function __construct( $filedata )
	{
		if (!isset($_FILES[$filedata]) || !is_uploaded_file($_FILES[$filedata]["tmp_name"]) || $_FILES[$filedata]["error"] != 0)
		{
			return FALSE;
		}
		$this->szFileData = $filedata;
		return TRUE;
	}
	
	public function IsOk()
	{
		if ( $this->szFileData != "" ) return TRUE;
		return FALSE;
	}
}
?>