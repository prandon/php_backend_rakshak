<?php
require_once '../includes/DbOperations.php';
$responce = array();

$db = new DbOperations();

$res=$db->getSiteInfo($_POST['siteid']);
if($res){
	$responce['error']=false;
	$responce['sitename'] = $res['sitename'];
}
else{
	$responce['error'] = true;
	$responce['message'] = "Site does not exist";
}


echo json_encode($responce); 