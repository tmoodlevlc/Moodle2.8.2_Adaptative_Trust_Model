<?php
global $DB, $CFG, $USER;
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');

$c = required_param('c', PARAM_INT);
$u = required_param('u', PARAM_INT);
$cat = required_param('cat', PARAM_INT);

	
$url = new moodle_url('/blocks/trust_model/F7W7_Institutional_templateStudent_lstProfesores.php',array('c' => $c, 'u' => $u, 'cat' => $cat));
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
echo '<label  class="mdl-align" style="color: #2A5A5F; font-size: 15px; "><b>'.get_string('student_questionnaire', 'block_trust_model').'</b></label>';

$urlImagen = new moodle_url('/blocks/trust_model/pix/items.png');
$imagen= '<img src="'.$urlImagen. '"alt="" />';
$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
$imgAtras= '<img src="'.$urlAtras. '"alt="" />';

//Motrar cuestionario	
$contexto =context_course::instance($c);											
$rol =  $DB -> get_record('role',  array ('shortname'=>'editingteacher'));	
$teachers = get_users_from_role_on_context($rol,$contexto);
if($teachers){
	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<strong>'.get_string('lst_teacher', 'block_trust_model').'</strong>';
	$cell2= '';
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
	foreach($teachers as $teacher){
		$persona = $DB -> get_record('user',array('id'=> $teacher->userid));
		$row = new html_table_row();
		$cell1= $imagen.fullname($persona);
		if($teacher->userid==$u){//No puede evaluarce asi mismo
			$cell2= get_string('evaluate', 'block_trust_model');
		}else{
			$t_inst= $DB -> get_record('trust_f7w7_t_inst',  array ('course_id'=>$c));
			$teacher_evaluate= $DB -> get_records('trust_f7w7_t_answer',  array ('cat'=>1, 't_inst_id'=>$t_inst->id, 'user_emisor'=>$u, 'user_receptor'=>$teacher->userid));
			if($teacher_evaluate){//Si ya fue evaluado
				$cell2= get_string('user_evaluated', 'block_trust_model');
			}else{
				$cell2= html_writer::link(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateStudent.php', array('c' => $c, 'u' => $u, 'teacher' => $persona->id,'cat' => $cat )), get_string('evaluate', 'block_trust_model'));
			}
		}
		$row->cells = array($cell1, $cell2);
		$t->data[] = $row;
	}
	echo html_writer::table($t);
	
}else{
	echo '<div style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('not_lst_teacher', 'block_trust_model').'</div>';
}

echo '<br>';
echo $imgAtras.''.html_writer::link( new moodle_url('/blocks/trust_model/F7W7_Institutional_templateShow.php', array('c' => $c, 'u' => $u)), get_string('paramsInstitutional', 'block_trust_model'));
echo $OUTPUT->box_end();
echo $OUTPUT->footer();

	
	