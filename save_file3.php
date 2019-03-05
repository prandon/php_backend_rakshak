<?php

	 //importing dbDetails file 
 	require_once 'includes/Constants.php';
 	
	 $response = array(); 
 
	$filepath = "images/";
	$filepath = $filepath.basename($_FILES["upload_file"]['name']);
	$serverip = gethostbyname(gethostname());

  	$file_url = "http://".$serverip."/login/".$filepath;

$name = $_POST['name'];

	try{
 //saving the file 
move_uploaded_file($_FILES["upload_file"]["tmp_name"], $filepath);
 $sql = "INSERT INTO `rakshak`.`attachments` (`id`, `name`, `url`) VALUES (NULL, '$name', '$file_url');";


 //adding the path and name to database 
 if(mysqli_query($con,$sql)){
 
 //filling response array with values 
 $response['error'] = false; 
 $response['url'] = $file_url; 
 $response['name'] = $name;
 }
 //if some error occurred 
 }catch(Exception $e){
 $response['error']=true;
 $response['message']=$e->getMessage();
 } 
 //displaying the response 
 echo json_encode($response);
 

	

?>