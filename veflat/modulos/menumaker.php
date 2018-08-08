<?php
/**
 * Modulo de login creado por defecto
 * Veflat,C.A.
 * Jhoubert Rincon
 */

$this->vista("null");
//$this->logeado(true);

if($this->isUser(1)){



}

?>









<div class="row">
    <button onclick="showAdd();">Agregar elemento</button>
</div>
<hr>



<div class="row mdiv" id="add">
  Tipo de usuario:
  <select name="id_grupo">
    <option value="">Publico</option>
    <option value="99">Cualquier usuario</option>
    <?php

      $usrs = $this->getData("SELECT id,nombre FROM tipo_usuarios WHERE 1");
      foreach ($usrs as $id => $nombre) {
        echo "<option value=\"$id\">$nombre</option>";
      }

    ?>
  </select><br>

Nombre <input type="text" name="nombre" placeholder="Nombre del menú"> <br>
Link <input type="text" name="link" placeholder="?modulo o http://www.veflat.com"><br>
Icono <input type="text" name="icon" placeholder="fa-icon"><br>
<input type="submit">



</div>

<div class="row mdiv" id="">
</div>





<script>

function hideAllDivs(){
  var divs = document.getElementsByClassName('mdiv'), i;
  for (var i = 0; i < divs.length; i ++) {
      divs[i].style.display = 'none';
  }
}

function showAdd(){
    hideAllDivs();
    document.getElementById("add").style.display = 'block';
}


</script>
