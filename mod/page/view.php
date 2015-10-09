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

/**
 * Page module version information
 *
 * @package mod_page
 * @copyright  2009 Petr Skoda (http://skodak.org)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot.'/mod/page/locallib.php');
require_once($CFG->libdir.'/completionlib.php');

$id      = optional_param('id', 0, PARAM_INT); // Course Module ID
$p       = optional_param('p', 0, PARAM_INT);  // Page instance ID
$inpopup = optional_param('inpopup', 0, PARAM_BOOL);

if ($p) {
    if (!$page = $DB->get_record('page', array('id'=>$p))) {
        print_error('invalidaccessparameter');
    }
    $cm = get_coursemodule_from_instance('page', $page->id, $page->course, false, MUST_EXIST);

} else {
    if (!$cm = get_coursemodule_from_id('page', $id)) {
        print_error('invalidcoursemodule');
    }
    $page = $DB->get_record('page', array('id'=>$cm->instance), '*', MUST_EXIST);
}

$course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/page:view', $context);

// Trigger module viewed event.
$event = \mod_page\event\course_module_viewed::create(array(
   'objectid' => $page->id,
   'context' => $context
));
$event->add_record_snapshot('course_modules', $cm);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('page', $page);
$event->trigger();

// Update 'viewed' state if required by completion system
require_once($CFG->libdir . '/completionlib.php');
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

$PAGE->set_url('/mod/page/view.php', array('id' => $cm->id));

$options = empty($page->displayoptions) ? array() : unserialize($page->displayoptions);

if ($inpopup and $page->display == RESOURCELIB_DISPLAY_POPUP) {
    $PAGE->set_pagelayout('popup');
    $PAGE->set_title($course->shortname.': '.$page->name);
    $PAGE->set_heading($course->fullname);
} else {
    $PAGE->set_title($course->shortname.': '.$page->name);
    $PAGE->set_heading($course->fullname);
    $PAGE->set_activity_record($page);
}


// =================Trust Model===========================
global $DB, $USER, $COURSE;
$user_id = $USER->id;
$course_id = $COURSE->id;
$page_id = $page->id;

//Obtener el id del usuario que ha creado el page
$select= "SELECT l.userid FROM  {logstore_standard_log} l, {course_modules} cm WHERE ";
$select.= "l.action='created' AND l.target='course_module' AND l.courseid=:courseid AND l.contextinstanceid = cm.id AND cm.course=:course_id AND cm.module='15' AND cm.instance=:pageid";												
$params = array();												
$params['courseid'] = $course_id;
$params['course_id'] = $course_id;
$params['pageid'] = $page_id;
$consulta= $DB -> get_record_sql("$select", $params, $limitfrom='', $limitnum='');

$page_user= $consulta->userid;

$like= get_string('like', 'block_trust_model');
$not_like= get_string('not_like', 'block_trust_model');

//Controla, no calificar un recurso creado por el mismo usuario
if($user_id != $page_user){
	$count = $DB->count_records('trust_f1w1_history_page', array('user_id' => $user_id,'course_id' => $course_id,'page_id' => $page_id, 'page_user' => $page_user));
	if($count==0){//Ingresa si no existe un registro, no evaluo 
		$url = $PAGE->url;
		$comandoTrust[] = html_writer::link(new moodle_url('/blocks/trust_model/F1W1_Previous_Experience.php', array('opc' => 6, 'u' => $user_id, 'c' => $course_id,'p' => $page_id,'pu' => $page_user, 'mc' => +1, 'url' => $url)),$like);
		$comandoTrust[] = html_writer::link(new moodle_url('/blocks/trust_model/F1W1_Previous_Experience.php', array('opc' => 6, 'u' => $user_id, 'c' => $course_id, 'p' => $page_id,'pu' => $page_user,'mc' => -1, 'url' => $url)),$not_like);
	}else{
		$comandoTrust[] = $like;
		$comandoTrust[] = $not_like;
	}
}else{
	$comandoTrust[] = $like;
	$comandoTrust[] = $not_like;
}
	
	$trust_model = html_writer::tag('div', implode(' | ', $comandoTrust), array('class'=>'commands')).html_writer::empty_tag('br');			
// =====================================================


echo $OUTPUT->header();
if (!isset($options['printheading']) || !empty($options['printheading'])) {
    echo $OUTPUT->heading(format_string($page->name), 2);
	// =====================Trust Model=====================
	echo $trust_model;
	// =====================================================
}

if (!empty($options['printintro'])) {
    if (trim(strip_tags($page->intro))) {
        echo $OUTPUT->box_start('mod_introbox', 'pageintro');
        echo format_module_intro('page', $page, $cm->id);
        echo $OUTPUT->box_end();
    }
}

$content = file_rewrite_pluginfile_urls($page->content, 'pluginfile.php', $context->id, 'mod_page', 'content', $page->revision);
$formatoptions = new stdClass;
$formatoptions->noclean = true;
$formatoptions->overflowdiv = true;
$formatoptions->context = $context;
$content = format_text($content, $page->contentformat, $formatoptions);
echo $OUTPUT->box($content, "generalbox center clearfix");

$strlastmodified = get_string("lastmodified");
echo "<div class=\"modified\">$strlastmodified: ".userdate($page->timemodified)."</div>";

echo $OUTPUT->footer();
