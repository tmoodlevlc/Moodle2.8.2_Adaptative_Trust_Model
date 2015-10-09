<?php
//Parametros de Conexion - Base de datos Tmoodle
class configBD{
	public static function getBBDDServer() {
		return 'servidorBD';
	}
	public static function getBBDDName(){
		return  'nombreBD'; 
	}
	public static function getBBDDUser(){
		return 'userBD';
	} 
	public static function getBBDDPassword(){
		return 'claveBD';  
	} 
}
//Conectarce a la BD
class conecction{
	public function executeQuery($sql){
		$con = mysql_connect(configBD::getBBDDServer(), configBD::getBBDDUser(),configBD::getBBDDPassword());
		if (!$con){ 
			die('Could not connect: ' . mysql_error());
		}
		mysql_select_db(configBD::getBBDDName(), $con) or die(mysql_error());
		$result = mysql_query($sql) or die(mysql_error());
		mysql_close($con);
		return $result;
	}
}
?>