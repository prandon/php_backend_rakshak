<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['name'])
		){
		$db = new DbOperations();
		$result = $db->getUserInfo($_POST['name']);

		$response['data']= $result;
		//$response['email'] = $result['email'];
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
