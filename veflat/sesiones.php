<?php
/* 
* 		Veflat,C.A.
*
* Clase : SESIONES
* Autor: Jhoubert Rincon
* Veflat Framework v0.8
* Class ver. 0,5
* 
*
*/


Class Sesiones extends Nork{
	function sessionNew($data){
		foreach ($data as $element => $valor) {
			if(is_array($valor)){
				$this->sessionNew($valor);
			}	
			else{
				if($element != "clave" && !is_numeric($element))
				{
					$this->sessionSet($element,$valor);
				}
			}
		}
		return true;
	}

	function sessionSet($key,$val){
		$_SESSION[$key] = $val;
	}

	function isUser(){

		$args = func_get_args();
    	if(count($args)>0){
    		foreach ($args as $index => $arg) {
	        	if($arg == $this->sessionGet("id_grupo")){
	        		return true;
	        	}
	        	unset($args[$index]);
	    	}	
    	}else{
    		return $this->logeado();
    	}

    	return false;
	}

	function sessionGet($key){
		return (isset($_SESSION[$key])? $_SESSION[$key]:"");
	}

	function logeado($kick = false){
		if(isset($_SESSION['id']) && !is_null($_SESSION['id']))
			return true;
		else
			if($kick){$this->redirect("?login");}
			return false;
	}

	function sessionUnset($key = "all"){	
		if($key!="all")
			unset($_SESSION[$key]);
		else
			unset($_SESSION);
			session_destroy();
	}

}

?>