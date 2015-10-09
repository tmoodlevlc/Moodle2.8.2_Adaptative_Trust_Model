<?php
global $DB, $CFG, $USER;
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');

$id_cat = required_param('id_cat', PARAM_INT);//A que clasificación pertenece 1:cuestionario estudiante|2:docente|3:directivo|4:par
$id = required_param('id', PARAM_INT);//id de la Categoria
$opc = optional_param('opc', '',PARAM_TEXT);


if (isguestuser()) {
    redirect($CFG->wwwroot);
}

if($opc=='save' || $opc=='delete' || $opc=='saveAll' || $opc=='dir' || $opc=='pairs'){

	if($opc=='save'){
		$question = $_POST['question'];
		if(isset($_POST['scale_type'])){
			$type='scale_type';
		}else if(isset($_POST['binary_type'])) {
			$type='binary_type';
		}
		save_template_question_f7w7($question,$id_cat, $id, $type);
	}else if ($opc=='delete'){
		$id_q = required_param('id_q', PARAM_INT);
		delete_template_question_f7w7($id_q);
	}else if($opc=='saveAll'){
		$lstquestions =  $DB -> get_records('trust_f7w7_t_questions',  array ('category'=>$id_cat));
		foreach($lstquestions as $q){
			$name=$q->id;
			$textoquestions= $_POST[$name];
			update_template_question_f7w7($q->id, $textoquestions);
		}
	}else if($opc=='dir'){
		$user = required_param('user', PARAM_INT);
		$categories= required_param('categories', PARAM_INT);
		selec_directive_f7w7($categories, $user);
	}else if($opc=='pairs'){
		$subcategories= required_param('subcategories', PARAM_INT);
		combination_pairs_f7w7($id, $subcategories);
	}
	redirect(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php',array('id_cat' => $id_cat, 'id' => $id)));
}else{
	$url = new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php',array('id_cat' => $id_cat, 'id' => $id));
	$PAGE->set_url($url);
	$urlImagen = new moodle_url('/blocks/trust_model/pix/items.png');
	$imagen= '<img src="'.$urlImagen. '"alt="" />';
	
	// Disable message notification popups while the user is viewing their messages
	$PAGE->set_pagelayout('standard');
	$PAGE->set_context(context_user::instance($USER->id));
	$tm = get_string('pluginname', 'block_trust_model');
	$PAGE->navbar->add($tm);
	$PAGE->navbar->add(get_string('institutional', 'block_trust_model'));
	$PAGE->navbar->add(get_string('templateIns', 'block_trust_model'));
	$PAGE->set_title("{$SITE->shortname}: $tm");
	$PAGE->set_heading("{$SITE->shortname}: $tm");
	//now the page contents
	echo $OUTPUT->header();
	echo $OUTPUT->box_start();

	//Mostrar Titulo
	if($id_cat==1){//Cuestionario del estudiante
		$title= get_string('student_questionnaire', 'block_trust_model');	
	}else if($id_cat==2){//Cuestionario del docente
		$title= get_string('teacher_questionnaire', 'block_trust_model');
	}else if($id_cat==3){//Cuestionario del directivo
		$title= get_string('directivo_questionnaire', 'block_trust_model');
	}else if($id_cat==4){//Cuestionario de pares
		$title= get_string('par_questionnaire', 'block_trust_model');
	}
	
	echo html_writer::start_tag('div', array('class' => 'mdl-align'));
	echo html_writer::tag('h4', $title);
	echo html_writer::end_tag('div');
	
	
	//Cuestionario del directivo
	if($id_cat==3){
		//El administrador eliga un directivo por cada categoria/comunidad
		$lst_category =  $DB -> get_records('course_categories',  array('parent' => 0));
		//Recorro las categorias
		echo '<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('selec_directive', 'block_trust_model').'</p>';
		$table = new html_table();
		foreach($lst_category as $c){
			$row = new html_table_row();
			$cell1= '<label>'.$c->name.'</label>';
			//Obtengo los profesores de cada categoria
			$roleTeacher =  $DB -> get_record('role',  array ('shortname'=>'editingteacher'));
			$lstTeacher= $DB->get_records_sql("SELECT mdl_user.id as userid, mdl_user.firstname, mdl_user.lastname, mdl_course.id as courseid
											FROM mdl_user
											INNER JOIN mdl_role_assignments ON mdl_user.id = mdl_role_assignments.userid 
											INNER JOIN mdl_role ON mdl_role.id = mdl_role_assignments.roleid
											INNER JOIN mdl_context ON mdl_context.id = mdl_role_assignments.contextid
											INNER JOIN mdl_course ON mdl_course.id = mdl_context.instanceid
											INNER JOIN mdl_course_categories ON mdl_course_categories.id = mdl_course.category
											WHERE mdl_role.id = ? AND mdl_course_categories.path LIKE '%$c->path%' GROUP BY mdl_user.id ORDER BY mdl_user.lastname, mdl_user.firstname  ASC ", 
											array($roleTeacher->id));
			//Si existe un directivo ya seleccionado
			$directiveActual =  $DB -> get_record('trust_f7w7_t_dir_sel',  array('categories_id'=> $c->id));
			$row = new html_table_row();
			$cell2= '<div style="overflow:hidden;">';
			foreach($lstTeacher as $t){//Recorro los profesores
				$cell2.='<label style="width: 50%; float:left;">'.$imagen.$t->lastname.' '.$t->firstname.'</label>';
				if($directiveActual){
					if ($directiveActual->user_id==$t->userid){
						$cell2.='<label style="width: 50%;float:left;">'.get_string('directive', 'block_trust_model').'</label>';			
					}else{
						$cell2.='<label style="width: 50%;float:left;  ">'.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('id_cat' => $id_cat, 'id' => $id, 'opc' => 'dir','categories' => $c->id, 'user' => $t->userid)), get_string('select', 'block_trust_model')).'</label>';			
					}
				}else{
					$cell2.='<label style="width: 50%;float:left;  ">'.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('id_cat' => $id_cat, 'id' => $id, 'opc' => 'dir', 'categories' => $c->id, 'user' => $t->userid)), get_string('select', 'block_trust_model')).'</label>';			
				}
			}
			$cell2.= '</div>';
			$row->cells = array($cell1, $cell2);
			$table->data[] = $row;
		}
		echo html_writer::table($table);
	}

	
	
	//Cuestionario Pares
	if($id_cat==4){
		//Obtener las subcategorias
		$lst_category =  $DB -> get_records('course_categories',  array('parent' => $id));
		if($lst_category){
			//Recorro las categorias
			echo '<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('sub_category', 'block_trust_model').'</p>';
			$table = new html_table();
			foreach($lst_category as $c){
				$row = new html_table_row();
				$cell1= '<label>'.$imagen.$c->name.'</label>';
				//Si ya se realizo la combinación
				if(!$DB->record_exists('trust_f7w7_t_pairs_comb', array('categories' => $id,'sub_categories' => $c->id))){
					$cell2='<label style="width: 50%;float:left;  ">'.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('id_cat' => $id_cat, 'id' => $id, 'opc' => 'pairs','subcategories' => $c->id)), get_string('activate_combination_par', 'block_trust_model')).'</label>';			
				}else{
					$cell2= get_string('combination_pairs_exit', 'block_trust_model');			
				}
				$row->cells = array($cell1, $cell2);
				$table->data[] = $row;
			}
			echo html_writer::table($table);
		
		}
	}
	
	
	
	$escala=  '<div style="overflow:hidden;">';
	$escala.= '<div style="width: 20%; float:left;"><label class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('scale_type', 'block_trust_model').' :</label></div>';
	$escala.= '<div style="width: 30%; float:left;">';
	$escala.= '<label><spam style="font-size: 12px;">0 '.get_string('escale0', 'block_trust_model').'</spam></label>';
	$escala.= '<label><spam style="font-size: 12px;">1 '.get_string('escale1', 'block_trust_model').'</spam></label>';
	$escala.= '<label><spam style="font-size: 12px;">2 '.get_string('escale2', 'block_trust_model').'</spam></label>';
	$escala.= '<label><spam style="font-size: 12px;">3 '.get_string('escale3', 'block_trust_model').'</spam></label>';
	$escala.= '<label><spam style="font-size: 12px;">4 '.get_string('escale4', 'block_trust_model').'</spam></label>';
	$escala.= '<label><spam style="font-size: 12px;">5 '.get_string('escale5', 'block_trust_model').'</spam></label></div>';

	$escala.=  '<div style="width: 20%; float:left;"><label style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('binary_type', 'block_trust_model').' :</label></div>';
	$escala.= '<div style="width: 30%; float:left;">';
	$escala.= '<label><spam style="font-size: 12px;">1 '.get_string('binary1', 'block_trust_model').'</spam></label>';
	$escala.= '<label><spam style="font-size: 12px;">2 '.get_string('binary2', 'block_trust_model').'</spam></label>';
	$escala.= '</div></div>';
	
	echo $escala;	
	
	//Mostrar las preguntas
	$questions =  $DB -> get_records('trust_f7w7_t_questions',  array ('category'=>$id_cat, 'id_categories'=>$id));		
	if($questions){
		$t = new html_table();
		$cont=0;
		foreach($questions as $q){
		$cont=$cont+1;
		$row = new html_table_row();
		$cell1= $cont.'. <input type="text" size="100%" name="'.$q->id.'" value="'.$q->pregunta.'" />';
		$cell2= get_string($q->type, 'block_trust_model');
		$cell3= html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('opc' => 'delete', 'id_cat' => $id_cat,'id' => $id, 'id_q' => $q->id)), get_string('delete', 'block_trust_model'));
		$row->cells = array($cell1, $cell2, $cell3);
		$t->data[] = $row;
		}
		$t=html_writer::table($t);
		$form = '<div>';
		$form  .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional_templateSave.php?opc=saveAll&id_cat='.$id_cat.'&id='.$id.'">';
		$form  .= $t;
		$form  .= '<button type="submit" >'.get_string('save', 'block_trust_model').'</button>';
		$form  .= '</form></div>';
		echo $form;
	}
	
	//Mostrar agregar nueva pregunta
	$nuevo  = '<div>';
	$nuevo .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional_templateSave.php?opc=save&id_cat='.$id_cat.'&id='.$id.'" style="display:inline"><fieldset class="invisiblefieldset">';
	$nuevo .= '<legend><label>'.get_string('new_question', 'block_trust_model').'</label></legend>';
	$nuevo .= '<input id="question" name="question" size="95%" type="text" required/>';
	
	$nuevo .= ' <input type="checkbox" name="scale_type" onclick="check(scale_type)" checked/><label> '.get_string('scale_type', 'block_trust_model').'</label>';
	$nuevo .= ' <input type="checkbox" name="binary_type" onclick="check(binary_type)" /><label> '.get_string('binary_type', 'block_trust_model').'</label>';
	
	$nuevo .= '<button type="submit">'.get_string('add', 'block_trust_model').'</button><br />';
	$nuevo .= '</fieldset></form></div>';
	echo $nuevo;
	
	$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
	$imgAtras= '<img src="'.$urlAtras. '"alt="" />';
	echo $imgAtras.' '.html_writer::link( new moodle_url('/blocks/trust_model/F7W7_Institutional.php'), get_string('paramsInstitutional', 'block_trust_model'));

	
	
	echo $OUTPUT->box_end();
	echo $OUTPUT->footer();

}

?>
<script language="JavaScript" type="text/JavaScript">
function check(resp){
	if(resp.name== 'scale_type'){
		resp.checked =true;
		var x = document.getElementsByName("binary_type")[0];
		x.checked =false;
		
	}else{
		resp.checked =true;
		var x = document.getElementsByName("scale_type")[0];
		x.checked =false;
	}
		
}
</script>


	