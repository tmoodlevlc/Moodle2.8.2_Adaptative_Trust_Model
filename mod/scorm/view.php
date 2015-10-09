<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

require_once("../../config.php");
require_once($CFG->dirroot.'/mod/scorm/locallib.php');
require_once($CFG->dirroot.'/course/lib.php');

$id = optional_param('id', '', PARAM_INT);       // Course Module ID, or
$a = optional_param('a', '', PARAM_INT);         // scorm ID
$organization = optional_param('organization', '', PARAM_INT); // organization ID.
$action = optional_param('action', '', PARAM_ALPHA);
$preventskip = optional_param('preventskip', '', PARAM_INT); // Prevent Skip view, set by javascript redirects.

if (!empty($id)) {
    if (! $cm = get_coursemodule_from_id('scorm', $id, 0, true)) {
        print_error('invalidcoursemodule');
    }
    if (! $course = $DB->get_record("course", array("id" => $cm->course))) {
        print_error('coursemisconf');
    }
    if (! $scorm = $DB->get_record("scorm", array("id" => $cm->instance))) {
        print_error('invalidcoursemodule');
    }
} else if (!empty($a)) {
    if (! $scorm = $DB->get_record("scorm", array("id" => $a))) {
        print_error('invalidcoursemodule');
    }
    if (! $course = $DB->get_record("course", array("id" => $scorm->course))) {
        print_error('coursemisconf');
    }
    if (! $cm = get_coursemodule_from_instance("scorm", $scorm->id, $course->id, true)) {
        print_error('invalidcoursemodule');
    }
} else {
    print_error('missingparameter');
}

$url = new moodle_url('/mod/scorm/view.php', array('id' => $cm->id));
if ($organization !== '') {
    $url->param('organization', $organization);
}
$PAGE->set_url($url);
$forcejs = get_config('scorm', 'forcejavascript');
if (!empty($forcejs)) {
    $PAGE->add_body_class('forcejavascript');
}

require_login($course, false, $cm);

$context = context_course::instance($course->id);
$contextmodule = context_module::instance($cm->id);

$launch = false; // Does this automatically trigger a launch based on skipview.
if (!empty($scorm->popup)) {
    $orgidentifier = '';

    $scoid = 0;
    $orgidentifier = '';
    if ($sco = scorm_get_sco($scorm->launch, SCO_ONLY)) {
        if (($sco->organization == '') && ($sco->launch == '')) {
            $orgidentifier = $sco->identifier;
        } else {
            $orgidentifier = $sco->organization;
        }
        $scoid = $sco->id;
    }

    if (empty($preventskip) && $scorm->skipview >= SCORM_SKIPVIEW_FIRST &&
        has_capability('mod/scorm:skipview', $contextmodule) &&
        !has_capability('mod/scorm:viewreport', $contextmodule)) { // Don't skip users with the capability to view reports.

        // Do we launch immediately and redirect the parent back ?
        if ($scorm->skipview == SCORM_SKIPVIEW_ALWAYS || !scorm_has_tracks($scorm->id, $USER->id)) {
            $launch = true;
        }
    }
    // Redirect back to the section with one section per page ?

    $courseformat = course_get_format($course)->get_course();
    $sectionid = '';
    if (isset($courseformat->coursedisplay) && $courseformat->coursedisplay == COURSE_DISPLAY_MULTIPAGE) {
        $sectionid = $cm->sectionnum;
    }
    if ($courseformat->format == 'singleactivity') {
        $courseurl = $url->out(false, array('preventskip' => '1'));
    } else {
        $courseurl = course_get_url($course, $sectionid)->out(false);
    }
    $PAGE->requires->data_for_js('scormplayerdata', Array('launch' => $launch,
                                                           'currentorg' => $orgidentifier,
                                                           'sco' => $scoid,
                                                           'scorm' => $scorm->id,
                                                           'courseurl' => $courseurl,
                                                           'cwidth' => $scorm->width,
                                                           'cheight' => $scorm->height,
                                                           'popupoptions' => $scorm->options), true);
    $PAGE->requires->string_for_js('popupsblocked', 'scorm');
    $PAGE->requires->string_for_js('popuplaunched', 'scorm');
    $PAGE->requires->js('/mod/scorm/view.js', true);
}

if (isset($SESSION->scorm)) {
    unset($SESSION->scorm);
}

$strscorms = get_string("modulenameplural", "scorm");
$strscorm  = get_string("modulename", "scorm");

$shortname = format_string($course->shortname, true, array('context' => $context));
$pagetitle = strip_tags($shortname.': '.format_string($scorm->name));

// Trigger module viewed event.
$event = \mod_scorm\event\course_module_viewed::create(array(
    'objectid' => $scorm->id,
    'context' => $contextmodule,
));
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('scorm', $scorm);
$event->add_record_snapshot('course_modules', $cm);
$event->trigger();

if (empty($preventskip) && empty($launch) && (has_capability('mod/scorm:skipview', $contextmodule))) {
    scorm_simple_play($scorm, $USER, $contextmodule, $cm->id);
}

// Print the page header.

$PAGE->set_title($pagetitle);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();

//---------------------Descargar Paquete Scorm y validar------------------------------------//
$general_settings =  $DB->get_record_sql('SELECT * FROM {trust_general_settings} WHERE codigo IS NOT NULL');//CONFIGURACION GENERAL DEL MODULO
if($general_settings){
	if($general_settings->f6w6=='true'){
		//Descargar archivo zip
		$context = context_module::instance($cm->id);
		$fs = get_file_storage();
		$packagefile = $fs->get_file($context->id, 'mod_scorm', 'package', 0, '/', $scorm->reference);
		$url  = moodle_url::make_pluginfile_url( $packagefile->get_contextid () , $packagefile -> get_component() , $packagefile->get_filearea () ,null ,$packagefile -> get_filepath () , $packagefile->get_filename ()) ;
		echo html_writer::link( $url, get_string('download', 'block_trust_model')).'<br>'; 
		
		//Mostrar el puntaje promedio, si ya valido el paquete Scorm
		if($DB->record_exists('trust_f6w6_quality', array('scorm_id' => $scorm->id,'user_validate' => $USER->id, 'course_id' => $course->id))){											
				
			$lstScorm = $DB->get_records('trust_f6w6_quality',  array ('course_id'=>$course->id, 'scorm_id'=>$scorm->id));
			$teaching_curricular=0;
			$interface_design=0;
			$navigation_design=0;
			$suma=0;
			$promedioCategoria1=0;
			$promedioCategoria2= 0;
			$promedioCategoria3=0;
			$promedio=0;
			//Obtengo la suma
			foreach($lstScorm  as $s){
				$teaching_curricular = $teaching_curricular + $s->teaching_curricular;
				$interface_design = $interface_design + $s->interface_design;
				$navigation_design = $navigation_design + $s->navigation_design;
				$suma = $suma + $s->value;
			}
			//Calculo los promedios
			if($lstScorm){
				$promedioCategoria1=$teaching_curricular/count($lstScorm);
				$promedioCategoria2= $interface_design/count($lstScorm);
				$promedioCategoria3= $navigation_design/count($lstScorm);
				$promedio=$suma/count($lstScorm);
			}
			//Segun el promedio en que intervalo se encuentra
			if ($promedioCategoria1 <= 1){
				$stringCategoria1=get_string('scale0', 'block_trust_model');
			}else if($promedioCategoria1 > 1 && $promedioCategoria1 <= 1.5 ){
				$stringCategoria1=get_string('scale1', 'block_trust_model');
			}else if($promedioCategoria1 > 1.5 && $promedioCategoria1 <= 2.5 ){
				$stringCategoria1=get_string('scale2', 'block_trust_model');
			}else if($promedioCategoria1 > 2.5 && $promedioCategoria1 <= 3.5){
				$stringCategoria1=get_string('scale3', 'block_trust_model');
			}else if($promedioCategoria1 >3.5 && $promedioCategoria1 <= 4.5){
				$stringCategoria1=get_string('scale4', 'block_trust_model');
			}else if($promedioCategoria1 >4.5 && $promedioCategoria1 <= 5){
				$stringCategoria1=get_string('scale5', 'block_trust_model');
			}
			
			if ($promedioCategoria2 <= 1){
				$stringCategoria2=get_string('scale0', 'block_trust_model');
			}else if($promedioCategoria2 > 1 && $promedioCategoria2 <= 1.5 ){
				$stringCategoria2=get_string('scale1', 'block_trust_model');
			}else if($promedioCategoria2 > 1.5 && $promedioCategoria2 <= 2.5 ){
				$stringCategoria2=get_string('scale2', 'block_trust_model');
			}else if($promedioCategoria2 > 2.5 && $promedioCategoria2 <= 3.5){
				$stringCategoria2=get_string('scale3', 'block_trust_model');
			}else if($promedioCategoria2 >3.5 && $promedioCategoria2 <= 4.5){
				$stringCategoria2=get_string('scale4', 'block_trust_model');
			}else if($promedioCategoria2 >4.5 && $promedioCategoria2 <= 5){
				$stringCategoria2=get_string('scale5', 'block_trust_model');
			}
			
			if ($promedioCategoria3 <= 1){
				$stringCategoria3=get_string('scale0', 'block_trust_model');
			}else if($promedioCategoria3 > 1 && $promedioCategoria3 <= 1.5 ){
				$stringCategoria3=get_string('scale1', 'block_trust_model');
			}else if($promedioCategoria3 > 1.5 && $promedioCategoria3 <= 2.5 ){
				$stringCategoria3=get_string('scale2', 'block_trust_model');
			}else if($promedioCategoria3 > 2.5 && $promedioCategoria3 <= 3.5){
				$stringCategoria2=get_string('scale3', 'block_trust_model');
			}else if($promedioCategoria3 >3.5 && $promedioCategoria3 <= 4.5){
				$stringCategoria3=get_string('scale4', 'block_trust_model');
			}else if($promedioCategoria3 >4.5 && $promedioCategoria3 <= 5){
				$stringCategoria3=get_string('scale5', 'block_trust_model');
			}
			
			if ($promedio <= 1){
				$string=get_string('scale0', 'block_trust_model');
			}else if($promedio > 1 && $promedio <= 1.5 ){
				$string=get_string('scale1', 'block_trust_model');
			}else if($promedio > 1.5 && $promedio <= 2.5 ){
				$string=get_string('scale2', 'block_trust_model');
			}else if($promedio > 2.5 && $promedio <= 3.5){
				$string=get_string('scale3', 'block_trust_model');
			}else if($promedio >3.5 && $promedio <= 4.5){
				$string=get_string('scale4', 'block_trust_model');
			}else if($promedio >4.5 && $promedio <= 5){
				$string=get_string('scale5', 'block_trust_model');
			}
			
			echo '<label style="color: #2A5A5F; font-size: 13px; font-weight: bold;">'.get_string('scale_five-point', 'block_trust_model').'</label>';
			$t = new html_table();
			
			$row = new html_table_row();
			$cell1= get_string('category1', 'block_trust_model');
			$cell2= $stringCategoria1;
			$cell3= get_string('average', 'block_trust_model').': '.$promedioCategoria1;
			$row->cells = array($cell1, $cell2, $cell3);
			$t->data[] = $row;
			
			$row = new html_table_row();
			$cell1= get_string('category2', 'block_trust_model');
			$cell2= $stringCategoria2;
			$cell3= get_string('average', 'block_trust_model').': '.$promedioCategoria2;
			$row->cells = array($cell1, $cell2, $cell3);
			$t->data[] = $row;
			
			$row = new html_table_row();
			$cell1= get_string('category3', 'block_trust_model');
			$cell2= $stringCategoria3;
			$cell3= get_string('average', 'block_trust_model').': '.$promedioCategoria3;
			$row->cells = array($cell1, $cell2, $cell3);
			$t->data[] = $row;
			
			$row = new html_table_row();
			$cell1= get_string('ratingScorm', 'block_trust_model');
			$cell2= $string;
			$cell3= get_string('average', 'block_trust_model').': '.$promedio;
			$row->cells = array($cell1, $cell2, $cell3);
			$t->data[] = $row;
			
			echo html_writer::table($t);
			
		}else{
			//Validar cuestionario Scorm
			$validateQuestionnaire = new moodle_url('/blocks/trust_model/F6W6_QuestionnaireScorm.php', array ('s' => $scorm->id,'u' => $USER->id, 'c' => $course->id));
			echo html_writer::link( $validateQuestionnaire, get_string('validate', 'block_trust_model')); 

		}
	}
}

//------------------------------------------------------------------------------------------------------------------
	
echo $OUTPUT->heading(format_string($scorm->name));

if (!empty($action) && confirm_sesskey() && has_capability('mod/scorm:deleteownresponses', $contextmodule)) {
    if ($action == 'delete') {
        $confirmurl = new moodle_url($PAGE->url, array('action' => 'deleteconfirm'));
        echo $OUTPUT->confirm(get_string('deleteuserattemptcheck', 'scorm'), $confirmurl, $PAGE->url);
        echo $OUTPUT->footer();
        exit;
    } else if ($action == 'deleteconfirm') {
        // Delete this users attempts.
        $DB->delete_records('scorm_scoes_track', array('userid' => $USER->id, 'scormid' => $scorm->id));
        scorm_update_grades($scorm, $USER->id, true);
        echo $OUTPUT->notification(get_string('scormresponsedeleted', 'scorm'), 'notifysuccess');
    }
}

$currenttab = 'info';
require($CFG->dirroot . '/mod/scorm/tabs.php');

// Print the main part of the page.
$attemptstatus = '';
if (empty($launch) && ($scorm->displayattemptstatus == SCORM_DISPLAY_ATTEMPTSTATUS_ALL ||
         $scorm->displayattemptstatus == SCORM_DISPLAY_ATTEMPTSTATUS_ENTRY)) {
    $attemptstatus = scorm_get_attempt_status($USER, $scorm, $cm);
}
echo $OUTPUT->box(format_module_intro('scorm', $scorm, $cm->id).$attemptstatus, 'generalbox boxaligncenter boxwidthwide', 'intro');

$scormopen = true;
$timenow = time();
if (!empty($scorm->timeopen) && $scorm->timeopen > $timenow) {
    echo $OUTPUT->box(get_string("notopenyet", "scorm", userdate($scorm->timeopen)), "generalbox boxaligncenter");
    $scormopen = false;
}
if (!empty($scorm->timeclose) && $timenow > $scorm->timeclose) {
    echo $OUTPUT->box(get_string("expired", "scorm", userdate($scorm->timeclose)), "generalbox boxaligncenter");
    $scormopen = false;
}
if ($scormopen && empty($launch)) {
    scorm_view_display($USER, $scorm, 'view.php?id='.$cm->id, $cm);
}
if (!empty($forcejs)) {
    echo $OUTPUT->box(get_string("forcejavascriptmessage", "scorm"), "generalbox boxaligncenter forcejavascriptmessage");
}

if (!empty($scorm->popup)) {
    $PAGE->requires->js_init_call('M.mod_scormform.init');
}

echo $OUTPUT->footer();
