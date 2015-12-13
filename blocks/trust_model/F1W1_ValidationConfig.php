<?php
//Variables y parametros
require_once('../../config.php');
require_once($CFG->dirroot.'/lib/accesslib.php');
global $DB, $USER, $CFG;
if (isguestuser()) {
    redirect($CFG->wwwroot);
}
$course = required_param('c', PARAM_INT);

//Impresión
$url = new moodle_url('/blocks/trust_model/F1W1_ValidationConfig.php',array ('c'=>$course));
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_user::instance($USER->id));
$tm = get_string('pluginname', 'block_trust_model');
$PAGE->navbar->add($tm);
$f1w1 = get_string('experience', 'block_trust_model');
$PAGE->navbar->add($f1w1);
$validateConfig = get_string('validateConfig', 'block_trust_model');
$PAGE->navbar->add($validateConfig);
$PAGE->set_title("{$SITE->shortname}: $tm");
$PAGE->set_heading("{$SITE->shortname}: $tm");
echo $OUTPUT->header();
echo $OUTPUT->box_start();
echo html_writer::start_tag('div', array('class' => 'mdl-align'));
echo html_writer::tag('h4', get_string('userValidate', 'block_trust_model'));
echo html_writer::end_tag('div');
echo html_writer::tag('h5', get_string('teacher', 'block_trust_model'));

//Imagenes
$imgSel = new moodle_url('/blocks/trust_model/pix/check_color.png');
$imgUrlSel= '<img src="'.$imgSel. '"alt="" />';
$urlimgEst2 = new moodle_url('/blocks/trust_model/pix/estrellaLikeSinFondo.png');
$imagenEst2= '<img src="'.$urlimgEst2. '"alt="" title="'.get_string('studentSuggested', 'block_trust_model').'" />';
$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
$imgAtras= '<img src="'.$urlAtras. '"alt="" />';

//Variables
$contexto =context_course::instance($course);												
$rol =  $DB -> get_record('role',  array ('shortname'=>'editingteacher'));	
$teachers = get_users_from_role_on_context($rol,$contexto);

//Tabla Docentes
$t = new html_table();
foreach($teachers as $teacher){
	$persona = $DB -> get_record('user',array('id'=> $teacher->userid));
	$row = new html_table_row();
	$cell1= fullname($persona);
	$uservalidate = $DB -> get_record('trust_f1w1_validate_user',array('course_id'=> $course, 'teacher_id'=> $persona->id));
	if($uservalidate){
		$cell2= get_string('select', 'block_trust_model');
		$cell3= $imgUrlSel.' '.get_string('userValidateED', 'block_trust_model');
	}else{
		$cell2= html_writer::link(new moodle_url('/blocks/trust_model/F1W1_ValidationProcess.php', array('c' => $course, 'user' => $persona->id, 'rol' => 'teacher','opc' => 1 )), get_string('select', 'block_trust_model'));
		$cell3= '';
	}
	$row->cells = array($cell1, $cell2, $cell3);
	$t->data[] = $row;
}
echo html_writer::table($t);

//Tabla Estudiantes
echo html_writer::tag('h5', get_string('student', 'block_trust_model'));													
$rol =  $DB -> get_record('role',  array ('shortname'=>'student'));	
$alumnos = get_users_from_role_on_context($rol,$contexto);
$t = new html_table();
//Usuario a sugerir
$contLike=0;
$contUser=0;
foreach($alumnos as $alumno){
	$persona = $DB -> get_record('user',array('id'=> $alumno->userid));
	$userlike = $DB -> get_record('trust_f1w1_validate',array('course_id'=> $course, 'user_id'=> $persona->id));
	if($userlike){
		$c= $userlike->i_like;
		if($c>$contLike){
			$contLike=$c;
			$contUser=$persona->id;
		}
	}
}
foreach($alumnos as $alumno){
	$persona = $DB -> get_record('user',array('id'=> $alumno->userid));
	$row = new html_table_row();
	
	if($contUser==$persona->id){
		$cell1= fullname($persona).' '.$imagenEst2 ;
	}else{
		$cell1= fullname($persona);
	}
	
	$uservalidate = $DB -> get_record('trust_f1w1_validate_user',array('course_id'=> $course, 'student_id'=> $persona->id));
	if($uservalidate){
		$cell2= get_string('select', 'block_trust_model');
		$cell3= $imgUrlSel.' '.get_string('userValidateED', 'block_trust_model');
	}else{
		$cell2= html_writer::link(new moodle_url('/blocks/trust_model/F1W1_ValidationProcess.php', array('c' => $course, 'user' => $persona->id, 'rol' => 'student', 'opc' => 1)), get_string('select', 'block_trust_model'));
		$cell3= '';
	}
	
	
	$row->cells = array($cell1, $cell2, $cell3);
	$t->data[] = $row;
}
echo html_writer::table($t);

//Activar validar experiencia directa
$teacherValidation =  $DB -> get_record('trust_f1w1_validate_user',  array ('course_id'=>$course));
if($teacherValidation){
	if($teacherValidation->teacher_id == $USER->id || $teacherValidation->student_id == $USER->id){
		$urlUser = new moodle_url('/blocks/trust_model/pix/users.png');
		$imgUser= '<img src="'.$urlUser. '"alt="" />';
		$url = new moodle_url('/blocks/trust_model/F1W1_Validation.php', array('c'=>$course));
		echo $imgUser.' '.html_writer::link( $url, get_string('validateED', 'block_trust_model')).html_writer::empty_tag('br');
	}
}
$course=$DB->get_record('course', array('id' => $course));
echo $imgAtras.' '.html_writer::link(new moodle_url('/course/view.php', array('id'=>$course->id)), get_string('returnCourse', 'block_trust_model').': '.$course ->shortname );
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
