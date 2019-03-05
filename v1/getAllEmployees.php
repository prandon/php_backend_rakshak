<?php 
 
 require_once '../includes/DbOperations.php';
 
 $response = array(); 

 if($_SERVER['REQUEST_METHOD']=='POST'){
	
		$db = new DbOperations();  
		$employees = $db->getAllEmployees();
		$response['error'] = "false"; 
 		$response['employees'] = array(); 
 
 		while($employee = $employees->fetch_assoc()){
 			$temp = array();
 			$temp['name']=$employee['name'];
 			array_push($response['employees'],$temp);
 	
	}
}
else{
	$response['error'] = "true";
	$response['message'] = "Invalid Request";
}
 
 echo json_encode($response);