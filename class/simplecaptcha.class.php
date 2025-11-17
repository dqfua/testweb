<?php

class SimpleCaptcha
{
	public $String = "";
	public $nSize = 4;
	public $SeassionName = "SimpleCaptcha";
	
	public function randStr(){
		$chars = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
		$string = "";
		for ($i = 0; $i < $this->nSize; $i++){
			$pos = rand(0, strlen($chars)-1);
			$string .= $chars{$pos};
		}
		$this->String = $string;
		$_SESSION[$this->SeassionName] = $this->String;
		return $string;
	}
	
	public function Result()
	{
		self::randStr();
		$_SESSION[ $this->SeassionName ] = $this->String;
	}
	
	public function Display()
	{
		echo $_SESSION[ $this->SeassionName ];
	}
}

?>