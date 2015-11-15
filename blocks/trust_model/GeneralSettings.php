<?php
require_once('../../config.php');
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');
global $DB, $USER;

$opc = optional_param('opc', '',PARAM_TEXT);
if (isguestuser()) {
    redirect($CFG->wwwroot);
}

if($opc=='save'){
	if(isset($_POST['f1w1'])){//Ingresa si esta Activo
		$f1w1='true';
	}else{$f1w1='false';}
	
	if(isset($_POST['f2w2'])){
		$f2w2='true';
	}else{$f2w2='false';}
	
	if(isset($_POST['f3w3'])){
		$f3w3='true';
	}else{$f3w3='false';}
	
	if(isset($_POST['f4w4'])){
		$f4w4='true';
	}else{$f4w4='false';}
	
	if(isset($_POST['f5w5'])){
		$f5w5='true';
	}else{$f5w5='false';}
	
	if(isset($_POST['f6w6'])){
		$f6w6='true';
	}else{$f6w6='false';}
	
	if(isset($_POST['f7w7'])){
		$f7w7='true';
	}else{$f7w7='false';}
	
	if(isset($_POST['f8w8'])){
		$f8w8='true';
	}else{$f8w8='false';}
	
	//Guardar en la base de datos
	save_general_settings($f1w1, $f2w2, $f3w3, $f4w4, $f5w5, $f6w6, $f7w7, $f8w8);
	
	//Guardar los pesos
	$p1f1w1=$_POST['p1f1w1'];
	save_general_settings_weights('p1f1w1', $p1f1w1);
	$p2f1w1=$_POST['p2f1w1'];
	save_general_settings_weights('p2f1w1', $p2f1w1);
	
	$p1f2w2=$_POST['p1f2w2'];
	save_general_settings_weights('p1f2w2', $p1f2w2);
	$p2f2w2=$_POST['p2f2w2'];
	save_general_settings_weights('p2f2w2', $p2f2w2);
	
	$p1f3w3=$_POST['p1f3w3'];
	save_general_settings_weights('p1f3w3', $p1f3w3);
	$p2f3w3=$_POST['p2f3w3'];
	save_general_settings_weights('p2f3w3', $p2f3w3);
	$p3f3w3=$_POST['p3f3w3'];
	save_general_settings_weights('p3f3w3', $p3f3w3);
	
	$p1f4w4=$_POST['p1f4w4'];
	save_general_settings_weights('p1f4w4', $p1f4w4);
	$p2f4w4=$_POST['p2f4w4'];
	save_general_settings_weights('p2f4w4', $p2f4w4);
	
	$p1f5w5=$_POST['p1f5w5'];
	save_general_settings_weights('p1f5w5', $p1f5w5);
	$p2f5w5=$_POST['p2f5w5'];
	save_general_settings_weights('p2f5w5', $p2f5w5);
	
	$p1f6w6=$_POST['p1f6w6'];
	save_general_settings_weights('p1f6w6', $p1f6w6);
	$p2f6w6=$_POST['p2f6w6'];
	save_general_settings_weights('p2f6w6', $p2f6w6);
	
	$p1f7w7=$_POST['p1f7w7'];
	save_general_settings_weights('p1f7w7', $p1f7w7);
	$p2f7w7=$_POST['p2f7w7'];
	save_general_settings_weights('p2f7w7', $p2f7w7);
	
	$p1f8w8=$_POST['p1f8w8'];
	save_general_settings_weights('p1f8w8', $p1f8w8);
	$p2f8w8=$_POST['p2f8w8'];
	save_general_settings_weights('p2f8w8', $p2f8w8);
	$p3f8w8=$_POST['p3f8w8'];
	save_general_settings_weights('p3f8w8', $p3f8w8);
	
	//Redirigir a la pagina
	redirect(new moodle_url('/blocks/trust_model/GeneralSettings.php'));
	
}else{

	$url = new moodle_url('/blocks/trust_model/GeneralSettings.php');
	$PAGE->set_url($url);
	$PAGE->set_pagelayout('standard');
	$PAGE->set_context(context_user::instance($USER->id));
	$tm = get_string('pluginname', 'block_trust_model');
	$PAGE->navbar->add($tm);
	$PAGE->navbar->add(get_string('generalSettings', 'block_trust_model'));
	$PAGE->set_title("{$SITE->shortname}: $tm");
	$PAGE->set_heading("{$SITE->shortname}: $tm");
	echo $OUTPUT->header();
	echo $OUTPUT->box_start();
	echo html_writer::start_tag('div', array('class' => 'mdl-align'));
	echo html_writer::tag('h4', get_string('pluginname', 'block_trust_model'));
	echo html_writer::end_tag('div');
	echo '<label style="color: #2A5A5F; font-size: 13px; font-weight: bold;">'.get_string('themegeneralSettings', 'block_trust_model').'<spam style="font-size: 11px;"> (Min 0 - Max 1)</spam></label>';;
	//Variables
	$general_settings =  $DB->get_record_sql('SELECT * FROM {trust_general_settings} WHERE codigo IS NOT NULL');	
	$t = new html_table();
	if($general_settings){
		//F1W1
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f1w1" onclick="check(f1w1)" checked/><label title='.get_string('obligatoryfield', 'block_trust_model').'>'.get_string('experience', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$p1f1w1 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f1w1'));
		$p2f1w1 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f1w1'));
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto"  value="'.$p1f1w1->value.'" name="p1f1w1"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').'<br><input type="texto"  value="'.$p2f1w1->value.'" name="p2f1w1"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		//F2W2
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f2w2" onclick="check(f2w2)" checked/><label title='.get_string('obligatoryfield', 'block_trust_model').'>'.get_string('reputation', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$p1f2w2 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f2w2'));
		$p2f2w2 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f2w2'));
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto" value="'.$p1f2w2->value.'" name="p1f2w2"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto" value="'.$p2f2w2->value.'" name="p2f2w2"/></div>
				</div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		//F3W3
		$row = new html_table_row();
		if($general_settings->f3w3=='true'){
			$cell1= '<input  type="checkbox" name="f3w3" checked/><label>'.get_string('role', 'block_trust_model').'</label>';
		}else{
			$cell1= '<input type="checkbox" name="f3w3" /><label>'.get_string('role', 'block_trust_model').'</label>';
		}
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$p1f3w3 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f3w3'));
		$p2f3w3 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f3w3'));
		$p3f3w3 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p3f3w3'));
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 30%; float:left;">'.get_string('single_activity_format', 'block_trust_model').'<br><input type="texto" value="'.$p1f3w3->value.'" name="p1f3w3"/></div>
					<div style="width: 35%; float:left;">'.get_string('social_format', 'block_trust_model').'<br><input type="texto" value="'.$p2f3w3->value.'" name="p2f3w3"/></div>
					<div style="width: 35%; float:left;">'.get_string('topic_week_format', 'block_trust_model').'<br><input type="texto" value="'.$p3f3w3->value.'" name="p3f3w3"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		//F4W4
		$row = new html_table_row();
		if($general_settings->f4w4=='true'){
			$cell1= '<input type="checkbox" name="f4w4" checked/><label>'.get_string('knowledge', 'block_trust_model').'</label>';
		}else{
			$cell1= '<input type="checkbox" name="f4w4" /><label>'.get_string('knowledge', 'block_trust_model').'</label>';
		}
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$p1f4w4 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f4w4'));
		$p2f4w4 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f4w4'));
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto" value="'.$p1f4w4->value.'" name="p1f4w4"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').'<br><input type="texto" value="'.$p2f4w4->value.'" name="p2f4w4"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		//F5W5
		$row = new html_table_row();
		if($general_settings->f5w5=='true'){
			$cell1= '<input type="checkbox" name="f5w5" checked/><label>'.get_string('security', 'block_trust_model').'</label>';
		}else{
			$cell1= '<input type="checkbox" name="f5w5" /><label>'.get_string('security', 'block_trust_model').'</label>';
		}
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$p1f5w5 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f5w5'));
		$p2f5w5 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f5w5'));
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">DNSSEC <br><input type="texto" value="'.$p1f5w5->value.'" name="p1f5w5"/></div>
					<div style="width: 50%; float:left;">'.get_string('other', 'block_trust_model').'<br><input type="texto" value="'.$p2f5w5->value.'" name="p2f5w5"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		//F6W6
		$row = new html_table_row();
		if($general_settings->f6w6=='true'){
			$cell1= '<input type="checkbox" name="f6w6" checked/><label>'.get_string('quality', 'block_trust_model').'</label>';
		}else{
			$cell1= '<input type="checkbox" name="f6w6" /><label>'.get_string('quality', 'block_trust_model').'</label>';
		}
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$p1f6w6 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f6w6'));
		$p2f6w6 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f6w6'));
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto" value="'.$p1f6w6->value.'" name="p1f6w6"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').'<br><input type="texto" value="'.$p2f6w6->value.'" name="p2f6w6"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		//F7W7
		$row = new html_table_row();
		if($general_settings->f7w7=='true'){
			$cell1= '<input type="checkbox" name="f7w7" checked/><label>'.get_string('institutional', 'block_trust_model').'</label>';
		}else{
			$cell1= '<input type="checkbox" name="f7w7" /><label>'.get_string('institutional', 'block_trust_model').'</label>';
		}
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$p1f7w7 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f7w7'));
		$p2f7w7 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f7w7'));
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto" value="'.$p1f7w7->value.'" name="p1f7w7"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').'<br><input type="texto" value="'.$p2f7w7->value.'" name="p2f7w7"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		//F8W8
		$row = new html_table_row();
		if($general_settings->f8w8=='true'){
			$cell1= '<input type="checkbox" name="f8w8" checked/><label>'.get_string('kin', 'block_trust_model').'</label>';
		}else{
			$cell1= '<input type="checkbox" name="f8w8" /><label>'.get_string('kin', 'block_trust_model').'</label>';
		}
		$row->cells = array($cell1);
		$t->data[] = $row;

		$p1f8w8 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f8w8'));
		$p2f8w8 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f8w8'));
		$p3f8w8 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p3f8w8'));
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 30%; float:left;">'.get_string('assigment_is', 'block_trust_model').' (0-4.9) <br><input type="texto"  value="'.$p1f8w8->value.'" name="p1f8w8"/></div>
					<div style="width: 35%; float:left;">'.get_string('assigment_is', 'block_trust_model').' (5-7.9) <br><input type="texto" value="'.$p2f8w8->value.'" name="p2f8w8"/></div>
					<div style="width: 35%; float:left;">'.get_string('assigment_is', 'block_trust_model').' (8-10) <br><input type="texto" value="'.$p3f8w8->value.'" name="p3f8w8"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$t=html_writer::table($t);
	
	}else{
		//F1W1
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f1w1" onclick="check(f1w1)" checked/><label title='.get_string('obligatoryfield', 'block_trust_model').'>'.get_string('experience', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto"  value="0.50" name="p1f1w1"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').'<br><input type="texto"  value="1.0" name="p2f1w1"/></div>
				</div>';
		$row->cells = array($cell1);
		$t->data[] = $row;

		//F2W2
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f2w2" onclick="check(f2w2)" checked/><label title='.get_string('obligatoryfield', 'block_trust_model').'>'.get_string('reputation', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto" value="0.50" name="p1f2w2"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').'<br><input type="texto" value="1.0" name="p2f2w2"/></div>
				</div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
	
		//F3W3
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f3w3" checked/><label>'.get_string('role', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 30%; float:left;">'.get_string('single_activity_format', 'block_trust_model').'<br><input type="texto" value="0.5" name="p1f3w3"/></div>
					<div style="width: 35%; float:left;">'.get_string('social_format', 'block_trust_model').'<br><input type="texto" value="0.75" name="p2f3w3"/></div>
					<div style="width: 35%; float:left;">'.get_string('topic_week_format', 'block_trust_model').'<br><input type="texto" value="1.0" name="p3f3w3"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		//F4W4
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f4w4" checked/><label>'.get_string('knowledge', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto" value="0.5" name="p1f4w4"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').'<br><input type="texto" value="1.0" name="p2f4w4"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		//F5W5
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f5w5" checked/><label>'.get_string('security', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">DNSSEC <br><input type="texto" value="1.0" name="p1f5w5"/></div>
					<div style="width: 50%; float:left;">'.get_string('other', 'block_trust_model').'<br><input type="texto" value="0.5" name="p2f5w5"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;

		//F6W6
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f6w6" checked/><label>'.get_string('quality', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').' LO <br><input type="texto" value="1.0" name="p1f6w6"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').' LO <br><input type="texto" value="0.5" name="p2f6w6"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		//F7W7
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f7w7" checked/><label>'.get_string('institutional', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 50%; float:left;">'.get_string('role_student', 'block_trust_model').'<br><input type="texto" value="0.5" name="p1f7w7"/></div>
					<div style="width: 50%; float:left;">'.get_string('role_teacher', 'block_trust_model').'<br><input type="texto" value="1.0" name="p2f7w7"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		//F8W8
		$row = new html_table_row();
		$cell1= '<input type="checkbox" name="f8w8" checked/><label>'.get_string('kin', 'block_trust_model').'</label>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$row = new html_table_row();
		$cell1= '<div style="overflow:hidden;">
					<div style="width: 30%; float:left;">'.get_string('assigment_is', 'block_trust_model').' (0-4.9) <br><input type="texto"  value="0.25" name="p1f8w8"/></div>
					<div style="width: 35%; float:left;">'.get_string('assigment_is', 'block_trust_model').' (5-7.9) <br><input type="texto" value="0.5" name="p2f8w8"/></div>
					<div style="width: 35%; float:left;">'.get_string('assigment_is', 'block_trust_model').' (8-10) <br><input type="texto"  value="1.0" name="p3f8w8"/></div>
				 </div>';
		$row->cells = array($cell1);
		$t->data[] = $row;
		
		$t=html_writer::table($t);
	}
	
	$check  = '<div>';
	$check  .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/GeneralSettings.php?opc=save">';
	$check  .= $t;
	$check  .= '<button id="config_button" type="submit" title="'.get_string('saveConfig', 'block_trust_model').'">'.get_string('save', 'block_trust_model').'</button>';
	$check  .= '</form></div>';
	echo $check;
	echo $OUTPUT->box_end();
	echo $OUTPUT->footer();
}

?>

<script language="JavaScript" type="text/JavaScript">
function check(resp){
	resp.checked =true;	
}

</script>
	