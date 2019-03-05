<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(isset($_POST['username']) and isset($_POST['password'])){
		$db = new DbOperations();

		//login using the userLogin method that returns true or false
		if($db->userLogin($_POST['username'], $_POST['password'])){
			//get user data by username

			//get account type
			$user_type= $db->getAccountType($_POST['username']);
			$response['type'] = $user_type['type'];
			$response['error']=false;

			if($user_type['type']=="a"){
				$response['message']="Admin's Login Successful";
			} 
			else if($user_type['type']=="e"){
				$response['message']="Employee's Login Successful";
			}
			else{
				$response['message']="Customer's Login Successful";
			}
		
		}
		else{
			$response['error']=true;
			$response['message']="Invalid username or password";
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