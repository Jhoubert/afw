<?php
/*
* 		Veflat,C.A.
*
* Clase :
* Autor: Jhoubert Rincon
* Veflat Framework v0.8
* Class ver. 0
*
*
*/

class Jhou {

	public function arrayToCsv($file_directory, $res, $delimiter = ',', $firstLineHeader = true){
		if($hcsv = fopen($file_directory,"w+")){
			if(is_array($res)) foreach ($res as $resline) {
						if(is_array($resline))foreach ($resline as &$str) {
								$haydelimitador = strpos($str, $delimiter);
								if ($haydelimitador === false) {
									} else {
									$str='"'.$str.'"';
								}
						}
						fwrite($hcsv,implode($resline,$delimiter)."\n");
					}else{
						return false;
					}
					fclose($hcsv);
				}else {
					return false;
				}
		return true;
	}
}
