<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	
	$db = new DbOperations();
	$result = $db->getAllCustomerSchedules($_POST['siteid']);

	$response['data']= $result;
	
	
}
else{
	$response['error']=true;
	$response['message']="invalid requesrt";
}

echo json_encode($response);