<?php
global $DB, $CFG, $USER;
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');
$scorm = required_param('s', PARAM_INT); 
$user = required_param('u', PARAM_INT);
$course = required_param('c', PARAM_INT);
$opc = optional_param('opc', '',PARAM_TEXT);
if (isguestuser()) {
    redirect($CFG->wwwroot);
}

if($opc=='save'){
		//Pregunta 1
		$pregunta1;
		if(isset($_POST['uno1'])){//Ingresa si esta Activo
			$pregunta1=0;
		}else if(isset($_POST['uno2'])){
			$pregunta1=1.5;
		}else if(isset($_POST['uno3'])){
			$pregunta1=2.5;
		}else if(isset($_POST['uno4'])){
			$pregunta1=3.5;
		}else if(isset($_POST['uno5'])){
			$pregunta1=4.5;
		}else if(isset($_POST['uno6'])){
			$pregunta1=5.0;
		}
		//Pregunta 2
		$pregunta2;
		if(isset($_POST['dos1'])){//Ingresa si esta Activo
			$pregunta2=0;
		}else if(isset($_POST['dos2'])){
			$pregunta2=1.5;
		}else if(isset($_POST['dos3'])){
			$pregunta2=2.5;
		}else if(isset($_POST['dos4'])){
			$pregunta2=3.5;
		}else if(isset($_POST['dos5'])){
			$pregunta2=4.5;
		}else if(isset($_POST['dos6'])){
			$pregunta2=5.0;
		}
		//Pregunta 3
		$pregunta3;
		if(isset($_POST['tres1'])){//Ingresa si esta Activo
			$pregunta3=0;
		}else if(isset($_POST['tres2'])){
			$pregunta3=1.5;
		}else if(isset($_POST['tres3'])){
			$pregunta3=2.5;
		}else if(isset($_POST['tres4'])){
			$pregunta3=3.5;
		}else if(isset($_POST['tres5'])){
			$pregunta3=4.5;
		}else if(isset($_POST['tres6'])){
			$pregunta3=5.0;
		}
		//Pregunta 4
		$pregunta4;
		if(isset($_POST['cuatro1'])){//Ingresa si esta Activo
			$pregunta4=0;
		}else if(isset($_POST['cuatro2'])){
			$pregunta4=1.5;
		}else if(isset($_POST['cuatro3'])){
			$pregunta4=2.5;
		}else if(isset($_POST['cuatro4'])){
			$pregunta4=3.5;
		}else if(isset($_POST['cuatro5'])){
			$pregunta4=4.5;
		}else if(isset($_POST['cuatro6'])){
			$pregunta4=5.0;
		}
		//Pregunta 5
		$pregunta5;
		if(isset($_POST['cinco1'])){//Ingresa si esta Activo
			$pregunta5=0;
		}else if(isset($_POST['cinco2'])){
			$pregunta5=1.5;
		}else if(isset($_POST['cinco3'])){
			$pregunta5=2.5;
		}else if(isset($_POST['cinco4'])){
			$pregunta5=3.5;
		}else if(isset($_POST['cinco5'])){
			$pregunta5=4.5;
		}else if(isset($_POST['cinco6'])){
			$pregunta5=5.0;
		}
		//Pregunta 6
		$pregunta6;
		if(isset($_POST['seis1'])){//Ingresa si esta Activo
			$pregunta6=0;
		}else if(isset($_POST['seis2'])){
			$pregunta6=1.5;
		}else if(isset($_POST['seis3'])){
			$pregunta6=2.5;
		}else if(isset($_POST['seis4'])){
			$pregunta6=3.5;
		}else if(isset($_POST['seis5'])){
			$pregunta6=4.5;
		}else if(isset($_POST['seis6'])){
			$pregunta6=5.0;
		}
		//Pregunta 7
		$pregunta7;
		if(isset($_POST['siete1'])){//Ingresa si esta Activo
			$pregunta7=0;
		}else if(isset($_POST['siete2'])){
			$pregunta7=1.5;
		}else if(isset($_POST['siete3'])){
			$pregunta7=2.5;
		}else if(isset($_POST['siete4'])){
			$pregunta7=3.5;
		}else if(isset($_POST['siete5'])){
			$pregunta7=4.5;
		}else if(isset($_POST['siete6'])){
			$pregunta7=5.0;
		}
		//Pregunta 8
		$pregunta8;
		if(isset($_POST['ocho1'])){//Ingresa si esta Activo
			$pregunta8=0;
		}else if(isset($_POST['ocho2'])){
			$pregunta8=1.5;
		}else if(isset($_POST['ocho3'])){
			$pregunta8=2.5;
		}else if(isset($_POST['ocho4'])){
			$pregunta8=3.5;
		}else if(isset($_POST['ocho5'])){
			$pregunta8=4.5;
		}else if(isset($_POST['ocho6'])){
			$pregunta8=5.0;
		}
		//Pregunta 9
		$pregunta9;
		if(isset($_POST['nueve1'])){//Ingresa si esta Activo
			$pregunta9=0;
		}else if(isset($_POST['nueve2'])){
			$pregunta9=1.5;
		}else if(isset($_POST['nueve3'])){
			$pregunta9=2.5;
		}else if(isset($_POST['nueve4'])){
			$pregunta9=3.5;
		}else if(isset($_POST['nueve5'])){
			$pregunta9=4.5;
		}else if(isset($_POST['nueve6'])){
			$pregunta9=5.0;
		}
		//Pregunta 10
		$pregunta10;
		if(isset($_POST['diez1'])){//Ingresa si esta Activo
			$pregunta10=0;
		}else if(isset($_POST['diez2'])){
			$pregunta10=1.5;
		}else if(isset($_POST['diez3'])){
			$pregunta10=2.5;
		}else if(isset($_POST['diez4'])){
			$pregunta10=3.5;
		}else if(isset($_POST['diez5'])){
			$pregunta10=4.5;
		}else if(isset($_POST['diez6'])){
			$pregunta10=5.0;
		}
		//Pregunta 11
		$pregunta11;
		if(isset($_POST['once1'])){//Ingresa si esta Activo
			$pregunta11=0;
		}else if(isset($_POST['once2'])){
			$pregunta11=1.5;
		}else if(isset($_POST['once3'])){
			$pregunta11=2.5;
		}else if(isset($_POST['once4'])){
			$pregunta11=3.5;
		}else if(isset($_POST['once5'])){
			$pregunta11=4.5;
		}else if(isset($_POST['once6'])){
			$pregunta11=5.0;
		}
		//Pregunta 12
		$pregunta1;
		if(isset($_POST['doce1'])){//Ingresa si esta Activo
			$pregunta12=0;
		}else if(isset($_POST['doce2'])){
			$pregunta12=1.5;
		}else if(isset($_POST['doce3'])){
			$pregunta12=2.5;
		}else if(isset($_POST['doce4'])){
			$pregunta12=3.5;
		}else if(isset($_POST['doce5'])){
			$pregunta12=4.5;
		}else if(isset($_POST['doce6'])){
			$pregunta12=5.0;
		}
		//Pregunta 13
		$pregunta13;
		if(isset($_POST['trece1'])){//Ingresa si esta Activo
			$pregunta13=0;
		}else if(isset($_POST['trece2'])){
			$pregunta13=1.5;
		}else if(isset($_POST['trece3'])){
			$pregunta13=2.5;
		}else if(isset($_POST['trece4'])){
			$pregunta13=3.5;
		}else if(isset($_POST['trece5'])){
			$pregunta13=4.5;
		}else if(isset($_POST['trece6'])){
			$pregunta13=5.0;
		}
		//Pregunta 14
		$pregunta14;
		if(isset($_POST['catorce1'])){//Ingresa si esta Activo
			$pregunta14=0;
		}else if(isset($_POST['catorce2'])){
			$pregunta14=1.5;
		}else if(isset($_POST['catorce3'])){
			$pregunta14=2.5;
		}else if(isset($_POST['catorce4'])){
			$pregunta14=3.5;
		}else if(isset($_POST['catorce5'])){
			$pregunta14=4.5;
		}else if(isset($_POST['catorce6'])){
			$pregunta14=5.0;
		}
		//Guardar por categorias
		$teaching_curricular=($pregunta1 + $pregunta2 + $pregunta3 + $pregunta4 + $pregunta5 + $pregunta6 + $pregunta7 )/7;
		$interface_design= ($pregunta8 + $pregunta9 + $pregunta10 + $pregunta11 + $pregunta12)/5;
		$navigation_design= ($pregunta13 + $pregunta14)/2;
		//Valor promedio de las 3 categorias
		$valorScorm= ($teaching_curricular + $interface_design + $navigation_design)/3;
		//Guardar en la base de datos
		validateScorm_f6w6($scorm, $user, $course, $teaching_curricular, $interface_design,$navigation_design, $valorScorm);
		//Redirigir al Scorm
		redirect(new moodle_url('/mod/scorm/view.php', array('a'=>$scorm)));
}else{
	
	$url = new moodle_url('/blocks/trust_model/F6W6_QuestionnaireScorm.php', array('s' => $scorm, 'u' => $user, 'c' => $course));
	$PAGE->set_url($url);
	$PAGE->set_pagelayout('standard');
	$PAGE->set_context(context_user::instance($USER->id));
	$tm = get_string('pluginname', 'block_trust_model');
	$PAGE->navbar->add($tm);
	$PAGE->navbar->add('Scorm');
	$PAGE->set_title("{$SITE->shortname}: $tm");
	$PAGE->set_heading("{$SITE->shortname}: $tm");
	echo $OUTPUT->header();
	echo $OUTPUT->box_start();
	echo html_writer::start_tag('div', array('class' => 'mdl-align'));
	echo html_writer::tag('h4', get_string('validateScorm', 'block_trust_model'));
	echo html_writer::end_tag('div');

	//Cuestionario
	$questionnaire  = '<div>';
	$questionnaire  .= '<form name="orderForm" method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F6W6_QuestionnaireScorm.php?opc=save&s='.$scorm.'&c='.$course.'&u='.$user.'">';

	$questionnaire  .= '<label class="info" style="color: #2A5A5F; font-family: cursive; font-size: 15px; font-weight: bold;">Categoría Didáctica-Curricular</label>';
	$questionnaire  .= '<label style="color: #2A5A5F; font-size: 12px; font-weight: bold;">CONTEXTO</label>';
	
	$t = new html_table();
	$row = new html_table_row();
	$cell1  = '<label>1. Nivel formativo adecuado a la situación educativa, por ejemplo: educación secundaria, etc</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta1" name="uno1" onclick="pregunta1(uno1)"  checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta1" name="uno2" onclick="pregunta1(uno2)"/>Muy deficiente</label>';
	$cell1.= '<div style="width: 15%;float:left;  "><input type="checkbox" class="pregunta1" name="uno3" onclick="pregunta1(uno3)"/>Deficiente</div>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta1" name="uno4" onclick="pregunta1(uno4)"/>Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta1" name="uno5" onclick="pregunta1(uno5)"/>Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta1" name="uno6" onclick="pregunta1(uno6)"/>Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>2. Descripción de la unidad: Presenta una introducción y/o resumen que explica de forma clara en qué consiste la unidad</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta2" name="dos1" onclick="pregunta2(dos1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta2" name="dos2" onclick="pregunta2(dos2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta2" name="dos3" onclick="pregunta2(dos3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta2" name="dos4" onclick="pregunta2(dos4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta2" name="dos5" onclick="pregunta2(dos5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta2" name="dos6" onclick="pregunta2(dos6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$questionnaire  .= '<label style="color: #2A5A5F; font-size: 12px; font-weight: bold;">OBJETIVOS</label>';

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>3. Correctamente formulado: generalmente los objetivos se elaboran segun la fórmula: verbo infinito + contenido + circunstancia</label>';;
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta3" name="tres1" onclick="pregunta3(tres1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta3" name="tres2" onclick="pregunta3(tres2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta3" name="tres3" onclick="pregunta3(tres3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta3" name="tres4" onclick="pregunta3(tres4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta3" name="tres5" onclick="pregunta3(tres5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta3" name="tres6" onclick="pregunta3(tres6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>4. Factible, puede ser alcanzable</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta4" name="cuatro1" onclick="pregunta4(cuatro1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta4" name="cuatro2" onclick="pregunta4(cuatro2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta4" name="cuatro3" onclick="pregunta4(cuatro3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta4" name="cuatro4" onclick="pregunta4(cuatro4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta4" name="cuatro5" onclick="pregunta4(cuatro5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta4" name="cuatro6" onclick="pregunta4(cuatro6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>5. Indica lo que se espera sea aprendido: el alumno debe ser consciente de lo que tiene que aprender</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta5" name="cinco1" onclick="pregunta5(cinco1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta5" name="cinco2" onclick="pregunta5(cinco2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta5" name="cinco3" onclick="pregunta5(cinco3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta5" name="cinco4" onclick="pregunta5(cinco4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta5" name="cinco5" onclick="pregunta5(cinco5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta5" name="cinco6" onclick="pregunta5(cinco6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>6. Coherente con los objetivos generales: los objetivos específicos deben ayudar a cumplir los objetivos generales</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta6" name="seis1" onclick="pregunta6(seis1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta6" name="seis2" onclick="pregunta6(seis2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta6" name="seis3" onclick="pregunta6(seis3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta6" name="seis4" onclick="pregunta6(seis4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta6" name="seis5" onclick="pregunta6(seis5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta6" name="seis6" onclick="pregunta6(seis6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;
	
	$questionnaire  .= '<label style="color: #2A5A5F; font-size: 12px; font-weight: bold;">TIEMPO DE APRENDIZAJE</label>';

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>7. El tiempo de duración estimada en el desarrollo de la unidad es adecuada al tiempo disponible</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta7" name="siete1" onclick="pregunta7(siete1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta7" name="siete2" onclick="pregunta7(siete2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta7" name="siete3" onclick="pregunta7(siete3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta7" name="siete4" onclick="pregunta7(siete4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta7" name="siete5" onclick="pregunta7(siete5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta7" name="siete6" onclick="pregunta7(siete6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$questionnaire  .= '<label class="info" style="color: #2A5A5F; font-family: cursive; font-size: 15px; font-weight: bold;">Categoría Diseño de Interfaz</label>';

	$questionnaire  .= '<label style="color: #2A5A5F; font-size: 12px; font-weight: bold;">SONIDO</label>';

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>8. Emplear el sonido solo cuando sea necesario (opcional para el usuario)</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta8" name="ocho1" onclick="pregunta8(ocho1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta8" name="ocho2" onclick="pregunta8(ocho2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta8" name="ocho3" onclick="pregunta8(ocho3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta8" name="ocho4" onclick="pregunta8(ocho4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta8" name="ocho5" onclick="pregunta8(ocho5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta8" name="ocho6" onclick="pregunta8(ocho6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>9. Informar de las caracteristicas del archivo de audio antes de su descarga (tamaño, tipos de conexión. etc)</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta9" name="nueve1" onclick="pregunta9(nueve1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta9" name="nueve2" onclick="pregunta9(nueve2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta9" name="nueve3" onclick="pregunta9(nueve3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta9" name="nueve4" onclick="pregunta9(nueve4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta9" name="nueve5" onclick="pregunta9(nueve5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta9" name="nueve6" onclick="pregunta9(nueve6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$questionnaire  .= '<label style="color: #2A5A5F; font-size: 12px; font-weight: bold;">VIDEO</label>';

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>10. Utilizar justificadamente, solo cuando pueda aportar algo</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta10" name="diez1" onclick="pregunta10(diez1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta10" name="diez2" onclick="pregunta10(diez2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta10" name="diez3" onclick="pregunta10(diez3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta10" name="diez4" onclick="pregunta10(diez4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta10" name="diez5" onclick="pregunta10(diez5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta10" name="diez6" onclick="pregunta10(diez6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>11. No tardar mucho tiempo en cargarse</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta11" name="once1" onclick="pregunta11(once1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta11" name="once2" onclick="pregunta11(once2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta11" name="once3" onclick="pregunta11(once3)"/>Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta11" name="once4" onclick="pregunta11(once4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta11" name="once5" onclick="pregunta11(once5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta11" name="once6" onclick="pregunta11(once6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>12. La imagen y el audio se presentan de forma clara</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta12" name="doce1" onclick="pregunta12(doce1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta12" name="doce2" onclick="pregunta12(doce2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta12" name="doce3" onclick="pregunta12(doce3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta12" name="doce4" onclick="pregunta12(doce4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta12" name="doce5" onclick="pregunta12(doce5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta12" name="doce6" onclick="pregunta12(doce6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$questionnaire  .= '<label class="info" style="color: #2A5A5F; font-family: cursive; font-size: 15px; font-weight: bold;">Categoria Diseño de navegación</label>';

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>13. La página deben ser censillas, no estar  recargadas con publicidad, animaciones, etc</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta13" name="trece1" onclick="pregunta13(trece1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta13" name="trece2" onclick="pregunta13(trece2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta13" name="trece3" onclick="pregunta13(trece3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta13" name="trece4" onclick="pregunta13(trece4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta13" name="trece5" onclick="pregunta13(trece5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta13" name="trece6" onclick="pregunta13(trece6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$t = new html_table();
	$row = new html_table_row();
	$cell1= '<label>14. El diseño es consistente en todas las pantallas (Tamaños, colores, iconos, tipos de letra, etc)</label>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$row = new html_table_row();
	$cell1=  '<div style="overflow:hidden;"><label style="width: 15%; float:left;"><input type="checkbox" class="pregunta14" name="catorce1" onclick="pregunta14(catorce1)" checked/>N/S</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta14" name="catorce2" onclick="pregunta14(catorce2)" />Muy deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left;  "><input type="checkbox" class="pregunta14" name="catorce3" onclick="pregunta14(catorce3)" />Deficiente</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta14" name="catorce4" onclick="pregunta14(catorce4)" />Aceptable</label>';
	$cell1.= '<label style="width: 15%;float:left; "><input type="checkbox" class="pregunta14" name="catorce5" onclick="pregunta14(catorce5)" />Alta</label>';
	$cell1.= '<label style="width: 15%;float:left;"><input type="checkbox" class="pregunta14" name="catorce6" onclick="pregunta14(catorce6)" />Muy Alta</label></div>';
	$row->cells = array($cell1);
	$t->data[] = $row;
	$t=html_writer::table($t);
	$questionnaire  .= $t;

	$questionnaire  .= '<button id="scorm_button" type="submit">'.get_string('send', 'block_trust_model').'</button>';
	$questionnaire  .= '</form></div>';

	echo $questionnaire;
	$urlAtras = new moodle_url('/blocks/trust_model/pix/atras.png');
	$imgAtras= '<img src="'.$urlAtras. '"alt="" />';
	$url = new moodle_url('/mod/scorm/view.php', array('a'=>$scorm));
	echo $imgAtras.''.html_writer::link( $url, get_string('returnScorm', 'block_trust_model'));
	echo $OUTPUT->box_end();
	echo $OUTPUT->footer();

}
?>
<script language="JavaScript" type="text/JavaScript">
function pregunta1(resp){
	var lst = document.getElementsByClassName("pregunta1");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta2(resp){
	var lst = document.getElementsByClassName("pregunta2");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta3(resp){
	var lst = document.getElementsByClassName("pregunta3");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta4(resp){
	var lst = document.getElementsByClassName("pregunta4");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta5(resp){
	var lst = document.getElementsByClassName("pregunta5");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta6(resp){
	var lst = document.getElementsByClassName("pregunta6");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta7(resp){
	var lst = document.getElementsByClassName("pregunta7");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta8(resp){
	var lst = document.getElementsByClassName("pregunta8");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta9(resp){
	var lst = document.getElementsByClassName("pregunta9");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta10(resp){
	var lst = document.getElementsByClassName("pregunta10");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta11(resp){
	var lst = document.getElementsByClassName("pregunta11");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta12(resp){
	var lst = document.getElementsByClassName("pregunta12");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta13(resp){
	var lst = document.getElementsByClassName("pregunta13");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}

function pregunta14(resp){
	var lst = document.getElementsByClassName("pregunta14");
	for (var i=0; i<lst.length; i++) {
		if (lst[i].name == resp.name) { 
			lst[i].checked =true;
		}else{
			lst[i].checked =false;
		}
	}
}
</script>
	