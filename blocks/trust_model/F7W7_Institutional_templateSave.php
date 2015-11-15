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
		$lstquestions =  $DB -> get_records('trust_f7w7_t_questions',  array ('category'=>$id_cat, 'id_categories'=>$id));
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
	
	$urlImagen = new moodle_url('/blocks/trust_model/pix/items.png');
	$imagen= '<img src="'.$urlImagen. '"alt="" />';
	$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
	$imgAtras= '<img src="'.$urlAtras. '"alt="" />';
	
	$url = new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php',array('id_cat' => $id_cat, 'id' => $id));
	$PAGE->set_url($url);
	$PAGE->set_pagelayout('standard');
	$PAGE->set_context(context_user::instance($USER->id));
	$tm = get_string('pluginname', 'block_trust_model');
	$PAGE->navbar->add($tm);
	$PAGE->navbar->add(get_string('institutional', 'block_trust_model'));
	$PAGE->navbar->add(get_string('templateIns', 'block_trust_model'));
	$PAGE->set_title("{$SITE->shortname}: $tm");
	$PAGE->set_heading("{$SITE->shortname}: $tm");
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
			$lstTeacher= $DB->get_records_sql("SELECT user.id as userid, user.firstname, user.lastname, course.id as courseid
											FROM {user} user
											INNER JOIN {role_assignments} role_assignments ON user.id = role_assignments.userid 
											INNER JOIN {role} role ON role.id = role_assignments.roleid
											INNER JOIN {context} context ON context.id = role_assignments.contextid
											INNER JOIN {course} course ON course.id = context.instanceid
											INNER JOIN {course_categories} course_categories ON course_categories.id = course.category
											WHERE role.id = ? AND course_categories.path LIKE '%$c->path%' GROUP BY user.id ORDER BY user.lastname, user.firstname  ASC ", 
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
	
	//Mostrar las preguntas
	$questions =  $DB -> get_records('trust_f7w7_t_questions',  array ('category'=>$id_cat, 'id_categories'=>$id));		
	if($questions){
		$t = new html_table();
		$cont=0;
		foreach($questions as $q){
			$cont=$cont+1;
			$row = new html_table_row();
			$cell1=  '<div>';
			$cell1.= '<div style="width: 50%; float:left;">'.$cont.'. '.get_string($q->type, 'block_trust_model').'</div>';
			$cell1.= '<div style="width: 50%; float:left; text-align:right">'.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateSave.php', array('opc' => 'delete', 'id_cat' => $id_cat,'id' => $id, 'id_q' => $q->id)), get_string('delete', 'block_trust_model')).'</div>';
			$cell1.= '</div>';
			$row->cells = array($cell1);
			$t->data[] = $row;
			
			$row = new html_table_row();
			$cell1= '<textarea type="text" rows=1 style="width:100%; resize:vertical;" name="'.$q->id.'"/>'.$q->pregunta.'</textarea>';
			$row->cells = array($cell1);
			$t->data[] = $row;
		}
		$t=html_writer::table($t);
		$form = '<div>';
		$form  .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional_templateSave.php?opc=saveAll&id_cat='.$id_cat.'&id='.$id.'">';
		$form  .= $t;
		$form  .= '<input id="id_submitbutton"  type="submit" value="'.get_string('saveAll', 'block_trust_model').'">';
		$form  .= '</form></div>';
		echo $form;
	}
	
	//Mostrar agregar nueva pregunta
	$nuevo  = '<div>';
	$nuevo .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional_templateSave.php?opc=save&id_cat='.$id_cat.'&id='.$id.'"><fieldset>';
	$nuevo .= '<legend><label>'.get_string('new_question', 'block_trust_model').'</label></legend>';
	$nuevo .= '<textarea id="question" name="question" style="width:100%; resize:vertical;"  required/></textarea>';
	$nuevo .= ' <input type="checkbox" name="scale_type" onclick="check(scale_type)" checked/><label> '.get_string('scale_type', 'block_trust_model').'&nbsp;</label>';
	$nuevo .= ' <input type="checkbox" name="binary_type" onclick="check(binary_type)" /><label> '.get_string('binary_type', 'block_trust_model').'&nbsp;</label>';
	$nuevo .= '<button type="submit">'.get_string('addQuestion', 'block_trust_model').'</button><br />';
	$nuevo .= '</fieldset></form></div>';
	echo $nuevo;
	
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


	