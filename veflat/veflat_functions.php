<?php

/*
*       Veflat, C.A
*
* Clase : Funciones
* Autor: 
* Framework v0.7
*
*
*/

class Helpers extends Vmysql{

    private $paginacion = false;
    function hora($format = "",$timezone="")
    {
        date_default_timezone_set(($timezone==""?TIME_ZONE_DEFAULT:$timezone));

        if($format==""){$format=HORA_DEFAULT;}
        switch (strtolower($format)) {
            case 'mysql':
                $format = "Y-m-d H:i:s";
                break;
            case '12h':
                $format = "h:i:sa";
                break;
            case '24h':
                $format = "H:i:s";
                break;
            case 'min':
                $format = "H:i";
                break;
            case 'sec':
                $format = "H:i:s";
                break;
            default:
                $format = $format;
                break;
        }
        return date($format);
    }

    function redirect($url,$modo="php")
    {   
        if($modo == "php")
        {header("location:".$url);}
        elseif($modo == "js")
        {echo '<script>window.location.href="'.$url.'";</script>';}
    }

    function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])){
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else{
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }


    function isAjax(){
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'){
            return true; 
        }else{
            return false;
        }
    }

    function arrayFormat($array, $reglas=""){

        if(!is_array($array)) return false;
        foreach ($array as $key => $row) {          
            foreach ($row as $name => $valor) {
                if(isset($reglas[$name]['formato'])){

                            if(isset($reglas[$name][$valor])){
                                $fieldReplace = $reglas[$name][$valor];
                            }else{
                                $fieldReplace = $valor;
                            }

                    $elemento = str_replace('{field}', $fieldReplace, $reglas[$name]['formato']);

                    preg_match_all("/\{([a-zA-Z0-9]+)\}/", $reglas[$name]['formato'],$x);
                    if(is_array($x))
                    foreach ($x[1] as $pregValu) {
                        if(isset($row[$pregValu])){
                            $elemento = str_replace('{'.$pregValu.'}', $row[$pregValu], $elemento);
                        }
                    }
                }
                elseif(isset($reglas[$name][$valor])){

                    $elemento = $reglas[$name][$valor];

                    $elemento = str_replace('{field}', $elemento, $reglas[$name][$valor]);
                    preg_match_all("/\{([a-zA-Z0-9]+)\}/", $reglas[$name][$valor],$x);
                    if(is_array($x))
                    foreach ($x[1] as $pregValu) {
                        if(isset($row[$pregValu])){
                            $elemento = str_replace('{'.$pregValu.'}', $row[$pregValu], $elemento);
                        }

                    }

                }else $elemento = $valor;

                if(isset($reglas[$name]['_callback'])){
                    $elemento = $reglas[$name]['_callback']($elemento,$row);
                }
                $array[$key][$name] = $elemento;
            }
        }
        return $array;
    }

function arrayTable($array, $titulos=false, $camb="", $class="",$idTable="example"){

    $array = $this->arrayFormat($array, $camb);

        if(!is_array($array)) return false;
        $tabla = '<table id="'.$idTable.'" class="table display responsive nowrap '.$class.'" width="100%">';
        if($titulos){
            $auxKey = array_keys($array);
            $keys = array_keys($array[$auxKey[0]]);

            if(is_array($camb)){
                foreach ($camb as $indx => $value) {
                    if($d = array_search($indx, $keys) AND isset($value['titulo'])){
                        $keys[$d] = ucfirst($value['titulo']);
                    }
                }
            }

            $tabla .= ' <thead><tr>';
            foreach ($keys as $kkey) {
                $tabla .= '<th>'.ucfirst($kkey).'</th>';
            }
            $tabla .= '</tr> </thead>';
        }
        
        foreach ($array as $key => $row) {          
            $myTr = '<tr>';
            foreach ($row as $name => $valor) {
                $myTr .= '<td>'.$valor.'</td>';
            }
            
            $myTr .= '</tr>';
            
            $tabla .= $myTr;
        
        }

        if(isset($camb['_limitar'])) $tabla = str_replace('id="example', '', $tabla);
        


        if($this->paginacion){

            $_GET['pag'] = $this->limit;
            $_GET['pag'] = $this->pag-1; // change page number
            $qs = str_replace(MODULO.'=',MODULO,http_build_query($_GET));
            $_GET['pag'] = $this->pag+1; // change page number
            $qs2 = str_replace(MODULO.'=',MODULO,http_build_query($_GET));

            $anterior = ($this->pag > 1 ? '<a href="?'.$qs.'" class="btn btn-success"><</a>':'');
            $tabla.='<tr><td colspan="'.count($keys).'" align="center">'.$anterior.'<a href="?'.$qs2.'" class="btn btn-success">></a></td></tr>';
        }

        $tabla .= '</table>';

        return $tabla;
    }

    
    function paginar($sql,$reglas,$pagina=1,$order="",$filas=DEFAULT_LIMIT_PAGINATION){

        if( $pagina < 1 ) $pagina = 1;
        $total = $this->getNumRows($sql);
        $desde = $filas*($pagina-1);

        if($desde > $total){    
            $pagina = ceil($total/$filas);
            $desde = $filas*($pagina-1);
        }

        if($desde < 1){
            $pagina = 1;    
            $desde = 0;
        }
        
        $sql .= (!empty($order) ? " ORDER BY $order ": '');
        $sql .= " LIMIT ".$desde.",".$filas." ";
        

        if($result = $this->getData($sql,FULLROWS)){
            $result =  array_values($result);
            foreach ($result as $key => $value) {
                //$result[$key]=array_values($value);
            }
            $hasta = ($desde+$filas);
            $filas2 = count($result);
            
            if($filas > $filas2){
                $hasta = $hasta-($filas-$filas2-1);
                $filas = $filas2;
            }
            
            $data = array('total'=>$total, 'pagina'=>$pagina,'desde'=>$desde+1,'hasta'=>$hasta,'filas'=>$filas,'data'=>$this->arrayFormat($result,$reglas));
            unset($result);
        }else{
            $data = array('total'=>0, 'pagina'=>0,'desde'=>$desde,'hasta'=>DEFAULT_LIMIT_PAGINATION,'filas'=>DEFAULT_LIMIT_PAGINATION,'data'=>'0');
        }

        return $data;
    }


    function paginacion($limit = DEFAULT_LIMIT_PAGINATION){
            $this->pag = (isset($_GET['pag']) ? $_GET['pag'] : 1 );
            $this->limit   = (isset($_GET['rang']) ? $_GET['rang'] : $limit-1);
            
            $ini = ($this->pag-1) * $this->limit;
            $this->paginacion=true;

            return $ini.', '.$this->limit;
    }

    function makeMenu($formato = ""){
        $menu = "";

        $idusr=$this->sessionGet("id_grupo");
        $xx = "";
        $Mdata= $this->getData("SELECT id,link,icon,nombre FROM menu WHERE grupo = '$idusr' OR grupo = '99' ORDER BY orden ASC",true);
        //echo "SELECT id,link,icon,nombre FROM menu WHERE grupo = '$idusr' OR grupo = '99' ORDER BY orden ASC";
        foreach ($Mdata as $key => $value) {
            $xx = $formato;
            $link = "";
            foreach ($value as $k2 => $v) {

                $link = substr($value['link'],1);
                if($link == MODULO){ 
                    $xx = str_replace('{active}','active',$xx);
                }

                $xx = str_replace('{'.$k2.'}',$v,$xx);
            }   
            $xx = str_replace('{active}',($link == 'inicio' ? (MODULO=='index' ? 'active' : '') : ''),$xx);
            $menu .=$xx;
        }
        echo $menu;
    }



     function arrayToCsv($file_directory, $res, $delimiter = ',', $firstLineHeader = true){
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

                        

function email_simple($coreo,$mensaje,$titulo,$asunto,$oculto){

   $email_text=$mensaje;
   $email_titulo=$titulo;
   $mail_Subject2 = $asunto; 
   $mail_To2      = $coreo;


$mensaje='
'.CSS_EMAIL.'
     <!-- HEADER -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" align="center">
            <!-- HIDDEN PREHEADER TEXT -->
            <div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;">
                
            </div>
            <table border="0" cellpadding="0" cellspacing="0" width="500" class="wrapper">
                <!-- LOGO/PREHEADER TEXT -->
                <tr>
                    <td style="padding: 20px 0px 30px 0px;" class="logo" align="center">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td bgcolor="#ffffff" width="100" align="center"><a href="#" target="_blank"><img alt="Logo" src="'.RUTA_LOGO.'"  style="display: block; font-family: Helvetica, Arial, sans-serif; color: #666666; font-size: 16px;" border="0"></a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!-- ONE COLUMN SECTION -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 15px 15px 15px 15px;" class="section-padding">
            <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                <tr>
                    <td>
                        <!-- COPY -->
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td align="center" style="font-size: 32px; font-family: Helvetica, Arial, sans-serif; color: #333333; padding-top: 30px;" class="padding-copy">'.$email_titulo.'</td>
                            </tr>
                            <tr>
                                <td align="left" style="padding: 20px 0 0 0; font-size: 16px; line-height: 25px; font-family: Helvetica, Arial, sans-serif; color: #666666;" class="padding-copy">

                            '.$email_text.' <br>
                   </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- ONE COLUMN SECTION -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 15px 15px 15px 15px;" class="section-padding">
            <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td>
                                    <!-- COPY -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td align="left" style="padding: 0 0 0 0; font-size: 14px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color: #aaaaaa; font-style: italic;" class="padding-copy">
                                           '.EMAIL_GRACIAS.'</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" align="center" style="padding: 15px 15px 15px 15px;" class="section-padding">
            <table border="0" cellpadding="0" cellspacing="0" width="500" class="responsive-table">
                <tr>
                    <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                               
                            <tr>
                                <td align="center">
                                    <!-- BULLETPROOF BUTTON -->
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mobile-button-container">
                                        <tr>
                                            <td align="center" style="padding: 25px 0 0 0;" class="padding-copy">
                                                <table border="0" cellspacing="0" cellpadding="0" class="responsive-table">
                                                    <tr>
                                                        <td align="center"></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" align="center">
            <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
                <tr>
                    <td style="padding: 70px 0px 20px 0px;" align="center">
                        <!-- UNSUBSCRIBE COPY -->
                        <table width="500" border="0" cellspacing="0" cellpadding="0" align="center" class="responsive-table">
                            <tr>
                                <td align="center" style="font-size: 12px; line-height: 18px; font-family: Helvetica, Arial, sans-serif; color:#666666;">
                                    <span class="appleFooter" style="color:#666666;">'.NOMBRE_COMPANY.'</span><br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>';   
  
$headers = "From: ".NOMBRE_EMAIL." <".EMAIL.">\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";                    
     
    include_once"vendors/PHPMailer/class.phpmailer.php";
    include_once("vendors/PHPMailer/class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();
$body             = $mensaje;
$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "veflat.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
$mail->Username   = "notificaciones@systenergy.com";  // GMAIL username
$mail->Password   = "asdsad123213";            // GMAIL password

$mail->SetFrom('notificaciones@systenergy.com', ''.NOMBRE_EMAIL.'');
$mail->Subject    = "".$mail_Subject2."";
//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
$mail->MsgHTML($body);
$address = "".$mail_To2."";
if ($oculto!="") {
$address2 = "".$oculto."";
$mail->AddBCC($address2);
}
$mail->AddAddress($address, "");

//$mail->AddAttachment("images/phpmailer.gif");      // attachment
//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  //echo "Message sent jeje!";
//$phpMailer->SmtpClose();

}









                                            }







function createAjaxTable($tableID,$source,$fields){
?>
<div align="center">
    <div class="row">
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-3">
                    <select id="<?=$tableID;?>_filas" name="example_length" aria-controls="example" class="form-control input-sm select2" onChange="setFilas(this.value,this.id);">
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="250">250</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 text-right">
            <div class="row">
                <div class="col-lg-5"><i align="right" id="<?=$tableID;?>_loading" style="display:none" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
                <div class="col-lg-7">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <span class="fa fa-search" aria-hidden="true"></span>
                        </div>
                        <input class="form-control" placeholder="Buscar" type="text" id="<?=$tableID;?>_filter" onKeyUp="setFilter(this.value,this.id);">
                    </div>
                </div>
            </div>
        </div>


    </div>
<br>

<table class="table table-hover mg-b-0 table-condensed" id="<?=$tableID;?>" data-source="<?=$source;?>">
    <thead>
        <tr>
        <?php
            if(is_array($fields))foreach($fields as $v){
                echo '<th>'.$v.'</th>';     
            }
        ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="8" align="center"><i id="" class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><br>Cargando...</td>
        </tr>
    </tbody>
</table>
<br>

    <nav class="text-center">
        <ul class="pagination" id="<?=$tableID;?>_pag">
        <i>...</i>
        </ul>
    </nav>

</div>

<?php
}

}
