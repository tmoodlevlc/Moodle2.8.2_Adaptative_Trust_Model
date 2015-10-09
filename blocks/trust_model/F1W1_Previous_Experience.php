<?php
require_once("../../config.php");
require_once($CFG->dirroot . '/blocks/trust_model/lib.php');
global $USER;
//Recupero parametros
$opc = required_param('opc', PARAM_INT);
$user = required_param('u', PARAM_INT); 
$course = required_param('c', PARAM_INT); 
$action_mc = required_param('mc', PARAM_INT); $_GET['mc'];
$value_date = date("Y/m/d");
//optional_param('id', 0, PARAM_INT);
	if($opc==1){//Modelo de confianza Foro
		$forum = $_GET['f'];
		$discussion = $_GET['d'];
		$post = $_GET['p'];
		$post_user = $_GET['pu'];
		$bandera=insert_history_forum($user,$course,$forum,$discussion,$post,$post_user,$action_mc,$value_date);
		
	}else{
		if($opc==2){ // Modelo de confianza Examen
			$quiz = $_GET['q'];
			$attempts = $_GET['a'];
			$attempts_user = $_GET['au'];
			$bandera=insert_history_quiz($user,$course,$quiz,$attempts,$attempts_user,$action_mc,$value_date);
		}else{
			if($opc==3){ // Modelo de confianza Tarea
				$role = $_GET['rol'];
				if($role=='doc'){ // Rol docente, califica la tarea
					$assign = $_GET['a'];
					$assign_user = $_GET['au'];
					$comment_user = 0;
				}else{ // Rol estudiante, califica el comentario
					$assign = $_GET['a'];
					$assign_user = 0;
					$comment_user = $_GET['cu'];
				}
				$bandera=insert_history_assign($user,$course,$assign,$assign_user,$comment_user, $action_mc,$value_date);
			
			//RESOURCES
			}else{ 
				if($opc==4){ // Modelo de confianza folder-file
				$folder_file_id = $_GET['ff'];
				$folder_file_user = $_GET['ffu'];
				$bandera=insert_history_folder_file($user,$course,$folder_file_id,$folder_file_user,$action_mc,$value_date);
				}else{
					if($opc==5){ // Modelo de confianza Book
						$book_id = $_GET['b'];
						$book_user = $_GET['bu'];
						$bandera=insert_history_book($user,$course,$book_id,$book_user,$action_mc,$value_date);
					}else{
						if($opc==6){// Modelo de confianza Page
							$page_id = $_GET['p'];
							$page_user = $_GET['pu'];
							$bandera=insert_history_page($user,$course,$page_id,$page_user,$action_mc,$value_date);
						}else{
							if($opc==7){ // Modelo de confianza resource-file
								$resource_id = $_GET['r'];
								$file_id = $_GET['f'];
								$file_user = $_GET['fu'];
								//No tomo encuenta el user del parametro recibido.
								if($file_user != $USER->id){ //Controla, no calificar un recurso creado por el mismo usuario
									$bandera=insert_history_file($USER->id,$course,$resource_id,$file_id,$file_user,$action_mc,$value_date);
									if(!$bandera){
										?>
										<script type="text/javascript">
											alert("Modelo de confianza ya registrada");
										</script> 
										<?php
										;
										
									}
								}else{
									?>
									<script type="text/javascript">
										alert("No puede validar su recurso");
										window.opener.document.location.reload();
									</script> 
									<?php
									;
								}
								
							}
						}
					}
				}
			}
		}
	}

	if($bandera){
		require_once("../../config.php");
		$url = new moodle_url('/course/view.php', array('id'=>$course));
		header("Location:$url");
	}else{
			?>
			<script type="text/javascript">
				alert("Error al guardar el Modelo de referencia");
			</script> 
			<?php
			;
	}
?>
