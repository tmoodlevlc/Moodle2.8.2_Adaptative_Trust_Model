<?php
global $DB, $CFG;
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');
$opc = required_param('opc', PARAM_INT); 

if($opc==1){//Guardar
	$name = $_POST['nombre'];
	security_f5w5($name, $opc);
}else{
	if($opc==2){//Eliminar
		$id = required_param('id', PARAM_INT);
		security_f5w5($id, $opc);
	}else{
		if($opc==3){//Guardar Configuración
			$lstSecuryti= $DB->get_records_sql('SELECT * FROM {trust_f5w5_security}', array ($params=null), $limitfrom=0, $limitnum=0);
			foreach($lstSecuryti as $s){
				$id= $s->id;
				if(isset($_POST[$id])){//Ingresa si esta Activo
					security_f5w5($id, 3);
				}else{//Ingresa si esta desactivado
					security_f5w5($id, 4);
				}
			}
		}
	}
}
redirect(new moodle_url('/blocks/trust_model/F5W5_Security.php'));

