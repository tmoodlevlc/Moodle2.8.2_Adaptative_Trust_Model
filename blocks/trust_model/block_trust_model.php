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
		//Variables
		global $DB,$USER, $CFG, $COURSE, $OUTPUT;
		if($this->content !== NULL) {return $this->content;}	
		$this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
		$urlImagen = new moodle_url('/blocks/trust_model/pix/items.png');
		$imagen= '<img src="'.$urlImagen. '"alt="" />';
		
		//Imagen Confianza
		$imgTrust= new moodle_url('/blocks/trust_model/pix/trust.jpg');
		$this->content->text = '<div class="info"><img src="'.$imgTrust. '"WIDTH=150 HEIGHT=100 VSPACE=1 HSPACE=9  ALIGN="center" alt="" /></div>';					
		
		//CARGA TCP PARA EL PARAMETRO F2W2 REPUTACION
		require_once($CFG->dirroot . '/blocks/trust_model/lib.php');
		tabla_probabilidad_condicional_f2w2();
		
		//Muestra información de acuerdo al rol del usuario
		if(isguestuser() or !isloggedin()){ 
					$this->content->text .= '<div class="info texto">'.get_string('signin', 'block_trust_model').'</div>';
		}else{
				//CONFIGURACION GENERAL DEL MODULO
				$general_settings =  $DB->get_record_sql('SELECT * FROM {trust_general_settings} WHERE codigo IS NOT NULL');
				//Obtengo el contexto de curso
				$context = context_course::instance($COURSE-> id);             
				//Obtengo los roles del usuario que tiene asignado en el contexto del curso
				$roles = get_user_roles ( $context , $USER -> id ,  true);
				if(empty($roles)){
						$this->content->text .= '<div class="info texto">'.get_string('course', 'block_trust_model').'</div>';
						//-----------------------------------------ADMINISTRADOR Contexto Sitio/Contexto Curso-------------------------------------
						if(is_siteadmin($USER->id)){ 
							//---------------------------Configuración general (Que parametros seran habilitados) y pesos--------------------------
							$generalSettingsUrl= '/blocks/trust_model/GeneralSettings.php';
							$this->content->text .= $imagen.html_writer::link(new moodle_url($generalSettingsUrl), get_string('generalSettings', 'block_trust_model')).'<br>';
							if($general_settings){
								//-------------------------------------------------Servicio Web-------------------------------------------------------
								$webService= '/blocks/trust_model/WebService.php';
								$this->content->text .= $imagen.html_writer::link(new moodle_url($webService), get_string('webService', 'block_trust_model')).'<br>';
								//----------------------------Configuración General Seguridad (crear nuevos items de seguridad)----------------------
								if($general_settings->f5w5=='true'){
									$f5w5Security= '/blocks/trust_model/F5W5_Security.php';
									$this->content->text .= $imagen.html_writer::link(new moodle_url($f5w5Security), get_string('securityParameters', 'block_trust_model')).'<br>';
									securityDateEstatic_f5w5();	
								}
								//------------------------------Configuración General F7W7 Institucional (templates o servicio web)-------------
								if($general_settings->f7w7=='true'){
									$f7w7Institutional= '/blocks/trust_model/F7W7_Institutional.php';
									$this->content->text .= $imagen.html_writer::link(new moodle_url($f7w7Institutional), get_string('paramsInstitutional', 'block_trust_model'));
								}
								if($COURSE -> id != 1){
									//----------------------PARAMETROS A EJECUTAR SEGUN LA CONFIGURACIÓN DEL ADMINISTRADOR--------------------
									$f1w1 = f1w1_Previous_Experience($USER -> id, $COURSE -> id);
									$f2w2 = inferencia_f2w2($USER -> id, $COURSE -> id);
									if($general_settings->f3w3=='true'){
										$f3w3 = 1.00;
									}else{
										$f3w3 = Null;
									}
									if($general_settings->f4w4=='true'){
										$f4w4 = knowledge_f4w4($USER -> id, $COURSE -> id);
									}else{
										$f4w4 = Null;
									}
									if($general_settings->f5w5=='true'){
										$f5w5 = securityTrust_f5w5();
									}else{
										$f5w5 = Null;
									}
									if($general_settings->f6w6=='true'){
										$f6w6= quality_f6w6($COURSE -> id);
									}else{
										$f6w6 = Null;
									}
									if($general_settings->f7w7=='true'){
										$f7w7= institutional_f7w7($COURSE -> id, $USER -> id);
									}else{
										$f7w7= Null;
									}
									if($general_settings->f8w8=='true'){
										$f8w8 = kin_f8w8($USER -> id);
									}else{
										$f8w8 = Null;
									}					
									$valueTrust= $this->trust($f1w1, $f2w2,$f3w3,$f4w4,$f5w5,$f6w6,$f7w7,$f8w8);
									
									//-----------------------Ver Detalles-----------------------------------------------------------------
									$details = '/blocks/trust_model/index.php';
									$this->content->text = $imagen.html_writer::link(new moodle_url($details, array('u' => $USER -> id, 'c' => $COURSE -> id)), get_string('details', 'block_trust_model')).html_writer::empty_tag('br');
									//------------------------Configurar experiencia directa (Seleccionar el docente y estudiante que validara me gusta y no me gusta)----------------
									$f1w1_validationConfig = '/blocks/trust_model/F1W1_ValidationConfig.php';
									$this->content->text .= $imagen.html_writer::link(new moodle_url($f1w1_validationConfig, array('c' => $COURSE -> id)), get_string('configED', 'block_trust_model'));
									//----------------------Mostrar Información resumida-----------------------------------------------------
									$this->content->text .= $this->string_trust($general_settings, $f1w1, $f2w2, $f3w3, $f4w4, $f5w5,$f6w6,$f7w7,$f8w8, $valueTrust);
								}
							}else{
								$this->content->text .= '<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('moduleConfigurationGeneral', 'block_trust_model').'</p>';
							}	
						}	
				//----------------------------------DOCENTES/ESTUDIANTES.. Contexto Curso--------------------------------------------------
				}else{	
						if($general_settings){
							//-------------PARAMETROS A EJECUTAR SEGUN LA CONFIGURACIÓN DEL ADMINISTRADOR-----------------------
							$rol = rolParticipante($COURSE -> id, $USER -> id);
							$f1w1 = f1w1_Previous_Experience($USER -> id, $COURSE -> id);
							$f2w2 = inferencia_f2w2($USER -> id, $COURSE -> id);
							if($general_settings->f3w3=='true'){
								$f3w3= ($rol==1)?0.50:1.00;
							}else{
								$f3w3 = Null;
							}
							if($general_settings->f4w4=='true'){
								$f4w4 = knowledge_f4w4($USER -> id, $COURSE -> id);
							}else{
								$f4w4 = Null;
							}
							if($general_settings->f5w5=='true'){
								$f5w5 = securityTrust_f5w5();
							}else{
								$f5w5 = Null;
							}
							if($general_settings->f6w6=='true'){
								$f6w6= quality_f6w6($COURSE -> id);
							}else{
								$f6w6 = Null;
							}
							if($general_settings->f7w7=='true'){
								$f7w7= institutional_f7w7($COURSE -> id, $USER -> id);
							}else{
								$f7w7= Null;
							}
							if($general_settings->f8w8=='true'){
								$f8w8 = kin_f8w8($USER -> id);
							}else{
								$f8w8 = Null;
							}	
							$valueTrust= $this->trust($f1w1, $f2w2,$f3w3,$f4w4,$f5w5,$f6w6,$f7w7,$f8w8);
							//-------------------------Ver detalles------------------------------------------------------------
							$details = '/blocks/trust_model/index.php';
							$this->content->text = $imagen.html_writer::link(new moodle_url($details, array('u' => $USER -> id, 'c' => $COURSE -> id)), get_string('details', 'block_trust_model')).html_writer::empty_tag('br');
			
							if($rol==1){
								//--------------F1W1 EXPERIENCIA DIRECTA, Rol Estudiante/Habilitar la validación me gusta y no me gusta 
								$studentValidation =  $DB -> get_record('trust_f1w1_validate_user',  array ('course_id'=>$COURSE -> id));
								if($studentValidation){
									if($studentValidation->student_id == $USER->id){
										$url = new moodle_url('/blocks/trust_model/F1W1_Validation.php', array('c'=>$COURSE -> id));
										$this->content->text .= $imagen.html_writer::link( $url, get_string('validateED', 'block_trust_model')).html_writer::empty_tag('br');
									}
								}
							}else{ 
								//------F1W1 EXPERIENCIA DIRECTA Rol Docente/ Configurar experiencia directa y Habilitar la validación me gusta y no me gusta
								$f1w1_validationConfig = '/blocks/trust_model/F1W1_ValidationConfig.php';
								$this->content->text .= $imagen.html_writer::link(new moodle_url($f1w1_validationConfig, array('c' => $COURSE -> id)), get_string('configED', 'block_trust_model')).html_writer::empty_tag('br'); 
							}
							//---------------------------F7W7 INSTITUCIONAL-----------------------------------------------------------
							if($general_settings->f7w7=='true'){
								$institutional =  $DB->get_record_sql('SELECT * FROM {trust_f7w7_config} WHERE codigo IS NOT NULL');	
								if($institutional){//Si el admin ya configuro F7W7
									if ($institutional->template=='true'){//TEMPLATES
										$f7w7_institutional= '/blocks/trust_model/F7W7_Institutional_templateShow.php';
										$this->content->text .= $imagen.html_writer::link(new moodle_url($f7w7_institutional,array('c' => $COURSE -> id, 'u' => $USER -> id)), get_string('paramsInstitutional', 'block_trust_model')).'<br>';
									}
								}else{
									$this->content->text .= $imagen.get_string('paramsInstitutional', 'block_trust_model').'<br>';
								}
							}
							//----------------------------------Mostrar al usuario Información detallada-----------------------------------------
							$this->content->text .= $this->string_trust($general_settings, $f1w1, $f2w2, $f3w3, $f4w4, $f5w5,$f6w6,$f7w7,$f8w8, $valueTrust);
						}else{
							$this->content->text .= '<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('moduleConfigurationGeneral', 'block_trust_model').'</p>';
						}								
				}						
		}	
		$this->content->footer='<p class="info" style="color: #2A5A5F; font-family: cursive; align: center">'.get_string('pluginname', 'block_trust_model').'</p>'; 			
		return $this->content;
	
	}
	
	function string_trust($general_settings, $f1w1, $f2w2, $f3w3, $f4w4, $f5w5,$f6w6,$f7w7,$f8w8,$valueTrust){
		$cadena='<ul class="dimensiones">
					<li>
						<a href="#">'.get_string('dimensions', 'block_trust_model').'<small>('.number_format($valueTrust, 2, '.', '').')</small>'.'
							<span> -'.get_string('experience', 'block_trust_model').': '.number_format($f1w1, 2, '.', '').'</span>
							<span> -'.get_string('reputation', 'block_trust_model').': '.number_format($f2w2, 2, '.', '').'</span>';
		if($general_settings->f3w3=='true'){
			$cadena.= '<span> -'.get_string('role', 'block_trust_model').': '.number_format($f3w3, 2, '.', '').'</span>';
		}
		
		if($general_settings->f4w4=='true'){
			$cadena.= '<span> -'.get_string('knowledge', 'block_trust_model').': '.number_format($f4w4, 2, '.', '').'</span>';
		}
		
		if($general_settings->f5w5=='true'){
			$cadena.= '<span> -'.get_string('security', 'block_trust_model').': '.number_format($f5w5, 2, '.', '').'</span>';
		}
		
		if($general_settings->f6w6=='true'){
			$cadena.= '<span> -'.get_string('quality', 'block_trust_model').': '.number_format($f6w6, 2, '.', '').'</span>';
		}
		
		if($general_settings->f7w7=='true'){
			$cadena.= '<span> -'.get_string('institutional', 'block_trust_model').': '.number_format($f7w7, 2, '.', '').'</span>';
		}
		
		if($general_settings->f8w8=='true'){
			$cadena.= '<span> -'.get_string('kin', 'block_trust_model').': '.number_format($f8w8, 2, '.', '').'</span>';
		}
		
		$cadena.='</a></li></ul>';
		
		return $cadena;
	}
	
	function trust($f1w1, $f2w2, $f3w3, $f4w4, $f5w5,$f6w6,$f7w7,$f8w8){
		global $DB, $COURSE, $USER;
		$userid=$USER-> id;
		$courseid=$COURSE-> id;
		$valueTrust= trust_model_total($f1w1, $f2w2, $f3w3, $f4w4, $f5w5,$f6w6,$f7w7,$f8w8, $courseid, $userid);
		//Ingresa los datos en la tabla principal Trust
			if ($DB->record_exists('trust', array('user_id' => $userid, 'course_id' => $courseid))) {//Retorna true si encuentra al menos un registro.
					//Actualizo las evidencias
					$actualizar =  $DB -> get_record('trust',  array('user_id' => $userid, 'course_id' => $courseid));											
					$trust = new stdClass ();
					$trust->id = 	$actualizar ->id;				
					$trust->user_id = $userid; 				
					$trust->course_id = $courseid;   
					$trust->f1w1 = $f1w1;
					$trust->f2w2 = $f2w2;
					$trust->f3w3 = $f3w3;
					$trust->f4w4 = $f4w4;
					$trust->f5w5 = $f5w5;
					$trust->f6w6 = $f6w6;
					$trust->f7w7 = $f7w7;
					$trust->f8w8 = $f8w8;
					$trust->trust_level = $valueTrust;
					$DB->update_record('trust', $trust);
				
			}else{
				if (!$DB->record_exists('trust', array('user_id' => $userid, 'course_id' => $courseid))){//Ingresa si no obtiene ningun registro
					//Creo un registro 
					$trust = new stdClass ();   
					$trust->user_id = $userid; 				
					$trust->course_id = $courseid;   
					$trust->f1w1 = $f1w1;
					$trust->f2w2 = $f2w2;
					$trust->f3w3 = $f3w3;
					$trust->f4w4 = $f4w4;
					$trust->f5w5 = $f5w5;
					$trust->f6w6 = $f6w6;
					$trust->f7w7 = $f7w7;
					$trust->f8w8 = $f8w8;
					$trust->trust_level = $valueTrust;
					$trust->id = $DB->insert_record('trust', $trust);	
				}
			}
			return $valueTrust;
	}
}
