<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['s_name']) and
		isset($_POST['s_address']) and
		isset($_POST['custid'])
		){
		$db = new DbOperations();
		$result = $db->addNewSite($_POST['s_name'], $_POST['s_address'], $_POST['custid']);

		if($result==1){
			$response['error']=false;
			$response['message']="Site added successfully";
			$id = $db->getSiteId($_POST['s_name']);
			$response['siteid']=$id['siteid'];
		}
		elseif($result==2){
			$response['error']=true;
			$response['message']="Adding site failed";
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