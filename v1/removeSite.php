<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['id'])
		){
		$db = new DbOperations();
		$result = $db->removeSite($_POST['id']);

		if($result==1){
			$response['error']=false;
			$response['message']="Removed Succesfully";
		}
		elseif($result==2){
			$response['error']=true;
			$response['message']="Remove failed";
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