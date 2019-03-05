<?php
require_once '../includes/DbOperations.php';
$responce = array();

$db = new DbOperations();

$res=$db->getCustomerInfo($_POST['id']);
if($res){
	$responce['error']=false;
	$responce['name'] = $res['name'];
}
else{
	$responce['error'] = true;
	$responce['message'] = "Customer does not exist";
}


echo json_encode($responce); 