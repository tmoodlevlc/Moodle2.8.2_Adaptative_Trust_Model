<?php
require_once("../../config.php");
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');

$courseid = required_param('c', PARAM_INT);
$userid = required_param('user', PARAM_INT);
$rol = required_param('rol', PARAM_TEXT);
$opc= required_param('opc', PARAM_INT);
if($opc==1){
	direct_experience_validation_user_f1w1($courseid, $userid, $rol);
	redirect(new moodle_url('/blocks/trust_model/F1W1_ValidationConfig.php', array('c' => $courseid)));
}else{
	if($opc==2){
			$action= required_param('act', PARAM_INT);
			direct_experience_validation_f1w1($courseid, $userid, $rol, $action);
			redirect(new moodle_url('/blocks/trust_model/F1W1_Validation.php', array('c' => $courseid)));
	}
}

