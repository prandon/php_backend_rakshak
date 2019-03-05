<?php 
 
 require_once '../includes/DbOperations.php';
 
 $response = array(); 

 if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$db = new DbOperations();  
		$siteid = $db->getSiteId($_POST['sitename']);
		$id = $siteid['siteid'];
		$checkpoints = $db->getCheckpoints($id);
		$response['error'] = "false"; 
 		$response['checkpoints'] = array(); 
 
 		while($checkpoint = $checkpoints->fetch_assoc()){
 			$temp = array();
 			$temp['name']=$checkpoint['checkpointname'];
 			array_push($response['checkpoints'],$temp);
 	
	}
}
else{
	$response['error'] = "true";
	$response['message'] = "Invalid Request";
}
 
 echo json_encode($response);