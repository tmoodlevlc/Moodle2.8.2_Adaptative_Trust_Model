<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Grafo de Interacciones</title>        
		<!--Libreria Graficar Nodos-->
		<script type="text/javascript" src="lib/dist/vis.js"></script>
		<link type="text/css"  href="lib/dist/vis.css" rel="stylesheet"/>
		<!--Booststrab-->
		<script type="text/javascript" src="bootstrap-3.3.5-dist/jquery-2.1.4.min.js"></script>
		<script type="text/javascript" src="bootstrap-3.3.5-dist/js/bootstrap.min.js"></script>
		<link type="text/css" href="bootstrap-3.3.5-dist/css/bootstrap.min.css" rel="stylesheet"/>


		<style type="text/css">
			#mynetwork {
			  height: 450px;
			  border: 1px solid lightgray;
			  margin: 20px;
			}
			#visualization{
				padding:20px;
			}
			
		</style>
        
    </head>
    <body>
        <?php
		require_once('../../../config.php');
		global $DB;
		$user_id = required_param('u', PARAM_INT);
		$course_id = required_param('c', PARAM_INT); 
		$course = $DB -> get_record('course',array('id'=> $course_id));
		$userlinea= $DB -> get_record('user',array('id'=> $user_id));
		//--------------------------------------------------OBTENER DATOS DE LA BD NODOS------------------------------------------------>
		
		//OBTENEMOS LOS PARTICIPANTES DEL CURSO
		$contexto =context_course::instance($course_id);												
		$roleTeacher =  $DB -> get_record('role',  array ('shortname'=>'editingteacher'));	
		$roleStudent =  $DB -> get_record('role',  array ('shortname'=>'student'));
		$roleTeacherSinEdition =  $DB -> get_record('role',  array ('shortname'=>'teacher'));
		$roleManager =  $DB -> get_record('role',  array ('shortname'=>'manager'));
		$roleCourseCreator =  $DB -> get_record('role',  array ('shortname'=>'coursecreator'));
		$lstParticipantes= $DB->get_records_sql("SELECT * FROM {role_assignments} WHERE contextid = ? AND (roleid = ? OR roleid = ? OR roleid = ? OR roleid = ? OR roleid = ? ) GROUP BY userid", 
		array($contexto->id, $roleStudent->id, $roleTeacher->id, $roleTeacherSinEdition->id, $roleManager->id, $roleCourseCreator->id));
		
		//NODOS PARTICIPANTES
		$arrayPHPnodos=array();
		foreach($lstParticipantes as $participante){
			//Obtener el numero de like y  not_like
			$sqlParticipante= "SELECT  user_receptor, action, SUM(cont) as sum FROM 
			(SELECT posts_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_forum 
			WHERE course_id = $course_id AND posts_user=$participante->userid GROUP BY user_receptor, action
			
			UNION ALL
			SELECT attempts_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_quiz 
			WHERE course_id = $course_id AND attempts_user=$participante->userid GROUP BY user_receptor, action 
			 
			UNION ALL
			SELECT CASE WHEN comments_user=0 THEN assing_user ELSE comments_user END as user_receptor, action, COUNT(*) as cont 
			FROM mdl_trust_f1w1_history_assign WHERE course_id = $course_id AND (comments_user=$participante->userid OR assing_user=$participante->userid) 
			GROUP BY user_receptor, action 
			 
			UNION ALL
			SELECT book_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_book 
			WHERE course_id = $course_id AND book_user=$participante->userid GROUP BY user_receptor, action
			 
			UNION ALL
			SELECT file_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_file 
			WHERE course_id = $course_id AND file_user=$participante->userid GROUP BY user_receptor, action

			UNION ALL
			SELECT folder_file_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_folder 
			WHERE course_id = $course_id AND folder_file_user=$participante->userid GROUP BY user_receptor, action
			 
			UNION ALL
			SELECT page_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_page 
			WHERE course_id = $course_id AND page_user=$participante->userid GROUP BY user_receptor, action 

			UNION ALL
			SELECT url_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_url 
			WHERE course_id = $course_id AND url_user=$participante->userid GROUP BY user_receptor, action) 
			 
			AS tablaTemporal GROUP BY user_receptor, action";
			$nroLike=0;
			$nroNotLike=0;
			$listaLikeNotLike = $DB->get_recordset_sql($sqlParticipante);
			foreach ($listaLikeNotLike as $objeto) {
				if($objeto->action==1){
					$nroLike= $objeto->sum;
				}else if ($objeto->action==-1){
					$nroNotLike= $objeto->sum;
				}
			}
			$listaLikeNotLike ->close();
			//Obtener nombre del usuario
			$user = $DB -> get_record('user',array('id'=> $participante->userid));
			$cadenaid= '"'.$user->id.'"';
			//Obtener el trust Model
			$trust_level= $DB -> get_record('trust',array('user_id'=> $participante->userid, 'course_id'=> $course_id));
			$trust_level_ext= $DB -> get_record('trust_external',array('userid'=> $participante->userid));
			$level= ($trust_level)? $trust_level->trust_level : '0.00';
			$level_external= ($trust_level_ext) ? $trust_level_ext->trust_external : 'No registrado';
			//Insertar al array
			$nodo = array ("id" => $cadenaid, "firstname" => fullname($user), "like" => $nroLike, "not_like" => $nroNotLike, "trust" => $level, "trust_external" => $level_external);
			$arrayPHPnodos[] = $nodo;
		}
		
		//ASIGNAR USUARIO ACTUAL
        $userActual='"'.$user_id.'"';          
        
		//INTERACCIONES
		$sql= "SELECT  user_emisor, user_receptor, action, SUM(cont) as sum FROM 
		(SELECT user_id as user_emisor,posts_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_forum 
		WHERE course_id = $course_id GROUP BY user_emisor, user_receptor, action
		
		UNION ALL
		SELECT user_id as user_emisor, attempts_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_quiz 
		WHERE course_id = $course_id GROUP BY user_emisor, user_receptor, action 
		 
		UNION ALL
		SELECT user_id as user_emisor, CASE WHEN comments_user=0 THEN assing_user ELSE comments_user END as user_receptor, action, COUNT(*) as cont 
		FROM mdl_trust_f1w1_history_assign WHERE course_id = $course_id  GROUP BY user_emisor, user_receptor, action 
		 
		UNION ALL
		SELECT user_id as user_emisor, book_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_book 
		WHERE course_id = $course_id GROUP BY user_emisor, user_receptor, action
		 
		UNION ALL
		SELECT user_id as user_emisor, file_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_file 
		WHERE course_id = $course_id GROUP BY user_emisor, user_receptor, action

		UNION ALL
		SELECT user_id as user_emisor, folder_file_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_folder 
		WHERE course_id = $course_id GROUP BY user_emisor, user_receptor, action
		 
		UNION ALL
		SELECT user_id as user_emisor, page_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_page 
		WHERE course_id = $course_id GROUP BY user_emisor, user_receptor, action 

		UNION ALL
		SELECT user_id as user_emisor, url_user as user_receptor, action, COUNT(*) as cont FROM mdl_trust_f1w1_history_url 
		WHERE course_id = $course_id GROUP BY user_emisor, user_receptor, action) 
		 
		AS tablaTemporal GROUP BY user_emisor, user_receptor, action";
		
		$arrayPHPenlaces=array();
		$recordset = $DB->get_recordset_sql($sql);
		foreach ($recordset as $objeto) {
			$cadena1= '"'.$objeto->user_emisor.'"';
			$cadena2= '"'.$objeto->user_receptor.'"';
			$enlace = array ("user_emisor" => $cadena1, "user_receptor" => $cadena2, "action" => $objeto->action, "label" => $objeto->sum );
			$arrayPHPenlaces[] = $enlace;
		}
		$recordset ->close();
		
		//--------------------------------------------------OBTENER DATOS DE LA BD LINEA DE TIEMPO-----------------------------------------------		
		$arrayLineaTiempo=array();
		$sqlLineaTiempo= $DB->get_records_sql('SELECT  t2.category, t3.name, AVG(trust_level) as promedio FROM {trust} t1
										INNER JOIN {course} t2 ON t1.course_id = t2.id
										INNER JOIN {course_categories}  t3 ON t2.category = t3.id
										WHERE t1.user_id=? GROUP BY category, name', array($user_id));		
		foreach ($sqlLineaTiempo as $objeto) {
			$category= array ("category" => $objeto->name, "promedio" => $objeto->promedio);
			$arrayLineaTiempo[] = $category;
		}
		
        ?>
        <!--------------------------------------------------HTML------------------------------------------------>
		
		<nav class="navbar navbar-default">
		  <div class="container-fluid">
			<div class="navbar-header">
			  <a class="navbar-brand" href="#">Modelo de Confianza</a>
			</div>
			<div class="collapse navbar-collapse">
			  <ul class="nav navbar-nav">
				<li class="active"><a href="#">Curso: <?php echo $course->fullname;?></a></li>
			  </ul>
			</div>
		  </div>
		</nav>
	
        <div class="row">
			<!--Lista de Participantes-->
			<div class="col-md-3">
				<div class="sidebar-nav">
				  <div class="navbar navbar-default" role="navigation">
					<div class="navbar-collapse collapse sidebar-navbar-collapse">
						<ul class="nav nav-pills nav-stacked">
							<li class="active"><a href="#" style="text-align:center; ">Participantes</a></li>
						</ul>
						<ol style="padding-left:20px">
							<?php 
							foreach($lstParticipantes as $participante){
								$user = $DB -> get_record('user',array('id'=> $participante->userid));
								?><li><spam><?php echo fullname($user);?></spam></li><?php
							}
							?>
						</ol>
					</div>
				  </div>
				</div>
			</div>
			<!--Grafico de Nodos-->
			<div class="col-md-7">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#interaccion" data-toggle="tab" onclick="grafoIteraccion()"><span class="badge">1</span> Interacción</a></li>
					<li><a href="#linea" data-toggle="tab" onclick="lineaTiempo()"><span class="badge">2</span> Linea de Tiempo</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="interaccion"><div id="mynetwork"></div></div>
					<div class="tab-pane" id="linea"><div id="visualization"></div></div>
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="row">
					<!--Visualización-->
					<div class="col-md-12">
						<div class="sidebar-nav">
						  <div class="navbar navbar-default" role="navigation">
							<div class="navbar-collapse collapse sidebar-navbar-collapse">
								<ul class="nav nav-pills nav-stacked">
									<li class="active"><a href="#" style="text-align:center; ">Visualización</a></li>
								</ul>
								<ul style="padding-left:10px; list-style:none;">
									<li><input type="checkbox" name="nodos"  onclick="cambiarVisualizacion(this)" checked> Nodos</li>
									<li><input type="checkbox" name="personas"  onclick="cambiarVisualizacion(this)" > Gráficos</li>
								</ul>
							</div>
						  </div>
						</div>
					</div>
					<!--Cursos-->
					<div class="col-md-12">
						<div class="sidebar-nav">
						  <div class="navbar navbar-default" role="navigation">
							<div class="navbar-collapse collapse sidebar-navbar-collapse">
								<ul class="nav nav-pills nav-stacked">
									<li class="active"><a href="#" style="text-align:center; ">Cursos</a></li>
								</ul>
								<?php 
								if(is_siteadmin($user_id)){
									$courses = $DB ->get_records_sql("SELECT * FROM {course} WHERE  format NOT LIKE ? ", array('site'));
									?>
									<ul style="padding-left:10px">
										<?php 
										foreach($courses as $obj){
											if($obj->id==$course_id){
												?><li><spam><?php echo $obj->shortname;?></spam></li><?php
											}else{
												$url_grafic = new moodle_url('/blocks/trust_model/graphic/index.php', array('u'=>$user_id, 'c'=>$obj->id));
												?><li><a href="<?php echo $url_grafic;?>"><?php echo $obj->shortname;?></a></li><?php
											}
										}
										?>
									</ul>
								<?php 
								}else{
									?>
									<ul style="padding-left:10px">
										<li><spam><?php echo $course->shortname;?></spam></li>
									</ul>
								<?php
								}
								?>
							</div>
						  </div>
						</div>
					</div>
				</div>
				
			</div>
            
        </div>
				
		<!--------------------------------------------------JAVASCRIP------------------------------------------------>
		<script type="text/javascript">		
            //Nodos
            var nodes = [];                       
            var arrayJSnodos=<?php echo json_encode($arrayPHPnodos);?>;
            var userActu= <?php echo json_encode($userActual); ?>;
            for(var i=0;i<arrayJSnodos.length;i++){
                if(arrayJSnodos[i].id === userActu){                                    
                    nodes.push({id: arrayJSnodos[i].id, label: arrayJSnodos[i].firstname , title: 'Nombres: '+arrayJSnodos[i].firstname+'<br>' + 'Numero de (Me gusta): '+arrayJSnodos[i].like+'<br>'+ 'Numero de (No me gusta): '+arrayJSnodos[i].not_like+'<br>'+'Confianza: '+arrayJSnodos[i].trust+'<br>'+'Confianza Externa: '+arrayJSnodos[i].trust_external, group:'UseraActual'});
                }else{
                    nodes.push({id: arrayJSnodos[i].id, label: arrayJSnodos[i].firstname, title: 'Nombres: '+arrayJSnodos[i].firstname+'<br>' + 'Numero de (Me gusta): '+arrayJSnodos[i].like+'<br>'+ 'Numero de (No me gusta): '+arrayJSnodos[i].not_like+'<br>'+'Confianza: '+arrayJSnodos[i].trust+'<br>'+'Confianza Externa: '+arrayJSnodos[i].trust_external});
                }
            }
			// Enlaces
            var EDGE_LENGTH = 150;
            var edges=[];
            var arrayJSenlaces=<?php echo json_encode($arrayPHPenlaces);?>;
            for(var i=0;i<arrayJSenlaces.length;i++){
                
                if(arrayJSenlaces[i].action === "-1"){
                    edges.push({from: arrayJSenlaces[i].user_emisor, to: arrayJSenlaces[i].user_receptor, label: '- '+arrayJSenlaces[i].label, color: 'green', length: EDGE_LENGTH});
                }else{
                    edges.push({from: arrayJSenlaces[i].user_emisor, to: arrayJSenlaces[i].user_receptor, label: '+ ' +arrayJSenlaces[i].label, color: 'blue', length: EDGE_LENGTH});
                }                
            }
			// Create a network
            var container = document.getElementById('mynetwork');
            var data = {
                nodes: nodes,
                edges: edges
            };
            //Configuracion de nodos, aristas, etc..
            var options = {
				smoothCurves: {dynamic:true, type: "continuous"},
				physics: {springLength:200}, 
                //Configuracion de nodos
                nodes: {
                    //image: 'img/student.png',
					image: 'img/nodoTodos.png',
                    shape: 'image',
					fontSize: 12,
					fontFace: "Tahoma"
                },
                //Configuracion de aristas
                edges: {
                    color: 'black',
                    style: 'arrow',
                    width: 0.5
                },
                //Configuracion de grupos
                groups: {
                    //Perfil de Usuario Principal
                    UseraActual: {
                        shape: 'image',
                        //image: 'img/user.png',
						image: 'img/nodoPrincipal.png',
                        fontSize: 15                        
                    }
                },
                stabilize: false,
                navigation: true
            };    
		var network = new vis.Network(container, data, options);
		//---------------------------------------------TAB Nodos-------------------------------------------------------------
		function grafoIteraccion(){
			setTimeout(function(){ 
				network = new vis.Network(container, data, options);
			}, 0);
		}
		
		//--------------------------------------------Cambiar por nodos o graficos-----------------------------------------
		function cambiarVisualizacion(parametro){
			parametro.checked =true;
			if(parametro.name=="nodos"){//Si es nodos
				var elem = document.getElementsByName("personas")[0];
				elem.checked =false;
				options.nodes={image: 'img/nodoTodos.png', shape: 'image', fontSize: 13}
				options.groups.UseraActual={shape: 'image',image: 'img/nodoPrincipal.png', fontSize: 15 }
				network = new vis.Network(container, data, options);
			}else{//Si es graficos
				var elem = document.getElementsByName("nodos")[0];
				elem.checked =false;
				options.nodes={image: 'img/student.png',shape: 'image', fontSize: 13}
				options.groups.UseraActual={image: 'img/user.png', shape: 'image', fontSize: 15 }
				network = new vis.Network(container, data, options);
			}
		}
		//------------------------------------------------------TAB Linea de tiempo---------------------------------------------------
		var arrayLineaTiempo=<?php echo json_encode($arrayLineaTiempo);?>;
		var fecha= 2000;
		var fechainicio= '2000-01-01';
		var fechafin= (fecha+arrayLineaTiempo.length)+'-01-01';
		var itemslinea=[];
		for(var i=0;i<arrayLineaTiempo.length;i++){
			var item={}
			item.x= fechainicio;
			item.y= Number(arrayLineaTiempo[i].promedio);
			item.label={
							content: arrayLineaTiempo[i].category+' ('+Number(arrayLineaTiempo[i].promedio).toFixed(2)+')'
						}
			itemslinea.push(item)
			fecha = fecha+1;
			fechainicio= fecha+'-01-01';
		}
		//Graficar
		var dataset = new vis.DataSet(itemslinea);
		var title = {text: "Nivel de Confianza: "+<?php echo json_encode(fullname($userlinea));?>};
		var optionslinea = {
			height: '500px',
			start: '2000-01-01',
			end: fechafin,
			showMajorLabels:false, //Ocultar label del eje x
			showMinorLabels:false,
			dataAxis: {title: {left: title}},//Titulo a la izquierda
			shaded: {orientation: 'bottom'},//Sombreado bottom, top
			
		};
		function lineaTiempo(){
			setTimeout(function(){ 
				document.getElementById("visualization").innerHTML="";
				var containerlinea = document.getElementById('visualization');
				var graph2d = new vis.Graph2d(containerlinea, dataset, optionslinea);
			}, 0);
		}
		
			
        </script>
    </body>
</html>
