<?php 
include('trust_model.class.php');
$soap = new SoapServer(null, array('uri' => 'urn:webservices', 'encoding'=>'ISO-8859-1'));  
// Asignamos la Clase
$soap->setClass('TrustModel');
// Atendemos las peticiones
$soap->handle();
?>