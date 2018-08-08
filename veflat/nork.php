<?php
/*
* 		Veflat,C.A.
*
* Clase :
* Autor: Jorge Osorio
* Veflat Framework v0.8
* Class ver. 0
*
*
*/

class Nork Extends Jhou {

	function hola($nombre){
		if($nombre=="Jorge"){
			return "Hola Jorge, esta es tu clase";
		}
		return "Hola <b>".$nombre."</b>, he aquí una funcion más de Nork.";
	}



	function makeMenu2($tipo_usuario,$ul,$id_ul,$li,$li_a,$ul_submenu){

		$select=$this->getData("SELECT `id`, `menu`, `url`, `titulo_modulo`, `modulo`, `icono`, `grupo` FROM `menu_nork` where grupo='".$tipo_usuario."'",true);
		$mmodulos = array();

		foreach ($select as $resultado) {
			$mmodulos[]=$resultado['id'];
		}

		echo '	<nav><ul class="'.$ul.'" id="'.$id_ul.'">';
		foreach ($mmodulos as $valor ) {

			$men=$this->getData("SELECT `id`, `menu`, `url`, `titulo_modulo`, `modulo`, `icono`, `grupo` FROM `menu_nork` WHERE id='".$valor."'",true);
			
			foreach ($men as $menus ) {
				$menus_titulo=$menus['titulo_modulo'];
				$menus_icono=$menus['icono'];
			}

			$row_cnt2=$this->execMysql("SELECT `id`, `menu`, `url`, `titulo_modulo`, `id_modulo`, modulo, `icono`, `grupo` FROM `submenu` WHERE id_modulo='".$valor."'");
			
			if ($row_cnt2>0) {
				//<img src="img/icons/packs/fugue/16x16/'.$menus_icono.'" alt="" height=16 width=16>
				
				if (MODULO==$menus_titulo) {
					$active="active";
				}
				echo '<li class="'.$li.' '.$active.'"><a class="'.$li_a.'" href="javascript:void(0);">'.$menus_titulo.'</a>';

			}else{
				//<img src="img/icons/packs/fugue/16x16/'.$menus_icono.'" alt="" height=16 width=16>
				echo '<li>
				<a href="index.php?'.$menus_titulo.'">'.$menus_titulo.'</a>';
			}

			//Validar Antes
			$row_cnt=$this->execMysql("SELECT `id`, `menu`, `url`, `titulo_modulo`, `id_modulo`, modulo, `icono`, `grupo` FROM `submenu` WHERE id_modulo='".$valor."'");
			if ($row_cnt>0) {
				echo '<ul class="'.$ul_submenu.'">';
				$subme=$this->getData("SELECT `id`, `menu`, `url`, `titulo_modulo`, `id_modulo`, modulo, `icono`, `grupo` FROM `submenu` WHERE id_modulo='".$valor."'",true);
				foreach ($subme as $smd) {
					if ($smd['titulo_modulo']!="") {
						echo '<li><a href="index.php?od='.$smd['titulo_modulo'].'"><span class="icon '.$smd['icono'].'"></span>'.$smd['titulo_modulo'].'</a></li>';
					}
				}
				echo '</ul></li>';
			}else{echo'</li>';}
		}
		echo '</ul></nav><!-- End of nav -->	';
	}


}
