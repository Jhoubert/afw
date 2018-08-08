<?php

class Veflat extends Helpers{

	var $veflat;
	var $notificaciones;
	var $lang = array();

	function set($key,$value)
	{
		$veflat[$key] = $value;
	}

	function get($key)
	{
		return $veflat[$key];
	}


	public function ver($args){

		if(USE_LANG){
			if($this->sessionGet("idioma")){
				$langOk = $this->cargarIdioma($this->sessionGet("idioma"));
			}else{
				$this->sessionSet("idioma",LANG);
				$langOk = $this->cargarIdioma();
			}

			if(!$langOk)die("Error, no se pudo cargar el archivo de idiomas, contacte al administrador del sistema.");
		}

		$this->cargarModulo();
		if(isset($this->vista) AND $this->vista == "null"){
			$this->Vista();
		}else{
			include(ESTRUCTURA);
		}
	}

	function cargarModulo()
	{


		$flag = false;
		$keys = array_keys($_GET);
		$vals = array_values($_GET);
		//$this->sessionNew($this->getData("SELECT * FROM usuarios WHERE id = '{$this->sessionGet('id')}' LIMIT 1",true)[0]);
			if(count($_GET)>0)
			{
				$idx = $keys[0];
				if(is_file(MODULOS.$idx.'.php')){
					define("MODULO",$idx);
					if(!$this->getPermiso()){$this->redirect("sinpermiso.php");}
					include(MODULOS.$idx.'.php');
					$flag = true;
					unset($_GET[$idx]);
				}elseif(is_file('veflat/'.MODULOS.$idx.'.php')){
					define("MODULO",$idx);
					if(!$this->getPermiso()){$this->redirect("sinpermiso.php");}
					include(MODULOS.$idx.'.php');
					$flag = true;
					unset($_GET[$idx]);
				}
			}elseif(count($_GET)==0){
				define("MODULO","inicio");
				if(!$this->getPermiso()){$this->redirect("sinpermiso.php");}
				$flag = true;
				include(INDEX_MODULO);
			}


			if(!$flag){
				define("MODULO","404");
				if(!$this->getPermiso()){$this->redirect("sinpermiso.php");}
				include('veflat/'.MODULOS.'404.php');
			}
	}


	public function Vista($arg = ""){
		if($arg != ""){
			$this->vista = $arg;
		}
		elseif(isset($this->vista)){
			if(is_file(VISTAS.$this->vista.'.php')) include(VISTAS.$this->vista.'.php');
			else echo "Error al cargar la vista establecida, el archivo no existe. ";
		}
		else{
			if(is_file(VISTAS.MODULO.'.php')){
				if(is_file(VISTAS.MODULO.'.php'))include(VISTAS.MODULO.'.php');
				else echo "Error al cargar la vista(default), el archivo no existe. ";
			}
			else{
				//include(VISTAS.'null.php');
				echo "Error al cargar vista, defina en el modulo: \$this->Vista(\"file\") en ".MODULO;
			}
		}
	}

	private function cargarIdioma($idioma = LANG){
		$archivo='veflat/lang/'.$idioma.'.lang';
		if(is_file($archivo)){
			$RUTA = $archivo;
		}else return false;
		if($file_handle = fopen($RUTA, 'r')){
			$this->lang=array();
			while (!feof($file_handle) ) {
				$fileLine = fgets($file_handle);
				$arrLine = explode('=', $fileLine);
				if(count($arrLine)>2){
					for ($i=1; $i < count($arrLine); $i++) {
						$arrLine[1].=$arrLine[$i];
					}
				}
				$this->lang[$arrLine[0]]=$arrLine[1];
			}
			return true;
		}else return false;
	}
}

?>
