<?php

	$filepath = "images/";
	$filepath = $filepath.basename($_FILES["upload_file"]['name']);
	$serverip = gethostbyname(gethostname());

	if(move_uploaded_file($_FILES["upload_file"]["tmp_name"], $filepath)){
		echo "success";
		echo "http://".$serverip."/login/".$filepath;
	}
	else{
		echo "error";
	}

?>