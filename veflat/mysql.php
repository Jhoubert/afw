<?php

/* 
* 		Veflat, C.A
*
* Clase : MySql
* Autor: Jhoubert Rincon
* Veflat Framework v0.4
* 
*
*/

class Vmysql extends alienClass{
	public $conexion;
	public $id_insertado;
	public $mysql_error;
	public $mysql_errorno;
	public $omitirEnBlanco;
	
	function conexion($SERVER = NULL,$USER = NULL,$PW = NULL,$DB=NULL)
	{
		if(!$this->conexion = mysqli_connect(
			($SERVER!=NULL?$SERVER:MySQL_S),
			($USER!=NULL?$USER:MySQL_U),
			($PW!=NULL?$PW:MySQL_P)))
		{
			die("Ha ocurrido un error al conectar a la base de datos.");
		}
		$this->conexion->select_db(($DB!=NULL?$DB:MySQL_DB));
		$this->execMysql("SET CHAetDataRACTER SET utf8");

		$_POST = $this->recursiveScape($_POST);
		$_GET = $this->recursiveScape($_GET);
	}

	 function recursiveScape($obj){
		if(is_array($obj)){
			foreach ($obj as $key => $value) {
				if(is_array($value)){
					$obj[$key] = $this->recursiveScape($value);
				}else{
					$obj[$key] = $this->conexion->real_escape_string($value);
				}
			}
			return $obj;
		}else{
			return $this->conexion->real_escape_string($obj);
		}
	}

	function login($username,$pw){  

		$pw = md5(SECURITY_SALT.$pw);
		if($user= $this->getData("SELECT * FROM usuarios WHERE (usuario = '$username' OR correo = '$username') AND clave='$pw' LIMIT 1",true)){
			$this->sessionNew($user);
			$upd = "UPDATE `usuarios` SET ultima_vez = '".$this->hora('mysql')."' WHERE id = '".$this->sessionGet('id')."'";
			if($this->conexion->query($upd) === TRUE){
				return true;
			}else{
				error_log('['.$this->hora().']['.MODULO.' login]:'.$upd.'['.$this->conexion->error.']', 3, CORE_LOG);
				return false;
			}
		}else

		return false;
	}

	function insertData($data,$tabla){ 

		$campos = "";
		$keys = array_keys($data);
		$campos_completos =false;
		$valores = (!is_array($data[$keys[0]])?'(':'');
		foreach ($data as $elemento => $valor) 
		{	
			if(is_array($valor))
			{
				$valores .='(';
				foreach ($valor as $a_campos => $a_valores) {
					if(!$campos_completos){$campos .= $a_campos.',';}
					$valores.='\''.$a_valores.'\',';
				}
				if(!$campos_completos){$campos = substr($campos, 0, -1).'';}
				$campos_completos = true;
				$valores = substr($valores, 0, -1).',\''.$this->sessionGet('id').'\'),';
			}else
			{
				$campos.=$elemento.',';
				$valores.='\''.$valor.'\',';
			}
		}
		$valores = substr($valores, 0, -1).(!is_array($data[$keys[0]])?',\''.$this->sessionGet('id').'\')':'');
		$campos = substr($campos, 0, -1);
		$insert = "INSERT INTO `$tabla` ($campos,iduser)VALUES $valores;";
		if($this->conexion->query($insert) === TRUE){
			$this->id_insertado = $this->conexion->insert_id."";
			//die($this->id_insertado);
			return true;
		}else{
			$this->mysql_error = $this->conexion->error;
			error_log('['.$this->hora().']['.$this->getIp().']['.MODULO.' insertData]:'.$insert.'['.$this->conexion->error.']'."
", 3, MYSQL_LOG);
			return false;
		}
	}

	function mysqlExist($qry){
		if ($result = $this->conexion->query($qry)) 
		{
			if($result->num_rows > 0)
			{
				return true;	
			}
		}
		return false;
	}

	function getNumRows($qry){
		if ($result = $this->conexion->query($qry)) 
		{
				return $result->num_rows;
		}
		return false;
	}

	function execMysql($qry){
		if(!$this->conexion->query($qry)) return false;

		return $this->conexion->affected_rows;
		
	}

	function getData($qry,$allField=false){

		if ($result = $this->conexion->query($qry))
		{

			if($result->num_rows > 0)
			{
				$akey = 0;
				while($row = ($allField?$result->fetch_assoc():$result->fetch_row()))
				{
					$keys = array_keys($row);
					if($allField){
						$index = $akey++;
					}else{
						if(isset($row[$keys[0]]))
							$index = $row[$keys[0]];
						//unset($row[0]);
					}

					if($allField)
					{
						foreach ($row as $key => $value) {
							$rec[$key] = $value;
						}
					}else
					{
						$rec = $row[1];
					}
					$data[$index] = $rec;
				}
			}else
			{
				return false;
			}
		    $result->close();
		}else
		{
			if(method_exists($this, "hora")){
				
				error_log('['.$this->hora().']['.$this->getIp().']['.MODULO.' getData]:'.$qry.'['.$this->conexion->error.']'."
					", 3, MYSQL_LOG);

			}else{
				
				error_log('['.date('Y-m-d H:i:s').'][VIRTUAL]['.MODULO.' getData]:'.$qry.'['.$this->conexion->error.']'."
					", 3, MYSQL_LOG);
			}
			return false;
		}
		return $data;
	}

	function getConfig($params,$useDefine=false){

		$parametros = implode("AND ", $params); 

		$cfg = $this->getData("SELECT name,value FROM config WHERE activo=1 ".$parametros);
		foreach ($cfg as $newVar => $valor) {
			if(!$useDefine)$this->$newVar=$valor;
			else define($newVar,$valor);
		}
		return true;
	}

	function updateData($fields, $tabla, $id){
		$usec = "";

		foreach ($fields as $camp => $val) {
				
			if(is_array($this->omitirEnBlanco)){
				if(in_array($camp, $this->omitirEnBlanco))
					$usec .=  " $camp = $camp,";
				else
				$usec .= " $camp = '$val',";		
			}else
				$usec .= " $camp = '$val',";
		}

		$usec = substr($usec, 0,-1);
		//$upd = "UPDATE $tabla SET $usec WHERE id = '$id' ".($this->sessionGet('id_grupo')!=1 ? "AND iduser = '".$this->sessionGet('id')."'" : "")." LIMIT 1";
		
		$upd = "UPDATE $tabla SET $usec WHERE id = '$id' LIMIT 1";
	
	//echo $upd.'<br>';
		if($this->conexion->query($upd) === TRUE){
			return true;
		}else
			$this->mysql_error=$this->conexion->error;
			$this->mysql_errorno=$this->conexion->errno;
			return false;
	}

	function getPermiso(){
		$p_g = true;// $this->getData("SELECT modulo,nivel FROM privilegios_grupo WHERE modulo = '".MODULO."' AND id_grupo='".$this->sessionGet('id_grupo')."'");
		if(!$p_g)
		{
			return false;	
		}
		return true;
	}



	function getNotificacion(){  
		//return $this->getData("SELECT id,texto FROM notificaciones WHERE id_user = '".$this->sessionGet('iduser')."'"); 
	}

	function getMsj(){  
		//return $this->getData("SELECT msge,iduser FROM mensajes WHERE id_user = '".$this->sessionGet('iduser')."'"); 
	}


	function sendNotificacion($data,$usuario,$type){  
		//return $this->insertData(""); 
	}

	function sendMsj($data,$usuario){

		//return $this->insertData(""); 
	}

	
}
