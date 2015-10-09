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

function insert_history_folder_file($user,$course,$folder_file_id,$folder_file_user,$action_mc,$value_date) {
	global $CFG, $DB;
    $date='date_tm';
	
	if(!$DB->record_exists('trust_f1w1_history_folder', array('user_id' => $user,'course_id' => $course,'folder_file_id' => $folder_file_id,
							'folder_file_user' => $folder_file_user)))
	{
		//Crea un registro trust_f1w1_history_folder
		$f1w1_history_folderfile = new stdClass ();   
		$f1w1_history_folderfile->user_id = $user; 				
		$f1w1_history_folderfile->course_id = $course;   
		$f1w1_history_folderfile->folder_file_id = $folder_file_id;
		$f1w1_history_folderfile->folder_file_user = $folder_file_user;
		$f1w1_history_folderfile->action = $action_mc;
		$f1w1_history_folderfile->$date = $value_date;
		return $DB->insert_record('trust_f1w1_history_folder', $f1w1_history_folderfile);
	}
}

function insert_history_file($user,$course,$resource_id,$file_id,$file_user,$action_mc,$value_date) {
	global $CFG, $DB;
    $date='date_tm';
	
	if(!$DB->record_exists('trust_f1w1_history_file', array('user_id' => $user,'course_id' => $course,'resource_id' => $resource_id, 'file_id' => $file_id,
							'file_user' => $file_user)))
	{
		//Crea un registro trust_f1w1_history_folder
		$f1w1_history_file = new stdClass ();   
		$f1w1_history_file->user_id = $user; 				
		$f1w1_history_file->course_id = $course;
		$f1w1_history_file->resource_id = $resource_id;		
		$f1w1_history_file->file_id = $file_id;
		$f1w1_history_file->file_user = $file_user;
		$f1w1_history_file->action = $action_mc;
		$f1w1_history_file->$date = $value_date;
		return $DB->insert_record('trust_f1w1_history_file', $f1w1_history_file);
	}
}

function insert_history_book($user,$course,$book_id,$book_user,$action_mc,$value_date) {
	global $CFG, $DB;
    $date='date_tm';
	
	if(!$DB->record_exists('trust_f1w1_history_book', array('user_id' => $user,'course_id' => $course,'book_id' => $book_id, 'book_user' => $book_user)))
	{
		//Crea un registro trust_f1w1_history_folder
		$f1w1_history_book = new stdClass ();   
		$f1w1_history_book->user_id = $user; 				
		$f1w1_history_book->course_id = $course;
		$f1w1_history_book->book_id = $book_id;
		$f1w1_history_book->book_user = $book_user;
		$f1w1_history_book->action = $action_mc;
		$f1w1_history_book->$date = $value_date;
		return $DB->insert_record('trust_f1w1_history_book', $f1w1_history_book);
	}
}

function insert_history_page($user,$course,$page_id,$page_user,$action_mc,$value_date) {
	global $CFG, $DB;
    $date='date_tm';
	
	if(!$DB->record_exists('trust_f1w1_history_page', array('user_id' => $user,'course_id' => $course,'page_id' => $page_id, 'page_user' => $page_user)))
	{
		//Crea un registro trust_f1w1_history_folder
		$f1w1_history_page = new stdClass ();   
		$f1w1_history_page->user_id = $user; 				
		$f1w1_history_page->course_id = $course;
		$f1w1_history_page->page_id = $page_id;
		$f1w1_history_page->page_user = $page_user;
		$f1w1_history_page->action = $action_mc;
		$f1w1_history_page->$date = $value_date;
		return $DB->insert_record('trust_f1w1_history_page', $f1w1_history_page);
	}
}

//Funciones para F2W2
function tabla_probabilidad_condicional_f2w2() {
	global $DB;
	$resource= 'resource';
		
	$combinaciones =  $DB -> get_records_sql('SELECT * FROM {trust_f2w2_tpc}');
	if(empty($combinaciones)){
		//Dimensión Confianza positiva
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '1.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_positive';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		
		
		//Dimensión Confianza negativa
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.0';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'i_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.25';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'i_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.50';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'i_like';
		$tcp->$resource = 'not_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'i_like';
		$tcp->probability = '0.75';
		$tcp->id = $DB->insert_record('trust_f2w2_tpc', $tcp);
		
		$tcp = new stdClass ();
	    $tcp->type_dimension = 'reputation_negative';
		$tcp->forum = 'not_like'; 				
		$tcp->quiz = 'not_like';   
		$tcp->assign = 'not_like';
		$tcp->$resource = 'not_like';
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
	
	$evidenceResource=$DB->get_record('trust_f1w1_resource', array('user_id' => $userid, 'course_id' => $courseid));
	if($evidenceResource){
		if($evidenceResource -> i_like == 0 && $evidenceResource -> not_like==0){
			$probResourceLike = 0.50;
			$probResourceNotlike = 0.50;
		}else{
			$sum = $evidenceResource -> i_like + $evidenceResource -> not_like;
			$probResourceLike = ($evidenceResource -> i_like)/ $sum;
			$probResourceNotlike = ($evidenceResource -> not_like)/ $sum;
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
		
		$res= 'resource';
		$resource = $c -> $res;
		if($resource == 'i_like'){
			$probabilityResource = $probResourceLike;
		}else{
			if($resource == 'not_like'){
				$probabilityResource = $probResourceNotlike;
			}
		}
		
		//Probabilidad Condicional
		$probabilityCondition = $c -> probability;
		
		//Probabilidad conjunta
		$multiplication= $probabilityForum * $probabilityQuiz * $probabilityAssign * $probabilityResource *$probabilityCondition;
		
		
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
	
	//return $probForumLike.'-'.$probForumNotlike.'/'.$probQuizLike.'-'.$probQuizNotlike.'/'.$probAssignLike.'-'.$probAssignNotlike. 'P'.$reputation_positive.
			//'N'.$reputation_negative;
	return $reputation_positive;
	
}
