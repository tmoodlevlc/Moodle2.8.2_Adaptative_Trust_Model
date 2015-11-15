<?php
global $DB, $CFG, $USER;
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');

$c = required_param('c', PARAM_INT);
$u = required_param('u', PARAM_INT);
$teacher = required_param('teacher', PARAM_INT);
$cat = required_param('cat', PARAM_INT);
$opc = optional_param('opc', '',PARAM_TEXT);

if (isguestuser()) {
    redirect($CFG->wwwroot);
}

if($opc=='save'){
		//Obtener la categoria a la que pertenece el curso
		$t_inst = $DB -> get_record('trust_f7w7_t_inst',  array ('course_id'=>$c));
		//Obtener las preguntas de la categoria
		$lst_questions  =  $DB -> get_records('trust_f7w7_t_questions',  array ('category'=>3, 'id_categories'=>$t_inst->category_course));
		
		//GUARDO EL VALOR TOTAL DE CUESTIONARIO
		$value=0;
		foreach($lst_questions as $q){
			$id= $q->id;
			//TIPO
			if($q->type == 'scale_type'){
				if(isset($_POST['0'.$id])){//Ingresa si esta Activo
					$value=$value+0;
				}else if (isset($_POST['1'.$id])){
					$value=$value+0;
				}else if (isset($_POST['2'.$id])){
					$value=$value+0.25;
				}else if (isset($_POST['3'.$id])){
					$value=$value+0.50;
				}else if (isset($_POST['4'.$id])){
					$value=$value+0.75;
				}else if (isset($_POST['5'.$id])){
					$value=$value+1;
				}
			}else{
				if(isset($_POST['1'.$id])){//Ingresa si esta Activo
					$value=$value+1;
				}else if (isset($_POST['2'.$id])){
					$value=$value+0;
				}
			}
		}
		
		$value= $value/count($lst_questions);
		$t_answer= save_answers_total_f7w7(3,$t_inst->id,$u, $teacher, $value);
		if($t_answer){
			//GUARDO LA RESPUESTA DE CADA PREGUNTA
			foreach($lst_questions as $q){
				$id= $q->id;
				$resp='';
				//TIPO
				if($q->type == 'scale_type'){
					if(isset($_POST['0'.$id])){//Ingresa si esta Activo
						$resp= 'escale_cero';
					}else if (isset($_POST['1'.$id])){
						$resp= 'escale_one';
					}else if (isset($_POST['2'.$id])){
						$resp= 'escale_two';
					}else if (isset($_POST['3'.$id])){
						$resp= 'escale_three';
					}else if (isset($_POST['4'.$id])){
						$resp= 'escale_four';
					}else if (isset($_POST['5'.$id])){
						$resp= 'escale_five';
					}
				}else{
					if(isset($_POST['1'.$id])){//Ingresa si esta Activo
						$resp= 'binary_one';
					}else if (isset($_POST['2'.$id])){
						$resp= 'binary_two';
					}
				}
				save_answers_directive_f7w7($u, $teacher, $t_inst->id, $q->id, $q->type, $resp, $t_answer);
			}
		}
		redirect(new moodle_url('/blocks/trust_model/F7W7_Institutional_templateDirective_lstProfesores.php',array('c' => $c, 'u' => $u, 'cat' => $cat)));
}else{
		
	$url = new moodle_url('/blocks/trust_model/F7W7_Institutional_templateDirective.php',array('c' => $c, 'u' => $u, 'teacher' => $teacher,'cat' => $cat ));
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
	echo '<label  class="mdl-align" style="color: #2A5A5F; font-size: 16px; font-family: cursive;">'.get_string('directivo_questionnaire', 'block_trust_model').'</label>';

	//Obtener la categoria a la que pertenece el curso
	$t_inst = $DB -> get_record('trust_f7w7_t_inst',  array ('course_id'=>$c));
	//Obtener las preguntas de la categoria
	$lst_questions =  $DB -> get_records('trust_f7w7_t_questions',  array ('category'=>3, 'id_categories'=>$t_inst->category_course));
	$t = new html_table();
	$cont=0;
	if($lst_questions){
		foreach ($lst_questions as $questions){
			$cont=$cont+1;
			$row = new html_table_row();
			$cell1  = '<p align="justify">'.$cont.'.'.$questions->pregunta.'</p>';
			$row->cells = array($cell1);
			$t->data[] = $row;
			$row = new html_table_row();
			if($questions->type=='scale_type'){
				$cell1=  '<div style="overflow:hidden;">';
				$cell1.= '<label style="width: 16%; float:left;"><input type="checkbox" class="'.$questions->id.'" name="0'.$questions->id.'" onclick="check(0'.$questions->id.', '.$questions->id.' )"  checked/>'.get_string('escale0', 'block_trust_model').'</label>';
				$cell1.= '<label style="width: 16%;float:left;"><input type="checkbox" class="'.$questions->id.'" name="1'.$questions->id.'" onclick="check(1'.$questions->id.', '.$questions->id.')"/>'.get_string('escale1', 'block_trust_model').'</label>';
				$cell1.= '<label style="width: 16%;float:left;"><input type="checkbox" class="'.$questions->id.'" name="2'.$questions->id.'" onclick="check(2'.$questions->id.', '.$questions->id.')"/>'.get_string('escale2', 'block_trust_model').'</label>';
				$cell1.= '<label style="width: 16%;float:left;"><input type="checkbox" class="'.$questions->id.'" name="3'.$questions->id.'" onclick="check(3'.$questions->id.', '.$questions->id.')"/>'.get_string('escale3', 'block_trust_model').'</label>';
				$cell1.= '<label style="width: 16%;float:left;"><input type="checkbox" class="'.$questions->id.'" name="4'.$questions->id.'" onclick="check(4'.$questions->id.', '.$questions->id.')"/>'.get_string('escale4', 'block_trust_model').'</label>';
				$cell1.= '<label style="width: 16%;float:left;"><input type="checkbox" class="'.$questions->id.'" name="5'.$questions->id.'" onclick="check(5'.$questions->id.', '.$questions->id.')"/>'.get_string('escale5', 'block_trust_model').'</label></div>';
			}else{
				$cell1=  '<div style="overflow:hidden;">';
				$cell1=  '<label style="width: 50%; float:left;"><input type="checkbox" class='.$questions->id.' name=1'.$questions->id.' onclick="check(1'.$questions->id.', '.$questions->id.')"  checked/>'.get_string('binary1', 'block_trust_model').'</label>';
				$cell1.= '<label style="width: 50%;float:left;"><input type="checkbox" class='.$questions->id.' name=2'.$questions->id.' onclick="check(2'.$questions->id.', '.$questions->id.')"/>'.get_string('binary2', 'block_trust_model').'</label>';
				$cell1.= '</div>';
			}
			$row->cells = array($cell1);
			$t->data[] = $row;
		}
		
		$t=html_writer::table($t);
		$form = '<div>';
		$form  .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F7W7_Institutional_templateDirective.php?opc=save&c='.$c.'&u='.$u.'&cat='.$cat.'&teacher='.$teacher.'">';
		$form  .= $t;
		$form  .= '<button type="submit" >'.get_string('save', 'block_trust_model').'</button>';
		$form  .= '</form></div>';
		echo $form;
		
	}else{
		echo '<div style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('not_questions_category', 'block_trust_model').'</div>';
	}
	$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
	$imgAtras= '<img src="'.$urlAtras. '"alt="" />';
	echo '<br>';
	echo $imgAtras.''.html_writer::link( new moodle_url('/blocks/trust_model/F7W7_Institutional_templateDirective_lstProfesores.php',array('c' => $c, 'u' => $u, 'cat' => $cat)), get_string('lst_teacher', 'block_trust_model'));
	echo $OUTPUT->box_end();
	echo $OUTPUT->footer();

}
?>

<script language="JavaScript" type="text/JavaScript">
function check(resp, idPregunta){
	var lst = document.getElementsByClassName(idPregunta);
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}
</script>
	
	