<?php

$this->Vista("null");
$FILE ="files/paises0.csv";
$this->conexion->set_charset("utf8");

alien::$qwerty['paises'] = 0;



function ejecutarFuncion($elemento,$row){
  alien::$qwerty['paises']++;
  if($row['continente']=="Asia"){
      return $elemento.' FUCK CHINOS!';
  }
  return $elemento.'';
}



$camb = array('nombre'=>array('formato'=>'+ {field} +','_callback'=>'ejecutarFuncion'),);


echo $this->arrayTable($this->getData("SELECT * FROM paises WHERE 1 LIMIT 12",FULLROWS),1,$camb);


echo alien::$qwerty['paises'];


?>
