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
 *  Block Trust Model 
 *  Bloque Modelo de Confianza
 *
 * @package    block_trust_model
 * @copyright  2015 Doctoral thesis
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
class block_trust_model extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_trust_model');
    }
	function get_content() {
		global $DB,$USER, $CFG, $COURSE, $OUTPUT;
		
		if($this->content !== NULL) {
			return $this->content;
		}	
		
		$this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
		
		$this->content->text = '<div class="info"><img src="'.$OUTPUT->pix_url('trust_model/trust') . '"WIDTH=150 HEIGHT=100 VSPACE=1 HSPACE=9  ALIGN="center" alt="" /></div>';					
		
		//Carga TCP para el parametro f2w2 Reputacion
		require_once($CFG->dirroot . '/blocks/trust_model/lib.php');
		tabla_probabilidad_condicional_f2w2();
		
		if(isguestuser() or !isloggedin()){ //Muestra si es usuario invitado o no esta logueado
					$this->content->text .= '<div class="info">Ingresar al sistema</div>';
		}else{			
			
			//Obtengo el contexto de curso
			$context = context_course::instance($COURSE-> id);             
			//Obtengo los roles del usuario que tiene asignado en el contexto del curso
			$roles = get_user_roles ( $context , $USER -> id ,  true);
					if(empty($roles)){//si esta vacio	
							$this->content->text .= '<div class="info">Ir al curso</div>';
							if(is_siteadmin($USER->id)){
								$this->content->text .='Administracion';
								$f1w1 = $this->f1w1_Previous_Experience();
								$this->content->text.= inferencia_f2w2($USER -> id, $COURSE -> id);
								$this->content->text.= $this->trust($f1w1);
							}	
					}else{	
							$f1w1 = $this->f1w1_Previous_Experience();
							$this->content->text.= inferencia_f2w2($USER -> id, $COURSE -> id);
							$this->content->text.= $this->trust($f1w1);
							
							$this->content->text.= '<div class="info">Confianza</div>';

							$bandera=true;
							foreach ($roles as $rol) {	
								//Si tiene rol de estudiante
								if( $rol ->shortname == 'student'){
									$this->content->text.='Estudiante';	 
								}else{
									//Si tiene rol de prefesor, profesor con permisos de edicion, creador de cursos
									if( ($rol ->shortname == 'teacher' OR $rol ->shortname == 'editingteacher' OR 
										 $rol ->shortname == 'coursecreator' OR $rol ->shortname == 'manager') AND $bandera==true){																											
										//Mostrar Estudiantes
										$this->content->text.= 'Docente';
										$bandera=false;	
									}
								}

							}
					}				
						
		}
		$this->content->footer='<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">Modelo de confianza</p>'; 			
		return $this->content;
	
	}
	//Obtiene la confianza del primer parameetro F1W1
	function f1w1_Previous_Experience(){
		global $DB, $COURSE, $USER;
		$userid=$USER-> id;
		$courseid=$COURSE-> id;
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
		
		//Confianza F1W1_Previous_Experience
		$f1w1_like= $forum_like + $quiz_like + $assign_like;
		$f1w1_notlike=$forum_notlike + $quiz_notlike + $assign_notlike;
		$f1w1= $f1w1_like/($f1w1_like+$f1w1_notlike);
		return $f1w1;
		
	}
	function trust($f1w1){
		global $DB, $COURSE, $USER;
		$userid=$USER-> id;
		$courseid=$COURSE-> id;
		//Ingresa los datos en la tabla principal Trust
			if ($DB->record_exists('trust', array('user_id' => $userid, 'course_id' => $courseid))) {//Retorna true si encuentra al menos un registro.
					//Actualizo las evidencias
					$actualizar =  $DB -> get_record('trust',  array('user_id' => $userid, 'course_id' => $courseid));											
					$trust = new stdClass ();
					$trust->id = 	$actualizar ->id;				
					$trust->user_id = $userid; 				
					$trust->course_id = $courseid;   
					$trust->f1w1 = $f1w1;
					$trust->f2w2 = 0;
					$trust->f3w3 =  0;
					$trust->f4w4 = 0;
					$trust->f5w5 = 0;
					$trust->f6w6 = 0;
					$trust->f7w7 = 0;
					$trust->f8w8 = 0;
					$trust->trust_level = 0;
					$DB->update_record('trust', $trust);
				
			}else{
				if (!$DB->record_exists('trust', array('user_id' => $userid, 'course_id' => $courseid))){//Ingresa si no obtiene ningun registro
					//Creo un registro 
					$trust = new stdClass ();   
					$trust->user_id = $userid; 				
					$trust->course_id = $courseid;   
					$trust->f1w1 = $f1w1;
					$trust->f2w2 = 0;
					$trust->f3w3 =  0;
					$trust->f4w4 = 0;
					$trust->f5w5 = 0;
					$trust->f6w6 = 0;
					$trust->f7w7 = 0;
					$trust->f8w8 = 0;
					$trust->trust_level = 0;
					$trust->id = $DB->insert_record('trust', $trust);	
				}
			}
			return $f1w1;
	}
}
