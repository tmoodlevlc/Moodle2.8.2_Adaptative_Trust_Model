<?php
require_once('../../config.php');
require_once('lib.php');

global $DB;
$user_id = required_param('u', PARAM_INT);
$course_id = required_param('c', PARAM_INT); 

if (isguestuser()) {
    redirect($CFG->wwwroot);
}

$url = new moodle_url('/blocks/trust_model/index.php', array('u' => $USER -> id, 'c' => $course_id));
$PAGE->set_url($url);

// Disable message notification popups while the user is viewing their messages
$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_user::instance($USER->id));
$tm = get_string('pluginname', 'block_trust_model');
$PAGE->navbar->add($tm);
$PAGE->set_title("{$SITE->shortname}: $tm");
$PAGE->set_heading("{$SITE->shortname}: $tm");
//now the page contents
echo $OUTPUT->header();
echo $OUTPUT->box_start();

//Imagen
$urlImagen = new moodle_url('/blocks/trust_model/pix/items.png');
$imagen= '<img src="'.$urlImagen. '"alt="" />';

$urlimgLike = new moodle_url('/blocks/trust_model/pix/icono-mas.png');
$imagenLike= '<img src="'.$urlimgLike. '"alt="" />';

$urlimgNotLike = new moodle_url('/blocks/trust_model/pix/iconomenos.png');
$imagenNotLike= '<img src="'.$urlimgNotLike. '"alt="" />';

$urlimgEst1 = new moodle_url('/blocks/trust_model/pix/estrellaNotLike.jpg');
$imagenEst1= '<img src="'.$urlimgEst1. '"alt="" />';

$urlimgEst2 = new moodle_url('/blocks/trust_model/pix/estrellaLikeSinFondo.png');
$imagenEst2= '<img src="'.$urlimgEst2. '"alt="" />';

$course=$DB->get_record('course', array('id' => $course_id));
echo html_writer::start_tag('div', array('class' => 'mdl-align'));
echo html_writer::tag('h4', get_string('nameCourse', 'block_trust_model').': '.$course -> fullname);
echo html_writer::end_tag('div');

//Número de Me gusta
$cont= $DB->get_record('trust_f1w1_validate', array('user_id' => $user_id, 'course_id' => $course_id));
$t = new html_table();
$row = new html_table_row();
$cell1 = new html_table_cell('');
$cell2 = new html_table_cell(get_string('registered_level', 'block_trust_model').$imagenEst1);
$cell3 = new html_table_cell(get_string('validated_level', 'block_trust_model').$imagenEst2);
$row->cells = array($cell1, $cell2, $cell3);
$t->data[] = $row;

$row = new html_table_row();
$cell1 = new html_table_cell($imagenLike.' '.get_string('like', 'block_trust_model'));
$cell2 = new html_table_cell($cont->i_like);
$cell3 = new html_table_cell($cont->like_validate);
$row->cells = array($cell1, $cell2, $cell3);
$t->data[] = $row;

$row = new html_table_row();
$cell1 = new html_table_cell($imagenNotLike.' '.get_string('not_like', 'block_trust_model'));
$cell2 = new html_table_cell($cont->not_like);
$cell3 = '';
$row->cells = array($cell1, $cell2, $cell3);
$t->data[] = $row;

echo html_writer::table($t);


//Nivel de confianza (Experiencia directa)
echo '<p class="info" style="color: #2A5A5F; font-family: cursive; font-size: 15px; font-weight: bold;">'.get_string('level', 'block_trust_model').'</p>';
$forum= $DB->get_record('trust_f1w1_forum', array('user_id' => $user_id, 'course_id' => $course_id));
$assign= $DB->get_record('trust_f1w1_assign', array('user_id' => $user_id, 'course_id' => $course_id));
$quiz= $DB->get_record('trust_f1w1_quiz', array('user_id' => $user_id, 'course_id' => $course_id));
$resource= $DB->get_record('trust_f1w1_resource', array('user_id' => $user_id, 'course_id' => $course_id));

$t = new html_table();
$row = new html_table_row();
$cell1 = new html_table_cell($imagen.'Forum '.$forum->trust);
$cell2 = new html_table_cell($imagen.'Tarea '.$assign->trust);
$cell3 = new html_table_cell($imagen.'Examen '.$quiz->trust);
$cell4 = new html_table_cell($imagen.'Resource '.$resource->trust);
$row->cells = array($cell1, $cell2, $cell3, $cell4);
$t->data[] = $row;
echo html_writer::table($t);


//Factores del Modelo de 
echo '<p class="info" style="color: #2A5A5F; font-family: cursive; font-size: 15px; font-weight: bold;">'.get_string('factors', 'block_trust_model').'</p>';
$trustModel= $DB->get_record('trust', array('user_id' => $user_id, 'course_id' => $course_id));
$general_settings =  $DB->get_record_sql('SELECT * FROM {trust_general_settings} WHERE codigo IS NOT NULL');

$t = new html_table();

$row = new html_table_row();
$cell1 = new html_table_cell($imagen.get_string('experience', 'block_trust_model'));
$cell2 = new html_table_cell($trustModel -> f1w1);
$row->cells = array($cell1, $cell2);
$t->data[] = $row;
	
$row = new html_table_row();
$cell1 = new html_table_cell($imagen.get_string('reputation', 'block_trust_model'));
$cell2 = new html_table_cell($trustModel-> f2w2);
$row->cells = array($cell1, $cell2);
$t->data[] = $row;
	
if($general_settings->f3w3=='true'){
	$row = new html_table_row();
	$cell1 = new html_table_cell($imagen.get_string('role', 'block_trust_model'));
	$cell2 = new html_table_cell($trustModel -> f3w3);
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
}

if($general_settings->f4w4=='true'){
	$row = new html_table_row();
	$cell1 = new html_table_cell($imagen.get_string('knowledge', 'block_trust_model'));
	$cell2 = new html_table_cell($trustModel -> f4w4);
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
}
	
if($general_settings->f5w5=='true'){
	$row = new html_table_row();
	$cell1 = new html_table_cell($imagen.get_string('security', 'block_trust_model'));
	$cell2 = new html_table_cell($trustModel -> f5w5);
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
}	
	
if($general_settings->f6w6=='true'){
	$row = new html_table_row();
	$cell1 = new html_table_cell($imagen.get_string('quality', 'block_trust_model'));
	$cell2 = new html_table_cell($trustModel-> f6w6);
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
}

if($general_settings->f7w7=='true'){
	$row = new html_table_row();
	$cell1 = new html_table_cell($imagen.get_string('institutional', 'block_trust_model'));
	$cell2 = new html_table_cell($trustModel -> f7w7);
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
}	
	
if($general_settings->f8w8=='true'){
	$row = new html_table_row();
	$cell1 = new html_table_cell($imagen.get_string('kin', 'block_trust_model'));
	$cell2 = new html_table_cell($trustModel -> f8w8);
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
}

$row = new html_table_row();
$cell1 = new html_table_cell(strtoupper(get_string('trust_level', 'block_trust_model')));//trust_level
$cell2 = new html_table_cell('( '.$trustModel-> trust_level.' )');
$row->cells = array($cell1, $cell2);
$t->data[] = $row;


//Confianza Externa
$trust_external =  $DB -> get_record('trust_external',  array('userid' => $user_id));
if($trust_external){
	$row = new html_table_row();
	$cell1 = new html_table_cell(strtoupper(get_string('levelExternalTrust', 'block_trust_model')));
	$cell2 = new html_table_cell('( '.$trust_external ->trust_external.' )');
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
}

echo html_writer::table($t);

//Grafico
$url_grafic = new moodle_url('/blocks/trust_model/graphic/index.php', array('u'=>$user_id, 'c'=>$course_id));
echo html_writer::tag('a', get_string('grafo', 'block_trust_model'), array('href' => $url_grafic, 'target' => '_blank')).'<br>';

$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
$imgAtras= '<img src="'.$urlAtras. '"alt="" />';
$url = new moodle_url('/course/view.php', array('id'=>$course_id));
echo $imgAtras.''.html_writer::link( $url, get_string('returnCourse', 'block_trust_model').': '.$course ->shortname );

echo $OUTPUT->box_end();
echo $OUTPUT->footer();





