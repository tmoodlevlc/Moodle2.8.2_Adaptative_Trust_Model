<?php
require_once('../../config.php');
//require_once('lib.php');

//require_login(0, false);

if (isguestuser()) {
    redirect($CFG->wwwroot);
}

$url = new moodle_url('/blocks/trust_model/index.php', array('user' => $USER->id));
$PAGE->set_url($url);

// Disable message notification popups while the user is viewing their messages
$PAGE->set_pagelayout('standard');
$PAGE->set_context(context_user::instance($USER->id));
$PAGE->navbar->add("TrustModel");
$tm = get_string('pluginname', 'block_trust_model');
$PAGE->set_title("{$SITE->shortname}: $tm");
$PAGE->set_heading("{$SITE->shortname}: $tm");

//now the page contents
echo $OUTPUT->header();
echo $OUTPUT->box_start();
echo $OUTPUT->box_end();
echo $OUTPUT->footer();


