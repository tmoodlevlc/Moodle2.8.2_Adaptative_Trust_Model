<?php
global $DB, $CFG, $USER;
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');

$c = required_param('c', PARAM_INT);
$u = required_param('u', PARAM_INT);
$campo = optional_param('campo', '',PARAM_TEXT);
$id = optional_param('id', '',PARAM_INT);

if (isguestuser()) {
    redirect($CFG->wwwroot);
}

if($campo=='interna' || $campo=='externa'){

		//Obtener la categoria a la que pertenece el curso
		$categoria= $DB->get_record_sql("SELECT course_categories.id, course_categories.path
											FROM {course} course
											INNER JOIN {course_categories} course_categories ON course.category = course_categories.id 
											WHERE course.id = ?",array($c));
		$categoriaCurso = explode("/", $categoria->path);
		save_instancia_course_f7w7($c, $id, $campo, $categoriaCurso[1]);
		
		//Redirigir a la pagina
		redirect(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php',array('c' => $c, 'u' => $u)));
}else{
	$urlImagen = new moodle_url('/blocks/trust_model/pix/items.png');
	$imagen= '<img src="'.$urlImagen. '"alt="" />';
	$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
	$imgAtras= '<img src="'.$urlAtras. '"alt="" />';
	
	$url = new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php',array('c' => $c, 'u' => $u));
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
	echo html_writer::start_tag('div', array('class' => 'mdl-align'));
	echo html_writer::tag('h4', get_string('pluginname', 'block_trust_model'));
	echo html_writer::end_tag('div');

	
	//Mostrar segun el rol, los cuestionarios habilitados
	$t='';
	$student=false;
	$teacher=false;
	$directive=false;
	$pair=false;
	$institutional_curso =  $DB -> get_record('trust_f7w7_t_inst',  array('course_id' => $c));
	//Roles del usuario
	$context = context_course::instance($c);             
	$roles = get_user_roles ( $context , $u ,  true);
	foreach ($roles as $rol) {
		//Si es profesor
		if($rol ->shortname == 'editingteacher'){
			$t = new html_table();
			$row = new html_table_row();
			$cell1= $imagen.get_string('evaluation_internal', 'block_trust_model');
			$cell3= $imagen.get_string('evaluation_external', 'block_trust_model');
			if($institutional_curso){//Si exise la instancia Evaluacion interna/externa para el curso
				//Mostrar Configuración inicial
				if($institutional_curso->internal_evaluation=='true'){
					$cell2= html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php', array('c' => $c,'u' => $u, 'campo' =>'interna', 'id'=>$institutional_curso->id)), get_string('disable', 'block_trust_model'));
				}else{
					$cell2= html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php', array('c' => $c,'u' => $u, 'campo' => 'interna','id'=>$institutional_curso->id)), get_string('enable', 'block_trust_model'));
				}
				if ($institutional_curso->external_evaluation=='true') {
					$teacher=true;
					$cell4= html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php', array('c' => $c,'u' => $u, 'campo' => 'externa','id'=>$institutional_curso->id)), get_string('disable', 'block_trust_model'));
				}else{
					$cell4= html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php', array('c' => $c,'u' => $u, 'campo' => 'externa', 'id'=>$institutional_curso->id)), get_string('enable', 'block_trust_model'));
				}
				
			}else {	
				$cell2= html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php', array('c' => $c,'u' => $u, 'campo' => 'interna' , 'id'=>0)), get_string('enable', 'block_trust_model'));
				$cell4= html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php', array('c' => $c,'u' => $u, 'campo' => 'externa', 'id'=>0)), get_string('enable', 'block_trust_model'));
			}
			$row->cells = array($cell1, $cell2, $cell3, $cell4);
			$t->data[] = $row;
			$t=html_writer::table($t);
			
		}else if($rol ->shortname == 'student' && $institutional_curso){
			if($institutional_curso->external_evaluation=='true'){
				$student=true;
			}
		}
	}
	//Si es directivo
	if($institutional_curso){
		if($DB -> record_exists('trust_f7w7_t_dir_sel',  array('user_id' => $u, 'categories_id' => $institutional_curso->category_course)) 
		&& $institutional_curso->internal_evaluation=='true'){
			$directive=true;
		}
	}
	//Si es Par
	if($institutional_curso){
		//Obtener la subcategoria a la que pertenece el curso
		$categoria= $DB->get_record_sql("SELECT course_categories.id, course_categories.path
											FROM {course} course
											INNER JOIN {course_categories} course_categories ON course.category = course_categories.id 
											WHERE course.id = ?",array($c));
		$categoriaCurso = explode("/", $categoria->path);	

		if(count($categoriaCurso)>=3){//Verificar que si tiene subcategoria
			$subcategoria= $categoriaCurso[2];//Obtengo la subcategoria
			if($DB->record_exists_sql("SELECT * FROM {trust_f7w7_t_pairs_comb} WHERE categories = ? AND sub_categories=? AND (one_pairs_user = ? OR two_pairs_user = ?)",
				array($institutional_curso->category_course, $subcategoria, $u, $u))
				&& $institutional_curso->internal_evaluation=='true'){
					$pair=true;
			}
		}
	}				
	
	//Mostrar los cuestionarios habilitados
	$items='';
	if($institutional_curso){
		if($institutional_curso->internal_evaluation=='true'){
			if($directive==true || $pair==true){
				$items.= '<div style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('evaluation_internal', 'block_trust_model').'</div>';
				if($directive==true){
					$items.= $imagen.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateDirective_lstProfesores.php', array('c' => $c,'u' => $u,'cat' => 3)), get_string('directivo_questionnaire', 'block_trust_model')).'<br>';							
				}
				if($pair==true){
					$items.= $imagen.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templatePairs_lstProfesores.php', array('c' => $c,'u' => $u,'cat' => 4, 'categoria' => $institutional_curso->category_course, 'subcategoria' => $subcategoria)), get_string('par_questionnaire', 'block_trust_model')).'<br>';							
				}
			}
		}else{
			$items.= '<div style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('evaluation_internal_notenabled', 'block_trust_model').'</div>';
		}
		
		if($institutional_curso->external_evaluation=='true'){
			if($student==true || $teacher==true){
				$items.= '<div style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('evaluation_external', 'block_trust_model').'</div>';
				if($teacher==true){
					$items.= $imagen.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateTeacher.php', array('c' => $c, 'u' => $u, 'cat' => 2)), get_string('teacher_questionnaire', 'block_trust_model')).'<br>';							
				}
				if($student==true){
					$items.= $imagen.html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateStudent_lstProfesores.php', array('c' => $c,'u' => $u,'cat' => 1)), get_string('student_questionnaire', 'block_trust_model')).'<br>';				
				}
			}
		}else{
			$items.= '<div style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('evaluation_external_notenabled', 'block_trust_model').'</div>';
		}
	}

	echo $t;
	echo $items;
	$course=$DB->get_record('course', array('id' => $c));
	echo '<br>';
	echo $imgAtras.''.html_writer::link( new moodle_url('/course/view.php', array('id'=>$c)), get_string('returnCourse', 'block_trust_model').': '.$course ->shortname );
	echo $OUTPUT->box_end();
	echo $OUTPUT->footer();

}


	