<?php

require_once '../includes/DbOperations.php';

$response = array();

if($_SERVER['REQUEST_METHOD']=='POST'){
	if(
		isset($_POST['s_date']) and
		isset($_POST['e_date']) and
		isset($_POST['s_time']) and
		isset($_POST['c_time']) and
		isset($_POST['sitename']) and
		isset($_POST['checkpointname']) and
		isset($_POST['empname'])
		){
		$db = new DbOperations();

		//get siteid
		$siteid = $db->getSiteId($_POST['sitename']);
		//echo $siteid['siteid'];

		//get checkpoint id
		$checkpointid = $db->getCheckpointId($_POST['checkpointname']);
		//echo $checkpointid['checkpointid'];

		//get emp id
		$empid = $db->getEmpId($_POST['empname']);
		//echo $empid['id'];

		$begin = new DateTime($_POST['s_date']);
		$end = new DateTime($_POST['e_date']);
		$end->setTime(0,0,1);

		$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);

		$query = 'INSERT INTO `schedules` (`s_date`, `s_time`, `c_time`, `siteid`, `checkpointid`, `empid`, `status`) VALUES ';
		$query_parts = array();
		//('2017-12-05', '03:10:02', '06:00:00', '4', '4', '5', '0')
		$i = 1;
		foreach($daterange as $date){
    		//echo $date->format("Y-m-d H:i:s") . "<br>";
    		$query_parts[] = "('" . $date->format("Y-m-d H:i:s") . "', '" . $_POST['s_time'] . "','".$_POST['c_time']."','".$siteid['siteid']."','".$checkpointid['checkpointid']."','".$empid['id']."',0)";
		}
		echo $query .= implode(',', $query_parts);


		
		$result = $db->adminAddSchedulesBulk($query);
		//echo $result;
		//$result = $db->addNewSchedule($_POST['s_date'], $_POST['s_time'], $_POST['c_time'],$siteid['siteid'], $checkpointid['checkpointid'],$empid['id']);

		if($result==1){
			$response['error']=false;
			$response['message']="Schedule added successfully";
		}
		elseif($result==2){
			$response['error']=true;
			$response['message']="Adding schedule failed";
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