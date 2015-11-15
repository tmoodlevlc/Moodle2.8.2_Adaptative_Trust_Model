<?php
global $DB, $CFG, $USER;
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');
$opc = optional_param('opc', '',PARAM_TEXT);
if (isguestuser()) {
    redirect($CFG->wwwroot);
}

if($opc=='save'){
		if(isset($_POST['template'])){//Ingresa si esta Activo
			$template='true';
			$web_service='false';
		}else if(isset($_POST['web_service'])){//Ingresa si esta Activo
				$web_service='true';
				$template='false';
		}
		//Guardar en la base de datos
		save_config_institutional_f7w7($template, $web_service);
		//Redirigir a la pagina
		redirect(new moodle_url('/blocks/trust_model/F7W7_Institutional.php'));
		
}else if($opc=='connectWS'){
		//Parametros recibidos
		$location=$_POST['locationWS'];
		$metodo=$_POST['functionWS'];
		//Se conecta al WS
		$lstF7W7= webServiceF7W7($location, $metodo);
		//Actualizo la base de datos Moodle
		if(is_array($lstF7W7)){
			$lstF7W7_filter= webServiceF7W7_FilteredSave($lstF7W7); 	
			$msg =  '<div style="overflow:hidden;">';
			$msg.= '<label style="width: 25%; float:left;">'.get_string('webService', 'block_trust_model').':&nbsp;</label>';
			$msg.= '<label style="width: 75%; float:left;">'.get_string('institutional', 'block_trust_model').'</label>';
			$msg.= '<label style="width: 25%; float:left;">'.get_string('description', 'block_trust_model').':&nbsp;</label>';
			$msg.= '<label style="width: 75%; float:left;">'.get_string('f7w7webServiceDescription', 'block_trust_model').'</label>';
			$msg.= '<label style="width: 25%; float:left;">'.get_string('numberTrustExternal', 'block_trust_model').':&nbsp;</label>';
			$msg.= '<label style="width: 75%; float:left;">'.count($lstF7W7_filter).'</label>';
			$msg.= '</div>';
		}else{
			$msg= get_string('error', 'block_trust_model').'<br>'.$lstF7W7;
		}
		//Mostrar mensaje
		$url = new moodle_url('/blocks/trust_model/F7W7_Institutional.php?');
		$PAGE->set_url($url);
		$PAGE->set_pagelayout('standard');
		$PAGE->set_context(context_user::instance($USER->id));
		$tm = get_string('pluginname', 'block_trust_model');
		$PAGE->navbar->add($tm);
		$PAGE->navbar->add(get_string('institutional', 'block_trust_model'));
		$PAGE->set_title("{$SITE->shortname}: $tm");
		$PAGE->set_heading("{$SITE->shortname}: $tm");
		echo $OUTPUT->header();
		echo $OUTPUT->box_start();
		echo html_writer::start_tag('div', array('class' => 'mdl-align'));
		echo html_writer::tag('h4', get_string('pluginname', 'block_trust_model'));
		echo html_writer::end_tag('div');
		$mensaje = '<div>';
		$mensaje.= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional.php" >';
		$mensaje.= $msg;
		$mensaje.= '<button  type="submit">'.get_string('exit', 'block_trust_model').'</button>';
		$mensaje.= '</form></div>';
		echo $mensaje;
		echo $OUTPUT->box_end();
		echo $OUTPUT->footer();

}else{
	
	$urlImagen = new moodle_url('/blocks/trust_model/pix/items.png');
	$imagen= '<img src="'.$urlImagen. '"alt="" />';
	$url = new moodle_url('/blocks/trust_model/F7W7_Institutional.php');
	$PAGE->set_url($url);
	$PAGE->set_pagelayout('standard');
	$PAGE->set_context(context_user::instance($USER->id));
	$tm = get_string('pluginname', 'block_trust_model');
	$PAGE->navbar->add($tm);
	$PAGE->navbar->add(get_string('institutional', 'block_trust_model'));
	$PAGE->set_title("{$SITE->shortname}: $tm");
	$PAGE->set_heading("{$SITE->shortname}: $tm");
	echo $OUTPUT->header();
	echo $OUTPUT->box_start();
	echo html_writer::start_tag('div', array('class' => 'mdl-align'));
	echo html_writer::tag('h4', get_string('pluginname', 'block_trust_model'));
	echo html_writer::end_tag('div');

	$t = new html_table();
	$row = new html_table_row();
	$category;
	$tbl_category;	
	$institutional =  $DB->get_record_sql('SELECT * FROM {trust_f7w7_config} WHERE codigo IS NOT NULL');	
	
	if($institutional){//Si existe la configuración
	
		if($institutional->template=='true'){
			$cell1= '<input type="checkbox" name="template" onclick="check(template)" checked/><label>'.get_string('templateIns', 'block_trust_model').'</label>';
			$cell2= '<input type="checkbox" name="web_service" onclick="check(web_service)" /><label>'.get_string('serviceWebIns', 'block_trust_model').'</label>';
			//Enlazar un cuestionario para cada categoria/comunidad
			$lst_category =  $DB -> get_records('course_categories',  array('parent' => 0));	
			$tbl_category= new html_table();
			foreach($lst_category as $c){
				$row= new html_table_row();
				$cat1= '<div>'.$imagen.$c->name.'</div>';
				$cat2= '<div style="overflow:hidden;">';
				$cat2.='<label style="width: 50%; float:left;">'.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('id_cat' => 1, 'id' => $c->id)), get_string('student_questionnaire', 'block_trust_model')).'</label>';
				$cat2.='<label style="width: 50%;float:left;  ">'.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('id_cat' => 2, 'id' => $c->id)), get_string('teacher_questionnaire', 'block_trust_model')).'</label>';			
				$cat2.='<label style="width: 50%;float:left;  ">'.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('id_cat' => 3, 'id' => $c->id)), get_string('directivo_questionnaire', 'block_trust_model')).'</label>';
				$cat2.='<label style="width: 50%;float:left;  ">'.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('id_cat' => 4, 'id' => $c->id)), get_string('par_questionnaire', 'block_trust_model')).'</label>';
				$cat2.='</div>';
				$row->cells = array($cat1, $cat2);
				$tbl_category->data[] = $row;
			}
			$tbl_category=html_writer::table($tbl_category);
			
			$escala= '<div style="overflow:hidden;">';
			$escala.= '<div style="width: 20%; float:left;"><label  style="color: #2A5A5F; font-family: cursive;">'.get_string('questionscale_type', 'block_trust_model').' :</label></div>';
			$escala.= '<div style="width: 30%; float:left;">';
			$escala.= '<label><spam style="font-size: 12px;">0 '.get_string('escale0', 'block_trust_model').'</spam></label>';
			$escala.= '<label><spam style="font-size: 12px;">1 '.get_string('escale1', 'block_trust_model').'</spam></label>';
			$escala.= '<label><spam style="font-size: 12px;">2 '.get_string('escale2', 'block_trust_model').'</spam></label>';
			$escala.= '<label><spam style="font-size: 12px;">3 '.get_string('escale3', 'block_trust_model').'</spam></label>';
			$escala.= '<label><spam style="font-size: 12px;">4 '.get_string('escale4', 'block_trust_model').'</spam></label>';
			$escala.= '<label><spam style="font-size: 12px;">5 '.get_string('escale5', 'block_trust_model').'</spam></label></div>';
			$escala.=  '<div style="width: 20%; float:left;"><label style="color: #2A5A5F; font-family: cursive;">'.get_string('questionbinary_type', 'block_trust_model').' :</label></div>';
			$escala.= '<div style="width: 30%; float:left;">';
			$escala.= '<label><spam style="font-size: 12px;">1 '.get_string('binary1', 'block_trust_model').'</spam></label>';
			$escala.= '<label><spam style="font-size: 12px;">2 '.get_string('binary2', 'block_trust_model').'</spam></label>';
			$escala.= '</div></div>';
			
			$tbl_category.= $escala;
		
		}else if($institutional->web_service=='true'){
				
				$cell1= '<input type="checkbox" name="template" onclick="check(template)" /><label>'.get_string('templateIns', 'block_trust_model').'</label>';
				$cell2= '<input type="checkbox" name="web_service" onclick="check(web_service)" checked/><label>'.get_string('serviceWebIns', 'block_trust_model').'</label>';
				//Campos para conectarce al Web Service
				$tbl_category  = '<div>';
				$tbl_category .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional.php?opc=connectWS" >';
				$tbl_category .= '<p align=" justify">'.get_string('f7w7webService', 'block_trust_model').'</p>';
				$tbl_category.=  '<div style="overflow:hidden;">';
				$tbl_category.= '<div style="width: 20%; float:left;">'.get_string('webServiceUri', 'block_trust_model').':&nbsp;</div>';
				$tbl_category.= '<div style="width: 80%; float:left;"><input size="50" name="locationWS" type="text" placeholder="'.get_string('webServiceLocation', 'block_trust_model').'"  required></div>';
				$tbl_category.= '<div style="width: 20%; float:left;">'.get_string('webServiceFunction', 'block_trust_model').':&nbsp;</div>';
				$tbl_category.= '<div style="width: 80%; float:left;"><input size="50" name="functionWS" type="text" placeholder="'.get_string('webServiceFunction', 'block_trust_model').'"  required></div>';
				$tbl_category.= '</div>';
				$tbl_category .= '<input id="id_submitbutton"  type="submit" value="'.get_string('connectServiceWeb', 'block_trust_model').'">';
				$tbl_category .= '</form></div>';
		}
		//Mostrar elementos
		$row->cells = array($cell1, $cell2);
		$t->data[] = $row;
		$t=html_writer::table($t);
		$check  = '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional.php?opc=save">';
		$check  .= $t;
		$check  .= '<button type="submit" title="'.get_string('saveConfig', 'block_trust_model').'">'.get_string('save', 'block_trust_model').'</button>';
		$check  .= '</form>';
		echo $check;
		echo $tbl_category;
		
	}else{//No configura todavia el admin
		$cell1= '<input type="checkbox" name="template" onclick="check(template)" checked/><label>'.get_string('templateIns', 'block_trust_model').'</label>';
		$cell2= '<input type="checkbox" name="web_service" onclick="check(web_service)" /><label>'.get_string('serviceWebIns', 'block_trust_model').'</label>';		
		$row->cells = array($cell1, $cell2);
		$t->data[] = $row;
		$t=html_writer::table($t);
		$check  = '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional.php?opc=save">';
		$check  .= $t;
		$check  .= '<button type="submit" title="'.get_string('saveConfig', 'block_trust_model').'">'.get_string('save', 'block_trust_model').'</button>';
		$check  .= '</form>';
		echo $check;
	}

	echo $OUTPUT->box_end();
	echo $OUTPUT->footer();
}
?>
<script language="JavaScript" type="text/JavaScript">
function check(resp){
	if(resp.name== 'template'){
		resp.checked =true;
		var x = document.getElementsByName("web_service")[0];
		x.checked =false;
		
	}else{
		resp.checked =true;
		var x = document.getElementsByName("template")[0];
		x.checked =false;
	}
		
}
</script>
	