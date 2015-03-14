<?php
defined('MOODLE_INTERNAL') || die();

//Funciones para el parametro F1W1
function insert_history_forum($user,$course,$forum,$discussion,$post,$post_user,$action_mc,$value_date) {
	global $DB;
    $date='date';
		if(!$DB->record_exists('trust_f1w1_history_forum', array('user_id' => $user,'course_id' => $course,'forum_id' => $forum,
								'discussions_id' => $discussion,'posts_id' => $post, 'posts_user' => $post_user))){
		//Crea un registro trust_f1w1_history_forum
		$f1w1_history_forum = new stdClass ();   
		$f1w1_history_forum->user_id = $user; 				
		$f1w1_history_forum->course_id = $course;   
		$f1w1_history_forum->forum_id = $forum;
		$f1w1_history_forum->discussions_id = $discussion;
		$f1w1_history_forum->posts_id = $post;
		$f1w1_history_forum->posts_user = $post_user;
		$f1w1_history_forum->action = $action_mc;
		$f1w1_history_forum->$date = $value_date;
		return $DB->insert_record('trust_f1w1_history_forum', $f1w1_history_forum);
		}
}

function insert_history_quiz($user,$course,$quiz,$attempts,$attempts_user,$action_mc,$value_date) {
	global $DB;
    $date='date';
		//Crea un registro trust_f1w1_history_quiz
		if(!$DB->record_exists('trust_f1w1_history_quiz', array('user_id' => $user,'course_id' => $course,'quiz_id' => $quiz,
								'attempts_id' => $attempts,'attempts_user' => $attempts_user))){
		$f1w1_history_quiz = new stdClass ();   
		$f1w1_history_quiz->user_id = $user; 				
		$f1w1_history_quiz->course_id = $course;   
		$f1w1_history_quiz->quiz_id = $quiz;
		$f1w1_history_quiz->attempts_id = $attempts;
		$f1w1_history_quiz->attempts_user = $attempts_user;
		$f1w1_history_quiz->action = $action_mc;
		$f1w1_history_quiz->$date = $value_date;
		return $DB->insert_record('trust_f1w1_history_quiz', $f1w1_history_quiz);
		}
	
}
function insert_history_assign($user,$course,$assign,$assign_user,$comment_user, $action_mc,$value_date) {
	global $CFG, $DB;
    $date='date';
	
	if(!$DB->record_exists('trust_f1w1_history_assign', array('user_id' => $user,'course_id' => $course,'assing_id' => $assign,
							'assing_user' => $assign_user, 'comments_user' => $comment_user)))
	{
		//Crea un registro trust_f1w1_history_assign
		$f1w1_history_assign = new stdClass ();   
		$f1w1_history_assign->user_id = $user; 				
		$f1w1_history_assign->course_id = $course;   
		$f1w1_history_assign->assing_id = $assign;
		$f1w1_history_assign->assing_user = $assign_user;
		$f1w1_history_assign->comments_user = $comment_user;
		$f1w1_history_assign->action = $action_mc;
		$f1w1_history_assign->$date = $value_date;
		return $DB->insert_record('trust_f1w1_history_assign', $f1w1_history_assign);
	}
}
//Funciones para F2W2
function tpc_reputacion() {
	global $DB;
		
	$tpc =  $DB -> get_records_sql('SELECT * FROM {trust_f2w2_tpc}');
	if(empty($tcp)){
		//Dimensión Me gusta
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'like';
		$tcp->forum = +1; 				
		$tcp->quiz = +1;   
		$tcp->assign = +1;
		$tcp->probability = '1.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'like';
		$tcp->forum = +1; 				
		$tcp->quiz = +1;   
		$tcp->assign = -1;
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'like';
		$tcp->forum = +1; 				
		$tcp->quiz = -1;   
		$tcp->assign = +1;
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'like';
		$tcp->forum = +1; 				
		$tcp->quiz = -1;   
		$tcp->assign = -1;
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'like';
		$tcp->forum = -1; 				
		$tcp->quiz = +1;   
		$tcp->assign = +1;
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'like';
		$tcp->forum = -1; 				
		$tcp->quiz = +1;   
		$tcp->assign = -1;
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'like';
		$tcp->forum = -1; 				
		$tcp->quiz = -1;   
		$tcp->assign = +1;
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'like';
		$tcp->forum = -1; 				
		$tcp->quiz = -1;   
		$tcp->assign = -1;
		$tcp->probability = '0.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
	
		//Dimensión No me gusta
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'notlike';
		$tcp->forum = +1; 				
		$tcp->quiz = +1;   
		$tcp->assign = +1;
		$tcp->probability = '0.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'notlike';
		$tcp->forum = +1; 				
		$tcp->quiz = +1;   
		$tcp->assign = -1;
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'notlike';
		$tcp->forum = +1; 				
		$tcp->quiz = -1;   
		$tcp->assign = +1;
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'notlike';
		$tcp->forum = +1; 				
		$tcp->quiz = -1;   
		$tcp->assign = -1;
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'notlike';
		$tcp->forum = -1; 				
		$tcp->quiz = +1;   
		$tcp->assign = +1;
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'notlike';
		$tcp->forum = -1; 				
		$tcp->quiz = +1;   
		$tcp->assign = -1;
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'notlike';
		$tcp->forum = -1; 				
		$tcp->quiz = -1;   
		$tcp->assign = +1;
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'notlike';
		$tcp->forum = -1; 				
		$tcp->quiz = -1;   
		$tcp->assign = -1;
		$tcp->probability = '1.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
	
	}
}

function inferencia_reputacion() {
	global $CFG, $DB;
    $date='date';
	
}
