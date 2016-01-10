<?php  
include_once('globals.php');
class TrustModel { 
	public function getTrustModel() {
		$lista = array();  
		$sql = 'SELECT u.id, 
						u.idnumber, 
						firstname, 
						lastname, 
						username, 
						email, AVG(trust_level) as trust 
				FROM mdl_trust t 
				INNER JOIN mdl_user u ON u.id = t.user_id
				WHERE t.state = "true"
				GROUP BY id, 
						idnumber, 
						firstname, 
						lastname, 
						username, 
						email';
		$db = new conecction();
		$result = $db->executeQuery($sql);
		
		while($row = mysql_fetch_array($result)) {
			$objeto = new stdClass();
			$objeto->idnumber = $row['idnumber'];
			$objeto->firstname = $row['firstname'];
			$objeto->lastname = $row['lastname'];
			$objeto->username = $row['username'];
			$objeto->email = $row['email'];
			$objeto->trust = $row['trust'];
			$lista[] = $objeto;  
		} 
		mysql_free_result($result);  
		return $lista; 
	}
}
?>