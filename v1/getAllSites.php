<?php 
 
 require_once '../includes/DbOperations.php';
 
 $response = array(); 

 if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$db = new DbOperations();  
		$sites = $db->getAllSites();
		$response['error'] = "false"; 
 		$response['sites'] = array(); 
 
 		while($site = $sites->fetch_assoc()){
 			$temp = array();
 			$temp['sitename']=$site['sitename'];
 			array_push($response['sites'],$temp);
 	
	}
}
else{
	$response['error'] = "true";
	$response['message'] = "Invalid Request";
}
 
 echo json_encode($response);