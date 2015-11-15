<?php
require_once('../../config.php');
global $DB, $USER;
if (isguestuser()) {
    redirect($CFG->wwwroot);
}
//Impresion
$url = new moodle_url('/blocks/trust_model/F5W5_Security.php');
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_user::instance($USER->id));
$tm = get_string('pluginname', 'block_trust_model');
$PAGE->navbar->add($tm);
$security = get_string('security', 'block_trust_model');
$PAGE->navbar->add($security);
$PAGE->set_title("{$SITE->shortname}: $tm");
$PAGE->set_heading("{$SITE->shortname}: $tm");
echo $OUTPUT->header();
echo $OUTPUT->box_start();
echo html_writer::start_tag('div', array('class' => 'mdl-align'));
echo html_writer::tag('h4', get_string('securityParameters', 'block_trust_model'));
echo html_writer::end_tag('div');
//Variables
$listasecurity =  $DB->get_records_sql('SELECT * FROM {trust_f5w5_security} ORDER BY id ASC');	
//Tabla
$t = new html_table();
foreach($listasecurity as $s){
	$row = new html_table_row();
	if($s->estado_security=='true'){
		$cell1= '<input type="checkbox" name="'.$s->id.'" checked/><label>'.$s->factor_security.'</label>';
	}else{
		$cell1= '<input type="checkbox" name="'.$s->id.'" /><label>'.$s->factor_security.'</label>';
	}
	
	if($s->factor_security=='DNSSEC' || $s->factor_security=='IPSEC' || $s->factor_security=='SSL' || $s->factor_security=='HTTPS'){
		$cell2= '';
	}else{
		$cell2= html_writer::link(new moodle_url('/blocks/trust_model/F5W5_SecuritySave.php', array('id' => $s->id, 'opc'=>2)), get_string('delete', 'block_trust_model'));
	}
	
	$row->cells = array($cell1, $cell2);
	$t->data[] = $row;
}
$t=html_writer::table($t);
$check  = '<div>';
$check  .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F5W5_SecuritySave.php?opc=3">';
$check  .= $t;
$check  .= '<button id="security_button" type="submit" title="'.get_string('saveConfig', 'block_trust_model').'">'.get_string('save', 'block_trust_model').'</button>';
$check  .= '</form></div>';
echo $check;

$form  = '<div>';
$form .= '<form method="post" action="'.$CFG->wwwroot.'/blocks/trust_model/F5W5_SecuritySave.php?opc=1" style="display:inline"><fieldset class="invisiblefieldset">';
$form .= '<legend><label>'.get_string('addSecurity', 'block_trust_model').'</label></legend>';
$form .= '<input id="security_name" name="nombre" type="text" size="20" required/>';
$form .= '<button id="security_button" type="submit" title='.get_string('saveSecurity', 'block_trust_model').'>'.get_string('add', 'block_trust_model').'</button><br />';
$form .= '</fieldset></form></div>';
echo $form;

echo $OUTPUT->box_end();
echo $OUTPUT->footer();





