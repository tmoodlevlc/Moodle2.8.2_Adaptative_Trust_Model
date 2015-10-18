<?php
defined('MOODLE_INTERNAL') || die();
//Configuración general
function save_general_settings($f1w1, $f2w2, $f3w3, $f4w4, $f5w5, $f6w6, $f7w7, $f8w8){
	global $DB;
	$general_settings =  $DB->get_record_sql('SELECT * FROM {trust_general_settings} WHERE codigo IS NOT NULL');	
	if($general_settings){//Actualizo
		$actualizar =  $DB -> get_record('trust_general_settings',  array('id' => $general_settings->id));											
		$config = new stdClass ();
		$config->id = 	$actualizar ->id;				
		$config->f1w1 = $f1w1; 				
		$config->f2w2 = $f2w2;   
		$config->f3w3 = $f3w3;
		$config->f4w4 = $f4w4;
		$config->f5w5 = $f5w5;
		$config->f6w6 = $f6w6;
		$config->f7w7 = $f7w7;
		$config->f8w8 = $f8w8;
		$config->codigo =$actualizar ->codigo;
		$DB->update_record('trust_general_settings', $config);
	}else{//Creo
		$config = new stdClass ();   
		$config->f1w1 = $f1w1; 				
		$config->f2w2 = $f2w2;   
		$config->f3w3 = $f3w3;
		$config->f4w4 = $f4w4;
		$config->f5w5 = $f5w5;
		$config->f6w6 = $f6w6;
		$config->f7w7 = $f7w7;
		$config->f8w8 = $f8w8;
		$config->codigo ='trust_model';
		return $DB->insert_record('trust_general_settings', $config);
	}
}

function save_general_settings_weights($name, $value){
	global $DB;
	$weights = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array($name));
	if($weights){//Actualizo
		$actualizar =  $DB -> get_record('trust_general_settings_weigh',  array('id' => $weights->id));											
		$config = new stdClass ();
		$config->id = 	$actualizar ->id;				
		$config->name = $actualizar ->name; 				
		$config->value = $value;   
		$DB->update_record('trust_general_settings_weigh', $config);
	}else{//Creo
		$config = new stdClass ();   
		$config->name = $name; 				
		$config->value = $value;   
		return $DB->insert_record('trust_general_settings_weigh', $config);
	}
}

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

function insert_history_url($user,$course,$url_id,$url_user,$action_mc,$value_date) {
	global $DB;
    $date='date_tm';
		if(!$DB->record_exists('trust_f1w1_history_url', array('user_id' => $user,'course_id' => $course,'url_id' => $url_id, 'url_user' => $url_user))){
		//Crea un registro trust_f1w1_history_url
		$f1w1_history_url = new stdClass ();   
		$f1w1_history_url->user_id = $user; 				
		$f1w1_history_url->course_id = $course;   
		$f1w1_history_url->url_id = $url_id;
		$f1w1_history_url->url_user = $url_user;
		$f1w1_history_url->action = $action_mc;
		$f1w1_history_url->$date = $value_date;
		return $DB->insert_record('trust_f1w1_history_url', $f1w1_history_url);
		}
}


//Obtiene la confianza del primer parameetro F1W1
function f1w1_Previous_Experience($userid, $courseid){
	global $DB, $COURSE, $USER;
	$userid=$userid;
	$courseid=$courseid;
	//Confianza en los foros
	$forum_like = $DB-> count_records('trust_f1w1_history_forum', array('posts_user' => $userid,'course_id'=>$courseid,'action' => 1));
	$forum_notlike = $DB-> count_records('trust_f1w1_history_forum', array('posts_user' => $userid,'course_id'=>$courseid,'action' => -1));	
	//Ingresa los datos en la BD
		if ($DB->record_exists('trust_f1w1_forum', array('user_id' => $userid, 'course_id' => $courseid))) {//Retorna true si encuentra al menos un registro.
				//Actualizo las evidencias
				$actualizar =  $DB -> get_record('trust_f1w1_forum',  array('user_id' => $userid, 'course_id' => $courseid));											
				$trust_forum = new stdClass ();
				$trust_forum->id = 	$actualizar ->id;				
				$trust_forum->user_id = $userid; 				
				$trust_forum->course_id = $courseid;   
				$trust_forum->i_like = $forum_like;
				$trust_forum->not_like = $forum_notlike;
				$trust_forum->trust = $forum_like/($forum_like+$forum_notlike);
				$DB->update_record('trust_f1w1_forum', $trust_forum);
			
		}else{
			if (!$DB->record_exists('trust_f1w1_forum', array('user_id' => $userid, 'course_id' => $courseid))){//Ingresa si no obtiene ningun registro
				//Creo un registro 
				$trust_forum = new stdClass ();   
				$trust_forum->user_id = $userid; 				
				$trust_forum->course_id = $courseid;   
				$trust_forum->i_like = $forum_like;
				$trust_forum->not_like = $forum_notlike;
				$trust_forum->trust = $forum_like/($forum_like+$forum_notlike);
				$trust_forum->id = $DB->insert_record('trust_f1w1_forum', $trust_forum);	
			}
		}
	//Confianza en los examenes
	$quiz_like = $DB-> count_records('trust_f1w1_history_quiz', array('attempts_user' => $userid,'course_id'=>$courseid,'action' => 1));
	$quiz_notlike = $DB-> count_records('trust_f1w1_history_quiz', array('attempts_user' => $userid,'course_id'=>$courseid,'action' => -1));
	//Ingresa los datos en la BD
		if ($DB->record_exists('trust_f1w1_quiz', array('user_id' => $userid, 'course_id' => $courseid))) {//Retorna true si encuentra al menos un registro.
				//Actualizo las evidencias
				$actualizar =  $DB -> get_record('trust_f1w1_quiz',  array('user_id' => $userid, 'course_id' => $courseid));											
				$trust_quiz = new stdClass ();
				$trust_quiz->id = 	$actualizar ->id;				
				$trust_quiz->user_id = $userid; 				
				$trust_quiz->course_id = $courseid;   
				$trust_quiz->i_like = $quiz_like;
				$trust_quiz->not_like = $quiz_notlike;
				$trust_quiz->trust = $quiz_like/($quiz_like+$quiz_notlike);
				$DB->update_record('trust_f1w1_quiz', $trust_quiz);
			
		}else{
			if (!$DB->record_exists('trust_f1w1_quiz', array('user_id' => $userid, 'course_id' => $courseid))){//Ingresa si no obtiene ningun registro
				//Creo un registro 
				$trust_quiz = new stdClass ();   
				$trust_quiz->user_id = $userid; 				
				$trust_quiz->course_id = $courseid;   
				$trust_quiz->i_like = $quiz_like;
				$trust_quiz->not_like = $quiz_notlike;
				$trust_quiz->trust = $quiz_like/($quiz_like+$quiz_notlike);
				$trust_quiz->id = $DB->insert_record('trust_f1w1_quiz', $trust_quiz);	
			}
		}
	
	//Confianza en las tareas
	$assign_like = $DB-> count_records('trust_f1w1_history_assign', array('assing_user' => $userid,'course_id'=>$courseid,'action' => 1));
	$assign_like += $DB-> count_records('trust_f1w1_history_assign', array('comments_user' => $userid,'course_id'=>$courseid,'action' =>1));
	
	$assign_notlike = $DB-> count_records('trust_f1w1_history_assign', array('assing_user' => $userid,'course_id'=>$courseid,'action' => -1));
	$assign_notlike += $DB-> count_records('trust_f1w1_history_assign', array('comments_user' => $userid,'course_id'=>$courseid,'action' =>-1));
	//Ingresa los datos en la BD
		if ($DB->record_exists('trust_f1w1_assign', array('user_id' => $userid, 'course_id' => $courseid))) {//Retorna true si encuentra al menos un registro.
				//Actualizo las evidencias
				$actualizar =  $DB -> get_record('trust_f1w1_assign',  array('user_id' => $userid, 'course_id' => $courseid));											
				$trust_assign = new stdClass ();
				$trust_assign->id = 	$actualizar ->id;				
				$trust_assign->user_id = $userid; 				
				$trust_assign->course_id = $courseid;   
				$trust_assign->i_like = $assign_like;
				$trust_assign->not_like = $assign_notlike;
				$trust_assign->trust = $assign_like/($assign_like+$assign_notlike);
				$DB->update_record('trust_f1w1_assign', $trust_assign);
			
		}else{
			if (!$DB->record_exists('trust_f1w1_assign', array('user_id' => $userid, 'course_id' => $courseid))){//Ingresa si no obtiene ningun registro
				//Creo un registro 
				$trust_assign = new stdClass ();   
				$trust_assign->user_id = $userid; 				
				$trust_assign->course_id = $courseid;   
				$trust_assign->i_like = $assign_like;
				$trust_assign->not_like = $assign_notlike;
				$trust_assign->trust = $assign_like/($assign_like+$assign_notlike);
				$trust_assign->id = $DB->insert_record('trust_f1w1_assign', $trust_assign);	
			}
		}
		
		
		
	//Confianza en los resources
	$resource_like = $DB-> count_records('trust_f1w1_history_book', array('book_user' => $userid,'course_id'=>$courseid,'action' => 1));
	$resource_like += $DB-> count_records('trust_f1w1_history_page', array('page_user' => $userid,'course_id'=>$courseid,'action' =>1));
	$resource_like += $DB-> count_records('trust_f1w1_history_folder', array('folder_file_user' => $userid,'course_id'=>$courseid,'action' => 1));
	$resource_like += $DB-> count_records('trust_f1w1_history_file', array('file_user' => $userid,'course_id'=>$courseid,'action' =>1));
	$resource_like += $DB-> count_records('trust_f1w1_history_url', array('url_user' => $userid,'course_id'=>$courseid,'action' =>1));
	
	$resource_notlike = $DB-> count_records('trust_f1w1_history_book', array('book_user' => $userid,'course_id'=>$courseid,'action' => -1));
	$resource_notlike += $DB-> count_records('trust_f1w1_history_page', array('page_user' => $userid,'course_id'=>$courseid,'action' =>-1));
	$resource_notlike += $DB-> count_records('trust_f1w1_history_folder', array('folder_file_user' => $userid,'course_id'=>$courseid,'action' => -1));
	$resource_notlike += $DB-> count_records('trust_f1w1_history_file', array('file_user' => $userid,'course_id'=>$courseid,'action' =>-1));
	$resource_notlike += $DB-> count_records('trust_f1w1_history_url', array('url_user' => $userid,'course_id'=>$courseid,'action' =>-1));
	
	//Ingresa los datos en la BD
		if ($DB->record_exists('trust_f1w1_resource', array('user_id' => $userid, 'course_id' => $courseid))) {//Retorna true si encuentra al menos un registro.
				//Actualizo las evidencias
				$actualizar =  $DB -> get_record('trust_f1w1_resource',  array('user_id' => $userid, 'course_id' => $courseid));											
				$trust_resource = new stdClass ();
				$trust_resource->id = 	$actualizar ->id;				
				$trust_resource->user_id = $userid; 				
				$trust_resource->course_id = $courseid;   
				$trust_resource->i_like = $resource_like;
				$trust_resource->not_like = $resource_notlike;
				$trust_resource->trust = $resource_like/($resource_like+$resource_notlike);
				$DB->update_record('trust_f1w1_resource', $trust_resource);
			
		}else{
			if (!$DB->record_exists('trust_f1w1_resource', array('user_id' => $userid, 'course_id' => $courseid))){//Ingresa si no obtiene ningun registro
				//Creo un registro 
				$trust_resource = new stdClass ();   
				$trust_resource->user_id = $userid; 				
				$trust_resource->course_id = $courseid;   
				$trust_resource->i_like = $resource_like;
				$trust_resource->not_like = $resource_notlike;
				$trust_resource->trust = $resource_like/($resource_like+$resource_notlike);
				$trust_resource->id = $DB->insert_record('trust_f1w1_resource', $trust_resource);	
			}
		}
		
	//Confianza F1W1_Previous_Experience
	$f1w1_like= $forum_like + $quiz_like + $assign_like + $resource_like;
	$f1w1_notlike=$forum_notlike + $quiz_notlike + $assign_notlike + $resource_notlike;
	
	$f1w1_like_validate=$f1w1_like;
	$f1w1_notlike_validate=$f1w1_notlike;
		
	if ($DB->record_exists('trust_f1w1_validate', array('user_id' => $userid, 'course_id' => $courseid))) {//Retorna true si encuentra al menos un registro.
				//Actualizo las evidencias
				$actualizar =  $DB -> get_record('trust_f1w1_validate',  array('user_id' => $userid, 'course_id' => $courseid));											
				$validate = new stdClass ();
				$validate->id = 	$actualizar ->id;				
				$validate->user_id = $userid; 				
				$validate->course_id = $courseid;   
				$validate->i_like = $f1w1_like;
				$validate->not_like = $f1w1_notlike;
				
				$validateUser =  $DB -> get_record('trust_f1w1_validate_user',  array('course_id' => $courseid));
				if($validateUser){
					$validate->teacher_validate = $actualizar ->teacher_validate;
					$validate->student_validate = $actualizar ->student_validate;
					//Docente
					if($actualizar->teacher_validate == 0 || $actualizar->teacher_validate == 1){ 
						$validate->like_validate = $f1w1_like_validate;
					}else{
						if($actualizar->teacher_validate == -1){ //Resto el 20%
							$f1w1_like_validate = $f1w1_like_validate - (($f1w1_like_validate*20)/100);
							$validate->like_validate = $f1w1_like_validate;
							
						}
					}
					//Estudiante
					if($actualizar->student_validate == 0 || $actualizar->student_validate == 1){ 
						$validate->like_validate = $f1w1_like_validate;
					}else{
						if($actualizar->student_validate == -1){ //Resto el 10
							$f1w1_like_validate = $f1w1_like_validate - (($f1w1_like_validate*10)/100);
							$validate->like_validate = $f1w1_like_validate;
						}
					}
				}else{
					$validate->teacher_validate = 0; 
					$validate->student_validate = 0;
					$validate->like_validate = $f1w1_like;
				}
				$validate->notlike_validate = $f1w1_notlike;
				
				$DB->update_record('trust_f1w1_validate', $validate);
			
		}else{
			if (!$DB->record_exists('trust_f1w1_validate', array('user_id' => $userid, 'course_id' => $courseid))){//Ingresa si no obtiene ningun registro
				//Creo un registro 
				$validate = new stdClass ();   
				$validate->user_id = $userid; 				
				$validate->course_id = $courseid;   
				$validate->i_like = $f1w1_like;
				$validate->not_like = $f1w1_notlike;
				$validate->teacher_validate = 0;
				$validate->student_validate = 0;
				$validate->like_validate = $f1w1_like;
				$validate->notlike_validate = $f1w1_notlike;
				$validate->id = $DB->insert_record('trust_f1w1_validate', $validate);	
			}
		}
	
	$f1w1= $f1w1_like_validate/($f1w1_like_validate+$f1w1_notlike);
	
	//Controlar si no hay valoración de like y not like
	if($f1w1_like==0 && $f1w1_notlike==0){
		$f1w1=0.00;
	}
	return $f1w1;
	
}

function direct_experience_validation_user_f1w1($courseid, $userid, $rol) {
	global $DB, $USER;
	if ($DB->record_exists('trust_f1w1_validate_user', array('course_id' => $courseid))) {//Retorna true si encuentra al menos un registro.
			//Actualizo las evidencias
			$actualizar =  $DB -> get_record('trust_f1w1_validate_user',  array('course_id' => $courseid));											
			$validation = new stdClass ();
			$validation->id = 	$actualizar ->id;
			$validation->course_id = $courseid; 
			if($rol=='teacher'){
				$validation->teacher_id = $userid; 
				$validation->student_id = $actualizar ->student_id;
			}else{//Student
				$validation->student_id = $userid; 
				$validation->teacher_id = $actualizar ->teacher_id; 
			} 
			$validation->teacher_percentage = 20; 
			$validation->student_percentage = 10;
			$DB->update_record('trust_f1w1_validate_user', $validation);
		
	}else{
		if (!$DB->record_exists('trust_f1w1_validate_user', array('course_id' => $courseid))){//Ingresa si no obtiene ningun registro
			//Creo un registro 
			$validation = new stdClass ();   
			$validation->course_id = $courseid; 
			if($rol=='teacher'){
				$validation->teacher_id = $userid; 
				$validation->student_id = 0;
			}else{//Student
				$validation->student_id = $userid; 
				$validation->teacher_id = 0; 
			} 
			$validation->teacher_percentage = 20; 
			$validation->student_percentage = 10;
			$validation->id = $DB->insert_record('trust_f1w1_validate_user', $validation);	
		}
	}
}

function direct_experience_validation_f1w1($courseid, $userid, $rol, $action) {
	global $DB, $USER;
	if ($DB->record_exists('trust_f1w1_validate', array('course_id' => $courseid, 'user_id' => $userid))) {//Retorna true si encuentra al menos un registro.
			//Actualizo las evidencias
			$actualizar =  $DB -> get_record('trust_f1w1_validate',  array('course_id' => $courseid, 'user_id' => $userid));											
			$validation = new stdClass ();
			$validation->id = 	$actualizar ->id;
			$validation->user_id= $actualizar ->user_id;
			$validation->course_id = $actualizar ->course_id;
			$validation->i_like = $actualizar ->i_like;
			$validation->not_like = $actualizar ->not_like;
			$validation->notlike_validate = $actualizar ->notlike_validate;
			
			if($rol=='teacher'){
				
				if($action==1){
					$validation->teacher_validate = 1;
					$validation->student_validate = $actualizar ->student_validate;

					if($actualizar->student_validate == -1){
						$validation->like_validate = $actualizar ->i_like - (($actualizar ->i_like*10)/100);
					}else{
						$validation->like_validate = $actualizar ->i_like;
					}
					
				}else{
					if($action==-1){
						$validation->teacher_validate = -1;
						$validation->student_validate = $actualizar ->student_validate;
						if($actualizar->student_validate == -1){
							$validation->like_validate = $actualizar ->like_validate - (($actualizar ->like_validate*20)/100);
						}else{
							$validation->like_validate = $actualizar ->i_like - (($actualizar ->i_like*20)/100);
						}	
					}
				}
			}else{ 
				if($rol=='student'){
					if($action==1){
						$validation->teacher_validate = $actualizar ->teacher_validate;
						
						if($actualizar->teacher_validate == -1){
							$validation->student_validate = 1; 
							$validation->like_validate = $actualizar ->i_like - (($actualizar ->i_like*20)/100);
						}else{
							$validation->student_validate = 1; 
							$validation->like_validate = $actualizar ->i_like;
						}
					
					}else{
						if($action==-1){
							$validation->teacher_validate = $actualizar ->teacher_validate;
			
							if($actualizar->teacher_validate == -1){
								$validation->student_validate = -1; 
								$validation->like_validate = $actualizar ->like_validate - (($actualizar ->like_validate*10)/100);
							}else{
								$validation->student_validate = -1; 
								$validation->like_validate = $actualizar ->i_like - (($actualizar ->i_like*10)/100);
							}
							
						}
					}
				}
			}
			$DB->update_record('trust_f1w1_validate', $validation);
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
			$DB->insert_record('trust_f2w2_inferencia', $f2w2_inferencia);
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

function knowledge_f4w4($userid, $courseid){
	global $DB;
	$f4w4=0;
	$quiz =  $DB -> get_record('quiz',  array ('course' => $courseid, 'name' => 'Knowledge_f4w4'),  $fields='*', $strictness=IGNORE_MULTIPLE);//Obtengo primer registro
	if($quiz){
		$quizattemps =  $DB -> get_record('quiz_attempts',  array ('quiz' => $quiz->id, 'userid' => $userid), $fields='*', $strictness=IGNORE_MULTIPLE);
		if($quizattemps){
			//Obtengo el quiz al que hace referencia el cuestionario resuelto
			$calificacionMaxima = $quiz->grade;
			$calificacionObtenida = ($quizattemps->sumgrades * $calificacionMaxima)/$quiz->sumgrades;
			if($calificacionMaxima>0){
				$intervalo= ($calificacionObtenida * 100)/ $calificacionMaxima; //Para verificar a que intervalo pertenece
				if($intervalo<50 ){
					$f4w4=0.5;
				}else{
					if($intervalo>=50 and $intervalo <=75){
							$f4w4=0.75;
					}else{
						if($intervalo>75){
							$f4w4=1;
						}
					}
				}
			}
		}
	}
	return $f4w4;
}

function securityDateEstatic_f5w5(){
	global $DB;
	$data =  $DB -> get_records_sql('SELECT * FROM {trust_f5w5_security}');
	if(empty($data)){										
			$security = new stdClass ();				
			$security->factor_security = 'DNSSEC'; 
			$security->estado_security = 'false'; 
			$DB->insert_record('trust_f5w5_security', $security);
			
			$security = new stdClass ();				
			$security->factor_security = 'IPSEC'; 
			$security->estado_security = 'false'; 
			$DB->insert_record('trust_f5w5_security', $security);
			
			$security = new stdClass ();				
			$security->factor_security = 'SSL'; 
			$security->estado_security = 'false'; 
			$DB->insert_record('trust_f5w5_security', $security);
			
			$security = new stdClass ();				
			$security->factor_security = 'HTTPS'; 
			$security->estado_security = 'false'; 
			$DB->insert_record('trust_f5w5_security', $security);
	}
}

function security_f5w5($s, $opc){
	global $DB;
	
	if($opc==1){
		$security = new stdClass ();   
		$security->factor_security = $s; 
		$security->estado_security = 'false'; 	
		$DB->insert_record('trust_f5w5_security', $security);
	}else{
		if($opc==2){
			$DB->delete_records('trust_f5w5_security', array('id' => $s)); 
		}else{
			if($opc==3){ //Guardar Configuracion a Activo
				if($DB->record_exists('trust_f5w5_security', array('id' => $s))){
						$actualizar =  $DB -> get_record('trust_f5w5_security',  array ('id' => $s));											
						$security = new stdClass ();
						$security->id = $actualizar ->id;				
						$security->factor_security = $actualizar ->factor_security; 
						$security->estado_security = 'true'; 
						$DB->update_record('trust_f5w5_security', $security);
				}
			}else{
				if($opc==4){ //Guardar Configuracion a Desactivado
					if($DB->record_exists('trust_f5w5_security', array('id' => $s))){
							$actualizar =  $DB -> get_record('trust_f5w5_security',  array ('id' => $s));											
							$security = new stdClass ();
							$security->id = $actualizar ->id;				
							$security->factor_security = $actualizar ->factor_security; 
							$security->estado_security = 'false'; 
							$DB->update_record('trust_f5w5_security', $security);
					}
				}
			}
		}
	}
	
}
function securityTrust_f5w5(){
	global $DB;
	$data =  $DB -> get_records_sql('SELECT * FROM {trust_f5w5_security}');
	$countTotal= 0; 
	$countActivos=0;
	if(!empty($data)){										
		foreach($data as $sec){
			$countTotal=$countTotal+1;
			$estado= $sec->estado_security;
			if($estado=='true'){//Activo
				$countActivos = $countActivos+1;
			}
		}
		$f5w5= $countActivos/$countTotal;
	}else{
		$f5w5=0;
	}
	return $f5w5;
}

function validateScorm_f6w6($scorm, $user,$course, $teaching_curricular, $interface_design,$navigation_design, $value){
	global $DB;
	if(!$DB->record_exists('trust_f6w6_quality', array('scorm_id' => $scorm,'course_id' => $course, 'user_validate' => $user))){											
		$f6w6 = new stdClass ();   
		$f6w6->scorm_id = $scorm;
		$f6w6->user_validate = $user; 
		$f6w6->course_id = $course;
		$f6w6->teaching_curricular = $teaching_curricular;
		$f6w6->interface_design = $interface_design;
		$f6w6->navigation_design = $navigation_design;
		$f6w6->value = $value; 		
		$DB->insert_record('trust_f6w6_quality', $f6w6);
	}	
}

function quality_f6w6($course){
	global $DB;
	$lstScorm = $DB->get_records('trust_f6w6_quality',  array ('course_id'=>$course));
	$suma=0;
	$promedio=0;
	foreach($lstScorm  as $s){
		$suma = $suma + $s->value;
	}
	if($lstScorm){
		$promedio=$suma/count($lstScorm);
	}
	
	if ($promedio <= 1){
		$quality=-1;
	}else if($promedio > 1 && $promedio <= 1.5 ){
		$quality=0.0;
	}else if($promedio > 1.5 && $promedio <= 2.5 ){
		$quality=0.25;
	}else if($promedio > 2.5 && $promedio <= 3.5){
		$quality=0.5;
	}else if($promedio >3.5 && $promedio <= 4.5){
		$quality=0.75;
	}else if($promedio >4.5 && $promedio <= 5){
		$quality=1;
	}
	
	return $quality;
}


function save_config_institutional_f7w7($template, $web_service){
	global $DB;
	$institutional =  $DB->get_record_sql('SELECT * FROM {trust_f7w7_config} WHERE codigo IS NOT NULL');	
	if($institutional){//Actualizo
		$actualizar =  $DB -> get_record('trust_f7w7_config',  array('id' => $institutional->id));											
		$config = new stdClass ();
		$config->id = 	$actualizar ->id;				
		$config->template = $template; 				
		$config->web_service = $web_service;   
		$config->codigo =$actualizar ->codigo;
		$DB->update_record('trust_f7w7_config', $config);
	}else{//Creo
		$config = new stdClass ();   
		$config->template = $template; 				
		$config->web_service = $web_service;  
		$config->codigo ='trust_model';
		return $DB->insert_record('trust_f7w7_config', $config);
	}
}

function save_template_question_f7w7($question, $cat_id, $id, $type){
	global $DB;
	$new = new stdClass ();   
	$new->pregunta = $question; 				
	$new->category = $cat_id; 
	$new->id_categories = $id; 				
	$new->type = $type; 	
	return $DB->insert_record('trust_f7w7_t_questions', $new);
}
function delete_template_question_f7w7($id){
	global $DB;
	$DB->delete_records('trust_f7w7_t_questions', array('id' => $id)); 
}

function selec_directive_f7w7($categories, $user){
	global $DB;
	$directive =  $DB -> get_record('trust_f7w7_t_dir_sel',  array('categories_id' => $categories));
	if($directive){
		$update = new stdClass ();
		$update->id = 	$directive->id;				
		$update->categories_id = $categories; 
		$update->user_id = $user; 		
		$DB->update_record('trust_f7w7_t_dir_sel', $update); 
	}else{
		$new = new stdClass ();    	
		$new->categories_id = $categories; 	
		$new->user_id = $user; 	
		$DB->insert_record('trust_f7w7_t_dir_sel', $new);
	}
}

function update_template_question_f7w7($id, $question){
	global $DB;
	$actualizar =  $DB -> get_record('trust_f7w7_t_questions',  array('id' => $id));											
	$update = new stdClass ();
	$update->id = 	$actualizar ->id;
	$update->pregunta = $question; 				
	$update->category = $actualizar->category;  
	$update->id_categories = $actualizar->id_categories; 				
	$update->type = $actualizar->type; 
	$DB->update_record('trust_f7w7_t_questions', $update); 
}

function save_instancia_course_f7w7($course, $id, $campo, $category_course){
	global $DB;
	if($id==0){//Creo para el curso
		$new = new stdClass ();   
		$new->course_id = $course; 
		$new->category_course=$category_course;
		if($campo=='interna'){
			$new->internal_evaluation = 'true';  
			$new->external_evaluation ='false';
		}else if($campo=='externa'){
			$new->internal_evaluation = 'false';  
			$new->external_evaluation ='true';
		}
		return $DB->insert_record('trust_f7w7_t_inst', $new);
	}else{//Actualizo
		$actualizar =  $DB -> get_record('trust_f7w7_t_inst',  array('id' => $id));											
		$update = new stdClass ();
		$update->id = 	$actualizar ->id;
		$update->course_id = $course;
		$update->category_course= $category_course;		
		if($campo=='interna'){
			if($actualizar ->internal_evaluation=='true'){
				$update->internal_evaluation = 'false'; 
			}else{
				$update->internal_evaluation = 'true'; 
			} 
			$update->external_evaluation =$actualizar->external_evaluation;
		}else if($campo=='externa'){
			if($actualizar ->external_evaluation=='true'){
				$update->external_evaluation = 'false'; 
			}else{
				$update->external_evaluation = 'true'; 
			} 
			$update->internal_evaluation =$actualizar->internal_evaluation;
		}		
		$DB->update_record('trust_f7w7_t_inst', $update); 
	}
}

function save_answers_total_f7w7($cat, $t_inst_id ,$user_emisor, $user_receptor, $value){
	global $DB;
	$new = new stdClass (); 
	$new->cat= $cat;
	$new->t_inst_id = $t_inst_id; 
	$new->user_emisor= $user_emisor; 
	$new->user_receptor = $user_receptor;
	$new->value= $value;
	return $DB->insert_record('trust_f7w7_t_answer', $new);
}


function save_answers_student_f7w7($u, $teacher,$t_inst_id, $t_questions_id, $type, $resp, $t_answer){
	global $DB;
	$new = new stdClass ();   
	$new->user_est = $u; 
	$new->user_tea = $teacher;
	$new->t_inst_id = $t_inst_id; 
	$new->t_questions_id = $t_questions_id;
	$new->type = $type;
	$new->$resp = 1;
	$new->t_answer= $t_answer;
	return $DB->insert_record('trust_f7w7_t_est', $new);
}
function save_answers_teacher_f7w7($teacher,$t_inst_id, $t_questions_id, $type, $resp, $t_answer){
	global $DB;
	$new = new stdClass (); 
	$new->user_tea = $teacher;
	$new->t_inst_id = $t_inst_id; 
	$new->t_questions_id = $t_questions_id;
	$new->type = $type;
	$new->$resp = 1;
	$new->t_answer= $t_answer;
	return $DB->insert_record('trust_f7w7_t_tea', $new);
}

function save_answers_directive_f7w7($directive, $teacher, $t_inst_id, $t_questions_id, $type, $resp, $t_answer){
global $DB;
	$new = new stdClass (); 
	$new->user_dir = $directive;
	$new->user_tea = $teacher;
	$new->t_inst_id = $t_inst_id; 
	$new->t_questions_id = $t_questions_id;
	$new->type = $type;
	$new->$resp = 1;
	$new->t_answer= $t_answer;
	return $DB->insert_record('trust_f7w7_t_dir', $new);
}


function save_answers_pairs_f7w7($u, $teacher, $t_inst_id, $t_questions_id, $type, $resp, $t_answer){
	global $DB;
	$new = new stdClass (); 
	$new->user_one = $u;
	$new->user_two = $teacher;
	$new->t_inst_id = $t_inst_id; 
	$new->t_questions_id = $t_questions_id;
	$new->type = $type;
	$new->$resp = 1;
	$new->t_answer= $t_answer;
	return $DB->insert_record('trust_f7w7_t_pair', $new);
}

function combination_pairs_f7w7($categories,$subcategories){
	global $DB;
	$cat =  $DB -> get_record('course_categories',  array ('id'=>$subcategories));
	$roleTeacher =  $DB -> get_record('role',  array ('shortname'=>'editingteacher'));
	$lstTeacher= $DB->get_records_sql("SELECT mdl_user.id as userid, mdl_user.firstname, mdl_user.lastname, mdl_course.id as courseid
									FROM mdl_user
									INNER JOIN mdl_role_assignments ON mdl_user.id = mdl_role_assignments.userid 
									INNER JOIN mdl_role ON mdl_role.id = mdl_role_assignments.roleid
									INNER JOIN mdl_context ON mdl_context.id = mdl_role_assignments.contextid
									INNER JOIN mdl_course ON mdl_course.id = mdl_context.instanceid
									INNER JOIN mdl_course_categories ON mdl_course_categories.id = mdl_course.category
									WHERE mdl_role.id = ? AND mdl_course_categories.path LIKE '%$cat->path%' GROUP BY mdl_user.id ORDER BY mdl_user.lastname, mdl_user.firstname  ASC ", 
									array($roleTeacher->id));

	$longitud = count($lstTeacher);
	if($longitud>1){//Permitir realizar la combinacion si se combina al menos un par
		$claves = array_keys($lstTeacher);
		for($i=0; $i<$longitud; $i++){
			$new = new stdClass (); 
			$new->categories = $categories;
			$new->sub_categories = $subcategories;
			if($i!=$longitud-1){//Si estoy en la ultima posicion
				$new->one_pairs_user = $lstTeacher[$claves[$i]]->userid; 
				$new->two_pairs_user = $lstTeacher[$claves[$i+1]]->userid;
				$DB->insert_record('trust_f7w7_t_pairs_comb', $new);
			}			
			
		}
	}
}

function institutional_f7w7($courseid, $userid){
	global $DB;
	$config = $DB->get_record_sql('SELECT * FROM {trust_f7w7_config} WHERE codigo LIKE \'trust_model\'');
	if($config){
		$rol= rolParticipante($courseid, $userid);
		if($rol==2){ //Si es docente
			if($config->template=='true'){ //Si la configuracion esta guardada desde los cuestionarios pre establecidos
				$f7w7_t_answer= $DB->get_record_sql("SELECT t1.user_receptor, t1.t_inst_id, count(*) as cont, SUM(value) as suma FROM {trust_f7w7_t_answer} t1
				INNER JOIN {trust_f7w7_t_inst}  t2 ON t1.t_inst_id =  t2.id
				WHERE t2.course_id = ? AND t1.user_receptor = ? GROUP BY user_receptor, t_inst_id", array($courseid, $userid));
				$f7w7 = ($f7w7_t_answer) ? $f7w7_t_answer->suma/$f7w7_t_answer->cont : 0; 
				
			}else if($config->web_service=='true'){//Si la configuracion esta guardada desde Web Service
				$user =  $DB -> get_record('user',  array ('id'=>$userid));
				$f7w7= institutional_f7w7_ws($userid, $user->idnumber);
			}
		}else{ //Es estudiante
			$f7w7=0.50;
		}
	}else{ //No esta guardada la configuracion previa
		$f7w7=Null;
	}
	return $f7w7;
}
 function institutional_f7w7_ws($userid, $idnumber){
	global $DB;
	$webService = $DB -> get_record('trust_f7w7_ws',  array ('userid'=>$userid, 'idnumber'=>$idnumber));
	$numero=0;
	if($webService){
		if($webService->value_student==Null){
			$value_student= 0;
		}else{
			$numero=$numero+1;
			$value_student= $webService->value_student;
		}
		
		if($webService->value_teacher==Null){
			$value_teacher= 0;
		}else{
			$numero=$numero+1;
			$value_teacher= $webService->value_teacher;
		}
		
		if($webService->value_directive==Null){
			$value_directive= 0;
		}else{
			$numero=$numero+1;
			$value_directive= $webService->value_directive;
		}
		
		if($webService->value_pair==Null){
			$value_pair= 0;
		}else{
			$numero=$numero+1;
			$value_pair= $webService->value_pair;
		}
		$f7w7 = ($numero >0) ? ($value_student+$value_teacher+$value_directive+$value_pair)/$numero : 0; 
	}else{
		$f7w7=0;
	}
	return $f7w7;
}

function kin_f8w8($userid){
	global $DB;
	$f8w8=0;
	$f8w8_course=0;
	
	$var='';
	
	//Obtengo todos los cursos como estudiante
	$select= "SELECT c.id FROM {role_assignments} ra, {context} cont, {course} c, {role}  r  WHERE ";
	$select.= "ra.userid=:userid AND ra.roleid = r.id AND r.shortname = 'student' AND ra.contextid = cont.id AND cont.instanceid = c.id";												
	$params = array();	
	$params['userid'] = $userid;
	$curso_matriculado= $DB -> get_records_sql("$select", $params,$limitfrom='', $limitnum='');
	foreach($curso_matriculado as $courso){
		//obtenemos el contexto del curso a partir de su id
		$contexto =context_course::instance($courso->id);
		//obtenemos rol Estudiante
		$rol =  $DB -> get_record('role',  array ('shortname'=>'student'));	
		$alumnos = get_users_from_role_on_context($rol,$contexto);
		$kinCourse = array();

		foreach($alumnos as $alumno){
			if($alumno->userid != $userid){//Controlar que no cuente el like del mismo estudiante
				$like = $DB -> get_record('trust',array('user_id'=> $alumno->userid, 'course_id'=> $courso->id ));
				if($like){
					if(count($alumnos)<=30){//Cuando numero de estudiantes es menor a 30, selecciono 3
						if(count($kinCourse)<3){
							$kinCourse [] = $like;
							//array_push($kinCourse,$like);
						}else{
							$kinCourse=list_delete_like_f8w8($kinCourse, $like);
						}
					}else{//Cuando numero de estudiantes es mayor a 30, selecciono 5
						if(count($kinCourse)<5){
							$kinCourse [] = $like;
						}else{	
							$kinCourse=list_delete_like_f8w8($kinCourse, $like);
						}
					}
					
				}
			}
		}
		//Obtengo de cada curso
		$f8w8_course=0;
		if(count($kinCourse)>0){
			foreach($kinCourse as $kin){
				$f8w8_course=$f8w8_course + $kin->f1w1;
			}
			$f8w8_course= $f8w8_course/count($kinCourse);
		}
		//Sumo para todos los cursos
		$f8w8= $f8w8+ $f8w8_course;
	}
	
	if(count($curso_matriculado)>0){
		$f8w8= $f8w8/count($curso_matriculado);
	}
	
	//Ubicarlo en el rango
	if($f8w8>=0.91 && $f8w8<=1){
		$f8w8= 1;
	}else if($f8w8>=0.81 && $f8w8<=0.90){
		$f8w8= 0.75;
	}else if($f8w8>=0.71 && $f8w8<=0.80){
		$f8w8= 0.50;
	}else if($f8w8>=0.61 && $f8w8<=0.70){
		$f8w8= 0.25;
	}else if($f8w8>=0.51 && $f8w8<=0.60){
		$f8w8= 0;
	}else if($f8w8<=0.50){
		$f8w8= -1;
	};
	
	return $f8w8;
}

function list_delete_like_f8w8($list, $item){
	$i=0;
	$index=$i;
	$nroLike=$list[0]->f1w1;
	foreach($list as $l){
		//Obtener el menor
		if($l->f1w1 <= $nroLike){
			$nroLike=$l->f1w1;
			$index=$i;
		}
		$i=$i+1;
	}
	if($item->f1w1 > $list[$index]->f1w1){// Si el item es mayor al menor numero, se igresa
		$list[$index]=$item;
	}
	
	return $list;
}

function trust_model_total($f1w1, $f2w2, $f3w3, $f4w4, $f5w5,$f6w6,$f7w7,$f8w8, $course, $user){
	global $DB;
	$trust=0;
	$rol= rolParticipante($course, $user);
	//F1W1
	if($f1w1!=NULL){
		if($rol==1){//Estudiante
			$p1f1w1 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f1w1'));
			$f1w1=$f1w1*$p1f1w1->value; 
		}else if($rol==2){//Docente
			$p2f1w1 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f1w1'));
			$f1w1=$f1w1*$p2f1w1->value;
		}
	}else{
		$f1w1=0;
	}
	//F2W2
	if($f2w2!=NULL){
		if($rol==1){//Estudiante 
			$p1f2w2 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f2w2'));
			$f2w2=$f2w2*$p1f2w2->value;
		}else if($rol==2){//Docente
			$p2f2w2 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f2w2'));
			$f2w2=$f2w2*$p2f2w2->value;
		}
	}else{
		$f2w2=0;
	}
	//F3W3
	$obj = $DB -> get_record('course',array('id'=> $course));
	if($f3w3!=NULL && $obj){
		if($obj->format=='singleactivity'){//actividad unica
			$p1f3w3 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f3w3'));
			$f3w3=$f3w3*$p1f3w3->value;
		}else if($obj->format=='social'){//social
			$p2f3w3 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f3w3'));
			$f3w3=$f3w3*$p2f3w3->value;
		}else if ($obj->format=='topics' || $obj->format=='weeks'){//Temas o semanal
			$p3f3w3 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p3f3w3'));
			$f3w3=$f3w3*$p3f3w3->value;
		}
	}else{
		$f3w3=0;
	}
	
	//F4W4
	if($f4w4!=NULL){
		if($rol==1){//Estudiante
			$p1f4w4 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f4w4'));
			$f4w4=$f4w4*$p1f4w4->value;
		}else if($rol==2){//Docente
			$p2f4w4 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f4w4'));
			$f4w4=$f4w4*$p2f4w4->value;
		}
	}else{
		$f4w4=0;
	}
	//F5W5
	if($f5w5!=NULL){
		$f5w5opc=0; 
		$lstSecurity =  $DB -> get_records_sql('SELECT * FROM {trust_f5w5_security}');
		foreach($lstSecurity as $security){
			if($security->factor_security=='DNSSEC' && $security->estado_security=='true'){
				$f5w5opc=1;
				break;
			}
		}
		$p1f5w5 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f5w5'));
		$p2f5w5 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f5w5'));
		$f5w5= ($f5w5opc==0)? $f5w5*$p2f5w5->value: $f5w5*$p1f5w5->value;
	}else{
		$f5w5=0;
	}
	//F6W6
	if($f6w6!=NULL){
		if($rol==1){//Estudiante
			$p1f6w6 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f6w6'));
			$f6w6=$f6w6*$p1f6w6->value;
		}else if($rol==2){//Docente
			$p2f6w6 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f6w6'));
			$f6w6=$f6w6*$p2f6w6->value;
		}
	}else{
		$f6w6=0;
	}
	//F7W7
	if($f7w7!=NULL){
		if($rol==1){//Estudiante
			$p1f7w7 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f7w7'));
			$f7w7=$f7w7*$p1f7w7->value;
		}else if($rol==2){//Docente
			$p2f7w7 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f7w7'));
			$f7w7=$f7w7*$p2f7w7->value;
		}
	}else{
		$f7w7=0;
	}
	
	//F8W8
	if($f8w8!=NULL){
		if($f8w8>=0.8){
			$p3f8w8 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p3f8w8'));
			$f8w8=$f8w8*$p3f8w8->value;
		}else if($f8w8>=0.5 && $f8w8<0.8){
			$p2f8w8 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p2f8w8'));
			$f8w8=$f8w8*$p2f8w8->value;
		}else if($f8w8<0.5){
			$p1f8w8 = $DB->get_record_sql("SELECT * FROM {trust_general_settings_weigh} WHERE  name  LIKE ? ", array('p1f8w8'));
			$f8w8=$f8w8*$p1f8w8->value;
		}
	}else{
		$f8w8=0;
	}
	$trust= $f1w1+$f2w2+$f3w3+$f4w4+$f5w5+$f6w6+$f7w7+$f8w8;
	return $trust;
}

function rolParticipante($course, $user){
	$context = context_course::instance($course);             
	$roles = get_user_roles ( $context , $user ,  true);
	$bandera=0; //1:Rol estudiante, 2: Rol docente
	foreach ($roles as $rol) {	
		if( $rol ->shortname == 'student'){
			$bandera = ($bandera==0)?1:$bandera; //Controlar mayor prioridad Teacher
		}else{
			if( $rol ->shortname == 'teacher' OR $rol ->shortname == 'editingteacher' OR 
				 $rol ->shortname == 'coursecreator' OR $rol ->shortname == 'manager'){
				$bandera = 2;
			}
		}
	}
	return $bandera;
}

function webService($location, $metodo){ //Cliente del servicio web
	try {
		$client = new SoapClient(NULL, array('uri' => 'urn:webservices',
											 'location' => $location, 
											 'wsdl_cache' => 0,
											 'trace' => 1,
											 'encoding'=>'ISO-8859-1'));
		$metodo= '$lstTrust = $client->'.$metodo.'();';
		eval($metodo);
		return $lstTrust;
	} catch (SoapFault $e) {
		return ($client->__getLastResponse());
	}
	
}
function webServiceFilteredSave($lstTrust){//Lista filtrada, saber que estudiantes del servicio web corresponden al esta comunidad
	global $DB;
	$lstUsers= array();
	foreach($lstTrust as $key => $value ){
		$user =  $DB -> get_record('user',  array('idnumber' => $value->idnumber));
		if ($user){	//Pertenece a la comunidad: creo o actualizo
			save_update_TrustExternal($user, $value);
			$lstUsers[] = $value;
		}
	}
	return $lstUsers;
}
function save_update_TrustExternal($user, $value ){
	global $DB;
	$actualizar =  $DB -> get_record('trust_external',  array('userid'=> $user->id,'idnumber' => $value->idnumber));	
	if($actualizar){//Actualizo													
		$actualizar->trust_external= $value->trust;
		$DB->update_record('trust_external', $actualizar);
	}else{//Creo
		$objeto = new stdClass ();   
		$objeto->userid = $user->id; 				
		$objeto->idnumber= $value->idnumber;
		$objeto->trust_external= $value->trust; 
		$DB->insert_record('trust_external', $objeto);
	}
}

//Servicio Web F7W7 Institutional
function webServiceF7W7($location, $metodo){ 	
	try {
		$client = new SoapClient(null, array('uri' => 'urn:webservices',
											 'location' => $location, 
											 'wsdl_cache' => 0,
											 'trace' => 1,
											 'encoding'=>'ISO-8859-1'));
		$metodo= '$lstF7W7 = $client->'.$metodo.'();';
		eval($metodo);
		return $lstF7W7;
	} catch (SoapFault $e) {
		return ($client->__getLastResponse());
	}
}
function webServiceF7W7_FilteredSave($lstF7W7){//Lista filtrada, saber que estudiantes del servicio web corresponden al esta comunidad
	global $DB;
	$lstUsers= array();
	foreach($lstF7W7 as $key => $value ){
		$user =  $DB -> get_record('user',  array('idnumber' => $value->idnumber));
		if ($user){	//Pertenece a la comunidad: creo o actualizo
			webServiceF7W7_SaveUpdate($user, $value);
			$lstUsers[] = $value;
		}
	}
	return $lstUsers;
}
function webServiceF7W7_SaveUpdate($user, $value ){
	global $DB;
	$actualizar =  $DB -> get_record('trust_f7w7_ws',  array('userid'=> $user->id,'idnumber' => $value->idnumber));	
	if($actualizar){//Actualizo													
		$actualizar->value_student= $value->value_student;
		$actualizar->value_teacher= $value->value_teacher;
		$actualizar->value_directive= $value->value_directive;
		$actualizar->value_pair= $value->value_pair;
		$DB->update_record('trust_f7w7_ws', $actualizar);
	}else{//Creo
		$objeto = new stdClass ();   
		$objeto->userid = $user->id; 				
		$objeto->idnumber= $value->idnumber;
		$objeto->value_student= $value->value_student; 
		$objeto->value_teacher= $value->value_teacher; 
		$objeto->value_directive= $value->value_directive; 
		$objeto->value_pair= $value->value_pair; 
		$DB->insert_record('trust_f7w7_ws', $objeto);
	}
}

