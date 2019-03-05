<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['empid'])
		){
			$db = new DbOperations();
			$result = $db->employeeGetSchedules($_POST['empid']);

			$response['data']= $result;
		
	}
	else{
		$response['error']=true;
		$response['message']="required fields are empty";
	}
}
else{
	$response['error']=true;
	$response['message']="invalid requesrt";
}

echo json_encode($response);