<?php
//Parametros
global $DB, $USER;
require_once('../../config.php');
require_once($CFG->dirroot.'/lib/accesslib.php');
$course = required_param('c', PARAM_INT);
if (isguestuser()) {
    redirect($CFG->wwwroot);
}
//Imprimir información
$url = new moodle_url('/blocks/trust_model/F1W1_Validation.php', array ('c'=>$course));
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_user::instance($USER->id));
$tm = get_string('pluginname', 'block_trust_model');
$PAGE->navbar->add($tm);
$f1w1 = get_string('experience', 'block_trust_model');
$PAGE->navbar->add($f1w1);
$validate = get_string('validate', 'block_trust_model');
$PAGE->navbar->add($validate);
$PAGE->set_title("{$SITE->shortname}: $tm");
$PAGE->set_heading("{$SITE->shortname}: $tm");
echo $OUTPUT->header();
echo $OUTPUT->box_start();
echo html_writer::start_tag('div', array('class' => 'mdl-align'));
echo html_writer::tag('h4', get_string('validateED', 'block_trust_model'));
echo html_writer::end_tag('div');

//Obtenemos variables
$contexto =context_course::instance($course);
$rol =  $DB -> get_record('role',  array ('shortname'=>'student'));	
$alumnos = get_users_from_role_on_context($rol,$contexto);

//Cabecera tabla
$t = new html_table();
$row = new html_table_row();
$cell1= '<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('listStudent', 'block_trust_model').'</p>';
$cell2= '<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('nroLike', 'block_trust_model').'</p>';
$cell3= '<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('actionPositive', 'block_trust_model').'</p>';
$cell4= '<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('actionNegative', 'block_trust_model').'</p>';
$row->cells = array($cell1, $cell2, $cell3, $cell4);
$t->data[] = $row;

//Imagenes
$imgSel = new moodle_url('/blocks/trust_model/pix/check_color.png');
$imgUrlSel= '<img src="'.$imgSel. '"alt="" />';
$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
$imgAtras= '<img src="'.$urlAtras. '"alt="" />';

//Obtener el usuario que validara F1W1
$userValidation =  $DB -> get_record('trust_f1w1_validate_user',  array ('course_id'=>$course));
foreach($alumnos as $alumno){
	$persona = $DB -> get_record('user',array('id'=> $alumno->userid));
	$row = new html_table_row();
	$cell1= fullname($persona);
	$f1w1 = $DB -> get_record('trust_f1w1_validate',array('user_id'=> $persona->id, 'course_id'=> $course ));
	if($f1w1){
		$cell2= $f1w1 -> i_like;
		if($persona->id == $USER->id){//No validar su numero de me gusta
			$cell3= get_string('like', 'block_trust_model');
			$cell4= get_string('not_like', 'block_trust_model');
		}else{
			//Obtener el rol del que valida la experiencia directa
			if($USER->id==$userValidation->teacher_id){
				$rol='teacher';
				if($f1w1 -> teacher_validate==1){
					$selec='like';
				}else{
					if($f1w1 -> teacher_validate==-1){
						$selec='not_like';
					}else{
						$selec='no_validate';
					}
				}
			}else{
				if($USER->id==$userValidation->student_id){
					$rol='student';
					if($f1w1 -> student_validate==1){
						$selec='like';
					}else{
						if($f1w1 -> student_validate==-1){
							$selec='not_like';
						}else{
							$selec='no_validate';
						}
					}
				}
			}
			if($selec=='like'){
				$cell3= html_writer::link(new moodle_url('/blocks/trust_model/F1W1_ValidationProcess.php', array('c' => $course, 'user' => $persona->id, 'rol' => $rol,'act' => 1, 'opc' => 2)), $imgUrlSel.' '.get_string('like', 'block_trust_model'));
				$cell4= html_writer::link(new moodle_url('/blocks/trust_model/F1W1_ValidationProcess.php', array('c' => $course, 'user' => $persona->id, 'rol' => $rol, 'act' => -1,'opc' => 2)), get_string('not_like', 'block_trust_model'));
			}else{
				if($selec=='not_like'){
					$cell3= html_writer::link(new moodle_url('/blocks/trust_model/F1W1_ValidationProcess.php', array('c' => $course, 'user' => $persona->id, 'rol' => $rol,'act' => 1, 'opc' => 2)), get_string('like', 'block_trust_model'));
					$cell4= html_writer::link(new moodle_url('/blocks/trust_model/F1W1_ValidationProcess.php', array('c' => $course, 'user' => $persona->id, 'rol' => $rol, 'act' => -1,'opc' => 2)), $imgUrlSel.' '.get_string('not_like', 'block_trust_model'));
				}else{
					$cell3= html_writer::link(new moodle_url('/blocks/trust_model/F1W1_ValidationProcess.php', array('c' => $course, 'user' => $persona->id, 'rol' => $rol,'act' => 1, 'opc' => 2)), get_string('like', 'block_trust_model'));
					$cell4= html_writer::link(new moodle_url('/blocks/trust_model/F1W1_ValidationProcess.php', array('c' => $course, 'user' => $persona->id, 'rol' => $rol, 'act' => -1,'opc' => 2)), get_string('not_like', 'block_trust_model'));
				}
			}
		}
	}else{
		$cell2=get_string('notSignin', 'block_trust_model');
		$cell3= get_string('like', 'block_trust_model');
		$cell4= get_string('not_like', 'block_trust_model');
	}
	$row->cells = array($cell1, $cell2, $cell3, $cell4);
	$t->data[] = $row;
}
$course=$DB->get_record('course', array('id' => $course));
echo html_writer::table($t);
echo $imgAtras.' '.html_writer::link(new moodle_url('/course/view.php', array('id'=>$course->id)), get_string('returnCourse', 'block_trust_model').': '.$course->shortname );
echo $OUTPUT->box_end();
echo $OUTPUT->footer();

