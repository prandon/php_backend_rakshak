<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['name']) and
		isset($_POST['phone']) and
		isset($_POST['email']) and
		isset($_POST['address']) and
		isset($_POST['dob'])
		){
		$db = new DbOperations();
		$result = $db->addNewEmployee($_POST['name'], $_POST['email'], $_POST['dob'],$_POST['phone'], $_POST['address']);

		if($result==1){
			$response['error']=false;
			$response['message']="Employee registered successfully";
			$id = $db->getEmpIdByEmail($_POST['email']);
			$response['empid']=$id['id'];
		}
		elseif($result==2){
			$response['error']=true;
			$response['message']="Employee Registration failed";
		}
		elseif($result==0){
			$response['error']=true;
			$response['message']="Employee already exists";
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