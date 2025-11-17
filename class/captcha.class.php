<?php
define("SEC_LEN",128);

class CSec
{
	static public function Begin()
	{
		$capchar = new Captcha();
		$capchar->size = SEC_LEN; // �ӹǹ�ѡ���
		$capchar->session=SESSION_CAPTCHA; // ���� Session
		$capchar->randStr();
		//$_SESSION[SESSION_CAPTCHA] = serialize( $capchar );
	}
	static public function Check()
	{
		return self::End();
	}
	static public function End()
	{
		if ( !isset( $_SESSION[SESSION_CAPTCHA] ) ) return false;
		if ( strlen( $_SESSION[SESSION_CAPTCHA] ) != SEC_LEN ) return false;
		$_SESSION[SESSION_CAPTCHA] = NULL;
		return true;
	}
}

class Captcha{
	public $size;
	public $session;


	public function randStr(){
		$chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$string = "";
		for ($i = 0; $i < $this->size; $i++){
			$pos = rand(0, strlen($chars)-1);
			$string .= $chars{$pos};
		}
		$_SESSION[$this->session] = $string;
		return $string;
	}

	public function display($string){
		//$width = 26 * strlen($string);
                $width = 104;
                $height = 26; 
                //$string = $this->randStr(); 
                $im = ImageCreate($width, $height); 
                //$imBG = imagecreatefromjpeg("../images/captcha.jpg");
                $black = imagecolorallocate($im, 0, 0, 0); 
                $white = imagecolorallocate($im, 255, 255, 255); 
                imagerectangle($im,0, 0, $width-1, $height-1, $black);
                $fontname = "arial.gdf";
                $font = imageloadfont("font/" . $fontname);
                if ( !$font ) $font = imageloadfont("../font/" . $fontname);
                imagestring($im, $font , 13, 0, $string, $white);
                //imagettftext($im, 12, 0, 0, 0, $white, 1, $string);
                //imagestring($im, $font , $this->size, 5, $string, $white);
                //imagecopymerge($im, $imBG, 0, 0, 0, 0, 256, 256, 55);
                imagepng($im); 
                imagedestroy($im); 
	}

	public function RandCaptcha(){
		$this->size = 64;
		$this->session=SESSION_CAPTCHA;
		return self::randStr();
	}
        
        public static function ShowQuickCaptcha( $CAPTCHA_SESSION , $key = "" , $setup = false )
        {
            global $_CONFIG;
            
            if ( $setup )
            {
                $_SESSION[$CAPTCHA_SESSION] = $key;
            }
            
            $_SESSION[$_CONFIG["CAPTCHASINGLEDISPLAY"]] = $_SESSION[$CAPTCHA_SESSION];
            echo "<img id=\"captchasingle\" src=\"../displaycaptcha.php\" border=\"0\"></img>";
        }
}
?>
