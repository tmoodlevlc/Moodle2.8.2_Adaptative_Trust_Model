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
function tabla_probabilidad_condicional_f2w2() {
	global $DB;
		
	$combinaciones =  $DB -> get_records_sql('SELECT * FROM {trust_f2w2_tpc}');
	if(empty($combinaciones)){
		//Dimensión Me gusta
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->probability = '1.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->probability = '0.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
	
		//Dimensión No me gusta
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->probability = '0.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->probability = '1.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
	
	}
}

function inferencia_f2w2($userid, $courseid) {
	global $DB;
	
	//DIMENSION ENTRADA
	$reputation_positive=0;
	$reputation_negative=0;
	
	//Obtengo las evidencias(Nodos Padres - probabilidad a priori)
	
	$evidenceForum=$DB->get_record('trust_f1w1_forum', array('user_id' => $userid, 'course_id' => $courseid));
	if($evidenceForum){
		if($evidenceForum -> i_like == 0 && $evidenceForum -> not_like==0){
			$probForumLike = 0.50;
			$probForumNotlike = 0.50;
		}else{
			$sum = $evidenceForum -> i_like + $evidenceForum -> not_like;
			$probForumLike = ($evidenceForum -> i_like)/ $sum;
			$probForumNotlike = ($evidenceForum -> not_like)/ $sum;
		}
	}
	
	$evidenceQuiz=$DB->get_record('trust_f1w1_quiz', array('user_id' => $userid, 'course_id' => $courseid));
	if($evidenceQuiz){
		if($evidenceQuiz -> i_like == 0 && $evidenceQuiz -> not_like==0){
			$probQuizLike = 0.50;
			$probQuizNotlike = 0.50;
		}else{
			$sum = $evidenceQuiz -> i_like + $evidenceQuiz -> not_like;
			$probQuizLike = ($evidenceQuiz -> i_like)/ $sum;
			$probQuizNotlike = ($evidenceQuiz -> not_like)/ $sum;
		}
	}
	
	$evidenceAssign=$DB->get_record('trust_f1w1_assign', array('user_id' => $userid, 'course_id' => $courseid));
	if($evidenceAssign){
		if($evidenceAssign -> i_like == 0 && $evidenceAssign -> not_like==0){
			$probAssignLike = 0.50;
			$probAssignNotlike = 0.50;
		}else{
			$sum = $evidenceAssign -> i_like + $evidenceAssign -> not_like;
			$probAssignLike = ($evidenceAssign -> i_like)/ $sum;
			$probAssignNotlike = ($evidenceAssign -> not_like)/ $sum;
		}
	}
	
	//Obtengo la tabla de probabilidad condicional
	$select= "SELECT * FROM {trust_f2w2_tpc} t";												
	$combinations = $DB -> get_records_sql("$select");
	
	//Realizo las conbinaciones
	foreach($combinations as $c){
		
		//Probabilidad a priori
		$forum = $c -> forum;
		if($forum == 'i_like'){
			$probabilityForum = $probForumLike;
		}else{
			if($forum == 'not_like'){
				$probabilityForum = $probForumNotlike;
			}
		}
		
		$quiz = $c -> quiz;
		if($quiz == 'i_like'){
			$probabilityQuiz = $probQuizLike;
		}else{
			if($quiz == 'not_like'){
				$probabilityQuiz = $probQuizNotlike;
			}
		}
		
		$assign = $c -> assign;
		if($assign == 'i_like'){
			$probabilityAssign = $probAssignLike;
		}else{
			if($assign == 'not_like'){
				$probabilityAssign = $probAssignNotlike;
			}
		}
		
		//Probabilidad Condicional
		$probabilityCondition = $c -> probability;
		
		//Probabilidad conjunta
		$multiplication= $probabilityForum * $probabilityQuiz * $probabilityAssign * $probabilityCondition;
		
		
		if($c->type_dimension == 'reputation_positive'){
			$reputation_positive+= $multiplication;
		}else{
			if($c->type_dimension == 'reputation_negative'){
			$reputation_negative+= $multiplication;
		}
		}
	}
	
	//Insertar la inferencia en la tabla trust_f2w2_inferencia
	if(!$DB->record_exists('trust_f2w2_inferencia', array('user_id' => $userid,'course_id' => $courseid))){
			$f2w2_inferencia = new stdClass ();   
			$f2w2_inferencia->user_id = $userid; 				
			$f2w2_inferencia->course_id = $courseid;   
			$f2w2_inferencia->reputation_positive = $reputation_positive;
			$f2w2_inferencia->reputation_negative = $reputation_negative;
			return $DB->insert_record('trust_f2w2_inferencia', $f2w2_inferencia);
	}else{
		if ($DB->record_exists('trust_f2w2_inferencia', array('user_id' => $userid, 'course_id' => $courseid))){
			$actualizar =  $DB -> get_record('trust_f2w2_inferencia',  array ('user_id' => $userid, 'course_id' => $courseid));											
			$f2w2_inferencia = new stdClass ();
			$f2w2_inferencia->id = $actualizar ->id;				
			$f2w2_inferencia->user_id= $userid;				
			$f2w2_inferencia->course_id = $courseid;	
			$f2w2_inferencia->reputation_positive = $reputation_positive;
			$f2w2_inferencia->reputation_negative = $reputation_negative;
			$DB->update_record('trust_f2w2_inferencia', $f2w2_inferencia);
					
		}
	}
	
	return $probForumLike.'-'.$probForumNotlike.'/'.$probQuizLike.'-'.$probQuizNotlike.'/'.$probAssignLike.'-'.$probAssignNotlike. 'P'.$reputation_positive.
			'N'.$reputation_negative;
	
	
}
