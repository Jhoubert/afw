<?php
/** 
 * Modulo de login creado por defecto  
 * Veflat,C.A.  
 * Jhoubert Rincon
 */
if(isset($_POST)){
	if(isset($_POST['user']) AND isset($_POST['pass'])){
		if($this->login($_POST['user'],$_POST['pass'])){
			$this->redirect("index.php?inicio");
		}else $this->redirect(LOGIN);
	}
}else
	$this->redirect(LOGIN);

if(!$this->logeado()){$this->redirect(LOGIN);}	

?>