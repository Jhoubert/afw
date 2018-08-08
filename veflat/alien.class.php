<?php
/*
* 		Veflat,C.A.
*
* Clase : ALIEN
* Autor: Jhoubert Rincon
* Veflat Framework v0.8
* Class ver. 0.9
*
*
*/


class alienClass extends Sesiones{

	var $visibleName = true;
	var $formCols=1;
	var $editQuery=false;
	var $req;
	var $noReq;
	/*
	private function execPlugins($postData)
	{
		if (isset($_POST['plugin']))
		{
			$p = explode(",", $_POST['plugin']);
			foreach ($p as $key => $value) {
			include(PLUGIN.'/'.$value.'.php');
			unset($_POST['plugin']);
			}
		}
		return true;
	}

	public function procesarFiles($limite=0, $directorio = "", $formatos="all"){

		if(is_string($formatos) AND !$formatos=="all"){
			$pos = strpos($formatos, ",");
			if ($pos === false) {
				$formatos = array_map('trim', explode(',', $formatos));
			}
		}

		$dir = ($directorio == "" ? "images/" : $directorio);
		$im = array_keys($_FILES)[0];

		if(isset($_FILES[$im]) AND is_array($_FILES[$im]['name']))
		{
			$vals = array_keys($_FILES[$im]['name']);

			$limite = ($limite>0 ? (count($vals) > $limite ? $limite : count($vals)) : count($vals));

			for ($i=0; $i < $limite; $i++) {

				if($formatos == "all" OR $_FILES[$im]["extension"][$vals[$i]] == $formatos OR (is_array($formatos) AND in_array($_FILES[$im]["extension"][$vals[$i]], $formatos) ) ){
					$error = $_FILES[$im]["error"][$vals[$i]];
			    	if ($error == UPLOAD_ERR_OK) {
						if(!$_FILES[$im]['error'][$vals[$i]]){
							move_uploaded_file($_FILES[$im]['tmp_name'][$vals[$i]], $dir.$_FILES[$im]['name'][$vals[$i]]);
							//echo '<br>'.$vals[$i] . ' -  ' . $dir.$_FILES[$im]['name'][$vals[$i]] . '  | ';
							$img = $dir.$_FILES[$im]['name'][$vals[$i]];
							$_POST[$im.'_'.($i+1)] = $img;

						}

					}

				}
			}	unset($_FILES['imagen']);
		}elseif(isset($_FILES[$im]) AND !is_array($_FILES[$im]['name'])){
					if($formatos == "all" OR $_FILES[$im]["extension"] == $formatos OR (is_array($formatos) AND in_array($_FILES[$im]["extension"], $formatos) ) ){
					$error = $_FILES[$im]["error"];
			    	if ($error == UPLOAD_ERR_OK) {
						if(!$_FILES[$im]['error']){
							move_uploaded_file($_FILES[$im]['tmp_name'], $dir.$_FILES[$im]['name']);
							//echo '<br>'.$vals[$i] . ' -  ' . $dir.$_FILES[$im]['name'] . '  | ';
							$img = $dir.$_FILES[$im]['name'];
							$_POST[$im] = $img;
							unset($_FILES['imagen']);
						}

					}

				}
		}

	}


	public function csvToArray($file_directory,$delimiter = ',', $firstLineHeader = true){

		if(isset($_FILES[$file_directory])){
			if(!$_FILES[$file_directory]['error']){
				$RUTA = $_FILES[$file_directory]['tmp_name'];
			}else{
				return false;
			}
		}elseif(is_file($file_directory)){
			$RUTA = $file_directory;
		}else return false;

		$file_handle = fopen($RUTA, "r");
		$headers = !$firstLineHeader;
		$inddex = 0;
		while (!feof($file_handle) ) {
			$fileLine = fgets($file_handle);
			$arrayLine = explode($delimiter, $fileLine);
			if(!$headers){
				foreach ($arrayLine as $key => $value) {
					$headerArray[$key] = $value;
				}
				$headers = true;
			}else{
				if(!is_array($headerArray)){
					for ($i=0; $i < count($arrayLine); $i++) {
						$headerArray[$i]=$i;
					}
				}
				for ($i=0; $i < count($arrayLine); $i+=$up) {  //recorro el arrayLine (linea separada por , (coma))
					$up=0;  //defino cantidad de saltos en 0
					if(substr($arrayLine[$i],0,1)=="\""){
						do{
							$up++; 	//Aumento el indice auxiliar (para unir campos)
							$data[$inddex][$headerArray[$i]].=$arrayLine[$i+$up-1].","; // Cargo el arreglo

						}while(substr(trim($arrayLine[$up]),-1) != "\"" && $up<40); // Mientras no encuentre un final literario " (comilla doble) repito el ciclo
						$data[$inddex][$headerArray[$i]] = substr(substr($data[$inddex][$headerArray[$i]],1),0,-2); // Elimino la ultima , y las comillas.
					}else{
						$up++;
						$data[$inddex][$headerArray[$i]] = $arrayLine[$i];
					}
				}
			}
			$inddex++;
		}
		fclose($file_handle);

		if(isset($_POST[$dir]))unset($_POST[$dir]);
		if(isset($_FILE[$dir]))unset($_FILE[$dir]);
		if(isset($data) && is_array($data)){
			return $data;
		}else return false;
	}
*/
}

?>
