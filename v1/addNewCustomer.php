<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['name']) and
		isset($_POST['phone']) and
		isset($_POST['email']) and
		isset($_POST['address'])
		){
		$db = new DbOperations();
		$result = $db->addNewCustomer($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['address']);

		if($result==1){
			$response['error']=false;
			$response['message']="Customer registered successfully";
		}
		elseif($result==2){
			$response['error']=true;
			$response['message']="Customer Registration failed";
		}
		elseif($result==0){
			$response['error']=true;
			$response['message']="Customer already exists";
		}
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