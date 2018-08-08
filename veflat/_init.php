<?php
class alien extends veflat{

	static public $qwerty = array();

	function __construct()
	{
	//	die("asd1");

		$this->conexion();

		if($this->isUser()){

			$xSes = $this->getData("SELECT * FROM usuarios WHERE id = '{$this->sessionGet('id')}'",true);
			$xSes = $xSes[0];
			$this->sessionNew($xSes);

			$qd = $this->getData("SELECT id, nombre, valor FROM config_user WHERE usuario ='".$this->sessionGet('id')."'", true);

			if(is_array($qd)) foreach ($qd as $k => $cfV) {
				$this->veflat_usuario[$cfV['nombre']] = $cfV['valor'];
			}
		}

		$this->ver("");
	}
}

$d = new alien();
?>
