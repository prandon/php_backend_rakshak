<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['siteid']) and
		isset($_POST['name'])
		){
		$db = new DbOperations();
		$result = $db->addCheckpoint($_POST['siteid'], $_POST['name']);

		if($result==1){
			$response['error']=false;
			$response['message']="Checkpoint added successfully";
		}
		elseif($result==2){
			$response['error']=true;
			$response['message']="Adding checkpoint failed";
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